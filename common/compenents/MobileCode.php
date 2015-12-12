<?php

namespace common\compenents;
use \yii\base\InvalidParamException;

/**
 * Class MobileCode
 * @package common\compenents
 */
class MobileCode extends \yii\base\Component
{
    //接口地址
    private $api;

    private $appkey;

    private $zone = 86;

    /**
     * 设置请求地址
     * @param string $value
     */
    public function setApi($value)
    {
        $this->api = $value;
    }

    /**
     * 设置appkey
     * @param string $value
     */
    public function setAppkey($value)
    {
        $this->appkey = $value;
    }

    /**
     * 设置区号
     * @param $value
     */
    public function setZone($value)
    {
        $this->zone = $value;
    }

    public function checkCode($mobile,$code)
    {
        $_param = $this->_paramMerge($mobile,$code);

        //请求接口
        $_response = $this->postRequest($_param);

        //解析返回值
        $_return = \common\helpers\JsonHelper::decode($_response);
        if(is_array($_return) && $_return['status'] == 200)
            return true;
        else
            return false;

    }

    /**
     * 合并参数
     * @param string $mobile
     * @param string $code
     * @return mixed
     */
    private function _paramMerge($mobile,$code)
    {
        $_param['zone'] = $this->zone;
        //appkey
        if(! is_string($this->appkey) || $this->appkey === '')
            throw new InvalidParamException('Param for appkey error');
        else
            $_param['appkey'] = $this->appkey;

        //手机号
        if( ! \common\helpers\MobileHelper::isMobile($mobile))
            throw new InvalidParamException('Param for mobile error: '.$mobile);
        else
            $_param['phone'] = $mobile;

        //code
        if(empty($code))
            throw new InvalidParamException('Param for code error: '.$code);
        else
            $_param['code'] = $code;

        return $_param;
    }

    private function postRequest(array $params = [], $timeout = 30 ) {
        if( ! is_string($this->api) || $this->api === '')
            throw new InvalidParamException('Param for api error');

        $ch = curl_init();
        curl_setopt( $ch, CURLOPT_URL, $this->api);
        // 以返回的形式接收信息
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
        // 设置为POST方式
        curl_setopt( $ch, CURLOPT_POST, 1 );
        curl_setopt( $ch, CURLOPT_POSTFIELDS, http_build_query( $params ) );
        // 不验证https证书
        curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, 0 );
        curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, 0 );
        curl_setopt( $ch, CURLOPT_TIMEOUT, $timeout );
        curl_setopt( $ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/x-www-form-urlencoded;charset=UTF-8',
            'Accept: application/json',
        ) );
        // 发送数据
        $response = curl_exec( $ch );
        // 不要忘记释放资源
        curl_close( $ch );
        return $response;
    }
}