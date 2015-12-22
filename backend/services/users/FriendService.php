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
    //���ѹ�ϵ�¼�
    const AFTER_ADD_FRIEND = 'after_add_friend';//�����������
    const AFTER_AGREE_FRIEND = 'after_agree_friend';//ͬ���������
    const AFTER_DISAGREE_FRIEND = 'after_disagree_friend';//�ܾ���������
    const AFTER_RELEASE_FRIEND  = 'after_release_friend';//�����������

    //�����б�Ĭ�Ͽ�ʼҳ
    const DEFAULT_PAGE_INDEX = 1;
    const DEFAULT_PAGE_COUNT = 10;

    /**
     * ��ȡ�����б�
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
     * ��Ӻ���
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
        //�ж����Ѿ��Ǻ��ѹ�ϵ
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
        //��Ӻ��������ϵ
        $friend = new FriendModel();
        $_isRight = $friend->addFriend(['originator' => $user_id,'agree_id' => $friend_id]);
        if($_isRight === false) return ErrorConstant::ADD_FRIEND_FAILED;
        //��������¼�
        $this->triggerFriendEvent(self::AFTER_ADD_FRIEND,$user_id, $friend_id);
        return true;
    }

    /**
     * �����������
     * @param $user_id
     * @param $friend_id
     * @param bool|true $is_agree
     * @return bool|int
     */
    public function handleFriend($user_id,$friend_id,$is_agree = true)
    {
        //�������Ϸ�
        if (!is_numeric($user_id) || !is_numeric($friend_id))
            return ErrorConstant::PARAM_ERROR;

        //���Ѳ�����
        if (UserModel::findOne($friend_id === null))
            return ErrorConstant::USER_NOT_EXISTS;

        //�ж��Ƿ��Ѿ��Ǻ��ѹ�ϵ
        $_relation = FriendModel::findOne(['originator' => $friend_id,'agree_id' => $user_id,'deleted' => FriendModel::IS_NOT_DELETED]);
        //û�к�������
        if($_relation === null) return ErrorConstant::NOT_INVITATION;
        switch ($_relation->status) {
            case FriendModel::RELATION_STATUS_IS_FRIEND:
                return ErrorConstant::IS_FRIEND;
            default: break;
        }
        //������������ϵ
        if($is_agree === true)
        {//ͬ���������
            $_relation->status = FriendModel::RELATION_STATUS_IS_FRIEND;
            $_relation->agree_time = \common\helpers\DateHelper::now();
            if($_relation->update() === false) return ErrorConstant::AGREE_FRIEND_FAILED;
        }else{//�ܾ���������
            $_relation->status = FriendModel::RELATION_STATUS_DISAGREE;
            $_relation->disagree_time = \common\helpers\DateHelper::now();
            $_relation->deleted  = FriendModel::IS_DELETED;
            if($_relation->update() === false) return ErrorConstant::DISAGREE_FRIEND_FAILED;
        }

        $this->triggerFriendEvent($is_agree === true ? self::AFTER_AGREE_FRIEND : self::AFTER_AGREE_FRIEND,$user_id,$friend_id);
        return true;
    }

    /**
     * ������ѹ�ϵ
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
        //�ж����Ѿ��Ǻ��ѹ�ϵ
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
     * @param $name �¼���
     * @param $user_id   �¼�������ID
     * @param $friend_id �¼�������ID
     */
    private function triggerFriendEvent($name,$user_id,$friend_id)
    {
        $event = new FriendEvent();
        $event->originator = $user_id;
        $event->friend = $friend_id;
        $this->trigger($name,$event);
    }
}