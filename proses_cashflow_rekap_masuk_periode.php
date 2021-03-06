<?php include 'session_login.php';
/* Database connection start */
include 'sanitasi.php';
include 'db.php';

 $kas = stringdoang($_POST['kas_rekap']);
 $dari_tanggal = stringdoang($_POST['dari_tanggal']);
 $sampai_tanggal = stringdoang($_POST['sampai_tanggal']);

$requestData= $_REQUEST;

$columns = array( 
// datatable column index  => database column name

	0=>'tanggal', 
	1=>'dari_akun',
	2=>'ke_akun',
	3=>'total',
	4=>'id'

);
// getting total number records without any search
$sql = "SELECT SUM(js.debit) AS masuk,js.jenis_transaksi,js.id,da.nama_daftar_akun,DATE(js.waktu_jurnal) AS tanggal,js.no_faktur";
$sql.=" FROM jurnal_trans js LEFT JOIN daftar_akun da ON js.kode_akun_jurnal = da.kode_daftar_akun";
$sql.=" WHERE DATE(js.waktu_jurnal) >= '$dari_tanggal' AND DATE(js.waktu_jurnal) <= '$sampai_tanggal' AND js.kode_akun_jurnal = '$kas' AND js.debit != '0' AND js.jenis_transaksi != 'Kas Mutasi' GROUP BY DATE(js.waktu_jurnal)";
$sql.=" ";


$query = mysqli_query($conn, $sql) or die("eror 1");
$totalData = mysqli_num_rows($query);
$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.


if( !empty($requestData['search']['value']) ) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
$sql = "SELECT SUM(js.debit) AS masuk,js.jenis_transaksi,js.id,da.nama_daftar_akun,DATE(js.waktu_jurnal) AS tanggal, js.no_faktur";
$sql.=" FROM jurnal_trans js LEFT JOIN daftar_akun da ON js.kode_akun_jurnal = da.kode_daftar_akun";
$sql.=" WHERE DATE(js.waktu_jurnal) >= '$dari_tanggal' AND DATE(js.waktu_jurnal) <= '$sampai_tanggal' AND js.kode_akun_jurnal = '$kas' AND js.debit != '0' AND js.jenis_transaksi != 'Kas Mutasi'";

	$sql.=" AND ( js.jenis_transaksi LIKE '".$requestData['search']['value']."%'";
	$sql.=" OR da.nama_daftar_akun LIKE '".$requestData['search']['value']."%' ) GROUP BY DATE(js.waktu_jurnal)";


}


$query=mysqli_query($conn, $sql) or die("eror 2");
$totalFiltered = mysqli_num_rows($query); // when there is a search parameter then we have to modify total number filtered rows as per search result. 

$sql.=" ORDER BY js.id ".$requestData['order'][0]['dir']."  LIMIT ".$requestData['start']." ,".$requestData['length']."";

/* $requestData['order'][0]['column'] contains colmun index, $requestData['order'][0]['dir'] contains order such as asc/desc  */	
$query=mysqli_query($conn, $sql) or die("eror 3");


$data = array();


while( $row=mysqli_fetch_array($query) ) {

	$nestedData=array(); 


	$select = $db->query("SELECT da.nama_daftar_akun,js.kode_akun_jurnal,js.waktu_jurnal,js.kode_akun_jurnal FROM jurnal_trans js LEFT JOIN daftar_akun da ON js.kode_akun_jurnal = da.kode_daftar_akun WHERE DATE(js.waktu_jurnal) = '$row[tanggal]' AND js.kredit != '0' AND js.kode_akun_jurnal != '$kas' GROUP BY js.kode_akun_jurnal ");
	$out = mysqli_fetch_array($select);

	$select_setting_akun = $db->query("SELECT sa.total_penjualan, da.nama_daftar_akun FROM setting_akun sa INNER JOIN daftar_akun da ON sa.total_penjualan = da.kode_daftar_akun");
	$ambil_setting = mysqli_fetch_array($select_setting_akun);

	$nestedData[] = $row["tanggal"];

	if ($row['jenis_transaksi'] == 'Penjualan') {
			$nestedData[] = $ambil_setting["nama_daftar_akun"];
	}
	elseif ($row['jenis_transaksi'] == 'Kas Masuk') {
		$nestedData[] = "Kas Masuk";
	}

	$nestedData[] = $row["nama_daftar_akun"];
	$nestedData[] = rp($row["masuk"]);
$data[] = $nestedData;
}
$json_data = array(
			"draw"            => intval( $requestData['draw'] ),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw. 
			"recordsTotal"    => intval( $totalData ),  // total number of records
			"recordsFiltered" => intval( $totalFiltered ), // total number of records after searching, if there is no searching then totalFiltered = totalData
			"data"            => $data   // total data array
			);

echo json_encode($json_data);  // send data as json format
 ?>

