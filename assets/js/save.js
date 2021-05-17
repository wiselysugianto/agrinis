function saveProcess(){
	$('#btn_submit i').removeClass('fa-save').addClass('fa-spin fa-spinner');
	$('button#btn_submit').attr('disabled','disabled');
}

function stopSaveProcess(){
	$('button#btn_submit').removeAttr('disabled');
	$('#btn_submit i').removeClass('fa-spin').removeClass('fa-spinner').addClass('fa-save');
}
