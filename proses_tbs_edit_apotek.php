<?php 
include 'db.php';
include_once 'sanitasi.php';

session_start();
$session_id = session_id();
$tipe = stringdoang($_POST['tipe_barang']);
$penjamin = stringdoang($_POST['penjamin']);
$apoteker = stringdoang($_POST['apoteker']);
$no_rm  = stringdoang($_POST['no_rm']);
$no_faktur  = stringdoang($_POST['no_faktur']);
$petugas = $_SESSION['nama'];
$waktu = date("Y-m-d H:i:s");
$bulan_php = date('m');
$tahun_php = date('Y');


      $kode = stringdoang($_POST['kode_barang']);
      $id_kasir = stringdoang($_POST['id_kasir']);
      $satuan = stringdoang($_POST['satuan']);
      $harga = angkadoang($_POST['harga']);
      $jumlah = angkadoang($_POST['jumlah_barang']);
      $nama = stringdoang($_POST['nama_barang']);
      $user = $_SESSION['nama'];
      $potongan = angkadoang($_POST['potongan']);
      $a = $harga * $jumlah;
      $tahun_sekarang = date('Y');
      $bulan_sekarang = date('m');
      $tanggal_sekarang = date('Y-m-d');
      $jam_sekarang = date('H:i:s');
      $tahun_terakhir = substr($tahun_sekarang, 2);

          if(strpos($potongan, "%") !== false)
          {
              $potongan_jadi = $a * $potongan / 100;
              $potongan_tampil = $potongan_jadi;
          }
          else{

             $potongan_jadi = $potongan;
             $potongan_tampil = $potongan;
          }

    $tax = angkadoang($_POST['tax']);
    $hargaa  = angkadoang($_POST['hargaa']);
    $ppn  = stringdoang($_POST['ppn']);

if ($ppn == 'Exclude')
{

 $a = $harga * $jumlah;

 $x = $a - $potongan_tampil;

   $tax_persen = $x * $tax / 100;

}
elseif ($ppn == 'Include') 
{

          $a = $harga * $jumlah;

            $satu = 1;

              $x = $a - $potongan_tampil;

              $hasil_tax = $satu + ($tax / 100);

              $hasil_tax2 = $x / $hasil_tax;

              $tax_persen = $x - $hasil_tax2;

}
else
{
  $tax_persen = 0;
}


              
        


          $query0 = $db->query("SELECT * FROM tbs_penjualan WHERE kode_barang = '$kode' AND no_faktur = '$no_faktur' AND no_reg = ''");
          $cek    = mysqli_num_rows($query0);
  

if ($ppn == 'Exclude') {
  # code...
              $subtotal1 = $harga * $jumlah;
              $xyz = $subtotal1 - $potongan_jadi;

              $cari_pajak = $xyz * $tax / 100;

              $subtotal = $harga * $jumlah - $potongan_jadi + round($cari_pajak); 


}

else

