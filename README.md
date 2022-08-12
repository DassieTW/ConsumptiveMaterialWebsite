## Environment init ( should not need it since node_module already in git flow)

npm install

## In Dev

npx mix watch

## In Deployment/Production

npm run clear:babel-cache
npx mix --production

## Useful Laravel Commands (used in workspace container)

php artisan view:clear
php artisan route:clear
php artisan cache:clear
php artisan queue:restart
php artisan clear-compiled
php artisan logs:clear
php artisan log:clear
php artisan config:clear
composer dump-autoload
php artisan storage:link
php artisan key:generate

## In intra network, under this folder: 

cp .env.server /home/it/PEGA_Projects/ConsumptiveMaterialWebsite/.env