<?php
namespace backend\models\mysql;
use backend\models\BackendModel;

/**
 * Class UserModel
 * @package backend\models\mysql
 */
class InvitationCode extends BackendModel{

    //验证码状态
    const CODE_STATUS_OK = 1;
    const CODE_STATUS_CLOCKED = 2;

    public static  function tableName()
    {
        return 'invitation_code';
    }

    public function scenarios()
    {
        return [
            'default' => ['invitation_code','user_id','status','create_time'],
        ];
    }

    public function beforeSave($insert)
    {
        if($insert)
        {
            $this->setAttribute('create_time',\common\helpers\DateHelper::now());
            $this->setAttribute('status',self::CODE_STATUS_OK);
        }
        return parent::beforeSave($insert);
    }
}