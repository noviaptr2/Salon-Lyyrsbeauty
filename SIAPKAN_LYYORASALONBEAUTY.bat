@echo off
chcp 65001 >nul
cd /d "%~dp0"
title Setup LyyoraSalonBeauty
 echo ============================================
echo   MENYIAPKAN LYYORASALONBEAUTY
echo ============================================
echo.
if not exist .env (
  echo File .env tidak ditemukan. Salin .env.example menjadi .env terlebih dahulu.
  pause
  exit /b 1
)
if not exist vendor\autoload.php (
  echo Menginstal dependency Laravel. Pastikan Composer sudah terpasang dan internet aktif...
  call composer install
  if errorlevel 1 (echo Composer gagal. Pastikan Composer sudah terpasang. & pause & exit /b 1)
)
echo Menjalankan migration database...
call php artisan migrate --force
if errorlevel 1 (echo Migration gagal. Periksa MySQL/Laragon dan database lyyorasalon. & pause & exit /b 1)
echo Membersihkan cache Laravel...
call php artisan optimize:clear
if errorlevel 1 (echo Cache tidak dapat dibersihkan, tetapi migration sudah dijalankan. & pause & exit /b 1)
echo.
echo Selesai! Jalankan website dengan file MULAI_WEBSITE.bat.
pause
