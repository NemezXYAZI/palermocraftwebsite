<?php

namespace Tests\Feature;

use App\Support\Content;
use Tests\TestCase;

class ModsPageTest extends TestCase
{
    public function test_mods_page_lists_mods_with_modrinth_links_for_server_version(): void
    {
        $version = config('palermo.game_version');

        $this->get('/mods')->assertOk()
            ->assertSee('Sodium')
            ->assertSee('Iris Shaders')
            ->assertSee('Xaero')
            ->assertSee('https://modrinth.com/mod/sodium/versions?g='.$version.'&amp;l=fabric', false)
            ->assertSee('https://modrinth.com/shader/complementary-reimagined/versions?g='.$version, false)
            ->assertSee('https://modrinth.com/resourcepack/fresh-animations/versions?g='.$version, false)
            ->assertSee('id="forbidden"', false);

        $this->get('/mods?lang=en')->assertOk()->assertSee('More FPS')->assertSee('Weak PC');
        $this->get('/mods?lang=ru')->assertOk()->assertSee('Больше FPS')->assertSee('Слабый ПК');
    }

    public function test_our_mods_are_downloadable_from_the_site(): void
    {
        $response = $this->get('/mods')->assertOk();

        foreach (Content::get('mods')['ours'] as $mod) {
            $this->assertFileExists(public_path('downloads/mods/'.$mod['file']), $mod['name']);
            $response->assertSee('/downloads/mods/'.$mod['file'], false);
        }
    }

    public function test_every_mod_has_a_known_load_level_and_unique_slug(): void
    {
        $slugs = [];
        foreach (Content::get('mods')['groups'] as $group) {
            foreach ($group['mods'] as $mod) {
                $this->assertContains($mod['load'], ['light', 'medium', 'heavy'], $mod['name']);
                $this->assertContains($mod['type'] ?? 'mod', ['mod', 'shader', 'resourcepack'], $mod['name']);
                $this->assertNotContains($mod['slug'], $slugs, 'Повтор: '.$mod['slug']);
                $slugs[] = $mod['slug'];
            }
        }
    }
}
