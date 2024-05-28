<?
$reqId = $_GET["reqId"];
$reqNomor = $_GET["reqNomor"];

$this->load->model("KelompokProyekPegawai");
$kelompok_proyek_pegawai = new KelompokProyekPegawai();
$kelompok_proyek_pegawai->selectByParams(array("A.KELOMPOK_PROYEK_PEGAWAI_ID" => $reqId));
$kelompok_proyek_pegawai->firstRow();
?>

<tr id="<?=$reqNomor?>">
	<td style="width:10%"><?=$kelompok_proyek_pegawai->getField("PEGAWAI_ID")?></td>
    <td style="width:30%"><?=$kelompok_proyek_pegawai->getField("NAMA_PEGAWAI")?></td>
    <td style="width:30%"><?=$kelompok_proyek_pegawai->getField("NAMA_JABATAN_PROYEK")?></td>
    <td align="center">
    	<!--
    	<input type="hidden" id="reqKelompokShiftPegawaiId<?=$reqNomor?>" name="reqKelompokShiftPegawaiId[]" value="">
    	<input type="hidden" name="reqPegawaiId[]" value="<?=$kelompok_proyek_pegawai->getField("PEGAWAI_ID")?>">
        <input type="hidden" name="reqNama[]" value="<?=$kelompok_proyek_pegawai->getField("NAMA_PEGAWAI")?>">
        -->
    	<input class="btn btn-sm btn-danger" type="button" onClick="$('#<?=$reqNomor?>').remove();" value="Hapus" />        
    </td>
</tr>