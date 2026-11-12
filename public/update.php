<?php
/**
 * PALERMOCRAFT — оновлення сайту на cPanel без SSH.
 *
 * Відкрий https://palermocraft.com/update.php, введи UPDATE_KEY з .env і вибери дію:
 *  - «Лише база і кеш»: перше встановлення (APP_KEY, таблиці) або після правки .env;
 *  - «Оновити з GitHub»: бере останню збірку з Releases (її робить GitHub Actions після злиття в main);
 *  - «Оновити з архіву»: palermocraft-site.zip, завантажений через форму або покладений поруч з artisan як update.zip.
 *
 * Ніколи не чіпає: .env, storage/ (скіни, логи, сесії), database/database.sqlite, .htaccess, .user.ini.
 * Перед оновленням робить копію коду в storage/app/backups/.
 *
 * Написано сумісно з PHP 7.4, щоб на старому PHP сторінка хоча б сказала, що треба 8.3.
 */

declare(strict_types=1);

@set_time_limit(600);
@ignore_user_abort(true);
@ini_set('memory_limit', '512M');

define('PC_BASE', dirname(__DIR__));
define('PC_ASSET', 'palermocraft-site.zip');
define('PC_MIN_PHP', '8.3.0');

/** Шляхи, які оновлення ніколи не перезаписує і не видаляє. */
const PC_PROTECTED_EXACT = [
    '.env', 'database/database.sqlite', 'update.zip',
    '.htaccess', 'public/.htaccess', '.user.ini', 'public/.user.ini', 'php.ini', 'public/php.ini',
    'error_log', 'public/error_log',
];
const PC_PROTECTED_PREFIX = ['storage/', '.git/', '.well-known/', 'public/.well-known/'];

// ----------------------------------------------------------------------------------------------
// Допоміжне
// ----------------------------------------------------------------------------------------------

function pc_env(): array
{
    static $env = null;
    if ($env !== null) {
        return $env;
    }
    $env = [];
    $file = PC_BASE . '/.env';
    if (!is_file($file)) {
        return $env;
    }
    foreach (file($file, FILE_IGNORE_NEW_LINES) as $line) {
        $line = trim($line);
        if ($line === '' || $line[0] === '#' || strpos($line, '=') === false) {
            continue;
        }
        [$k, $v] = explode('=', $line, 2);
        $v = trim($v);
        if (strlen($v) >= 2 && ($v[0] === '"' || $v[0] === "'") && substr($v, -1) === $v[0]) {
            $v = substr($v, 1, -1);
        }
        $env[trim($k)] = $v;
    }
    return $env;
}

function pc_log(string $line): void
{
    $GLOBALS['pc_log'][] = $line;
    @file_put_contents(PC_BASE . '/storage/logs/update.log', '[' . date('Y-m-d H:i:s') . '] ' . $line . "\n", FILE_APPEND);
}

function pc_h(string $s): string
{
    return htmlspecialchars($s, ENT_QUOTES, 'UTF-8');
}

function pc_is_protected(string $rel): bool
{
    if (in_array($rel, PC_PROTECTED_EXACT, true)) {
        return true;
    }
    foreach (PC_PROTECTED_PREFIX as $p) {
        if (strncmp($rel, $p, strlen($p)) === 0) {
            return true;
        }
    }
    return false;
}

function pc_safe_rel(string $rel): bool
{
    return $rel !== '' && $rel[0] !== '/' && strpos($rel, '..') === false && strpos($rel, "\0") === false
        && strpos($rel, '\\') === false;
}

function pc_rrmdir(string $dir): void
{
    if (!is_dir($dir) || is_link($dir)) {
        @unlink($dir);
        return;
    }
    $it = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($dir, FilesystemIterator::SKIP_DOTS),
        RecursiveIteratorIterator::CHILD_FIRST
    );
    foreach ($it as $f) {
        $f->isDir() && !$f->isLink() ? @rmdir($f->getPathname()) : @unlink($f->getPathname());
    }
    @rmdir($dir);
}

