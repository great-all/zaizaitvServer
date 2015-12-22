<?php
namespace backend\models\mysql;
use backend\models\BackendModel;

/**
 * Class UserModel
 * @package backend\models\mysql
 */
class CreditModel extends BackendModel
{
    const CREDIT_STATUS_OK = 0;
    const CRREDIT_STATUS_LOCKED = 1;

    public static  function tableName(){
        return 'user_bonus';
    }

    public function scenarios()
    {
        return [
            'default' => ['id','remainder','create_time','status'],
        ];
    }

    public function beforeSave($insert)
    {
        if($insert)
        {
            $this->setAttribute('create_time',\common\helpers\DateHelper::now());
            $this->setAttribute('status',self::CREDIT_STATUS_OK);
        }
        return parent::beforeSave($insert);
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