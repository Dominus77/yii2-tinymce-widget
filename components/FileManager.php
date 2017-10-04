<?php
namespace dominus77\tinymce\components;

use yii\base\Object;

/**
 * Class FileManager
 * @package dominus77\tinymce\components
 */
abstract class FileManager extends Object
{
    public function init()
    {
        parent::init();
    }

    abstract public function getFilePickerCallback();

    abstract public function registerAsset();
}