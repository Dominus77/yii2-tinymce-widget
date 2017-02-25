<?php

namespace dominus77\tinymce;

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\widgets\InputWidget;
use dominus77\tinymce\components\xCopy;

/**
 * TinyMCE renders a tinyMCE js plugin for WYSIWYG editing.
 */
class TinyMce extends InputWidget
{
    /**
     * Set Language
     * @var string
     */
    public $language = 'en';
    /**
     * @var array the options for the TinyMCE JS plugin.
     * Please refer to the TinyMCE JS plugin Web page for possible options.
     * @see http://www.tinymce.com/wiki.php/Configuration
     */
    public $clientOptions = [];
    /**
     * @var bool whether to set the on change event for the editor. This is required to be able to validate data.
     */
    public $triggerSaveOnBeforeValidateForm = true;

    /**
     * @inheritdoc
     */
    public function run()
    {
        if ($this->hasModel()) {
            echo Html::activeTextarea($this->model, $this->attribute, $this->options);
        } else {
            echo Html::textarea($this->name, $this->value, $this->options);
        }
        $this->registerClientScript();
    }

    /**
     * Registers tinyMCE js plugin
     */
    protected function registerClientScript()
    {
        $js = [];
        $view = $this->getView();
        $languagePack = \Yii::getAlias('@dominus77/tinymce/assets');
        if ($tinyAssetBundle = TinyMceAsset::register($view)) {
            $xCopy = new xCopy();
            $assetPath = $tinyAssetBundle->basePath;
            $xCopy->copyFolder($languagePack, $assetPath, true, true);
        }
        $id = $this->options['id'];
        $this->clientOptions['selector'] = "#$id";
        $this->clientOptions['language'] = isset($this->clientOptions['language']) ? $this->clientOptions['language'] : $this->language;
        $options = Json::encode($this->clientOptions);
        $js[] = "tinymce.init($options);";
        if ($this->triggerSaveOnBeforeValidateForm) {
            $js[] = "$('#{$id}').parents('form').on('beforeValidate', function() { tinymce.triggerSave(); });";
        }
        $view->registerJs(implode("\n", $js));
    }
}
