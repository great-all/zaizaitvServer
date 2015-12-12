<?php
namespace backend\services\users;

use backend\services\BackendService;
use backend\models\redis\TokenModel;
use common\constants\ErrorConstant;
/**
 * Class TokenService
 * @package backend\services\users
 */
class TokenService extends BackendService{

    /**
     * 根据token 获取用户id
     * @param string $token token
     * @return mixed|null
     */
    public function getIdByToken($token)
    {
        $_token = TokenModel::find()->where(['token' => $token])->asArray()->one();

        if($_token === null || empty($_token['userId'])) return ErrorConstant::USER_TOKEN_NOT_EXISTS;

        //判断token是否过期
        if(!empty($_token['update_time']) && $_token['update_time'] + TokenModel::EXPIRED_TIME < time())
            return ErrorConstant::USER_TOKEN_OVERDUE;

        return intval($_token['userId']);
    }

    /**
     * 生成token 支持同一用户多个token(支持多点登陆)
     * @param int $user_id
     * @return bool|string
     */
    public function createToken($user_id)
    {
        if(! is_numeric($user_id))
            return ErrorConstant::USER_ID_ERROR;

        //判断该用户是否有token(单点登录)
        $token_model = TokenModel::findOne(['userId' => $user_id]);
        if($token_model !== null) $token_model->delete();

        //生成新token
        $token['token']  = $this->_createToken();
        $token['userId'] = $user_id;

        $token_model = new TokenModel();
        if($token_model->load($token,''))
            if($token_model->insert())
                return $token_model->token;

        return ErrorConstant::USER_TOKEN_CREATE_FAILED;//生成token失败
    }

    /**
     * 刷新token
     * @param string $token
     * @return int|string
     */
    public function refreshToken($token)
    {
        $_token = static::findOne(['token' => $token]);
        if($_token === null) return ErrorConstant::USER_TOKEN_NOT_EXISTS;

        //判断token是否已经过期
        if($_token->update_time + tokenModel::EXPIRED_TIME < time())
            return ErrorConstant::USER_TOKEN_OVERDUE;

        $_token->token = $this->_createToken();

        if($_token->update())
            return $_token->token;
        else
            return ErrorConstant::USER_TOKEN_REFRESH_FAILED;
    }

    /**
     * 生成token算法
     * @return string
     */
    public function _createToken()
    {
        do{
            $radStr = \common\helpers\CommonHelper::randString(16);
            if(TokenModel::findOne(['token' => $radStr]) === null)
                return $radStr;
        }while(true);
    }
}