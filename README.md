# Onebox

[http://oneboxapp.tech/](http://oneboxapp.tech/)

setup
```
mkdir oneboxapp.tech
sudo chown lingxiao14122:www-data oneboxapp.tech
cd oneboxapp.tech
git clone https://github.com/lingxiao14122/onebox.git .
mkdir storage/app/public/itemImages
sudo chown lingxiao3218:www-data storage/app/public/itemImages
sudo chown www-data:www-data /var/www/oneboxapp.tech/storage/framework

composer install
cp .env.example .env
php artisan key:generate
php artisan migrate:fresh --seed
php artisan storage:link
npm install
```

Running
```
npm run build
./mailhog
php artisan queue:listen
php artisan websockets:serve
```