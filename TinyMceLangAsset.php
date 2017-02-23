<?php

namespace dominus77\tinymce;

use yii\web\AssetBundle;

class TinyMceLangAsset extends AssetBundle
{
    public static $publishPath = '@dominus77/tinymce/assets';

    public $sourcePath;
    public $depends = [];

    public function init()
    {
        $this->sourcePath = self::$publishPath;
        //$this->depends[] = 'dominus77\tinymce\TinyMceAsset';
    }
}