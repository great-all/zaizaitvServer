<?php
/**
 * Created by PhpStorm.
 * User: chao
 * Date: 2015/12/2
 * Time: 19:13
 */
namespace common\helpers;
use yii\base\InvalidParamException;

class JsonHelper extends \yii\helpers\Json{

    public static function returnError($code) {
        $message = static::parseCode($code);
        return static::returnJson($code,NULL,$message);
    }

    public static function returnSuccess($data = NULL){
        return static::returnJson(\common\constants\ErrorConstant::SUCCESS,$data);
    }

    public static function returnJson($code,$data = NULL,$desc = NULL){
        if( ! is_int($code))
            throw new InvalidParamException('code not exists!');

        $_return['code'] = $code;
        if( $data !== NULL)
            $_return['data'] = $data;

        if($desc !== NULL)
            $_return['desc'] = $desc;

        return \yii\helpers\Json::encode($_return);
    }

    /**
     * 根据错误状态码解析错误提示信息
     * @param $code
     * @return string
     * @deprecate 转换到parseCode方法
     */
    private static function _parseCode($code)
    {
        include_once \yii::getAlias('@common/config/error.php');

        $lang = \yii::$app->language;
        include_once \yii::getAlias('@common/lang/'. $lang .'/error_message_lang.php');//待优化
        return isset($lang[$code]) ? $lang[$code] : null;
    }

    /**
     * 根据错误状态码 返回错误提示信息
     *
     * @param $code
     * @return mixed
     */
    private static function parseCode($code)
    {
        $lang = \yii::$app->lang;
        $lang->load('error_message');
        return $lang->line($code);
    }
}
