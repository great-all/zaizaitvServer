<?php
return [
    'id' => 'yii-console',
    'basePath' => dirname(__DIR__ ),
    'controllerNamespace' => 'console\controllers',
    'bootstrap' => ['log'],
    'components' => [
        'log' => [
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
       'config' => [
           'ConfigPaths' => ['@console'],
       ],
    ],
    //'params' => $params,
];