{

$subtotal = $harga * $jumlah - $potongan_jadi; 

} 

                                  // STARETTO HARGA BELI 1

          if ($cek > 0 )

          {

  
                  $xml = $db->prepare("UPDATE tbs_penjualan SET jumlah_barang = jumlah_barang + ?, subtotal = subtotal + ?, potongan = ? WHERE kode_barang = ? AND no_faktur = ? AND no_reg = ''");

                  $xml->bind_param("iisss",
                      $jumlah, $subtotal, $potongan_tampil, $kode, $no_faktur);

                  $xml->execute();


                if ($tipe == 'Jasa' ){


                }

                else if ($tipe == 'BHP')
                {


                }


                $cek_persen_apoteker1 = $db->query("SELECT * FROM fee_produk WHERE nama_petugas = '$apoteker' AND kode_produk = '$kode'");
                $data_persen_apoteker1 = mysqli_fetch_array($cek_persen_apoteker1);

                if ($data_persen_apoteker1['jumlah_prosentase'] != 0 AND $data_persen_apoteker1['jumlah_uang'] == 0 )
                {

                $hasil_hitung_fee_persen_apoteker_harga1 = $subtotal * $data_persen_apoteker1['jumlah_prosentase'] / 100;
                $query_persen_apoteker3 = $db->query("UPDATE tbs_fee_produk SET jumlah_fee = jumlah_fee + '$hasil_hitung_fee_persen_apoteker_harga1' WHERE nama_petugas = '$apoteker' AND kode_produk = '$kode'");

                }
                // AKHIR PSERSENTASE APOTEKER HARGA 1

                else

                // HITUNGAN NOMINAL APOTEKER HARGA 1
                {

                $hasil_hitung_fee_nominal_apoteker_harga1 = $data_persen_apoteker1['jumlah_uang'] * $jumlah;
                $query_nominal_apoteker3 = $db->query("UPDATE tbs_fee_produk SET jumlah_fee = jumlah_fee + '$hasil_hitung_fee_nominal_apoteker_harga1' WHERE nama_petugas = '$apoteker' AND kode_produk = '$kode'");

                }
                // ENDING NOMINAL APOTEKER HARGA 1

                // MULAI PERSENTASE UNTUK PETUGAS 1
                $cek_persen_petugas_harga1 = $db->query("SELECT * FROM fee_produk WHERE nama_petugas = '$id_kasir' AND kode_produk = '$kode'");
                $data_persen_petugas_harga1 = mysqli_fetch_array($cek_persen_petugas_harga1);
                 if ($data_persen_petugas_harga1['jumlah_prosentase'] != 0 AND $data_persen_petugas_harga1['jumlah_uang'] == 0 )
                {

                $hasil_hitung_fee_persen_petugas_harga1 = $subtotal * $data_persen_petugas_harga1['jumlah_prosentase'] / 100;
                $query_persen_petugas1 = $db->query("UPDATE tbs_fee_produk SET jumlah_fee = jumlah_fee + '$hasil_hitung_fee_persen_petugas_harga1' WHERE nama_petugas = '$id_kasir' AND kode_produk = '$kode'");

                }
                // AKHIR PERSENTASE UNTUK PETUGAS 1

                else

                // START NOMINAL UNTUK PETUGAS 1
                {

                $hasil_hitung_fee_nominal_petugas_harga1 = $data_persen_petugas_harga1['jumlah_uang'] * $jumlah;
                $query_nominal_petugas1 = $db->query("UPDATE tbs_fee_produk SET jumlah_fee = jumlah_fee + '$hasil_hitung_fee_nominal_petugas_harga1' WHERE nama_petugas = '$id_kasir' AND kode_produk = '$kode'");

                }
                // ENDING UNTUK PETUGAS 1

          }//dont touch me 

          else
                         
          {
                         
  
                     
          // PERHITUNGAN UNTUK FEE APOTEKER
          $cek_apoteker = $db->query("SELECT * FROM fee_produk WHERE nama_petugas = '$apoteker' AND kode_produk = '$kode'");
          $cek_fee_apoteker1 = mysqli_num_rows($cek_apoteker);
          $dataui_apoteker = mysqli_fetch_array($cek_apoteker);

          if ($cek_fee_apoteker1 > 0){
          if ($dataui_apoteker['jumlah_prosentase'] != 0 AND $dataui_apoteker['jumlah_uang'] == 0 )

          {  
          $hasil_hitung_fee_persen_apoteker = $subtotal * $dataui_apoteker['jumlah_prosentase'] / 100;

          $insert_apoteker = "INSERT INTO tbs_fee_produk 
          (no_faktur,no_rm,nama_petugas,kode_produk,nama_produk,jumlah_fee,tanggal,jam) VALUES 
          ('$no_faktur','$no_rm','$apoteker','$kode','$nama','$hasil_hitung_fee_persen_apoteker','$tanggal_sekarang','$jam_sekarang')";
          if ($db->query($insert_apoteker) === TRUE) {

            } 
          else 
                {
              echo "Error: " . $insert_apoteker . "<br>" . $db->error;
                }

            }

          else

          {

          $hasil_hitung_fee_nominal_apoteker = $dataui_apoteker['jumlah_uang'] * $jumlah;

          $insert2_apoteker = "INSERT INTO tbs_fee_produk 
          (no_faktur,no_rm,nama_petugas,kode_produk,nama_produk,jumlah_fee,tanggal,jam) VALUES 
          ('$no_faktur','$no_rm','$apoteker','$kode','$nama','$hasil_hitung_fee_nominal_apoteker','$tanggal_sekarang','$jam_sekarang')";
          if ($db->query($insert2_apoteker) === TRUE) 
          {
            
              } 
          else
                {
              echo "Error: " . $insert2_apoteker . "<br>" . $db->error;
                }
            }
          } // penutup if apoteker di harga1 > 0
          // ENDING PERHITUNGAN UNTUK FEE APOTEKER


          // PERHITUNGAN UNTUK FEE PETUGASS
          $cek_petugas = $db->query("SELECT * FROM fee_produk WHERE nama_petugas = '$id_kasir' AND kode_produk = '$kode'");
          $cek_fee_petugas1 = mysqli_num_rows($cek_petugas);
          $dataui_petugas = mysqli_fetch_array($cek_petugas);

          if ($cek_fee_petugas1 > 0) 
          {

          if ($dataui_petugas['jumlah_prosentase'] != 0 AND $dataui_petugas['jumlah_uang'] == 0 )

          {  
          $hasil_hitung_fee_persen_petugas = $subtotal * $dataui_petugas['jumlah_prosentase'] / 100;

          $insert1_petugas = "INSERT INTO tbs_fee_produk 
          (no_faktur,no_rm,nama_petugas,kode_produk,nama_produk,jumlah_fee,tanggal,jam) VALUES 
          ('$no_faktur','$no_rm','$id_kasir','$kode','$nama','$hasil_hitung_fee_persen_petugas','$tanggal_sekarang','$jam_sekarang')";
          if ($db->query($insert1_petugas) === TRUE) 
          {
            
          } 
          else 
                  {
              echo "Error: " . $insert1_petugas . "<br>" . $db->error;
                  }
          }

          else
          {
          $hasil_hitung_fee_nominal_petugas = $dataui_petugas['jumlah_uang'] * $jumlah;

          $insert2_petugas = "INSERT INTO tbs_fee_produk 
          (no_faktur,no_rm,nama_petugas,kode_produk,nama_produk,jumlah_fee,tanggal,jam) VALUES 
          ('$no_faktur','$no_rm','$id_kasir','$kode','$nama','$hasil_hitung_fee_nominal_petugas','$tanggal_sekarang','$jam_sekarang')";
          if ($db->query($insert2_petugas) === TRUE) 
          {
            
            } 
          else 
              {
              echo "Error: " . $insert2_petugas . "<br>" . $db->error;
              }
           }
          } // penutup if petugas di harga 1 > 0
          // ENDING PERHITUNGAN UNTUK FEE PETUGAS                      

          $query6 = " INSERT INTO tbs_penjualan (no_faktur,kode_barang,nama_barang,jumlah_barang,satuan,harga,subtotal,tipe_barang,potongan,tax,tanggal,jam) VALUES ('$no_faktur','$kode','$nama','$jumlah','$satuan','$hargaa','$subtotal','$tipe','$potongan_tampil','$tax_persen','$tanggal_sekarang','$jam_sekarang')";

          if ($db->query($query6) === TRUE)
          { 
                         
          } 
          else 
          {

          echo "Error: " . $query6 . "<br>" . $db->error;

          }

          if ($tipe == 'Jasa' ){


          }

          else if ($tipe == 'BHP'){

          }


          }                     
               

 
 