function pc_version(string $root = PC_BASE): array
{
    $file = $root . '/VERSION';
    if (!is_file($file)) {
        return [];
    }
    $data = json_decode((string) file_get_contents($file), true);
    return is_array($data) ? $data : [];
}

function pc_manifest(string $root): array
{
    $file = $root . '/.deploy-manifest';
    if (!is_file($file)) {
        return [];
    }
    $lines = array_filter(array_map('trim', file($file)), 'strlen');
    return array_values(array_filter($lines, 'pc_safe_rel'));
}

function pc_http(string $url, array $headers, ?string $saveTo = null): array
{
    if (function_exists('curl_init')) {
        $ch = curl_init($url);
        $fh = null;
        $opts = [
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_MAXREDIRS => 5,
            CURLOPT_CONNECTTIMEOUT => 15,
            CURLOPT_TIMEOUT => 300,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_USERAGENT => 'palermocraft-updater',
        ];
        if ($saveTo !== null) {
            $fh = fopen($saveTo, 'wb');
            $opts[CURLOPT_FILE] = $fh;
        } else {
            $opts[CURLOPT_RETURNTRANSFER] = true;
        }
        curl_setopt_array($ch, $opts);
        $body = curl_exec($ch);
        $status = (int) curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
        $err = curl_error($ch);
        curl_close($ch);
        if ($fh) {
            fclose($fh);
        }
        if ($body === false) {
            throw new RuntimeException('Мережа: ' . $err);
        }
        return [$status, $saveTo === null ? (string) $body : ''];
    }

    $ctx = stream_context_create(['http' => [
        'header' => implode("\r\n", array_merge($headers, ['User-Agent: palermocraft-updater'])),
        'timeout' => 300,
        'ignore_errors' => true,
        'follow_location' => 1,
    ]]);
    $body = @file_get_contents($url, false, $ctx);
    $status = 0;
    foreach ($http_response_header ?? [] as $h) {
        if (preg_match('#^HTTP/\S+\s+(\d+)#', $h, $m)) {
            $status = (int) $m[1];
        }
    }
    if ($body === false) {
        throw new RuntimeException('Мережа: не вдалося відкрити ' . $url . ' (немає curl і allow_url_fopen)');
    }
    if ($saveTo !== null) {
        file_put_contents($saveTo, $body);
        return [$status, ''];
    }
    return [$status, $body];
}

// ----------------------------------------------------------------------------------------------
// Кроки оновлення
// ----------------------------------------------------------------------------------------------

/** Завантажує останню збірку з GitHub Releases. Повертає [шлях до zip, тег]. */
function pc_download_github(string $dest): array
{
    $env = pc_env();
    $repo = $env['UPDATE_GITHUB_REPO'] ?? 'NemezXYAZI/palermocraftwebsite';
    if (!preg_match('#^[\w.-]+/[\w.-]+$#', $repo)) {
        throw new RuntimeException('UPDATE_GITHUB_REPO має вигляд власник/репозиторій');
    }
    $token = $env['UPDATE_GITHUB_TOKEN'] ?? '';
    $headers = ['Accept: application/vnd.github+json', 'X-GitHub-Api-Version: 2022-11-28'];
    if ($token !== '') {
        $headers[] = 'Authorization: Bearer ' . $token;
    }

    [$status, $body] = pc_http("https://api.github.com/repos/$repo/releases/latest", $headers);
    if ($status === 404) {
        throw new RuntimeException("На GitHub ще немає збірок ($repo → Releases). Вони з'являються після злиття в main.");
    }
    if ($status !== 200) {
        throw new RuntimeException("GitHub API відповів $status");
    }
    $release = json_decode($body, true);
    $asset = null;
    foreach ($release['assets'] ?? [] as $a) {
        if (($a['name'] ?? '') === PC_ASSET) {
            $asset = $a;
        }
    }
    if ($asset === null) {
        throw new RuntimeException('У релізі ' . ($release['tag_name'] ?? '?') . ' немає файлу ' . PC_ASSET);
    }
    pc_log('Реліз: ' . ($release['tag_name'] ?? '?') . ', ' . round(($asset['size'] ?? 0) / 1048576, 1) . ' МБ');

    if ($token !== '') {
        $dl = [$asset['url'], ['Accept: application/octet-stream', 'Authorization: Bearer ' . $token]];
    } else {
        $dl = [$asset['browser_download_url'], []];
    }
    [$status] = pc_http($dl[0], $dl[1], $dest);
    if ($status !== 200 || !is_file($dest) || filesize($dest) < 1024) {
        throw new RuntimeException("Не вдалося завантажити архів (HTTP $status)");
    }
    return [$dest, (string) ($release['tag_name'] ?? '')];
}

