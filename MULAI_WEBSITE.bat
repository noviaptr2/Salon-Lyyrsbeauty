@echo off
chcp 65001 >nul
cd /d "%~dp0"
title LyyoraSalonBeauty
if not exist vendor\autoload.php (
 echo Dependency belum siap. Jalankan SIAPKAN_LYYORASALONBEAUTY.bat terlebih dahulu.
 pause
 exit /b 1
)
php artisan serve
pause
