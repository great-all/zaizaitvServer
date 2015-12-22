<?php
namespace common\constants;

/**
 * 与任务相关的常量
 * 主要描述任务 名字
 * 任务生产时的场景
 * 任务队列名字
 * 以及任务名字
 * Class TaskConstant
 * @package common\constants
 */
class TaskConstant
{
    //任务队列名
//    const CREDITS_QUEUE_NAME = 'credits_queue';//加积分任务队列名
//    const FORUM_QUEUE_NAME   = 'forum_queue';//加耕币任务队列名
//    const INVITATION_QUEUE_NAME = 'invitation_queue';//生成邀请码任务队列名
//    const MAIL_QUEUE_NAME    = 'mail_queue';//发邮件任务队列名
//    const MESSAGE_QUEUE_NAME = 'message_queue';//发短信任务队列名

    //与用户注册相关的
    const REGISTER_CREDITS_TASK = 'register_credits';//注册任务名
    const REGISTER_FORUM_TASK   = 'register_forum';//加耕币任务名
    const REGISTER_INVITATION_TASK = 'register_invitation';//生成邀请码任务
    const REGISTER_TASK_SCEBARIO = 'register';//注册任务生产场景
}