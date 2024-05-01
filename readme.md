# Описание:
    API с использованием фреймворка symphony для внесения книг в базу данных с их авторами и издателями, редактирование их и получение в разных форматах
# Как запустить
    1. Настройте базу данных
    2. Настройка php:
        - sudo apt update
        - sudo apt install php
        - sudo apt install php-fpm (на данный момент момент версия php 8.3 в дальнейшем буду указывать именно ее)
        - sudo apt install composer
        - sudo apt install php-curl (Для более быстрой установки)
        - sudo apt install php-xml
        - sudo apt install php-mysql
    3.  Настройте Сервер:
            - Пример настройки сервера apache2 на ubuntu:
        Команды в терминале:
            - sudo apt update
            - sudo a2enmod proxy_fcgi setenvif
            - sudo a2enconf php8.3-fpm
        Затем нужно зайти в папку /etc/apache2/(в моем случае папка называлась sites-available) 
        и создать конфигурационный файл (пример лежит в корне под apacheConf.txt)
            - sudo a2ensite example.conf
            - sudo a2enmod rewrite 
            - composer update
            - sudo service apache2 restart
    4. Настроить файл .env (Указать DATABASE_URL и поменять APP_KEY)
    5. Запустить миграции - bin/console doctrine:migrations:migrate

# Доступные команды симфони
    - bin/console app:seed - создание тестовых данных в базе данных

# Доступные Маршруты:
    ! В Маршрутах происходит проверка authorization заголовка на соответствие значению APP_TOKEN
    ! Можно отправлять заголовок Accept:application/json, без него будут приходить ответы xml
## Получение информации об авторе

### 'PUT /createBook'
### Параметры URL
- 'authors[]': integer - Массив идентификаторов авторов книги
- 'name': string - Название книги
- 'year': integer - Год выпуска
- 'publisher_id': integer - Идентификатор издателя (не обязательный параметр)

### Пример запроса
http://example.com/createBook?authors[]=5&authors[]=7&name=ExampleName&year=2024&publisher_id=5

/getBooks checked

## Получение всех книг
### 'GET /getBooks'

### Пример запроса
http://example.com/getBooks

## Создание автора
### 'PUT /authorCreate'
### Параметры URL
- 'name': string - Имя автора
- 'surname': string - Фамилия автора

### Пример запроса
http://example.com/authorCreate?name=ExampleName&surname=ExampleSurname

## Удаление автора
### 'DELETE /authorDelete'
### Параметры URL
- 'author_id': integer - Идентификатор автора

### Пример запроса
http://localhost/authorDelete?author_id=1

## Удаление издателя и всех связанных книг
### 'DELETE /publisherDelete'
### Параметры URL
- 'publisher_id': integer - Идентификатор автора

### Пример запроса
http://localhost/publisherDelete?publisher_id=32

## Удаление автора
### 'DELETE /bookDelete'
### Параметры URL
- 'book_id': integer - Идентификатор книги

### Пример запроса
http://localhost/bookDelete?book_id=32

## Редактирование издателя
### 'PATCH /publisherUpdate'
### Параметры URL
- 'publisher_id': integer - Идентификатор книги
- 'name': string - Название издателя
- 'address': string - Адрес издателя
- 
### Пример запроса
http://localhost/publisherUpdate?publisher_id=34&name=Example&address=randomAdress
