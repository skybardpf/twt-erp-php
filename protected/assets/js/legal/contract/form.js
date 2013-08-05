/**
 * Форма редактирования договора.
 */
$(document).ready(function(){
    $('button.del-signatory').on('click', del_signatory);
    $('button.add-signatory').on('click', showModal);
    $('button.add-signatory-contractor').on('click', showModal);

    function del_signatory(){
        var type = $(this).data('type');
        if (type != 'signatory' && type != 'signatory_contractor'){
            return false;
        }
        var local = $(this);
        $('<div>Вы действительно хотите удалить подписанта из списка?</div>').dialog({
            modal: true,
            resizable: false,
            title: 'Удалить подписанта из списка',
            buttons: [{
                text: "Удалить",
                class: 'btn btn-danger',
                click: function(event){
                    var button = $(event.target);
                    var dialog = $(this);
                    button.attr('disabled', 'disabled');
                    Loading.show();

                    var id = local.data('id');
                    var json, button_add;
                    if (type == 'signatory'){
                        json = $('#Contract_json_signatory');
                        button_add = $('.add-signatory');
                    } else {
                        json = $('#Contract_json_signatory_contractor');
                        button_add = $('.add-signatory-contractor');
                    }
                    var persons = eval(json.val());
                    var ind = persons.indexOf(id);
                    if (ind != -1){
                        persons.splice(ind, 1);
                        json.val($.toJSON(persons));
                        local.parents('tr').remove();
                    }

                    if (persons.length < 2){
                        button_add.removeClass('hide');
                    }

                    Loading.hide();
                    dialog.dialog('destroy');
                }
            },{
                text: 'Отмена',
                class: 'btn',
                click: function(){ $(this).dialog('destroy'); }
            }]
        });
        return false;
    }

    /**
     *  Show modal
     */
    function showModal(){
        var type = $(this).data('type');

        if (type != 'signatory' && type != 'signatory_contractor'){
            return false;
        }
        var ids = [];
        if (type == 'signatory'){
            ids = $('#Contract_json_signatory').val()
        } else {
            ids = $('#Contract_json_signatory_contractor').val()
        }

        Loading.show();

        $.ajax({
            type: 'POST',
            dataType: "html",
            url: "/legal/contract/_html_modal_select_signatory/",
            cache: false,
            data: {
                ids: ids,
                type: type
            }
        }).done(function(data) {
            $("#dataModalSignatory .modal-body").html(data);
            $('#dataModalSignatory').modal().css({
                width: 'auto',
                'margin-left': function () {
                    return -($(this).width() / 2);
                }
            });
        }).fail(function(a, ret, message) {

        }).always(function(){
            Loading.hide();
        });
        return true;
    }

    /**
     *  Сохранеям выбранного подписанта.
     */
    $('#dataModalSignatory .button_save').on('click', function(){
        var sel = $('#select_signatory option:selected');
        var pid = sel.val();
        if (pid == ''){
            alert('Выберите подписанта из списка');
            return false;
        }
        var type = $('#form-select-signatory').data('type');
        if (type != 'signatory' && type != 'signatory_contractor'){
            return false;
        }
        var name = sel.html();

        $.ajax({
            type: 'POST',
            dataType: "html",
            url: "/legal/contract/_html_row_signatory/",
            cache: false,
            data: {
                id: pid,
                name: name,
                type: type
            }
        }).done(function(data) {
            var table, json, button;
            if (type == 'signatory'){
                json = $('#Contract_json_signatory');
                table = $('#Contract_signatory');
                button = $('.add-signatory');
            } else {
                json = $('#Contract_json_signatory_contractor');
                table = $('#Contract_signatory_contr');
                button = $('.add-signatory-contractor');
            }
            var persons = eval(json.val());
            var ind = persons.indexOf(pid);
            if (ind == -1){
                persons.push(pid);
            }
            json.val($.toJSON(persons));

            var number = ((table.find('tr').size())%2 === 0) ? 'even' : 'odd';
            var html = '<tr class="'+number+'">'+data+'</tr>';

            table.find('tbody').append(html);
            $('.del-signatory').off('click').on('click', del_signatory);

            if (persons.length >= 2){
                button.addClass('hide');
            }

        }).fail(function(a, ret, message) {

        });
        return true;
    });
});