<?
$nomorpasal= $this->input->get("nomorpasal");
$NamaBagian= $this->input->get("NamaBagian");
$reqJenisNaskah= "8";
// $id= $nomorpasal;
$infonomorpasal= infonomor($nomorpasal, 9);
$infonomorbab= infonomor($nomorpasal, $reqJenisNaskah);
$id= rand();
?>
<table class="table" id="table<?=$id?>">
<tr>
    <td>
        <a onclick="$(this).parent().parent().remove(); $('#table<?=$id?>').remove(); hapuspasal()"><i class="fa fa-trash fa-lg"></i></a>
        <label class="infonomorpasal" <?if($NamaBagian=='BAB'){?>style="display: none;"<?}?>><?=$infonomorpasal?></label>
        <label class="infonomorbap" <?if($NamaBagian=='PASAL'){?>style="display: none;"<?}?>><?=$infonomorbab?></label>
    </td>
    <td>:</td>
    <td>
        <textarea placeholder="isi pesan..." class="froalaeditor" name="reqPasal[]" id="reqPasal<?=$id?>"></textarea>
    </td>
</tr>
</table>
<script type="text/javascript">
    const editorInstance = new FroalaEditor('.froalaeditor', {
        // key: "cJC7bB4B3B2F2C1G1C4zbgolbohA11euB-13prh1txymjB-11uhA-8lI-7C7bmnxE2F2G2F1B10B2D2E6C1A1==",

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
            // ,
            // 'keyup': function (keyupEvent) {
            // // Do something here.
            // // this is the editor instance.
            // console.log(keyupEvent);
            //     setinfovalidasi();
            // }
        },
        tableCellStyles: {
            borderAll: "Border All",
            borderTop: "Border Top",
            borderBottom: "Border Bottom",
            borderLeft: "Border Left",
            borderRight: "Border Right",
        },


        enter: FroalaEditor.ENTER_P,
        placeholderText: null,
        events: {
            initialized: function () {
                const editor = this
                this.el.closest('form').addEventListener('submit', function (e) {
                console.log(editor.$oel.val())
                e.preventDefault()
                })
            }
        }
    })
</script>