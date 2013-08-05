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
            url: '/legal/contractor_group/_json_contractor_groups' //  урл источника данных
        }
    });

    var tree = Ext.create('Ext.tree.Panel', {
        store: store,
        rootVisible: false,
        autoScroll: true,
        buttons: [
            { text: 'Добавить', handler: onClickAdd },
            { text: 'Переименовать', handler: onClickEdit },
            { text: 'Удалить', handler: onClickDel }
        ]
    });


    Ext.create('Ext.Panel', {
        renderTo: 'tree-contractor',
        autoScroll: true,
        layout: {
            type: 'vbox',
            align : 'stretch',
            pack  : 'start'
        },
        title: 'Список групп контрагентов',
        items: [tree]
    });

    function onClickAdd(){
        var selectedNode = tree.getSelectionModel().getSelection();
        if (Ext.isEmpty(selectedNode)){
            Ext.Msg.alert('Выберите корневой элемент.', 'Выберите корневой элемент.');
        }
        console.log(selectedNode);
    }

    function onClickEdit(){
        var selectedNode = tree.getSelectionModel().getSelection();
        if (Ext.isEmpty(selectedNode)){
            Ext.Msg.alert('Ошибка', 'Выберите группу для редактирования.');
            return false;
        }

        Ext.MessageBox.prompt('Название', 'Введите название группы:', enterGroupName, this, false, selectedNode.getName());
        return true;
    }

    function enterGroupName(){
        console.log(arguments);
    }

    /**
     * @returns {boolean}
     */
    function onClickDel(){
        var selectedNode = tree.getSelectionModel().getSelection();
        if (Ext.isEmpty(selectedNode)){
            Ext.Msg.alert('Ошибка', 'Выберите группу для удаления.');
            return false;
        }
        selectedNode = selectedNode[0];

        if (selectedNode.hasChildNodes()){
            Ext.Msg.alert('Ошибка', 'Нельзя удалить группу, у которой присутствуют подгруппы.');
            return false;
        }

        Loading.show();
        Ext.Ajax.request({
            url: '/legal/contractor_group/delete/id/'+selectedNode.get('id'),
            success: function(response){
                Loading.hide();

                var data = Ext.decode(response.responseText);
                if (data.success){
                    selectedNode.parentNode.removeChild(selectedNode);
                } else {
                    Ext.Msg.alert('Error', data.error);
                }
            },
            failure: function(){
                Loading.hide();
            }
        });
        return true;
    }
});