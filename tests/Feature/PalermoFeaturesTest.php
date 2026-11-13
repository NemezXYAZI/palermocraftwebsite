<?php

namespace Tests\Feature;

use App\Models\MinecraftLinkCode;
use App\Models\News;
use App\Models\Skin;
use App\Models\User;
use App\Services\TelegramLoginVerifier;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PalermoFeaturesTest extends TestCase
{
    use RefreshDatabase;

    private const TOKEN = 'test-token-123';

    /** Що відповідає api.mcsrvstat.us у тестах. */
    private array $serverResponse = ['online' => false];

    protected function setUp(): void
    {
        parent::setUp();
        config([
            'palermo.game_api_token' => self::TOKEN,
            'palermo.game_webhook_url' => null,
            'services.mineskin.key' => null,
        ]);
        Storage::fake('local');
        Http::fake(['api.mcsrvstat.us/*' => fn () => Http::response($this->serverResponse)]);
    }

    private function png(int $w = 64, int $h = 64): UploadedFile
    {
        $img = imagecreatetruecolor($w, $h);
        ob_start();
        imagepng($img);
        $bytes = ob_get_clean();

        return UploadedFile::fake()->createWithContent('skin.png', $bytes);
    }

    private function api(): array
    {
        return ['X-Palermo-Token' => self::TOKEN];
    }

    public function test_game_api_requires_token(): void
    {
        $this->getJson('/api/game/players/Steve')->assertStatus(401);
        $this->getJson('/api/game/players/Steve', ['X-Palermo-Token' => 'wrong'])->assertStatus(401);
        $this->getJson('/api/game/players/Steve', $this->api())->assertOk()->assertJson(['linked' => false]);
    }

    public function test_link_code_flow_binds_minecraft_name(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user)->post('/profile/minecraft/code')->assertSessionHas('link_code');
        $code = MinecraftLinkCode::where('user_id', $user->id)->value('code');

        $this->postJson('/api/game/link', ['code' => 'WRONG123', 'name' => 'Nemez'], $this->api())->assertStatus(422);
        $this->postJson('/api/game/link', ['code' => strtolower($code), 'name' => 'Nemez', 'uuid' => '0f9a8b7c-1234-4abc-9def-0123456789ab'], $this->api())
            ->assertOk()->assertJson(['ok' => true]);

        $this->assertSame('Nemez', $user->fresh()->minecraft_name);
        // Код одноразовий
        $this->postJson('/api/game/link', ['code' => $code, 'name' => 'Nemez'], $this->api())->assertStatus(422);
    }

    public function test_link_refuses_name_owned_by_another_account(): void
    {
        User::factory()->create(['minecraft_name' => 'Taken']);
        $user = User::factory()->create();
        $code = MinecraftLinkCode::create(['user_id' => $user->id, 'code' => 'ABCDEFGH', 'expires_at' => now()->addMinutes(5)]);

        $this->postJson('/api/game/link', ['code' => $code->code, 'name' => 'Taken'], $this->api())->assertStatus(409);
    }

    public function test_skin_upload_requires_approval_then_is_served(): void
    {
        config(['palermo.skins.require_approval' => true]);
        $user = User::factory()->create(['minecraft_name' => 'Nemez']);

        $this->actingAs($user)->post('/profile/skin', ['skin' => $this->png(64, 48), 'model' => 'classic'])
            ->assertSessionHasErrors('skin');

        $this->actingAs($user)->post('/profile/skin', ['skin' => $this->png(), 'model' => 'slim'])->assertSessionHasNoErrors();
        $skin = $user->fresh()->skin;
        $this->assertSame(Skin::STATUS_PENDING, $skin->status);
        $this->get('/skins/Nemez.png')->assertNotFound();
        $this->getJson('/api/game/players/Nemez', $this->api())->assertJson(['linked' => true, 'skin' => null]);

        $admin = User::factory()->create();
        $admin->forceFill(['role' => User::ROLE_ADMIN])->save();
        $this->actingAs($admin)->post("/admin/skins/{$skin->id}/approve")->assertSessionHas('success');

        $this->get('/skins/Nemez.png')->assertOk()->assertHeader('Content-Type', 'image/png');
        $this->getJson('/api/game/players/Nemez', $this->api())
            ->assertJsonPath('skin.model', 'slim')
            ->assertJsonPath('skin.value', null); // без MINESKIN_API_KEY підпису немає
    }

    public function test_mineskin_signature_is_stored(): void
    {
        config(['services.mineskin.key' => 'k', 'palermo.skins.require_approval' => false]);
        Http::fake([
            'api.mineskin.org/*' => Http::response(['success' => true, 'skin' => ['texture' => ['data' => ['value' => 'VAL', 'signature' => 'SIG']]]]),
            'api.mcsrvstat.us/*' => Http::response(['online' => false]),
        ]);
        $user = User::factory()->create(['minecraft_name' => 'Signed']);
        $this->actingAs($user)->post('/profile/skin', ['skin' => $this->png(), 'model' => 'classic']);

        $this->getJson('/api/game/players/Signed', $this->api())
            ->assertJsonPath('skin.value', 'VAL')
            ->assertJsonPath('skin.signature', 'SIG');
    }

    public function test_admin_area_is_staff_only(): void
    {
        $player = User::factory()->create();
        $this->actingAs($player)->get('/admin')->assertForbidden();

        $mod = User::factory()->create();
        $mod->forceFill(['role' => User::ROLE_MODERATOR])->save();
        $this->actingAs($mod)->get('/admin')->assertOk();
        $this->actingAs($mod)->get('/admin/news')->assertForbidden();
        $this->actingAs($mod)->post("/admin/users/{$player->id}/role", ['role' => 'admin'])->assertForbidden();
    }

    public function test_ban_blocks_site_and_is_reported_to_game(): void
    {
        $admin = User::factory()->create();
        $admin->forceFill(['role' => User::ROLE_ADMIN])->save();
        $player = User::factory()->create(['minecraft_name' => 'Griefer']);

        $this->actingAs($admin)->post("/admin/users/{$player->id}/ban", ['reason' => 'Гріфінг'])->assertSessionHas('success');
        $this->getJson('/api/game/players/Griefer', $this->api())->assertJson(['banned' => true, 'ban_reason' => 'Гріфінг']);

        $this->actingAs($player->fresh())->get('/profile')->assertRedirect('/login');
    }

    public function test_fake_store_is_gone_and_old_links_redirect(): void
    {
        // Магазин був заглушкою: «видавав» товари, яких гра не отримувала
        $player = User::factory()->create();
        $this->actingAs($player)->post('/profile/top-up', ['amount' => 1000])->assertNotFound();
        $this->actingAs($player)->post('/store/buy-now', ['product_id' => 'vip_30'])->assertNotFound();
        $this->assertSame(0, (int) $player->fresh()->balance);

        $this->get('/store')->assertRedirect('/support')->assertStatus(301);
        $this->get('/about')->assertRedirect('/world')->assertStatus(301);
        $this->get('/support')->assertOk()->assertSee('Без pay-to-win');
    }

    public function test_telegram_login_signature_check(): void
    {
        config(['services.telegram.bot_token' => '123:ABC']);
        $data = ['id' => '42', 'first_name' => 'Sem', 'username' => 'sem', 'auth_date' => (string) time()];
        ksort($data);
        $check = collect($data)->map(fn ($v, $k) => "$k=$v")->implode("\n");
        $data['hash'] = hash_hmac('sha256', $check, hash('sha256', '123:ABC', true));

        $verifier = new TelegramLoginVerifier;
        $this->assertTrue($verifier->verify($data));
        $this->assertFalse($verifier->verify(array_merge($data, ['id' => '43'])));

        $user = User::factory()->create();
        $this->actingAs($user)->get('/auth/telegram/callback?'.http_build_query($data))->assertRedirect('/profile');
        $this->assertSame(42, $user->fresh()->telegram_id);
    }

    public function test_public_pages_render(): void
    {
        News::create(['title' => 'Старт сезону', 'slug' => 'start', 'body' => "Перший абзац\n\nДругий", 'published_at' => now()]);
        $this->get('/')->assertOk()
            ->assertSee('Ехо Безодні')
            ->assertSee(config('palermo.server_address'))
            ->assertSee('Старт сезону');
        $this->get('/world')->assertOk()->assertSee('Пісок Забуття')->assertSee('Око Забуття');
        $this->get('/guide')->assertOk()->assertSee('/link КОД', false)->assertSee('Метрополія');
        $this->get('/rules')->assertOk()->assertSee('Чесна гра')->assertSee('2.1');
        $this->get('/news')->assertOk()->assertSee('Старт сезону');
        $this->get('/news/start')->assertOk()->assertSee('Другий');
        $this->get('/login')->assertOk()->assertSee('Вхід');
        $this->get('/register')->assertOk()->assertSee('Створити акаунт');

        $user = User::factory()->create();
        $this->actingAs($user)->get('/profile')->assertOk()->assertSee('Акаунт у грі');
        $this->actingAs($user)->get('/profile/skin')->assertOk();
    }

    public function test_admin_pages_render(): void
    {
        $admin = User::factory()->create(['minecraft_name' => 'Boss']);
        $admin->forceFill(['role' => User::ROLE_ADMIN])->save();
        $player = User::factory()->create(['minecraft_name' => 'Player1']);
        $this->actingAs($player)->post('/profile/skin', ['skin' => $this->png(), 'model' => 'classic']);
        $news = News::create(['title' => 'Чернетка', 'slug' => 'draft', 'body' => 'Текст']);

        $this->actingAs($admin);
        $this->get('/admin')->assertOk();
        $this->get('/admin/users?q=Player')->assertOk()->assertSee('Player1');
        $this->get("/admin/users/{$player->id}")->assertOk();
        $this->get('/admin/skins')->assertOk()->assertSee('Player1');
        $this->get('/admin/news')->assertOk()->assertSee('Чернетка');
        $this->get('/admin/news/create')->assertOk();
        $this->get("/admin/news/{$news->id}/edit")->assertOk();
        $this->post('/admin/news', ['title' => 'Відкриття сервера', 'body' => 'Скоро!', 'publish' => 1])->assertRedirect('/admin/news');
        $this->get('/news')->assertSee('Відкриття сервера');
    }

    public function test_home_shows_who_is_online(): void
    {
        $this->serverResponse = [
            'online' => true,
            'version' => 'Purpur 1.21.4',
            'players' => ['online' => 2, 'max' => 50, 'list' => [['name' => 'Nemez'], ['name' => 'Semen']]],
        ];
        $owner = User::factory()->create(['minecraft_name' => 'Nemez']);
        Skin::create(['user_id' => $owner->id, 'model' => 'classic', 'path' => 'skins/x.png', 'hash' => 'abc', 'status' => Skin::STATUS_APPROVED]);

        $this->get('/')->assertOk()
            ->assertSee('Онлайн: 2 з 50')
            ->assertSee('2 Мандрівники у Безодні')
            ->assertSee('Semen')
            ->assertSee('/skins/Nemez.png', false)
            ->assertDontSee('/skins/Semen.png', false);
    }

    public function test_sitemap_lists_pages_and_published_news(): void
    {
        News::create(['title' => 'Опубліковано', 'slug' => 'public-one', 'body' => 'Текст', 'published_at' => now()]);
        News::create(['title' => 'Чернетка', 'slug' => 'secret-draft', 'body' => 'Текст']);

        $this->get('/sitemap.xml')->assertOk()
            ->assertHeader('Content-Type', 'application/xml; charset=UTF-8')
            ->assertSee('/guide', false)
            ->assertSee('/news/public-one', false)
            ->assertDontSee('secret-draft', false);
    }

    public function test_errors_are_themed_and_validation_speaks_ukrainian(): void
    {
        $this->get('/no-such-page')->assertNotFound()->assertSee('поглинула Безодня');

        $this->from('/register')->post('/register', [])
            ->assertRedirect('/register')
            ->assertSessionHasErrors(['name' => 'Заповніть поле «нікнейм».']);
    }
}
