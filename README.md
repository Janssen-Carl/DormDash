# DormDash

## Run with Docker

From the project root, open a terminal and run:

```bash
cd database/draftImplementation/draft2
docker compose up --build
```

Then open:

http://localhost:8000

---------------------------------------------------------------------------------------- --
Run current project:

```bash
cd ./source
docker-compose up -d db

Other tab
cd ./source
php artisan serve

Other tab
cd ./source
npm run dev
```
Then open http://localhost:8000

To stop the container:

```bash
docker compose down
```

To stop other tabs:

```bash
ctrl + c 
```


First time install for ui shits:
```bash
composer require blade-ui-kit/blade-icons    
composer require blade-ui-kit/blade-heroicons

composer require livewire/livewire   
composer require livewire/flux   
```
