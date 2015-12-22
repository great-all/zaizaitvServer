<?php
namespace backend\behaviors;
use backend\services\users\FriendService;
/**
 * Class UserBehavior
 * @package backend\behaviors
 */
class UserBehavior extends \yii\base\Behavior{
    public function events() {
        return [
            FriendService :: AFTER_ADD_FRIEND => 'afterAdd',
            FriendService :: AFTER_AGREE_FRIEND => 'afterAgree',
            FriendService :: AFTER_DISAGREE_FRIEND => 'afterDisagree',
            FriendService :: AFTER_RELEASE_FRIEND => 'afterRelease',
        ];
    }

    /**
     * 用户登录后置事件处理句柄
     * @param object $event
     */
    public function afterAdd($event)
    {

    }

    /**
     * 用户登录后置事件处理句柄
     * @param object $event
     */
    public function afterAgree($event)
    {

    }


    /**
     * 用户注册事件处理句柄
     * @param object $event
     */
    public function afterDisagree($event)
    {
        //处理积分

        //处理耕币

        //用户邀请码
    }

    /**
     * 用户签到事件处理句柄
     * @param object $event
     */
    public function afterRelease($event)
    {

    }
}