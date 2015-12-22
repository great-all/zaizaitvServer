<?php
namespace backend\behaviors;
use backend\services\users\UserService;
use common\constants\TaskConstant;
use backend\tasks\CreditTask;
use backend\tasks\ForumTask;
use backend\tasks\InvitationCodeTask;
use yii\helpers\ArrayHelper;
use common\services\queues\CreditsService;
use common\services\queues\ForumService;
use common\services\queues\InvitationService;
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
        $creditTask = new CreditTask();
        $creditTask->task_name = TaskConstant::REGISTER_CREDITS_TASK;//任务名称
        $creditTask->scebario  = TaskConstant::REGISTER_TASK_SCEBARIO;//场景
        $creditTask->data = ['user_id'=>$event->userId,'credits' => 500];
        CreditsService::getService()->push(ArrayHelper::toArray($creditTask));

        //处理耕币
        $forumTask = new ForumTask();
        $forumTask->task_name = TaskConstant::REGISTER_FORUM_TASK;
        $forumTask->scebario  = TaskConstant::REGISTER_TASK_SCEBARIO;//场景
        $forumTask->data = ['user_id'=>$event->userId,'forum' => 500];
        ForumService::getService()->push(ArrayHelper::toArray($forumTask));

        //用户邀请码
        $invitationTask = new InvitationCodeTask();
        $invitationTask->task_name = TaskConstant::REGISTER_FORUM_TASK;
        $invitationTask->scebario  = TaskConstant::REGISTER_TASK_SCEBARIO;//场景
        $invitationTask->data = ['user_id'=>$event->userId];
        InvitationService::getService()->push(ArrayHelper::toArray($invitationTask));
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