/** Розпаковує архів у тимчасову папку і повертає корінь збірки (папку з artisan). */
function pc_extract(string $zipPath, string $tmp): string
{
    if (!class_exists('ZipArchive')) {
        throw new RuntimeException('На хостингу немає PHP-розширення zip (увімкни в «Select PHP Version» → Extensions)');
    }
    $zip = new ZipArchive();
    if ($zip->open($zipPath) !== true) {
        throw new RuntimeException('Файл не схожий на zip-архів');
    }
    for ($i = 0; $i < $zip->numFiles; $i++) {
        $name = (string) $zip->getNameIndex($i);
        if (strpos($name, '..') !== false || $name === '' || $name[0] === '/' || strpos($name, '\\') !== false) {
            $zip->close();
            throw new RuntimeException('Підозрілий шлях в архіві: ' . $name);
        }
    }
    pc_rrmdir($tmp);
    @mkdir($tmp, 0755, true);
    if (!$zip->extractTo($tmp)) {
        $zip->close();
        throw new RuntimeException('Не вдалося розпакувати (місце на диску?)');
    }
    $zip->close();

    // Архів міг бути перепакований з папкою зверху
    $root = $tmp;
    if (!is_file($root . '/artisan')) {
        foreach (glob($tmp . '/*', GLOB_ONLYDIR) ?: [] as $dir) {
            if (is_file($dir . '/artisan')) {
                $root = $dir;
            }
        }
    }
    foreach (['artisan', 'vendor/autoload.php', 'public/index.php', 'bootstrap/app.php'] as $must) {
        if (!is_file($root . '/' . $must)) {
            throw new RuntimeException("В архіві немає $must — це не повна збірка palermocraft-site.zip");
        }
    }
    return $root;
}

/** Копія поточного коду (без vendor і storage), щоб можна було відкотитися вручну. */
function pc_backup(): void
{
    if (!class_exists('ZipArchive')) {
        return;
    }
    $dir = PC_BASE . '/storage/app/backups';
    @mkdir($dir, 0755, true);
    $file = $dir . '/code-' . date('Ymd-His') . '.zip';
    $zip = new ZipArchive();
    if ($zip->open($file, ZipArchive::CREATE) !== true) {
        pc_log('⚠ Резервну копію не створено');
        return;
    }
    $count = 0;
    foreach (['app', 'bootstrap', 'config', 'database/migrations', 'public', 'resources', 'routes'] as $d) {
        if (!is_dir(PC_BASE . '/' . $d)) {
            continue;
        }
        $it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator(PC_BASE . '/' . $d, FilesystemIterator::SKIP_DOTS));
        foreach ($it as $f) {
            if ($f->isFile()) {
                $zip->addFile($f->getPathname(), substr($f->getPathname(), strlen(PC_BASE) + 1));
                $count++;
            }
        }
    }
    foreach (['composer.json', 'composer.lock', 'VERSION', '.deploy-manifest'] as $f) {
        if (is_file(PC_BASE . '/' . $f)) {
            $zip->addFile(PC_BASE . '/' . $f, $f);
        }
    }
    $zip->close();
    pc_log("Резервна копія коду: storage/app/backups/" . basename($file) . " ($count файлів)");

    // Лишаємо 3 останні
    $all = glob($dir . '/code-*.zip') ?: [];
    sort($all);
    foreach (array_slice($all, 0, max(0, count($all) - 3)) as $old) {
        @unlink($old);
    }
}

