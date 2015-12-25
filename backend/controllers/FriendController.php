<?php
/**
 * @category 北京阿克米有限公司
 */
namespace backend\controllers;
use backend\services\users\FriendService;
use \yii\helpers\ArrayHelper;

/**
 * Class UserController
 * @package backend\controllers
 * @author zhangchao
 * @since	Version 1.0.0
 */
class FriendController extends BaseController
{
    /**
     * 默认控制器（待用）
     * @return string
     */
    public function actionIndex()
    {
        return 'welcome to zaizai!';
    }

    /**
     *获取好友列表
     * @return string
     */
    public function actionList()
    {
        $param = $this->parseParam();
        $user_id = ArrayHelper::getValue($param,'user_id');
        $page_index = ArrayHelper::getValue($param,'page_index',1);
        $page_count = ArrayHelper::getValue($param,'page_count',10);
        $_return = FriendService::getService()->friendList($user_id,$page_index,$page_count);
        if(is_array($_return))
            return $this->returnSuccess(['friends_list'=>$_return]);

        return $this->returnError($_return);
    }

    /**
     * 发起好友请求接口
     * @return string
     */
    public function actionLaunch()
    {
        $param = $this->parseParam();
        $user_id = ArrayHelper::getValue($param,'user_id');
        $friend_id  = ArrayHelper::getValue($param,'friend_id');
        $_return = FriendService::getService()->launchFriend($user_id, $friend_id);
        if($_return === true)
            return $this->returnSuccess();

        return $this->returnError($_return);
    }

    /**
     * 同意好友请求
     * @return string
     */
    public function actionAgree()
    {
        $param = $this->parseParam();
        $user_id = ArrayHelper::getValue($param,'user_id');
        $friend_id  = ArrayHelper::getValue($param,'friend_id');
        $_return = FriendService::getService()->handleFriend($user_id, $friend_id);
        if($_return === true)
            return $this->returnSuccess();

        return $this->returnError($_return);
    }

    /**
     * 拒绝好友请求
     * @return string
     */
    public function actionDisagree()
    {
        $param = $this->parseParam();
        $user_id = ArrayHelper::getValue($param,'user_id');
        $friend_id  = ArrayHelper::getValue($param,'friend_id');
        $_return = FriendService::getService()->handleFriend($user_id, $friend_id,false);
        if($_return === true)
            return $this->returnSuccess();

        return $this->returnError($_return);
    }

    /**
     * 解除好友关系
     * @return string
     */
    public function actionRelease()
    {
        $param = $this->parseParam();
        $user_id = ArrayHelper::getValue($param,'user_id');
        $friend_id  = ArrayHelper::getValue($param,'friend_id');
        $_return = FriendService::getService()->releaseFriend($user_id, $friend_id);
        if($_return === true)
            return $this->returnSuccess();

        return $this->returnError($_return);
    }
}