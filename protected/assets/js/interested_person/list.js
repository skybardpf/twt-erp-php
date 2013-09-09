/**
 * Список заинтересованных лиц.
 */
$(document).ready(function(){
    var org_info = $('.org-info');

    $('.history_date').click(function(){
        var local = $(this);
        var history_date = local.data('date');
        var block = $('.block-history-'+history_date);

        if (local.data('open-toggle') == 1){
            block.addClass('hide');
            local.data('open-toggle', 0);
        } else {
            if (local.data('already-loaded') == 1){
                block.removeClass('hide');
                local.data('open-toggle', 1);
            } else {
                Loading.show();
                $.ajax({
                    type: 'GET',
                    dataType: "json",
                    url: '/interested_person/_get_history_models',
                    data: {
                        'org_id': org_info.data('org-id'),
                        'org_type': org_info.data('org-type'),
                        'type_person': org_info.data('type-person'),
                        'date': history_date
                    }
                })
                .done(function(data, success) {
                    if (success == 'success'){
                        if (!data.success){
                            alert(data.message);
                        } else {
                            block.html(data.html);
                            block.removeClass('hide');
                            local.data('open-toggle', 1);
                            local.data('already-loaded', 1);
                        }
                    }
                })
                .fail(function(){})
                .always(function(){
                    Loading.hide();
                });
            }
        }
        return false;
    });

});
