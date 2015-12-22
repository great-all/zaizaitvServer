<?php
return [
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=127.0.0.1;dbname=zaizai', // MySQL, MariaDB
            'username' => 'root',
            'password' => 'root',
            'charset' => 'utf8',
        ],
        'redis' => [
            'class' => 'yii\redis\Connection',
            'hostname' => '127.0.0.1',
            'port' => 6379,
            'database' => 0,
        ],

        'mongodb' => [
            'class' => '\yii\mongodb\Connection',
            'dsn' => 'mongodb://127.0.0.1:27017/zaizai',
        ],

        'queue' => [
            'redis' => [
                'scheme' => 'tcp',
                'host'   => '127.0.0.1',
                'port'   => 6379,
            ],
        ],
    ],
];
