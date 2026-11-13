<?php

// English translation of content/mods.php: texts only, same structure.
return [

    'ours' => [
        ['text' => 'A STALCRAFT-style dialogue window: on the left, the model of the NPC you are talking to; on the right, the conversation log and reply options in separate frames. You can answer with keys 1–9. Without the mod, conversations open in a regular chest-like window.'],
        ['text' => 'Shows site skins right after approval, even while the signature for vanilla clients isn\'t ready yet, and refreshes them in the game without rejoining.'],
    ],

    'groups' => [
        [
            'title' => 'More FPS',
            'lead' => 'Start here, especially on a weak PC or laptop. These mods don\'t change how the game looks — they just remove unnecessary work.',
            'mods' => [
                ['text' => 'A new rendering engine: several times more FPS and a smoother picture. The base for shaders and most graphics mods.'],
                ['text' => 'Cuts RAM usage. A lifesaver on PCs with 4–8 GB.'],
                ['text' => 'Speeds up drawing of the interface, text, signs, maps and particles.'],
                ['text' => 'Skips rendering mobs, players and chests hidden behind walls. Noticeably more FPS in villages and bases.'],
                ['text' => 'Skips invisible block faces and leaves — less work for your graphics card.'],
                ['text' => 'Faster game start-up and less memory while playing.'],
                ['text' => 'Lowers FPS when the game is minimised or inactive: your PC stays cool and a laptop lasts longer.'],
                ['text' => 'Extra graphics settings: clouds, fog, particles, animations. Turn off anything that eats FPS.'],
                ['text' => 'A handy Sodium settings menu with tabs and search.'],
            ],
        ],
        [
            'title' => 'Beauty',
            'lead' => 'Visual mods make the world and players look more alive. Each affects FPS differently — check the load label.',
            'mods' => [
                ['text' => 'Shaders: real shadows, light, water and sky. The shader pack is chosen separately — below are options for weak and powerful PCs.'],
                ['text' => 'Turns the outer skin layer 3D: hoods, jackets and hair look volumetric. Works with site skins too.'],
                ['text' => 'First-person animations become visible from outside: how players eat, hold maps and draw bows.'],
                ['text' => 'Connected textures: glass without frames between blocks and nicer bookshelves and sandstone with resource packs.'],
                ['text' => 'Lets resource packs change mob models. Required for Fresh Animations.'],
                ['text' => 'Different textures for mobs of the same kind, glowing eyes and random variants from resource packs.'],
                ['text' => 'A resource pack that brings mobs to life: they blink, breathe, look around and move more naturally.'],
                ['text' => 'Leaves slowly fall from the treetops — the forests of the three lands come alive.'],
                ['text' => 'Small particles: sparks from crystals, water splashes, bones from skeletons.'],
                ['text' => 'Rain and snow as particles, fog in bad weather and splashes on water. Especially beautiful in the Icy Crowns.'],
                ['text' => 'Capes smoothly flutter in the wind.'],
                ['text' => 'A torch in your hand lights up your surroundings. It\'s a visual effect for you only: it doesn\'t affect mob spawning.'],
            ],
        ],
        [
            'title' => 'Shaders for Iris',
            'lead' => 'Put the shader pack into the shaderpacks folder and turn it on in Video Settings → Shader Packs. Requires Iris and Sodium.',
            'mods' => [
                ['text' => 'The lightest good-looking shaders: shadows and light even on a weak graphics card.'],
                ['text' => 'Balanced shaders in the spirit of vanilla Minecraft: soft light, beautiful water and sky. For mid-range and powerful PCs.'],
                ['text' => 'A classic: bright sun, volumetric clouds and deep shadows. For powerful PCs.'],
            ],
        ],
        [
            'title' => 'Sound',
            'lead' => 'Atmosphere isn\'t only about the picture: these mods barely affect FPS.',
            'mods' => [
                ['text' => 'Realistic echo and muffling: sound reverberates in caves and gets duller behind walls.'],
                ['text' => 'Ambient sounds: wind in the mountains, birds in the forest, crickets at night, dripping in caves.'],
                ['text' => 'Footsteps sound different on different surfaces, and mobs have their own steps.'],
            ],
        ],
        [
            'title' => 'Comfort',
            'lead' => 'Mods allowed by the rules: they give no advantage over other players.',
            'mods' => [
                ['text' => 'A minimap and waypoints. The server turns off the entity radar and cave mode by itself — the rules require it.'],
                ['text' => 'Shows saturation and how much food restores right on the hunger bar.'],
                ['text' => 'A list of installed mods and their settings right in the game menu.'],
            ],
        ],
    ],

    'presets' => [
        ['title' => 'Weak PC', 'text' => 'FPS only, the look of the game doesn\'t change:'],
        ['title' => 'The golden mean', 'text' => 'Everything from “Weak PC”, plus a livelier picture and sound with almost no FPS loss:'],
        ['title' => 'Maximum atmosphere', 'text' => 'Everything from “The golden mean”, plus shaders, living mobs and immersive sound — for a powerful PC:'],
    ],

    'forbidden' => [
        'X-Ray in any form — mods and resource packs',
        'Cheat clients: kill aura, auto-clicker, flight, speed, freecam',
        'Player and mob radars, minimaps with radar or cave mode',
        'Bots and automation that play for you (Baritone and the like)',
    ],
];
