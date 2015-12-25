<?php
/**
 * Created by PhpStorm.
 * User: chao
 * Date: 2015/12/9
 * Time: 15:17
 */
namespace backend\models\mongodb;

class ActModel extends \backend\models\mongodb\ActiveRecord
{
    //演员演出状态, act_status
    const ACT_STATUS_SYSTEM_DEL = 0; //系统删除
    const ACT_STATUS_DRAFT = 1; //待筛选
    const ACT_STATUS_ACCEPT = 2; //已接受
    const ACT_STATUS_USER_DEL = 3; //用户删除

    public static function collectionName()
    {
        return 'act';
    }

    public function attributes()
    {
        return ['_id','role_id','actor_id','act_status','create_user_id','create_time','praise_num','vote_type_id_list'];
    }

    public function scenarios()
    {
        return [
            self::SCENARIO_DEFAULT  => ['_id','role_id','actor_id','act_status','create_user_id','create_time','praise_num','vote_type_id_list']
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
            $this->setAttribute('act_status',self::ACT_STATUS_DRAFT);
            //设置_id
        }
    }

}