<?php
namespace common\compenents;
use yii\base\InvalidConfigException;
use yii\helpers\ArrayHelper;
/**
 * Language Class
 *
 * @package		CodeIgniter
 * @subpackage	Libraries
 * @category	Language
 * @author		EllisLab Dev Team
 * @link		http://codeigniter.com/user_guide/libraries/language.html
 */
class Lang extends \yii\base\Object{

    /**
     * List of translations
     *
     * @var	array
     */
    private $language =	[];

    /**
     * @var array
     */
    private $_current_lang = [];

    /**
     * @var array
     */
    private $_lang_paths = ['@common'];

    /**
     * List of loaded language files
     *
     * @var	array
     */
    private $is_loaded = [];

    /**
     * @param $langfile
     * @param string $idiom
     * @param bool|FALSE $return
     * @return array|bool|void
     * @throws InvalidConfigException
     */
    public function load($langfile, $idiom = '', $return = FALSE)
    {
        if (is_array($langfile))
        {
            foreach ($langfile as $value)
            {
                $this->load($value, $idiom, $return);
            }

            return;
        }

        $langfile = str_replace('.php', '', $langfile) . '_lang.php';

        if (empty($idiom) OR ! preg_match('/^[a-z_-]+$/i', $idiom))
        {
            $idiom = \yii::$app->language ?: 'zh_cn';
        }

        if ($return === FALSE && isset($this->is_loaded[$langfile]) && $this->is_loaded[$langfile] === $idiom)
        {
            return;
        }

        $_is_load = false;
        // Load the base file, so any others found can override it
        $lang = [];

        //循环获取多个模块下的语言文件
        foreach($this->_lang_paths as $path)
        {
            $_file_path = \yii::getAlias($path.'/lang/'.$idiom.'/'.$langfile);

            if( ! file_exists($_file_path))
                continue;

            $section_lang = include_once($_file_path);
            if( ! is_array($section_lang))
            {
                if($return === true)
                    return [];
                else
                    throw new InvalidConfigException('Language file contains no data: language/'.$idiom.'/'.$langfile);
            }

            $_is_load = true;

            $lang = ArrayHelper::merge($lang,$section_lang);
        }

        if ($_is_load !== TRUE)
        {
            if($return === true)
                return [];
            else
                throw new InvalidConfigException('Unable to load the requested language file: language/'.$idiom.'/'.$langfile);
        }

        $this->is_loaded[$langfile] = $idiom;
        $this->_current_lang = $lang;
        $this->language = ArrayHelper::merge($this->language, $lang);

        if ($return === TRUE)
        {
            return $lang;
        }

        return TRUE;
    }

    // --------------------------------------------------------------------

    /**
     * @param $line
     * @param string $defaultValue
     * @return string
     */
    public function line($line, $defaultValue = null)
    {
        return isset($this->language[$line]) ? $this->language[$line] : $defaultValue;
    }

}
