/**
 * Форма редактирования договора.
 */
$(document).ready(function(){
//    console.log($('.del-signatory'));
//    console.log($('.del-signatory-contractor'));
//    console.log($('.add-signatory'));
//    console.log($('.add-signatory-contractor'));

    $('.del-signatory').each(function(){
        console.log($(this));
        $(this).on('click', del_signatory);
    });
//    $('.del-signatory').on('click', del_signatory);
    $('.del-signatory-contractor').on('click', del_signatory_contractor);

    function del_signatory(){
        console.log($(this));
        $('<div>Вы действительно хотите удалить подписанта из списка?</div>').dialog({
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

//                        $.get($(e).data('url'), {}, function(data){
//                            Loading.hide();
//                            dialog.dialog('destroy');
//                            if (data.error) {
//                                $('<div>'+data.error+'</div>').dialog({});
//                            } else {
//                                window.location.href = $(e).data('redirect_url');
//                            }
//                        }, 'json');
                    }
                },{
                    text: 'Отмена',
                    class: 'btn',
                    click: function(){ $(this).dialog('destroy'); }
                }
            ]
        });
        return false;
    }

    function del_signatory_contractor(){
        $('<div>Вы действительно хотите удалить подписанта из списка?</div>').dialog({
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

//                        $.get($(e).data('url'), {}, function(data){
//                            Loading.hide();
//                            dialog.dialog('destroy');
//                            if (data.error) {
//                                $('<div>'+data.error+'</div>').dialog({});
//                            } else {
//                                window.location.href = $(e).data('redirect_url');
//                            }
//                        }, 'json');
                    }
                },{
                    text: 'Отмена',
                    class: 'btn',
                    click: function(){ $(this).dialog('destroy'); }
                }
            ]
        });
        return false;
    }
});