# Тестовое задание
Репозиторий содержит тестовый стенд для задания.  

Для работы необходим установленный docker, docker-compose.  
Для сборки и запуска используйте команду
```
docker-compose build && docker-compose up -d
```

После запуска фронт можно открыть по ссылке (http://127.0.0.1:8080), RabbitMQ (http://127.0.0.1:15672) (логин/пароль rmuser/rmpassword)  
В консоль кликхауса можно попасть командой
```
docker-compose exec clickhouse clickhouse-client
```

Таблицу для метрик можно создать командами:
```
CREATE DATABASE IF NOT EXISTS telemetry;
USE telemetry;

CREATE TABLE IF NOT EXISTS telemetry.metrics (
    `datetime` DateTime,
    `device` UInt32,
    `sensor` UInt16,
    `temperature` Float32
) ENGINE = MergeTree() 
PARTITION BY toYYYYMM(datetime)
ORDER BY
    (device, sensor);

```
> Не обязательно использовать именно такую структуру таблицы

! Контейнеры generator и consumer не запустятся тк команды которые ими выполняются - не реализованы, их надо будет реализовать в рамках тестового задания.