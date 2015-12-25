<?php
namespace backend\services\films;

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
class FilmService extends BackendService
{
    const PAGE_INDEX_DEFAULT = 1;
    const PAGE_COUNT_DEFAULT = 10;

    const AFTER_COMMENT_ACTOR = 'after_comment_actor';

    /**
     * 获取演员列表
     * @param int $index
     * @param int $count
     * @param null $company
     * @return $this|array|\yii\db\ActiveRecord[]
     */
    public function videoList($index = self::PAGE_INDEX_DEFAULT,$count = self::PAGE_COUNT_DEFAULT)
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
}