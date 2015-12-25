<?php
namespace backend\services\actors;

use backend\models\mysql\UserModel;
use backend\services\BackendService;
use backend\models\mysql\ActorModel;
use backend\models\mongodb\ActorCommentModel;
use backend\models\mongodb\ActModel;
use backend\models\mongodb\PhotoTextFrameModel;
use common\helpers\ArrayHelper;
use common\constants\ErrorConstant;
/**
 * Class TokenService
 * @package backend\services\users
 */
class ActorService extends BackendService
{
    const PAGE_INDEX_DEFAULT = 1;
    const PAGE_COUNT_DEFAULT = 10;

    const AFTER_COMMET_ACTOR = 'after_comment_actor';

    /**
     * 获取演员列表
     * @param int $index
     * @param int $count
     * @param null $company
     * @return $this|array|\yii\db\ActiveRecord[]
     */
    public function actorList($index = self::PAGE_INDEX_DEFAULT,$count = self::PAGE_COUNT_DEFAULT,$company = null)
    {
        $index = is_numeric($index) ? $index : self::PAGE_INDEX_DEFAULT;
        $count = is_numeric($count) ? $count : self::PAGE_COUNT_DEFAULT;
        $offset = ($index - 1) * $count;
        $_actorList = ActorModel::find()->where(['status' => ActorModel::ACTOR_STATUS_OK]);
        if($company !== null)
            $_actorList = $_actorList->andWhere(['company_id' => $company]);
        $_actorList = $_actorList->offset($offset)->limit($count)->asArray()->all();
        return $_actorList;
    }

    /**
     * @param $actor_id
     * @param $index
     * @param $count
     * @return array|int|null|\yii\db\ActiveRecord
     * @throws \yii\mongodb\Exception
     */
    public function actorDetail($actor_id,$index,$count)
    {
        if( ! is_numeric($actor_id))
            return ErrorConstant::PARAM_ERROR;
        $index = is_numeric($index) ? $index : self::PAGE_INDEX_DEFAULT;
        $count = is_numeric($count) ? $count : self::PAGE_COUNT_DEFAULT;
        $offset = ($index -1) * $count;
        //判断演员是否存在
        $actor = ActorModel::find()->where(['status' => ActorModel::ACTOR_STATUS_OK, 'id' =>$actor_id])->asArray()->one();
        if($actor === [])
            return ErrorConstant::ACTOR_NOT_EXISTS;
        //获取该演员的评论数量
        $actor['comment_num'] = ActorCommentModel::find()->where(['status' => ActorCommentModel::COMMENT_STATUS_OK, 'actor_id' => (int)$actor_id])->count();

        //获取演员出演的所有角色的点赞数量
        $actor['praise_num'] = ActModel::find()->where(['actor_id' => (int)$actor_id,'act_status' => ['$in' => [ActModel::ACT_STATUS_DRAFT,ActModel::ACT_STATUS_ACCEPT]]])->sum('praise_num');

        //获取演员图片帧列表
        $actor['actor_frames'] = PhotoTextFrameModel::find()->where([
            'frame_seq' => 1,
            'photo_text_source_id' => (int)$actor_id,
            'photo_text_source_type' => PhotoTextFrameModel::PHOTO_TEXT_SOURCE_TYPE_ACTOR
        ])->asArray()->offset($offset)->limit($count)->all();
        return $actor;
    }

    /**
     * 获取演员评论信息
     * @param $actor_id
     * @param $index
     * @param $count
     * @return array|int|\yii\mongodb\ActiveRecord
     */
    public function commentList($actor_id,$index,$count)
    {
        if( ! is_numeric($actor_id))
            return ErrorConstant::PARAM_ERROR;
        $index = is_numeric($index) ? $index : self::PAGE_INDEX_DEFAULT;
        $count = is_numeric($count) ? $count : self::PAGE_COUNT_DEFAULT;
        $offset = ($index -1) * $count;
        //判断演员是否存在
        $actor = ActorModel::find()->where(['status' => ActorModel::ACTOR_STATUS_OK, 'id' =>$actor_id])->asArray()->one();
        if($actor === [])
            return ErrorConstant::ACTOR_NOT_EXISTS;
        //获取评论列表
        $commentList = ActorCommentModel::find()->where(['actor_id' => (int)$actor_id])->offset($offset)->limit($count)->asArray()->orderBy('create_time DESC')->all();

        if($commentList === [])
            return $commentList;
        $user_id = array_unique(array_merge(ArrayHelper::getColumn($commentList,'user_id'),ArrayHelper::getColumn($commentList,'target_user_id')));
        //获取用户信息
        $comment_user = UserModel::find()->where(['in','id',$user_id])->asArray()->all();
        $comment_user = ArrayHelper::index($comment_user,'id');

        //获取用户评论的评论信息
        $comment_id = array_unique(ArrayHelper::getColumn($commentList,'target_comment_id'));
        $target_comment = ActorCommentModel::find()->where(['in','_id',$comment_id])->asArray()->all();
        $target_comment = ArrayHelper::index($target_comment,'_id');

        foreach ($commentList as $key => $comment)
        {
            $commentList[$key]['user_name'] = $comment_user[$comment['user_id']]['nick_name'];
            $commentList[$key]['icon_url']  = $comment_user[$comment['user_id']]['icon_url'];
            if($comment['target_comment_id'] >= 0)
            {
                $commentList[$key]['target_user_name'] = $comment_user[$comment['target_user_id']]['nick_name'];
                $commentList[$key]['target_user_icon_url'] = $comment_user[$comment['target_user_id']]['icon_url'];
                $commentList[$key]['target_comment_content'] = $target_comment[$comment['target_user_id']]['content'];
            }
        }
        return $commentList;
    }

    public function comment($actor_id,$user_id,$content,$target_id = null,$target_user = null)
    {
        if( ! is_numeric($actor_id) || ! is_numeric($user_id))
            return ErrorConstant::PARAM_ERROR;
        //判断演员是否存在
        $actor = ActorModel::find(['id' => $actor_id])->asArray()->one();
        if($actor === [])
            return ErrorConstant::ACTOR_NOT_EXISTS;
        $target_id = $target_id?: -1;
        $target_user = $target_user ?: -1;
        $comment = [
            'actor_id' => (int)$actor_id,
            'user_id'  => (int)$user_id,
            'content'  => $content,
            'target_user_id' => (int)$target_user,
            'target_comment_id' => (int)$target_id,
        ];
        $commentActor = new ActorCommentModel();
        if($commentActor->addComment($comment))
        {
            $this->onAfterComment($comment);
            return true;
        }
        return ErrorConstant::ACTOR_COMMENT_FAILED;
    }

    private function onAfterComment(array $comment)
    {
        $this->trigger(self::AFTER_COMMET_ACTOR);
    }


}