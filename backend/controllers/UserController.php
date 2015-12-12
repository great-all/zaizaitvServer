<?php
/**
 * @category 北京阿克米有限公司
 */
namespace backend\controllers;
use backend\models\redis\TokenModel;
use backend\services\users\InvitationCodeService;
use backend\services\users\UserService;
use backend\services\users\UserInfoService;
use common\helpers\JsonHelper;

/**
 * Class UserController
 * @package backend\controllers
 * @author zhangchao
 * @since	Version 1.0.0
 */
class UserController extends BackendController
{

    /**
     * @return array
     *
     */
    public function behaviors()
    {
        return [
            [
                'class' => \backend\filters\TokenFilter::className(),
                'only' => ['sign', 'changePassword','userCenter','account'],
            ],
        ];
    }

    /**
     * 默认控制器（待用）
     * @return string
     */
    public function actionIndex()
    {
        return 'welcome to zaizai!';
    }

    /**
     * 用户登录接口
     * @return string
     */
    public function actionLogin()
    {
        $param = $this->parseParam();
        $user_name = $param['user_name'];
        $password  = $param['password'];
        $client_type = $param['client_type'];
        $user_service = UserService::getService();
        $_return = $user_service->login($user_name, $password, $client_type);
        if(is_string($_return))
            return JsonHelper::returnSuccess(['token' => $_return]);

        return JsonHelper::returnError($_return);
    }

    /**
     * 检查用户名的合法性
     * @return string
     */
    public function actionCheckName()
    {
        $param = $this->parseParam();
        $user_name = $param['user_name'];
        $_return = UserService::getService()->checkName($user_name);
        if($_return === true)
            return returnSuccess();

        return JsonHelper::returnError($_return);
    }

    /**
     * 检查昵称的合法性
     * @return string
     */
    public function actionCheckNick()
    {
        $param = $this->parseParam();
        $nick_name = $param['nick_name'];
        $_return = UserService::getService()->checkNick($nick_name);
        if($_return === true)
            return returnSuccess();

        return JsonHelper::returnError($_return);
    }

    /**
     * 检查手机号的合法性
     * @return string
     */
    public function actionCheckMobile()
    {
        $param = $this->parseParam();
        $mobile = $param['mobile'];
        $_return = UserService::getService()->checkMobile($mobile);
        if($_return === true)
            return returnSuccess();

        return JsonHelper::returnError($_return);
    }

    /**
     * 用户注册
     * @return string
     */
    public function actionRegister()
    {
        $param = $this->parseParam();
        $user_name = $param['user_name'];
        $password = $param['password'];
        $nick_name = $param['nick_name'];
        $mobile = $param['mobile'];
        unset($param['user_name'],$param['password'],$param['nick_name'],$param['mobile']);
        $_return = UserService::getService()->register($user_name,$password,$nick_name,$mobile,$param);
        if($_return === true)
            return JsonHelper::returnSuccess();

        return JsonHelper::returnError($_return);


    }

    /**
     * 第三方登陆
     */
    public function actionNewLogin()
    {

    }

    /**
     * 用户签到
     * @return string
     */
    public function actionSign()
    {
        $param = $this->parseParam();
        $user_id = $param['user_id'];
        $_return = UserService::getService()->sign($user_id);
        if($_return === true)
            return JsonHelper::returnSuccess();

        return JsonHelper::returnError($_return);
    }

    /**
     * 找回密码
     * @return string
     */
    public function actionForgetPassword()
    {
        $param = $this->parseParam();
        $mobile = $param['mobile'];
        $password = $param['password'];
        $code     = $param['code'];
        $_return = UserService::getService()->forgetPassword($mobile,$password,$code);
        if($_return === true)
            return JsonHelper::returnSuccess();

        return JsonHelper::returnError($_return);
    }

    /**
     * 修改密码
     * @return string
     */
    public function actionChangePassword()
    {
        $param = $this->parseParam();
        $user_id = $param['user_id'];
        $password = $param['password'];
        $old_password     = $param['oldPassword'];
        $_return = UserService::getService()->changePassword($user_id,$password,$old_password);
        if($_return === true)
            return JsonHelper::returnSuccess();

        return JsonHelper::returnError($_return);
    }

    /**
     * 我的账户
     * @return string
     */
    public function actionAccount()
    {
        $param = $this->parseParam();
        $user_id = $param['user_id'];
        $_return = UserInfoService::getService()->account($user_id);
        if(is_array($_return))
            return JsonHelper::returnSuccess($_return);

        return JsonHelper::returnError($_return);
    }

    /**
     * 用户中心
     * @return string
     */
    public function actionUserCenter()
    {
        $param = $this->parseParam();
        $user_id = $param['user_id'];
        $_return = UserinfoService::getService()->userCenter($user_id);
        if(is_array($_return))
            return JsonHelper::returnSuccess($_return);

        return JsonHelper::returnError($_return);
    }

    /**
     * 获取用户邀请码信息
     *
     * @return string
     */
    public function actionInvitationCode()
    {
        $param = $this->parseParam();
        $user_id = $param['user_id'];
        $_return = InvitationCodeService::getService()->invitationCode($user_id);
        if(is_array($_return))
            return JsonHelper::returnSuccess($_return);

        return JsonHelper::returnError($_return);
    }
}