<?php
namespace backend\services\users;

use backend\services\BackendService;
use backend\models\mongodb\SignModel;
use common\constants\ErrorConstant;

/**
 * Class SignService
 * @package backend\services\users
 */
class SignService extends BackendService
{
    //用户签到事件
    const AFTER_SIGN_EVENT      = 'after_sign';

    public function sign($user_id)
    {
        if( ! is_numeric($user_id))
            return ErrorConstant::PARAM_ERROR;
        //检查用户当天是否已经签到
        $_sign = SignModel::findOne(['user_id' => $user_id,'create_time' => ['$gte'=>\common\helpers\DateHelper::startDate(),'$lte' => \common\helpers\DateHelper::endDate()],]);
        if($_sign !== null)
            return ErrorConstant::USER_IS_SIGNED;

        //签到
        $_sign = new SignModel();
        $_sign->user_id = $user_id;
        if($_sign->insert())
        {
            $this->OnAfterSign();
            return true;
        }
        return ErrorConstant::USER_SIGN_FAILED;
    }
    /**
     * 用户签到事件
     */
    private function OnAfterSign()
    {
        $this->trigger(static::AFTER_SIGN_EVENT);
    }
}