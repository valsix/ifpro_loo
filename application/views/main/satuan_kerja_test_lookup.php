<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="lib/bootstrap-3.3.7/docs/favicon.ico">

    <title>Starter Template for Bootstrap</title>
    <base href="<?=base_url();?>">

    <!-- Bootstrap core CSS -->
    <link href="lib/bootstrap-3.3.7/docs/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <link href="lib/bootstrap-3.3.7/docs/assets/css/ie10-viewport-bug-workaround.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="lib/bootstrap-3.3.7/docs/examples/starter-template/starter-template.css" rel="stylesheet">

    <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
    <!--[if lt IE 9]><script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->
    <script src="lib/bootstrap-3.3.7/docs/assets/js/ie-emulation-modes-warning.js"></script>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>

  <body>

    <?php /*?><nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="#">Project name</a>
        </div>
        <div id="navbar" class="collapse navbar-collapse">
          <ul class="nav navbar-nav">
            <li class="active"><a href="#">Home</a></li>
            <li><a href="#about">About</a></li>
            <li><a href="#contact">Contact</a></li>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </nav>
<?php */?>
    <div class="container">
    	<div class="row">
        	<div class="col-md-6">
            	<div id="seleted-rows">
                
                </div>
            </div>
            <div class="col-md-6">
            	<table>
                    <tr>
                        <td>
                            <input type="checkbox" name="test1" value=""></td>
                        <td>
                            <span>Bapak Budi</span>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <input type="checkbox" name="test2" value="">
                        </td>
                        <td>
                            <span>Ibu Wati</span>
                        </td>
                    </tr>
                </table>
            </div>
        </div>

      <?php /*?><div class="starter-template">
        <h1>Bootstrap starter template</h1>
        <p class="lead">Use this document as a way to quickly start any new project.<br> All you get is this text and a mostly barebones HTML document.</p>
      </div><?php */?>

    </div><!-- /.container -->


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script>window.jQuery || document.write('<script src="../../assets/js/vendor/jquery.min.js"><\/script>')</script>
    <script src="lib/bootstrap-3.3.7/docs/dist/js/bootstrap.min.js"></script>
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="lib/bootstrap-3.3.7/docs/assets/js/ie10-viewport-bug-workaround.js"></script>
    
    <!---->
    <script>
	$(":checkbox").on("click", function(){
		alert("test");
		if($(this).is(":checked")) {
			$(this).closest("td").siblings("td").each(function(){
			  $("#seleted-rows").append($(this).text());
			});
		}
		else {
		 $("#seleted-rows").html("");
		 $(":checkbox:checked").closest("td").siblings("td").each(function(){
		   $("#seleted-rows").append($(this).text());
		 });
		}
	})
	
	/*
	$.each(buildings, function (index, value) {
		$('#dropListBuilding').append($('<option/>', { 
			value: value,
			text : value 
		}));
	});      

	*/
	
	</script>
    
  </body>
</html>
