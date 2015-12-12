<?php
namespace common\constants;

class ErrorConstant
{
    const SUCCESS = 200;//成功状态吗

    //user module
    const USER_BASE = -100;

    //登陆
    const LOGIN_PARAM_ERROR = self::USER_BASE - 1;//登陆时参数错误
    const USER_NOT_EXISTS   = self::USER_BASE - 2;//登陆时用户不存在
    const USER_PASSWORD_ERROR = self::USER_BASE - 2;//登陆时密码错误
    const USER_IS_LOCKED    = self::USER_BASE - 3;//用户被锁

    //注册
    const REGISTER_PARAM_ERROE = self::USER_BASE - 4;//注册参数错误
    const USER_IS_EXISTS       = self::USER_BASE - 5;//注册时用户已经存在
    const REGISTER_NAME_SENSITIVE = self::USER_BASE - 6;//注册时用户名中包含敏感词
    const REGISTER_NICK_SENSITIVE = self::USER_BASE - 7;//注册时昵称中包含敏感词
    const NICK_NAME_IS_EXISTS   =  self::USER_BASE - 8;//注册时昵称已经存在
    const USER_MOBILE_IS_EXISTS  = self::USER_BASE - 9;//注册时手机号已经存在
    const REGISTER_FAIL          = self::USER_BASE - 10;//注册失败

    //找回密码
    const MOBILE_NOT_REGISTER    = self::USER_BASE - 11;//手机号未注册
    const FORGET_PASSWORD_FAIED  = self::USER_BASE - 12;//找回密码失败

    //修改用户资料
    const USER_ID_ERROR   = self::USER_BASE - 13;//用户ID不合法
    const PASSWORD_UNCONFORMITY   = self::USER_BASE - 14;//两次密码输入不一致
    const CHANGE_PASSWORD_FAILED  = self::USER_BASE - 15;//密码修改失败

    //token module
    const TOKEN_BASE  = -300;
    const USER_TOKEN_NOT_EXISTS  = self::TOKEN_BASE - 1;//token不存在
    const USER_TOKEN_OVERDUE     = self::TOKEN_BASE - 2;//token过期
    const USER_TOKEN_CREATE_FAILED = self::TOKEN_BASE - 4;//token生成失败
    const USER_TOKEN_REFRESH_FAILED = self::TOKEN_BASE -5;//token刷新失败

    //sign module
    const SIGN_BASE  = -400;
    const USER_IS_SIGNED  = self::SIGN_BASE - 1;//当前已经签到
    const USER_SIGN_FAILED  = self::SIGN_BASE - 2;//签到失败

    //code module
    const CODE_BASE = -500;
    const MOBILE_NOT_VALIDITY = self::CODE_BASE -1;//手机号格式不合法
    const CODE_NOT_VALIDITY = self::CODE_BASE -2;//验证码格式不正确
    const CODE_CHECKED_FAILED = self::CODE_BASE -3;//验证码校验失败

    //invitation code
    const  INVITATION_CODE_BASE = -600;
    const CREATE_INVITATION_CODE_FAILED = self::INVITATION_CODE_BASE - 1;//验证码创建失败
}