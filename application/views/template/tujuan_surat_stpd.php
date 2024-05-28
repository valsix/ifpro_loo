<?
$reqNama= $this->input->post("reqNama");
$reqJenis= $this->input->post("reqJenis");
$reqSatkerId= $this->input->post("reqSatkerId");
$reqKelompokId= $this->input->post("reqKelompokId");
?>
<div class="item"><?=$reqJenis?>:<?=$reqNama?> 
	<i class="fa fa-times-circle" onclick="$(this).parent().remove(); $('#itemisi<?=$reqSatkerId?>').empty(); setinfovalidasi(); sethapusuangsaku('<?=$reqKelompokId?>');"></i>
    <input type="hidden" name="reqTujuanSuratValidasi" value="<?=$reqSatkerId?>" />
    <input type="hidden" name="reqTujuanSuratParafValidasi" value="<?=$reqSatkerId?>" />
    <input type="hidden" class="infogroupkelompok" value="<?=$reqKelompokId?>" />
    <?
    if($reqJenis == "PELAKSANA" || $reqJenis == "DISPOSISI")
	{
	?>
    <input type="hidden" name="reqSatuanKerjaIdTujuan[]" value="<?=$reqSatkerId?>">
	<?
	}
    if($reqJenis == "TEMBUSAN")
	{
	?>
    <input type="hidden" name="reqSatuanKerjaIdTembusan[]" value="<?=$reqSatkerId?>">
	<?
	}
    if($reqJenis == "PARAF")
	{
	?>
    <input type="hidden" name="reqSatuanKerjaIdParaf[]" value="<?=$reqSatkerId?>">
	<?
	}
	?>
</div>