$(function() {
	// BINR FORM INPUTS
	var input_frombin = $('.binr-form').find('input[name=from-bin]');
	var input_tobin = $('.binr-form').find('input[name=to-bin]');
	var input_qty = $('.binr-form').find('input[name=qty]');
	
	
	$("body").on("click", ".binr-inventory-result", function(e) {
		var button = $(this);
		var desc = button.data('desc');
		var qty = parseInt(button.data('qty'));
		var title =  desc.toUpperCase() + ' ' + button.data('item') + ' is Not Available';
		
		if (qty < 1) {
			e.preventDefault();
			swal({
				type: 'error',
				title: title,
				text: 'The system does not see any quantity at this location'
			});
		}
	});
	
	$("body").on("click", ".choose-bin", function(e) {
		e.preventDefault();
		var binrow = $(this);
		var binID = binrow.data('binid');
		var qty = binrow.data('qty');
		var bindirection = binrow.data('direction');
		
		$('.binr-form').find('input[name='+bindirection+'-bin]').val(binID);
		input_qty.val(qty);
		$('.binr-form').find('.qty-available').text(qty);
		binrow.closest('.list-group').parent().addClass('hidden');
	});
	
	$("body").on("click", ".show-select-bins", function(e) {
		e.preventDefault();
		var button = $(this);
		var bindirection = button.data('direction');
		$('.choose-'+bindirection+'-bins').parent().removeClass('hidden').focus();
	});
	
	$("body").on("click", ".use-bin-qty", function(e) {
		e.preventDefault();
		var button = $(this);
		var bindirection = button.data('direction');
		var binID = $('.binr-form').find('input[name='+bindirection+'-bin]').val();
		var binqty = $('.choose-'+bindirection+'-bins').find('[data-binid="'+binID+'"]').data('qty');
		$('.binr-form').find('.qty-available').text(binqty);
		input_qty.val(binqty);
	});
	
	$(".binr-form").validate({
		submitHandler : function(form) {
			var valid_frombin = validate_frombin();
			var valid_qty = validate_qty();
			var valid_tobin = validate_tobin();
			var valid_form = new SwalError(false, '', '');
			
			if (valid_frombin.error) {
				valid_form = valid_frombin;
			} else if (valid_qty.error) {
				valid_form = valid_qty;
			} else if (valid_tobin.error) {
				valid_form = valid_tobin;
			}
			
			if (valid_form.error) {
				swal({
					type: 'error',
					title: valid_form.title,
					text: valid_form.msg
				});
			} else {
				form.submit();
			}
		}
	});
	
	// IF WAREHOUSE HAS A BIN LIST
	$("body").on("click", ".show-possible-bins", function(e) {
		e.preventDefault();
		var button = $(this);
		
		if (whsesession.whse.bins.arranged == 'list') {
			var bins = {};
			var binid = '';
			var spacesneeded = 0;
			var spaces = '';
			
			for (var key in whsesession.whse.bins.bins) {
				binid = key;
				spacesneeded = (8 - binid.length);
				spaces = '';
				for (var i = 0; i <= spacesneeded; i++) {
					spaces += '&nbsp;';
				}
				bins[key] = binid + spaces + whsesession.whse.bins.bins[key];
			}
			swal_choosebin();
		} else {
			var msg = 'Warehouse bin range is between ' + whsesession.whse.bins.bins.from + ' and ' + whsesession.whse.bins.bins.through;
			swal({
				type: 'info',
				title: 'Bin Range',
				text: msg
			}).catch(swal.noop);
		}
	});
	
	function swal_choosebin() {
		swal({
			type: 'question',
			title: 'Choose a bin',
			input: 'select',
			inputClass: 'form-control',
			inputOptions: bins,
		}).then(function (input) {
			if (input) {
				input_tobin.val(input);
				swal.close();
			} 
		}).catch(swal.noop);
	}
	
	function validate_frombin() {
		var error = false;
		var title = '';
		var msg = '';
		var lowercase_frombin = input_frombin.val();
		input_frombin.val(lowercase_frombin.toUpperCase());
		
		if (input_frombin.val() == '') {
			error = true;
			title = 'Error';
			msg = 'Please Fill in the From Bin';
		}
		return new SwalError(error, title, msg);
	}

	function validate_qty() {
		var error = false;
		var title = '';
		var msg = '';
		
		if (input_qty.val() == '') {
			error = true;
			title = 'Error';
			msg = 'Please fill in the Qty';
		}
		return new SwalError(error, title, msg);
	}

	function validate_tobin() {
		var error = false;
		var title = '';
		var msg = '';
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
		} else if (whsesession.whse.bins.arranged == 'list' && input_tobin.val() < whsesession.whse.bins.bins.from || input_tobin.val() > whsesession.whse.bins.bins.through) {
			error = true;
			title = 'Invalid Bin ID';
			msg = 'To Bin must be between ' + whsesession.whse.bins.bins.from + ' and ' + whsesession.whse.bins.bins.through;
		}
		return new SwalError(error, title, msg);
	}
});
