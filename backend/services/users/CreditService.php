<?php
namespace backend\services\users;

use backend\services\BackendService;
use common\constants\ErrorConstant;
use backend\models\mysql\CreditModel;
/**
 * Class UserInfoService
 * @package backend\services\users
 */
class CreditService extends BackendService
{
    /**
     * @param $user_id
     * @param int $num
     * @return bool|int
     */
    public function addCredit($user_id,$num = 0)
    {
        if( ! is_numeric($user_id))
            return ErrorConstant::PARAM_ERROR;
        $credit = new CreditModel;
        if($credit->add(['id' => $user_id,'remainder' => $num]))
            return true;
        else
            return ErrorConstant::ADD_CREDIT_FAILED;
    }

    public function updateCredit($user_id,$num = 0)
    {
        if( ! is_numeric($user_id))
            return ErrorConstant::PARAM_ERROR;
        $credit = CreditModel::findOne(['id' => $user_id]);
        if($credit === null)
            return ErrorConstant::USER_NOT_EXISTS;
        $credit->remainder = (int)$credit->remainder + (int)$num;
        if($credit->update())
            return true;

        return ErrorConstant::UPDATE_CREDIT_FAILED;
    }
}