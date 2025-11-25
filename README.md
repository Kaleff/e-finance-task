# Launching laravel application

> [!NOTE]
> I've used Laravel Sail approach for building an application Dockerized.
> To run laravel sail you need to utilise MAC, LINUX or Windows with WSL2 and a docker engine running.
> In case you are using Windows WSL2, make sure to mount this project repository in WSL2 and run from there.
> Further info is available here: https://laravel.com/docs/12.x/sail

1) Clone the repo

```
git clone https://github.com/Kaleff/e-finance-task.git
cd e-finance-task
```
2) Copy, configure the .env.example file and rename the copy to .env
```
cp .env.example .env
```

3) Run the application using SAIL, make sure the docker engine is running

```
docker compose up -d
```

4) Navigate to the back-end repository and  Run the composer installation in the project directory, stay in back-end repository

```
cd backend
docker run --rm -v ${PWD}:/app composer install
```

5) Generate APP_KEY for .env file

```
./vendor/bin/sail artisan key:generate
```


6) Run the migrations and seeders.
```
./vendor/bin/sail artisan migrate:refresh --seed
```

7) Navigate to the front-end and build front-end
```
cd ../frontend
npm i
npm run dev
```
8) Make sure that the project is running at [localhost](http://localhost)
9) Access phpMyAdmin at port 8080 [phpMyAdmin](http://localhost:8080) to view databases
10) Access front-end [Mailpit](http://127.0.0.1:3000) to view applicaton
## Run time commands
1) Run tests
```
./vendor/bin/sail artisan test
```
## Task notes
1) Things I have not done, there are lot of things that I would done if I had more time to polish and improve the code:
  - Privilege systems, for now every user can create, edit and delete everything
  - Infinite scroll
  - Drag and drop
  - Rich text editor
  - API resources, as for many api endpoints wouldn't make a huge difference
  - Stores for projects, tasks and comments, which I think is uneccessary and not productive
2) Docker notes. Breakdown of services in docker compose:
  - app (PHP 8.2+, Laravel) - part of the sail laravel image - service (laravel.test), image - (sail-8.4/app) ✔️
  - nginx (Web server) - part of the sail laravel image - service (laravel.test), image - (sail-8.4/app) ✔️
  - mysql (Database) - ✔️
  - node (For Nuxt dev server) - ✔️
  - phpMyAdmin (Optional, for convenience in dev mode) - ✔️

## Nice  to have

Sail Alias in ```~/.zshrc``` or ```~/.bashrc```
```
alias sail='sh $([ -f sail ] && echo sail || echo vendor/bin/sail)'
```
