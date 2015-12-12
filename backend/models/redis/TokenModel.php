<?php
/**
 * Created by PhpStorm.
 * User: chao
 * Date: 2015/12/9
 * Time: 11:12
 */
namespace backend\models\redis;
class TokenModel extends \yii\redis\ActiveRecord{

    const  EXPIRED_TIME = 3600;
    public function attributes()
    {
        return ['id', 'token', 'userId','create_time','update_time','status'];
    }

    public function scenarios()
    {
        return [
            self::SCENARIO_DEFAULT => ['id', 'token', 'userId','create_time','update_time','status'],
        ];
    }

    public function beforeSave($insert)
    {
        $this->_beforeSave($insert);
        return parent::beforeSave($insert);
    }

    /**
     * 重载beforeSave新加功能
     * @param bool $insert
     */
    private function _beforeSave($insert)
    {
        //插入
        if($insert) {
            $this->setAttribute('create_time', \common\helpers\DateHelper::now());
            $this->setAttribute('status', 0);
        }
        $this->setAttribute('update_time',\common\helpers\DateHelper::now());
    }
}