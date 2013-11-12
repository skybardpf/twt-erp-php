Ext.require([
    'Ext.data.*',
    'Ext.grid.*',
    'Ext.tree.*'
]);

Ext.onReady(function() {
    Ext.QuickTips.init();

    var buttonAdd = new Ext.Button({
        text: 'Добавить',
        handler: onClickAdd,
        disabled: true
    });
    var buttonEdit = new Ext.Button({
        text: 'Переименовать',
        handler: onClickEdit,
        disabled: true
    });
    var buttonDel = new Ext.Button({
        text: 'Удалить',
        handler: onClickDel,
        disabled: true
    });

    console.log(global_data);
    var store = Ext.create('Ext.data.TreeStore', {
        proxy: {
            data : global_data, // instead it goes here
            type: 'memory',
            reader: {
                type: 'json'
            }
        },
        root: {
            text: 'Все группы',
            id: 'root',
            expanded: true
        },

        listeners: {
            load : function(){
                toggleDisabledButtons(false);
            }
        }
    });


    var tree = Ext.create('Ext.tree.Panel', {
        store: store,
        rootVisible: true,
        autoScroll: true,
        buttons: [
            buttonAdd, buttonEdit, buttonDel
        ]
    });

    function toggleDisabledButtons(value){
        buttonAdd.setDisabled(value);
        buttonEdit.setDisabled(value);
        buttonDel.setDisabled(value);
    }

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
            Ext.Msg.alert('Ошибка', 'Выберите родительскую группу.');
        }
        selectedNode = selectedNode[0];
        Ext.MessageBox.prompt('Название', 'Введите название группы:', addGroupName, selectedNode);
    }

    /**
     * @returns {boolean}
     */
    function onClickEdit(){
        var selectedNode = tree.getSelectionModel().getSelection();
        if (Ext.isEmpty(selectedNode)){
            Ext.Msg.alert('Ошибка', 'Выберите группу для редактирования.');
            return false;
        }
        selectedNode = selectedNode[0];
        if (selectedNode.isRoot()){
            Ext.Msg.alert('Ошибка', 'Нельзя редактировать корневую группу.');
            return false;
        }
        Ext.MessageBox.prompt('Название', 'Введите название группы:', editGroupName, selectedNode, false, selectedNode.data.text);
        return true;
    }

    /**
     * @param btn
     * @param text
     * @returns {boolean}
     */
    function addGroupName(btn, text){
        var root_node = this;
        if (btn == 'ok'){
            if (Ext.isEmpty(text) || text == ''){
                Ext.Msg.alert('Ошибка', 'Введите название группы');
                return false;
            }

            toggleDisabledButtons(true);

            Loading.show();
            Ext.Ajax.request({
                url: '/contractor_group/create/id/'+root_node.get('id'),
                params: {
                    name: text
                },
                success: function(response){
                    Loading.hide();
                    toggleDisabledButtons(false);

                    var data = Ext.decode(response.responseText);
                    if (data.success){
                        root_node.set('allowChildren', true);
                        root_node.set('leaf', false);
                        root_node.appendChild({
                            id: data.id,
                            text: data.name,
                            leaf: false,
                            allowChildren: false,
                            children: []
                        });
                        root_node.expand();
                    } else {
                        Ext.Msg.alert('Error', data.error);
                    }
                },
                failure: function(){
                    Loading.hide();
                    toggleDisabledButtons(false);
                }
            });
        }
        return true;
    }

    /**
     * @param btn
     * @param text
     * @returns {boolean}
     */
    function editGroupName(btn, text){
        var node = this;
        if (btn == 'ok'){
            if (Ext.isEmpty(text) || text == ''){
                Ext.Msg.alert('Ошибка', 'Введите название группы');
                return false;
            }

            toggleDisabledButtons(true);

            Loading.show();
            Ext.Ajax.request({
                url: '/contractor_group/update/id/'+node.get('id'),
                params: {
                    name: text
                },
                success: function(response){
                    Loading.hide();
                    toggleDisabledButtons(false);

                    var data = Ext.decode(response.responseText);
                    if (data.success){
                        node.set('text', text);
                        tree.getView().refresh(true);
                    } else {
                        Ext.Msg.alert('Error', data.error);
                    }
                },
                failure: function(){
                    Loading.hide();
                    toggleDisabledButtons(false);
                }
            });
        }
        return true;
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
        if (selectedNode.isRoot()){
            Ext.Msg.alert('Ошибка', 'Нельзя удалить корневую группу.');
            return false;
        }

        if (selectedNode.hasChildNodes()){
            Ext.Msg.alert('Ошибка', 'Нельзя удалить группу, у которой присутствуют подгруппы.');
            return false;
        }

        toggleDisabledButtons(true);
        Loading.show();
        Ext.Ajax.request({
            url: '/contractor_group/delete/id/'+selectedNode.get('id'),
            success: function(response){
                Loading.hide();
                toggleDisabledButtons(false);

                var data = Ext.decode(response.responseText);
                if (data.success){
                    selectedNode.parentNode.removeChild(selectedNode);
                } else {
                    Ext.Msg.alert('Error', data.error);
                }
            },
            failure: function(){
                Loading.hide();
                toggleDisabledButtons(false);
            }
        });
        return true;
    }
});