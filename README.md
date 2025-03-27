# Consumptive Material Website

## Introduction
This project is a web application for managing consumptive materials.<br/>
It is built using **Laravel v8.83.29 (PHP v8.4.1)** and **Vue3** and it leverages Docker for containerization.

## Setup
### Prerequisites
- Docker
- npm(for npx)
- yarn

### Installation
1. Clone the website repository:
    ```bash
    git clone https://github.com/VinTheMan/ConsumptiveMaterialWebsite.git
    ```
2. Clone the docker container setup repository:
    ```bash
    git clone https://github.com/VinTheMan/MultiProjectLaradock.git
    ```
3. Build the Docker containers:
    ```bash
    cd MultiProjectLaradock
    docker compose up -d nginx workspace redis mssql meilisearch
    ```
4. **INSIDE** the workspace container, run commands to set up the project:
    ```bash
    docker exec -it <laradock-workspace container id> /bin/bash
    yarn install
    composer dump-autoload
    php artisan storage:link
    php artisan key:generate
    php artisan migrateAll:fresh --seed
    ```

## Development
### Hot Reload While Editing Vue Files
To enable hot reload while editing Vue files, run the following command in your vscode terminal:<br/>
(**OUTSIDE** the containers's terminal)
```bash
cd ConsumptiveMaterialWebsite
npx mix watch
```

### Updating Keywords
Remember to update the keywords from time to time in `/resources/meilisearchWords/keywords.json`<br/>
and then run the following command **INSIDE** the workspace container:
```bash
php artisan meilisearch:clear
```

### Updating Translations
Remember to generate the translations files for js/vuejs after updating anything under `/resources/lang/`:
```bash
php artisan lang:js --quiet
php artisan lang:js resources/js/vue-translations.js --no-lib --quiet
```
### Updating JavaScript or CSS
Whenever you update a JavaScript or CSS file, make sure to update the version number in the `.env` file to ensure clients' browsers download the latest files.

## Deployment
### Output the Docker Images
In your local machine, run the following command to output the docker images:
```bash
docker save -o XXXX.tar <repo>:<tag>
```
**Remember to zip the tar before sending it to Windows to prevent corruption**
### File Transfer through PuTTY
To transfer files from remote desktop to the web server<br/>
**(The file should be in the same directory as the pscp.exe)**<br/>
Open Windows PowerShell and run the following command:
```sh
.\pscp -l it -pw 'it#1234' C:\Vincent\XXXX.tar.zip 172.18.220.52:/home/it
```
<br/>
Now use PuTTY to connect to the server and run the following command to extract the files and load the docker images:

```bash
unzip XXXX.tar.zip
docker load < XXXX.tar
```
**INSIDE the workspace container, Make sure that the `public/` and the `storage/logs/` folders are  mod 755 and own by user 1000:1000**
```bash
cd /home/it/PEGA_Projects/ConsumptiveMaterialWebsite
```
```bash
cp .env.server /home/it/PEGA_Projects/ConsumptiveMaterialWebsite/.env
```
```bash
cd /home/it/PEGA_Projects/MultiProjectLaradock
```
```bash
cp docker-compose-server.yml /home/it/PEGA_Projects/MultiProjectLaradock/docker-compose.yml
``` 
```bash
docker compose up -d nginx workspace redis mssql meilisearch
``` 
```bash
docker exec -it <laradock-workspace container id> /bin/bash
```
```bash
composer dump-autoload
```
```bash
php artisan storage:link
```
```bash
php artisan key:generate
```
```bash
php artisan migrateAll
``` 
```bash
php artisan config:clear
``` 
```bash
yarn run clear:babel-cache
``` 
```bash
npx mix --production
```

## Scheduled Tasks
To set up scheduled tasks on a new server container, follow the Laravel documentation:
https://laravel.com/docs/8.x/scheduling#running-the-scheduler
```bash
docker exec -it <laradock-workspace container id> /bin/bash
```
```bash
crontab -e
```
Add the following line to the crontab file:
```bash
* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
```
Since we `docker exec` into the container, the `{/path-to-your-project}` should be `/var/www`.

### ------------- Now all the services should be up and running ------------- 

## Manually Update the Website in Production server
```bash
cd /home/it/PEGA_Projects/ConsumptiveMaterialWebsite
```
```bash
git pull
```
```bash
git checkout --theirs .
``` 
```bash
cp .env.server /home/it/PEGA_Projects/ConsumptiveMaterialWebsite/.env
```
```bash
git add . && git commit -m "server"
```
**NEVER push to repo, only pull in the production server**
```bash
docker exec -it <laradock-workspace container id> /bin/bash
```
```bash
php artisan config:clear
exit
```
### ------------- Now the website should be updated -------------
## Services
This project depends on the following services:
```bash
docker compose up -d nginx workspace redis mssql meilisearch
```

## Useful Laravel Commands
These commands are used within the workspace container:
```bash
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
```

## Database Migrations
To migrate a single file (table):
```bash
php artisan migrate --path=/database/migrations/2022_12_21_165804_create_bulletins_table.php
```

## Maintenance Mode
To put the application into maintenance mode:
```bash
php artisan down --secret="someKey" --render="errors::503"
```
Then IT can access the website using the URL:
```
https://ptwsiteservice01.pega.corp.pegatron/someKey
```

## Telescope
After logging in, you can access the Telescope dashboard at:<br/>
https://ptwsiteservice01.pega.corp.pegatron/telescope

## Environment Configuration for Production
Under intra network, inside the project folder:
```bash
cd /home/it/PEGA_Projects/ConsumptiveMaterialWebsite
cp .env.server /home/it/PEGA_Projects/ConsumptiveMaterialWebsite/.env
```

## How to Add a New Database for a New Department/Factory
1. Use SSMS (SQL Server Management Studio) to New a Database<br/>
   ⚠️**Remember to add a trailing ` Consumables management` after the name**⚠️<br/>
   ⚠️**DO NOT PUT ANY `_` IN NAME**⚠️<br/>
   <img width="246" alt="SSMS add new database" src="https://github.com/user-attachments/assets/08fe81a2-337a-46ea-a5dd-4040530a37ae" />
3. Add the Whole DB Name to [Config File](./config/database_list.php)
4. Run migration inside workspace container.<br/>
   Since it's in production enviornment, you should type `YES` or `Y` to confirm the migration for every database when prompted.
   ```bash
   php artisan migrateAll
   ```
5. 
