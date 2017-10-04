yii2-tinymce-widget
======

Renders a [TinyMCE WYSIWYG text editor](https://www.tinymce.com) widget with the support [ElFinder Extension for Yii 2](https://github.com/MihailDev/yii2-elfinder)

Supplements
------------
A plugin [TinyMCE-FontAwesome-Plugin](https://github.com/josh18/TinyMCE-FontAwesome-Plugin/tree/master) that lets you insert FontAwesome icons via TinyMCE.

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require dominus77/yii2-tinymce-widget "*"
```

or add

```
"dominus77/yii2-tinymce-widget": "*"
```

to the require section of your `composer.json` file.


Usage
-----

Once the extension is installed, simply use it in your code by  :

```php
<?= $form->field($model, 'text')->widget(\dominus77\tinymce\TinyMce::className(), [    
    'options' => [
        'rows' => 6
    ], 
    'language' => 'ru',
    'clientOptions' => [        
        'theme' => 'modern',
        'plugins' => [
            "advlist autolink lists link image charmap print preview hr anchor pagebreak",
            "searchreplace wordcount visualblocks visualchars code fullscreen",
            "insertdatetime media nonbreaking save table contextmenu directionality",
            "emoticons template paste textcolor colorpicker textpattern imagetools codesample toc fontawesome noneditable",
        ],
        'noneditable_noneditable_class' => 'fa',
        'extended_valid_elements' => 'span[class|style]',
        'toolbar1' => "undo redo | insert | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image",
        'toolbar2' => "print preview media | forecolor backcolor emoticons fontawesome | codesample",
        'image_advtab' => true,
        'templates' => [
            [
                'title' => 'Test template 1',
                'content' => 'Test 1',
            ],
            [
                'title' => 'Test template 2',
                'content' => 'Test 2',
            ]
        ],
        'content_css' => [
            '//fonts.googleapis.com/css?family=Lato:300,300i,400,400i',
            '//www.tinymce.com/css/codepen.min.css',
            '//netdna.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css'
        ]
    ]
]);?>

```

ElFinder file manager
-----
Install [mihaildev/yii2-elfinder](https://github.com/MihailDev/yii2-elfinder) extension.

Either run

```
php composer.phar require --prefer-dist mihaildev/yii2-elfinder "*"
```

or add

```
"mihaildev/yii2-elfinder": "*"
```

Configure elFinder (more info [here](https://github.com/MihailDev/yii2-elfinder))

```php
'controllerMap' => [
    'elfinder' => [
        'class' => 'mihaildev\elfinder\Controller',
        'access' => ['@'], //Global file manager access @ - for authorized , ? - for guests , to open to all ['@', '?']
        'disabledCommands' => ['netmount'], //disabling unnecessary commands https://github.com/Studio-42/elFinder/wiki/Client-configuration-options#commands
        'roots' => [
            [
                'baseUrl'=>'@web',
                'basePath'=>'@webroot',
                'path' => 'files/global',
                'name' => 'Global'
            ],
            [
                'class' => 'mihaildev\elfinder\volume\UserPath',
                'path'  => 'files/user_{id}',
                'name'  => 'My Documents'
            ],
            [
                'path' => 'files/some',
                'name' => ['category' => 'my','message' => 'Some Name'] //перевод Yii::t($category, $message)
            ],
            [
                'path'   => 'files/some',
                'name'   => ['category' => 'my','message' => 'Some Name'], // Yii::t($category, $message)
                'access' => ['read' => '*', 'write' => 'UserFilesAccess']  // * - for all, otherwise the access check in this example can be seen by all users with rights only UserFilesAccess
            ]
        ],
        'watermark' => [
             'source'         => __DIR__.'/logo.png', // Path to Water mark image
             'marginRight'    => 5,          // Margin right pixel
             'marginBottom'   => 5,          // Margin bottom pixel
             'quality'        => 95,         // JPEG image save quality
             'transparency'   => 70,         // Water mark image transparency ( other than PNG )
             'targetType'     => IMG_GIF|IMG_JPG|IMG_PNG|IMG_WBMP, // Target image formats ( bit-field )
             'targetMinPixel' => 200         // Target image minimum pixel size
        ]
    ]
],
```

Then select file manager provider in the widget:

```php
$form->field($model, 'text')->widget(\dominus77\tinymce\TinyMce::className(), [    
    'clientOptions' => [
        //...
        /** @see https://www.tinymce.com/docs/configure/file-image-upload/#file_picker_types */
        //'file_picker_types' => 'file image media',        
    ],
    'fileManager' => [
        'class' => \dominus77\tinymce\components\MihaildevElFinder::className(),
    ],    
    //...
])
```

You can customize some window (width and height) and manager (language, filter, path and multiple) properties. If you want to use different access, watermark and roots setting just prepare controllers:

```php
'controllerMap' => [
    'elf1' => [
        'class' => 'mihaildev\elfinder\Controller',
        'access' => ['@'],
        'roots' => [
            [
                'baseUrl'=>'@web',
                'basePath'=>'@webroot',
                'path' => 'files/global',
                'name' => 'Global'
            ],
        ],
        'watermark' => [
            'source'         => __DIR__.'/logo.png', // Path to Water mark image
            'marginRight'    => 5,          // Margin right pixel
            'marginBottom'   => 5,          // Margin bottom pixel
            'quality'        => 95,         // JPEG image save quality
            'transparency'   => 70,         // Water mark image transparency ( other than PNG )
            'targetType'     => IMG_GIF|IMG_JPG|IMG_PNG|IMG_WBMP, // Target image formats ( bit-field )
            'targetMinPixel' => 200         // Target image minimum pixel size            
        ],
    ],
    'elf2' => [
        'class' => 'mihaildev\elfinder\Controller',
        'access' => ['*'],
        'roots' => [
            [                
                'path' => 'files/some1',
                'name' => ['category' => 'my','message' => 'Some Name']
            ],
            [                
                'path' => 'files/some2',
                'name' => ['category' => 'my','message' => 'Some Name'],
                'access' => ['read' => '*', 'write' => 'UserFilesAccess']
            ],
        ],
    ],
]
```

Connection in the module:

```php
namespace modules\example;

class Module extends \yii\base\Module
{
    //...
    public function init()
    {
        parent::init();
        $this->controllerMap = [
            'elfinder' => [
                'class' => 'mihaildev\elfinder\Controller',
                'access' => ['@'],
                'disabledCommands' => ['netmount'],
                'roots' => [
                    [
                        'baseUrl'=>'@web',
                        'basePath'=>'@webroot',
                        'path' => 'files/global',
                        'name' => 'Global'
                    ],
                ],
                'watermark' => [
                    'source'         => __DIR__.'/logo.png', // Path to Water mark image
                    'marginRight'    => 5,          // Margin right pixel
                    'marginBottom'   => 5,          // Margin bottom pixel
                    'quality'        => 95,         // JPEG image save quality
                    'transparency'   => 70,         // Water mark image transparency ( other than PNG )
                    'targetType'     => IMG_GIF|IMG_JPG|IMG_PNG|IMG_WBMP, // Target image formats ( bit-field )
                    'targetMinPixel' => 200         // Target image minimum pixel size            
                ],
            ]
        ];
    }
    //...
}
```

in module view:

```php
$form->field($model, 'text')->widget(\dominus77\tinymce\TinyMce::className(), [    
    'clientOptions' => [
        //...
        /** @see https://www.tinymce.com/docs/configure/file-image-upload/#file_picker_types */
        //'file_picker_types' => 'file image media',
    ],
    'fileManager' => [
        'class' => \dominus77\tinymce\components\MihaildevElFinder::className(),
        'controller' => 'elfinder',        
        'title' => 'My File Manager',
        'width' => 900,
        'height' => 600,
        'resizable' => 'yes',
    ],    
    //...
]);
```

Further Information
-----
Please, check the [TinyMCE site](https://www.tinymce.com/docs/configure/) and [ElFinder Extension](https://github.com/MihailDev/yii2-elfinder) documentation for further information about its configuration options.

License
-----
The BSD License (BSD). Please see [License File](https://github.com/Dominus77/yii2-tinymce-widget/blob/master/LICENSE.md) for more information.