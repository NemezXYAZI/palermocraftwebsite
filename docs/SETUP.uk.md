# palermocraft.com — розгортання і налаштування

## 1. Хостинг (cPanel)

1. PHP 8.3+, розширення: `pdo_mysql`, `mbstring`, `openssl`, `gd` або `fileinfo`, `curl`.
2. Створи базу MySQL і користувача в cPanel → *MySQL Databases*.
3. Залий код (Git Version Control у cPanel або архівом) **поза** `public_html`, напр. `~/palermocraft`.
4. Document root домену → `~/palermocraft/public` (cPanel → *Domains*). Якщо так не можна —
   симлінк: `rm -rf ~/public_html && ln -s ~/palermocraft/public ~/public_html`.
5. У терміналі cPanel:
   ```bash
   cd ~/palermocraft
   composer install --no-dev --optimize-autoloader
   cp .env.example .env && php artisan key:generate
   # заповни .env (див. нижче)
   php artisan migrate --force
   php artisan config:cache && php artisan route:cache && php artisan view:cache
   ```
6. Права на запис: `storage/` і `bootstrap/cache/`.

Фонових процесів сайт не потребує: гра сама питає сайт через API.

## 2. `.env`

| Змінна | Що це |
|---|---|
| `APP_URL` | `https://palermocraft.com` |
| `APP_ENV` / `APP_DEBUG` | `production` / `false` |
| `DB_*` | дані MySQL з cPanel |
| `PALERMO_GAME_API_TOKEN` | спільний секрет із плагінами. Згенерувати: `php -r "echo bin2hex(random_bytes(32));"` |
| `PALERMO_GAME_WEBHOOK_URL` | необов'язково, `http://IP_СЕРВЕРА:8085` — миттєве оновлення скіна в грі |
| `PALERMO_SKINS_REQUIRE_APPROVAL` | `true` — нові скіни спершу перевіряє адмін |
| `MINESKIN_API_KEY` | ключ з https://account.mineskin.org (підписує скіни, щоб їх бачили всі клієнти, і ліцензія, і піратки) |
| `DISCORD_CLIENT_ID` / `DISCORD_CLIENT_SECRET` | застосунок на https://discord.com/developers/applications → OAuth2 |
| `TELEGRAM_BOT_USERNAME` / `TELEGRAM_BOT_TOKEN` | той самий бот, що в `palermo-sync` |

### Discord
OAuth2 → *Redirects* → додай `https://palermocraft.com/auth/discord/callback`. Scope: `identify`.

### Telegram
У @BotFather: `/setdomain` → вибери бота → `palermocraft.com`. Без цього віджет входу не з'явиться.

## 3. Перший адмін

```bash
php artisan tinker
>>> App\Models\User::where('email', 'ТВОЯ@ПОШТА')->update(['role' => 'admin']);
```
Ролі: `player`, `moderator` (скіни, бани), `admin` (усе, плюс ролі й новини).

## 4. Зв'язок із грою

Плагін `palermo-skins` (config.yml):
```yaml
website:
  url: https://palermocraft.com
  token: той самий PALERMO_GAME_API_TOKEN
```

- `GET /api/game/players/{нік}` — підписаний скін (value/signature), бан, Discord/Telegram ID для 2FA.
- `POST /api/game/link` — гравець вводить у грі `/sitelink КОД` з профілю на сайті.
- Обидва вимагають заголовок `X-Palermo-Token`, ліміт 120 запитів/хв.

## 5. Баланс

Поповнення поки вимкнене для всіх, крім адмінів (і локального середовища), доки не підключено справжню оплату.
