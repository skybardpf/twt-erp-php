Ext.require([
    'Ext.data.*',
    'Ext.grid.*',
    'Ext.tree.*'
]);

Ext.onReady(function() {
    Ext.QuickTips.init();

    var store = Ext.create('Ext.data.TreeStore', {
        proxy: {
            data : global_data, // instead it goes here
            type: 'memory',
            reader: {
                type: 'json'
            }
        },
        root: {
            text: 'Все шаблоны',
            id: 'root',
            expanded: true
        }
    });

    var tree = Ext.create('Ext.tree.Panel', {
        store: store,
        rootVisible: true,
        autoScroll: true
    });


    Ext.create('Ext.Panel', {
        renderTo: 'tree-template-library',
        autoScroll: true,
        layout: {
            type: 'vbox',
            align : 'stretch',
            pack  : 'start'
        },
        title: 'Библиотека шаблонов',
        items: [tree]
    });
});