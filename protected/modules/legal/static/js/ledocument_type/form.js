/**
 * User: Forgon / Yury Zyuzkevich
 * Date: 03.04.13
 * Time: 14:44
 */
$(document).ready(function(){
    var iteration = $(document.getElementById('list_of_countries')).children().length;
    $(document.getElementById('list_of_countries')).on('change', '[data-country_select]', function(event){
        if ($(this).data('new')) {
            // Мы установили значение у пустого инпута - надо добавить еще один пустой инпут
            var $cloning_elems = $(this.parentNode);
            var $cloned = $(this.parentNode).clone();
            $cloning_elems.children().each(function(i, e) {
                var $this = $(this);
                $this.attr('name', $this.data('name').replace('[iteration]', '['+iteration+']'));
            });
            $(this).data('new', 0);
            iteration++;
            $cloned.children().val('');
            $cloned.insertAfter(this.parentNode);
        } else {
            // Поменяли значение у существующего - если на пустое - убрать инпут
            if (!$(this).val()) {
                $(this.parentNode).remove();
            }
        }
    });
});