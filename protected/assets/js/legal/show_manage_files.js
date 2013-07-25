/**
 *  @global {String} controller_name
 */
$(document).ready(function(){
    if (window.controller_name === undefined || window.controller_name == ''){
        throw new Error("Не определен котроллер.");
    }

    $('.links_for_download .download_online').on('click', function(ev){
        var id = $(this).parent('.links_for_download').data('id');
        window.open('/legal/'+controller_name+'/download_archive/id/'+id+'/type/files');
        return false;
    });

    $('.links_for_download .download_scans').on('click', function(ev){
        var id = $(this).parent('.links_for_download').data('id');
        window.open('/legal/'+controller_name+'/download_archive/id/'+id+'/type/scans');
        return false;
    });

    $('.links_for_download .download_generic_doc').on('click', function(ev){
        return false;
    });
});