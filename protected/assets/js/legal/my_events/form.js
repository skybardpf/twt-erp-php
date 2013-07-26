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

    /**
     * Добавить организацию
     */
    $('.add-organization').on('click', function(){
        Loading.show();
        $.ajax({
            type: 'POST',
            dataType: "html",
            url: "/legal/my_events/_html_form_select_organization/",
            cache: false,
            data: {
                ids: $('#Event_json_organizations').val()
            }
        }).done(function(data) {
            $("#dataModalYur .modal-body").html(data);
            $('#dataModalYur').modal().css({
                width: 'auto',
                'margin-left': function () {
                    return -($(this).width() / 2);
                }
            });
        }).fail(function(a, ret, message) {

        }).always(function(){
            Loading.hide();
            $(button).button('reset');
        });
        return true;
    });

    $('.add-country').on('click', function(){
        console.log($(this));
    });

    $('.del-organization').on('click', delete_organization);

    $('.del-country').on('click', delete_country);

    /**
     *  Сохраняем выбранную организацию
     */
    $('#dataModalYur .button_save').on('click', function(){
        var sel = $('#select_organizations option:selected');
        var pid = sel.val();
        var name = sel.html();
        if (pid == ''){
            alert('Выберите организацию из списка');
            return false;
        }
        $.ajax({
            type: 'POST',
            dataType: "html",
            url: "/legal/contract/_html_row_organization/",
            cache: false,
            data: {
                id: pid,
                name: name
            }
        }).done(function(data) {
            var el = $('#Event_json_organizations');
            var org = eval(el.val());

            var ind = org.indexOf(pid);
            if (ind == -1){
                org.push(pid);

            }
            el.val($.toJSON(org));
            var table = $('#grid-organizations');

            var number = ((table.find('tr').size())%2 === 0) ? 'even' : 'odd';
            var html = '<tr class="'+number+'">'+data+'</tr>';

            table.find('tbody').append(html);
            $('.del-organization').off('click').on('click', del_organization);

        }).fail(function(a, ret, message) {

        });
    });

    /**
     *  Добавляем юр. лицо
     */
    $('#dataModalCountries .button_save').on('click', function(){
        var sel = $('#select_countries option:selected');
        console.log(sel);
        var pid = sel.val();
        var name = sel.html();
        if (pid == ''){
            alert('Выберите страну');
            return false;
        } else {
            var el = $('#Event_json_countries');
            var org = eval(el.val());
            var ind = org.indexOf(pid);
                console.log(pid);
            if (ind == -1){
                org.push(pid);

            }
                console.log(org);
            el.val($.toJSON(org));

            var div = $(
                '<div class="block" data-id="'+pid+'"' +
                    '<div class="view_countries">' + name + '&nbsp;&nbsp;&nbsp;' +
//                    '<a href="/legal/countries/view/id/'+pid+'">'+name+'</a>&nbsp;&nbsp;&nbsp;' +
                    '<a class="icon-remove" href="#"></a></div>' +
                    '</div>'
            );
            $('#for_countries_list').append(div);
            div.find('.icon-remove').on('click', delete_country);
        }
    });

    /**
     *  Show modal for yur
     */
    $('#data-add-yur').click(function(){
        var button = this;
        $(button).button('loading');

        Loading.show();

        $.ajax({
//                type: 'POST',
//                dataType: "json",
            url: "/legal/"+window.controller_name+'/get_list_organizations/',
            cache: false,
            data: {
                'selected_ids': $('#Event_json_organizations').val()
            }
        }).done(function(data) {
            $("#dataModalYur .modal-body").html(data);
            $(button).button('reset');
            $('#dataModalYur').modal().css({
                width: 'auto',
                'margin-left': function () {
                    return -($(this).width() / 2);
                }
            });
        }).fail(function(a, ret, message) {

        }).always(function(){
            Loading.hide();
            $(button).button('reset');
        })
    });

    /**
     *  Show modal for countries
     */
    $('#data-add-country').click(function(){
        var button = this;
        $(button).button('loading');

        Loading.show();

        $.ajax({
            type: 'POST',
//            dataType: "json",
            url: "/legal/"+window.controller_name+'/get_countries/',
            cache: false,
            data: {
                'selected_ids': $('#Event_json_countries').val()
            }
        }).done(function(data) {
                $("#dataModalCountries .modal-body").html(data);
                $(button).button('reset');
                $('#dataModalCountries').modal().css({
                    width: 'auto',
                    'margin-left': function () {
                        return -($(this).width() / 2);
                    }
                });
            }).fail(function(a, ret, message) {

            }).always(function(){
                Loading.hide();
                $(button).button('reset');
            })
    });

    /**
     *  Удаляем организацию из списка
     *  @returns {boolean}
     */
    function delete_organization(){
        var local = $(this);
        $('<div>'+'Вы уверены, что хотите удалить из списка организацию?'+'</div>').dialog({
            modal: true,
            resizable: false,
            title: 'Удаление организации из списка',
            buttons: [
                {
                    text: "Удалить",
                    class: 'btn btn-danger',
                    click: function(event){
                        var dialog = $(this);
                        var button = $(event.target);

                        var id = local.data('id');
//                        var json, button_add;
//                        if (type == 'signatory'){
//                            json = $('#Contract_json_signatory');
//                            button_add = $('.add-signatory');
//                        } else {
//                            json = $('#Contract_json_signatory_contractor');
//                            button_add = $('.add-signatory-contractor');
//                        }
//                        var persons = eval(json.val());
//                        var ind = persons.indexOf(id);
//                        if (ind != -1){
//                            persons.splice(ind, 1);
//                            json.val($.toJSON(persons));
//                            local.parents('tr').remove();
//                        }

//                        var div_block = target.parent('.block');
//                        var id = div_block.data('id');
                        var type = local.data('type');

                        var el;
                        if (type == 'organization'){
                            el = $('#Event_json_organizations');
                        } else if (type == 'contractor'){
                            el = $('#Event_json_contractors');
                        } else {
                            dialog.dialog('destroy');
                            throw new Error('Неизвестный тип организации.');
                        }

                        button.attr('disabled', 'disabled');
                        Loading.show();

                        var persons = eval(el.val());
                        var ind = persons.indexOf(id);

                        if (ind != -1){
                            persons.splice(ind, 1);
                            el.val($.toJSON(persons));
                            local.parents('tr').remove();
                        }
                        Loading.hide();
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

    /**
     *  Удаляем страну из списка.
     *  @returns {boolean}
     */
    function delete_country(){
        var target = $(this);
        $('<div>'+'Вы уверены, что хотите удалить страну из списка?'+'</div>').dialog({
            modal: true,
            resizable: false,
            title: 'Удаление страны из списка',
            buttons: [
                {
                    text: "Удалить",
                    class: 'btn btn-danger',
                    click: function(event){
                        var dialog = $(this);
                        var button = $(event.target);

                        var div_block = target.parent('.block');
                        var id = div_block.data('id');
                        var el = $('#Event_json_countries');

                        button.attr('disabled', 'disabled');
                        Loading.show();

                        var persons = eval(el.val());
                        var ind = persons.indexOf(id);

                        if (ind != -1){
                            persons.splice(ind, 1);
                            el.val($.toJSON(persons));
                            div_block.remove();
                        }
                        Loading.hide();
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
