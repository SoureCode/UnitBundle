#!/usr/bin/env bash

set -euo pipefail

CURRENT_DIRECTORY="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
PROJECT_DIRECTORY="$(dirname "$CURRENT_DIRECTORY")"

pushd "$PROJECT_DIRECTORY" >/dev/null

if [ ! -d "vendor" ]; then
    symfony composer update --no-interaction --no-progress --ansi
fi

kyx composer-normalize
kyx php-cs-fixer fix --show-progress=dots --using-cache=no --verbose

popd >/dev/null
