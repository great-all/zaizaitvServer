<?php
/**
 * Created by PhpStorm.
 * User: chao
 * Date: 2015/11/27
 * Time: 14:30
 */
namespace common\services\queues;

class MailService extends QueueService
{
    protected $_queue_name = 'mail_queue';

}