?>
<?php
  //menampilkan semua data yang ada pada tabel tbs penjualan dalam DB
                $perintah = $db->query("SELECT tp.no_faktur,tp.id,tp.kode_barang,tp.satuan,tp.nama_barang,tp.jumlah_barang,tp.harga,tp.subtotal,tp.potongan,tp.tax,tp.jam,tp.tipe_barang,s.nama FROM tbs_penjualan tp INNER JOIN satuan s ON tp.satuan = s.id WHERE tp.no_faktur = '$no_faktur' ORDER BY tp.id DESC LIMIT 1");

$pilih_akses_tombol = $db->query("SELECT * FROM otoritas_penjualan_apotek WHERE id_otoritas = '$_SESSION[otoritas_id]' ");
$otoritas_tombol = mysqli_fetch_array($pilih_akses_tombol);
                
                //menyimpan data sementara yang ada pada $perintah
                
                $data1 = mysqli_fetch_array($perintah);
                //menampilkan data
                echo "<tr class='tr-kode-". $data1['kode_barang'] ." tr-id-". $data1['id'] ."' data-kode-barang='".$data1['kode_barang']."'>
                <td style='font-size:15px'>". $data1['kode_barang'] ."</td>
                <td style='font-size:15px;'>". $data1['nama_barang'] ."</td>";

                 $kd = $db->query("SELECT f.nama_petugas, u.nama,f.tanggal,f.jam FROM tbs_fee_produk f INNER JOIN user u ON f.nama_petugas = u.id WHERE f.kode_produk = '$data1[kode_barang]' AND f.no_faktur = '$no_faktur' ");
                
                $kdD = $db->query("SELECT f.nama_petugas, u.nama,f.tanggal,f.jam  FROM tbs_fee_produk f INNER JOIN user u ON f.nama_petugas = u.id WHERE f.kode_produk = '$data1[kode_barang]' AND f.no_faktur = '$no_faktur' ");
                    
                $nu = mysqli_fetch_array($kd);

                  if ($nu['nama'] != '')
                  {

                  echo "<td style='font-size:15px;'>";
                   while($nur = mysqli_fetch_array($kdD))
                  {
                    echo $nur['nama']." ,";
                  }
                   echo "</td>";

                  }
                  else
                  {
                    echo "<td></td>";
                  }

