# palermocraft.com

Сайт сервера **PALERMOCRAFT** — Сезон I «Ехо Безодні».

Laravel 12 без збирання фронтенду: власна дизайн-система (`public/css/palermo.css`), трохи JavaScript без залежностей (`public/js/palermo.js`), шрифт Comfortaa лежить поруч із сайтом. Тож хостингу без Node.js вистачає, а сторінки легкі навіть для слабких ПК.

## Що є на сайті

| Розділ | Адреса | Звідки тексти |
|---|---|---|
| Головна: сезон, адреса сервера, хто онлайн, новини | `/` | `resources/content/season.php` |
| Світ: лор, домени, 12 Очей, відбудова | `/world` | `resources/content/season.php` |
| Путівник: як зайти, вхід без паролів, поселення, команди, питання | `/guide` | `resources/content/season.php` |
| Правила | `/rules` | `resources/content/rules.php` |
| Підтримка (замість магазину) | `/support` | `resources/views/pages/support.blade.php` |
| Новини | `/news` | адмін-панель |
| Профіль, скін (3D-перегляд), Discord/Telegram, привʼязка ніку | `/profile` | — |
| Адмін-панель: гравці, бани, ролі, скіни, новини | `/admin` | — |
| API для плагінів гри | `/api/game/*` | див. `docs/SETUP.uk.md` |

Старі адреси `/store` і `/about` переадресовуються на `/support` і `/world`.

## Як змінити тексти

Лор, можливості, ранги поселень, команди й питання — у `resources/content/season.php`, правила — у `resources/content/rules.php`. Це звичайні PHP-масиви: міняєш рядок у лапках, зберігаєш — і сайт одразу показує нове (кеш конфігурації їх не зачіпає).

Адреса сервера, версія Minecraft і посилання на Discord/Telegram задаються в `.env` (див. `docs/SETUP.uk.md`).

## Локально

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan serve
```

Тести: `php artisan test`.

## Розгортання

cPanel без SSH: архів зі збіркою та сторінка `update.php` — усе в [`docs/SETUP.uk.md`](docs/SETUP.uk.md).

## Сторонні ресурси

- Шрифт Comfortaa — SIL Open Font License 1.1 (`public/fonts`).
- Іконки Lucide — ISC (`resources/icons/LICENSE`).
- Логотипи Discord і Telegram — Simple Icons, CC0 (`resources/icons/brands`).
- 3D-перегляд скінів skinview3d — MIT (`public/vendor/skinview3d`).

PALERMOCRAFT не є офіційним продуктом Minecraft і не повʼязаний із Mojang чи Microsoft.
