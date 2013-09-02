/**
 *  Форма редактирования заинтересованного лица.
 */
$(document).ready(function(){

    var div_list_org = $('.control-group.list-organizations');
    var div_list_cont = $('.control-group.list-contractors');
    var div_list_person = $('.control-group.list-individuals');

    // --- Физ. лица
    $("input.type_lico[value='ФизическиеЛица']").change(function(){
        if (div_list_org)
            div_list_org.addClass('hide');
        if (div_list_cont)
            div_list_cont.addClass('hide');
        div_list_person.removeClass('hide');
    });
    // --- Организации
    $("input.type_lico[value='Организации']").change(function(){
        if (div_list_person)
            div_list_person.addClass('hide');
        if (div_list_cont)
            div_list_cont.addClass('hide');
        div_list_org.removeClass('hide');
    });
    // --- Контрагенты
    $("input.type_lico[value='Контрагенты']").change(function(){
        if (div_list_person)
            div_list_person.addClass('hide');
        if (div_list_org)
            div_list_org.addClass('hide');
        div_list_cont.removeClass('hide');
    });
});
