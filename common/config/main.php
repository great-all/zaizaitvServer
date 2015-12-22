<?php
return [
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'modules' => [
        'debug' => 'yii\debug\Module',
    ],
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],

        'config' => [
            'class' => \common\compenents\Config::className(),
            'ConfigPaths' => ['@common'],
        ],

        'lang' => [
            'class' => \common\compenents\Lang::className(),
        ],
        'queue' => [
            'class' => \yii\queue\RedisQueue::className(),
        ],
    ],
    //'params' => \common\helpers\CommonHelper::loadConfig('params'),
];