function pc_maintenance(bool $on): void
{
    $file = PC_BASE . '/storage/framework/down';
    if ($on) {
        @file_put_contents($file, json_encode([
            'except' => [], 'redirect' => null, 'retry' => 60, 'refresh' => 15,
            'secret' => null, 'status' => 503, 'template' => null,
        ]));
    } else {
        @unlink($file);
    }
}

/** Переносить нову збірку на місце старої. */
function pc_install_files(string $src): void
{
    $oldManifest = pc_manifest(PC_BASE);
    $newManifest = pc_manifest($src);
    $copied = 0;
    $kept = 0;

    $it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($src, FilesystemIterator::SKIP_DOTS));
    foreach ($it as $f) {
        if (!$f->isFile()) {
            continue;
        }
        $rel = substr($f->getPathname(), strlen($src) + 1);
        if (strncmp($rel, 'vendor/', 7) === 0) {
            continue; // vendor міняємо цілком нижче
        }
        if (pc_is_protected($rel) && file_exists(PC_BASE . '/' . $rel)) {
            $kept++;
            continue;
        }
        if (strncmp($rel, 'storage/', 8) === 0) {
            continue; // каркас storage потрібен лише при першому встановленні
        }
        $dest = PC_BASE . '/' . $rel;
        if (!is_dir(dirname($dest))) {
            @mkdir(dirname($dest), 0755, true);
        }
        if (!@copy($f->getPathname(), $dest)) {
            throw new RuntimeException('Не вдалося записати ' . $rel . ' (права доступу?)');
        }
        $copied++;
    }
    pc_log("Скопійовано файлів: $copied, збережено твоїх: $kept");

    // vendor: підміна цілої папки, щоб не лишалося сміття від видалених пакетів
    $vNew = PC_BASE . '/vendor.new';
    $vOld = PC_BASE . '/vendor.old';
    pc_rrmdir($vNew);
    pc_rrmdir($vOld);
    if (!@rename($src . '/vendor', $vNew)) {
        throw new RuntimeException('Не вдалося перенести vendor');
    }
    if (is_dir(PC_BASE . '/vendor') && !@rename(PC_BASE . '/vendor', $vOld)) {
        throw new RuntimeException('Не вдалося прибрати старий vendor');
    }
    if (!@rename($vNew, PC_BASE . '/vendor')) {
        @rename($vOld, PC_BASE . '/vendor');
        throw new RuntimeException('Не вдалося поставити новий vendor (старий повернуто)');
    }
    pc_rrmdir($vOld);
    pc_log('vendor оновлено');

    // Файли, яких більше немає в новій збірці (лише ті, що ставила попередня збірка)
    if ($oldManifest && $newManifest) {
        $gone = array_diff($oldManifest, $newManifest);
        $removed = 0;
        foreach ($gone as $rel) {
            if (!pc_safe_rel($rel) || pc_is_protected($rel) || strncmp($rel, 'vendor/', 7) === 0) {
                continue;
            }
            if (is_file(PC_BASE . '/' . $rel) && @unlink(PC_BASE . '/' . $rel)) {
                $removed++;
            }
        }
        if ($removed) {
            pc_log("Видалено застарілих файлів: $removed");
        }
    }
}

