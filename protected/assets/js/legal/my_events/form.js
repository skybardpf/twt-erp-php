/**
 *  Форма редактирование моих событий (мероприятий).
 */
$(document).ready(function(){
    var div_yur = $('#for_yur');
    var div_countries = $('#for_countries');

    $('#Event_for_yur_0').change(function(){
        div_countries.addClass('hide');
        div_yur.removeClass('hide');
    });

    $('#Event_for_yur_1').change(function(){
        div_yur.addClass('hide');
        div_countries.removeClass('hide');
    });

    $('button.add-organization').on('click', showModal);
    $('button.add-country').on('click', showModal);
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
        var type = $('#form-select-element').data('type');
        if (type != 'organization' && type != 'country'){
            return false;
        }
        var name = sel.html();

        $.ajax({
            type: 'POST',
            dataType: "html",
            url: "/legal/my_events/_html_row_element/",
            cache: false,
            data: {
                id: pid,
                name: name,
                type: type
            }
        }).done(function(data) {
            var table, json;
            if (type == 'organization'){
                json = $('#Event_json_organizations');
                table = $('#grid-organizations');
            } else {
                json = $('#Event_json_countries');
                table = $('#grid-countries');
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
            $('.del-element').off('click').on('click', del_element);

        }).fail(function(a, ret, message) {

        });
        return true;
    });

    /**
     * Delete element
     * @returns {boolean}
     */
    function del_element(){
        var type = $(this).data('type');
        if (type != 'organization' && type != 'country' && type != 'contractor'){
            return false;
        }
        var local = $(this);
        $('<div>Вы действительно хотите удалить элемент из списка?</div>').dialog({
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

                    var id = local.data('id');
                    var json, table;
                    if (type == 'organization'){
                        json = $('#Event_json_organizations');
                        table = $('#grid-organizations');
                    }else if (type == 'contractor'){
                        json = $('#Event_json_contractors');
                        table = $('#grid-organizations');
                    } else {
                        json = $('#Event_json_countries');
                        table = $('#grid-countries');
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
        if (type != 'organization' && type != 'country'){
            return false;
        }
        var ids = [],
            modal = $('#dataModal');
        if (type == 'organization'){
            ids = $('#Event_json_organizations').val();
//            modal = $('#dataModalOrganization');
        } else {
            ids = $('#Event_json_countries').val();
//            modal = $('#dataModalCountry');
        }


        Loading.show();
        $.ajax({
            type: 'POST',
            dataType: "json",
            url: "/legal/my_events/_html_form_select_element/",
            cache: false,
            data: {
                ids: ids,
                type: type
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

