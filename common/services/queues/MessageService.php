<?php
/**
 * Created by PhpStorm.
 * User: chao
 * Date: 2015/11/27
 * Time: 14:30
 */
namespace common\services\queues;

class MessageService extends QueueService
{
    protected $_queue_name = 'message_queue';

}