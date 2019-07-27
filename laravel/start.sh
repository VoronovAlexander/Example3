composer install
php artisan migrate
php artisan db:seed
ln -s /home/laravel/storage/app /home/laravel/public/storage
php -S 0.0.0.0:8080 -t public