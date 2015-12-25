<?php
/**
 * Created by PhpStorm.
 * User: chao
 * Date: 2015/12/9
 * Time: 15:17
 */
namespace backend\models\mongodb;

class IdsModel extends \yii\mongodb\ActiveRecord
{
    const IDS_STATUS_OK = 1;
    const IDS_STATUS_DEL = 0;

    public static function collectionName()
    {
        return 'ids';
    }

    public function attributes()
    {
        ['_id','table_name','id','status','create_time'];
    }

    public function scenarios()
    {
        return [
            self::SCENARIO_DEFAULT => ['_id','table_name','id','status','create_time']
            ];
    }

    /**
     * 重载插入数据操作
     * @param bool $insert
     */
    protected function _beforeSave($insert)
    {
        parent::_beforeSave($insert);
        //插入前添加默认属性
        if($insert === true)
        {
            $this->setAttribute('create_time',\common\helpers\DateHelper::now());
            $this->setAttribute('act_status',self::IDS_STATUS_OK);
        }
    }

}