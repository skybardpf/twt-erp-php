/**
 * @global {Number | NULL} window.organization_id
 *
 * @author Skibardin A.A. <skybardpf@artektiv.ru>
 */
$(document).ready(function(){
    $('button.del-signatory').on('click', del_signatory);
    $('button.add-type-contract').on('click', showModal);

    function arrayObjectIndexOf(myArray, searchTerm) {
        var i = 0;
        for(var key in myArray) {
            if (key === searchTerm) return i;
            i++;
        }
        return -1;
    }

    function del_signatory(){
        var local = $(this);
        var type = grid_signatories.data('type');
        var json;
        if (type == 'organization'){
            json = $('#Organization_json_signatories');
        } else if (type == 'contractor') {
            json = $('#Contractor_json_signatories');
        } else {
            return false;
        }

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
                    var persons = $.parseJSON(json.val());
                    var ind = arrayObjectIndexOf(persons, id);

                    if (ind != -1){
                        delete persons[id];
                        json.val($.toJSON(persons));
                        local.parents('tr').remove();
                    }

                    var table = grid_signatories.find('table');
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
        var org_id = grid_signatories.data('id');
        if (org_id == ''){
            return false;
        }
        var modal = $('#dataModalSignatory');
        var type = grid_signatories.data('type');
        var ids;
        if (type == 'organization'){
            ids = $('#Organization_json_signatories').val();
        } else if (type == 'contractor') {
            ids = $('#Contractor_json_signatories').val();
        } else {
            return false;
        }

        Loading.show();
        $.ajax({
            type: 'POST',
            dataType: "json",
            url: "/legal/contractor/_html_form_select_element/id/"+org_id,
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

    /**
     *  Сохранеям выбранного подписанта.
     */
    $('#dataModalSignatory .button_save').on('click', function(){
        var sel_person = $('#select-person option:selected');
//        var person_id = sel_person.val();
//        if (person_id == ''){
//            alert('Выберите подписанта из списка');
//            return false;
//        }

        var sel_doc = $('#select-doc option:selected');
        var doc_id = sel_doc.val();
        if (doc_id == ''){
            alert('Выберите довереность из списка');
            return false;
        }

        var json;
        var type = grid_signatories.data('type');
        if (type == 'organization'){
            json = $('#Organization_json_signatories');
        } else if (type == 'contractor') {
            json = $('#Contractor_json_signatories');
        } else {
            return false;
        }

        Loading.show();

        $.ajax({
            type: 'POST',
            dataType: "json",
            url: "/legal/contractor/_html_row_element/",
            cache: false,
            data: {
                doc_id: doc_id
            }
        }).done(function(data) {
            if (data.success == false){
                alert('Error', data.error);
            } else {
                var id = data.person_id + '_' + data.doc_id;
                var persons = $.parseJSON(json.val());
                var ind = arrayObjectIndexOf(persons, id);
                if (ind == -1){
                    persons[id] = {
                        id: data.person_id,
                        doc_id: data.doc_id
                    };
                }
                json.val($.toJSON(persons));

                if (grid_signatories.find('.empty')){
                    grid_signatories.find('.empty').parents('tr').remove();
                }
                var number = ((grid_signatories.find('tr').size())%2 === 0) ? 'even' : 'odd';
                var html = '<tr class="'+number+'">'+data.html+'</tr>';

                grid_signatories.find('tbody').append(html);
                $('.del-signatory').off('click').on('click', del_signatory);
            }

        }).fail(function(a, ret, message) {

        }).always(function(){
            Loading.hide();
        });
        return true;
    });

    /**
     * Показываем поля в зависимости от выбранной страны.
     */
    function list_country_fields(){
        var country_id = $('.list-countries').val();
        if(country_id == COUNTRY_RUSSIAN_ID){ // Россия
            $('#rus_fields').show();
            $('#foreign_fields').hide();
        } else {
            $('#rus_fields').hide();
            $('#foreign_fields').show();
        }
    }

    /**
     * Инициализация select2-инпутов
     *//*
    function select2_init() {
        var e = $('.input-profile')[0];

        var options = {width: '90%'};
        if (e.dataset.multiple) {
            options.multiple = true;
        }
        if (e.dataset.minimum_input_length) {
            options.minimumInputLength = e.dataset.minimum_input_length;
        }
        if (e.dataset.maximum_input_length) {
            options.minimumInputLength = e.dataset.maximum_input_length;
        } else {
            options.maximumInputLength = 65;
        }
        if (e.dataset.placeholder) {
            options.placeholder = e.dataset.placeholder;
        }
        if (e.dataset.allow_clear) {
            options.allowClear = true;
        }

        if (e.dataset.ajax) {
            options.ajax = {
                url: e.dataset.ajax_url,
                dataType: 'json',
                data: function(term, page) {
                    return {
                        q: term,
                        page_limit: 10
                    }
                },
                results: function(data, page) {
                    return {
                        results: data.values
                    }
                }
            };
            options.initSelection = function(element, callback) {
                var id = $(element).val();
                var ajaxoptions = {dataType: "json"};
                if (id) {
                    ajaxoptions.data = {id: id};
                }
                $.ajax(e.dataset.ajax_url, ajaxoptions).done(
                    function(data) {
                        callback(data.values);
                    }
                );
            };
            $(e).select2(options);
        }
    }*/
});