<p><b>Catatan:</b> Jika stok sudah habis atau tidak mencukupi untuk di keluar kan, maka tidak akan langsung di lakukan perbaikan otomatis oleh program</p>
<table border="1">
	<thead>
		<th>Tanggal</th>
		<th>No Faktur</th>
		<th>Kode Barang</th>
		<th>Nama Barang</th>
		<th>Selisih Fisik</th>
		<th>Status Hpp Keluar / Masuk</th>
		<th>Stok Sekarang</th>
	</thead>
<tbody><?php 
include 'db.php';
include 'sanitasi.php';
include 'persediaan.function.php';

$dari_tanggal = stringdoang($_GET['dari_tanggal']);
$sampai_tanggal = stringdoang($_GET['sampai_tanggal']);



$query_detail_stok_opname = $db->query("SELECT no_faktur,
tanggal,
jam,
kode_barang,
nama_barang,
awal,
masuk,
keluar,
stok_sekarang,
fisik,
selisih_fisik,
selisih_harga,
harga,
hpp FROM detail_stok_opname WHERE tanggal >= '$dari_tanggal' AND tanggal <= '$sampai_tanggal'");


while ($data_detail_stok_opname = mysqli_fetch_array($query_detail_stok_opname)) {

	$stok_barang = cekStokHpp($data_detail_stok_opname['kode_barang']);
	
	if ($data_detail_stok_opname['selisih_fisik'] < 0) {



		# code...
		$query_hpp_keluar = $db->query("SELECT COUNT(*) AS jumlah_data FROM hpp_keluar WHERE kode_barang = '$data_detail_stok_opname[kode_barang]' AND no_faktur = '$data_detail_stok_opname[no_faktur]'");

		$data_hpp_keluar = mysqli_fetch_array($query_hpp_keluar);

		if ($data_hpp_keluar['jumlah_data'] == 0) {
			# code...
			$status_selisih_hpp = 'Tidak Ada';
			$sisa_barang = $stok_barang - $data_detail_stok_opname['selisih_fisik'];

			if ($sisa_barang >= 0) {
				# code...

					$db->query("DELETE FROM  detail_stok_opname WHERE no_faktur = '$data_detail_stok_opname[no_faktur]' AND kode_barang = '$data_detail_stok_opname[kode_barang]'");

			  $query_insert_detail_stok_opname = "INSERT INTO detail_stok_opname (no_faktur, tanggal, jam, kode_barang, nama_barang, awal, masuk, keluar, stok_sekarang, fisik, selisih_fisik, selisih_harga, harga, hpp) 
            VALUES ('$data_detail_stok_opname[no_faktur]', '$data_detail_stok_opname[tanggal]', '$data_detail_stok_opname[jam]', '$data_detail_stok_opname[kode_barang]', '$data_detail_stok_opname[nama_barang]', '$data_detail_stok_opname[awal]', '$data_detail_stok_opname[masuk]', '$data_detail_stok_opname[keluar]', '$data_detail_stok_opname[stok_sekarang]', '$data_detail_stok_opname[fisik]', '$data_detail_stok_opname[selisih_fisik]', '$data_detail_stok_opname[selisih_harga]', '$data_detail_stok_opname[harga]', '$data_detail_stok_opname[hpp]')";

                if ($db->query($query_insert_detail_stok_opname) === TRUE) {
                
	            } else {
	            echo "Error: " . $query_insert_detail_stok_opname . "<br>" . $db->error;
	            }

			}
		
            
            

		}
		else {
			$status_selisih_hpp = 'Ada';
		}



	}
	else {

		$query_hpp_masuk = $db->query("SELECT COUNT(*) AS jumlah_data FROM hpp_masuk WHERE kode_barang = '$data_detail_stok_opname[kode_barang]' AND no_faktur = '$data_detail_stok_opname[no_faktur]'");
		
		$data_hpp_masuk = mysqli_fetch_array($query_hpp_masuk);

		if ($data_hpp_masuk['jumlah_data'] == 0) {

			$status_selisih_hpp = 'Tidak Ada';
		}
		else {
			$status_selisih_hpp = 'Ada';
		}

	}


	echo "<tr>
	<td>".$data_detail_stok_opname['tanggal']."</td>
	<td>".$data_detail_stok_opname['no_faktur']."</td>
	<td>".$data_detail_stok_opname['kode_barang']."</td>
	<td>".$data_detail_stok_opname['nama_barang']."</td>
	<td>".$data_detail_stok_opname['selisih_fisik']."</td>
	<td>".$status_selisih_hpp."</td>
	<td>".$stok_barang."</td>
	</tr>";

}  //end while

 ?>



 </tbody>
</table>