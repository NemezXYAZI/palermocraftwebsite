<?php

/*
| English translation of content/season.php: same structure, texts only.
| Anything missing here is shown in Ukrainian.
*/

return [

    'title' => 'Echoes of the Void',
    'tagline' => 'A story-driven SMP in a dark fantasy world',
    'pitch' => 'The Lords who stole the power of the Void have become shadows of their own ruins. Found settlements with friends, explore forgotten lands and find out what happened to the First Wanderer.',

    'facts' => [
        ['title' => 'Vanilla client', 'text' => 'No required mods — runs fine even on a weak PC.'],
        ['title' => 'Password-free login', 'text' => 'A Telegram or Discord bot lets in only you.'],
        ['title' => 'Any launcher', 'text' => 'Licensed or cracked — everyone can join.'],
        ['title' => 'Several languages', 'text' => 'Українська, English, русский — pick yours with /language.'],
    ],

    'domains' => [
        'sun' => [
            'region' => 'Desert',
            'name' => 'Sands of Oblivion',
            'lord' => 'Azrael',
            'lord_title' => 'Priest of the Sun',
            'text' => 'A former high priest driven mad by worship of the undying sun. He sought light without shadow — and immortality for himself.',
            'style' => 'Smooth sandstone, acacia and bamboo — the architecture of the eastern sands.',
        ],
        'life' => [
            'region' => 'Summer',
            'name' => 'Fort of Green Silence',
            'lord' => 'Elior',
            'lord_title' => 'Engineer of the Living',
            'text' => 'A brilliant strategist and explorer. He fused living things with ancient mechanisms and redstone matrices to conquer nature itself.',
            'style' => 'Oak and birch, stone bricks, flowers — cosy plains classics.',
        ],
        'frost' => [
            'region' => 'Winter',
            'name' => 'Icy Crowns',
            'lord' => 'Velaris',
            'lord_title' => 'Lord of Cold and Time',
            'text' => 'The reserved ruler of the northern mountains. Horrified by decay, he wanted to stop time and preserve his kingdom in icy eternity.',
            'style' => 'Spruce, snow, ice and slate, warm lanterns of mountain villages.',
        ],
    ],

    'chapters' => [
        [
            'title' => 'Three Lords',
            'text' => [
                'Once the world flourished under the watch of three rulers. Each embodied one of mortals\' strivings for perfection: Velaris — eternity, Azrael — light without shadow, Elior — power over nature itself.',
            ],
        ],
        [
            'title' => 'Hearts of the Void',
            'text' => [
                'To realise their ambitions, the Lords crossed a forbidden line and turned to the primordial energy of the Void. From it they forged three artefacts — the Hearts, which gave them godlike power over their lands.',
                'But the Hearts became breaches in the fabric of existence. The Void began to seep into the world and twist everything alive.',
            ],
        ],
        [
            'title' => 'The Deed of the First Wanderer',
            'text' => [
                'Kael learned where the rulers\' experiments were leading. He challenged them, tore out the Hearts and stopped the collapse — but the price was terrible.',
                'The Lords did not die: without their Hearts they turned into twisted, half-living sentinels of their own ruins. And the breach of the Void could not be closed from outside — so Kael went into the heart of the rift and chained himself there, becoming the Living Barrier.',
            ],
        ],
        [
            'title' => 'A New Wanderer',
            'text' => [
                'Centuries passed. The villages emptied, castles became tombs of knowledge, and the names of Velaris, Azrael, Elior and Kael faded into myth.',
                'Now you arrive in this fallen world — and from here on, the Wanderers write the story.',
            ],
        ],
    ],

    'features' => [
        ['title' => 'Settlements', 'text' => 'Build a town with friends: Town Hall, Warehouse, Housing, Sawmill, Farm, Mine, Barracks. Villagers keep working even while you\'re offline.'],
        ['title' => 'Dungeons', 'text' => 'Everyone sees their own loot in chests — nobody strips the ruins before you. Boss dungeons are played as a group in separate worlds.'],
        ['title' => 'Lords', 'text' => 'Azrael, Elior and Velaris — custom bosses with unique battle phases. Better to face them as a squad.'],
        ['title' => 'Armour trims', 'text' => 'Every trim material and smithing pattern gives its own bonus — from night vision to a shield in a pinch. A full set triggers resonance.'],
        ['title' => 'Wanderer\'s Codex', 'text' => 'A living book: the hero\'s thoughts, discovered places, forgotten recipes and a bestiary. New entries appear on their own as soon as you find something.'],
        ['title' => 'Teams and squads', 'text' => 'Gather friends into a team (/team): shared land, a settlement and a compass to your allies. For boss dungeons — a squad (/party).'],
        ['title' => 'Living inhabitants', 'text' => 'Village elders and traders talk to you and hand out tasks, while colony villagers work alongside you.'],
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
