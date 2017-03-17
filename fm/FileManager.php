<?php
namespace dominus77\tinymce\fm;

use yii\base\Object;
use yii\web\JsExpression;
use yii\web\View;

abstract class FileManager extends Object
{
    public function init(){}

    abstract public function getFileBrowserCallback();

    abstract public function registerAsset();
}