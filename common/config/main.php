<?php
return [
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],

        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
        ],

        'config' => [
            'class' => \common\compenents\Config::className(),
            'ConfigPaths' => ['@common'],
        ],

        'lang' => [
            'class' => \common\compenents\Lang::className(),
        ],
    ],
];