/** Міграції і кеш через Artisan прямо з PHP (SSH не потрібен). */
function pc_artisan_steps(): bool
{
    if (!is_file(PC_BASE . '/vendor/autoload.php')) {
        throw new RuntimeException('Немає vendor/ — спершу розпакуй повний архів palermocraft-site.zip');
    }
    foreach (glob(PC_BASE . '/bootstrap/cache/*.php') ?: [] as $f) {
        @unlink($f);
    }
    if (function_exists('opcache_reset')) {
        @opcache_reset();
    }

    require PC_BASE . '/vendor/autoload.php';
    $app = require PC_BASE . '/bootstrap/app.php';
    /** @var \Illuminate\Contracts\Console\Kernel $kernel */
    $kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
    $kernel->bootstrap();

    $ok = true;
    $run = function (string $cmd, array $args = [], bool $critical = false) use ($kernel, &$ok) {
        try {
            $code = $kernel->call($cmd, $args);
            $out = trim(preg_replace('/\e\[[\d;]*m/', '', $kernel->output()));
            pc_log(($code === 0 ? '✔ ' : '✖ ') . "artisan $cmd" . ($out !== '' ? "\n" . $out : ''));
            if ($code !== 0 && $critical) {
                $ok = false;
            }
        } catch (Throwable $e) {
            pc_log("✖ artisan $cmd: " . $e->getMessage());
            if ($critical) {
                $ok = false;
            }
        }
    };

    if (trim((string) (pc_env()['APP_KEY'] ?? '')) === '') {
        $run('key:generate', ['--force' => true], true);
    }
    $run('migrate', ['--force' => true], true);
    $run('optimize:clear');
    $run('config:cache');
    $run('route:cache');
    $run('view:cache');
    return $ok;
}

// ----------------------------------------------------------------------------------------------
// Сторінка
// ----------------------------------------------------------------------------------------------

$GLOBALS['pc_log'] = [];
$env = pc_env();
$key = (string) ($env['UPDATE_KEY'] ?? '');
$enabled = strlen($key) >= 16;
$phpOk = version_compare(PHP_VERSION, PC_MIN_PHP, '>=');
$version = pc_version();
$result = null;

$checks = [
    'PHP ' . PHP_VERSION . ' (потрібно ' . PC_MIN_PHP . '+)' => $phpOk,
    'Файл .env існує' => is_file(PC_BASE . '/.env'),
    'Розширення zip' => class_exists('ZipArchive'),
    'Розширення pdo_mysql' => extension_loaded('pdo_mysql'),
    'Розширення mbstring' => extension_loaded('mbstring'),
    'Розширення openssl' => extension_loaded('openssl'),
    'curl або allow_url_fopen' => function_exists('curl_init') || (bool) ini_get('allow_url_fopen'),
    'Запис у storage/' => is_writable(PC_BASE . '/storage'),
    'Запис у bootstrap/cache/' => is_writable(PC_BASE . '/bootstrap/cache'),
    'Запис у папку сайту' => is_writable(PC_BASE),
];

