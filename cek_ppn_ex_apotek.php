<?php session_start();

include 'sanitasi.php';
include 'db.php';

$session_id = session_id();


$query = $db->query("SELECT * FROM tbs_penjualan WHERE session_id = '$session_id' AND no_reg = '' AND  tax != '0' AND lab IS NULL  LIMIT 1 ");
$data = mysqli_num_rows($query);

if ($data > 0) {
      
      $sql = mysqli_fetch_array($query);

      $a = ($sql['jumlah_barang'] * $sql['harga']) - $sql['potongan'];
      $b = $a + $sql['tax'];
      if ($b == $sql['subtotal']) {
       echo 1;// EXCLUDE
      }
      elseif ($b != $sql['subtotal']) {
        echo 2;// INCLUDE
      }

}
else
{
  echo 0;// NON
}

//Untuk Memutuskan Koneksi Ke fbsql_database( )e
mysqli_close($db);   

 ?>

