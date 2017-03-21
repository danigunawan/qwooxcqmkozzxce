<?php include 'session_login.php';

include 'sanitasi.php';
include 'db.php';

$no_faktur = $_POST['no_faktur'];



?>

<div class="container">				
<div class="table-responsive"> 
<table id="tableuser" class="table table-bordered table-sm">
        <thead>
           <th> Nama Pemeriksaan </th>
           <th> Hasil Pemeriksaan </th>
           <th> Nilai Normal Pria </th>
           <th> Nilai Normal Wanita </th>
           <th> Status Rawat </th>
           
            
        </thead>
        
        <tbody>
        <?php

$detail = $db->query("SELECT * FROM hasil_lab WHERE no_faktur = '$no_faktur' GROUP BY id_sub_header");
while($drop_master = mysqli_fetch_array($detail))
{

  $select = $db->query("SELECT id,nama_pemeriksaan FROM setup_hasil WHERE id = '$drop_master[id_sub_header]' AND kategori_index = 'Header'");
  $drop = mysqli_fetch_array($select);
   $face_drop = mysqli_num_rows($select);
  $id_set_up = $drop['nama_pemeriksaan'];
  $id_get = $drop['id'];

  $get_name = $db->query("SELECT nama FROM jasa_lab WHERE id = '$id_set_up' GROUP BY id");
  $get = mysqli_fetch_array($get_name);
  $name_sub_header = $get['nama'];
                //menampilkan data
    
  $show = $db->query("SELECT * FROM hasil_lab WHERE no_faktur = '$no_faktur' AND status = 'Selesai' ");
  $drop_show = mysqli_fetch_array($show);

if($face_drop >= 1)
{

    $hitung_baris = 0;
    echo "
    <tr>";

    if($hitung_baris != 0)
      {
          $name_sub_header = '';
      }
          $hitung_baris++;

          echo "<td><b>".$name_sub_header."</b></td>
                <td><center>-</center></td>
                <td><center>-</center></td>
                <td><center>-</center></td>
                <td><center>-</center></td>

    </tr>";

    $show_one = $db->query("SELECT * FROM hasil_lab WHERE no_faktur = '$no_faktur' AND status = 'Selesai' AND id_sub_header = '$id_get'");
            //menyimpan data sementara yang ada pada $perintah
  
        while ($take = mysqli_fetch_array($show_one))
        {

        echo "<tr>";
        echo "<td>  <li> ". $take['nama_pemeriksaan'] ."</li></td>";
        echo "<td>". $take['hasil_pemeriksaan'] ."</td>";

        $model_hitung = $take['model_hitung']; 
        if($model_hitung == '')
        {
            echo "
            <td>&nbsp; ". '-' ." </td>
            <td>&nbsp; ". '-'." </td>
            ";
        }
        else
        {
          switch ($model_hitung) {
          case "Lebih Kecil Dari":
          echo "<td>&lt;&nbsp; ". $take['nilai_normal_lk']."&nbsp;". $take['satuan_nilai_normal']." </td>
          <td>&lt;&nbsp; ". $take['nilai_normal_pr']."&nbsp;". $take['satuan_nilai_normal']." </td>";
          
          break;
          case "Lebih Kecil Sama Dengan":
          echo "<td>&lt;=&nbsp; ". $take['nilai_normal_lk']."&nbsp;". $take['satuan_nilai_normal']." </td>
          <td>&lt;=&nbsp; ". $take['nilai_normal_pr']."&nbsp;". $take['satuan_nilai_normal']." </td>";

          break;
          case "Lebih Besar Dari":
          echo "<td>&gt;&nbsp; ". $take['nilai_normal_lk']."&nbsp;". $take['satuan_nilai_normal']." </td>
          <td>&gt;&nbsp; ". $take['nilai_normal_pr']."&nbsp;". $take['satuan_nilai_normal']." </td>";

          break;
          case "Lebih Besar Sama Dengan":
          echo "<td>&gt;=&nbsp; ". $take['nilai_normal_lk']."&nbsp;". $take['satuan_nilai_normal']." </td>
          <td>&gt;=&nbsp; ". $take['nilai_normal_pr']."&nbsp;". $take['satuan_nilai_normal']." </td>";

          break;
          case "Antara Sama Dengan":
          echo "<td>". $take['nilai_normal_lk']."&nbsp;-&nbsp; ". $take['nilai_normal_lk2']."&nbsp;". $take['satuan_nilai_normal']." </td>
          <td>". $take['nilai_normal_pr']."&nbsp;-&nbsp; ". $take['nilai_normal_pr2']."&nbsp;". $take['satuan_nilai_normal']."</td>";
                            
          break;
          //Text
          case "Text":
          echo "<td>&nbsp; ". $take['nilai_normal_lk']."&nbsp;". $take['satuan_nilai_normal']." </td>
          <td>&nbsp; ". $take['nilai_normal_pr']."&nbsp;". $take['satuan_nilai_normal']." </td>";
          break;
          //End Text

                } 
          }  

        echo " <td>". $take['status_pasien'] ."</td>
        <tr>";

        } //END WHILE
} //END IF UNTUK DATA LABORATORIUM YANG ADA HEADER / INDUX
}


