# DormDash

## Run with Docker

From the project root, open a terminal and run:

```bash
cd database/draftImplementation/draft2
docker compose up --build
```

Then open:

http://localhost:8000

----------------------------------------------------------------------------------------
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

To access database 

create it first:
```
cd source
php artisan migrate
```

then use the seeder
Option 1: Run seeder alone
````
php artisan db:seed
````

Option 2: Fresh database + migrations + seeder
````
php artisan migrate:fresh --seed
````


Test Accounts Created:

Account 1: Customer

Username: johndoe

Email: john@example.com

Password: password123

Account 2: Vendor

Username: vendor_store

Email: vendor@example.com

Password: password123

Account 3-7: Random (5 factory users)

Username: auto-generated (e.g., user_abc123)

Email: auto-generated

Password: password


testing123