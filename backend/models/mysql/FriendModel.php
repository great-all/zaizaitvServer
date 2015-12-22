<?php
namespace backend\models\mysql;
use backend\models\BackendModel;

/**
 * Class UserModel
 * @package backend\models\mysql
 */
class FriendModel extends BackendModel{

    //关系状态
    const RELATION_STATUS_IN_HAND   = 0;   //等待好友处理中
    const RELATION_STATUS_IS_FRIEND = 1;   //好友关系
    const RELATION_STATUS_DISAGREE  = 2;   //拒绝好友请求
    const RELATION_STATUS_RELEASE   = 3;   //解除好友关系

    //记录状态
    const IS_NOT_DELETED = 0;//已经删除 一般在用户拒绝好友请求或者解除好友关系后标记为1
    const IS_DELETED = 1;//未删除

    public static  function tableName(){
        return 'zaizai_friend';
    }

    public function scenarios()
    {
        return [
            'default' => ['originator','agree_id','status','create_time','agree_time','disagree_time','release_time','deleted'],
        ];
    }

    public function beforeSave($insert)
    {
        if($insert)
        {
            $this->setAttribute('create_time',\common\helpers\DateHelper::now());
        }
        return parent::beforeSave($insert);
    }

    /***
     * 判断用户是否是好友
     * @param $user_id
     * @param $friend_id
     * @return bool
     */
    public static function isFriend($user_id,$friend_id)
    {
        $row = self::find()
            ->where(['originator' =>$user_id,'agree_id' =>$friend_id])
            ->orWhere(['originator' =>$friend_id,'agree_id' =>$user_id])
            ->andWhere(['deleted' => self::IS_NOT_DELETED])
            ->one();
        if($row !== null)
            return $row;
        else
            return false;
    }

    /**
     * 添加用户
     * @param array $friend
     * @return bool|mixed
     * @throws \Exception
     */
    public function addFriend(array $friend)
    {
        if($this->load($friend,''))
           if($this->insert()) return $this->id;

        return false;
    }
}