$tooBig = $_SERVER['REQUEST_METHOD'] === 'POST' && empty($_POST) && (int) ($_SERVER['CONTENT_LENGTH'] ?? 0) > 0;
if ($tooBig) {
    $result = ['ok' => false, 'title' => 'Архів більший за post_max_size (' . ini_get('post_max_size')
        . '). Поклади його через Файловий менеджер як update.zip поруч з artisan і натисни «Оновити з архіву» без файлу.'];
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $given = (string) ($_POST['key'] ?? '');
    $action = (string) ($_POST['action'] ?? '');
    if (!$enabled || !hash_equals($key, $given)) {
        usleep(random_int(400000, 900000));
        http_response_code(403);
        $result = ['ok' => false, 'title' => 'Невірний ключ або UPDATE_KEY не задано в .env'];
    } elseif (!$phpOk) {
        $result = ['ok' => false, 'title' => 'Потрібен PHP ' . PC_MIN_PHP . '+ (cPanel → Select PHP Version)'];
    } else {
        $lock = @fopen(PC_BASE . '/storage/framework/update.lock', 'c');
        if (!$lock || !flock($lock, LOCK_EX | LOCK_NB)) {
            $result = ['ok' => false, 'title' => 'Оновлення вже йде в іншій вкладці'];
        } else {
            $tmp = PC_BASE . '/storage/app/update-tmp';
            $download = PC_BASE . '/storage/app/update-download.zip';
            $maintenance = false;
            try {
                if ($action === 'github' || $action === 'zip') {
                    if ($action === 'github') {
                        [$zipPath, $tag] = pc_download_github($download);
                    } elseif (!empty($_FILES['archive']['tmp_name']) && is_uploaded_file($_FILES['archive']['tmp_name'])) {
                        $zipPath = $_FILES['archive']['tmp_name'];
                        pc_log('Архів із форми: ' . (string) $_FILES['archive']['name']);
                    } elseif (is_file(PC_BASE . '/update.zip')) {
                        $zipPath = PC_BASE . '/update.zip';
                        pc_log('Архів: update.zip у папці сайту');
                    } else {
                        $err = $_FILES['archive']['error'] ?? UPLOAD_ERR_NO_FILE;
                        throw new RuntimeException($err === UPLOAD_ERR_INI_SIZE || $err === UPLOAD_ERR_FORM_SIZE
                            ? 'Архів більший за upload_max_filesize. Поклади його через Файловий менеджер як update.zip поруч з artisan.'
                            : 'Вибери архів або поклади update.zip поруч з artisan');
                    }

                    $root = pc_extract($zipPath, $tmp);
                    $new = pc_version($root);
                    pc_log('Нова збірка: ' . ($new['commit'] ?? '?') . ' від ' . ($new['built_at'] ?? '?'));
                    if (empty($_POST['force']) && !empty($new['commit']) && ($version['commit'] ?? null) === $new['commit']) {
                        pc_log('Ця збірка вже встановлена. Постав галочку «Все одно», щоб перевстановити.');
                        $result = ['ok' => true, 'title' => 'Уже актуальна версія'];
                    } else {
                        pc_maintenance($maintenance = true);
                        pc_log('Сайт переведено в режим обслуговування');
                        pc_backup();
                        pc_install_files($root);
                    }
                    if ($action === 'zip' && isset($zipPath) && $zipPath === PC_BASE . '/update.zip' && $result === null) {
                        @unlink(PC_BASE . '/update.zip');
                    }
                }

                if ($result === null) {
                    if (!in_array($action, ['github', 'zip', 'migrate'], true)) {
                        throw new RuntimeException('Невідома дія');
                    }
                    $ok = pc_artisan_steps();
                    $result = ['ok' => $ok, 'title' => $ok ? 'Готово ✔' : 'Є помилки — дивись журнал нижче'];
                }
            } catch (Throwable $e) {
                pc_log('✖ ' . $e->getMessage());
                $result = ['ok' => false, 'title' => 'Помилка: ' . $e->getMessage()];
            } finally {
                pc_rrmdir($tmp);
                @unlink($download);
                if ($maintenance) {
                    pc_maintenance(false);
                    pc_log('Режим обслуговування вимкнено');
                }
                flock($lock, LOCK_UN);
                fclose($lock);
            }
            $version = pc_version();
        }
    }
}

