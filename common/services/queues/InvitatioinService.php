<?php
/**
 * Created by PhpStorm.
 * User: chao
 * Date: 2015/11/27
 * Time: 14:30
 */
namespace common\services\queues;

class InvitationService extends QueueService
{
    protected $_queue_name = 'invitation_queue';

}