
$(document).ready(function(){

	$('.numeric').on('input', function(e){
		 var rinp = this.value.replace(/ /g, "");
		 $.getJSON($(this).attr('ajax_url') + '/id/' + this.id + '/val/' + rinp, function(json){
			 var inp = document.getElementById(json['id']);
			 inp.value = json['values'];
		});
    });
});