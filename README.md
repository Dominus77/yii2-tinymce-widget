README
======

Renders a [TinyMCE WYSIWYG text editor](https://www.tinymce.com) widget.

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
<?php
...
use dominus77\tinymce\TinyMce;
...
?>

<?= $form->field($model, 'text')->widget(TinyMce::className(), [    
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
            'https://netdna.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css'
        ]
    ]
]);?>

```

Further Information
-----
Please, check the [TinyMCE plugin site](https://www.tinymce.com/docs/configure/) documentation for further information about its configuration options.

License
-----
The BSD License (BSD). Please see [License File](https://github.com/Dominus77/yii2-tinymce-widget/blob/master/LICENSE.md) for more information.