header('Cache-Control: no-store');
header('X-Robots-Tag: noindex');
?><!doctype html>
<html lang="uk">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="robots" content="noindex">
<title>Оновлення — PALERMOCRAFT</title>
<style>
  :root { --bg:#0b0f0c; --card:#121a14; --line:#1f2d22; --neon:#39ff14; --text:#d7e4d9; --muted:#7f9184; --bad:#ff5a5a; }
  * { box-sizing:border-box; }
  body { margin:0; background:var(--bg); color:var(--text); font:15px/1.5 system-ui, -apple-system, "Segoe UI", sans-serif; }
  main { max-width:760px; margin:40px auto; padding:0 16px; }
  h1 { font-size:22px; margin:0 0 4px; letter-spacing:.5px; }
  h1 span { color:var(--neon); }
  .muted { color:var(--muted); font-size:13px; }
  .card { background:var(--card); border:1px solid var(--line); border-radius:12px; padding:18px; margin:16px 0; }
  .checks { display:grid; grid-template-columns:1fr 1fr; gap:4px 16px; font-size:13px; }
  .ok { color:var(--neon); } .bad { color:var(--bad); }
  input[type=password], input[type=file] { width:100%; padding:10px 12px; background:#0b120d; color:var(--text);
      border:1px solid var(--line); border-radius:8px; font:inherit; }
  label { display:block; margin:12px 0 6px; font-size:13px; color:var(--muted); }
  .row { display:flex; gap:10px; flex-wrap:wrap; margin-top:14px; }
  button { cursor:pointer; padding:10px 16px; border-radius:8px; border:1px solid var(--neon); background:transparent;
      color:var(--neon); font:inherit; font-weight:600; }
  button.primary { background:var(--neon); color:#08100a; }
  button:hover { filter:brightness(1.15); }
  pre { white-space:pre-wrap; word-break:break-word; background:#070a08; border:1px solid var(--line); border-radius:8px;
      padding:12px; font-size:12.5px; max-height:480px; overflow:auto; }
  .result { font-weight:700; font-size:16px; }
  .hint { font-size:13px; color:var(--muted); margin-top:10px; }
</style>
</head>
<body>
<main>
  <h1><span>PALERMOCRAFT</span> · оновлення сайту</h1>
  <div class="muted">
    Встановлено: <?= $version ? pc_h(($version['commit'] ?? '?') . ' · ' . ($version['built_at'] ?? '')) : 'невідомо' ?>
  </div>

  <?php if (!$enabled): ?>
    <div class="card bad">
      Сторінку вимкнено: задай у <b>.env</b> рядок <code>UPDATE_KEY=</code> (мінімум 16 символів).
    </div>
  <?php endif; ?>

  <?php if ($result !== null): ?>
    <div class="card">
      <div class="result <?= $result['ok'] ? 'ok' : 'bad' ?>"><?= pc_h($result['title']) ?></div>
      <?php if ($GLOBALS['pc_log']): ?>
        <pre><?= pc_h(implode("\n", $GLOBALS['pc_log'])) ?></pre>
      <?php endif; ?>
    </div>
  <?php endif; ?>

  <div class="card">
    <div class="checks">
      <?php foreach ($checks as $label => $ok): ?>
        <div class="<?= $ok ? 'ok' : 'bad' ?>"><?= $ok ? '✔' : '✖' ?> <?= pc_h($label) ?></div>
      <?php endforeach; ?>
    </div>
  </div>

  <form class="card" method="post" enctype="multipart/form-data" autocomplete="off">
    <label for="key">UPDATE_KEY з .env</label>
    <input type="password" id="key" name="key" required>

    <label for="archive">Архів palermocraft-site.zip (лише для «Оновити з архіву»; або поклади його як update.zip поруч з artisan)</label>
    <input type="file" id="archive" name="archive" accept=".zip">

    <label><input type="checkbox" name="force" value="1"> Все одно перевстановити, навіть якщо версія та сама</label>

    <div class="row">
      <button class="primary" name="action" value="github">Оновити з GitHub</button>
      <button name="action" value="zip">Оновити з архіву</button>
      <button name="action" value="migrate">Лише база і кеш</button>
    </div>
    <div class="hint">
      «Лише база і кеш» — після першого встановлення і після <b>кожної</b> правки .env (скидає кеш налаштувань).<br>
      Твої .env, скіни, логи і база не чіпаються. Копія старого коду — у storage/app/backups/.
    </div>
  </form>
</main>
</body>
</html>
