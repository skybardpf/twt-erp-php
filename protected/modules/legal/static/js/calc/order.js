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
        $cloned.find('[data-route_input]').val('').each(function(i, e) {
            var $this = $(this);
            $this.attr('name', $this.attr('name').replace('[__iteration__]', '['+iteration+']'));
        });
        iteration++;
        $cloned.insertBefore(document.getElementById('route_last_point'));
        $cloned.show();
    });


});