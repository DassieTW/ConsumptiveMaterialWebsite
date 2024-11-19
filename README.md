## In Dev
```
npx mix watch
```

## Everytime u update a js or a css
you should also update the version number in .env file,
so that all clients' broswer will download the latest files.

## In Deployment/Production
```
yarn run clear:babel-cache
npx mix --production
```

## Current Services
```
docker compose up -d nginx workspace redis mssql meilisearch hq_component_workspace
```

## Useful Laravel Commands (used in workspace container)
```
php artisan optimize:clear
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

php artisan lang:js --quiet
php artisan lang:js resources/js/vue-translations.js --no-lib --quiet
php artisan exchange:rate
```

## Migrate a single file (table)
```
php artisan migrate --path=/database/migrations/2022_12_21_165804_create_bulletins_table.php
```

## Go into Maintenance Mode
```
php artisan down --secret="someKey" --render="errors::503"
```
# Then IT can access the website by the address below
https://ptwsiteservice01.pega.corp.pegatron/someKey

# In intra network, under this project's folder: 
```
cp .env.server /home/it/PEGA_Projects/ConsumptiveMaterialWebsite/.env
```

## Remember to update the keywords from time to time
/resources/meilisearchWords/keywords.json
# and then run the command below
```
php artisan meilisearch:clear
```

## New Server Container Should Run the Command Below to Set Up Scheduled Tasks
https://laravel.com/docs/8.x/scheduling#running-the-scheduler
```
* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
```
since we docker exec into the container, the {path-to-your-project} should be /var/www
