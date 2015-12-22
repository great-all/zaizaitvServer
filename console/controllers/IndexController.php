<?php
/**
 * Created by PhpStorm.
 * User: chao
 * Date: 2015/12/20
 * Time: 10:03
 */
namespace console\controllers;
class IndexController extends \yii\console\Controller{

    public function actionIndex($id = 0)
    {
        echo $id;
    }
}