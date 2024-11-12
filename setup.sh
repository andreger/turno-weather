#!/bin/bash
cp ./api-laravel/.env.example ./api-laravel/.env
docker exec turno-api-laravel composer install 
docker exec turno-api-laravel php artisan migrate:fresh --seed