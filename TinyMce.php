<?php

namespace dominus77\tinymce;

use Yii;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\widgets\InputWidget;
use dominus77\tinymce\assets\TinyMceAsset;
use dominus77\tinymce\components\xCopy;

/**
 * Class TinyMce
 * @package dominus77\tinymce
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
     * @var bool|array FileManager configuration
     * For example:
     * 'fileManager' => array(
     *       'class' => 'FileManager',
     * )
     */
    public $fileManager = false;

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
        if ($tinyAssetBundle = TinyMceAsset::register($view)) {
            $xCopy = new xCopy();
            $assetPath = $tinyAssetBundle->basePath;
            // Language pack
            $languagesPack = \Yii::getAlias('@dominus77/tinymce/assets/languages_pack');
            $xCopy->copyFolder($languagesPack, $assetPath, true, true);
            // Plugins
            $pluginsPack = \Yii::getAlias('@dominus77/tinymce/assets/plugins_pack');
            $xCopy->copyFolder($pluginsPack, $assetPath, true, true);
            // Skins
            $skinsPack = \Yii::getAlias('@dominus77/tinymce/assets/skins_pack');
            $xCopy->copyFolder($skinsPack, $assetPath, true, true);
        }
        $id = $this->options['id'];
        $this->clientOptions['selector'] = "#$id";
        $this->clientOptions['language'] = isset($this->clientOptions['language']) ? $this->clientOptions['language'] : $this->language;

        if ($this->fileManager !== false) {
            /** @var $fm \dominus77\tinymce\components\FileManager */
            $fm = Yii::createObject(array_merge($this->fileManager, [
                'tinyMceSettings' => $this->clientOptions,
                'parentView' => $view]));
            $fm->init();
            $fm->registerAsset();

            $this->clientOptions['file_picker_callback'] = $fm->getFilePickerCallback();
        }

        $options = Json::encode($this->clientOptions);
        $js[] = "tinymce.init($options);";
        if ($this->triggerSaveOnBeforeValidateForm) {
            $js[] = "$('#{$id}').parents('form').on('beforeValidate', function() { tinymce.triggerSave(); });";
        }
        $view->registerJs(implode("\n", $js));
    }
}
