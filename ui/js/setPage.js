var information = "Informasi";
var warning = "Peringatan";

function LoadPage(url) {
	$('#CMSModalForm').modal('hide');
	$('#CMSModalDel').modal('hide');
    $.ajax({
		url: url,
		beforeSend: function() {
			$('#loading').show();
		},
		success: function(data) {
			$('#loading').hide();
			$('#setPage').html(data);
		}
	});
}

function LoadModalForm(url,size='modal-md') {
	$('#modal-size').attr('class', 'modal-dialog '+size);
	$('#CMSModalForm').modal('show');
    $.ajax({
		url: url,
		beforeSend: function() {
			$('#loading').show();
		},
		success: function(data) {
			$('#loading').hide();
			$('#modal-element').html(data);
		}
	});
}

function LoadModalFileManager(url,size='modal-md') {
	$('#modal-size-filemanager').attr('class', 'modal-dialog '+size);
	$('#CMSModalFileManager').modal('show');
    $.ajax({
		url: url,
		beforeSend: function() {
			$('#loading').show();
		},
		success: function(data) {
			$('#loading').hide();
			$('#modal-element-filemanager').html(data);
		}
	});
}

function LoadModalFormFileManager(url,size='modal-md') {
	$('#modal-size-filemanager-add').attr('class', 'modal-dialog '+size);
	$('#CMSModalFileManagerAdd').modal('show');
    $.ajax({
		url: url,
		beforeSend: function() {
			$('#loading').show();
		},
		success: function(data) {
			$('#loading').hide();
			$('#modal-element-filemanager-add').html(data);
		}
	});
}

function LoadModalDel(url) {
	$('#CMSModalDel').modal('show');
	$('#btn-del').attr('url', url);
}

function LoadDelData(url) {
	$.ajax({
		url: url,
		dataType: 'JSON',
		beforeSend: function() {
			$('#loading').show();
		},
		success: function(data) {
			console.log(data);
			$('#loading').hide();
			if(data._redirect==true) {
				LoadPage(data._page);
				LoadNotifikasi('success','<b>' + information + '</b><br>' + data._pesan);
			}else {
				LoadNotifikasi('danger','<b>' + warning + '</b><br>' + data._pesan);
			}
		}
	});
}

function LoadAjaxRefresh(url) {
	$.ajax({
		url: url,
		dataType: 'JSON',
		beforeSend: function() {
			$('#loading').show();
		},
		success: function(data) {
			$('#loading').show();
			if(data._redirect==true) {
				LoadNotifikasi('success','<b>' + information + '</b><br>' + data._pesan);
				setTimeout(function(){ window.location.href = data._page; }, 1000);
			}else {
				LoadNotifikasi('danger','<b>' + warning + '</b><br>' + data._pesan);
			}
		}
	});
}

function LoadNotifikasi(notif,msg) {
	$('#notifikasi').append('<div class="alert alert-' + notif + ' alert-dismissible animated bounce">'
               + '<i class="fa fa-exclamation-circle fa-fw fa-2x pull-left"></i>'
               + '<button class="close" data-dismiss="alert">×</button>'
               + msg
            + '</div>');
	setTimeout(function() {
		$('.alert').fadeOut('slow',function() {
			$('.alert').alert('close');
		});
	}, 5000);
}

function setFieldId(id){
	setTimeout(function(){
		$('#fieldId').val(id);
	}, 1000);
}