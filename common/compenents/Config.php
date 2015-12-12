<?php

namespace common\compenents;
use \yii\helpers\ArrayHelper;
use \yii\base\InvalidParamException;

/**
 * Class MobileCode
 * @package common\compenents
 */
class Config extends \yii\base\Object
{
    /**
     * List of all loaded config values
     *
     * @var	array
     */
    private static $config = [];

    /**
     * List of all loaded config values
     *
     * @var	array
     * @todo 最近一次加载的配置文件信息，待用
     */
    private $current_config = [];

    /**
     * List of all loaded config files
     *
     * @var	array
     */
    private $is_loaded =	[];

    /**
     * List of paths to search when trying to load a config file.
     *
     * @used-by	CI_Loader
     * @var		array
     */
    private $_config_paths =	 [];

    /**
     * 设置配置文件查找目录
     * @param array $values
     */
    public function setConfigPaths(array $values = [])
    {
        $this->_config_paths = array_merge($this->_config_paths,$values);
    }

    /**
     * Load Config File
     *
     * @param	string	$file			Configuration file name
     * @param   bool   $return             是否返回
     * @param	bool	$use_sections		Whether configuration values should be loaded into their own section
     * @param	bool	$fail_gracefully	Whether to just return FALSE or display an error message
     * @return	bool	TRUE if the file was loaded correctly or FALSE on failure
     */
    public function load($file = '', $return = true, $use_sections = FALSE, $fail_gracefully = FALSE)
    {
        $file = ($file === '') ? 'main' : str_replace('.php', '', $file);
        $loaded = FALSE;

        foreach ($this->_config_paths as $path)
        {
            foreach ([$file, YII_ENV .'/'.$file] as $location)
            {
                $file_path = \yii::getAlias($path.'/config/'.$location.'.php');
                if (in_array($file_path, $this->is_loaded, TRUE))
                {
                    return TRUE;
                }

                if ( ! file_exists($file_path))
                {
                    continue;
                }

                $sction_config = include_once($file_path);

                if ( ! is_array($sction_config))
                {
                    if ($fail_gracefully === TRUE)
                    {
                        return FALSE;
                    }

                    //show_error('Your '.$file_path.' file does not appear to contain a valid configuration array.');
                }

                $this->current_config = ArrayHelper::merge($this->current_config,$sction_config);

                if ($use_sections === TRUE)
                {
                    self::$config[$file] = isset(self::$config[$file])
                        ? ArrayHelper::merge(self::$config[$file], $this->current_config)
                        : $this->current_config;
                }
                else
                {
                    self::$config = ArrayHelper::merge(self::$config, $this->current_config);
                }

                $this->is_loaded[] = $file_path;
                $loaded = TRUE;
                //log_message('debug', 'Config file loaded: '.$file_path);
            }
        }

        if ($loaded === TRUE)
        {
            if($return === true )
                return $this->current_config;
            else
                return TRUE;
        }
        elseif ($fail_gracefully === TRUE)
        {
            return FALSE;
        }

        //show_error('The configuration file '.$file.'.php does not exist.');
    }

    // --------------------------------------------------------------------

    /**
     * Fetch a config file item
     *
     * @param	string	$item	Config item name
     * @param	string	$index	Index name
     * @return	string|null	The configuration item or NULL if the item doesn't exist
     * @todo    获取配置项时候 优先从最近一次加载的配置文件中获取配置信息
     */
    public function item($item, $index = '')
    {
        if ($index == '')
        {

            return ArrayHelper::getValue(self::$config, $item);
        }

        if(is_array(ArrayHelper::getValue(self::$config, $index)))
            return ArrayHelper::getValue(self::$config[$index], $item);

        return null;

    }

    // --------------------------------------------------------------------

    /**
     * Fetch a config file item with slash appended (if not empty)
     *
     * @param	string		$item	Config item name
     * @return	string|null	The configuration item or NULL if the item doesn't exist
     * @todo    获取配置项时候 优先从最近一次加载的配置文件中获取配置信息
     */
    public function slash_item($item)
    {
        $value = ArrayHelper::getValue(self::$config, $item);

        if ($value === null)
        {
            return NULL;
        }
        elseif (trim($value) === '')
        {
            return '';
        }

        return rtrim($value, '/').'/';
    }

    /**
     * Set a config file item
     *
     * @param	string	$item	Config item key
     * @param	string	$value	Config item value
     * @return	void
     * @todo    更新配置信息时，如果是新增 只在主$config中添加 如果是修改：在$current_config中存在时，同时更新两个地方
     */
    public function set_item($item, $value)
    {
        self::$config[$item] = $value;
    }
}