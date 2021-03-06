<?php  include_once 'session_login.php';
include 'db.php';
include_once 'sanitasi.php';

$tahun_sekarang = date('Y');
$bulan_sekarang = date('m');
$tanggal_sekarang = date('Y-m-d');
$jam_sekarang = date('H:i:s');
$tahun_terakhir = substr($tahun_sekarang, 2);
$waktu = date('Y-m-d H:i:s');

try {
    // First of all, let's begin a transaction
$db->begin_transaction();
    // A set of queries; if one fails, an exception should be thrown

$no_faktur = stringdoang($_POST['no_faktur']);
$no_reg = stringdoang($_POST['no_reg']);
$total = angkadoang($_POST['total']);
$potongan = angkadoang($_POST['potongan']);
$biaya_admin = angkadoang($_POST['biaya_admin']);


// menampilakn hasil penjumlah subtotal ALIAS total penjualan dari tabel tbs_penjualan berdasarkan data no faktur
 $query111 = $db->query("SELECT SUM(subtotal) AS total_penjualan FROM tbs_penjualan WHERE no_reg = '$no_reg' AND no_faktur = '$no_faktur'");
 $data111 = mysqli_fetch_array($query111);
 $total111 = $data111['total_penjualan'];


// menampilakn hasil penjumlah subtotal ALIAS total penjualan dari tabel tbs_penjualan berdasarkan data no faktur
 $query222 = $db->query("SELECT SUM(harga_jual) AS harga_jual FROM tbs_operasi WHERE no_reg = '$no_reg' ");
 $data222 = mysqli_fetch_array($query222);
 $total222 = $data222['harga_jual'];

 $total_sum = ($total111 + $total222);


 $total_tbs = ($total_sum - $potongan) + $biaya_admin;

if ($total != $total_tbs) {
    echo 1;
  }
  else{
    

echo $no_faktur = stringdoang($_POST['no_faktur']);

$no_rm = stringdoang($_POST['no_rm']);
$ber_stok = stringdoang($_POST['ber_stok']);
$tanggal_jt = tanggal_mysql($_POST['tanggal_jt']);
$tanggal = tanggal_mysql($_POST['tanggal']);

$penyesuaian_tanggal = stringdoang($_POST['penyesuaian_tanggal']);


$akses_registrasi = $db->query("SELECT tanggal_masuk FROM otoritas_registrasi WHERE id_otoritas = '$_SESSION[otoritas_id]' ");
$data_akses = mysqli_fetch_array($akses_registrasi);

if ($data_akses['tanggal_masuk'] > 0) {

$tanggal_masuk = tanggal_mysql($_POST['tanggal_masuk']); 

}else{

$tanggal_masuk = $tanggal; 

}



$nama_petugas = stringdoang($_SESSION['nama']);
$kode_gudang = stringdoang($_POST['kode_gudang']);
$ppn_input = stringdoang($_POST['ppn_input']);
$penjamin = stringdoang($_POST['penjamin']);
$nama_pasien = stringdoang($_POST['nama_pasien']);

    $petugas_kasir = stringdoang($_POST['sales']);
    $petugas_paramedik = stringdoang($_POST['petugas_paramedik']);
    $petugas_farmasi = stringdoang($_POST['petugas_farmasi']);
    $petugas_lain = stringdoang($_POST['petugas_lain']);
    $dokter = stringdoang($_POST['dokter']);

$keterangan = stringdoang($_POST['keterangan']);
$total2 = angkadoang($_POST['total2']);
$tax = angkadoang($_POST['tax']);
$sisa_pembayaran = angkadoang($_POST['sisa_pembayaran']);
$sisa_kredit = angkadoang($_POST['kredit']);
$sisa = angkadoang($_POST['sisa']);
$cara_bayar = stringdoang($_POST['cara_bayar']);
$pembayaran = angkadoang($_POST['pembayaran']);
$bed = stringdoang($_POST['bed']);
$group_bed = stringdoang($_POST['group_bed']);

$waktu_edit = $tanggal." ".$jam_sekarang;

$no_jurnal = no_jurnal();


    $select_kode_pelanggan = $db_pasien->query("SELECT nama_pelanggan FROM pelanggan WHERE kode_pelanggan = '$no_rm'");
    $ambil_kode_pelanggan = mysqli_fetch_array($select_kode_pelanggan);

    $delete_lap_fee = $db->query("DELETE FROM laporan_fee_faktur WHERE no_reg = '$no_reg' ");


    $delete_jurnal = $db->query("DELETE FROM jurnal_trans WHERE no_faktur = '$no_faktur' ");

    // petugas kasir
    $fee_kasir = $db->query("SELECT * FROM fee_faktur WHERE nama_petugas = '$petugas_kasir' ");
    $data_fee_kasir = mysqli_fetch_array($fee_kasir);           
    $nominal_kasir = $data_fee_kasir['jumlah_uang'];
    $prosentase_kasir = $data_fee_kasir['jumlah_prosentase'];

    if ($nominal_kasir != 0) {
      

      $perintah01 = $db->query("INSERT INTO laporan_fee_faktur (nama_petugas, no_faktur, jumlah_fee, tanggal, jam, status_bayar, no_rm, no_reg) VALUES ('$data_fee_kasir[nama_petugas]', '$no_faktur', '$nominal_kasir', '$tanggal', '$jam_sekarang', '', '$no_rm', '$no_reg')");

    }

    elseif ($prosentase_kasir != 0) {


     
      $fee_prosentase = $prosentase_kasir * $total / 100;
      
      $perintah01 = $db->query("INSERT INTO laporan_fee_faktur (nama_petugas, no_faktur, jumlah_fee, tanggal, jam, no_rm, no_reg) VALUES ('$data_fee_kasir[nama_petugas]', '$no_faktur', '$fee_prosentase', '$tanggal', '$jam_sekarang', '$no_rm', '$no_reg')");
      
    }
    
    // petugas paramedik
    $fee_paramedik = $db->query("SELECT * FROM fee_faktur WHERE nama_petugas = '$petugas_paramedik' ");
    $data_fee_paramedik = mysqli_fetch_array($fee_paramedik);
    $nominal_paramedik = $data_fee_paramedik['jumlah_uang'];
    $prosentase_paramedik = $data_fee_paramedik['jumlah_prosentase'];

    if ($nominal_paramedik != 0) {
      
      $perintah01 = $db->query("INSERT INTO laporan_fee_faktur (nama_petugas, no_faktur, jumlah_fee, tanggal, jam, status_bayar, no_rm, no_reg) VALUES ('$data_fee_paramedik[nama_petugas]', '$no_faktur', '$nominal_paramedik', '$tanggal', '$jam_sekarang', '', '$no_rm', '$no_reg')");

    }

    elseif ($prosentase_paramedik != 0) {


     
      $fee_prosentase = $prosentase_paramedik * $total / 100;
      
      $perintah01 = $db->query("INSERT INTO laporan_fee_faktur (nama_petugas, no_faktur, jumlah_fee, tanggal, jam, no_rm, no_reg) VALUES ('$data_fee_paramedik[nama_petugas]', '$no_faktur', '$fee_prosentase', '$tanggal', '$jam_sekarang', '$no_rm', '$no_reg')");
      
    }

    // petugas farmasi
    $fee_farmasi = $db->query("SELECT * FROM fee_faktur WHERE nama_petugas = '$petugas_farmasi'");
    $data_fee_farmasi = mysqli_fetch_array($fee_farmasi);
    $nominal_farmasi = $data_fee_farmasi['jumlah_uang'];
    $prosetase_farmasi = $data_fee_farmasi['jumlah_prosentase'];

    if ($nominal_farmasi != 0) {
      
      $perintah01 = $db->query("INSERT INTO laporan_fee_faktur (nama_petugas, no_faktur, jumlah_fee, tanggal, jam, status_bayar, no_reg, no_rm) VALUES ('$data_fee_farmasi[nama_petugas]', '$no_faktur', '$nominal_farmasi', '$tanggal', '$jam_sekarang', '', '$no_reg', '$no_rm')");

    }

    elseif ($prosetase_farmasi != 0) {
    

     
      $fee_prosentase = $prosetase_farmasi * $total / 100;
      
      $perintah01 = $db->query("INSERT INTO laporan_fee_faktur (nama_petugas, no_faktur, jumlah_fee, tanggal, jam, no_reg, no_rm) VALUES ('$data_fee_farmasi[nama_petugas]', '$no_faktur', '$fee_prosentase', '$tanggal', '$jam_sekarang', '$no_reg', '$no_rm')");
      
    }
    
    // petugas lain
    $fee_lain = $db->query("SELECT * FROM fee_faktur WHERE nama_petugas = '$petugas_lain'");
    $data_fee_lain = mysqli_fetch_array($fee_lain);
    $nominal_lain = $data_fee_lain['jumlah_uang'];
    $prosentase_lain = $data_fee_lain['jumlah_prosentase'];

    if ($nominal_lain != 0) {
      
      $fee_lain = $db->query("INSERT INTO laporan_fee_faktur (nama_petugas, no_faktur, jumlah_fee, tanggal, jam, no_reg, no_rm) VALUES ('$data_fee_lain[nama_petugas]', '$no_faktur', '$nominal_lain', '$tanggal', '$jam_sekarang', '$no_reg', '$no_rm')");

    }

    elseif ($prosentase_lain != 0) {


     
      $fee_prosentase = $prosentase_lain * $total / 100;
      
      $fee_lain = $db->query("INSERT INTO laporan_fee_faktur (nama_petugas, no_faktur, jumlah_fee, tanggal, jam, no_reg, no_rm) VALUES ('$data_fee_lain[nama_petugas]', '$no_faktur', '$fee_prosentase', '$tanggal', '$jam_sekarang', '$no_reg', '$no_rm')");
      
    }

    
    //dokter
    $fee_dokter = $db->query("SELECT * FROM fee_faktur WHERE nama_petugas = '$dokter'");
    $data_fee_dokter = mysqli_fetch_array($fee_dokter);
    $nominal_dokter = $data_fee_dokter['jumlah_uang'];
    $prosentase_dokter = $data_fee_dokter['jumlah_prosentase'];


    if ($nominal_dokter != 0) {
      
      $perintah01 = $db->query("INSERT INTO laporan_fee_faktur (nama_petugas, no_faktur, jumlah_fee, tanggal, jam, status_bayar, no_reg, no_rm) VALUES ('$data_fee_dokter[nama_petugas]', '$no_faktur', '$nominal_dokter', '$tanggal', '$jam_sekarang', '', '$no_reg', '$no_rm')");

    }

    elseif ($prosentase_dokter != 0) {


     
      $fee_prosentase = $prosentase_dokter * $total / 100;
      
      $perintah01 = $db->query("INSERT INTO laporan_fee_faktur (nama_petugas, no_faktur, jumlah_fee, tanggal, jam, no_reg, no_rm) VALUES ('$data_fee_dokter[nama_petugas]', '$no_faktur', '$fee_prosentase', '$tanggal', '$jam_sekarang', '$no_reg', '$no_rm')");
      
    }

  $delete_lap_fee_produk = $db->query("DELETE FROM laporan_fee_produk WHERE no_reg = '$no_reg' ");

    // FEE PETUGAS OPERASI
              
    $fee_petugas_operasi = $db->query("SELECT tdp.no_reg,tdp.id_sub_operasi,do.jumlah_persentase,tdp.id_user,tdp.waktu,tp.harga_jual,do.id_detail_operasi,do.nama_detail_operasi,DATE(tdp.waktu) AS tanggal, TIME(tdp.waktu) AS jam  FROM tbs_detail_operasi tdp LEFT JOIN sub_operasi tp ON tdp.id_sub_operasi = tp.id_sub_operasi LEFT JOIN detail_operasi do ON tdp.id_detail_operasi = do.id_detail_operasi WHERE tdp.no_reg = '$no_reg'");
   while  ($data_fee_produk = mysqli_fetch_array($fee_petugas_operasi)){

          $jumlah_fee1 = ($data_fee_produk['jumlah_persentase'] * $data_fee_produk['harga_jual']) / 100;
          $jumlah_fee = round($jumlah_fee1);
    

          $query10 = $db->query("INSERT INTO laporan_fee_produk (nama_petugas, no_faktur, kode_produk, nama_produk, jumlah_fee, tanggal, jam,no_reg,no_rm) VALUES ('$data_fee_produk[id_user]', '$no_faktur', '$data_fee_produk[id_detail_operasi]', '$data_fee_produk[nama_detail_operasi]  - $data_fee_produk[waktu]', '$jumlah_fee', '$data_fee_produk[tanggal]', '$data_fee_produk[waktu]','$no_reg','$no_rm')");

  
    }


    // petugas kasir
              
    $fee_produk_ksir = $db->query("SELECT * FROM tbs_fee_produk WHERE nama_petugas = '$petugas_kasir' AND no_reg = '$no_reg'");
   while  ($data_fee_produk = mysqli_fetch_array($fee_produk_ksir)){



          $query10 = $db->query("INSERT INTO laporan_fee_produk (nama_petugas, no_faktur, kode_produk, nama_produk, jumlah_fee, tanggal, jam,no_reg,no_rm) VALUES ('$data_fee_produk[nama_petugas]', '$no_faktur', '$data_fee_produk[kode_produk]', '$data_fee_produk[nama_produk]', '$data_fee_produk[jumlah_fee]', '$data_fee_produk[tanggal]', '$data_fee_produk[jam]','$no_reg','$no_rm')");


    }
    

// petugas paramedik
       
    $fee_produk_paramedik = $db->query("SELECT * FROM tbs_fee_produk WHERE nama_petugas = '$petugas_paramedik' AND no_reg = '$no_reg'");
   while  ($data_fee_produk = mysqli_fetch_array($fee_produk_paramedik)){



          $query10 = $db->query("INSERT INTO laporan_fee_produk (nama_petugas, no_faktur, kode_produk, nama_produk, jumlah_fee, tanggal, jam,no_reg,no_rm) VALUES ('$data_fee_produk[nama_petugas]', '$no_faktur', '$data_fee_produk[kode_produk]', '$data_fee_produk[nama_produk]', '$data_fee_produk[jumlah_fee]', '$data_fee_produk[tanggal]', '$data_fee_produk[jam]','$no_reg','$no_rm')");


    }

// petugas farmasi
       
    $fee_produk_farmasi = $db->query("SELECT * FROM tbs_fee_produk WHERE nama_petugas = '$petugas_farmasi' AND no_reg = '$no_reg'
AND no_reg = '$no_reg'");
   while  ($data_fee_produk = mysqli_fetch_array($fee_produk_farmasi)){



          $query10 = $db->query("INSERT INTO laporan_fee_produk (nama_petugas, no_faktur, kode_produk, nama_produk, jumlah_fee, tanggal, jam,no_reg,no_rm) VALUES ('$data_fee_produk[nama_petugas]', '$no_faktur', '$data_fee_produk[kode_produk]', '$data_fee_produk[nama_produk]', '$data_fee_produk[jumlah_fee]', '$data_fee_produk[tanggal]', '$data_fee_produk[jam]','$no_reg','$no_rm')");


    }

// petugas lain
       
    $fee_produk_lain = $db->query("SELECT * FROM tbs_fee_produk WHERE nama_petugas = '$petugas_lain' AND no_reg = '$no_reg'
AND no_reg = '$no_reg'");
   while  ($data_fee_produk = mysqli_fetch_array($fee_produk_lain)){



          $query10 = $db->query("INSERT INTO laporan_fee_produk (nama_petugas, no_faktur, kode_produk, nama_produk, jumlah_fee, tanggal, jam,no_reg,no_rm) VALUES ('$data_fee_produk[nama_petugas]', '$no_faktur', '$data_fee_produk[kode_produk]', '$data_fee_produk[nama_produk]', '$data_fee_produk[jumlah_fee]', '$data_fee_produk[tanggal]', '$data_fee_produk[jam]','$no_reg','$no_rm')");


    }

//dokter 
       
    $fee_produk_dokter = $db->query("SELECT * FROM tbs_fee_produk WHERE nama_petugas = '$dokter' AND no_reg = '$no_reg'
AND no_reg = '$no_reg'");
   while  ($data_fee_produk = mysqli_fetch_array($fee_produk_dokter)){



          $query10 = $db->query("INSERT INTO laporan_fee_produk (nama_petugas, no_faktur, kode_produk, nama_produk, jumlah_fee, tanggal, jam,no_reg,no_rm) VALUES ('$data_fee_produk[nama_petugas]', '$no_faktur', '$data_fee_produk[kode_produk]', '$data_fee_produk[nama_produk]', '$data_fee_produk[jumlah_fee]', '$data_fee_produk[tanggal]', '$data_fee_produk[jam]','$no_reg','$no_rm')");


    }


 $z = $db->query("DELETE FROM detail_penjualan WHERE no_reg = '$no_reg' ");

    $query = $db->query("SELECT * FROM tbs_penjualan WHERE  no_reg = '$no_reg'");
    while ($data = mysqli_fetch_array($query))
      {

      $pilih_konversi = $db->query("SELECT  sk.konversi * $data[jumlah_barang] AS jumlah_konversi, $data[subtotal] / ($data[jumlah_barang] * sk.konversi) AS harga_konversi, sk.id_satuan, b.satuan FROM satuan_konversi sk INNER JOIN barang b ON sk.id_produk = b.id  WHERE sk.id_satuan = '$data[satuan]' AND sk.kode_produk = '$data[kode_barang]'");
      $data_konversi = mysqli_fetch_array($pilih_konversi);

      if ($data_konversi['harga_konversi'] != 0 || $data_konversi['harga_konversi'] != "") {
        $harga = $data_konversi['harga_konversi'];
        $jumlah_barang = $data_konversi['jumlah_konversi'];
        $satuan = $data_konversi['satuan'];
      }
      else{
        $harga = $data['harga'];
        $jumlah_barang = $data['jumlah_barang'];
        $satuan = $data['satuan'];
      }
        
    if($penyesuaian_tanggal == 'Ya')
    {
      $tanggal_produk = $tanggal;
    }
    else
    {
            if ($data['tipe_barang'] == 'Bed') { 
              $tanggal_produk = $tanggal_masuk; 
            } 
            else{ 
              $tanggal_produk = $data['tanggal']; 
            } 
    }
        $query2 = "INSERT INTO detail_penjualan (no_faktur,no_rm, no_reg, tanggal, jam, kode_barang, nama_barang, jumlah_barang, asal_satuan,satuan, harga, subtotal, potongan, tax, sisa,tipe_produk,lab,ruangan,waktu) VALUES ('$no_faktur','$no_rm', '$no_reg',
          '$tanggal_produk', '$data[jam]', '$data[kode_barang]',
          '$data[nama_barang]','$jumlah_barang','$satuan','$data[satuan]',
          '$harga','$data[subtotal]','$data[potongan]','$data[tax]',
          '$jumlah_barang','$data[tipe_barang]','$data[lab]','$data[ruangan]','$waktu_edit')";

        if ($db->query($query2) === TRUE) {
        } 

        else {
        echo "Error: " . $query2 . "<br>" . $db->error;
        }

        
      }



    $sisa = angkadoang($_POST['sisa']);
            $pembayaran = stringdoang($_POST['pembayaran']);
            $total = stringdoang($_POST['total']);
            $tunai_i = $pembayaran - $total;


          if ($tunai_i >= 0) 

            {
                $ket_jurnal = "Penjualan Rawat Inap Lunas ".$ambil_kode_pelanggan['nama_pelanggan']." ";
            
             $stmt = $db->prepare("UPDATE penjualan SET apoteker = ?, perawat = ?, petugas_lain = ?, biaya_admin = ?, kode_gudang = ?,kode_pelanggan = ? , total = ?, jam = ?, status = 'Lunas', potongan = ?,  sisa = ?, cara_bayar = ?, tunai = ?, ppn = ?, status_jual_awal = 'Tunai', keterangan = ?, tanggal = ?, no_faktur_jurnal = ?, keterangan_jurnal = ?, petugas_edit = ?, waktu_edit = ?, tanggal_jt = '', kredit = '0', nilai_kredit = '0' WHERE no_faktur = ? AND no_reg = ?") ;
              
    // hubungkan "data" dengan prepared statements
              $stmt->bind_param("sssisisiississsssssss",
                $petugas_farmasi, $petugas_paramedik, $petugas_lain, $biaya_admin, $kode_gudang,$no_rm,$total, $jam_sekarang, $potongan, $sisa_pembayaran, $cara_bayar, $pembayaran, $ppn_input, $keterangan, $tanggal,$no_jurnal,$ket_jurnal, $nama_petugas,$waktu,$no_faktur, $no_reg);
              
    // jalankan query
              $stmt->execute();


                // cek query
            if (!$stmt) 
                  {
                    die('Query Error : '.$db->errno.
                      ' - '.$db->error);
                  }

            else 
                  {
                
                  }

$select_setting_akun = $db->query("SELECT * FROM setting_akun");
$ambil_setting = mysqli_fetch_array($select_setting_akun);

$select = $db->query("SELECT SUM(total_nilai) AS total_hpp FROM hpp_keluar WHERE no_faktur = '$no_faktur'");
$ambil = mysqli_fetch_array($select);
$total_hpp = $ambil['total_hpp'];


$sum_tax_tbs = $db->query("SELECT SUM(tax) AS total_tax FROM tbs_penjualan WHERE no_faktur = '$no_faktur'");
$jumlah_tax = mysqli_fetch_array($sum_tax_tbs);
$total_tax = $jumlah_tax['total_tax'];

    $ppn_input = stringdoang($_POST['ppn_input']);
    $select_kode_pelanggan = $db_pasien->query("SELECT nama_pelanggan FROM pelanggan WHERE kode_pelanggan = '$no_rm'");
    $ambil_kode_pelanggan = mysqli_fetch_array($select_kode_pelanggan);

/*

//PERSEDIAAN    
        $insert_jurnal = $db->query("INSERT INTO jurnal_trans (nomor_jurnal,waktu_jurnal,keterangan_jurnal,kode_akun_jurnal,debit,kredit,jenis_transaksi,no_faktur,approved,user_buat) VALUES ('".no_jurnal()."', '$tanggal $jam_sekarang', 'Penjualan Rawat Inap Tunai - $ambil_kode_pelanggan[nama_pelanggan]', '$ambil_setting[persediaan]', '0', '$total_hpp', 'Penjualan', '$no_faktur','1', '$nama_petugas')");
        

//HPP    
      $insert_jurnal = $db->query("INSERT INTO jurnal_trans (nomor_jurnal,waktu_jurnal,keterangan_jurnal,kode_akun_jurnal,debit,kredit,jenis_transaksi,no_faktur,approved,user_buat) VALUES ('".no_jurnal()."', '$tanggal $jam_sekarang', 'Penjualan Rawat Inap Tunai - $ambil_kode_pelanggan[nama_pelanggan]', '$ambil_setting[hpp_penjualan]', '$total_hpp', '0', 'Penjualan', '$no_faktur','1', '$nama_petugas')");

 //KAS
        $insert_juranl = $db->query("INSERT INTO jurnal_trans (nomor_jurnal,waktu_jurnal,keterangan_jurnal,kode_akun_jurnal,debit,kredit,jenis_transaksi,no_faktur,approved,user_buat) VALUES ('".no_jurnal()."', '$tanggal $jam_sekarang', 'Penjualan Rawat Inap Tunai - $ambil_kode_pelanggan[nama_pelanggan]', '$cara_bayar', '$total', '0', 'Penjualan', '$no_faktur','1', '$nama_petugas')");



if ($ppn_input == "Non") {
    $total_penjualan = $total2 + $biaya_admin;


  //Total Penjualan
        $insert_juranl = $db->query("INSERT INTO jurnal_trans (nomor_jurnal,waktu_jurnal,keterangan_jurnal,kode_akun_jurnal,debit,kredit,jenis_transaksi,no_faktur,approved,user_buat) VALUES ('".no_jurnal()."', '$tanggal $jam_sekarang', 'Penjualan Rawat Inap Tunai - $ambil_kode_pelanggan[nama_pelanggan]', '$ambil_setting[total_penjualan]', '0', '$total_penjualan', 'Penjualan', '$no_faktur','1', '$nama_petugas')");

} 


else if ($ppn_input == "Include") {
//ppn == Include
  $total_penjualan = ($total2 + $biaya_admin) - $total_tax ;
  $pajak = $total_tax;

 //Total Penjualan
        $insert_juranl = $db->query("INSERT INTO jurnal_trans (nomor_jurnal,waktu_jurnal,keterangan_jurnal,kode_akun_jurnal,debit,kredit,jenis_transaksi,no_faktur,approved,user_buat) VALUES ('".no_jurnal()."', '$tanggal $jam_sekarang', 'Penjualan Rawat Inap Tunai - $ambil_kode_pelanggan[nama_pelanggan]', '$ambil_setting[total_penjualan]', '0', '$total_penjualan', 'Penjualan', '$no_faktur','1', '$nama_petugas')");

if ($pajak != "" || $pajak != 0 ) {
  //PAJAK
        $insert_juranl = $db->query("INSERT INTO jurnal_trans (nomor_jurnal,waktu_jurnal,keterangan_jurnal,kode_akun_jurnal,debit,kredit,jenis_transaksi,no_faktur,approved,user_buat) VALUES ('".no_jurnal()."', '$tanggal $jam_sekarang', 'Penjualan Rawat Inap Tunai - $ambil_kode_pelanggan[nama_pelanggan]', '$ambil_setting[pajak_jual]', '0', '$pajak', 'Penjualan', '$no_faktur','1', '$nama_petugas')");
      }
      

  }

else {
  //ppn == Exclude
  $total_penjualan = ($total2 + $biaya_admin) - $total_tax;
  $pajak = $total_tax;

 //Total Penjualan
        $insert_juranl = $db->query("INSERT INTO jurnal_trans (nomor_jurnal,waktu_jurnal,keterangan_jurnal,kode_akun_jurnal,debit,kredit,jenis_transaksi,no_faktur,approved,user_buat) VALUES ('".no_jurnal()."', '$tanggal $jam_sekarang', 'Penjualan Rawat Inap Tunai - $ambil_kode_pelanggan[nama_pelanggan]', '$ambil_setting[total_penjualan]', '0', '$total_penjualan', 'Penjualan', '$no_faktur','1', '$nama_petugas')");


if ($pajak != "" || $pajak != 0) {
//PAJAK
        $insert_juranl = $db->query("INSERT INTO jurnal_trans (nomor_jurnal,waktu_jurnal,keterangan_jurnal,kode_akun_jurnal,debit,kredit,jenis_transaksi,no_faktur,approved,user_buat) VALUES ('".no_jurnal()."', '$tanggal $jam_sekarang', 'Penjualan Rawat Inap Tunai - $ambil_kode_pelanggan[nama_pelanggan]', '$ambil_setting[pajak_jual]', '0', '$pajak', 'Penjualan', '$no_faktur','1', '$nama_petugas')");
}

}


if ($potongan != "" || $potongan != 0 ) {
//POTONGAN
        $insert_juranl = $db->query("INSERT INTO jurnal_trans (nomor_jurnal,waktu_jurnal,keterangan_jurnal,kode_akun_jurnal,debit,kredit,jenis_transaksi,no_faktur,approved,user_buat) VALUES ('".no_jurnal()."', '$tanggal $jam_sekarang', 'Penjualan Rawat Inap Tunai - $ambil_kode_pelanggan[nama_pelanggan]', '$ambil_setting[potongan_jual]', '$potongan', '0', 'Penjualan', '$no_faktur','1', '$nama_petugas')");
}

*/            
              
}



              else if ($tunai_i != 0)
              
            {

          $nilai_piutang = $total - $pembayaran;
          $ket_jurnal = "Penjualan Rawat Inap Piutang ".$ambil_kode_pelanggan['nama_pelanggan']." ";
            
             $stmt = $db->prepare("UPDATE penjualan SET apoteker = ?, perawat = ?, petugas_lain = ?, biaya_admin = ?, kode_gudang = ?,kode_pelanggan = ?, total = ?, jam = ?, status = 'Piutang', potongan = ?, kredit = ?, cara_bayar = ?, tunai = ?, ppn = ?, status_jual_awal = 'Kredit', keterangan = ?, nilai_kredit = ?,tanggal = ?, no_faktur_jurnal = ?, keterangan_jurnal = ?, tanggal_jt = ? WHERE no_faktur = ? AND no_reg = ?") ;
              
      
  // hubungkan "data" dengan prepared statements
              $stmt->bind_param("sssisisiissississssss",
                $petugas_farmasi, $petugas_paramedik, $petugas_lain, $biaya_admin, $kode_gudang,$kode_pelanggan, $total, $jam_sekarang, $potongan, $nilai_piutang, $cara_bayar, $pembayaran, $ppn_input, $keterangan, $nilai_piutang,$tanggal,$no_jurnal,$ket_jurnal,$tanggal_jt, $no_faktur,$no_reg );
              
    // jalankan query
              $stmt->execute();


                // cek query
            if (!$stmt) 
                  {
                    die('Query Error : '.$db->errno.
                      ' - '.$db->error);
                  }

            else 
                  {
                
                  }
              
              
              
$select_setting_akun = $db->query("SELECT * FROM setting_akun");
$ambil_setting = mysqli_fetch_array($select_setting_akun);

$select = $db->query("SELECT SUM(total_nilai) AS total_hpp FROM hpp_keluar WHERE no_faktur = '$no_faktur'");
$ambil = mysqli_fetch_array($select);

$total_hpp = $ambil['total_hpp'];


$sum_tax_tbs = $db->query("SELECT SUM(tax) AS total_tax FROM tbs_penjualan WHERE no_faktur = '$no_faktur'");
$jumlah_tax = mysqli_fetch_array($sum_tax_tbs);
$total_tax = $jumlah_tax['total_tax'];

    $ppn_input = stringdoang($_POST['ppn_input']);
    $select_kode_pelanggan = $db_pasien->query("SELECT nama_pelanggan FROM pelanggan WHERE kode_pelanggan = '$no_rm'");
    $ambil_kode_pelanggan = mysqli_fetch_array($select_kode_pelanggan);



            $pembayaran = stringdoang($_POST['pembayaran']);
            $total = stringdoang($_POST['total']);
            $piutang_1 = $total - $pembayaran;
/*
//PERSEDIAAN    
        $insert_jurnal = $db->query("INSERT INTO jurnal_trans (nomor_jurnal,waktu_jurnal,keterangan_jurnal,kode_akun_jurnal,debit,kredit,jenis_transaksi,no_faktur,approved,user_buat) VALUES ('".no_jurnal()."', '$tanggal $jam_sekarang', 'Penjualan Rawat Inap Piutang - $ambil_kode_pelanggan[nama_pelanggan]', '$ambil_setting[persediaan]', '0', '$total_hpp', 'Penjualan', '$no_faktur','1', '$nama_petugas')");
        

//HPP    
      $insert_jurnal = $db->query("INSERT INTO jurnal_trans (nomor_jurnal,waktu_jurnal,keterangan_jurnal,kode_akun_jurnal,debit,kredit,jenis_transaksi,no_faktur,approved,user_buat) VALUES ('".no_jurnal()."', '$tanggal $jam_sekarang', 'Penjualan Rawat Inap Piutang - $ambil_kode_pelanggan[nama_pelanggan]', '$ambil_setting[hpp_penjualan]', '$total_hpp', '0', 'Penjualan', '$no_faktur','1', '$nama_petugas')");

 //KAS
        $insert_juranl = $db->query("INSERT INTO jurnal_trans (nomor_jurnal,waktu_jurnal,keterangan_jurnal,kode_akun_jurnal,debit,kredit,jenis_transaksi,no_faktur,approved,user_buat) VALUES ('".no_jurnal()."', '$tanggal $jam_sekarang', 'Penjualan Rawat Inap Piutang - $ambil_kode_pelanggan[nama_pelanggan]', '$cara_bayar', '$pembayaran', '0', 'Penjualan', '$no_faktur','1', '$nama_petugas')");

 //PIUTANG
        $insert_juranl = $db->query("INSERT INTO jurnal_trans (nomor_jurnal,waktu_jurnal,keterangan_jurnal,kode_akun_jurnal,debit,kredit,jenis_transaksi,no_faktur,approved,user_buat) VALUES ('".no_jurnal()."', '$tanggal $jam_sekarang', 'Penjualan Rawat Inap Piutang - $ambil_kode_pelanggan[nama_pelanggan]', '$ambil_setting[pembayaran_kredit]', '$piutang_1', '0', 'Penjualan', '$no_faktur','1', '$nama_petugas')");



if ($ppn_input == "Non") {

    $total_penjualan = $total2 + $biaya_admin;

 $ppn_input;
  //Total Penjualan
        $insert_juranl = $db->query("INSERT INTO jurnal_trans (nomor_jurnal,waktu_jurnal,keterangan_jurnal,kode_akun_jurnal,debit,kredit,jenis_transaksi,no_faktur,approved,user_buat) VALUES ('".no_jurnal()."', '$tanggal $jam_sekarang', 'Penjualan Rawat Inap Piutang - $ambil_kode_pelanggan[nama_pelanggan]', '$ambil_setting[total_penjualan]', '0', '$total_penjualan', 'Penjualan', '$no_faktur','1', '$nama_petugas')");

} 


else if ($ppn_input == "Include") {
//ppn == Include
$ppn_input;
  $total_penjualan = ($total2 + $biaya_admin) - $total_tax ;
  $pajak = $total_tax;

 //Total Penjualan
        $insert_juranl = $db->query("INSERT INTO jurnal_trans (nomor_jurnal,waktu_jurnal,keterangan_jurnal,kode_akun_jurnal,debit,kredit,jenis_transaksi,no_faktur,approved,user_buat) VALUES ('".no_jurnal()."', '$tanggal $jam_sekarang', 'Penjualan Rawat Inap Piutang - $ambil_kode_pelanggan[nama_pelanggan]', '$ambil_setting[total_penjualan]', '0', '$total_penjualan', 'Penjualan', '$no_faktur','1', '$nama_petugas')");

if ($pajak != "" || $pajak != 0) {
  //PAJAK
        $insert_juranl = $db->query("INSERT INTO jurnal_trans (nomor_jurnal,waktu_jurnal,keterangan_jurnal,kode_akun_jurnal,debit,kredit,jenis_transaksi,no_faktur,approved,user_buat) VALUES ('".no_jurnal()."', '$tanggal $jam_sekarang', 'Penjualan Rawat Inap Piutang - $ambil_kode_pelanggan[nama_pelanggan]', '$ambil_setting[pajak_jual]', '0', '$pajak', 'Penjualan', '$no_faktur','1', '$nama_petugas')");
}


  }

else {
  //ppn == Exclude
  $total_penjualan = ($total2 + $biaya_admin) - $total_tax ;
  $pajak = $total_tax;
 //Total Penjualan
        $insert_juranl = $db->query("INSERT INTO jurnal_trans (nomor_jurnal,waktu_jurnal,keterangan_jurnal,kode_akun_jurnal,debit,kredit,jenis_transaksi,no_faktur,approved,user_buat) VALUES ('".no_jurnal()."', '$tanggal $jam_sekarang', 'Penjualan Rawat Inap Piutang - $ambil_kode_pelanggan[nama_pelanggan]', '$ambil_setting[total_penjualan]', '0', '$total_penjualan', 'Penjualan', '$no_faktur','1', '$nama_petugas')");


if ($pajak != "" || $pajak != 0) {
//PAJAK
        $insert_juranl = $db->query("INSERT INTO jurnal_trans (nomor_jurnal,waktu_jurnal,keterangan_jurnal,kode_akun_jurnal,debit,kredit,jenis_transaksi,no_faktur,approved,user_buat) VALUES ('".no_jurnal()."', '$tanggal $jam_sekarang', 'Penjualan Rawat Inap Piutang - $ambil_kode_pelanggan[nama_pelanggan]', '$ambil_setting[pajak_jual]', '0', '$pajak', 'Penjualan', '$no_faktur','1', '$nama_petugas')");
}

}


if ($potongan != "" || $potongan != 0 ) {
//POTONGAN
        $insert_juranl = $db->query("INSERT INTO jurnal_trans (nomor_jurnal,waktu_jurnal,keterangan_jurnal,kode_akun_jurnal,debit,kredit,jenis_transaksi,no_faktur,approved,user_buat) VALUES ('".no_jurnal()."', '$tanggal $jam_sekarang', 'Penjualan Rawat Inap Piutang - $ambil_kode_pelanggan[nama_pelanggan]', '$ambil_setting[potongan_jual]', '$potongan', '0', 'Penjualan', '$no_faktur','1', '$nama_petugas')");
}

*/
   
}


    // cek query
if (!$stmt) 
      {
        die('Query Error : '.$db->errno.
          ' - '.$db->error);
      }

else 
      {
    
      }





// IBSERT HASIL OPERASI

          $tbs_opsss = $db->query("DELETE FROM hasil_operasi WHERE no_reg = '$no_reg'");

    $tbs_ops = $db->query("SELECT * FROM tbs_operasi WHERE no_reg = '$no_reg'");
    while ($data_ops = mysqli_fetch_array($tbs_ops))
      {

        $insert_operasi = "INSERT INTO hasil_operasi (sub_operasi,petugas_input, no_reg, harga_jual, operasi, waktu) VALUES ('$data_ops[sub_operasi]','$data_ops[petugas_input]', '$no_reg', '$data_ops[harga_jual]', '$data_ops[operasi]', '$data_ops[waktu]')";

        if ($db->query($insert_operasi) === TRUE) {
        } 

        else {
        echo "Error: " . $insert_operasi . "<br>" . $db->error;
        }

      }

// IBSERT HASIL DETAIL OPERASI

          $tbs_opsddd = $db->query("DELETE FROM hasil_detail_operasi WHERE no_reg = '$no_reg'");

    $detail_ops = $db->query("SELECT * FROM tbs_detail_operasi WHERE no_reg = '$no_reg'");
    while ($data_detail_ops = mysqli_fetch_array($detail_ops))
      {

        $insert_detail_operasi = "INSERT INTO hasil_detail_operasi (id_detail_operasi,id_user, id_sub_operasi, id_operasi, petugas_input, no_reg, waktu, id_tbs_operasi) VALUES ('$data_detail_ops[id_detail_operasi]','$data_detail_ops[id_user]', '$data_detail_ops[id_sub_operasi]', '$data_detail_ops[id_operasi]', '$data_detail_ops[petugas_input]', '$no_reg', '$data_detail_ops[waktu]', '$data_detail_ops[id_tbs_operasi]')";

        if ($db->query($insert_detail_operasi) === TRUE) {
        } 

        else {
        echo "Error: " . $insert_detail_operasi . "<br>" . $db->error;
        }

      }

   $update_registrasi = $db->query("UPDATE registrasi SET status = 'Sudah Pulang', tanggal_masuk = '$tanggal_masuk' WHERE no_reg ='$no_reg'"); 


   // history tbs penjulan 
         $delete_history_tbs_penjualan = $db->query("DELETE FROM history_edit_tbs_penjualan WHERE no_reg = '$no_reg' ");


         $history_edit_tbs_penjualan = "INSERT INTO history_edit_tbs_penjualan (session_id,no_faktur,no_reg,kode_barang,nama_barang,jumlah_barang,satuan,harga,subtotal,potongan,tax,hpp,tipe_barang,dosis,tanggal,jam,lab) SELECT session_id,no_faktur,no_reg,kode_barang,nama_barang,jumlah_barang,satuan,harga,subtotal,potongan,tax,hpp,tipe_barang,dosis,tanggal,jam,lab FROM tbs_penjualan WHERE no_reg = '$no_reg' ";

        if ($db->query($history_edit_tbs_penjualan) === TRUE) {
        } 

        else {
        echo "Error: " . $history_edit_tbs_penjualan . "<br>" . $db->error;
        }
        // end

           // history tbs fee produk 


        $delete_history_tbs_fee_produk = $db->query("DELETE FROM history_edit_tbs_fee_produk WHERE no_reg = '$no_reg' ");


         $history_edit_tbs_fee_produk = "INSERT INTO history_edit_tbs_fee_produk (session_id,nama_petugas,no_faktur,kode_produk,nama_produk,jumlah_fee,tanggal,waktu,jam,no_reg,no_rm) SELECT session_id,nama_petugas,no_faktur,kode_produk,nama_produk,jumlah_fee,tanggal,waktu,jam,no_reg,no_rm FROM tbs_fee_produk WHERE no_reg = '$no_reg' ";

        if ($db->query($history_edit_tbs_fee_produk) === TRUE) {
        } 

        else {
        echo "Error: " . $history_edit_tbs_fee_produk . "<br>" . $db->error;
        }

        // end


      // history tbs_fee_masuk 

 $delete_history_tbs_detail_operasi = $db->query("DELETE FROM history_edit_tbs_detail_operasi WHERE no_reg = '$no_reg' ");

    $history_edit_tbs_detail_operasi = "INSERT INTO history_edit_tbs_detail_operasi (id_detail_operasi,id_user, id_sub_operasi, id_operasi, petugas_input, no_reg, waktu, id_tbs_operasi) SELECT id_detail_operasi,id_user, id_sub_operasi, id_operasi, petugas_input, no_reg, waktu, id_tbs_operasi FROM tbs_detail_operasi WHERE no_reg = '$no_reg'";

        if ($db->query($history_edit_tbs_detail_operasi) === TRUE) {
        } 

        else {
        echo "Error: " . $history_edit_tbs_detail_operasi . "<br>" . $db->error;
        }
 // end


  // history tbs_fee_masuk 
 $delete_history_tbs_operasi = $db->query("DELETE FROM history_edit_tbs_operasi WHERE no_reg = '$no_reg' ");

    $history_edit_tbs_operasi = "INSERT INTO history_edit_tbs_operasi (sub_operasi,petugas_input, no_reg, harga_jual, operasi, waktu) SELECT sub_operasi,petugas_input, no_reg, harga_jual, operasi, waktu FROM tbs_operasi WHERE no_reg = '$no_reg' ";

        if ($db->query($history_edit_tbs_operasi) === TRUE) {
        } 

        else {
        echo "Error: " . $history_edit_tbs_operasi . "<br>" . $db->error;
        }

// end


      
// UPDATE KAMAR
$query = $db->query("UPDATE bed SET sisa_bed = sisa_bed + 1 WHERE nama_kamar = '$bed' AND group_bed = '$group_bed'");
// END UPDATE KAMAR


    $query3 = $db->query("DELETE FROM tbs_penjualan WHERE no_reg = '$no_reg'  ");

    $query30 = $db->query("DELETE  FROM tbs_fee_produk WHERE no_reg = '$no_reg' ");
    $hapus_tbs_operasi = $db->query("DELETE  FROM tbs_operasi WHERE no_reg = '$no_reg'");
    $hapus_tbs_detail_operasi = $db->query("DELETE  FROM tbs_detail_operasi WHERE no_reg = '$no_reg'");

}// braket cek subtotal (di proses)

    // If we arrive here, it means that no exception was thrown
    // i.e. no query has failed, and we can commit the transaction
    $db->commit();
}

catch (Exception $e) {
    // An exception has been thrown
    // We must rollback the transaction
    $db->rollback();
}

//Untuk Memutuskan Koneksi Ke Database
mysqli_close($db);   
    
    ?>