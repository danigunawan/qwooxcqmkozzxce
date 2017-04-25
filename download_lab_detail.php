<?php session_start();
// Fungsi header dengan mengirimkan raw data excel
header("Content-type: application/vnd-ms-excel");
 
// Mendefinisikan nama file ekspor "hasil-export.xls"
header("Content-Disposition: attachment; filename=laboratorium_rekap.xls");

include 'db.php';
include 'sanitasi.php';

$dari_tanggal = stringdoang($_GET['dari_tanggal']);
$sampai_tanggal = stringdoang($_GET['sampai_tanggal']);

$queryperusahaan = $db->query("SELECT nama_perusahaan,alamat_perusahaan,no_telp FROM perusahaan ");
$data_perusahaan = mysqli_fetch_array($queryperusahaan);


 ?>

<div class="container">
<center><h3> <b> LAPORAN LABORATORIUM DETAIL </b></h3><hr></center>
 <div class="row"><!--row1-->

        <div class="col-sm-6">
            <h4> <b> <?php echo $data_perusahaan['nama_perusahaan']; ?> </b> </h4> 
            <p> <?php echo $data_perusahaan['alamat_perusahaan']; ?> </p> 
            <p> No.Telp:<?php echo $data_perusahaan['no_telp']; ?> </p> 
                 
        </div><!--penutup colsm4-->

        <div class="col-sm-4">              
<table>
  <tbody>

      <tr><td  width="20%">PERIODE</td> <td> &nbsp;:&nbsp; </td> <td> <?php echo tanggal($dari_tanggal); ?> s/d <?php echo tanggal($sampai_tanggal); ?></td>
      </tr>
            
  </tbody>
</table>           
                 
        </div><!--penutup colsm4-->


        
    </div><!--penutup row1-->
    <br>
    <br>
    <br>


<table id="tableuser" class="table table-bordered table-sm">
<thead>
          
          <th style="background-color: #4CAF50; color: white;"> No RM</th>
          <th style="background-color: #4CAF50; color: white;"> No REG</th>
          <th style="background-color: #4CAF50; color: white;"> No Faktur</th>
          <th style="background-color: #4CAF50; color: white;"> Pasien</th>
          <th style="background-color: #4CAF50; color: white;">Pemeriksaan</th>
          <th style="background-color: #4CAF50; color: white;">Hasil Pemeriksaan</th>
          <th style="background-color: #4CAF50; color: white;">Nilai Normal Pria</th>
          <th style="background-color: #4CAF50; color: white;">Nilai Normal Wanita</th>
          <th style="background-color: #4CAF50; color: white;"> Dokter</th>
          <th style="background-color: #4CAF50; color: white;"> Analis</th>
          <th style="background-color: #4CAF50; color: white;"> Status Rawat </th>
          <th style="background-color: #4CAF50; color: white;"> Tanggal </th>
                                    
</thead>
            
  <tbody>
    <?php

    $perintah009 = $db->query("SELECT us.nama AS dokter, se.nama AS analis,hl.nama_pasien,hl.no_rm,hl.no_faktur,hl.no_reg,hl.nama_pemeriksaan,
      hl.status,hl.hasil_pemeriksaan,hl.id,hl.status_pasien,hl.tanggal,hl.nama_pemeriksaan,hl.hasil_pemeriksaan,hl.nilai_normal_lk,hl.nilai_normal_pr,hl.model_hitung,hl.satuan_nilai_normal,hl.nilai_normal_lk2,hl.nilai_normal_pr2 FROM hasil_lab hl LEFT JOIN user us ON hl.dokter = us.id  LEFT JOIN user se ON hl.petugas_analis = se.id 
      WHERE hl.tanggal >= '$dari_tanggal' AND hl.tanggal <= '$sampai_tanggal'");


// SAMPAI SINI , LANJUT NANTI
        while ($data11 = mysqli_fetch_array($perintah009))

          {
            $stat = 'Belum Penjualan';
            

            echo "<tr>

                  <td>". $data11['no_rm'] ."</td>
                  <td>". $data11['no_reg'] ."</td>";

                  if($data11['no_faktur'] == '')
                  {
                    echo "<td>". $stat ."</td>";
                  }
                  else
                  {
                    echo "<td>". $data11['no_faktur'] ."</td>";
                  }

            echo "<td>". $data11['nama_pasien'] ."</td>
                  <td>". $data11['nama_pemeriksaan'] ."</td>
                  <td>". $data11['hasil_pemeriksaan'] ."</td>
                  <td>". $data11['nilai_normal_lk'] ."</td>
                  <td>". $data11['nilai_normal_pr'] ."</td>
                  <td>". $data11['dokter'] ."</td>
                  <td>". $data11['analis'] ."</td>
                  <td>". $data11['status_pasien'] ."</td>
                  <td>". $data11['tanggal'] ."</td>

                  </tr>";


                  }

                          //Untuk Memutuskan Koneksi Ke Database
                          
                          mysqli_close($db); 
        
        
            ?>
            </tbody>

      </table>
      <hr>
</div>
</div>
<br>

<div class="col-sm-7">
</div>


<div class="col-sm-2">
</div>


<div class="col-sm-3">
        
 <table>
  <tbody>

     <font class="satu"><b>Petugas <br><br><br> <br><br><br> <font class="satu"><?php echo $_SESSION['nama']; ?></font></b></font>
            
  </tbody>
  </table>


     </div>

 <script>
$(document).ready(function(){
  window.print();
});
</script>

<?php include 'footer.php'; ?>