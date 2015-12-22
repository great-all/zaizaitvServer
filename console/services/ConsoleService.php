<?php
/**
 * Created by PhpStorm.
 * User: chao
 * Date: 2015/11/27
 * Time: 13:59
 */
namespace console\services;

class ConsoleService extends \common\services\Service{

    /**
     * 保存实例化过的service
     * @var array
     */
    protected static $_services = [];

    /**
     * @return mixed
     * @throws \yii\base\InvalidConfigException
     */
    public static function getService(){
        $class_name = static::className();
        if( ! isset(static::$_services[$class_name]) || ! static::$_services[$class_name] instanceof Service)
            static::$_services[$class_name] = \yii::createObject($class_name);
        return static::$_services[$class_name];
    }
}