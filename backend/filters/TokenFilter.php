<?php
/**
 * Created by PhpStorm.
 * User: chao
 * Date: 2015/12/2
 * Time: 15:03
 */

namespace backend\filters;
use backend\services\users\TokenService;

class TokenFilter extends \yii\base\ActionFilter
{
     public function beforeAction($action)
     {
         $_token = \yii::$app->request->post_get('token');

         //判断token是否合法
         $user_id = TokenService::getService()->getIdByToken($_token);

         if(is_int($user_id) && $user_id < 0)
             exit(\common\helpers\JsonHelper::returnError($user_id));

         $_POST['data']['user_id'] = $user_id;
         return true;
     }
}