$pilih = $db->query("SELECT no_faktur_penjualan FROM detail_retur_penjualan WHERE no_faktur_penjualan = '$data1[no_faktur]' AND kode_barang = '$data1[kode_barang]'");
$row_retur = mysqli_num_rows($pilih);

$pilih = $db->query("SELECT no_faktur_penjualan FROM detail_pembayaran_piutang WHERE no_faktur_penjualan = '$data1[no_faktur]'");
$row_piutang = mysqli_num_rows($pilih);

if ($otoritas_tombol['edit_produk_apotek'] > 0) {

            if ($row_retur > 0 || $row_piutang > 0) {
                  echo"<td style='font-size:15px' align='right' class='edit-jumlah-alert' data-id='".$data1['id']."' data-faktur='".$data1['no_faktur']."'  data-kode='".$data1['kode_barang']."'>". $data1['jumlah_barang'] ."</td>";  
            }
            else
            {
                echo"<td style='font-size:15px' align='right' class='edit-jumlah' data-id='".$data1['id']."'><span id='text-jumlah-".$data1['id']."'>". $data1['jumlah_barang'] ."</span> <input type='hidden' id='input-jumlah-".$data1['id']."' value='".$data1['jumlah_barang']."' class='input_jumlah' data-id='".$data1['id']."' autofocus='' data-kode='".$data1['kode_barang']."' data-tipe='".$data1['tipe_barang']."' data-harga='".$data1['harga']."' data-satuan='".$data1['satuan']."' data-tipe='".$data1['tipe_barang']."' > </td>";
            }

}
else{
          echo"<td style='font-size:15px' align='right' class='tidak_punya_otoritas' data-id='".$data1['id']."'><span id='text-jumlah-".$data1['id']."'>". $data1['jumlah_barang'] ."</span> <input type='hidden' id='input-jumlah-".$data1['id']."' value='".$data1['jumlah_barang']."' class='input_jumlah' data-id='".$data1['id']."' autofocus='' data-kode='".$data1['kode_barang']."' data-tipe='".$data1['tipe_barang']."' data-harga='".$data1['harga']."' data-satuan='".$data1['satuan']."' data-tipe='".$data1['tipe_barang']."' > </td>";
}


                echo"<td style='font-size:15px'>". $data1['nama'] ."</td>
              <td style='font-size:15px' align='right'> <span id='text-harga-".$data1['id']."'>". rp($data1['harga']) ."</span></td>

                <td class='edit-potongan' style='font-size:15px' align='right' data-id=".$data1['id']."><span id='text-potongan-".$data1['id']."'> ".rp($data1["potongan"])." </span> 
                <input type='hidden' id='input-potongan-".$data1['id']."' value='".$data1['potongan']."' class='input_potongan' data-id='".$data1['id']."' autofocus='' data-kode='".$data1['kode_barang']."'> </td>

                <td style='font-size:15px' align='right'><span id='text-tax-".$data1['id']."'>". rp($data1['tax']) ."</span></td>
                <td style='font-size:15px' align='right'><span id='text-subtotal-".$data1['id']."'>". rp($data1['subtotal']) ."</span></td>";

