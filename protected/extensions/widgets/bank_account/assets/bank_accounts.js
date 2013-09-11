$(document).ready(function(){
	var new_count = 0;
	$(document).on('click', '[data-bank_account_link]', function () {
		$.get(this.href, function (data) {
			document.getElementById('modal-header').innerHTML = data.title;
			document.getElementById('modal-body').innerHTML = data.html;
			document.getElementById('modal-footer').innerHTML = data.footer;
			$('#ModalWindow').modal('show');
		}, 'json');
		return false;
	});

	$(document).on('click', '[data-bank_account_delete]', function () {
		var link = $(this);
		$.get(this.href, function (data) {
			if (data.code == 'error') {
				alert(data.message);
			} else if (data.code == 'Ok') {
				link.parent().remove();
			}
		}, 'json');
		return false;
	});

	$(document).on('click', '[data-save_button="account"]', function(){
		var url = $(this).data('link');
		var formdata = {'CBankAccount[account_number]' : $('#CBankAccount_account_number_field').val(),
						'CBankAccount[bank]' : $('#CBankAccount_bank_field').val(),
						'CBankAccount[swift]' : $('#CBankAccount_swift_field').val(),
						'CBankAccount[iban]' : $('#CBankAccount_iban_field').val(),
						'CBankAccount[bik]' : $('#CBankAccount_bik_field').val(),
						'CBankAccount[correspondent]' : $('#CBankAccount_correspondent_field').val()};
		$.post(url, formdata, function(data){
			if (data.code == 'error') {
				alert(data.message);
			} else if (data.code == 'Ok') {
				var link = $('[data-bank_accounts="1"]').find('[data-bank_account_link="'+data.account_id+'"]');
				if (link.length) {
					link.parent().replaceWith(data.new_link);
					if (data.hidden_fields) {
						$('[data-bank_accounts="1"]').append(data.hidden_fields);
					}
				} else {
					$('[data-bank_accounts="1"]').append(data.new_link);
					if (data.hidden_fields) {
						$('[data-bank_accounts="1"]').append(data.hidden_fields);
						$('[data-bank_accounts="1"]').find('[name*=new_count]').each(function (k, l){
							$(l).attr('name', $(l).attr('name').replace('[new_count]', '['+new_count+']'));
						});
						new_count += 1;
					}
				}
				$('#ModalWindow').modal('hide');
			}
		}, 'json');
		return false;
	});
});