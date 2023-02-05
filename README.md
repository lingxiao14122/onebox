# Onebox
## The project
Onebox is a final year project.
The ultimate goal is to help e-commerce sellers manage stock from one single platform.
This project is built with Laravel and tailwind, and deployed on GCP https://oneboxapp.tech (instance deleted but a Image is kept)
### Features
- Login
- Dashboard
- CURD Product/Item
- Record Transactions (track stock quantity moving)
- Sync with Lazada (stock count will be syncronised on both ways)
- Forecast stock-out using existing depletion rate and provide UI and email alerts
- Manage user accounts
## Local setup
```
git clone https://github.com/lingxiao14122/onebox.git
cd onebox
composer install
cp .env.example .env
php artisan key:generate
create database in mysql
npm install
symbolic link php artisan storage:link
php artisan migrate:fresh --seed
```
## Running
```
npm run build
./mailhog (check .env port 1025)
php artisan queue:listen
```
## Screenshots
![](docs/login.png)
![](docs/dashboard.png)
![](docs/items.png)
![](docs/item_detail.png')
![](docs/transactions.png)
![](docs/new_transaction.png)
![](docs/integration.png)
![](docs/integration_setting.png)
![](docs/forecast.png)
![](docs/users.png)

