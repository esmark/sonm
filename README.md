СОНМ
====


Установка
---------

Создать БД PostgreSQL

```
sudo -u postgres createuser <username>
sudo -u postgres createdb <db>
sudo -u postgres psql

alter user <username> with encrypted password '<password>';
grant all privileges on database <db> to <username>;
exit;
```

Получение кода
```
git clone https://github.com/esmark/sonm.git
cd sonm
cp .env .env.local
```

Отредактировать конфиг `.env.local`
1. Обязательно указать произвольную строку для APP_SECRET
2. Прописать доступы к БД в параметре DATABASE_URL 
```
nano .env.local
```

Установка зависимых библиотек
```
composer i
```

Обновление схемы БД
```
bin/console doctrine:migrations:migrate --no-interaction
```

В случае, если будут проблеммы с доступак к файлам, то нужно обнулить кеш
```    
bin/clear_cache
```

Запуск в Docker
---------------

Пока только в режиме разработки!

Получение кода:
```
git clone https://github.com/esmark/sonm.git
cd sonm
```

Инициализация приолжения:

```
make init
```

Запуск докера:
```
make up
```

По умолчанию веб порт задан 8089, открывать проект по адресу:

```
http://localhost:8089/
``` 

Остановка докера:
```
make down
```

Если нужно изменить порт, тогда запускать проект так:
```
make down
WEB_PORT=80 make up
```
в этом случае, проект будет доступен на 80 порту:
```
http://localhost/
``` 

Или можно запустить установку с нуля одной строчкой:

Для Windows:
```
git clone https://github.com/esmark/sonm.git;cd sonm;make init;make up;start http://localhost:8089/
```

Для Linux:
```
git clone https://github.com/esmark/sonm.git;cd sonm;make init;make up;xdg-open http://localhost:8089/
```

Дополнительные команды
----------------------

Посмотреть список всех пользователей:
```
bin/console user:list
```

Назначить роль пользователю: (ROLE_SUPER_ADMIN)
```
bin/console user:role:promote <username> <role>
```

Для запуска команд в докере, нужно перед командой написать: `docker-compose run php` итого формат будет такой: 

```
docker-compose run php <command>
# например:
docker-compose run php bin/console user:list
# или более короткая запись:
make cli c=user:list
```
