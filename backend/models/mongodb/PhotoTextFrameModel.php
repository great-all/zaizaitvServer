<?php
/**
 * Created by PhpStorm.
 * User: chao
 * Date: 2015/12/9
 * Time: 15:17
 */
namespace backend\models\mongodb;

class PhotoTextFrameModel extends \backend\models\mongodb\ActiveRecord
{
    const PHOTO_TEXT_FRAME_STATUS_OK  = 1;
    const PHOTO_TEXT_FRAME_STATUS_DEL = 0;

    //14.图文内容类型, photo_text_source_type
    const PHOTO_TEXT_SOURCE_TYPE_TRIVIA = 1; //拍摄花絮
    const PHOTO_TEXT_SOURCE_TYPE_PHOTXTNEWS = 2; //图文消息
    const PHOTO_TEXT_SOURCE_TYPE_POSTER = 3; //海报
    const PHOTO_TEXT_SOURCE_TYPE_ACTMODEL = 4; //造型详情
    const PHOTO_TEXT_SOURCE_TYPE_ACTOR = 5; //演员详情

    public static function collectionName()
    {
        return 'photo_text_frame';
    }

    public function attributes()
    {
        return [
            '_id',
            'frame_seq',
            'photo_text_source_type',
            'photo_text_source_id',
            'name',
            'description',
            'picture_url',
            'target_resource_type',
            'target_resource_id',
            'status',
            'create_time',
        ];
    }

    public function scenarios()
    {
        return [
            self::SCENARIO_DEFAULT => [
            '_id',
            'frame_seq',
            'photo_text_source_type',
            'photo_text_source_id',
            'name',
            'description',
            'picture_url',
            'target_resource_type',
            'target_resource_id',
            'status',
            'create_time',],
            ];
    }

    /**
     * 重载插入数据操作
     * @param bool $insert
     */
    protected function _beforeSave($insert)
    {
        parent::_beforeSave($insert);
        //插入前添加默认属性
        if($insert === true)
        {
            $this->setAttribute('create_time',\common\helpers\DateHelper::now());
            $this->setAttribute('status',self::PHOTO_TEXT_FRAME_STATUS_OK);
        }
    }

}