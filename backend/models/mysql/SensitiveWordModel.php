<?php
namespace backend\models\mysql;
use backend\models\BackendModel;

/**
 * Class SensitiveWordModel
 * @package backend\models\mysql
 */
class SensitiveWordModel extends BackendModel{

    public static  function tableName(){
        return 'dict_dim';
    }
}