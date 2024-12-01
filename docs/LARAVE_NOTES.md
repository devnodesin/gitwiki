
# Laravel Notes

## Init Laravel Project

```
composer create-project laravel/laravel hub
cd hub

npm install
npm run build
```

## Clear Cache

```
php artisan optimize:clear
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
composer dump-autoload
```

## Setup Permissions for directories
```
sudo chmod -R 0777 storage/
sudo chmod -R 0777 bootstrap/cache
```
