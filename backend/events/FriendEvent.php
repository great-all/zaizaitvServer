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
     * �¼������ߵ�ID
     * @var
     */
    public $originator;

    /**
     * �¼������ߵ�
     * @var
     */
    public $friend;
}