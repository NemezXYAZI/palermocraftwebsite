# palermocraft.com — розгортання і налаштування

## 1. Хостинг (cPanel), без SSH

Потрібно: PHP **8.3+** (cPanel → *Select PHP Version*), розширення `zip`, `pdo_mysql`, `mbstring`, `openssl`, `fileinfo`, `curl`.

### Перше встановлення
1. cPanel → *MySQL® Databases*: створи базу і користувача, дай йому ALL PRIVILEGES.
2. *Файловий менеджер*: створи папку `palermocraft` у домашній папці (поруч з `public_html`, не всередині).
   Завантаж туди `palermocraft-site.zip` → *Extract*.
3. *Domains* → palermocraft.com → *Document Root* = `palermocraft/public`.
   Якщо змінити не можна: розпакуй архів прямо в `public_html`. Файл `.htaccess` з архіву сам перекине все в `public/`.
4. Перейменуй `.env.cpanel` → `.env` (увімкни *Show Hidden Files* у налаштуваннях менеджера) і заповни:
   `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`, `UPDATE_KEY` (довгий випадковий пароль, 16+ символів) і решту з таблиці нижче.
5. Відкрий `https://palermocraft.com/update.php`, введи `UPDATE_KEY` → **«Лише база і кеш»**.
   Згенерується `APP_KEY`, створяться таблиці, збереться кеш. Сайт готовий.

Після **кожної** правки `.env` знову натискай «Лише база і кеш»: налаштування закешовані.

### Оновлення
`https://palermocraft.com/update.php`:
- **«Оновити з GitHub»**: після кожного злиття в `main` GitHub Actions проганяє тести і викладає `palermocraft-site.zip` у Releases. Кнопка бере останній.
- **«Оновити з архіву»**: або вибери zip у формі, або (якщо він більший за ліміт завантаження PHP) поклади його через Файловий менеджер як `update.zip` поруч з `artisan` і натисни кнопку без файлу.

Що робить оновлення:
- вмикає режим обслуговування;
- кладе копію коду в `storage/app/backups/` (три останні);
- міняє файли і `vendor/`, прибирає файли, яких більше немає в збірці;
- запускає міграції й кеш, вимикає режим обслуговування.

**Ніколи не чіпає:** `.env`, `storage/` (скіни, логи, сесії), `database/database.sqlite`, `.htaccess`, `.user.ini`.
Журнал оновлень: `storage/logs/update.log`.

Зібрати архів вручну: `bash deploy/build.sh` → `dist/palermocraft-site.zip`.

Фонових процесів сайт не потребує: гра сама питає сайт через API.

## 2. `.env`

| Змінна | Що це |
|---|---|
| `APP_URL` | `https://palermocraft.com` |
| `APP_ENV` / `APP_DEBUG` | `production` / `false` |
| `DB_*` | дані MySQL з cPanel |
| `UPDATE_KEY` | пароль сторінки `/update.php`, 16+ символів |
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

Зареєструйся на сайті, потім у cPanel → *phpMyAdmin* → таблиця `users` → у своєму рядку постав `role` = `admin`.
Або SQL: `UPDATE users SET role = 'admin' WHERE email = 'ТВОЯ@ПОШТА';`
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
