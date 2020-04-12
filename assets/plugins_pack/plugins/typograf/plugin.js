/**
 * TinyMCE 4
 *
 */
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
        },
        data = {},
        tpDefault = {},
        tpSettings = tinymce.activeEditor.settings.typograf;

    if(tpSettings) {
        data = tpSettings;
    }

    tpDefault.locale = ['ru', 'en-US'];
    tpDefault.mode = 'name';

    if (!data.locale) {
        data.locale = tpDefault.locale;
    }
    if (!data.mode) {
        data.mode = tpDefault.mode;
    }

    scriptLoader.add(url + '/dist/typograf.all.min.js');

    scriptLoader.loadQueue(function () {
        tp = new Typograf(data);
    });

    editor.addButton('typograf', {
        title: 'Typography',
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
            return {
                name: 'Typography',
                url: 'https://github.com/Dominus77/typograf'
            };
        }
    };
});
