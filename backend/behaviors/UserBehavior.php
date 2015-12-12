<?php
namespace backend\behaviors;
use backend\services\users\UserService;
/**
 * Class UserBehavior
 * @package backend\behaviors
 */
class UserBehavior extends \yii\base\Behavior{
    public function events() {
        return [
            UserService :: AFTER_LOGIN_EVENT => 'afterLogin',
            UserService :: BEFORE_LOGIN_EVENT => 'beforeLogin',
            UserService :: AFTER_REGISTER_EVENT => 'afterRegister',
            UserService :: AFTER_SIGN_EVENT => 'afterSign',
            UserService :: AFTER_CHANGE_PASSWORD_EVENT => 'afterChangePass',
            UserService :: AFTER_FIND_PASSWORD_EVENT => 'afterFindPass',
        ];
    }

    /**
     * 用户登录后置事件处理句柄
     * @param object $event
     */
    public function afterLogin($event)
    {

    }

    /**
     * 用户登录后置事件处理句柄
     * @param object $event
     */
    public function beforeLogin($event)
    {

    }


    /**
     * 用户注册事件处理句柄
     * @param object $event
     */
    public function afterRegister($event)
    {
        //处理积分

        //处理耕币

        //用户邀请码
    }

    /**
     * 用户签到事件处理句柄
     * @param object $event
     */
    public function afterSign($event)
    {

    }

    /**
     * 修改密码后置事件
     * @param $event
     */
    public function afterChangePass($event)
    {

    }

    /**
     * 找回密码后置事件
     * @param $event
     */
    public function afterFindPass($event)
    {

    }
}