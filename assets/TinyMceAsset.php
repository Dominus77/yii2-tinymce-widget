<?php

namespace dominus77\tinymce\assets;

use yii\web\AssetBundle;

/**
 * Class TinyMceAsset
 * @package dominus77\tinymce
 */
class TinyMceAsset extends AssetBundle
{
    public static $tinyPublishPath = '@vendor/tinymce/tinymce';

    public $sourcePath;

    public $js = [];

    public function init()
    {
        $this->sourcePath = self::$tinyPublishPath;
        $min = YII_ENV_DEV ? '' : '.min';        
        $this->js[] = 'tinymce' . $min . '.js';            
    }
}