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
