## Creating Docker containers
Start off by cloning the github repository by running command

```git clone git@github.com:lukebaker11/educake.git```

and change to use that directory (```cd educake```)

Build the docker image and run the environment in background mode using the commands:

```docker-compose build app```<br/>
```docker-compose up -d```

Run composer for the app and create the laravel key by running commands:

```docker-compose exec app composer install```<br/>
```docker-compose exec app php artisan key:generate```

Docker is using port 8000, so assuming you have created this locally the default URL is:

```http://localhost:8000```

If you want to destroy the containers there is probably a better way, but I do:

```docker ps```<br/>
Then for each process (there will be 3)<br/>
```docker kill <process number>```<br/>
```docker system prune -a```
