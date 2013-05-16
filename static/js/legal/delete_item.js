/**
 * User: Forgon / Yury Zyuzkevich
 * Date: 24.04.2013 от Рождества Христова
 * Time: 13:54
 */
$(document).ready(function(){
    $('[data-delete_item_element]').each(function(i,e){
        $(e).click(function(){
            $('<div>'+$(e).data('question')+'</div>').dialog({
                modal: true,
                resizable: false,
                title: $(e).data('title'),
                buttons: [
                    {
                        text: "Удалить",
                        class: 'btn btn-danger',
                        click: function(event){
                            var button = $(event.target);
                            var dialog = $(this);
                            button.attr('disabled', 'disabled');
                            Loading.show();
                            $.get($(e).data('url'), {}, function(data){
                                Loading.hide();
                                dialog.dialog('destroy');
                                if (data.error) {
                                    $('<div>'+data.error+'</div>').dialog({});
                                } else {
                                    window.location.href = $(e).data('redirect_url');
                                }
                            }, 'json');
                        }
                    },{
                        text: 'Отмена',
                        class: 'btn',
                        click: function(){ $(this).dialog('destroy'); }
                    }
                ]
            });
            return false;
        });
    });
});
