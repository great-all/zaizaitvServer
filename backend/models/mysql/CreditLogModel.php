<?php
namespace backend\models\mysql;
use backend\models\BackendModel;

/**
 * Class UserModel
 * @package backend\models\mysql
 */
class CreditLogModel extends BackendModel
{
    const CREDIT_STATUS_OK = 1;
    const CRREDIT_STATUS_LOCKED = 0;

    public static  function tableName(){
        return 'user_bonus';
    }

    public function scenarios()
    {
        return [
            'default' => ['user_id','charge_type','charge_scence_type','amount','create_time','digest','provide_user_id'],
        ];
    }

    public function beforeSave($insert)
    {
        if($insert)
        {
            $this->setAttribute('create_time',\common\helpers\DateHelper::now());
            $this->setAttribute('status',self::CREDIT_STATUS_OK);
        }
    }

    /**
     * @param array $credit
     * @return bool|mixed
     * @throws \Exception
     */
    public function add(array $credit)
    {
        if($this->load($credit,''))
           if($this->insert()) return $this->id;

        return false;

    }
}