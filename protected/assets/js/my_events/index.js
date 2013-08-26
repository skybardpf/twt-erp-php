/**
 *  Список моих событий (мероприятий) с фильтром.
 */
$(document).ready(function(){
    var div_countries = $('div.block_countries');

    $('#EventForm_for_organization_0').change(function(){
        div_countries.addClass('hide');
    });

    $('#EventForm_for_organization_1').change(function(){
        div_countries.removeClass('hide');
    });
});

