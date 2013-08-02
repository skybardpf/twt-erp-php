Ext.require([
    'Ext.data.*',
    'Ext.grid.*',
    'Ext.tree.*'
]);

Ext.onReady(function() {
    Ext.QuickTips.init();


    var store = Ext.create('Ext.data.TreeStore', {
        proxy: {                        // указание типа и  источника данных
            type: 'ajax',   //  тип данных - ajax
            url: '/legal/contractor/_json_contractor_groups' //  урл источника данных
        }
    });

    var tree = Ext.create('Ext.tree.Panel', {
        store: store,
        rootVisible: false,
//        root: {
//            expanded: true
//        },
        autoScroll: true
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
//        width: 200,
//        height: 150,
        items: [
            tree
        ]
    });
});