//start untuk yang sendirian / yang tidak ber HEADER/INDUX
       $show_two = $db->query("SELECT * FROM hasil_lab WHERE no_faktur = '$no_faktur' AND status = 'Selesai' AND id_sub_header = 0");
            //menyimpan data sementara yang ada pada $perintah
  
          while ($drop_two = mysqli_fetch_array($show_two))
          {

            echo "<tr>";
            echo "<td><b> ".$drop_two['nama_pemeriksaan']."</b></td>";
            echo "<td>". $drop_two['hasil_pemeriksaan'] ."</td>";

            $model_hitung = $drop_two['model_hitung']; 
            if($model_hitung == '')
            {
              echo "
              <td>&nbsp; ". '-' ." </td>
              <td>&nbsp; ". '-'." </td>
              ";
            }
            else
            {
            
            switch ($model_hitung) {
            case "Lebih Kecil Dari":
            echo "<td>&lt;&nbsp; ". $drop_two['nilai_normal_lk']."&nbsp;". $drop_two['satuan_nilai_normal']." </td>
            <td>&lt;&nbsp; ". $drop_two['nilai_normal_pr']."&nbsp;". $drop_two['satuan_nilai_normal']." </td>";
            break;
                        
            case "Lebih Kecil Sama Dengan":
            echo "<td>&lt;=&nbsp; ". $drop_two['nilai_normal_lk']."&nbsp;". $drop_two['satuan_nilai_normal']." </td>
            <td>&lt;=&nbsp; ". $drop_two['nilai_normal_pr']."&nbsp;". $drop_two['satuan_nilai_normal']." </td>";
            
            break;
            case "Lebih Besar Dari":
            echo "<td>&gt;&nbsp; ". $drop_two['nilai_normal_lk']."&nbsp;". $drop_two['satuan_nilai_normal']." </td>
            <td>&gt;&nbsp; ". $drop_two['nilai_normal_pr']."&nbsp;". $drop_two['satuan_nilai_normal']." </td>";
                            
            break;
            case "Lebih Besar Sama Dengan":
            echo "<td>&gt;=&nbsp; ". $drop_two['nilai_normal_lk']."&nbsp;". $drop_two['satuan_nilai_normal']." </td>
            <td>&gt;=&nbsp; ". $drop_two['nilai_normal_pr']."&nbsp;". $drop_two['satuan_nilai_normal']." </td>";
                            
            break;
            case "Antara Sama Dengan":
            echo "<td>". $drop_two['nilai_normal_lk']."&nbsp;-&nbsp; ". $drop_two['nilai_normal_lk']."&nbsp;". $drop_two['satuan_nilai_normal']." </td>
            <td>". $drop_two['nilai_normal_pr']."&nbsp;-&nbsp; ". $drop_two['nilai_normal_pr']."&nbsp;". $drop_two['satuan_nilai_normal']." </td>";
            
            break;
            //Text
            case "Text":
            echo "<td>&nbsp; ". $drop_two['nilai_normal_lk']."&nbsp;". $drop_two['satuan_nilai_normal']." </td>
            <td>&nbsp; ". $drop_two['nilai_normal_pr']."&nbsp;". $drop_two['satuan_nilai_normal']." </td>";
            break;
            //End Text
                
                } 
              }  
            echo " 
            <td>". $drop_two['status_pasien'] ."</td>
            <tr>";

          } //END WHILE
//ending untuk yang sendirian / yang tidak ber HEADER/INDUX

mysqli_close($db); 

        ?>
        </tbody>

    </table>
</div>
</div>

					<script>
		
		$(document).ready(function(){
		$('#table').DataTable(
			{"ordering": false});
		});
		</script>