/**
 * @author Skibardin A.A. <skybardpf@artektiv.ru>
 */
$(document).ready(function(){
    const COUNTRY_RUSSIAN_ID = 643;

    list_country_fields();
    select2_init();

    $('.list-countries').on('change', list_country_fields);

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
     */
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
    }
});