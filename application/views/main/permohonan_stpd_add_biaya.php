<?

?>
<tr>
	<td><input class='easyui-validatebox textbox form-control' type='text' name='reqAlokasi[]' id='reqAlokasi' value=''  style=''></td>
	<td style="width: 25%" ><input class='easyui-validatebox textbox form-control' type='text' name='reqPengajuan[]' id='reqPengajuan' value=''  ></td>
	<td style="width: 25%" ><input class='easyui-validatebox textbox form-control txtCal' type='text' name='reqRealisasi[]' id='reqRealisasi' value=''  ></td>
	<td style="width: 5%">
		<span style='background-color: red; padding: 10px; border-radius: 5px;top: 50%;position: relative;'><a onclick='' class='btn-remove'><i class='fa fa-trash fa-lg' style='color: white;' aria-hidden='true'></i></a></span>
	</td>
	<input type='hidden' name='reqTabelId[]' id='reqTabelId' value='<?=$tabelid?>' >
	<input type='hidden' name='reqPengukuranId[]' id='reqPengukuranId' value='<?=$pengukuranid?>' >
	
</tr>

<script type="text/javascript">
	 $('#reqRealisasi,#reqPengajuan').bind('keyup paste', function(){
        this.value = this.value.replace(/[^0-9]/g, '');
    });
</script>