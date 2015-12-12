<?php
/**
 * Created by PhpStorm.
 * User: chao
 * Date: 2015/11/27
 * Time: 14:30
 */
namespace common\services;

use yii\base\InvalidConfigException;
use common\constans\ErrorConstant;

class VerifyCodeService extends Service
{
    //mob 组件
    private static $mobCompenent = null;

    public function init()
    {
        $this->_init();
        parent::init();
    }

    /**
     * 自定义初始化组件方法
     * @throws InvalidConfigException
     */
    private function _init()
    {
        if( ! (static::$mobCompenent instanceof \common\compenents\MobileCode))
        {
            //注册mob组件
            $_conf = [
                'class' => \common\compenents\MobileCode::className(),
            ];
            $_conf = array_merge($_conf,\common\helpers\CommonHelper::loadConfig('mobConf'));

            static::$mobCompenent = \yii::createObject($_conf);
        }
    }

    /**
     * 校验验证码
     * @param string $mobile
     * @param string $code
     * @return mixed
     */
    public function checkCode($mobile,$code)
    {
        //检验手机号的合法性
        if(($_isRight = $this->_checkMobile($mobile)) !== true) return $_isRight;
        //校验验证码的合法性
        if(($_isRight = $this->_checkCode($code)) !== true) return $_isRight;

        //调用第三方接口校验
        $_return = static::$mobCompenent->checkCode($mobile,$code);
        if($_return === true) return true;

        return ErrorConstant::CODE_CHECKED_FAILED;
    }

    /**
     * 手机号格式校验
     * @param string $mobile
     * @return bool|int
     */
    private function _checkMobile($mobile)
    {
        return \common\helpers\MobileHelper::isMobile($mobile) ? true : ErrorConstant::MOBILE_NOT_VALIDITY;
    }

    /**
     * 校验验证码格式
     * @param string $code
     * @return bool|int
     */
    private function _checkCode($code)
    {
        return ! empty($code) ? true : ErrorConstant::CODE_NOT_VALIDITY;
    }
}