<?php
namespace backend\services\users;

use backend\services\BackendService;
use backend\models\mysql\UserModel;
use common\constants\ErrorConstant;

/**
 * Class UserInfoService
 * @package backend\services\users
 */
class UserInfoService extends BackendService
{
    /**
     * 获取用户中心信息
     * @param int $user_id
     * @return array|int
     */
    public function userCenter($user_id)
    {
        if( ! is_numeric($user_id)) return ErrorConstant::USER_ID_ERROR;
        $user = UserModel::findOne($user_id);
        if($user === null) return ErrorConstant::USER_NOT_EXISTS;

        $_userInfo = $user->toArray(['name,icon_url,nick_name,gender,birthday,mobile']);

        return $_userInfo;
    }

    /**
     * 我的账户信息
     * @param string $user_id
     * @return array|int
     */
    public function account($user_id)
    {
        if( ! is_numeric($user_id)) return ErrorConstant::USER_ID_ERROR;
        $user = UserModel::findOne($user_id);
        if($user === null) return ErrorConstant::USER_NOT_EXISTS;

        $_userInfo = $user->toArray(['icon_url','nick_name']);
        //用户积分
        $_userInfo['score'] = 999;

        //是否有未读消息
        $_userInfo['unreadMessagee'] = true;

        return $_userInfo;
    }
}