if ($otoritas_tombol['hapus_produk_apotek'] > 0) {

            if ($row_retur > 0 || $row_piutang > 0) {

                echo "<td> <button class='btn btn-danger btn-sm btn-alert-hapus' id='btn-hapus-".$data1['id']."' data-id='".$data1['id']."' data-subtotal='".$data1['subtotal']."' data-faktur='".$data1['no_faktur']."' data-kode='".$data1['kode_barang']."'><span class='glyphicon glyphicon-trash'></span> Hapus </button></td>";   

            }
            else
            {
               echo "<td style='font-size:15px'> <button class='btn btn-danger btn-sm btn-hapus-tbs' id='btn-hapus-id-".$data1['id']."' data-id='". $data1['id'] ."' data-kode-barang='". $data1['kode_barang'] ."' data-barang='". $data1['nama_barang'] ."' data-subtotal='". $data1['subtotal'] ."'>Hapus</button> </td> ";
            }

}
else{
            echo "<td style='font-size:15px; color:red'> Tidak Ada Otoritas </td>";
}
                echo"</tr>";


//Untuk Memutuskan Koneksi Ke Database
mysqli_close($db);   
    ?>
    <script type="text/javascript">
                                 
                                 $(".edit-jumlah").dblclick(function(){

                                    var id = $(this).attr("data-id");

                                    $("#text-jumlah-"+id+"").hide();

                                    $("#input-jumlah-"+id+"").attr("type", "text");

                                 });


                                 $(".input_jumlah").blur(function(){

                                    var id = $(this).attr("data-id");


                                    var jumlah_baru = $(this).val();
                                    if (jumlah_baru == '') {
                                      jumlah_baru = '0';
                                    }
                                    var kode_barang = $(this).attr("data-kode");
                                    var harga = $(this).attr("data-harga");
                                    var jumlah_lama = $("#text-jumlah-"+id+"").text();
                                    var satuan_konversi = $(this).attr("data-satuan");
                                    var tipe = $(this).attr("data-tipe");
                                    var ppn = $("#ppn").val();


                                    var subtotal_lama = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah($("#text-subtotal-"+id+"").text()))));
                                    var potongan = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah($("#text-potongan-"+id+"").text()))));

                                    var tax = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah($("#text-tax-"+id+"").text()))));
                                   
                                    var subtotal = harga * jumlah_baru - potongan;

                                    var subtotal_penjualan = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah($("#total2").val()))));

                                    subtotal_penjualan = subtotal_penjualan - subtotal_lama + subtotal;

                                    var pot_fakt_rp = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah($("#potongan_penjualan").val()))));

                                    var pot_fakt_per = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah($("#potongan_persen").val()))));
                                    var potongaaan = pot_fakt_per;
                                          var pos = potongaaan.search("%");
                                          var potongan_persen = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah(potongaaan))));
                                              potongan_persen = potongan_persen.replace("%","");
                                          potongaaan = subtotal_penjualan * potongan_persen / 100;
                                          $("#potongan_penjualan").val(potongaaan);
                                    
                                          var biaya_admin = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah($("#biaya_adm").val()))));
                                          if (biaya_admin == '')
                                          {
                                            biaya_admin = 0;
                                          }
                                          /*var tax_faktur = $("#tax").val();
                                            if(tax_faktur == '')
                                            {
                                              tax_faktur = 0;
                                            }*/


                                    var sub_akhir = parseInt(subtotal_penjualan,10) - parseInt(potongaaan,10) + parseInt(biaya_admin,10);


                          if (ppn == 'Exclude') {

                                   var subtotal1 = harga * jumlah_baru - potongan;

                                    var subtotal_penjualan = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah($("#total2").val()))));

                                    var subtotal_ex = parseInt(subtotal_lama,10) - parseInt(tax,10);

                                    var cari_tax = (parseInt(tax,10) * 100) / parseInt(subtotal_ex,10);


                                    var cari_tax1 = parseInt(subtotal1,10) * parseInt(cari_tax,10) / 100;

                                    var jumlah_tax = Math.round(cari_tax1);

                                    var subtotal = parseInt(subtotal1,10) + parseInt(jumlah_tax,10);

                                     var subtotal_penjualan = subtotal_penjualan - subtotal_lama + subtotal;
                                    }
                                    else
                                    {

                                   var subtotal1 = harga * jumlah_baru - potongan;

                                    var subtotal_penjualan = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah($("#total2").val()))));

                                      var cari_tax = parseInt(subtotal_lama,10) - parseInt(tax,10);
                                    var cari_tax1 = parseInt(subtotal_lama,10) / parseInt(cari_tax,10);

                                    var tax_ex = cari_tax1.toFixed(2);

                                    var subtotal = subtotal1;
                                    var tax_ex1 = parseInt(subtotal,10) / tax_ex;
                                    var tax_ex2 = parseInt(subtotal,10) - parseInt(Math.round(tax_ex1));
                                    var jumlah_tax = Math.round(tax_ex2);
                                    

                                       var subtotal_penjualan = subtotal_penjualan - subtotal_lama + subtotal;

                                    }    
                                     /*var t_tax = ((parseInt(bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah(sub_akhir,10))))) * parseInt(bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah(tax_faktur)))))) / 100);*/

                         

