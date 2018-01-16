<?php

namespace dominus77\tinymce\components;

use yii\base\BaseObject;

/**
 * Class FileManager
 * @package dominus77\tinymce\components
 */
abstract class FileManager extends BaseObject
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
    }

    abstract public function getFilePickerCallback();

    abstract public function registerAsset();
}
