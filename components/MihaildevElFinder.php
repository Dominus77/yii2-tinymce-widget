<?php
namespace dominus77\tinymce\components;

use Yii;
use yii\helpers\Json;
use yii\web\JsExpression;

/**
 * Class MihaildevElFinder
 * @package dominus77\tinymce\components
 */
class MihaildevElFinder extends \dominus77\tinymce\components\FileManager
{
    public $tinyMceSettings = [];
    /** @var  \yii\web\View */
    public $parentView;
    public $assets = [
        '\mihaildev\elfinder\AssetsCallBack',
        '\mihaildev\elfinder\Assets'
    ];
    public $controller = 'elfinder';
    public $language;
    public $filter;
    public $path;
    public $title = 'ElFinder';
    public $width = 900;
    public $height = 600;
    public $resizable = 'yes';
    public $multiple;

    private $_id;
    private static $_counter = 0;
    private $managerUrl;

    public function getId()
    {
        if ($this->_id !== null) {
            return $this->_id;
        } else {
            return $this->_id = 'elfd' . self::$_counter++;
        }
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

        $managerOptions[0] = '/' . $this->controller . "/manager";
        $this->managerUrl = Yii::$app->urlManager->createUrl($managerOptions);

        return $managerOptions;
    }

    /**
     * @return JsExpression
     */
    public function getFilePickerCallback()
    {
        $this->getManagerOptions();

        $script = new JsExpression("
            mihaildev.elFinder.register(" . Json::encode($this->getId()) . ", function (file, fm) {
                parent.tinymce.activeEditor.windowManager.getParams().oninsert(file, fm);
                parent.tinymce.activeEditor.windowManager.close();
                return false;
            });
        ");
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
     * Fix Bug TinyMCE 4.6.7
     * @see https://github.com/tinymce/tinymce/issues/3939
     * @return JsExpression
     */
    public function getFilePickerFixCallback()
    {
        $this->getManagerOptions();

        $script = new JsExpression("
            mihaildev.elFinder.register(" . Json::encode($this->getId()) . ", function (file, fm) {
                parent.tinymce.activeEditor.windowManager.getParams().oninsert(file, fm);
                return false;
            });
        ");
        $this->parentView->registerJs($script);

        $script = new JsExpression("
            function(callback, value, meta) {
                var editor = tinymce.activeEditor.windowManager.open({
                    file: '{$this->managerUrl}',
                    title: '{$this->title}',
                    width: '{$this->width}',
                    height: '{$this->height}',
                    resizable: '{$this->resizable}'
                });
                tinymce.activeEditor.windowManager.setParams({
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

                        editor.close();
                    }
                });
                return false;
            }
        ");
        return $script;
    }

    /**
     * @throws \yii\base\InvalidConfigException
     */
    public function registerAsset()
    {
        if (!is_array($this->assets)) {
            $this->assets = [$this->assets];
        }
        foreach ($this->assets as $asset) {
            $this->parentView->registerAssetBundle($asset);
        }
    }
}