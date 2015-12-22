<?php
/**
 * Created by PhpStorm.
 * User: chao
 * Date: 2015/11/27
 * Time: 14:30
 */
namespace common\services\queues;

use yii\base\InvalidConfigException;

class QueueService extends \common\services\Service
{

    /**
     * 队列名
     * @var null
     */
    protected $_queue_name = 'test_queue';

    /**
     * 队列句柄
     * @var null
     */
    protected static $_queue_handler = null;

    /**
     * 队列名前缀
     * @var string
     */
    protected $_queue_prefix = '';

    /**
     * 入队列
     */
    public function init()
    {
        self::$_queue_handler = self::$_queue_handler === null ?  \yii::$app->queue : self::$_queue_handler;
        if(! self::$_queue_handler instanceof \yii\queue\RedisQueue)
            throw new InvalidConfigException('queue handler not exists!');

        parent::init();
    }

    /**
     * 入队列
     * @param $data
     * @param $delay
     */
    public function push($data,$delay = 0)
    {
        return self::$_queue_handler->push($data,$this->_queue_prefix .$this->_queue_name,$delay);
    }

    /**
     * 出队列
     * @return mixed
     */
    public function pop()
    {
        return self::$_queue_handler->pop($this->_queue_prefix .$this->_queue_name);
    }

    /**
     *
     */
    public function purge()
    {
        return self::$_queue_handler->purge($this->_queue_prefix .$this->_queue_name);
    }

    public function release($data, $delay = 0)
    {
        $message = ['queue' => $this->_queue_prefix .$this->_queue_name,'body' => $data];
        return self::$_queue_handler->release($message,$delay);
    }

    public function delete($data)
    {
        $message = ['queue' => $this->_queue_prefix .$this->_queue_name,'body' => $data];
        return self::$_queue_handler->delete($message);
    }


}