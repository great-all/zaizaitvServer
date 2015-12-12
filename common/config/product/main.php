<?php
return [
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=localhost;dbname=zaizai', // MySQL, MariaDB
            'username' => 'root', //���ݿ��û���
            'password' => 'root', //���ݿ�����
            'charset' => 'utf8',
            'tablePrefix'  => 'tbl_',
        ],
        'redis' => [
            'class' => 'yii\redis\Connection',
            'hostname' => 'localhost',
            'port' => 6379,
            'database' => 0,
        ],

        'mongodb' => [
            'class' => '\yii\mongodb\Connection',
            'dsn' => 'mongodb://zaizai:zaizai@123.56.160.211:27017/zaizai',
        ],
    ],
];
