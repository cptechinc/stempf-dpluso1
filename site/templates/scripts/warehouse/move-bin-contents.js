$(function() {
	// BINR FORM INPUTS
	var form_movecontents = $('.move-contents-form');
	var input_frombin = form_movecontents.find('input[name=from-bin]');
	var input_tobin = form_movecontents.find('input[name=to-bin]');
	
	form_movecontents.validate({
		submitHandler : function(form) {
			var valid_frombin = validate_frombin();
			var valid_tobin = validate_tobin();
			var valid_form = new SwalError(false, '', '', false);
			
			if (valid_frombin.error) {
				valid_form = valid_frombin;
			} else if (valid_tobin.error) {
				valid_form = valid_tobin;
			}
			
			if (valid_form.error) {
				swal({
					type: 'error',
					title: valid_form.title,
					text: valid_form.msg,
					html: valid_form.html
				});
			} else {
				form.submit();
			}
		}
	});
	
	function validate_frombin() {
		var error = false;
		var title = '';
		var msg = '';
		var html = false;
		var lowercase_frombin = input_frombin.val();
		input_frombin.val(lowercase_frombin.toUpperCase());
		
		if (input_frombin.val() == '') {
			error = true;
			title = 'Error';
			msg = 'Please Fill in the From Bin';
		} else if (whsesession.whse.bins.arranged == 'list' && whsesession.whse.bins.bins[input_frombin.val()] === undefined) {
			error = true;
			title = 'Invalid Bin ID';
			msg = 'Please Choose a valid From bin';
		} else if (whsesession.whse.bins.arranged == 'range') {
			error = true;
			title = 'Invalid Bin ID';
			
			whsesession.whse.bins.bins.forEach(function(bin) {
				if (input_frombin.val() >= bin.from && input_frombin.val() <= bin.through) {
					error = false;
				}
			});
			
			if (error) {
				title = 'Invalid From Bin ID';
				msg = 'Your From Bin ID must between these ranges';
				html = "<h4>Valid Bin Ranges<h4>" + create_binrangetable();
			}
			
		}
		return new SwalError(error, title, msg, html);
	}

	function validate_tobin() {
		var error = false;
		var title = '';
		var msg = '';
		var html = false;
		var lowercase_tobin = input_tobin.val();
		input_tobin.val(lowercase_tobin.toUpperCase());
		
		if (input_tobin.val() == '') {
			error = true;
			title = 'Error';
			msg = 'Please Fill in the To Bin';
		} else if (whsesession.whse.bins.arranged == 'list' && whsesession.whse.bins.bins[input_tobin.val()] === undefined) {
			error = true;
			title = 'Invalid Bin ID';
			msg = 'Please Choose a valid To bin';
		} else if (whsesession.whse.bins.arranged == 'range') {
			error = true;
			
			whsesession.whse.bins.bins.forEach(function(bin) {
				if (input_tobin.val() >= bin.from && input_tobin.val() <= bin.through) {
					error = false;
				}
			});
			
			if (error) {
				title = 'Invalid To Bin ID';
				msg = 'Your To Bin ID must between these ranges';
				html = "<h4>Valid Bin Ranges<h4>" + create_binrangetable();
			}
		}
		return new SwalError(error, title, msg, html);
	}
});
