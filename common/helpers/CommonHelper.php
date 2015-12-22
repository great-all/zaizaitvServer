<?php

namespace common\helpers;
use \yii\base\InvalidConfigException;
use \yii\base\InvalidParamException;
use \yii\helpers\ArrayHelper;
/**
 * Class CommonHelper
 * @package common\helpers
 */
class CommonHelper {

    /**
     * @param $file
     * @param array $config_path
     * @return array
     * @throws InvalidConfigException
     */
    public static function loadConfig($file,$config_path = [])
    {
        static $_config_paths = ['@common', '@backend'];
        if(!empty($config_path)) $_config_paths = $config_path;
        $_config = [];

        $file = ($file === '') ? 'main' : str_replace('.php', '', $file);

        foreach ($_config_paths as $path)
        {
            foreach ([$file, YII_ENV .'/'.$file] as $location)
            {
                $file_path = \yii::getAlias($path.'/config/'.$location.'.php');

                if ( ! file_exists($file_path))
                {
                    continue;
                }

                $_config_section = include_once($file_path);

                if ( ! is_array($_config_section))
                    throw new InvalidConfigException('Your '.$file_path.' file does not appear to contain a valid configuration array.');

                $_config = ArrayHelper::merge($_config, $_config_section);
            }
        }
        return $_config;
    }

    /**
     * 生成指定长度的随机字符串
     *
     * @param int $length
     * @param string $source
     * @return string
     */
    public static function randString($length = 6,$source = '')
    {
        $source = is_string($source) && $source !== '' ? $source : 'QWERTYUIOPASDFGHJKLZXCVBNM1234567890qwertyuiopasdfghjklzxcvbnm';
        $_strLength = strlen($source);
        if($length > $_strLength)
            throw new InvalidParamException('Param error for $length too long ' . $length);

        $_str = '';
        for($i = 0; $i <$length; ++$i )
        {
            srand(time());
            $_ranm = mt_rand(0,$_strLength - 1);
            $_str .= $source[$_ranm];
        }

        return $_str;
    }

    /**
     * 手机号格式验证
     *
     * @param string $mobile
     * @return bool
     */
    public static function isMobile($mobile)
    {
        return preg_match('/^1[3|4|5|8][0-9]\d{4,8}$/',$mobile) ? true : false;
    }
}
