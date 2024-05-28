<?
$reqId = $_GET["reqId"];
$reqPermohonanProyekId = $_GET["reqPermohonanProyekId"];
$reqNomor = $_GET["reqNomor"];


$this->load->model("KelompokProyekPegawai");
$kelompok_proyek_pegawai = new KelompokProyekPegawai();
$kelompok_proyek_pegawai->selectByParams(array("A.PERMOHONAN_PROYEK_ID" => $reqPermohonanProyekId , "A.PEGAWAI_ID" => $reqId));
$kelompok_proyek_pegawai->firstRow();

/*
$this->load->model("PegawaiProyek");
$pegawai_proyek = new PegawaiProyek();
$pegawai_proyek->selectByParamsMonitoring(array("B.PEGAWAI_PROYEK_ID" => $reqId));
$pegawai_proyek->firstRow();
*/
?>

<tr id="<?=$reqNomor?>">
	<td style="width:10%"><?=$kelompok_proyek_pegawai->getField("PEGAWAI_ID")?></td>
    <td style="width:30%"><?=$kelompok_proyek_pegawai->getField("NAMA_PEGAWAI")?></td>
    <td style="width:30%"><?=$kelompok_proyek_pegawai->getField("NAMA_JABATAN_PROYEK")?></td>
    <td style="width:30%">
    	<script>
			$('input[id^="reqTanggalPulang"]').datebox({ validType:'date', required: true});
		</script>
    	<input type="text" class="easyui-datebox" id="reqTanggalPulang<?=$reqNomor?>" name="reqTanggalPulang[]" value="" />
    </td>
    <td>
    	<script>
			$('input[id^="reqKeterangan"]').validatebox({ required: true});
		</script>
    	<input type="text" class="easyui-validatebox" id="reqKeterangan<?=$reqNomor?>" name="reqKeterangan[]" value="" />
    </td>
    <td>
        <input name="reqLampiran[]" type="file" class="maxsize-1024" accept="pdf|jpg|jpeg" id="reqLampiran<?=$reqNomor?>" value="" />
    </td>
    <td align="center">
    	<!--
    	<input type="hidden" id="reqKelompokShiftPegawaiId<?=$reqNomor?>" name="reqKelompokShiftPegawaiId[]" value="">
    	<input type="hidden" name="reqPegawaiId[]" value="<?=$kelompok_proyek_pegawai->getField("PEGAWAI_ID")?>">
        <input type="hidden" name="reqNama[]" value="<?=$kelompok_proyek_pegawai->getField("NAMA_PEGAWAI")?>">
        -->
    	<input class="btn btn-sm btn-danger" type="button" onClick="$('#<?=$reqNomor?>').remove();" value="Hapus" />        
    </td>
</tr>