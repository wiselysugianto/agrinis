<?php
$this->load->view('template/head');
?>

<body>
	<div class="navbar navbar-default">
		<div class="container">
			<h2><span class="glyphicon glyphicon-home"></span>&nbsp;Welcome to my Application</h2>
		</div>
	</div>
	<div class="container">
		<h3>Daftar Pembayaran</h3>
		<button id="btnTambah" class="btn btn-success">Tambah</button><br><br>
			<table id="example1" class="table table-bordered table-hover" role="grid">
				<thead>
					<tr>
						<th>ID</th>
						<th>Pembayaran</th>
            <th>Jumlah Buruh</th>
						<th>Aksi</th>
					</tr>
				</thead>
				<tbody id="showdata">
					<!-- result get data from controller -->
				</tbody>
			</table>
	</div>

	<div id="modalForm" class="modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Modal title</h4>
      </div>
      <div class="modal-body">
      	<form class="form-horizontal" id="myForm">
					<input type="hidden" id="id" name="id" class="form-control" value="0"/>
					<div class="form-group">
            <label class="label-control col-md-2">Pembayaran:</label>
						<div class="col-md-6">
							<?php echo form_input('pembayaran', '', array('class' => 'form-control', 'placeholder' => 'Pembayaran', 'id' => 'pembayaran', 'onkeyup' => 'hitung()','onfocus' => 'this.select()'));?>
							<div id="error"></div>
						</div>
            <div class="col-md-3">
							<a href="javascript:tambahBuruh(1)" class="btn bg-purple btn-sm"><i class="fa fa-plus-square"></i>&nbsp;&nbsp; Add Detail</a>
						</div>
          </div>

          <div id="daftar_buruh">

          </div>

          <hr>
          <h4><b>Perhitungan</b></h4>
          <div id="hitung_buruh">

          </div>
				</form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" id="btnSave" class="btn btn-primary">Save</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<div id="deleteModal" class="modal fade" tabindex="-1" role="dialog">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">Konfirmasi Hapus</h4>
			</div>
			<div class="modal-body">
				Anda yakin akan menghapus data ini?
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				<button type="button" id="btnDelete" class="btn btn-danger">Delete</button>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

</body>
<?php
	$this->load->view('template/footer');
  $this->load->view('template/js');
?>

<script>
$(document).ready(function(){
  showAllUser();
  tambahBuruh(1);

	function initDatatable(){
		$('#example1').dataTable({
			"aoColumnDefs":[
				{"aTargets":[0],"className": "dt_center","bSortable":false},
				{"aTargets":[1],"className": "dt_left"},
				{"aTargets":[2],"className": "dt_center"},
				{"aTargets":[3],"className": "dt_center","bSortable":false},
			],
				"paging": true,
				"lengthChange": true,
				"searching": true,
				"ordering": true,
				"info": false,
				"autoWidth": false,
				"bDestroy":true,
		});
	}

	$('#btnTambah').click(function(){
    showModal('modalForm','Tambah Pembayaran','<?=$this->control?>/inputTableData');
	});

	// Simpan data
	$('#btnSave').click(function(){
	 var url = $('#myForm').attr('action');
	 var data = $('#myForm').serialize();

    if(!checkData()){return false;}

		$.ajax({
			method:'post',
			url:url,
			data:data,
			async:false,
			dataType:'json',
      beforeSend : function() {
        lockBrowser();
      },
			success:function(data){
        unlockBrowser();
        // console.log(data);
				if(data.type=='add'){var type = 'Tambah';}else if(data.type=='edit'){var type = 'Edit';}

				if(data.status){
					$('#modalForm').modal('hide');
					showOverhang('success','Success! '+type+' pembayaran berhasil');
					showAllUser();
				}else{
					$.each(data.validation, function(key, value) {
							$('#'+key).addClass('is-invalid');
							$('#'+key).parents('.form-group').find('#error').html(value);
					});

					$.each(data.validation.buruh, function(key, value) {
							$('#error_'+key).addClass('is-invalid');
							$('#error_'+key).html(value);
					});
					showOverhang('error','Error! '+type+' pembayaran gagal');
				}
			},
		});
	});

	// get Edit Data
	$('#showdata').on('click','.item-edit',function(){
    showModal('modalForm','Edit Pembayaran','<?=$this->control?>/editTableData');
		var id = $(this).attr('data');
		$.ajax({
			method:'get',
			url:base_url+'<?=$this->control?>/getEditTableData',
			data:{id:id},
			async:false,
			dataType:'json',
      beforeSend : function() {
        lockBrowser();
      },
			success:function(data){
        unlockBrowser();
				hapusSemuaBuruh();
				$.each(data.header, function(key, value) {
						$('#' + key).val(value);
				});

				var jumlahBuruh = 0;
				$.each(data.detail, function(key, value) {
					  tambahBuruh(1);
					  $('#buruh_'+array_id[jumlahBuruh]).val(value.buruh);
						$('#id_buruh_'+array_id[jumlahBuruh]).val(value.id);
						jumlahBuruh++;
				});
				hitung();
			},
		});
	});

	// Delete
	$('#showdata').on('click','.item-delete',function(){
		var id = $(this).attr('data');
		$('#deleteModal').modal('show');
    showModal('deleteModal','Hapus Pembayaran','')
		$('#btnDelete').unbind().click(function(){
			$.ajax({
				type:'ajax',
				method:'get',
				url:base_url+'<?=$this->control?>/deleteTableData',
				data:{id:id},
				async:false,
				dataType:'json',
        beforeSend : function() {
          lockBrowser();
        },
				success:function(data){
          unlockBrowser();
					if(data.type=='delete'){var type = 'Hapus';}
					if(data.status){
						$('#deleteModal').modal('hide');
						showOverhang('success','Success! '+type+' pembayaran berhasil');
						showAllUser();
					}else{
						showOverhang('error','Error! '+type+' pembayaran gagal');
					}
				},
			});
		});
	});

	// function show data
	function showAllUser(){
		$.ajax({
			type:'ajax',
			url:base_url+'<?=$this->control?>/getTableData',
			async:false,
			dataType:'json',
			success:function(data){

				var html = '';
				var i;
				for(i=0;i<data.length;i++){
					html += '<tr>'+
										'<td>'+data[i].id+'</td>'+
										'<td>'+data[i].pembayaran+'</td>'+
										'<td>'+data[i].jumlah_buruh+'</td>'+
										'<td>'+
											'<a href="javascript:;" class="btn btn-info item-edit" data="'+data[i].id+'">Edit</a>&nbsp&nbsp'+
											'<a href="javascript:;" class="btn btn-danger item-delete" data="'+data[i].id+'">Delete</a>'+
										'</td>'+
									'</tr>';

				}
				$('#example1').DataTable().clear().destroy();
				$('#showdata').html(html);
				initDatatable();
			},
		});
	}
});

