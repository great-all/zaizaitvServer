<?php
/**
 * Created by PhpStorm.
 * User: chao
 * Date: 2015/12/9
 * Time: 19:22
 */
namespace common\helpers;

class DateHelper {

    /**
     * 获取当前时间错
     * @param string $timezone
     * @return int
     */
    public static function now($timezone = 'local')
    {
        if ($timezone === 'local' OR $timezone === date_default_timezone_get())
        {
            return time();
        }

        $datetime = new DateTime('now', new DateTimeZone($timezone));
        sscanf($datetime->format('j-n-Y G:i:s'), '%d-%d-%d %d:%d:%d', $day, $month, $year, $hour, $minute, $second);

        return mktime($hour, $minute, $second, $month, $day, $year);
    }

    /**
     * 返回一个时间段的开始时间点
     * @return int
     */
    public static function startDate()
    {
        return strtotime(date('Y-m-d').' 00:00:00');

    }

    /**
     * 返回一个时间段的截至时间点
     * @return int
     */
    public static function endDate($date = '',$format = '')
    {
        return strtotime(date('Y-m-d').' 23:59:59');
    }
}