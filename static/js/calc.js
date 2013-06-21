/**
 * User: Forgon / Yury Zyuzkevich
 * Date: 27.02.13
 * Time: 15:08
 */
$(document).ready(function(){

    select2_init($(document), false);

    $('input[type=radio][name=tnved]').on('change', function(e){
        $('[data-new_row="0"]').remove();
    });

    var iteration = 1;
    $('#calc-form-form').on('change', '[data-one_row=1]', function(event){
        // Инпуты данной строки
        var elements = $(this).find('input[name*=data]');
        // Инпут
        var $this = $(this);
        if ($this.data('new_row')) {
            // добавление строки при заполнении ее значений
            if (elements.get(0).value || elements.get(1).value) {
                $this.data('new_row', 0);
                $this.attr('data-new_row', 0);
                var clone = $('#calc_clone_row').clone();
                clone.removeAttr('id');

                clone.find('[name*=new]').each(function (k, l){
                    $(l).attr('name', $(l).attr('name').replace('[new]', '[new_'+iteration+']'));
                });
                iteration++;
                clone.appendTo(this.parentNode);
                select2_init(clone, true);
                clone.show();
            }
        } else {
            // Удаление строки при очистке ее значений
            if (!elements.get(0).value && !elements.get(1).value) {
                $this.remove();
            }
        }
    });

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
                                page_limit: 10,
                                tnved: $('[data-tnved_selection]:checked').val()
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