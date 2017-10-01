<?php
namespace dominus77\tinymce\components;

use yii\base\Object;

abstract class FileManager extends Object
{
    public function init()
    {
        parent::init();
    }

    abstract public function getFilePickerCallback();

    abstract public function getFileBrowserCallback();

    abstract public function registerAsset();
}