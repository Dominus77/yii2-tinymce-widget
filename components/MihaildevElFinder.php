<?php

namespace dominus77\tinymce\components;

use Yii;
use yii\base\InvalidConfigException;
use yii\helpers\Json;
use yii\web\JsExpression;
use yii\web\View;

/**
 * Class MihaildevElFinder
 * @package dominus77\tinymce\components
 */
class MihaildevElFinder extends FileManager
{
    public $tinyMceSettings = [];
    /** @var  View */
    public $parentView;
    public $assets = [
        '\mihaildev\elfinder\AssetsCallBack',
        '\mihaildev\elfinder\Assets'
    ];
    /**
     * @var string
     */
    public $controller = 'elfinder';

    /**
     * @var string
     */
    public $language;

    /**
     * @var string
     */
    public $filter;

    /**
     * @var string
     */
    public $path;

    /**
     * @var string
     */
    public $title = 'ElFinder';

    /**
     * @var int
     */
    public $width = 900;

    /**
     * @var int
     */
    public $height = 600;

    /**
     * @var string
     */
    public $resizable = 'yes';

    /**
     * @var bool
     */
    public $multiple;

    /**
     * @var string
     */
    private $_id;

    /**
     * @var int
     */
    private static $_counter = 0;

    /**
     * @var string
     */
    private $managerUrl;

    /**
     * @return string
     */
    public function getId()
    {
        if ($this->_id !== null) {
            return $this->_id;
        }
        return $this->_id = 'elfd' . self::$_counter++;
    }

    /**
     * Manager Options
     * @return array
     */
    private function getManagerOptions()
    {
        if (!$this->language) {
            $this->language = $this->tinyMceSettings['language'];
        }

        $managerOptions = [
            'callback' => $this->getId(),
        ];

        if (!empty($this->filter)) {
            $managerOptions['filter'] = $this->filter;
        }

        if (!empty($this->language)) {
            $managerOptions['lang'] = $this->language;
        }

        if (!empty($this->multiple)) {
            $managerOptions['multiple'] = $this->multiple;
        }

        if (!empty($this->path)) {
            $managerOptions['path'] = $this->path;
        }

        $managerOptions[0] = '/' . $this->controller . '/manager';
        $this->managerUrl = Yii::$app->urlManager->createUrl($managerOptions);

        return $managerOptions;
    }

    /**
     * @return JsExpression
     */
    public function getFilePickerCallback()
    {
        $this->getManagerOptions();

        $script = new JsExpression('
            mihaildev.elFinder.register(' . Json::encode($this->getId()) . ', function (file, fm) {
                parent.tinymce.activeEditor.windowManager.getParams().oninsert(file, fm);
                parent.tinymce.activeEditor.windowManager.close();
                return false;
            });
        ');
        $this->parentView->registerJs($script);

        $script = new JsExpression("
            function(callback, value, meta) {
                tinymce.activeEditor.windowManager.open({
                    file: '{$this->managerUrl}',
                    title: '{$this->title}',
                    width: '{$this->width}',
                    height: '{$this->height}',
                    resizable: '{$this->resizable}'
                }, {
                    oninsert: function(file, elf) {
                        var url, reg, info;

                        // URL normalization
                        url = file.url;
                        reg = /\\/[^/]+?\\/\\.\\.\\//;
                        while(url.match(reg)) {
                            url = url.replace(reg, '/');
                        }

                        // Make file info
                        info = file.name + ' (' + elFinder.prototype.formatSize(file.size) + ')';

                        // Provide file and text for the link dialog
                        if (meta.filetype == 'file') {
                            callback(url, {text: info, title: info});
                        }

                        // Provide image and alt text for the image dialog
                        if (meta.filetype == 'image') {
                            callback(url, {alt: info});
                        }

                        // Provide alternative source and posted for the media dialog
                        if (meta.filetype == 'media') {
                            callback(url);
                        }
                    }
                });
                return false;
            }
        ");
        return $script;
    }

    /**
     * @return bool|mixed
     * @throws InvalidConfigException
     */
    public function registerAsset()
    {
        if (!is_array($this->assets)) {
            $this->assets = [$this->assets];
        }
        foreach ($this->assets as $asset) {
            $this->parentView->registerAssetBundle($asset);
        }
        return true;
    }
}
