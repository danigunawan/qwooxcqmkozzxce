<?php 

include 'db.php';
include 'sanitasi.php';

    $kode_barang = stringdoang($_POST['kode_barang']);
 
    //ambil data barang

       $cek_harga_hpp = $db->query("SELECT total_nilai,jumlah_kuantitas FROM hpp_masuk WHERE kode_barang = '$kode_barang' AND sisa != 0 ORDER BY waktu DESC LIMIT 1  ");
       $harga_kel = mysqli_fetch_array($cek_harga_hpp);

        $jumlah_kuantitas = $harga_kel['jumlah_kuantitas'];

        if ($jumlah_kuantitas == 0)
        {
            $jumlah_kuantitas = 1;
        }

        $harga_hpp = $harga_kel['total_nilai'] / $jumlah_kuantitas;

        echo round($harga_hpp);

//Untuk Memutuskan Koneksi Ke Database
mysqli_close($db);   
 ?>