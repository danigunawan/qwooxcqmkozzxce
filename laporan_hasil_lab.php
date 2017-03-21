<?php include_once 'session_login.php';
//memasukkan file session login, header, navbar, db.php
include 'header.php';
include 'navbar.php';
include 'sanitasi.php';
include 'db.php';

?>

<div class="container">
<!--DROPDOWN UNTUK FILTER LAPORAN-->


<!-- Modal Untuk LAB RANAP-->
<div id="modal_lab_inap" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">
    <!-- Modal content-->
    <div class="modal-content">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
          <center><h2>Lihat Data Hasil Detail Laboratorium Rawat Inap </h2></center>       
    </div>
    <div class="modal-body">

      <span id="tampil_lab">
      </span>
     
     <form role="form" method="POST">

     <div class="row">
     <div class="col-sm-6">

       <div class="form-group">
      <label for="">No RM</label>
      <input style="height: 20px;" type="text" class="form-control" name="lab_rm" readonly="" autocomplete="off" id="lab_rm" placeholder="No RM">
    </div>

     <div class="form-group">
      <label for="">Nama Pasien</label>
      <input  style="height: 20px;" type="text" class="form-control" name="lab_nama" readonly="" autocomplete="off" id="lab_nama" placeholder="Nama Pasien">
    </div>

     </div>


     <div class="col-sm-6">
       <div class="form-group">
      <label for="">No Faktur</label>
      <input style="height: 20px;" type="text" class="form-control" name="lab_rm" readonly="" autocomplete="off" id="lab_faktur" placeholder="Faktur">
    </div>

    

    <div class="form-group">
      <label for="">No Reg</label>
      <input style="height: 20px;" type="text" class="form-control" name="lab_reg" readonly="" autocomplete="off" id="lab_reg" placeholder=" No Reg">
    </div>

     </div>
     </div>

     
   <center> <a href="'detail_laboratorium_inap.php" type="submit" class="btn btn-info" id="show_lab_inap" data-id=""> <i class="fa fa-send" ></i> Yes</a>


        <button type="button" class="btn btn-danger" data-dismiss="modal" ><i class="fa fa-remove"></i> No</button>
</center> 
     </form>

       </div>
       <div class="modal-footer">
        
    </div>
    </div>
  </div>
</div>
<!--modal end LAB RANAP-->



<div class="row">

<div class="col-sm-2">
<div class="dropdown">
             <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown" style="width:150px"> Jenis Laporan <span class="caret"></span></button>

  <ul class="dropdown-menu dropdown-ins">

      <li><a class="dropdown-item" href="laporan_lab_rekap.php"> Laporan Rekap </a></li>

      <li><a class="dropdown-item" href="laporan_lab_detail.php"> Laporan Detail </a></li>

  </ul>

</div> <!--/ dropdown-->
</div>

<div class="col-sm-3">
</div>
  
</div>
<!--DROPDOWN UNTUK FILTER LAPORAN-->
<br>

<h3><b>Laporan Hasil Laboratorium</b></h3>
<br>

<div id="modal_detail" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"><center><b>Detail Hasil Laboratorium</b></center></h4>
      </div>

      <div class="modal-body">
      <div class="table-responsive">
      <span id="modal-detail"> </span>
      </div>

     </div>

      <div class="modal-footer">
        
  <center> <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button></center> 
      </div>
    </div>

  </div>
</div>


<div class="table-responsive"><!--membuat agar ada garis pada tabel disetiap kolom-->
<span id="result">
<table id="table_lab" class="table table-bordered table-sm">
		<thead>
			<th style="background-color: #4CAF50; color: white;"> Input Hasil</th>
			<th style="background-color: #4CAF50; color: white;"> Cetak</th>
			<th style="background-color: #4CAF50; color: white;"> No RM</th>
			<th style="background-color: #4CAF50; color: white;"> No REG</th>
			<th style="background-color: #4CAF50; color: white;"> No Faktur</th>
			<th style="background-color: #4CAF50; color: white;"> Pasien</th>
			<th style="background-color: #4CAF50; color: white;"> Dokter</th>
			<th style="background-color: #4CAF50; color: white;"> Analis</th>
			<th style="background-color: #4CAF50; color: white;"> Status Rawat </th>
			<th style="background-color: #4CAF50; color: white;"> Tanggal </th>
			<th style="background-color: #4CAF50; color: white;"> Detail </th>
		</thead>
		<tbody>
			

		</tbody>

	</table>
</span>
 <h6 style="text-align: left ; color: red"><i>* Bisa Cetak Jika Input Hasil Sudah Selesai dan Penjualan Sudah Selesai ( No Faktur Tidak Kosong ) !!</i></h6>
 <h6 style="text-align: left ; color: red"><i>* Detail Laboratorium Akan Tampil Jika Sudah Melakukan Penjualan !!</i></h6>
</div> <!--/ responsive-->

</div>



<!--start ajax datatable-->
<script type="text/javascript" language="javascript" >
      $(document).ready(function() {
        var dataTable = $('#table_lab').DataTable( {
          "processing": true,
          "serverSide": true,
          "ajax":{
            url :"show_data_hasil_lab.php", // json datasource
            type: "post",  // method  , by default get
            error: function(){  // error handling
              $(".tbody").html("");

             $("#table_lab").append('<tbody class="tbody"><tr><th colspan="3">Tidak Ada Data Yang Ditemukan</th></tr></tbody>');

              $("#table_lab_processing").css("display","none");
              
            }
          },
              "fnCreatedRow": function( nRow, aData, iDataIndex ) {
              $(nRow).attr('class','tr-id-'+aData[11]+'');
            },
        } );
      } );
    </script>
<!--end ajax datatable-->


<script type="text/javascript">
$(document).ready(function () {
$(document).on('click', '.detail-lab', function (e) {

		var no_faktur = $(this).attr('data-faktur');

		
		$("#modal_detail").modal('show');
		
		$.post('show_hasil_lab.php',{no_faktur:no_faktur},function(info) {
		
		$("#modal-detail").html(info);
		
		
		});
		
		});
		});
</script>


<!--SKRIPT DETAIL RAWAT INAP -->
<script type="text/javascript">
     $(document).on('click', '.detail-lab-inap', function (e) {
               var rm = $(this).attr("data-rm");
               var nama = $(this).attr("data-nama");
               var reg = $(this).attr("data-reg");
               var faktur = $(this).attr("data-faktur");

  
        $("#modal_lab_inap").modal('show');

               $("#lab_nama").val(nama);
               $("#lab_rm").val(rm);
               $("#lab_reg").val(reg);
               $("#lab_faktur").val(faktur);

  $("#show_lab_inap").attr('href','detail_laboratorium_inap.php?no_reg='+reg+'&faktur='+faktur+'&nama='+nama+'&no_rm='+rm);
               
     });
</script>
<!-- SKRIPT DETAIL RAWAT INAP -->

<?php include 'footer.php'; ?>