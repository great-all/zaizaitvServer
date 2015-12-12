<?php
/**
 * Created by PhpStorm.
 * User: chao
 * Date: 2015/12/2
 * Time: 19:13
 */
namespace common\helpers;

class MobileHelper {
    public static function isMobile($mobile)
    {
        return preg_match("/^13[0-9]{1}[0-9]{8}$|15[0189]{1}[0-9]{8}$|189[0-9]{8}$/",$mobile) ? true : false;
    }
}
