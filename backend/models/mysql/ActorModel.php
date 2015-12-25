<?php
namespace backend\models\mysql;
use backend\models\BackendModel;

/**
 * Class UserModel
 * @package backend\models\mysql
 */
class ActorModel extends BackendModel{

    const ACTOR_STATUS_LOCKED = 0;
    const ACTOR_STATUS_OK = 1;

    public static  function tableName(){
        return 'actor';
    }

    public function scenarios()
    {
        return [
            'default' => ['name','gender','description','thumb_picture_url','picture_url','company_id','h5_page_id','status'],
        ];
    }

    public function afterFind()
    {
        $this->_afterFind();
        parent::afterFind();
    }

    public function fields()
    {
        return [
            'id' => 'actor_id',
            'name',
            'status',
            'description',
            'picture_url',
            'thumb_picture_url',
        ];
    }

    private function _afterFind()
    {
        if($this->status == self::USER_STATUS_LOCKED) $this->icon_url = 'fobidden';
        else
            if($this->icon_url !== null) $this->icon_url = 'flex' . $this->icon_url;
    }
}