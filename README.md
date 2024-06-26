# Сервер для сканера штрихкодов

Приложение состоит из 3х частей:

## 1. TCP-сервер
Сервер слушает подключения от сканера штрихкодов и принимает данные в форматах:
- <штрих-код>
- <штрих-код>,<количество>
 
Запускается следующей командой:

``` bash
docker run --network=barcoder_dev-net \
-p <IP-адрес интерфейса>:<порт>:<порт> \
--rm -it -w /var/www -v $(pwd)/www:/var/www php-cli php server.php start
```

## 2. Websocket сервер
Предназначен для получения приходящих штрих-кодов в реальном времени
``` bash
docker run --network=barcoder_dev-net \
-p <IP-адрес интерфейса>:<порт>:<порт> \
--rm -it -w /var/www -v $(pwd)/www:/var/www php-cli php websocket.php start
```
Демо-клиент для подлкючения к серверу расположен по адресу `/demo`

## 3. REST-api
Доступ к api осуществляется по адресу `/api/` 

[Swagger](https://barcoder.ds4.ru/swagger/) документация

