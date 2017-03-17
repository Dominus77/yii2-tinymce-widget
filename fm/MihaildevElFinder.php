<?php
namespace dominus77\tinymce\fm;

use Yii;
use yii\base\Exception;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\web\View;

class MihaildevElFinder extends \dominus77\tinymce\fm\FileManager
{
    public $tinyMceSettings = [];
    /** @var  \yii\web\View */
    public $parentView;
    public $assets = [
        '\mihaildev\elfinder\AssetsCallBack'
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

    public function getFileBrowserCallback()
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
        $this->parentView->registerJs("mihaildev.elFinder.register(" . Json::encode($this->getId()) . ", function (file, id) { parent.tinymce.activeEditor.windowManager.getParams().setUrl(file.url); parent.tinymce.activeEditor.windowManager.close(); });");
        $script = new JsExpression("
            function(field_name, url, type, win) {
                tinymce.activeEditor.windowManager.open({
                    file: '{$this->managerUrl}',
                    title: '{$this->title}',
                    width: '{$this->width}',
                    height: '{$this->height}',
                    resizable: '{$this->resizable}'
                }, {
                    setUrl: function (url) {
                        win.document.getElementById(field_name).value = url;
                    }
                });
                return false;
            }
        ");
        return $script;
    }

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