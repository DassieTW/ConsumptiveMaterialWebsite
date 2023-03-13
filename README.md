## In Dev

npx mix watch

## Everytime u update a js or a css
you should also update the version number in .env file,
so that all clients' broswer will download the latest files.

## In Deployment/Production

yarn run clear:babel-cache <br />
npx mix --production <br />

## Generate the vue-translations.js 
### (convert Laravel translation lang to Vue translation lang)

php artisan lang:js resources/js/vue-translations.js --no-lib --quiet

## Useful Laravel Commands (used in workspace container)

php artisan optimize:clear <br />
php artisan view:clear <br />
php artisan route:clear <br />
php artisan cache:clear <br />
php artisan queue:restart <br />
php artisan clear-compiled <br />
php artisan logs:clear <br />
php artisan log:clear <br />
php artisan config:clear <br />
composer dump-autoload <br />
php artisan storage:link <br />
php artisan key:generate <br />

# Migrate a single file (table)
php artisan migrate --path=/database/migrations/2022_12_21_165804_create_bulletins_table.php <br />

## In intra network, under this folder: 

cp .env.server /home/it/PEGA_Projects/ConsumptiveMaterialWebsite/.env
