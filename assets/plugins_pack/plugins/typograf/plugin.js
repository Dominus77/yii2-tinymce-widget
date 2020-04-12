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
        icon: 'blockquote',
        onclick: typo
    });

    editor.addMenuItem('typograf', {
        context: 'format',
        text: 'Typography',
        icon: 'blockquote',
        onclick: typo
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
