## Инструкция по запуску

- Устанавливаем проект с помощью composer install
- Настраиваем подключение к БД в файле .env
- Запускаем миграцию БД php artisan migrate:fresh
- Заполняем БД сидами php artisan db:seed

## Получение рабочего расписания

- Переходим по адресу http://localhost/schedule?startDate=2018-01-01&?endDate=2018-01-14&userId=1

## Получение нерабочего расписания

- Переходим по адресу http://localhost/schedule?startDate=2018-01-01&?endDate=2018-01-14&userId=1&vacation

## PS

- время корпоратива в расписании учитывал исходя из того, что данное событие распространяется только на одну дату, а не на период дат
- в течение одного дня может быть несколько событий в таблице specific_vacations
