/**
 *  Форма редактирования заинтересованного лица.
 */
$(document).ready(function(){

    const ROLE_SHAREHOLDER = 'Номинальный акционер';
    const ROLE_BENEFICIARY = 'Бенефициар';
    const ROLE_LEADER = 'Директор';
//    const ROLE_SECRETARY = 'Секретарь';
//    const ROLE_MANAGER = 'Менеджер';

    var div_list_org = $('.control-group.list-organizations');
    var div_list_person = $('.control-group.list-individuals');

    var div_job_title = $('.control-group.job-title');
    var div_percent = $('.control-group.percent');
    var div_date_issue = $('.control-group.date-issue');
    var div_num_pack = $('.control-group.num-pack');
    var div_type_stock = $('.control-group.type-stock');
    var div_quant_stock = $('.control-group.quant-stock');

    // --- Физ. лица
    $('#InterestedPerson_type_lico_0').change(function(){
        div_list_org.addClass('hide');
        div_list_person.removeClass('hide');
    });
    // --- Юр. лица
    $('#InterestedPerson_type_lico_1').change(function(){
        div_list_person.addClass('hide');
        div_list_org.removeClass('hide');
    });

    $('#InterestedPerson_role').change(function(){
        var role = $(this).find('option:selected').val();
        if (role == ROLE_LEADER){
            div_job_title.removeClass('hide');

            div_percent.addClass('hide');
            div_date_issue.addClass('hide');
            div_num_pack.addClass('hide');
            div_type_stock.addClass('hide');
            div_quant_stock.addClass('hide');
        } else if (role == ROLE_BENEFICIARY || role == ROLE_SHAREHOLDER){
            div_job_title.addClass('hide');

            div_percent.removeClass('hide');
            div_date_issue.removeClass('hide');
            div_num_pack.removeClass('hide');
            div_type_stock.removeClass('hide');
            div_quant_stock.removeClass('hide');
        } else {
            div_job_title.addClass('hide');

            div_percent.addClass('hide');
            div_date_issue.addClass('hide');
            div_num_pack.addClass('hide');
            div_type_stock.addClass('hide');
            div_quant_stock.addClass('hide');
        }
    });
});
