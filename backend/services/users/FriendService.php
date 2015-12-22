<?php
namespace backend\services\users;

use backend\services\BackendService;
use backend\models\mysql\UserModel;
use backend\models\mysql\FriendModel;
use backend\events\FriendEvent;
use yii\helpers\ArrayHelper;
use common\constants\ErrorConstant;

/**
 * Class UserInfoService
 * @package backend\services\users
 */
class FriendService extends BackendService
{
    //好友关系事件
    const AFTER_ADD_FRIEND = 'after_add_friend';//发起好友请求
    const AFTER_AGREE_FRIEND = 'after_agree_friend';//同意好友请求
    const AFTER_DISAGREE_FRIEND = 'after_disagree_friend';//拒绝好友请求
    const AFTER_RELEASE_FRIEND  = 'after_release_friend';//解除好友请求

    //好友列表默认开始页
    const DEFAULT_PAGE_INDEX = 1;
    const DEFAULT_PAGE_COUNT = 10;

    /**
     * 获取好友列表
     * @param $user_id
     * @param int $page_index
     * @param int $page_count
     * @return array|int|\yii\db\ActiveRecord[]
     */
    public function friendList($user_id,$page_index = self::DEFAULT_PAGE_INDEX,$page_count = self::DEFAULT_PAGE_COUNT)
    {
        if (!is_numeric($user_id))
            return ErrorConstant::PARAM_ERROR;
        $page_index = is_numeric($page_index) ? $page_index : self::DEFAUT_PAGE_INDEX;
        $page_count = is_numeric($page_count) ? $page_count : self::DEFAUT_PAGE_COUNT;
        $_offset = ($page_index - 1) * $page_count;

        $_friend = FriendModel::find()
            ->where(['originator'=>$user_id,'deleted' => FriendModel::IS_NOT_DELETED,'status'=>FriendModel::RELATION_STATUS_IS_FRIEND])
            ->indexBy('friend_id')->asArray()->all();
        $_originator = FriendModel::find()
            ->where(['agree_id'=>$user_id,'deleted' => FriendModel::IS_NOT_DELETED,'status'=>FriendModel::RELATION_STATUS_IS_FRIEND])
            ->asArray()->indexBy('originator')->all();
        $_list_id = ArrayHelper::merge(ArrayHelper::getColumn($_friend,'friend_id'),ArrayHelper::getColumn($_originator,'originator'));

        $_friends = UserModel::find()->where(['in','id',$_list_id])->offset($_offset)->limit($page_count)->asArray()->all();
        return $_friends;
    }

    /**
     * 添加好友
     * @param $user_id
     * @param $friend_id
     * @return bool|int
     */
    public function launchFriend($user_id, $friend_id)
    {
        if (!is_numeric($user_id) || !is_numeric($friend_id))
            return ErrorConstant::PARAM_ERROR;

        if (UserModel::findOne($friend_id === null))
            return ErrorConstant::USER_NOT_EXISTS;
        //判断是已经是好友关系
        $_relation = FriendModel::isFriend($user_id, $friend_id);
        if ($_relation !== false) {
            switch ($_relation->status) {
                case FriendModel::RELATION_STATUS_IN_HAND:
                    return ErrorConstant::FRIEND_IN_HAND;
                case FriendModel::RELATION_STATUS_IS_FRIEND:
                    return ErrorConstant::IS_FRIEND;
                default: break;
            }
        }
        //添加好友请求关系
        $friend = new FriendModel();
        $_isRight = $friend->addFriend(['originator' => $user_id,'agree_id' => $friend_id]);
        if($_isRight === false) return ErrorConstant::ADD_FRIEND_FAILED;
        //触发添加事件
        $this->triggerFriendEvent(self::AFTER_ADD_FRIEND,$user_id, $friend_id);
        return true;
    }

    /**
     * 处理好友请求
     * @param $user_id
     * @param $friend_id
     * @param bool|true $is_agree
     * @return bool|int
     */
    public function handleFriend($user_id,$friend_id,$is_agree = true)
    {
        //参数不合法
        if (!is_numeric($user_id) || !is_numeric($friend_id))
            return ErrorConstant::PARAM_ERROR;

        //好友不存在
        if (UserModel::findOne($friend_id === null))
            return ErrorConstant::USER_NOT_EXISTS;

        //判断是否已经是好友关系
        $_relation = FriendModel::findOne(['originator' => $friend_id,'agree_id' => $user_id,'deleted' => FriendModel::IS_NOT_DELETED]);
        //没有好友请求
        if($_relation === null) return ErrorConstant::NOT_INVITATION;
        switch ($_relation->status) {
            case FriendModel::RELATION_STATUS_IS_FRIEND:
                return ErrorConstant::IS_FRIEND;
            default: break;
        }
        //处理好友请求关系
        if($is_agree === true)
        {//同意好友请求
            $_relation->status = FriendModel::RELATION_STATUS_IS_FRIEND;
            $_relation->agree_time = \common\helpers\DateHelper::now();
            if($_relation->update() === false) return ErrorConstant::AGREE_FRIEND_FAILED;
        }else{//拒绝好友请求
            $_relation->status = FriendModel::RELATION_STATUS_DISAGREE;
            $_relation->disagree_time = \common\helpers\DateHelper::now();
            $_relation->deleted  = FriendModel::IS_DELETED;
            if($_relation->update() === false) return ErrorConstant::DISAGREE_FRIEND_FAILED;
        }

        $this->triggerFriendEvent($is_agree === true ? self::AFTER_AGREE_FRIEND : self::AFTER_AGREE_FRIEND,$user_id,$friend_id);
        return true;
    }

    /**
     * 解除好友关系
     * @param $user_id
     * @param $friend_id
     * @return int
     */
    public function releaseFriend($user_id,$friend_id)
    {
        if (!is_numeric($user_id) || !is_numeric($friend_id))
            return ErrorConstant::PARAM_ERROR;

        if (UserModel::findOne($friend_id === null))
            return ErrorConstant::USER_NOT_EXISTS;
        //判断是已经是好友关系
        $_relation = FriendModel::isFriend($user_id, $friend_id);
        if($_relation === false) return ErrorConstant::FRIEND_NOT_EXISTS;
        switch ($_relation->status) {
            case FriendModel::RELATION_STATUS_IN_HAND:
                return ErrorConstant::FRIEND_IN_HAND;
            default: break;
        }
        $_relation->status = FriendModel::RELATION_STATUS_RELEASE;
        $_relation->release_time = \common\helpers\DateHelper::now();
        $_relation->deleted = FriendModel::IS_DELETED;
        if($_relation->update() === false) return ErrorConstant::RELEASE_FRIEND_FAILED;
        $this->triggerFriendEvent(self::AFTER_RELEASE_FRIEND,$user_id,$friend_id);
        return true;

    }

    /**
     *
     * @param $name 事件名
     * @param $user_id   事件触发者ID
     * @param $friend_id 事件关联者ID
     */
    private function triggerFriendEvent($name,$user_id,$friend_id)
    {
        $event = new FriendEvent();
        $event->originator = $user_id;
        $event->friend = $friend_id;
        $this->trigger($name,$event);
    }
}