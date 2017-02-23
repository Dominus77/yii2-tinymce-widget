<?php
/**
 * Created by PhpStorm.
 * User: Alexey Shevchenko <ivanovosity@gmail.com>
 * Date: 23.02.17
 * Time: 8:59
 */

namespace dominus77\tinymce;

use yii\web\AssetBundle;

class TinyMceAsset extends AssetBundle
{
    public static $publishPath = '@vendor/tinymce/tinymce';

    public $sourcePath;

    public $js = [];

    public $depends = [];

    public function init()
    {
        $this->sourcePath = self::$publishPath;
        $min = YII_ENV_DEV ? '' : '.min';
        $this->js[] = 'tinymce' . $min . '.js';
        $this->depends[] = 'dominus77\tinymce\TinyMceLangAsset';
    }
}