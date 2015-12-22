<?php
/**
 * Created by PhpStorm.
 * User: chao
 * Date: 2015/12/20
 * Time: 10:03
 */
namespace console\controllers;
use console\services\CreditsService;
class CreditsController extends \yii\console\Controller
{

    public function actionAddCredits()
    {
        CreditsService::getService()->handleCredit();
    }

    public function actionTest()
    {
        \yii::$app->queue->push(['scebario'=> 'register','data'=>['user_id' => 739,'credits' => 500]],'credits_queue');
        echo 'ok';
    }
}