/**
 * @global {Number | NULL} window.organization_id
 *
 * @author Skibardin A.A. <webprofi1983@gmail.com>
 */
$(document).ready(function(){
    $('button.del-type-contract').on('click', del_type_contract);
    $('button.add-type-contract').on('click', showModal);

    $('#PowerAttorneyForOrganization_typ_doc').change(function(){
        var tc = $('#block_type_of_contract');
        if ($(this).val() == 'Генеральная'){
            tc.addClass('hide');
        } else {
            tc.removeClass('hide');
        }
    });

    /**
     * Удаляем вид договора из списка
     * @returns {boolean}
     */
    function del_type_contract(){
        var local = $(this);
        $('<div>Вы действительно хотите удалить вид договора из списка?</div>').dialog({
            modal: true,
            resizable: false,
            title: 'Удалить вид договора из списка',
            buttons: [{
                text: "Удалить",
                class: 'btn btn-danger',
                click: function(event){
                    var button = $(event.target);
                    var dialog = $(this);
                    button.attr('disabled', 'disabled');
                    Loading.show();

                    var json = $('#PowerAttorneyForOrganization_json_type_of_contract');
                    var id = local.data('id') + '';
                    var elements = $.parseJSON(json.val());
                    var ind = elements.indexOf(id);
                    if (ind != -1){
                        elements.splice(ind, 1);
                        json.val($.toJSON(elements));
                        local.parents('tr').remove();
                    }

                    var table = $('#grid-type-contract').find('table');
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
        var modal = $('#modalWindow');
        var ids = $('#PowerAttorneyForOrganization_json_type_of_contract').val();

        Loading.show();
        $.ajax({
            type: 'POST',
            dataType: "json",
            url: "/legal/power_attorney_organization/_html_form_select_element/",
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

    /**
     *  Сохранеям выбранный вид договора.
     */
    $('#modalWindow .button_save').on('click', function(){
        var sel = $('#select-element option:selected');
        var id = sel.val();
        if (id == ''){
            alert('Выберите вид договора из списка');
            return false;
        }

        Loading.show();
        $.ajax({
            type: 'POST',
            dataType: "json",
            url: "/legal/power_attorney_organization/_html_row_element/",
            cache: false,
            data: {
                id: id,
                name: sel.html()
            }
        }).done(function(data) {
            if (data.success == false){
                alert('Ошибка', data.message);
            } else {
                var table = $('#grid-type-contract').find('table');
                var json = $('#PowerAttorneyForOrganization_json_type_of_contract');
                var elements = eval(json.val());
                var ind = elements.indexOf(id);
                if (ind == -1){
                    elements.push(id);
                }
                json.val($.toJSON(elements));

                if (table.find('.empty')){
                    table.find('.empty').parents('tr').remove();
                }
                var number = ((table.find('tr').size())%2 === 0) ? 'even' : 'odd';
                var html = '<tr class="'+number+'">'+data.html+'</tr>';

                table.find('tbody').append(html);
                $('.del-type-contract').off('click').on('click', del_type_contract);
            }

        }).fail(function(a, ret, message) {

        }).always(function(){
            Loading.hide();
        });
        return true;
    });
});