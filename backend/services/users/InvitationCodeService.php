<?php
namespace backend\services\users;

use backend\services\BackendService;
use backend\models\mysql\UserModel;
use backend\models\mysql\InvitationCode;
use common\constants\ErrorConstant;

/**
 * Class UserInfoService
 * @package backend\services\users
 */
class InvitationCodeService extends BackendService
{
    /**
     * 获取用户邀请码信息
     *
     * @param $user_id
     * @return array|int
     */
    public function invitationCode($user_id)
    {
        if( ! is_numeric($user_id)) return ErrorConstant::USER_ID_ERROR;
        $user = UserModel::findOne($user_id);
        if($user === null) return ErrorConstant::USER_NOT_EXISTS;

        $_userInfo = $user->toArray(['icon_url','nick_name','name']);
        $_invitationCode = $this->getInvitationCode($user_id);
        if(is_array($_invitationCode))
            $_userInfo['invitationCode'] = $_invitationCode;
        else
            return $_invitationCode;

        return $_userInfo;
    }

    /**
     * 给用户生成验证码
     *
     * @param int $user_id
     * @return InvitationCode
     * @throws \Exception
     */
    public function addInvitationCode($user_id)
    {
        $invitationCode = new InvitationCode();
        $invitationCode->user_id = $user_id;
        $invitationCode->invitation_code = $this->_createInvitationCode();
        if($invitationCode->insert())
            return $invitationCode;
        else
            return ErrorConstant::CREATE_INVITATION_CODE_FAILED;
    }

    /**
     * 获取验证码
     *
     * @param int $user_id
     * @return mixed
     */
    private function getInvitationCode($user_id)
    {
        $invitationCode = InvitationCode::findOne(['user_id'=>$user_id]);
        if($invitationCode === null)
            $invitationCode = $this->addInvitationCode($user_id);

        if($invitationCode === null)
            return ErrorConstant::CREATE_INVITATION_CODE_FAILED;

        $_return['code'] = $invitationCode->invitation_code;
        $_return['registerNum'] = UserModel::find()->where(['invitation_code' => $invitationCode->invitation_code])->count();
        return $_return;
    }

    private function _createInvitationCode()
    {
        do{
            $randStr = \common\helpers\CommonHelper::randString();
            if(InvitationCode::findOne(['invitation_code' => $randStr]) === null)
                return $randStr;
        }while(true);
    }
}