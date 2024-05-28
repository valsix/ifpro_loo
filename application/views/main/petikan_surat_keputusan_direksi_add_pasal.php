<?
$nomorpasal= $this->input->get("nomorpasal");
$reqJenisNaskah= "20";
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
        <textarea placeholder="isi pesan..." class="froalaeditor" name="reqPasal[]" id="reqPasal<?=$id?>"></textarea>
    </td>
</tr>
</table>
<script type="text/javascript">
    $(function(){
        $('.froalaeditor').froalaEditor({
            //fontFamilySelection: true,
            //fontSizeSelection: true,
            //paragraphFormatSelection: true,
            
            imageUploadParam: 'image_param',
            
            // Set the image upload URL.
            imageUploadURL: '<?=base_url()?>upload',
            
            // Additional upload params.
            imageUploadParams: {id: 'my_editor'},
            
            // Set request type.
            imageUploadMethod: 'POST',
            
            // Set max image size to 5MB.
            imageMaxSize: 5 * 1024 * 1024,
            
            // Allow to upload PNG and JPG.
            imageAllowedTypes: ['jpeg', 'jpg', 'png'],
            
            events: {
                'image.beforeUpload': function (images) {
                console.log(images)
                // Return false if you want to stop the image upload.
                },
                'image.uploaded': function (response) {
                console.log(response)
                // Image was uploaded to the server.
                },
                'image.inserted': function ($img, response) {
                console.log($img, response)
                // Image was inserted in the editor.
                },
                'image.replaced': function ($img, response) {
                console.log($img, response)
                // Image was replaced in the editor.
                },
                'image.error': function (error, response) {
                console.log(error, response)
                // Bad link.
                // if (error.code == 1) { ... }
                
                // // No link in upload response.
                // else if (error.code == 2) { ... }
                
                // // Error during image upload.
                // else if (error.code == 3) { ... }
                
                // // Parsing response failed.
                // else if (error.code == 4) { ... }
                
                // // Image too text-large.
                // else if (error.code == 5) { ... }
                
                // // Invalid image type.
                // else if (error.code == 6) { ... }
                
                // // Image can be uploaded only to same domain in IE 8 and IE 9.
                // else if (error.code == 7) { ... }
                
                // Response contains the original server response to the request if available.
                }
            }
          
        })
    });
</script>