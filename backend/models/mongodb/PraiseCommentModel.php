<?php
/**
 * Created by PhpStorm.
 * User: chao
 * Date: 2015/12/9
 * Time: 15:17
 */
namespace backend\models\mongodb;

class PraiseCommentModel extends \backend\models\mongodb\ActiveRecord
{
    const COMMENT_STATUS_OK = 0;
    const COMMENT_STATUS_LOCKED = 1;

    public static function collectionName()
    {
        return 'praise_comment';
    }

    public function attributes()
    {
        return ['_id','target_resource_type','target_resource_id','user_id','status','create_time'];
    }

    public function scenarios()
    {
        return [
            self::SCENARIO_DEFAULT => ['_id','target_resource_type','target_resource_id','user_id','status','create_time']
            ];
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
    protected function _beforeSave($insert)
    {
        parent::_beforeSave($insert);
        //插入前添加默认属性
        if($insert === true)
        {
            $this->setAttribute('create_time',\common\helpers\DateHelper::now());
            $this->setAttribute('status',self::COMMENT_STATUS_OK);
        }
    }

}