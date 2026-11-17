<?php

/*
| English translation of content/season.php: same structure, texts only.
| Anything missing here is shown in Ukrainian.
*/

return [

    'title' => 'Echoes of the Void',
    'tagline' => 'A story-driven SMP in a dark fantasy world',
    'pitch' => 'Three Lords turned to the power of the Void — and their kingdoms fell silent. Come to the Palermo archipelago, rebuild settlements with friends and uncover the truth someone tried so hard to hide.',

    'facts' => [
        ['title' => 'Vanilla client', 'text' => 'No required mods — runs fine even on a weak PC.'],
        ['title' => 'Password-free login', 'text' => 'A Telegram or Discord bot lets in only you.'],
        ['title' => 'Any launcher', 'text' => 'Licensed or cracked — everyone can join.'],
        ['title' => 'Several languages', 'text' => 'Українська, English, русский — pick yours with /language.'],
    ],

    'domains' => [
        'sun' => [
            'region' => 'Desert',
            'name' => 'The Ancient Pyramid',
            'lord' => 'Azrael',
            'lord_title' => 'Lord of the Desert',
            'text' => 'A former priest obsessed with an eternal sun, light and the idea of immortality. His Heart commanded the earth, the sands and the forces beneath them.',
            'style' => 'Smooth sandstone, acacia and bamboo — the architecture of the eastern sands.',
        ],
        'life' => [
            'region' => 'Summer',
            'name' => 'The Underground Fortifications',
            'lord' => 'Elior',
            'lord_title' => 'Lord of Summer',
            'text' => 'A strategist and scholar who tried to master life through nature and ancient mechanisms. His Heart commanded life, plants and warmth.',
            'style' => 'Oak and birch, stone bricks, flowers — cosy plains classics.',
        ],
        'frost' => [
            'region' => 'Winter',
            'name' => 'The Winter Castle',
            'lord' => 'Velaris',
            'lord_title' => 'Lord of Winter',
            'text' => 'A cold, reserved ruler who wished to stop time and preserve his kingdom forever. His Heart commanded time, cold and ice.',
            'style' => 'Spruce, snow, ice and slate, warm lanterns of mountain villages.',
        ],
    ],

    'chapters' => [
        [
            'title' => 'Three Lords',
            'text' => [
                'Once, three mighty Lords ruled the world. Velaris, Lord of Winter, was a cold, reserved ruler who wished to stop time and preserve his kingdom forever. Azrael, Lord of the Desert, was a former priest obsessed with an eternal sun, light and the idea of immortality. Elior, Lord of Summer, was a strategist and scholar who tried to master life through nature and ancient mechanisms.',
                'Each of them sought absolute power over their element. For that, they turned to a forbidden power — the Void.',
            ],
        ],
        [
            'title' => 'Hearts of the Void',
            'text' => [
                'From the energy of the Void, the Lords created three mysterious artifacts — the Hearts, which gave them incredible strength and let them control nature.',
                'But along with that power, they opened a path for the Void into the ordinary world. What was meant to be the dawn of a new era slowly became a threat to every living thing.',
            ],
        ],
        [
            'title' => 'Forgotten Legends',
            'text' => [
                'Many years passed. The settlements emptied, the castles fell to ruin, and the stories of the Lords became forgotten legends.',
                'All they say is that someone once stood against them — and paid far too high a price. Who it was and what became of them, the legends do not tell.',
            ],
        ],
        [
            'title' => 'A New Traveller',
            'text' => [
                'Now a new Traveller arrives on the Palermo archipelago. Ahead lies a journey through abandoned lands, ancient dungeons and forgotten fortresses. They will have to restore the settlements, face the consequences of long-past events and gather the fragments of a story someone tried so hard to hide.',
                'But can they learn the truth without repeating the mistakes of those who came before?',
            ],
        ],
    ],

    'features' => [
        ['title' => 'Settlements', 'text' => 'Build a town with friends: Town Hall, Warehouse, Housing, Sawmill, Farm, Mine, Barracks. Villagers keep working even while you\'re offline.'],
        ['title' => 'Dungeons', 'text' => 'Everyone sees their own loot in chests — nobody strips the ruins before you. Boss dungeons are played as a group in separate worlds.'],
        ['title' => 'Lords', 'text' => 'Azrael, Elior and Velaris — custom bosses with unique battle phases. Better to face them as a squad.'],
        ['title' => 'Armour trims', 'text' => 'Every trim material and smithing pattern gives its own bonus — from night vision to a shield in a pinch. A full set triggers resonance.'],
        ['title' => 'Wanderer\'s Codex', 'text' => 'A living book: the hero\'s diary, discovered places, forgotten recipes and a bestiary. New entries appear on their own as soon as you find something.'],
        ['title' => 'Teams and squads', 'text' => 'Gather friends into a team (/team): shared land, a settlement and a compass to your allies. For boss dungeons — a squad (/party).'],
        ['title' => 'Settlement spirits', 'text' => 'Spirits of former inhabitants tell you what happened here and ask you to rebuild their homes, while colony villagers work alongside you.'],
        ['title' => 'Your look', 'text' => 'Every player sees your site skin — licensed or cracked, with no mods at all.'],
    ],

    'ranks' => [
        ['title' => 'Camp', 'unlocks' => 'Town Hall, Warehouse, Housing'],
        ['title' => 'Hamlet', 'unlocks' => 'Sawmill, Farm'],
        ['title' => 'Village', 'unlocks' => 'Mine'],
        ['title' => 'Town', 'unlocks' => 'Barracks, settlement wars'],
        ['title' => 'Fortress', 'unlocks' => 'Elite guard'],
        ['title' => 'Metropolis', 'unlocks' => 'Maximum output'],
    ],

    'commands' => [
        ['text' => 'settlement menu: land, buildings, rank'],
        ['text' => 'villagers, Warehouse and construction'],
        ['text' => 'team: invite friends, compass'],
        ['text' => 'group for boss dungeons'],
        ['text' => 'Wanderer\'s Codex'],
        ['cmd' => '/link CODE', 'text' => 'link Telegram or Discord for logging in'],
        ['text' => 'interface language'],
        ['cmd' => '/sitelink CODE', 'text' => 'link your nickname to your site account'],
    ],

    'faq' => [
        [
            'q' => 'Do I need a licensed copy of Minecraft?',
            'a' => 'No. Join from any launcher — official, TLauncher or another. Your account is protected by a Telegram or Discord bot rather than a password, so nobody can steal your nickname.',
        ],
        [
            'q' => 'Which mods do I need to install?',
            'a' => 'None: the server works with a clean :version client. But there are optional mods for FPS, looks and comfort — the list and descriptions are on the “Mods” page.',
        ],
        [
            'q' => 'The bot didn\'t send the “Allow” button. What should I do?',
            'a' => 'First you need to link your nickname: on your first login the server shows a code — send it to the bot with /link CODE. If you\'re linked and the button still doesn\'t arrive, contact the staff.',
        ],
        [
            'q' => 'Will others see my skin?',
            'a' => 'Yes, everyone — licensed or cracked, without mods. Upload a PNG in your profile; a moderator will check it and the skin will appear in the game.',
        ],
        [
            'q' => 'Can I play with friends in one settlement?',
            'a' => 'Yes. Create a team (/team), invite friends and found a settlement with /lands. The leader and officers can build and upgrade buildings.',
        ],
        [
            'q' => 'Can I buy an advantage for money?',
            'a' => 'No. Everything that affects gameplay is earned only in the game. More on the “Support” page.',
        ],
    ],
];
