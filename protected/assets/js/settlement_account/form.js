/**
 *  Форма редактирование банковского счета.
 */
$(document).ready(function(){
    $('#SettlementAccount_bank').change(getBankName);
    $('#SettlementAccount_correspondent_bank').change(getBankName);

    /**
     *  Получаем название банка по его идентификатору (БИК или СВИФТ)
     */
    function getBankName() {
        Loading.show();

        var local = $(this);
        var id = $(this)[0].id;
        var bank_name = $('#'+id+'_name');
        $.ajax({
//                type: 'POST',
            dataType: "json",
            url: "/settlement_account/_get_bank_name",
            cache: false,
            data: {
                'bank': local.val()
            }
        })
        .done(function (data, ret) {
            var res = '';
            if (ret == 'success') {
                res = data.bank_name
            }
            bank_name.val(res);
        })
        .fail(function (a, ret, message) {
            bank_name.val('');
        })
        .always(function () {
            Loading.hide();
        });
    }

    $('button.add-person').on('click', showModal);
    $('button.del-element').on('click', del_element);

    /**
     *  Сохранеям выбранного подписанта.
     */
    $('#dataModal .button_save').on('click', function(){
        var sel = $('#select-element option:selected');
        var pid = sel.val();
        if (pid == ''){
            alert('Выберите элемент из списка');
            return false;
        }
        var name = sel.html();

        Loading.show();
        $.ajax({
            type: 'POST',
            dataType: "json",
            url: "/settlement_account/_html_row_element/",
            cache: false,
            data: {
                id: pid,
                name: name
            }
        }).done(function(data) {
            if (!data.success){
                alert(data.message);
            } else {
                var json = $('#SettlementAccount_json_managing_persons'),
                    table = $('#grid-persons');

                var persons = eval(json.val());
                var ind = persons.indexOf(pid);
                if (ind == -1){
                    persons.push(pid);
                }
                json.val($.toJSON(persons));

                if (table.find('.empty')){
                    table.find('.empty').parents('tr').remove();
                }
                var number = ((table.find('tr').size())%2 === 0) ? 'even' : 'odd';
                var html = '<tr class="'+number+'">'+data.html+'</tr>';

                table.find('tbody').append(html);
                $('.del-element').off('click').on('click', del_element);
            }
        }).fail(function(a, ret, message) {

        }).always(function () {
            Loading.hide();
        });
        return true;
    });

    /**
     * Delete element
     * @returns {boolean}
     */
    function del_element(){
        var local = $(this);
        $('<div>Вы действительно хотите удалить персону из списка?</div>').dialog({
            modal: true,
            resizable: false,
            title: 'Удалить из списка?',
            buttons: [{
                text: "Удалить",
                class: 'btn btn-danger',
                click: function(event){
                    var button = $(event.target);
                    var dialog = $(this);
                    button.attr('disabled', 'disabled');
                    Loading.show();

                    var id = local.data('id')+'';
                    var json = $('#SettlementAccount_json_managing_persons'),
                        table = $('#grid-persons');

                    var persons = eval(json.val());
                    var ind = persons.indexOf(id);
                    if (ind != -1){
                        persons.splice(ind, 1);
                        json.val($.toJSON(persons));
                        local.parents('tr').remove();
                    }

                    if (table.find('tr').size() == 1){
                        table.find('tbody').append(
                            '<tr>' +
                                '<td colspan="2" class="empty">' +
                                '<span class="empty">Нет результатов.</span>' +
                                '</td>' +
                            '</tr>'
                        );
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
        var ids = $('#SettlementAccount_json_managing_persons').val(),
            modal = $('#dataModal');
        Loading.show();
        $.ajax({
            type: 'POST',
            dataType: "json",
            url: "/settlement_account/_html_form_select_element/",
            cache: false,
            data: {
                ids: ids
            }
        }).done(function(data) {
            if (!data.success){
                alert(data.message);
            } else {
                modal.find('.modal-body').html(data.html);
                modal.modal().css({
                    width: 'auto',
                    'margin-left': function () {
                        return -($(this).width() / 2);
                    }
                });
            }
        }).fail(function(a, ret, message) {

        }).always(function(){
            Loading.hide();
        });
        return true;
    }
});

