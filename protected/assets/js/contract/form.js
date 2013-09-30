/**
 * Форма редактирования договора.
 */
$(document).ready(function(){
    $('button.del-signatory').on('click', del_signatory);
    $('button.add-signatory').on('click', showModal);
    $('button.add-signatory-contractor').on('click', showModal);

    function del_signatory(){
        var type = $(this).data('type');
        if (type != 'organization_signatories' && type != 'contractor_signatories'){
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
                    var json, button_add, table;
                    if (type == 'organization_signatories'){
                        json = $('#Contract_json_organization_signatories');
                        table = $('#Contract_signatory');
                        button_add = $('.add-signatory');
                    } else if (type == 'contractor_signatories') {
                        json = $('#Contract_json_contractor_signatories');
                        table = $('#Contract_signatory_contr');
                        button_add = $('.add-signatory-contractor');
                    } else {
                        return false;
                    }
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
                    return false;
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
        var ids = [];
        if (type == 'organization_signatories'){
            ids = $('#Contract_json_organization_signatories').val()
        } else if (type == 'contractor_signatories'){
            ids = $('#Contract_json_contractor_signatories').val()
        } else {
            return false;
        }

        Loading.show();
        $.ajax({
            type: 'POST',
            dataType: "html",
            url: "/contract/_html_modal_select_signatory/",
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
        var name = sel.html();

        $.ajax({
            type: 'POST',
            dataType: "html",
            url: "/contract/_html_row_signatory/",
            cache: false,
            data: {
                id: pid,
                name: name,
                type: type
            }
        }).done(function(data) {
            var table, json, button;
            if (type == 'organization_signatories'){
                json = $('#Contract_json_organization_signatories');
                table = $('#Contract_signatory');
                button = $('.add-signatory');
            } else if (type == 'contractor_signatories') {
                json = $('#Contract_json_contractor_signatories');
                table = $('#Contract_signatory_contr');
                button = $('.add-signatory-contractor');
            } else {
                return false;
            }

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
            var html = '<tr class="'+number+'">'+data+'</tr>';

            table.find('tbody').append(html);
            $('.del-signatory').off('click').on('click', del_signatory);

            return false;

        }).fail(function(a, ret, message) {

        });
        return true;
    });
});