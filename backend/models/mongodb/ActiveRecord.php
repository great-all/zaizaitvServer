<?php
/**
 * Created by PhpStorm.
 * User: chao
 * Date: 2015/12/9
 * Time: 15:17
 */
namespace backend\models\mongodb;
use backend\models\mongodb\IdsModel;

class ActiveRecord extends \yii\mongodb\ActiveRecord
{
    public function beforeSave($insert)
    {
        //重载部分
        $this->_beforeSave($insert);
       return parent::beforeSave($insert);
    }

    /**
     * 重载插入数据操作
     * @param bool $insert
     */
    protected function _beforeSave($insert)
    {
        //插入前添加默认属性
        if($insert === true)
        {
            $id = IdsModel::getCollection()->findAndModify(
                ['table_name'=>static::collectionName()],
                ['$inc' =>['id' => 1]],
                [],
                ['new' => true,'upsert' => true]
            );
            $this->setAttribute('_id',$id['id']);
        }
    }

}