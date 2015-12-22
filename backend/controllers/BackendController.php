<?php
/**
 * Created by PhpStorm.
 * User: chao
 * Date: 2015/11/27
 * Time: 13:56
 */
namespace backend\controllers;
use common\constants\CommonConstant;

class BackendController extends \common\controllers\CommonController{
    /**
     * @return array
     *
     */
    public function behaviors()
    {
        return \yii\helpers\ArrayHelper::merge(parent::behaviors(), [
            [
                'class' => \backend\filters\CommonFilter::className(),
            ],
        ]);
    }

    /**
     * 解析参数
     *
     * @return mixed
     */
    protected function parseParam()
    {
        return \yii::$app->request->post_get(CommonConstant::REQUEST_PARAM,[]);
    }

    /**
     * 签名认证
     */
    protected function checkSign()
    {

    }

    /**
     * 解析token
     * @return bool|int
     */
    protected function parseToken()
    {
        $token = \yii::$app->request->post_get(CommonConstant::TOKEN_NAME);
        if($token === NULL)
            return false;
        return $user_id = 2;
    }
}