<?php

namespace Tests\Feature;

use App\Support\Locale;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/** Три мови сайту: вибір мови, переклади, контент, SEO-посилання. */
class LocalizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_ukrainian_is_the_default_language(): void
    {
        $this->get('/')->assertOk()
            ->assertSee('<html lang="uk">', false)
            ->assertSee('Ехо Безодні')
            ->assertHeader('Content-Language', 'uk')
            ->assertCookieMissing(Locale::COOKIE);

        // Мова браузера, якої сайт не знає, → українська
        $this->withHeader('Accept-Language', 'de-DE,de;q=0.9')->get('/guide')
            ->assertSee('<html lang="uk">', false);
    }

    public function test_browser_language_is_used_when_nothing_was_chosen(): void
    {
        $this->withHeader('Accept-Language', 'ru-RU,ru;q=0.9,en;q=0.8')->get('/world')->assertOk()
            ->assertSee('<html lang="ru">', false)
            ->assertSee('Эхо Бездны')
            ->assertHeader('Content-Language', 'ru');

        $this->withHeader('Accept-Language', 'pl-PL,pl;q=0.9,en;q=0.8')->get('/world')
            ->assertSee('<html lang="en">', false)
            ->assertSee('Echoes of the Void');
    }

    public function test_choice_from_switcher_is_remembered_in_cookie(): void
    {
        $this->get('/guide?lang=en')->assertOk()
            ->assertSee('<html lang="en">', false)
            ->assertSee('Useful mods for FPS and looks')
            ->assertPlainCookie(Locale::COOKIE, 'en');

        // Далі мову бере з cookie, навіть якщо браузер український
        $this->withUnencryptedCookie(Locale::COOKIE, 'ru')->get('/mods')->assertOk()
            ->assertSee('<html lang="ru">', false)
            ->assertSee('Как установить');

        // Явний ?lang важливіший за cookie; «ua» теж розуміємо
        $this->withUnencryptedCookie(Locale::COOKIE, 'ru')->get('/?lang=ua')
            ->assertSee('<html lang="uk">', false)
            ->assertPlainCookie(Locale::COOKIE, 'uk');
    }

    public function test_switcher_and_seo_links_point_to_every_language(): void
    {
        $this->get('/rules?lang=en')->assertOk()
            ->assertSee('<link rel="canonical" href="http://localhost/rules?lang=en">', false)
            ->assertSee('<link rel="alternate" hreflang="uk" href="http://localhost/rules">', false)
            ->assertSee('<link rel="alternate" hreflang="ru" href="http://localhost/rules?lang=ru">', false)
            ->assertSee('<link rel="alternate" hreflang="x-default" href="http://localhost/rules">', false)
            ->assertSee('<meta property="og:locale" content="en_US">', false)
            ->assertSee('hreflang="ru" lang="ru"', false);

        $this->get('/guide')->assertSee('<link rel="canonical" href="http://localhost/guide">', false);
    }

    public function test_errors_and_validation_follow_the_language(): void
    {
        $this->withHeader('Accept-Language', 'en')->get('/no-such-page')->assertNotFound()
            ->assertSee('The Void swallowed this page');

        $this->withHeader('Accept-Language', 'en')->from('/register')->post('/register', [])
            ->assertSessionHasErrors(['name' => 'Please fill in the username field.']);

        $this->withHeader('Accept-Language', 'ru')->from('/register')->post('/register', [])
            ->assertSessionHasErrors(['name' => 'Заполните поле «никнейм».']);
    }

    public function test_every_site_string_is_translated(): void
    {
        $keys = [];
        $files = array_merge(
            glob(resource_path('views/{,*/,*/*/,*/*/*/}*.blade.php'), GLOB_BRACE),
            glob(app_path('{,*/,*/*/,*/*/*/}*.php'), GLOB_BRACE),
        );
        foreach ($files as $file) {
            if (str_contains($file, '/views/admin/')) {
                continue; // адмінка лише українською
            }
            preg_match_all("/(?:__|trans_choice)\\(\\s*'((?:[^'\\\\]|\\\\.)*)'/u", file_get_contents($file), $found);
            foreach ($found[1] as $key) {
                // як PHP читає рядок в одинарних лапках: лише \\ і \' — екрановані
                $keys[strtr($key, ['\\\\' => '\\', "\\'" => "'"])] = basename($file);
            }
        }
        $this->assertGreaterThan(300, count($keys));

        foreach (['en', 'ru'] as $locale) {
            $translations = json_decode(file_get_contents(lang_path($locale.'.json')), true, flags: JSON_THROW_ON_ERROR);
            foreach ($keys as $key => $file) {
                $this->assertArrayHasKey($key, $translations, "Немає перекладу ($locale) для «{$key}» з {$file}");
                preg_match_all('/:[a-z]+/', $key, $expected);
                preg_match_all('/:[a-z]+/', $translations[$key], $actual);
                $this->assertEqualsCanonicalizing(array_unique($expected[0]), array_unique($actual[0]), "Плейсхолдери ($locale) у «{$key}»");
            }
        }
    }

    public function test_content_translations_match_the_original_structure(): void
    {
        foreach (glob(resource_path('content/*.php')) as $file) {
            $base = require $file;
            foreach (['en', 'ru'] as $locale) {
                $path = resource_path('content/'.$locale.'/'.basename($file));
                $this->assertFileExists($path);
                $this->assertSameShape($base, require $path, $locale.'/'.basename($file));
            }
        }
    }

    /** Переклад може містити лише ключі оригіналу, а списки — бути тієї ж довжини. */
    private function assertSameShape(array $base, array $overlay, string $where): void
    {
        foreach ($overlay as $key => $value) {
            $this->assertArrayHasKey($key, $base, "Зайвий ключ «{$key}» у {$where}");
            if (is_array($value)) {
                $this->assertIsArray($base[$key], "«{$key}» у {$where}");
                if (array_is_list($base[$key])) {
                    $this->assertCount(count($base[$key]), $value, "Довжина списку «{$key}» у {$where}");
                }
                $this->assertSameShape($base[$key], $value, $where.' › '.$key);
            }
        }
    }
}
