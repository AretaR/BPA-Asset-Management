@echo off
echo ================================================
echo BPA Asset Management System - Setup Script
echo ================================================
echo.

echo Step 1: Installing Composer dependencies...
call composer install

echo.
echo Step 2: Creating environment file...
if exist .env (
    echo .env file already exists. Skipping...
) else (
    copy .env.example .env
    echo .env file created from .env.example
)

echo.
echo Step 3: Generating application key...
php artisan key:generate

echo.
echo Step 4: Running database migrations...
php artisan migrate

echo.
echo Step 5: Seeding database...
php artisan db:seed

echo.
echo Step 6: Creating storage link...
php artisan storage:link

echo.
echo ================================================
echo Setup Complete!
echo ================================================
echo.
echo Default Login Credentials:
echo Email: admin@bpa.com
echo Password: password
echo.
echo Start the development server with:
echo php artisan serve
echo.
echo Then visit: http://localhost:8000
echo.
pause
