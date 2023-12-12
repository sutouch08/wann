
var validPin = true;

function validatePin(input)
{
	var passw = /^[0-9]{6}$/; //--- required number 6 digit

	if(input.match(passw))
	{
		return true;
	}

	return false;
}


function checkPin() {
	const hasPin = $('#c-pin').length == 1 ? true : false;
	const uname = $('#uname').val();
	const current = $('#cu-pin');
	const newPin = $('#pin');
	const conPin = $('#cm-pin');
	const pinErr = $('#pin-error');
	const cuErr = $('#cu-pin-error');
	const cmErr = $('#cm-pin-error');

	if(hasPin) {
		if(current.val().length === 0) {
			current.addClass('has-error');
			cuErr.text("กรุณาใส่ PIN ปัจจุบัน");
			return false;
		}
		else {
			current.removeClass('has-error');
			cuErr.text('');
		}
	}

	if(newPin.val().length === 0) {
		newPin.addClass('has-error');
		pinErr.text('กรุณากำหนด PIN ใหม่');
		return false;
	}
	else {
		newPin.removeClass('has-error');
		pinErr.text('');
	}

	//--- check use same as current passsword ?
	if(newPin.val() === current.val()) {
		newPin.addClass('has-error');
		pinErr.text("PIN ใหม่ต้องไม่ซ้ำกับ PIN ปัจจุบัน");
		return false;
	}
	else {
		newPin.removeClass('has-error');
		pinErr.text('');
	}

	//--- check complexity
	if( ! validatePin(newPin.val())) {
		newPin.addClass('has-error');
		pinErr.text('กรุณากำหนด PIN เป็นตัวเลข 6 หลักเท่านั้น');
		return false;
	}
	else {
		newPin.removeClass('has-error');
		pinErr.text('');
	}


	if(newPin.val() !== conPin.val()) {
		conPin.addClass('has-error');
		cmErr.text('PIN ไม่ตรงกัน');
		return false;
	}
	else {
		conPin.removeClass('has-error');
		cmErr.text('');
	}

	return true;
}


function changePIN() {
	if(checkPin()) {
		const uname = $('#uname').val();
		const current = $('#cu-pin');
		const newPin = $('#pin');
		const cuErr = $('#cu-pin-error');
		const pinErr = $('#pin-error');
		const hasPin = $('#c-pin').length == 1 ? true : false;

		$.ajax({
			url:BASE_URL + 'user_pin/check_current_pin',
			type:"POST",
			cache:false,
			data: {
				"uname" : uname,
				"pin" : current.val()
			},
			success:function(rs) {
				if(rs === 'valid') {
					$.ajax({
						url:BASE_URL + 'user_pin/change_pin',
						type:'POST',
						cache:false,
						data:{
							'uname' : uname,
							'pin' : current.val(),
							'new_pin' : newPin.val()
						},
						success:function(rs) {
							var rs = $.trim(rs);
							if(rs === 'success') {
								swal({
									title:'Success',
									type:'success',
									timer:1000
								})
							}
							else {
								current.addClass('has-error');
								pinErr.text(rs);
								pinErr.removeClass('hide');
								return false;
							}
						}
					})
				}
				else if(rs === 'invalid') {
					if( ! hasPin) {
						swal({
							title:'Error!',
							text:'PIN ไม่ถูกต้อง',
							type:'error'
						});
					}
					else {
						current.addClass('has-error');
						cuErr.text('PIN ไม่ถูกต้อง');
						return false;
					}
				}
				else {
					if( ! hasPin) {
						swal({
							title:'Error!',
							text:'PIN ไม่ถูกต้อง',
							type:'error'
						});
					}
					else {
						current.addClass('has-error');
						pinErr.text(rs);
						return false;
					}
				}
			}
		})
	}
}


$('#cu-pin').focusout(function() {
	checkPin();
})


$('#pin').focusout(function(){
  checkPin();
})


$('#cm-pin').keyup(function(e){
  checkPin();
})
