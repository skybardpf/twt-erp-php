/**
 * @global {Number | NULL} window.organization_id
 *
 * @author Skibardin A.A. <webprofi1983@gmail.com>
 */
$(document).ready(function(){
    const COUNTRY_RUSSIAN_ID = 643;

    list_country_fields();

    /**
     * Показываем поля в зависимости от выбранной страны.
     */
    function list_country_fields(){
        var country_id = $('.list-countries').val();
        if(country_id == COUNTRY_RUSSIAN_ID){ // Россия
            $('#rus_fields').show();
            $('#foreign_fields').hide();
        } else {
            $('#rus_fields').hide();
            $('#foreign_fields').show();
        }
    }
});