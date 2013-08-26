/**
 *  Список моих событий (мероприятий) с фильтром.
 */
$(document).ready(function(){
    var div_countries = $('div.block_countries');

    $('#EventForm_for_organization_0').change(function(){
        div_countries.addClass('hide');
        window.location.href = '/my_events?for_yur=1';
    });

    $('#EventForm_for_organization_1').change(function(){
        div_countries.removeClass('hide');
        window.location.href = '/my_events?for_yur=2';
    });

    $('#EventForm_countries').change(function(){
        window.location.href = '/my_events?for_yur=2&country_id='+$(this).val();
    });
});

