/**
 * User: Forgon / Yury Zyuzkevich
 * Date: 27.02.13
 * Time: 15:08
 */
$(document).ready(function(){

    select2_init($(document), false);

    /**
     * Инициализация select2-инпутов
     * @param elem
     * @param clone
     */
    function select2_init(elem, clone) {
        var selector = clone ? '[data-tnved=1]' : '[data-tnved=1][data-init_on_clone!=1]';
        if (elem.find(selector)) {
            elem.find(selector).each(function(i, e){
                var options = {width: '100%'};
                if (e.dataset.multiple) options.multiple = true;
                if (e.dataset.minimum_input_length) options.minimumInputLength = e.dataset.minimum_input_length;
                if (e.dataset.maximum_input_length) {
                    options.minimumInputLength = e.dataset.maximum_input_length;
                } else {
                    options.maximumInputLength = 65;
                }
                if (e.dataset.placeholder) options.placeholder = e.dataset.placeholder;
                if (e.dataset.allow_clear) options.allowClear = true;

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
                        results: function(data, page) { return {results: data.values }}
                    };
                    options.initSelection = function(element, callback) {
                        var id = $(element).val();
                        var ajaxoptions = {dataType: "json"};
                        if (id) ajaxoptions.data = { id: id, tnved: $('[data-tnved_selection]:checked').val()};
                        $.ajax(e.dataset.ajax_url, ajaxoptions).done(function(data) { callback(data.values); });
                    }
                }
                $(e).select2(options);
            });
        }
    }
});