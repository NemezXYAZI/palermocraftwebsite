<?php

// English translation of content/rules.php: texts only, same structure.
return [

    'intro' => 'We are building a world that is interesting to live in for a long time. The rules apply on the server, on the website and in our Discord and Telegram communities. Not knowing the rules does not excuse breaking them.',

    'sections' => [
        [
            'title' => 'Communication',
            'rules' => [
                ['text' => 'Respect other players and the staff: no insults, harassment, threats or discrimination.'],
                ['text' => 'No spam, flooding, caps lock or advertising of other projects.'],
                ['text' => 'Don\'t share other people\'s personal data — not even “as a joke”.'],
                ['text' => 'Politics, shock content and NSFW are forbidden in the public chat.', 'note' => 'The same goes for settlement names, signs, books and buildings.'],
            ],
        ],
        [
            'title' => 'Fair play',
            'rules' => [
                ['text' => 'Cheats and modifications that give an advantage are forbidden: X-Ray (including resource packs), kill aura, flight, auto-clickers, player or cave radars.', 'note' => 'Allowed: optimisation (Sodium, Iris, etc.), cosmetic mods, minimaps without entity and cave radar. A checked list is on the “Mods” page.'],
                ['text' => 'Using bugs and dupes is forbidden. Found a bug? Tell the staff.'],
                ['text' => 'One player — one account. Alt accounts for farming or dodging penalties are forbidden.'],
                ['text' => 'Contraptions that overload the server (lag machines, endless entity farms) are forbidden.', 'note' => 'If a farm lowers TPS, the staff may limit it.'],
            ],
        ],
        [
            'title' => 'The world and others\' property',
            'rules' => [
                ['text' => 'Griefing, stealing and destroying other people\'s buildings are forbidden — even outside claims.'],
                ['text' => 'Don\'t build right next to someone else\'s settlement without its residents\' consent.'],
                ['text' => 'Story locations — villages, ruins, NPCs — must not be destroyed or seized.'],
                ['text' => 'PvP only with both sides\' consent or within a war between settlements.'],
            ],
        ],
        [
            'title' => 'Account',
            'rules' => [
                ['text' => 'You are responsible for your account and the linked Telegram and Discord. Don\'t give access to others.'],
                ['text' => 'Nicknames, skins and names without insults, NSFW or politics.', 'note' => 'Moderators check every skin before it can be seen in the game.'],
                ['text' => 'Selling and buying in-game valuables for real money is forbidden.'],
            ],
        ],
        [
            'title' => 'Staff',
            'rules' => [
                ['text' => 'Don\'t pretend to be staff or a moderator.'],
                ['text' => 'Disagree with a decision? Appeal it calmly in private messages to the staff.'],
                ['text' => 'The rules may be updated — we announce changes in the news.'],
            ],
        ],
    ],

    'penalties' => [
        ['title' => 'Warning', 'text' => 'for a minor first offence'],
        ['title' => 'Mute', 'text' => 'from an hour to a day — for chat'],
        ['title' => 'Temporary ban', 'text' => 'from 1 to 30 days — for repeat offences'],
        ['title' => 'Permanent ban', 'text' => 'for cheats, griefing and selling accounts'],
    ],
];
