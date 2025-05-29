@echo off
echo ================================
echo Laravel Project Optimization
echo ================================

:: Navigate to Laravel root (if needed, change this)
cd /d %~dp0

echo.
echo Clearing Laravel cache...
php artisan optimize:clear

echo.
echo Clearing config, route, view cache...
php artisan config:clear
php artisan route:clear
php artisan view:clear

echo.
echo Deleting old log files...
del /q storage\logs\*.log

echo.
echo Caching config, routes, views...
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

echo.
echo ✅ Optimization Complete!
pause
