<?php

namespace common\compenents;

/**
 * Class Request
 * @package common\compenents
 */
class Request extends \yii\web\Request{

    /**
     * ��ȡget post����
     * @param null $name
     * @param null $defaultValue
     * @return array|mixed
     */
    public function get_post($name = null,$defaultValue = null)
    {
        if($name === NULL)
            return \yii\helpers\ArrayHelper::merge($this->post(),$this->get());
        else
            return $this->get($name) ?: $this->post($name,$defaultValue);
    }

    /**
     * ��ȡpost get����
     * @param null $name
     * @param null $defaultValue
     * @return array|mixed
     */
    public function post_get($name = null,$defaultValue = null)
    {
        if($name === NULL)
            return \yii\helpers\ArrayHelper::merge($this->get(),$this->post());
        else
            return $this->post($name) ?: $this->get($name,$defaultValue);
    }
}