#!/usr/bin/env bash
# Збирає dist/palermocraft-site.zip для cPanel: код з git HEAD + vendor без dev-пакетів.
# Використання: bash deploy/build.sh   (запускає й GitHub Actions після злиття в main)
set -euo pipefail

ROOT="$(cd "$(dirname "$0")/.." && pwd)"
OUT="$ROOT/dist"
STAGE="$OUT/stage"
ZIP="$OUT/palermocraft-site.zip"

rm -rf "$OUT"
mkdir -p "$STAGE"

# 1. Лише закомічені файли: жодних .env, баз, логів чи завантажених скінів
git -C "$ROOT" archive HEAD | tar -x -C "$STAGE"
cd "$STAGE"

# 2. Залежності без dev (на cPanel зазвичай немає composer)
composer install --no-dev --optimize-autoloader --no-interaction --no-progress --prefer-dist

# 3. Прибираємо те, що на хостингу не потрібне
#    (.git у vendor з'являються, якщо composer качав пакети з джерел, а не архівами)
find vendor -name .git -type d -prune -exec rm -rf {} +
rm -f vendor/laravel/framework/bin/splitsh-lite
rm -rf tests phpunit.xml .github .editorconfig .gitattributes node_modules dist
rm -f bootstrap/cache/*.php
cp deploy/root.htaccess .htaccess
cp deploy/env.cpanel .env.cpanel
rm -rf deploy

# 4. Версія і список файлів (update.php за ним прибирає застарілі файли)
COMMIT="$(git -C "$ROOT" rev-parse --short HEAD)"
printf '{"commit":"%s","built_at":"%s"}\n' "$COMMIT" "$(date -u +%Y-%m-%dT%H:%MZ)" > VERSION
find . -type f -not -path './vendor/*' | sed 's#^\./##' | LC_ALL=C sort > .deploy-manifest

# 5. Архів без верхньої папки: розпаковується прямо в папку сайту
zip -qr -X "$ZIP" .
cd "$ROOT"
rm -rf "$STAGE"
echo "Готово: $ZIP ($(du -h "$ZIP" | cut -f1)), коміт $COMMIT"
