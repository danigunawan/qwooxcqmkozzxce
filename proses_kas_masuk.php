<?php session_start();

include 'sanitasi.php';
include 'db.php';
    
$session_id = $_POST['session_id'];

$tahun_sekarang = date('Y');
$bulan_sekarang = date('m');
$tanggal_sekarang = date('Y-m-d');
$jam_sekarang = date('H:i:s');
$tahun_terakhir = substr($tahun_sekarang, 2);
$waktu = date('Y-m-d H:i:s');


//mengecek jumlah karakter dari bulan sekarang
$cek_jumlah_bulan = strlen($bulan_sekarang);

//jika jumlah karakter dari bulannya sama dengan 1 maka di tambah 0 di depannya
if ($cek_jumlah_bulan == 1) {
  # code...
  $data_bulan_terakhir = "0".$bulan_sekarang;
 }
 else
 {
  $data_bulan_terakhir = $bulan_sekarang;

 }

//ambil bulan dari tanggal penjualan terakhir

 $bulan_terakhir = $db->query("SELECT MONTH(waktu_input) as bulan FROM kas_masuk ORDER BY id DESC LIMIT 1");
 $v_bulan_terakhir = mysqli_fetch_array($bulan_terakhir);

//ambil nomor  dari penjualan terakhir
$no_terakhir = $db->query("SELECT no_faktur FROM kas_masuk ORDER BY id DESC LIMIT 1");
 $v_no_terakhir = mysqli_fetch_array($no_terakhir);
$ambil_nomor = substr($v_no_terakhir['no_faktur'],0,-8);

/*jika bulan terakhir dari penjualan tidak sama dengan bulan sekarang, 
maka nomor nya kembali mulai dari 1 ,
jika tidak maka nomor terakhir ditambah dengan 1
 
 */
 if ($v_bulan_terakhir['bulan'] != $bulan_sekarang) {
  # code...
$no_faktur = "1/KM/".$data_bulan_terakhir."/".$tahun_terakhir;

 }

 else
 {

$nomor = 1 + $ambil_nomor ;

$no_faktur = $nomor."/KM/".$data_bulan_terakhir."/".$tahun_terakhir;


 }

    $perintah = $db->prepare("INSERT INTO kas_masuk (no_faktur,keterangan,ke_akun,jumlah,tanggal,jam,user) VALUES (?,?,?,?,?,?,?)");

    $perintah->bind_param("sssisss",
        $no_faktur, $keterangan, $ke_akun , $jumlah, $tanggal_sekarang, $jam_sekarang, $user);

    $keterangan = stringdoang($_POST['keterangan']);
    $ke_akun = stringdoang($_POST['ke_akun']);
    $jumlah = angkadoang($_POST['jumlah']);  
    $user = $_SESSION['user_name'];

    $perintah->execute();



    $query1 = $db->query("SELECT * FROM tbs_kas_masuk WHERE session_id = '$session_id'");

    while ($data=mysqli_fetch_array($query1)) {

    $query2 = $db->query("INSERT INTO detail_kas_masuk (no_faktur,keterangan,dari_akun,ke_akun,jumlah,tanggal,jam,user) VALUES ('$no_faktur','$data[keterangan]','$data[dari_akun]','$data[ke_akun]','$data[jumlah]','$data[tanggal]','$data[jam]','$data[user]')");
    
    }


//jurnal

    $ke_akun = stringdoang($_POST['ke_akun']);

    $ambil_tbs = $db->query("SELECT * FROM detail_kas_masuk WHERE no_faktur = '$no_faktur'");
    while ($ambil = mysqli_fetch_array($ambil_tbs))
    {


        $insert_jurnal = $db->query("INSERT INTO jurnal_trans (nomor_jurnal,waktu_jurnal,keterangan_jurnal,kode_akun_jurnal,debit,kredit,jenis_transaksi,no_faktur,approved,user_buat) VALUES ('".no_jurnal()."', '$waktu', 'Transaksi Kas Masuk  - $ambil[keterangan]','$ambil[ke_akun]', '$ambil[jumlah]', '0', 'Kas Masuk', '$no_faktur','1', '$user')");


        $insert_jurnal2 = $db->query("INSERT INTO jurnal_trans (nomor_jurnal,waktu_jurnal,keterangan_jurnal,kode_akun_jurnal,debit,kredit,jenis_transaksi,no_faktur,approved,user_buat) VALUES ('".no_jurnal()."', '$waktu', 'Transaksi Kas Masuk  - $ambil[keterangan]','$ambil[dari_akun]', '0', '$ambil[jumlah]', 'Kas Masuk', '$no_faktur','1', '$user')");
       
    }

    $query3 = $db->query("DELETE FROM tbs_kas_masuk WHERE session_id = '$session_id'");
 

 //Untuk Memutuskan Koneksi Ke Database
 mysqli_close($db);   

    ?>