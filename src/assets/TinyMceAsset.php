<?php

namespace dominus77\tinymce\assets;

use yii\web\AssetBundle;

/**
 * Class TinyMceAsset
 * @package dominus77\tinymce
 */
class TinyMceAsset extends AssetBundle
{
    /**
     * @var string
     */
    public static $tinyPublishPath = '@vendor/tinymce/tinymce';
    /**
     * @var string
     */
    public $sourcePath;

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->sourcePath = self::$tinyPublishPath;
        $min = YII_ENV_DEV ? '' : '.min';
        $this->js[] = 'tinymce' . $min . '.js';
    }
}
