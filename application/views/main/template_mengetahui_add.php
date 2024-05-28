    <!-- EASYUI -->
   
 <tr>
    <td><input type="text" id="reqUrut" class="easyui-validatebox textbox form-control" required name="reqUrut[]" id="reqUrut"  data-options="required:true" style="width:50%" /></td>
    <td>
        <input type="text" id="reqSatkerId" class="easyui-combotree" name="reqSatkerId[]" required data-options="width:'500'
        , panelHeight:'120'
        , valueField:'id'
        , textField:'text'
        , url:'web/satuan_kerja_json/combotreesatker/'
        , prompt:'Tentukan Untuk...'," value=""
        />
    </td>
    <td style="width: 5%">
        <span style='background-color: red; padding: 6px; border-radius: 5px;top: 50%;position: relative;'><a onclick='' class='btn-remove'><i class='fa fa-trash fa-lg' style='color: white;' aria-hidden='true'></i></a></span>
    </td>
</tr>

<link rel="stylesheet" type="text/css" href="lib/easyui/themes/default/easyui.css">
<script type="text/javascript" src="lib/easyui/jquery.easyui.min.js"></script>
<script type="text/javascript">
    
$('#reqUrut').bind('keyup paste', function(){
   var numeric = $(this).val().replace(/\D/g, '');
   $(this).val(numeric);
});
</script>

