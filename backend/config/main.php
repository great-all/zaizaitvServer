<?php
return [
    'id' => 'app-backend',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log'],
    'language' => 'zh_cn',
    'modules' => [],
    'components' => [
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
        ],

        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],

        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
        ],

        'request' => [
            'class' => 'common\compenents\Request',
        ],

        'errorHandler' => [
            'errorAction' => 'site/error',
        ],

        'config' => [
            'ConfigPaths' => ['@backend'],
        ],
    ],
    'params' => \common\helpers\CommonHelper::loadConfig('params'),
];
