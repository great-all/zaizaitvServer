<?php
/**
 * Created by PhpStorm.
 * User: chao
 * Date: 2015/12/2
 * Time: 17:18
 */
namespace backend\events;
class RegisterEvent extends \yii\base\Event {
    public $userId;

    public function addUser() {
        if(empty($this->userId))
            throw new \yii\base\InvalidParamException("The user id can not empty: {$this->userId}");
    }
}