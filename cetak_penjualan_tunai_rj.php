<?php session_start();


include 'header_cetak.php';
include 'sanitasi.php';
include 'db.php';



$no_reg = stringdoang($_GET['no_reg']);
$potongan = stringdoang($_GET['potongan']);
$biaya_admin = stringdoang($_GET['biaya_admin']);
$total = stringdoang($_GET['total']);
$tunai = stringdoang($_GET['tunai']);
$sisa = stringdoang($_GET['sisa']);
$no_rm = stringdoang($_GET['no_rm']);
$nama_pasien = stringdoang($_GET['nama_pasien']);
$tanggal = date('Y-m-d');

$select_operasi = $db->query("SELECT * FROM hasil_operasi WHERE no_reg = '$no_reg'");

$status_print = $data_setting_printer['status_print'];
  
 ?>


  <?php echo $data_perusahaan['nama_perusahaan']; ?><br>
  <?php echo $data_perusahaan['alamat_perusahaan']; ?><br><br>

===================<br>
  <table>
  <tbody>
    <tr>
<td>No RM </td><td>&nbsp;:&nbsp;</td><td> <?php echo $no_rm;?></td></tr><tr>
<?php if ($nama_pasien == ""): ?> 
  <td>Nama Pasien </td><td>&nbsp;:&nbsp;</td><td> <?php echo $no_rm;?></td>
<?php else: ?>  
  <td>Nama Pasien </td><td>&nbsp;:&nbsp;</td><td> <?php echo $nama_pasien;?></td>
<?php endif ?>
    </tr>
  </tbody>
</table>
===================<br>
 <table>
  <tbody>
    <tr>
  <td>No. REG</td><td>&nbsp;:&nbsp;</td><td> <?php echo $no_reg; ?></td></tr><tr>  

<td>Kasir </td><td>&nbsp;:&nbsp;</td><td> <?php echo $_SESSION['nama']; ?></td>
    </tr>
  </tbody>
</table>
===================<br>
 <table>

  <tbody id="tbody-detail">

<?php 


if ($status_print == 'Detail') {
   $c = new Cache();
  $c->setCache('detail_penjualan');
  $data_detail_penjualan = $c->retrieve($no_reg);

  foreach ($data_detail_penjualan as $data ) {

   echo  '<tr><td width:"50%"> '. $data['nama_barang'] .' </td><td style="padding:3px"> '. $data['jumlah_barang'] .'</td><td style="padding:3px"> '. $data['harga'] .'</td><td style="padding:3px"> '. $data['subtotal'] . ' </td></tr>';
    
  }
}
           while ($out_operasi = mysqli_fetch_array($select_operasi))
           {

              $select_or = $db->query("SELECT id_operasi,nama_operasi FROM operasi");
              $outin = mysqli_fetch_array($select_or);
                 
              echo '<tr>';

              if($out_operasi['operasi'] == $outin['id_operasi'])
              {
                  echo' <td width:"50%"> '. $outin['nama_operasi'] .' </td> ';
              }
                  echo' <td style="padding:3px"> </td> 
                        <td style="padding:3px"></td>
                        <td style="padding:3px"> '. rp($out_operasi['harga_jual']) .'</td> 

              </tr>';

           }
//Untuk Memutuskan Koneksi Ke Database

mysqli_close($db);            
           
           ?> 
           
 </tbody>
</table>
    ===================<br>
 <table>
  <tbody>

  <?php 
  $subtotal_item = $total - $biaya_admin + $potongan;
   ?>
      <tr><td width="50%">Subtotal</td> <td> :</td> <td><?php echo $subtotal_item ?> </tr>
      <tr><td width="50%">Diskon</td> <td> :</td> <td><?php echo rp($potongan);?> </tr>
      <tr><td  width="50%">Biaya Admin</td> <td> :</td> <td> <?php echo rp($biaya_admin);?> </td></tr>
      <tr><td width="50%">Total Penjualan</td> <td> :</td> <td><?php echo rp($total) ?> </tr>
      <tr><td  width="50%">Tunai</td> <td> :</td> <td> <?php echo rp($tunai); ?> </td></tr>
      <tr><td  width="50%">Kembalian</td> <td> :</td> <td> <?php echo rp($sisa); ?>  </td></tr>
            

  </tbody>
</table>
    ===================<br>
    ===================<br>
    Tanggal : <?php echo tanggal($tanggal);?><br>
    ===================<br><br>
    Terima Kasih<br>
    Semoga Lekas Sembuh...<br>
    Telp. <?php echo $data_perusahaan['no_telp']; ?><br>


 <script>
$(document).ready(function(){
 
  window.print();
 });

</script>

<?php 
include 'footer.php';

 ?>
