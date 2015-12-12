<?php
/**
 * Created by PhpStorm.
 * User: chao
 * Date: 2015/11/27
 * Time: 13:56
 */
namespace backend\controllers;

class BackendController extends \common\controllers\CommonController{
    private $param_model = 'data';
    private $token_model = 'token';

    protected function parseParam()
    {
        return \yii::$app->request->post_get($this->param_model);
    }

    protected function checkSign()
    {

    }

    protected function parseToken()
    {
        $token = \yii::$app->request->post_get($this->token_model);
        if($token === NULL)
            return false;
        return $user_id = 2;
    }
}