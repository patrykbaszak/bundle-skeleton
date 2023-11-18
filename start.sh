#!/bin/bash

composer create-project pbaszak/skeleton --no-interaction

rm -rf skeleton/src
rm -rf skeleton/config/routes.yaml
cp -r src/src skeleton/src
rm -rf skeleton/bin
cp -r src/bin skeleton/bin
rm -rf skeleton/public

docker run --rm -v $(pwd):/app -w /app php:latest bash -c "php scripts/Setup.php $1"

rm -rf skeleton/src/SkeletonBundle.php skeleton/src/DependencyInjection/SkeletonExtension.php;
rm -rf node_modules scripts src .gitignore CHANGELOG.md composer.json composer.lock README.md LICENSE .git vendor start.sh package.json package-lock.json
mv skeleton/{,.[^.]}* ./
rm -rf skeleton
rm -rf CHANGELOG.md

bash start.sh
docker exec php composer update || composer update
