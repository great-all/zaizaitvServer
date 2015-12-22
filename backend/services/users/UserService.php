<?php
/**
 * @category 北京阿克米有限公司
 */
namespace backend\services\users;

use backend\events\RegisterEvent;
use backend\services\BackendService;
use backend\models\mysql\UserModel;
use backend\models\mongodb\SignModel;
use common\constants\ErrorConstant;

/**
 * Class UserService
 * @package backend\services\users
 * @author zhangchao
 * @since	Version 1.0.0
 */
class UserService extends BackendService{

    //事件
    const AFTER_LOGIN_EVENT     = 'after_login';//用户登录后置事件
    const BEFORE_LOGIN_EVENT    = 'before_login';//用户登陆前置事件
    const AFTER_REGISTER_EVENT  = 'after_register';//用户注册后置事件
    const AFTER_SIGN_EVENT      = 'after_sign';    //用户签到后置事件
    const AFTER_CHANGE_PASSWORD_EVENT = 'after_changePassword';//修改密码后置事件
    const AFTER_FIND_PASSWORD_EVENT = 'after_findPassword';//找回密码后置事件

    /**
     * 行为列表
     * @return array
     */
    public function behaviors()
    {
        return [
            'userBehaviors' => [
                'class' => \backend\behaviors\UserBehavior::className(),
            ],
        ];
    }

    /**
     * 用户登录
     * @param string $user_name   用户名
     * @param string $password    密码
     * @param string $client_type 客户端类型
     * @return int|string 成功token 失败返回错误状态吗
     */
    public function login($user_name,$password,$client_type)
    {
        if(empty($user_name) || empty($password))
            return ErrorConstant::LOGIN_PARAM_ERROR;
        //检测密码是否正确
        $user = $this->_findUser($user_name,$password);
        if($user !== NULL)//登陆成功
        {
            //查看用户是否被封号
            if($user->status != UserModel::USER_STATUS_OK) return ErrorConstant::USER_IS_LOCKED;
            //生成token
            $_token = TokenService::getService()->createToken($user->id);

            //触发登陆成功事件
            if(is_string($_token))
                $this->OnAfterLogin($user,$client_type);

            return $_token;
        }
        //检测用户是否注册
        $_isRegister = $this->_isRegister($user_name);
        if($_isRegister === false)
            return ErrorConstant::USER_NOT_EXISTS;
        else
            return ErrorConstant::USER_PASSWORD_ERROR;
    }

    /**
     * 用户注册
     * @param $user_name   用户名
     * @param $password    密码
     * @param $nick_name   昵称
     * @param $mobile      手机号
     * @param array $other 其他信息
     * @return bool|int    成功返回true失败返回错误状态码
     */
    public function register($user_name,$password,$nick_name,$mobile,array $other = [])
    {
        //验证用户名，密码，昵称，手机号合法性
        $_isRight = $this->_checkRegister($user_name,$password,$nick_name,$mobile);
        if($_isRight !== true)
            return $_isRight;

        $user = ['name'=>$user_name,'password'=>$password,'nick_name' => $nick_name, 'mobile'=>$mobile];
        $user_model = new UserModel();
        $_isOk = $user_model->addUser($user);
        if($_isOk === false)
            return ErrorConstant::REGISTER_FAIL;

        //注册事件
        $this->OnAfterRegister($_isOk);
        //可以考虑注册成功后调用登陆接口（直接调用登陆服务）
        return true;

    }

    /**
     * 检查用户名的合法性
     * @param $user_name
     * @return bool|int
     */
    public function checkName($user_name)
    {
        if( empty($user_name)) return ErrorConstant::R;
        //判断用户是否已经存在
        if(UserModel::findOne(['name' => $user_name]) !== NULL)
            return ErrorConstant::USER_IS_EXISTS;
        //判断用户名是否是敏感词
        $_isSensivite = \backend\services\SensitiveWordService::getService()->isSensitive($user_name,\backend\services\SensitiveWordService::DICT_CODE_SENSITIVE);
        if($_isSensivite === true)
            return ErrorConstant::REGISTER_NAME_SENSITIVE;

        return true;

    }

    /**
     * 检查密码保密程度(待用)
     * @param $password
     * @return bool|int
     */
    public function checkPass($password)
    {
        if( empty($password)) return ErrorConstant::R;
        return true;
    }

    /**
     * 检查昵称的合法性
     * @param $nick_name
     * @return bool|int
     */
    public function checkNick($nick_name)
    {
        if( empty($nick_name)) return ErrorConstant::REGISTER_PARAM_ERROR;
        //判断用户是否已经存在R
        if(UserModel::findOne(['nick_name' => $nick_name]) !== NULL)
            return ErrorConstant::NICK_NAME_IS_EXISTS;
        //判断用户名是否是敏感词
        $_isSensivite = \backend\services\SensitiveWordService::getService()->isSensitive($nick_name,\backend\services\SensitiveWordService::DICT_CODE_SENSITIVE);
        if($_isSensivite === true)
            return ErrorConstant::REGISTER_NICK_SENSITIVE;

        return true;
    }

