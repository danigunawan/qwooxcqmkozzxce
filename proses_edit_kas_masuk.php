<?php session_start();


    include 'sanitasi.php';
    include 'db.php';
    
$no_faktur = stringdoang($_POST['no_faktur']);

$tahun_sekarang = date('Y');
$bulan_sekarang = date('m');
$tanggal_sekarang = date('Y-m-d');
$jam_sekarang = date('H:i:s');
$tahun_terakhir = substr($tahun_sekarang, 2);
$waktu = date('Y-m-d H:i:s');

$query5 = $db->query("DELETE FROM detail_kas_masuk WHERE no_faktur = '$no_faktur'");  
$hapus_jurnal = $db->query("DELETE FROM jurnal_trans WHERE no_faktur = '$no_faktur'");

    $perintah = $db->prepare("UPDATE kas_masuk SET no_faktur = ?, keterangan = ?, ke_akun = ?, jumlah = ?, tanggal = ?, jam = ?, user_edit = ?, waktu_edit = ? WHERE no_faktur = ?");

    $perintah->bind_param("sssisssss",
        $no_faktur, $keterangan, $ke_akun , $jumlah, $tanggal, $jam, $user, $waktu,$no_faktur );

    $no_faktur = stringdoang($_POST['no_faktur']);
    $keterangan = stringdoang($_POST['keterangan']);
    $ke_akun = stringdoang($_POST['ke_akun']);
    $tanggal = stringdoang($_POST['tanggal']);
    $jam = stringdoang($_POST['jam']);

    $jumlah = angkadoang($_POST['jumlah']);
    $user = $_SESSION['user_name'];

    $no_faktur = stringdoang($_POST['no_faktur']);

    $perintah->execute();


    $query1 = $db->query("SELECT * FROM tbs_kas_masuk WHERE no_faktur = '$no_faktur'");

    while ($data=mysqli_fetch_array($query1)) {

    $query2 = $db->query("INSERT INTO detail_kas_masuk (no_faktur,keterangan,dari_akun,ke_akun,jumlah,tanggal,jam,user) VALUES ('$no_faktur','$data[keterangan]','$data[dari_akun]','$data[ke_akun]','$data[jumlah]','$data[tanggal]','$data[jam]','$data[user]')");
    
    }
    

//jurnal


    $ke_akun = stringdoang($_POST['ke_akun']);

    $ambil_tbs = $db->query("SELECT * FROM detail_kas_masuk WHERE no_faktur = '$no_faktur'");
    while ($ambil = mysqli_fetch_array($ambil_tbs))
    {



        $insert_jurnal = $db->query("INSERT INTO jurnal_trans (nomor_jurnal,waktu_jurnal,keterangan_jurnal,kode_akun_jurnal,debit,kredit,jenis_transaksi,no_faktur,approved,user_buat,user_edit) VALUES ('".no_jurnal()."', '$tanggal $jam', 'Transaksi Kas Masuk - $ambil[keterangan]','$ambil[ke_akun]', '$ambil[jumlah]', '0', 'Kas Masuk', '$no_faktur','1', '$user', '$user')");


      $insert_jurnal2 = $db->query("INSERT INTO jurnal_trans (nomor_jurnal,waktu_jurnal,keterangan_jurnal,kode_akun_jurnal,debit,kredit,jenis_transaksi,no_faktur,approved,user_buat,user_edit) VALUES ('".no_jurnal()."', '$tanggal $jam', 'Transaksi Kas Masuk - $ambil[keterangan]','$ambil[dari_akun]', '0', '$ambil[jumlah]', 'Kas Masuk', '$no_faktur','1', '$user', '$user')");
       
    }


    $query3 = $db->query("DELETE FROM tbs_kas_masuk WHERE no_faktur = '$no_faktur'");                      
  
//Untuk Memutuskan Koneksi Ke Database
mysqli_close($db);   
    ?>