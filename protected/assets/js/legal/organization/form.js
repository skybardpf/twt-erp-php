/**
 * User: Forgon / Yury Zyuzkevich
 * Date: 24.04.2013 от Рождества Христова
 * Time: 13:52
 */
$(document).ready(function(){
    const COUNTRY_RUSSIAN_ID = 643;
    list_country_fields();
    $('#Organization_country').on('change', list_country_fields);

    function list_country_fields(){
        var country_id = $('#Organization_country').val();
        if(country_id == COUNTRY_RUSSIAN_ID){ // Россия
            $('#rus_fields').show();
            $('#foreign_fields').hide();
        } else {
            $('#rus_fields').hide();
            $('#foreign_fields').show();
        }
    }

    select2_init($(document), false);

    /**
     * Инициализация select2-инпутов
     * @param elem
     * @param clone
     */
    function select2_init(elem, clone) {
        var e = $('#Organization_profile')[0];

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
//
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
    }
});