<?php session_start();
// memasukan file db.php
include 'db.php';
include 'sanitasi.php';

$no_faktur = stringdoang($_POST['no_faktur']); // mengirim data no faktur menggunakan metode POST

 
 // menampilakn hasil penjumlahan subtotal dengan ALIAS total pembelian, pada tabel tbs pembelian
 // berdasarkan no faktur
 $query = $db->query("SELECT SUM(selisih_harga) AS total_selisih_harga FROM tbs_stok_opname WHERE no_faktur = '$no_faktur' ");

 // menyimpan data sementara pada $query
 $data = mysqli_fetch_array($query);

// menampilkan file atau isi dari data total pembelian
 echo $data['total_selisih_harga'];

         //Untuk Memutuskan Koneksi Ke Database

        mysqli_close($db); 
  
  ?>


