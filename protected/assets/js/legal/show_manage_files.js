/**
 *  @global {String} controller_name
 */
$(document).ready(function(){
    if (window.controller_name === undefined || window.controller_name == ''){
        throw new Error("Не определен котроллер.");
    }

    var info = $('.model-info');
    $('.download_file').on('click', function(){
        var type = $(this).data('type');
        var url = '/legal/download/download/?';
            url += 'id='+encodeURIComponent(info.data('id'));
            url += '&class_name='+encodeURIComponent(info.data('class-name'));
            url += '&type='+type;
            url += '&file='+encodeURIComponent($(this).html());

        var preparingFileModal = $("#preparing-file-modal");
        preparingFileModal.dialog({ modal: true });
        $.fileDownload(
            url,
            {
                successCallback: function (url) {
                    preparingFileModal.dialog('close');
                },
                failCallback: function (responseHtml, url) {
                    preparingFileModal.dialog('close');
                    $("#error-modal").dialog({ modal: true });
                }
            });
        return false;
    });

    $('.delete_file').on('click', function(){
        var local = $(this);

        var type = $(this).data('type');
        var json, grid;
        if (type == 'file'){
            json = $('#PowerAttorneyForContractor_json_exists_files');
            grid = $('#grid-files');
        } else if (type == 'scan') {
            json = $('#PowerAttorneyForContractor_json_exists_scans');
            grid = $('#grid-scans');
        } else {
            return false;
        }

        var filename = local.data('filename');

        $('<div>Вы действительно хотите удалить файл?</div>').dialog({
            modal: true,
            resizable: false,
            title: 'Удаление файла',
            buttons: [
                {
                    text: "Удалить",
                    class: 'btn btn-danger',
                    click: function(event){
                        var url = '/legal/download/delete/?';
                            url += 'id='+encodeURIComponent(info.data('id'));
                            url += '&class_name='+encodeURIComponent(info.data('class-name'));
                            url += '&type='+type;
                            url += '&file='+encodeURIComponent(filename);

                        var button = $(event.target);
                        var dialog = $(this);
                        button.attr('disabled', 'disabled');
                        Loading.show();
                        $.ajax({
                            type: 'GET',
                            dataType: "json",
                            url: url
                        })
                        .done(function(data, success) {
                            if (success == 'success'){
                                if (!data.success){
                                    alert(data.message);
                                } else {
                                    var files = $.parseJSON(json.val());
                                    var ind = files.indexOf(filename);
                                    if (ind != -1){
                                        files.splice(ind, 1);
                                        json.val($.toJSON(files));
                                        local.parents('tr').remove();
                                    }

                                    var table = grid.find('table');
                                    if (table.find('tr').size() == 1){
                                        table.find('tbody').append(
                                            '<tr>' +
                                                '<td colspan="2" class="empty">' +
                                                '<span class="empty">Нет результатов.</span>' +
                                                '</td>' +
                                            '</tr>'
                                        );
                                    }
                                }
                            }
                        })
                        .fail(function() {
//                            console.log("error");
                        })
                        .always(function(){
                            Loading.hide();
                        });
                        dialog.dialog('destroy');
                    }
                },{
                    text: 'Отмена',
                    class: 'btn',
                    click: function(){
                        $(this).dialog('destroy');
                    }
                }
            ]
        });
        return false;
    });
});