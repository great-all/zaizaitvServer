<?php
/**
 * Created by PhpStorm.
 * User: chao
 * Date: 2015/12/9
 * Time: 15:17
 */
namespace backend\models\mongodb;

class SignModel extends \yii\mongodb\ActiveRecord
{
    public static function collectionName()
    {
        return 'user_sign';
    }

    public function attributes()
    {
        return ['_id','user_id','status','create_time'];
    }

    public function beforeSave($insert)
    {
        //重载部分
        $this->_beforeSave($insert);
       return parent::beforeSave($insert);
    }

    /**
     * 重载插入数据操作
     * @param bool $insert
     */
    private function _beforeSave($insert)
    {
        //插入前添加默认属性
        if($insert === true)
        {
            $this->setAttribute('create_time',\common\helpers\DateHelper::now());
            $this->setAttribute('status',1);
        }
    }

}