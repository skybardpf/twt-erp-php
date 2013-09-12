/**
 * Корзина акционирования
 */
$(document).ready(function(){
    $('#organization_id').change(function(){
        var url = '/cart_corporatization/'+window.scheme+'/type/'+window.orgType;
        var org_id = $(this).val();
        if (org_id != '')
            url += '/oid/'+org_id;
        window.location.href = url;
    });

    $('#individual_id').change(function(){
        var url = '/cart_corporatization/direct/type/'+window.orgType;
        var iid = $(this).val();
        var oid = $('#organization_id').val();
        if (iid != '' && iid != '')
            url = '/cart_corporatization/indirect/type/'+window.orgType+'/oid/'+oid+'/iid/'+iid;
        window.location.href = url;
    });
});
