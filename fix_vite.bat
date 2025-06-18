@echo off
echo Fixing Vite Manifest Issue...
cd /d D:\SERVER\htdocs\messenger-app

echo 1/5: Installing dependencies...
npm install

echo 2/5: Clearing caches...
php artisan cache:clear
php artisan view:clear

echo 3/5: Building Vite assets...
npm run dev

echo 4/5: Verifying manifest file...
if exist public\build\manifest.json (
    echo Manifest file found! Success.
) else (
    echo Error: Manifest file still not found. Check for errors above.
)

echo 5/5: Starting Laravel server...
php artisan serve

echo Fix complete! Try accessing your app now.
pause
