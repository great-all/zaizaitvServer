<?php
/**
 * @category 北京阿克米有限公司
 */
namespace backend\controllers;
use backend\services\actors\ActorService;
use common\helpers\JsonHelper;
use \yii\helpers\ArrayHelper;

/**
 * Class UserController
 * @package backend\controllers
 * @author zhangchao
 * @since	Version 1.0.0
 */
class ActorController extends BackendController
{
    /**
     * @return array
     *
     */
//    public function behaviors()
//    {
//        return [
//            [
//                'class' => \backend\filters\TokenFilter::className(),
//                'only' => ['sign', 'changePassword','userCenter','account'],
//            ],
//        ];
//    }

    /**
     * 默认控制器（待用）
     * @return string
     */
    public function actionIndex()
    {
        return 'welcome to zaizai!';
    }

    /**
     * 获取演员列表接口
     * @return string
     */
    public function actionActorList()
    {
        $param       = $this->parseParam();
        $page_index  = ArrayHelper::getValue($param,'page_index');
        $page_count  = ArrayHelper::getValue($param,'page_count');
        $company     = ArrayHelper::getValue($param,'company');
        $_return = ActorService::getService()->actorList($page_index,$page_count,$company);
        if(is_array($_return))
            return JsonHelper::returnSuccess($_return);

        return JsonHelper::returnError($_return);
    }

    /**
     * 获取演员详情列表
     * @return string
     */
    public function actionActorDetail()
    {
        $param       = $this->parseParam();
        $actor_id    = ArrayHelper::getValue($param,'actor_id');
        $page_index  = ArrayHelper::getValue($param,'page_index');
        $count       = ArrayHelper::getValue($param,'count');
        $_return = ActorService::getService()->actorDetail($actor_id,$page_index,$count);
        if(is_array($_return))
            return JsonHelper::returnSuccess($_return);

        return JsonHelper::returnError($_return);
    }

    /**
     * 演员评论列表
     * @return string
     */
    public function actionCommentList()
    {
        $param       = $this->parseParam();
        $actor_id    = ArrayHelper::getValue($param,'actor_id');
        $page_index  = ArrayHelper::getValue($param,'page_index');
        $count       = ArrayHelper::getValue($param,'count');
        $_return = ActorService::getService()->commentList($actor_id,$page_index,$count);
        if(is_array($_return))
            return JsonHelper::returnSuccess($_return);

        return JsonHelper::returnError($_return);
    }

    /**
     * 对演员评论
     * @return string
     */
    public function actionComment()
    {
        $param       = $this->parseParam();
        $actor_id    = ArrayHelper::getValue($param,'actor_id');
        $user_id     = ArrayHelper::getValue($param,'user_id');
        $content     = ArrayHelper::getValue($param,'content');
        $target_id     = ArrayHelper::getValue($param,'target_id');
        $target_user     = ArrayHelper::getValue($param,'target_user');
        $_return = ActorService::getService()->comment($actor_id,$user_id,$content,$target_id,$target_user);
        if($_return === true)
            return JsonHelper::returnSuccess();

        return JsonHelper::returnError($_return);
    }
}