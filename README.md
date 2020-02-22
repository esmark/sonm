# СОНМ

## Установка


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
bin/migrate
```

В случае, если будут проблеммы с доступами к файлам, то нужно обнулить кеш
```    
bin/clear_cache
```

## Гео-базы

### ФИАС

Создать в папку `/var/fias` и скопировать туда файлы нужных регионов `ADDROB*.DBF` и `SOCRBASE.DBF`, которые можно получить из архива `fias_dbf.rar` который можно скачать с сайта https://fias.nalog.ru/Updates 

Для импортирования данных ФИАС в БД, выполнить команду:

```
bin/console app:geo:fias-update
```

### Данные о численности населения по городам

После загрузки данных из фиас, рекомендуется загрузить данные о популяции.
Это позволит в формах автокомплита городов подгружать списки более походящими.

```
bin/console app:geo:population-update
``` 

### MaxMind GeoLite2

Для распознования по IP адресу, зарегистрироваться на https://dev.maxmind.com/geoip/geoip2/geolite2/ 
и скачать файл `GeoLite2-City.mmdb`, который нужно будет расположить по данному пути: `/var/MaxMind/GeoLite2-City.mmdb`  

Настройка веб-сервера
---------------------

Пример конфига для nginx находится тут `doc/nginx-sonm.conf`

Для Microsoft IIS конфиг `web.config` уже находится в папке `public` 

Для `apache` @todo

В режиме разработки можно запускать проект утилитой symfony:

```
symfony serve
``` 
 

Запуск в Docker
---------------

Пока только в режиме разработки!

Получение кода:
```
git clone https://github.com/esmark/sonm.git
cd sonm
```

Инициализация приложения (займёт примерно 10 минут):

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
