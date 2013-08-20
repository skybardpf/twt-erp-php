/**
 *  @global {String} controller_name
 */
$(document).ready(function(){
    if (window.controller_name === undefined || window.controller_name == ''){
        throw new Error("Не определен контроллер.");
    }

    function delete_file(p){
        $('<div>Вы действительно хотите удалить файл?</div>').dialog({
            modal: true,
            resizable: false,
            title: 'Удаление файла',
            buttons: [
                {
                    text: "Удалить",
                    class: 'btn btn-danger',
                    click: function(event){
                        var button = $(event.target);
                        var dialog = $(this);
                        button.attr('disabled', 'disabled');
                        Loading.show();
                        $.ajax({
                            type: 'GET',
                            dataType: "json",
                            url: '/legal/'+controller_name+'/delete_file/id/'+p.data.file_id
                        })
                        .done(function(data, success) {
                            if (success == 'success'){
                                if (!data.success){
                                    alert(data.message);
                                } else {
                                    $(p.currentTarget).parent('.block').remove();
                                }
                            }
                        })
                        .fail(function() {
                            console.log("error");
                        })
                        .always(function(){
                            Loading.hide();
                        });
                        dialog.dialog('destroy');
                    }
                },{
                    text: 'Отмена',
                    class: 'btn',
                    click: function(){
                        $(this).dialog('destroy');
                    }
                }
            ]
        });
        return false;
    }
});