<?php include 'session_login.php';
include 'db.php';
include 'sanitasi.php';

// storing  request (ie, get/post) global array to a variable  
$requestData= $_REQUEST;

$columns = array( 
// datatable column index  => database column name
	0 => 'no_faktur', 
	1 => 'ke_akun',
	2 => 'jumlah',
	3 => 'tanggal',
	4 => 'jam',
	5 => 'user',
	6 => 'id'
);

// getting total number records without any search

$sql = "SELECT * ";
$sql.= "FROM kas_masuk ";
$query=mysqli_query($conn, $sql) or die("show_data_kas_masuk.php: get employees");
$totalData = mysqli_num_rows($query);
$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.

$sql = "SELECT *";
$sql.= "FROM kas_masuk ";
$sql.=" WHERE 1=1";
if( !empty($requestData['search']['value']) ) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
	$sql.=" AND ( km.no_faktur LIKE '".$requestData['search']['value']."%' ";    
	$sql.=" OR km.jumlah LIKE '".$requestData['search']['value']."%' ";   
	$sql.=" OR km.tanggal LIKE '".$requestData['search']['value']."%' )";
}

$query=mysqli_query($conn, $sql) or die("show_data_kas_masuk.php: get employees");
$totalFiltered = mysqli_num_rows($query); // when there is a search parameter then we have to modify total number filtered rows as per search result. 
$sql.=" ORDER BY id ".$requestData['order'][0]['dir']." LIMIT ".$requestData['start']." ,".$requestData['length']."";
/* $requestData['order'][0]['column'] contains colmun index, $requestData['order'][0]['dir'] contains order such as asc/desc  */	
$query=mysqli_query($conn, $sql) or die("show_data_kas_masuk.php: get employees");

$data = array();
while( $row=mysqli_fetch_array($query) ) {  // preparing an array
	$nestedData=array(); 

	$nestedData[] = $row["no_faktur"];
	$nestedData[] = $row["ke_akun"];
	$nestedData[] = $row["jumlah"];	
	$nestedData[] = $row["tanggal"];
	$nestedData[] = $row["jam"];
	$nestedData[] = $row["user"];


$pilih_akses_kas_masuk = $db->query("SELECT * FROM otoritas_kas_masuk WHERE id_otoritas = '$_SESSION[otoritas_id]'");
$kas_masuk = mysqli_fetch_array($pilih_akses_kas_masuk);


$nestedData[] = "<td><button class='btn btn-info detail' no_faktur='".$row['no_faktur']."'> <span class='glyphicon glyphicon-th-list'></span> Detail </button> </td>";

if ($kas_masuk['kas_masuk_edit'] == 1) {

	$nestedData[] = "<td><a href='proses_edit_data_kas_masuk.php?no_faktur=". $row['no_faktur']."&nama_daftar_akun=".$row['ke_akun']."' class='btn btn-success'> Edit </a> </td>";
		}

if ($kas_masuk['kas_masuk_hapus'] == 1) {
	$nestedData[] = "<td> <button class=' btn btn-danger btn-hapus' data-id='".$row['id']."' no-faktur='".$row['no_faktur'] ."'> </span> Hapus </button> </td> ";
}

	$nestedData[] = $row["id"];
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