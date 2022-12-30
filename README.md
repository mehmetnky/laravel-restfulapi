# LARAVEL RESTFUL API
[![Run in Postman](https://run.pstmn.io/button.svg)](https://app.getpostman.com/run-collection/5524479-ee77a50d-1000-4de7-8e0e-cadb929c5d13?action=collection%2Ffork&collection-url=entityId%3D5524479-ee77a50d-1000-4de7-8e0e-cadb929c5d13%26entityType%3Dcollection%26workspaceId%3D33306f35-6ab0-45f1-ac64-0abf353b6b5a)

A RESTful API template uses Laravel Sanctum for authentication. 

## Installation Steps
- `cp .env.example .env`
-- Set your variables
- `composer install && composer update`
- `php artisan key:generate`
- `php artisan migrate`
- `php artisan db:seed`
-- 1 admin, 3 customer users and 50 orders created via Faker.
- `php artisan user:new --help`
-- Customer users can be registered with `/api/register` route but If more admin or customer users needed, this command can be executed.
