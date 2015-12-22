<?php
/**
 * Created by PhpStorm.
 * User: chao
 * Date: 2015/11/27
 * Time: 14:30
 */
namespace console\services;
use common\constants\TaskConstant;

/**
 * Class CreditsService
 * 处理加积分的任务
 * 根据不同的任务生产场景处理积分问题
 * @package common\services
 */
class CreditsService extends ConsoleService
{
    protected $_queue_service = null;

    public function  init()
    {
        $this->_queue_service = \common\services\queues\CreditsService::getService();
        parent::init();
    }

    /***
     * 处理积分任务
     */
    public function handleCredit()
    {
        if($_task = $this->getTask())//获取任务 并且存在时
        {
            $_creditService = \backend\services\users\CreditService::getService();
            if($_task['scebario'] === TaskConstant::REGISTER_TASK_SCEBARIO)
            {//如果是注册时添加积分 创建一条记录
                $_isRight = $_creditService->addCredit($_task['data']['user_id'],$_task['data']['credits']);
                if($_isRight === true)
                    //记录日志
                    return true;
                else
                    ;//记录错误日志
            }else{//根据用户积分数量
                $_isRight = $_creditService->updateCredit($_task['data']['user_id'],$_task['data']['credits']);
                if($_isRight === true)
                    //记录日志
                    return true;
                else
                    ;//记录错误日志
            }
        }
    }

    /**
     * 获取任务
     * @return bool
     */
    public function getTask()
    {
        $_task = $this->_queue_service->pop();
        return $_task === false ? false : $_task['body'];
    }
}