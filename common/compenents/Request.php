<?php

namespace common\compenents;

/**
 * Class Request
 * @package common\compenents
 */
class Request extends \yii\web\Request{
    public function get_post($name = null,$defaultValue = null)
    {
        if($name === NULL)
            return \yii\helpers\ArrayHelper::merge($this->get(),$this->post());
        else
            return $this->get($name) ?: $this->post($name,$defaultValue);
    }

    public function post_get($name = null,$defaultValue = null)
    {
        if($name === NULL)
            return \yii\helpers\ArrayHelper::merge($this->get(),$this->post());
        else
            return $this->post($name) ?: $this->get($name,$defaultValue);
    }
}