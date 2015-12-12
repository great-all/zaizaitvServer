<?php
/**
 * Created by PhpStorm.
 * User: chao
 * Date: 2015/12/2
 * Time: 17:18
 */
namespace backend\events;
class LoginEvent extends \yii\base\Event {
    public $userId;
    public $loginTime;
    public $loginIp;
    public $clientType;
}