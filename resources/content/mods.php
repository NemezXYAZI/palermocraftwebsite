<?php

/*
|--------------------------------------------------------------------------
| Сторінка «Моди»: необовʼязкові моди для Minecraft Java 1.21.4 на Fabric.
|--------------------------------------------------------------------------
| Сервер працює на чистому клієнті — усе тут лише бонус для FPS, краси й звуку.
| 'slug' — адреса проєкту на modrinth.com; 'type' — mod (за замовчуванням), shader, resourcepack.
| 'load' — навантаження на ПК: light, medium, heavy. 'requires' — що ще встановити.
| Наші моди лежать у public/downloads/mods/ ('file').
| Переклад — у content/en/mods.php і content/ru/mods.php (лише тексти, та сама структура).
*/

return [

    'ours' => [
        [
            'name' => 'Palermo Dialogues',
            'file' => 'palermo-dialogues-client-1.1.0.jar',
            'version' => '1.1.0',
            'icon' => 'message-circle',
            'text' => 'Вікно розмов у стилі STALCRAFT: ліворуч модель НПС, з яким говориш, праворуч журнал розмови й варіанти відповіді в окремих рамках. Відповідати можна клавішами 1–9. Без мода розмови відкриваються у звичайному вікні-скрині.',
            'requires' => ['Fabric API'],
        ],
        [
            'name' => 'Palermo Skin Sync',
            'file' => 'palermo-skin-client-1.1.0.jar',
            'version' => '1.1.0',
            'icon' => 'shirt',
            'text' => 'Показує скіни із сайту одразу після схвалення, навіть поки підпис для ванільних клієнтів ще не готовий, і оновлює їх у грі без перезаходу.',
            'requires' => ['Fabric API'],
        ],
    ],

    'groups' => [
        [
            'id' => 'performance',
            'icon' => 'zap',
            'accent' => 'life',
            'title' => 'Більше FPS',
            'lead' => 'Почни з цього, особливо на слабкому ПК чи ноутбуці. Ці моди нічого не змінюють у вигляді гри — лише прибирають зайву роботу.',
            'mods' => [
                ['name' => 'Sodium', 'slug' => 'sodium', 'load' => 'light', 'essential' => true,
                    'text' => 'Новий рушій рендеру: у рази більше FPS і плавніша картинка. Основа для шейдерів і більшості графічних модів.'],
                ['name' => 'FerriteCore', 'slug' => 'ferrite-core', 'load' => 'light', 'essential' => true,
                    'text' => 'Зменшує споживання оперативної памʼяті. Рятує на ПК з 4–8 ГБ.'],
                ['name' => 'ImmediatelyFast', 'slug' => 'immediatelyfast', 'load' => 'light', 'essential' => true,
                    'text' => 'Прискорює малювання інтерфейсу, тексту, табличок, мап і частинок.'],
                ['name' => 'Entity Culling', 'slug' => 'entityculling', 'load' => 'light', 'essential' => true,
                    'text' => 'Не малює мобів, гравців і скрині, яких не видно за стінами. Помітно більше FPS у селах і на базах.'],
                ['name' => 'More Culling', 'slug' => 'moreculling', 'load' => 'light', 'requires' => ['Cloth Config'],
                    'text' => 'Не малює невидимі грані блоків і листя — менше роботи відеокарті.'],
                ['name' => 'ModernFix', 'slug' => 'modernfix', 'load' => 'light',
                    'text' => 'Швидший запуск гри й менше памʼяті під час гри.'],
                ['name' => 'Dynamic FPS', 'slug' => 'dynamic-fps', 'load' => 'light',
                    'text' => 'Знижує FPS, коли гра згорнута або неактивна: ПК не гріється, а ноутбук довше тримає заряд.'],
                ['name' => 'Sodium Extra', 'slug' => 'sodium-extra', 'load' => 'light', 'requires' => ['Sodium'],
                    'text' => 'Додаткові налаштування графіки: хмари, туман, частинки, анімації. Можна вимкнути все, що зʼїдає FPS.'],
                ['name' => 'Reese\'s Sodium Options', 'slug' => 'reeses-sodium-options', 'load' => 'light', 'requires' => ['Sodium'],
                    'text' => 'Зручне меню налаштувань Sodium з вкладками й пошуком.'],
            ],
        ],
        [
            'id' => 'visual',
            'icon' => 'sparkles',
            'accent' => 'violet',
            'title' => 'Краса',
            'lead' => 'Візуальні моди: світ і гравці виглядають живіше. Кожен впливає на FPS по-різному — дивись позначку навантаження.',
            'mods' => [
                ['name' => 'Iris Shaders', 'slug' => 'iris', 'load' => 'heavy', 'requires' => ['Sodium'],
                    'text' => 'Шейдери: справжні тіні, світло, вода й небо. Набір шейдерів обирається окремо — нижче є варіанти для слабкого й потужного ПК.'],
                ['name' => '3D Skin Layers', 'slug' => '3dskinlayers', 'load' => 'light', 'essential' => true,
                    'text' => 'Другий шар скіна стає обʼємним: капюшони, куртки й волосся виглядають тривимірно. Працює і зі скінами із сайту.'],
                ['name' => 'Not Enough Animations', 'slug' => 'not-enough-animations', 'load' => 'light',
                    'text' => 'Анімації від першої особи видно й ззовні: як гравці їдять, тримають мапу, натягують лук.'],
                ['name' => 'Continuity', 'slug' => 'continuity', 'load' => 'light',
                    'text' => 'Зʼєднані текстури: скло без рамок між блоками й гарніші полиці та пісковик з ресурспаками.'],
                ['name' => 'Entity Model Features', 'slug' => 'entity-model-features', 'load' => 'medium', 'requires' => ['Entity Texture Features'],
                    'text' => 'Дозволяє ресурспакам змінювати моделі мобів. Потрібен для Fresh Animations.'],
                ['name' => 'Entity Texture Features', 'slug' => 'entitytexturefeatures', 'load' => 'light',
                    'text' => 'Різні текстури для мобів одного виду, світні очі й випадкові варіанти з ресурспаків.'],
                ['name' => 'Fresh Animations', 'slug' => 'fresh-animations', 'type' => 'resourcepack', 'load' => 'medium', 'requires' => ['Entity Model Features', 'Entity Texture Features'],
                    'text' => 'Ресурспак, що оживляє мобів: вони моргають, дихають, озираються й рухаються природніше.'],
                ['name' => 'Falling Leaves', 'slug' => 'fallingleaves', 'load' => 'light', 'requires' => ['Cloth Config'],
                    'text' => 'З крон дерев повільно падає листя — ліси трьох країв оживають.'],
                ['name' => 'Visuality', 'slug' => 'visuality', 'load' => 'light', 'requires' => ['Cloth Config'],
                    'text' => 'Дрібні частинки: іскри від кристалів, бризки води, кістки від скелетів.'],
                ['name' => 'Particle Rain', 'slug' => 'particle-rain', 'load' => 'medium',
                    'text' => 'Дощ і сніг частинками, туман у негоду й бризки на воді. Особливо гарно в Крижаних Коронах.'],
                ['name' => 'Wavey Capes', 'slug' => 'wavey-capes', 'load' => 'light',
                    'text' => 'Плащі плавно розвіваються на вітрі.'],
                ['name' => 'LambDynamicLights', 'slug' => 'lambdynamiclights', 'load' => 'medium',
                    'text' => 'Факел у руці світить навколо. Це лише візуальний ефект для тебе: на спавн мобів він не впливає.'],
            ],
        ],
        [
            'id' => 'shaders',
            'icon' => 'sun',
            'accent' => 'sun',
            'title' => 'Шейдери для Iris',
            'lead' => 'Набір шейдерів кладеться в папку shaderpacks і вмикається в налаштуваннях: Відео → Шейдери. Потрібні Iris і Sodium.',
            'mods' => [
                ['name' => 'MakeUp — Ultra Fast', 'slug' => 'makeup-ultra-fast-shaders', 'type' => 'shader', 'load' => 'medium',
                    'text' => 'Найлегші гарні шейдери: тіні й світло навіть на слабкій відеокарті.'],
                ['name' => 'Complementary Reimagined', 'slug' => 'complementary-reimagined', 'type' => 'shader', 'load' => 'heavy',
                    'text' => 'Збалансовані шейдери в дусі ванільного Minecraft: мʼяке світло, гарна вода й небо. Для середніх і потужних ПК.'],
                ['name' => 'BSL Shaders', 'slug' => 'bsl-shaders', 'type' => 'shader', 'load' => 'heavy',
                    'text' => 'Класика: яскраве сонце, обʼємні хмари й глибокі тіні. Для потужних ПК.'],
            ],
        ],
        [
            'id' => 'sound',
            'icon' => 'volume-2',
            'accent' => 'frost',
            'title' => 'Звук',
            'lead' => 'Атмосфера тримається не лише на картинці: ці моди майже не впливають на FPS.',
            'mods' => [
                ['name' => 'Sound Physics Remastered', 'slug' => 'sound-physics-remastered', 'load' => 'medium',
                    'text' => 'Реалістична луна й приглушення: у печерах звук відбивається, а за стінами стає глухішим.'],
                ['name' => 'AmbientSounds', 'slug' => 'ambientsounds', 'load' => 'light', 'requires' => ['CreativeCore'],
                    'text' => 'Звуки довкілля: вітер у горах, птахи в лісі, цвіркуни вночі, крапель у печерах.'],
                ['name' => 'Presence Footsteps', 'slug' => 'presence-footsteps', 'load' => 'light',
                    'text' => 'Кроки звучать по-різному на різних поверхнях, а в мобів — свої кроки.'],
            ],
        ],
        [
            'id' => 'comfort',
            'icon' => 'compass',
            'accent' => 'gold',
            'title' => 'Зручність',
            'lead' => 'Моди, які правила дозволяють: вони не дають переваги над іншими гравцями.',
            'mods' => [
                ['name' => 'Xaero\'s Minimap', 'slug' => 'xaeros-minimap', 'load' => 'light',
                    'text' => 'Мінімапа й мітки. Радар сутностей і режим печер сервер вимикає сам — так вимагають правила.'],
                ['name' => 'AppleSkin', 'slug' => 'appleskin', 'load' => 'light',
                    'text' => 'Показує ситість і відновлення від їжі прямо на шкалі голоду.'],
                ['name' => 'Mod Menu', 'slug' => 'modmenu', 'load' => 'light', 'requires' => ['Text Placeholder API'],
                    'text' => 'Список встановлених модів і їхні налаштування просто в меню гри.'],
            ],
        ],
    ],

    // Набори — це «список покупок»: разом із бібліотеками з «Потрібно» (крім Fabric API, він у кроці 2)
    'presets' => [
        [
            'icon' => 'monitor',
            'title' => 'Слабкий ПК',
            'text' => 'Лише FPS, вигляд гри не змінюється:',
            'mods' => ['Fabric API', 'Sodium', 'FerriteCore', 'ImmediatelyFast', 'Entity Culling', 'More Culling', 'Cloth Config', 'ModernFix', 'Dynamic FPS'],
        ],
        [
            'icon' => 'gamepad-2',
            'title' => 'Золота середина',
            'text' => 'Усе зі «Слабкого ПК», а ще жива картинка й звук майже без втрати FPS:',
            'mods' => ['3D Skin Layers', 'Not Enough Animations', 'Continuity', 'Falling Leaves', 'Presence Footsteps', 'AppleSkin'],
        ],
        [
            'icon' => 'sparkles',
            'title' => 'Максимум атмосфери',
            'text' => 'Усе із «Золотої середини», а ще шейдери, живі моби й обʼємний звук — для потужного ПК:',
            'mods' => ['Iris Shaders', 'Complementary Reimagined', 'Entity Texture Features', 'Entity Model Features', 'Fresh Animations', 'Visuality', 'Particle Rain', 'Sound Physics Remastered', 'AmbientSounds', 'CreativeCore'],
        ],
    ],

    'forbidden' => [
        'X-Ray у будь-якому вигляді — моди й ресурспаки',
        'Чит-клієнти: кілаура, автоклікер, політ, швидкість, freecam',
        'Радари гравців і мобів, мінімапи з радаром або режимом печер',
        'Боти й автоматизація, що грають замість тебе (Baritone тощо)',
    ],
];
