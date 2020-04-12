tinymce.PluginManager.requireLangPack('typograf', 'en,ru');
tinymce.PluginManager.add('typograf', function (editor, url) {
    'use strict';

    let scriptLoader = new tinymce.dom.ScriptLoader(),
        tp,
        typo = function () {
            if (tp) {
                editor.setContent(tp.execute(editor.getContent()));
                editor.undoManager.add();
            }
        };

    scriptLoader.add(url + '/dist/typograf.all.min.js');

    scriptLoader.loadQueue(function () {
        tp = new Typograf({
            locale: ['ru', 'en-US'],
            mode: 'name'
        });
    });

    editor.ui.registry.addButton('typograf', {
        text: 'Typography',
        tooltip: 'Typography',
        onAction: function (_){
            typo
        }
        //onclick: typo
    });

    editor.ui.registry.addMenuItem('typograf', {
        context: 'format',
        text: 'Typography',
        icon: 'blockquote',
        onAction: function (_){
            typo
        }
        //onclick: typo
    });

    return {
        getMetadata: function () {
            return  {
                name: 'Typography',
                url: 'https://github.com/Dominus77/typograf'
            };
        }
    };
});
