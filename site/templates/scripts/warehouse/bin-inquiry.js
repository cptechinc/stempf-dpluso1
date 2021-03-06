$(function() {
	// BINR FORM INPUTS
	var input_bin = $('input[name=binID]');
	var form_physicalcount = $('.physical-count-form');
	
	/**
	 * The Order of Functions based on Order of Events
	 * 1. Select Bin / Validate BIN
	 * 2. Select Item
	 * 3. Physical Count 
	 */
	
/////////////////////////////////////
// 1. Select Bin
////////////////////////////////////
	/**
	 * Validate Bin form
	 */
	$(".select-bin-form").validate({
		submitHandler : function(form) {
			var valid_form = new SwalError(false, '', '', false);
			var valid_bin = validate_binID();
			
			if (valid_bin.error) {
				valid_form = valid_bin;
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
	
	/**
	 * Validates Bin ID and returns with an Swal Error object for details
	 * // NOTE THIS IS USED IN STEPS 1, 2
	 * @return SwalError Error 
	 */
	function validate_binID() {
		var error = false;
		var title = '';
		var msg = '';
		var html = false;
		
		var bin_lower = input_bin.val();
		input_bin.val(bin_lower.toUpperCase());
		
		if (input_bin.val() == '') {
			error = true;
			title = 'Error';
			msg = 'Please Fill in the Bin ID';
		} else if (whsesession.whse.bins.arranged == 'list' && whsesession.whse.bins.bins[input_bin.val()] === undefined) {
			error = true;
			title = 'Invalid Bin ID';
			msg = 'Please Choose a valid bin ID';
		} else if (whsesession.whse.bins.arranged == 'range') {
			error = true;
			title = 'Invalid Bin ID';
			msg = 'Please Enter a valid bin ID';
			html = "<h4>Valid Bin Ranges<h4>"  + create_binrangetable();
			
			whsesession.whse.bins.bins.forEach(function(bin) {
				if (input_bin.val() >= bin.from && input_bin.val() <= bin.through) {
					error = false;
				}
			});
		}
		return new SwalError(error, title, msg, html);
	}
});