    /**
     * 检查手机号的合法性
     * @param $mobile
     * @return bool|int
     */
    public function checkMobile($mobile)
    {
        //判断手机号是否被注册
        if(UserModel::findOne(['mobile' => $mobile]) !== NULL)
            return ErrorConstant::USER_MOBILE_IS_EXISTS;

        //判断手机号格式是否正确
        if( empty($mobile) || \common\helpers\CommonHelper::isMobile($mobile) === false) return ErrorConstant::MOBILE_FORMAT_ERROR;

        return true;
    }

    /**
     * 找回密码
     * @param string $mobile
     * @param string $password
     * @param string $code
     * @return bool|int
     */
    public function forgetPassword($mobile,$password,$code)
    {
        //校验手机号是否已经注册
        $user = UserModel::findOne(['mobile' =>$mobile ]);
        if($user === null) return ErrorConstant::MOBILE_NOT_REGISTER;

        //校验密码
        if(($_isRight = $this->checkPass($password)) !== true) return $_isRight;
        //校验验证码
        $_isRight = \common\services\VerifyCodeService::getService()->checkCode($mobile,$code);
        if($_isRight !== true) return $_isRight;

        //跟新密码
        $user->password = $password;
        if($user->update())
        {
            $this->OnAfterFindPassword();
            return true;
        }
        return ErrorConstant::FORGET_PASSWORD_FAIED;
    }

    /**
     * 修改密码
     *
     * @param int $user_id
     * @param string $password
     * @param string $old_password
     * @return bool|int
     */
    public function changePassword($user_id, $password, $old_password)
    {
        if( ! is_numeric($user_id)) return ErrorConstant::USER_ID_ERROR;
        if( $password !== $old_password) return ErrorConstant::PASSWORD_UNCONFORMITY;
        if(($_isRight = $this->checkPass($password)) !== true) return $_isRight;
        if(UserModel::updateAll(['password'=>$password],['id'=>$user_id]))
        {
            $this->OnAfterChangePassword();
            return true;
        }
        return ErrorConstant::CHANGE_PASSWORD_FAILED;
    }

    /**
     * 检查用户填写的注册信息
     * @param $user_name 用户名
     * @param $password  密码
     * @param $nick_name 昵称
     * @param $mobile    手机号
     * @return bool|int  合法返回true 不合法 返回错误状态码
     */
    private function _checkRegister($user_name,$password,$nick_name,$mobile)
    {
        //检查用户名合法性
        if(($_isRight = $this->checkName($user_name)) !== true) return $_isRight;
        //检查密码合法性
        if(($_isRight = $this->checkPass($password)) !== true) return $_isRight;
        //检查昵称合法性
        if(($_isRight = $this->checkNick($nick_name)) !== true) return $_isRight;
        //检车手机号合法性
        if(($_isRight = $this->checkMobile($mobile)) !== true) return $_isRight;

        return true;
    }

    /**
     * 注册信息验证
     * @param $user_name
     * @return bool
     */
    private function _isRegister($user_name)
    {
        $user = UserModel::find()->where(['name' => $user_name])->orWhere(['mobile' => $user_name])->one();
        return $user !== NULL ? true : false;
    }

    /**
     * 根据用户名名（手机号），密码查找用户
     * @param string $user_name
     * @param string $password
     * @return array|null|\yii\db\ActiveRecord
     */
    private function _findUser($user_name,$password)
    {
        return UserModel::find()->where('(name = :name OR mobile = :name) AND password = :password',['name' => $user_name,'password' => $password])->one();
    }

    /**
     * 获取token
     * @param int $user_id
     * @return mixed
     * @deprecate 具有代码冗余的嫌疑 暂时先不用
     */
    private function _buildToken($user_id)
    {
        return TokenService::getService()->createToken($user_id);
    }

    /**
     * 登陆事件
     * @param int $user
     * @param string $client_type
     * @throws \yii\base\InvalidConfigException
     */
    private function OnAfterLogin($user,$client_type = NULL)
    {
        $_config = [
            'class' => '\backend\events\LoginEvent',
            'userId' => $user->id,
            'loginTime' => time(),
            'loginIp'  => \yii::$app->getRequest()->userIP,
            'clientType' => $client_type,
        ];
        $user_event = \yii::createObject($_config);
        $this->trigger(static::AFTER_LOGIN_EVENT,$user_event);
    }

    /**
     * 用户注册事件
     */
    private function OnAfterRegister($user_id)
    {
        $registerEvent = new RegisterEvent();
        $registerEvent->userId = $user_id;
        $this->trigger(static::AFTER_REGISTER_EVENT,$registerEvent);
    }

    /**
     * 用户签到事件
     */
    private function OnAfterSign()
    {
        $this->trigger(static::AFTER_SIGN_EVENT);
    }

    private function OnAfterFindPassword()
    {
        $this->trigger(static::AFTER_FIND_PASSWORD_EVENT);
    }

    /**
     * 修改密码事件
     */
    private function OnAfterChangePassword()
    {
        $this->trigger(static::AFTER_CHANGE_PASSWORD_EVENT);
    }
}