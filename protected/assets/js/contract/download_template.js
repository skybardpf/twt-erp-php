$(document).ready(function(){
    var info = $('.model-info');
    $('.download_template').on('click', function(){
        var tid = $(this).data('template-id');
        var url = '/contract/download_template/?';
            url += 'id='+encodeURIComponent(info.data('id'));
            url += '&tid='+encodeURIComponent(tid);

        Loading.show();
        $.ajax({
            type: 'POST',
            dataType: "json",
            url: url,
            cache: false
        }).done(function(data) {
            if (!data.success){
                alert(data.message);
            } else {
                download_template('/site/download/path/'+data.path);
            }

        }).fail(function(a, ret, message) {

        }).always(function(){
            Loading.hide();
        });
        return false;
    });

    function download_template(url){
        var preparingFileModal = $("#preparing-file-modal");

        Loading.show();
        preparingFileModal.dialog({ modal: true });
        $.fileDownload(
            url,
            {
                successCallback: function (url) {
                    preparingFileModal.dialog('close');
                    Loading.hide();
                },
                failCallback: function (responseHtml, url) {
                    preparingFileModal.dialog('close');
                    $("#error-modal").dialog({ modal: true });
                    Loading.hide();
                }
            });
        return false;
    }
});