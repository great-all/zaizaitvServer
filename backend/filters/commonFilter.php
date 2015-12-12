<?php
/**
 * Created by PhpStorm.
 * User: chao
 * Date: 2015/12/2
 * Time: 15:03
 */

namespace backend\filters;


use yii\base\ActionFilter;

class commonFilter extends \yii\base\ActionFilter
{
     public function beforeAction($action) {
         var_dump($action->controller->module->id);
     }

    public function afterAction($action,$result) {
        var_dump($result);exit;
    }
}