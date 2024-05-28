<?
$reqNama	 = $this->input->post("reqNama");
$reqJenis	 = $this->input->post("reqJenis");
$reqSatkerId = $this->input->post("reqSatkerId");
?>

<div class="item"><?=$reqJenis?>:<?=$reqNama?> 
	<i class="fa fa-times-circle" onclick="$(this).parent().remove();"></i>
    <input type="hidden" name="reqPerintahSuratValidasi" value="<?=$reqSatkerId?>">
  
    <input type="hidden" name="reqSatuanKerjaIdPerintah[]" value="<?=$reqSatkerId?>">
</div>