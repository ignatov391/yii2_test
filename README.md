<p align="center">
    <a href="https://github.com/yiisoft" target="_blank">
        <img src="https://avatars0.githubusercontent.com/u/993323" height="100px">
    </a>
    <h1 align="center">Yii 2 Basic Данные о курсах валют</h1>
    <br>
</p>


DIRECTORY STRUCTURE
-------------------

      assets/             contains assets definition
      commands/           contains console commands (controllers)
      config/             contains application configurations
      controllers/        contains Web controller classes
      mail/               contains view files for e-mails
      models/             contains model classes
      runtime/            contains files generated during runtime
      tests/              contains various tests for the basic application
      vendor/             contains dependent 3rd-party packages
      views/              contains view files for the Web application
      web/                contains the entry script and Web resources



REQUIREMENTS
------------

The minimum requirement by this project template that your Web server supports PHP 7.2.0.


INSTALLATION
------------

### Install via Composer

If you do not have [Composer](http://getcomposer.org/), you may install it by following the instructions
at [getcomposer.org](http://getcomposer.org/doc/00-intro.md#installation-nix).

You can then install this project template using the following command:

~~~
composer i
~~~

Затем нужно выполнить миграции (предварительно нужно настроить соединение с базой данных):

~~~
yii migrate
~~~

Now you should be able to access the application through the following URL, assuming `basic` is the directory
directly under the Web root.

~~~
http://localhost/
~~~

### Install with Docker

Update your vendor packages

    docker-compose run --rm php composer update --prefer-dist
    
Run the installation triggers (creating cookie validation code)

    docker-compose run --rm php composer install    
    
Start the container

    docker-compose up -d
    
You can then access the application through the following URL:

    http://127.0.0.1:8000

На случае необходимости добавлен контейнер adminer:

    http://127.0.0.1:8080

**NOTES:** 
- Minimum required Docker engine version `17.04` for development (see [Performance tuning for volume mounts](https://docs.docker.com/docker-for-mac/osxfs-caching/))
- The default configuration uses a host-volume in your home directory `.docker-composer` for composer caches


CONFIGURATION
-------------

### Database

Edit the file `config/db.php` with real data, for example:

```php
return [
    'class' => 'yii\db\Connection',
    'dsn' => 'pgsql:host=localhost;dbname=db_yii2',
    'username' => 'root',
    'password' => '1234',
    'charset' => 'utf8',
];
```

**NOTES:**
- Yii won't create the database for you, this has to be done manually before you can access it.
- Check and edit the other files in the `config/` directory to customize your application as required.

**ЗАМЕЧАНИЯ:**
- Для автозаполнения таблицы необходимо выполнить команду:

```
yii currencies/index
```

Вдальнейшем эту команду можно добавить в cron, чтобы собиралась история. Вначале нужно будет указать аболютный путь.

REST-API
------

**СПИСОК ВСЕХ ВАЛЮТ**

Для получения полного списка валют необходимо обратиться к адресу:

```
$ curl "Accept:application/json" http://localhost:8000/currencies?access-token=099-token
```

Также для метода доступны следующие параметры, все необязательные:
Для получения истории изменений (если не указан диапазон дат - вернётся история за всё время):

    expand=currencyHistories

Дата, от с которой вернётся история. Рекоммендован формат yyyy-MM-dd hh:mm:ss:

    date-min=

Дата, по которую вернётся история. Рекоммендован формат yyyy-MM-dd hh:mm:ss:

    date-max=

Пример полного запроса:

```
http://docker.local:8000/currencies/?access-token=099-token&date-min=2020-05-25&date-max=2020-05-25&expand=currencyHistories
```

**ДЕТАЛЬНЫЙ ЗАПРОС ДЛЯ ВАЛЮТЫ**

Для отправки детального запроса необходимо передать параметр remoteID:

```
http://docker.local:8000/currencies/<remoteID>?access-token=099-token&expand=currencyHistories
```

Дополнительные параметрый аналогичны предудещему запросу.
Пример полного запроса:

```
http://docker.local:8000/currencies/R01565?access-token=099-token&date-min=2020-05-25&date-max=2020-05-25&expand=currencyHistories
```

**ВНИМАНИЕ!**

- Access-token в примерах указан тестовый, в качестве примера.
- Метод аутентификации реализован самый простой. Не рекоммендуется использовать на реальных проектах.