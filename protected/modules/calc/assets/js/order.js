/**
 * User: Forgon / Yury Zyuzkevich
 * Date: 11.04.13
 * Time: 14:36
 */
$(document).ready(function(){
    var iteration = $('tr[data-route_middle]').length;
    var $original = $(document.getElementById('route_point'));
    $(document.getElementById('route_point_add_button')).click(function(){
        var $cloned = $original.clone();
        $cloned.removeAttr('id');
        $cloned.find('[data-route_input]').val('').removeAttr('disabled').each(function(i, e) {
            var $this = $(this);
            $this.attr('name', $this.attr('name').replace('[__iteration__]', '['+iteration+']'));
        });
        iteration++;
        $cloned.insertBefore(document.getElementById('route_last_point'));
        $cloned.show();
    });

    $(document.getElementById('route_points_table')).on('change', '[data-country_input]', function(event){
        var $this = $(this)
        var $city_select = $(this.parentNode.parentNode.parentNode).find('[data-city_input]');

        $city_select.val('');
        $city_select.find('option:not(:selected)').remove();
        if ($this.val()) {
            $.get(window.order_cities_link, {country: $this.val()}, function(data){
                if (data.error) {
                    return;
                }
                var ind;
                for(ind in data.values) {
                    if (data.values.hasOwnProperty(ind)) {
                        var $opt = $(document.createElement('option'));
                        $opt.attr('value', ind);
                        $opt.html(data.values[ind]);
                        $opt.appendTo($city_select);
                    }
                }
            }, 'json');
        }
        console.log($(this).val());
    });
});