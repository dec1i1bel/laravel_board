# LBoard - доска объявлений

---

## Установка:
_Подразумевается, что настроен сервер, созданы хост и БД, установлен composer. Рекомендуемая версия php - 7.4_

- DocumentRoot сервера изменить на
```
/путь/от/корня/к/папке/проекта/public
```
например, /var/www/lboard/public_html/public

- клонировать репозиторий в корень сайта

- в корне создать файл .env - копию .env.example. в файле .env в значениях DB_HOST, DB_PORT, DB_DATABASE, DB_USERNAME, DB_PASSWORD указать параметры подключения.

- ставим зависимости
```
composer install
```
- генерируем ключ
```
php artisan key:generate
```
- накатываем миграции:

```
php artisan migrate
```
- из сидера заполняем категории в таблице categories:

```
php artisan db:seed --class=CategoriesSeeder
```

- линкуем storage в public:

```
php artisan storage:link
```

> Готово.
-----

### Использование

- после сохранения товара нужно чистить кеш, иначе товар не появится на главной

```cmd
php artisan cache:clear
```

- для теста корзины на детальной странице товара есть кнопка добавления в корзину

-----

### Добавляем админ-панель Laravel-admin
#### установка
```
php artisan vendor:publish --provider="Encore\Admin\AdminServiceProvider"
php artisan admin:install
```
#### использование
- заходим в <url проекта>/admin
- пользователь по умолчанию: admin / admin

---

## API

### http-авторизация:
- тип: Basic Auth,
- логин: demo,
- пароль: demo,
- http-метод: GET

### http-заголовки:
'Content-Type': 'application/json',
'Authorization': 'Basic ZGVtbzpkZW1v

### методы:
доступно получение данных в json:
- /api/posts - все объявления,
- /api/posts/{post_id} - объявление по id,
- /api/categories - все категории,
- /api/categories/{category_id} - категория: id, название и количество объявлений
- /api/category_posts/{category_id} - объявления категории

### тестовое клиентское приложение
/apiClient/index.html, каталог в репозитории - /apiClient

## Демо-пакет

[репозиторий в Gitlab DW](https://gitlab.ddemo.ru/v.balabanov/laravel_demo_package 'DW Gitlab')

_не работает в IE!_
