<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

/* CHECK USER LOGIN 
$CI =& get_instance();
$CI->checkUserLogin();*/

$this->load->model('KelompokShiftPegawai');

$kelompok_shift_pegawai = new KelompokShiftPegawai();

$reqId = $this->input->get("reqId");

$kelompok_shift_pegawai->selectByParams(array("C.PERMOHONAN_SHIFT_ID" => $reqId), -1, -1, "", " ORDER BY C.NAMA, B.NAMA ");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Untitled Document</title>

<script type="text/javascript" src="<?=base_url()?>js/jquery-1.9.1.js"></script>

<link rel="stylesheet" type="text/css" href="<?=base_url()?>css/gaya.css">

<link rel="stylesheet" type="text/css" href="<?=base_url()?>lib/easyui/themes/default/easyui.css">
<script type="text/javascript" src="<?=base_url()?>lib/easyui/jquery.easyui.min.js"></script>
<script type="text/javascript" src="<?=base_url()?>lib/easyui/kalender-easyui.js"></script>
<script type="text/javascript" src="<?=base_url()?>lib/easyui/globalfunction.js"></script>

<!-- BOOTSTRAP CORE -->
<link href="<?=base_url()?>lib/startbootstrap-sb-admin-2-1.0.7/bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="<?=base_url()?>lib/startbootstrap-sb-admin-2-1.0.7/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>


<style>
#iframeModal-upload{
	*border:1px solid red;
	height:100% !important;
}
body.modal-open{
	*border:5px solid cyan;
}
iframe.embed-responsive-item.tmp-modal-content{
	*height:700px !important;
}

#iframeModal-upload .modal-dialog.modal-lg{
	*border:2px solid #F60; 
	height:90% !important;
}
#iframeModal-upload .modal-content{
	*border:2px solid #9C3;
}

.modal-backdrop{
	*border:3px solid #FFF;
	height:100%;
	width:100%;
	position:absolute;
	z-index:999;
	background:url(<?=base_url()?>images/bg-popup2.png) top repeat-x;
}
</style>

</head>

<body class="bg-kanan-full">
	<div id="judul-popup">Kelompok Pegawai</div>
	<div id="konten" align="center">
      <table class="table">
      <thead>
          <tr>
              <th style="text-align:center">NIP</th>
              <th style="text-align:center">Nama</th>
              <th style="text-align:center">Jabatan</th>
              <th style="text-align:center">Departemen</th>
          </tr> 
      </thead>
      <tbody id="tbData">
      <?
      $i = 0;
	  $nama_kelompok = "";
      while($kelompok_shift_pegawai->nextRow())
      {
		  if($nama_kelompok == $kelompok_shift_pegawai->getField("NAMA_KELOMPOK_SHIFT"))
		  {}
		  else
		  {
      ?>
      	<tr>
        	<th colspan="4"><?=$kelompok_shift_pegawai->getField("NAMA_KELOMPOK_SHIFT")?></th>
        </tr>
      <?
		  }
	  ?>
          <tr id="<?=$i?>">
              <td style="width:10%"><?=$kelompok_shift_pegawai->getField("PEGAWAI_ID")?></td>
              <td style="width:30%"><?=$kelompok_shift_pegawai->getField("NAMA_PEGAWAI")?></td>
              <td style="width:30%"><?=$kelompok_shift_pegawai->getField("JABATAN")?></td>
              <td style="width:25%"><?=$kelompok_shift_pegawai->getField("DEPARTEMEN")?></td>
          </tr>                    
      <?
          $i++;
		  $nama_kelompok = $kelompok_shift_pegawai->getField("NAMA_KELOMPOK_SHIFT");
      }
      ?>
      </tbody>
      </table>
    </div>
</body>
</html>