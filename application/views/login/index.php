<?
$this->load->library("crfs_protect"); $csrf = new crfs_protect('_crfs_login');
// $reqUser= $reqPasswd= "10906004";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Aplikasi E-Office - PT. Indonesia Ferry Property</title>
	<base href="<?=base_url();?>">
    <!-- Bootstrap Core CSS - Uses Bootswatch Flatly Theme: http://bootswatch.com/flatly/ -->
    <link href="lib/valsix/css/bootstrap.min.css" rel="stylesheet">
    <!-- CUSTOMIZE -->
    <link rel="stylesheet" href="css/gaya.css" type="text/css">
    <link rel="shortcut icon" href="images/logo.png" />

</head>
<body onload="requestPermission()" class="body-utama">
    <div class="bg-login"></div>
    <div class="col-sm-12" align="center">
        <div class="container-main-login">
            <div class="login-area-baru">
                <img src="images/logo.png">
                <form action="login/action" method="post" >
                    <span class="judul">E-OFFICE LOGIN</span>
                    <div class="nama-perusahaan">PT. Indonesia Ferry Property</div>
                    <br>
                    <input type="text" name="reqUser" placeholder="Username.." value="<?=$reqUser?>" />
                    <br>
                    <input type="password" name="reqPasswd" placeholder="Password.." value="<?=$reqPasswd?>" />
                    <br>
                    <input type="hidden" id="reqTokenFirebase" name="reqTokenFirebase">
                    <br>
                    <input type="submit" value="Login">
                    <?=$csrf->echoInputField();?>
                </form>
            </div>
        </div>
	</div>
    <div class="footer-login" align="center" style="color:white;">PT. Indonesia Ferry Property</div>
    <!-- jQuery -->
    <script src="lib/valsix/js/jquery.js"></script>
    <!-- Bootstrap Core JavaScript -->
    <script src="lib/valsix/js/bootstrap.min.js"></script>
    <script type="text/javascript">
      var base_url = '<?=base_url()?>';
    </script>
</body>
</html>

<script type="text/javascript">
    $( document ).ready(function() {
        pesann= '<?=$pesan?>'
        if (pesann!=="") 
        {
            alert(pesann);
        }
    });
</script>