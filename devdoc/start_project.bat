@echo off
title Start Smart Pantry Project

echo ===============================
echo Starting XAMPP Services...
echo ===============================

cd /d C:\xampp
start apache_start.bat
start mysql_start.bat

timeout /t 5 > nul

echo ===============================
echo Opening Project in Browser...
echo ===============================

start http://localhost/your-project/public

exit
