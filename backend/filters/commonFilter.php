<?php
/**
 * Created by PhpStorm.
 * User: chao
 * Date: 2015/12/2
 * Time: 15:03
 */

namespace backend\filters;
use backend\services\users\TokenService;
use common\constants\CommonConstant;

class commonFilter extends \yii\base\ActionFilter
{
    public function beforeAction($action)
    {
        $_token = \yii::$app->request->post_get(CommonConstant::TOKEN_NAME);

        //ÅÐ¶ÏtokenÊÇ·ñºÏ·¨
        $user_id = TokenService::getService()->getIdByToken($_token);

        if(is_numeric($user_id) && $user_id > 0)
            $_POST[CommonConstant::REQUEST_PARAM]['user_id'] = $user_id;
        else
            $_POST[CommonConstant::REQUEST_PARAM]['user_id'] = null;
        return true;
    }
}