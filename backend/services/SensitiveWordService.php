<?php
/**
 * Created by PhpStorm.
 * User: chao
 * Date: 2015/11/27
 * Time: 14:30
 */
namespace backend\services;

use backend\models\mysql\SensitiveWordModel;

class SensitiveWordService extends BackendService{

    //敏感词类型
    const DICI_CODE_SENSITIVE = 'SENSITIVE';//敏感词
    const DICT_CODE_ALL       = 'ALL';

    /**
     * 获取敏感词列表
     * @param string $type
     * @return array
     */
    public function getSensitiveWord($type = self::DICT_CODE_ALL)
    {
        $_sensitive_words = [];
        $_words = $this->_getWord($type);
        if($_words !== null)
            foreach($_words as $_word)
                if( !empty($_word['datavalue']))
                {
                    $_sensitive_words = \yii\helpers\ArrayHelper::merge($_sensitive_words,explode(',',$_word['datavalue']));
                }
        return $_sensitive_words;
    }

    public function isSensitive($word,$type)
    {
        $_words = $this->getSensitiveWord($type);
        return  in_array($word,$_words);
    }

    private function _getWord($type)
    {
        if($type === self::DICT_CODE_ALL)
            return SensitiveWordModel::find()->asArray()->all();
        else
            return SensitiveWordModel::find()->where(['dictcode'=>$type])->asArray()->all();
    }
}