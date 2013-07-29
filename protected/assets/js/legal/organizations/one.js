/**
 * User: Forgon / Yury Zyuzkevich
 * Date: 24.04.2013 от Рождества Христова
 * Time: 13:52
 */
$(document).ready(function(){
    list_country_fields();
    $('#Organizations_country').on('change', function(){
        list_country_fields();
    });
});

function list_country_fields(){
    country_id = $('#Organizations_country').val();
    if(country_id == '643'){ // Россия
        $('#rus_fields').show();
        $('#foreign_fields').hide();
    }
    else{
        $('#rus_fields').hide();
        $('#foreign_fields').show();
    }
}
