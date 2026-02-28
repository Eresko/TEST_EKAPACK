# EKAPACK Laravel Project

<p align="center">
<a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="300" alt="Laravel Logo"></a>
</p>

## Описание

EKAPACK — это тестовое задание на Laravel 12, обеспечивающее управление заказами и товарами через REST API.  
Используем Docker и Docker Compose.

---

## Структура Docker

Файл `docker-compose.yml` содержит четыре основных сервиса:

1. **ekapack** – PHP-контейнер с Laravel.
2. **ekapack_nginx** – Nginx для отдачи запросов.
3. **ekapack_postgres** – PostgreSQL база данных.
4. **ekapack_queue** – обработчик очередей Laravel.

---
## OPENAPI

 {url}/openapi#/

## Быстрый старт (Docker)

1. **Клонируем проект**

git clone <репозиторий>
cd test-app-ekapack

2. **Собираем контейнеры**

docker-compose build

3. **Запускаем контейнеры**

docker-compose up -d

4. **Устанавливаем зависимости**

docker exec -it laravel_php composer install

5. **Настройка окружения**

docker exec -it laravel_php cp .env.example .env

6. **Миграции**

docker exec -it laravel_php php artisan migrate

7. **Миграции**

docker exec -it laravel_php php artisan migrate

8. **Запускаем сиды**

docker exec -it laravel_php php artisan db:seed

9. **Очереди**
docker exec -it laravel_queue php artisan queue:work --sleep=3 --tries=3 --timeout=90

10. **Геренация OPENAPI**
docker exec -it laravel_php php artisan swagger:generate