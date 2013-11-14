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
        }
    });

    var tree = Ext.create('Ext.tree.Panel', {
        store: store,
        rootVisible: false,
        autoScroll: true,
        height: 800
    });


    Ext.create('Ext.Panel', {
        renderTo: 'tree-contractor',
        autoScroll: true,
        layout: {
            type: 'vbox',
            align : 'stretch',
            pack  : 'start'
        },
        title: 'Список контрагентов',
        items: [tree]
    });
});