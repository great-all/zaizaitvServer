<?php
namespace backend\models\mysql;
use backend\models\BackendModel;

/**
 * Class UserModel
 * @package backend\models\mysql
 */
class UserModel extends BackendModel{

    //用户状态
    const USER_STATUS_OK = 1;   //用户状态正常
    const USER_STATUS_LOCKED = 0;//用户被锁状态

    //用户类型
    const USER_TYPE_SYSTEEM = 3;//系统用户
    const USER_TYPE_COMMON  = 2;//普通用户

    public static  function tableName(){
        return 'user';
    }

    public function scenarios()
    {
        return [
            'default' => ['name','nick_name','user_type','password','mobile','create_time','modify_time'],
        ];
    }

    public function beforeSave($insert)
    {
        if($insert)
        {
            $this->setAttribute('create_time',\common\helpers\DateHelper::now());
            $this->setAttribute('user_type',self::USER_TYPE_COMMON);
        }
        $this->setAttribute('modify_time',\common\helpers\DateHelper::now());
        return parent::beforeSave($insert);
    }

    public function afterFind()
    {
        $this->_afterFind();
        parent::afterFind();
    }

    /**
     * 添加用户
     * @param array $user
     * @return bool|mixed
     * @throws \Exception
     */
    public function addUser(array $user)
    {
        if($this->load($user,''))
           if($this->insert()) return $this->id;

        return false;

    }

    private function _afterFind()
    {
        if($this->status == self::USER_STATUS_LOCKED) $this->icon_url = 'fobidden';
        else
            if($this->icon_url !== null) $this->icon_url = 'flex' . $this->icon_url;
    }
}