<?php
/**
 * Created by PhpStorm.
 * User: chao
 * Date: 2015/12/2
 * Time: 17:18
 */
namespace backend\events;
class FriendEvent extends \yii\base\Event {
    /**
     * 事件触发者的ID
     * @var
     */
    public $originator;

    /**
     * 事件接受者的
     * @var
     */
    public $friend;
}