if (pot_fakt_per == 0) {
      var potongaaan = pot_fakt_rp;

      var potongaaan_per = parseInt(potongaaan,10) / parseInt(subtotal_penjualan,10) * 100;
      var potongaaan = pot_fakt_rp;
  /*
      var hitung_tax = parseInt(subtotal_penjualan,10) - parseInt(pot_fakt_rp,10);
      var tax_bener = parseInt(hitung_tax,10) * parseInt(tax_faktur,10) / 100;*/

      var total_akhir = parseInt(subtotal_penjualan,10) - parseInt(pot_fakt_rp,10) + parseInt(biaya_admin,10);


    }
    else if(pot_fakt_rp == 0)
    {
      var potongaaan = pot_fakt_per;
      var pos = potongaaan.search("%");
      var potongan_persen = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah(potongaaan))));
          potongan_persen = potongan_persen.replace("%","");
      potongaaan = subtotal_penjualan * potongan_persen / 100;
      
      var potongaaan_per = pot_fakt_per;  /*
      var hitung_tax = parseInt(subtotal_penjualan,10) - parseInt(potongaaan,10);
      var tax_bener = parseInt(hitung_tax,10) * parseInt(tax_faktur,10) / 100;*/

     var total_akhir = parseInt(subtotal_penjualan,10) - parseInt(potongaaan,10) + parseInt(biaya_admin,10);

    }
     else if(pot_fakt_rp != 0 && pot_fakt_rp != 0)
    {
      var potongaaan = pot_fakt_per;
      var pos = potongaaan.search("%");
      var potongan_persen = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah(potongaaan))));
          potongan_persen = potongan_persen.replace("%","");
      potongaaan = subtotal_penjualan * potongan_persen / 100;
      
      var potongaaan_per = pot_fakt_per;
      /*
      var hitung_tax = parseInt(subtotal_penjualan,10) - parseInt(potongaaan,10);
      var tax_bener = parseInt(hitung_tax,10) * parseInt(tax_faktur,10) / 100;*/
 
      var total_akhir = parseInt(subtotal_penjualan,10) - parseInt(potongaaan,10) + parseInt(biaya_admin,10);

    
    }


                                        if (jumlah_baru == '0') {
                                          alert("Jumlah Tidak Boleh Kosong atau Nol");
                                          $("#input-jumlah-"+id+"").val(jumlah_lama);
                                          $("#text-jumlah-"+id+"").text(jumlah_lama);
                                          $("#text-jumlah-"+id+"").show();
                                          $("#input-jumlah-"+id+"").attr("type", "hidden");

                                        }
                                        else
                                        {

                                            if (tipe == 'Jasa' || tipe == 'BHP') {


                                                      $("#text-jumlah-"+id+"").show();
                                                        $("#text-jumlah-"+id+"").text(jumlah_baru);

                                                        $("#text-subtotal-"+id+"").text(tandaPemisahTitik(subtotal));
                                                        $("#btn-hapus-id-"+id+"").attr("data-subtotal",subtotal);
                                                        $("#text-tax-"+id+"").text(Math.round(jumlah_tax));
                                                        $("#input-jumlah-"+id+"").attr("type", "hidden"); 
                                                        $("#total2").val(tandaPemisahTitik(subtotal_penjualan));
                                                        $("#potongan_penjualan").val(Math.round(potongaaan));
                                                        $("#total1").val(tandaPemisahTitik(total_akhir));
                                                       /* $("#tax_rp").val(tandaPemisahTitik(Math.round(t_tax)));*/
                                                       $("#pembayaran_penjualan").val('');
                                                       $("#sisa_pembayaran_penjualan").val('');
                                                       $("#kredit").val('');
                                                       
                                                         $.post("update_pesanan_barang_edit_apotek.php",{jumlah_lama:jumlah_lama,tax:tax,id:id,jumlah_baru:jumlah_baru,kode_barang:kode_barang,potongan:potongan,harga:harga,jumlah_tax:jumlah_tax,subtotal:subtotal},function(data){

                                                       


                                                         });  

                                            }
                                            else{

                                              $.post("cek_stok_pesanan_barang.php",{kode_barang:kode_barang,jumlah_baru:jumlah_baru,satuan_konversi:satuan_konversi},function(data){

                                                     if (data < 0) {

                                                     alert ("Jumlah Yang Di Masukan Melebihi Stok !");

                                                      $("#input-jumlah-"+id+"").val(jumlah_lama);
                                                      $("#text-jumlah-"+id+"").text(jumlah_lama);
                                                      $("#text-jumlah-"+id+"").show();
                                                      $("#input-jumlah-"+id+"").attr("type", "hidden");
                                                  
                                                      }

                                                    else{

                                                      $("#text-jumlah-"+id+"").show();
                                                        $("#text-jumlah-"+id+"").text(jumlah_baru);

                                                        $("#text-subtotal-"+id+"").text(tandaPemisahTitik(subtotal));
                                                        $("#btn-hapus-id-"+id+"").attr("data-subtotal",subtotal);
                                                        $("#text-tax-"+id+"").text(Math.round(jumlah_tax));
                                                        $("#input-jumlah-"+id+"").attr("type", "hidden"); 
                                                        $("#total2").val(tandaPemisahTitik(subtotal_penjualan));
                                                        $("#potongan_penjualan").val(Math.round(potongaaan));
                                                        $("#total1").val(tandaPemisahTitik(total_akhir));
                                                        /*$("#tax_rp").val(tandaPemisahTitik(Math.round(t_tax)));*/
                                                       $("#pembayaran_penjualan").val('');
                                                       $("#sisa_pembayaran_penjualan").val('');
                                                       $("#kredit").val('');

                                                         $.post("update_pesanan_barang_edit_apotek.php",{jumlah_lama:jumlah_lama,tax:tax,id:id,jumlah_baru:jumlah_baru,kode_barang:kode_barang,potongan:potongan,harga:harga,jumlah_tax:jumlah_tax,subtotal:subtotal},function(data){

                                                       

                                                        });

                                                      }

                                                 });

                                            }
                                            
                                            }

                                    $("#kode_barang").focus();
                                    
                    });


                             </script>