<?php
/**
 * @category 北京阿克米有限公司
 */
namespace backend\controllers;
use backend\services\films\FilmService;
use \yii\helpers\ArrayHelper;

/**
 * Class UserController
 * @package backend\controllers
 * @author zhangchao
 * @since	Version 1.0.0
 */
class FilmController extends BaseController
{
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
    public function actionVideoList()
    {
        $param       = $this->parseParam();
        $page_index  = ArrayHelper::getValue($param,'page_index');
        $page_count  = ArrayHelper::getValue($param,'page_count');
        $_return = FilmService::getService()->videoList($page_index,$page_count);
        if(is_array($_return))
            return $this->returnSuccess($_return);

        return $this->returnError($_return);
    }
}