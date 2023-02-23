## Introduction

This app demonstrates how registered "crmUser" can register at CRM via Laravel API using Vue Form on frontend.

After user is registered at CRM via Laravel API, user can repeat form Submit and all data will be saved in Logs 
on Laravel API side, same as on CRM side.

WARNING: the code for the last one, was already written, but not fully tested due to lack of time
NOTE: At the moment, frontend only work with one end point - https://yourdomain.com/api/signup/procform 

## Requirements

PHP 8.1, Node v18.7.0

## Installation
1. Clone repository and setup ``.env`` file
2. Create DB and set credentials
3. ```composer install```
4. ```npm install```
5. ```npm run dev```
6. php artisan migrate
7. ```php artisan serve --port=8001``` for Laravel API
8. In another CLI window ```php artisan serve --port=8002``` for CRM
9. Visit - Laravel API