function showModal(modal_name,title,modal_url){
	hapusSemuaBuruh();
	tambahBuruh(1);
  $('.form-group').find('#error').html('');
  $('#'+modal_name).modal('show');
  $('#'+modal_name).find('.modal-title').text(title);
  $('#myForm').attr('action',base_url+modal_url);
}

var array_id = [];
function tambahBuruh(jumlah){
  var a = '';var b = '';
  for(i=1;i<=jumlah;i++){
    var random = Math.floor((Math.random() * 10000000) + 1);
    a += '<div class="form-group" id="daftar_buruh_'+random+'">'+
            '<label class="label-control col-md-2">Buruh :</label>'+
            '<div class="col-md-3">'+
              '<input class="form-control buruh[]" placeholder="Buruh" id="buruh_'+random+'" name="buruh[]" onfocus="this.select()" onkeyup="hitung()" value="0"></input>'+
							'<input type="hidden" id="id_buruh_'+random+'" name="id_buruh[]"></input>'+
							'<input type="hidden" name="array_buruh[]" value="'+random+'"></input>'+
              '<div class="error" id="error_'+random+'"></div>'+
            '</div>'+
            '<div class="col-md-3">'+
              '<a class="btn btn-danger btn-xs btn-flat" data-toggle="tooltip" href="javascript:void(0);" onclick="hapusBuruh('+random+')" tabindex="-1" data-original-title="Hapus">'+
            '</div>'+
          '</div>';

    b += '<div class="form-group" id="hitung_buruh_'+random+'">'+
            '<label class="label-control col-md-2">Buruh:</label>'+
            '<div class="col-md-6">'+
              '<div id="hitung_'+random+'">0</div>'+
            '</div>'+
          '</div>';
    array_id.push(random);
  }
  $('#daftar_buruh').append(a);
  $('#hitung_buruh').append(b);
}

function hapusBuruh(id){
  if(array_id.length < 3){
    alert('Minimal 3 buruh')
    return false;
  }

  var index_to_delete = array_id.indexOf(id);
  array_id.splice(index_to_delete, 1);
  $('#daftar_buruh_'+id).remove();
  $('#hitung_buruh_'+id).remove();
}

function hapusSemuaBuruh(){
	array_id = [];
	$('#pembayaran').val('0');
	$('#daftar_buruh').html('');
	$('#hitung_buruh').html('');
}

function hitung(){
  var pembayaran = $('#pembayaran').val();
  if(array_id.length > 0){
    for (k = 0; k < array_id.length; k++) {
        var buruh = $('#buruh_'+array_id[k]).val();
        total = parseFloat(pembayaran) * parseFloat(buruh) / 100;
        $('#hitung_'+array_id[k]).text(total);
     }
   }
}

function checkData(){
  var total = 0;
  if(array_id.length > 0){
    for (k = 0; k < array_id.length; k++) {
        var buruh = $('#buruh_'+array_id[k]).val();
        total = parseFloat(total) + parseFloat(buruh);
     }
     if(array_id.length < 3){
			 swalFireBasic('Error','Minimal 3 buruh','error');
       return false;
     }

     if(total < 100){
			 swalFireBasic('Error','Belum 100 persen','error');
       return false;
     }else if(total > 100){
			 swalFireBasic('Error','Maksimal 100 persen','error');
       return false;
     }
   }
   return true;
}
</script>
