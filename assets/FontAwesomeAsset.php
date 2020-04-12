<?php

namespace dominus77\tinymce\assets;

use yii\web\AssetBundle;

/**
 * Class FontAwesomeAsset
 * @package dominus77\tinymce\assets
 */
class FontAwesomeAsset extends AssetBundle
{
    /**
     * @var string
     */
    public $sourcePath = '@vendor/fortawesome/font-awesome';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $min = !YII_ENV_DEV ? '.min' : '';
        $this->css[] = 'css/font-awesome' . $min . '.css';
    }
}
