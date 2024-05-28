<?
$nomorpasal= $this->input->get("nomorpasal");
$reqJenisNaskah= "8";
// $id= $nomorpasal;
$infonomorpasal= infonomor($nomorpasal, $reqJenisNaskah);
$id= rand();
?>
<table class="table" id="table<?=$id?>">
<tr>
    <td>
    	<a onclick="$(this).parent().parent().remove(); $('#table<?=$id?>').remove(); hapuspasal()"><i class="fa fa-trash fa-lg"></i></a>
        <label class="infonomorpasal"><?=$infonomorpasal?></label>
    </td>
    <td>:</td>
    <td>
        <textarea placeholder="isi pesan..." class="tinyMCESimple" name="reqPasal[]" id="reqPasal<?=$id?>"></textarea>
    </td>
</tr>
</table>
<script type="text/javascript">
    tinymce.init({
        selector: ".tinyMCESimple",
        //height: 200,
        plugins: [
            "advlist autolink lists link image charmap print preview anchor",
            "searchreplace visualblocks code fullscreen",
            "insertdatetime media table contextmenu paste"
        ],
        toolbar: "nonbreaking undo redo | styleselect | fontsizeselect fontselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image"
    });
</script>