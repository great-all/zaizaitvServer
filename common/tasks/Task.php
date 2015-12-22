<?php

namespace common\tasks;

/**
 * Class MobileCode
 * @package common\compenents
 */
class Task extends \yii\base\Object
{
    /**
     * 任务名称
     * @var
     */
    public $task_name;

    /**
     * 产生任务的场景
     * @var
     */
    public $scebario;

    /**
     * 与该任务相关的数据
     * @var array
     */
    public $data = [];
}