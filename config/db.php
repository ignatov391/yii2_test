<?php

return [
    'class' => 'yii\db\Connection',
    'dsn' => 'pgsql:host=postgres;dbname=db_yii2',
    'username' => 'root',
    'password' => 'password',
    'charset' => 'utf8',

    // Schema cache options (for production environment)
    //'enableSchemaCache' => true,
    //'schemaCacheDuration' => 60,
    //'schemaCache' => 'cache',
];
