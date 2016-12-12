<?php 
include 'sanitasi.php';
include 'db.php';

$no_faktur_pembayaran = $_POST['no_faktur_pembayaran'];


$query = $db->query("SELECT * FROM detail_pembayaran_hutang WHERE no_faktur_pembayaran = '$no_faktur_pembayaran'");

?>
					<div class="container">
					
					<div class="table-responsive">
					<table id="tableuser" class="table table-bordered">
					<thead>
					<th> No Faktur Pembayaran</th>
					<th> No Faktur Pembelian </th>
					<th> Suplier</th>
					<th> Tanggal </th>
					<th> Jatuh Tempo </th>
					<th> Kredit </th>
					<th> Potongan </th>
					<th> Total </th>
					<th> Jumlah Bayar </th>
					</thead>
					
					
					<tbody>
					
					<?php
					
					//menyimpan data sementara yang ada pada $perintah
					while ($data1 = mysqli_fetch_array($query))
					{

		$suplier = $db->query("SELECT id,nama FROM suplier WHERE id = '$data1[suplier]'");
        $out = mysqli_fetch_array($suplier);
        if ($data1['suplier'] == $out['id'])
        {
          $out['nama'];
        }
					//menampilkan data
					echo "<tr>
					<td>". $data1['no_faktur_pembayaran'] ."</td>
					<td>". $data1['no_faktur_pembelian'] ."</td>
					<td>". $out['nama'] ."</td>
					<td>". $data1['tanggal'] ."</td>
					<td>". $data1['tanggal_jt'] ."</td>
					<td>". $data1['kredit'] ."</td>
					<td>". rp($data1['potongan']) ."</td>
					<td>". rp($data1['total']) ."</td>
					<td>". rp($data1['jumlah_bayar']) ."</td>
					</tr>";
					}
					
					//Untuk Memutuskan Koneksi Ke Database
					mysqli_close($db);   
					?>
					
					</tbody>
					</table>
					</div>
					</div>
<script>
		
		// untk menampilkan datatable atau filter seacrh
		$(document).ready(function(){
		$('#tableuser').DataTable({"ordering":false});
		});
</script>