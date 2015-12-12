<?php
/**
 * Created by PhpStorm.
 * User: chao
 * Date: 2015/11/27
 * Time: 13:59
 */
namespace common\services;
class Service extends \yii\base\Model{

    /**
     * @return mixed
     * @throws \yii\base\InvalidConfigException
     */
    public static function getService(){
        static $_services = [];
        $class_name = static::className();
        if( ! isset($_services[$class_name]) || ! $_services[$class_name] instanceof Service)
            $_services[$class_name] = \yii::createObject($class_name);
        return $_services[$class_name];
    }
}