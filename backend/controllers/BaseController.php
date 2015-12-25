<?php
/**
 * Created by PhpStorm.
 * User: chao
 * Date: 2015/11/27
 * Time: 13:56
 */
namespace backend\controllers;
use common\constants\CommonConstant;
use yii\base\InvalidParamException;

class BaseController extends \yii\rest\Controller{

    /**
     * ��������
     *
     * @return mixed
     */
    protected function parseParam()
    {
        return \yii::$app->request->post_get(CommonConstant::REQUEST_PARAM,[]);
    }

    /**
     * ǩ����֤
     */
    protected function checkSign()
    {

    }

    /**
     * ����token
     * @return bool|int
     */
    protected function parseToken()
    {
        $token = \yii::$app->request->post_get(CommonConstant::TOKEN_NAME);
        if($token === NULL)
            return false;
        return $user_id = 2;
    }

    /**
     * ����ʧ�ܷ������ݴ���
     *
     * @param int $code
     * @return string
     */
    protected function returnError($code) {
        //$message = static::parseCode($code);
        //��������״̬��
        $lang = \yii::$app->lang;
        $lang->load('error_message');
        $message = $lang->line($code);
        return $this->returnResult($code,NULL,$message);
    }

    /**
     * ����ɹ����ݴ���
     *
     * @param null $data
     * @return string
     */
    public function returnSuccess($data = NULL){
        return $this->returnResult(\common\constants\ErrorConstant::SUCCESS,$data);
    }

    /**
     * ���󷵻ش���
     * @param int $code
     * @param null $data
     * @param null $desc
     * @return string
     */
    public function returnResult($code,$data = NULL,$desc = NULL){
        if( ! is_int($code))
            throw new InvalidParamException('code not exists!');

        $_return[CommonConstant::RETURN_CODE] = $code;
        if( $data !== NULL)
            $_return[CommonConstant::RETURN_DATA] = $data;

        if($desc !== NULL)
            $_return[CommonConstant::RETURN_DESC] = $desc;

        return \yii\helpers\Json::encode($_return);
    }
}