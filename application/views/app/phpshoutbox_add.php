<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

$reqId = $this->input->get("reqId");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Untitled Document</title>
<base href="<?=base_url()?>" />
<link rel="stylesheet" type="text/css" href="css/gaya.css">

<link rel="stylesheet" type="text/css" href="lib/easyui/themes/default/easyui.css">

<!-- BOOTSTRAP CORE -->
<link href="<?=base_url()?>lib/startbootstrap-sb-admin-2-1.0.7/bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="<?=base_url()?>lib/startbootstrap-sb-admin-2-1.0.7/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>

  <style type="text/css">
    #daddy-shoutbox {
      padding: 5px;
      background: #3E5468;
      color: white;
      width: 100%;
      font-family: Arial,Helvetica,sans-serif;
      font-size: 11px;
    }
    .shoutbox-list {
      border-bottom: 1px solid #627C98;
      
      padding: 5px;
      display: none;
    }
    #daddy-shoutbox-list {
      text-align: left;
      margin: 0px auto;
    }
    #daddy-shoutbox-form {
      text-align: left;
      
    }
    .shoutbox-list-time {
      color: #8DA2B4;
    }
    .shoutbox-list-nick {
      margin-left: 5px;
      font-weight: bold;
    }
    .shoutbox-list-message {
      margin-left: 5px;
    }
    
  </style>
  <script type="text/javascript" src="lib/shoutbox2/javascript/jquery.js"></script>
  <script type="text/javascript" src="lib/shoutbox2/javascript/jquery.form.js"></script>
  
</head>

<body class="bg-kanan-full">
	<div id="judul-popup">Chatting</div>
	<div id="konten">
    	<div id="popup-tabel2">
            

          <div id="daddy-shoutbox">
            <div id="daddy-shoutbox-list"></div>
            <br />
            <form id="daddy-shoutbox-form" action="daddy_shoutbox/json/?action=add" method="post"> 
            <input type="hidden" name="nickname" value="Administrator" /> 
            <input type="hidden" name="reqId" value="<?=$reqId?>" readonly /> 
            <input type="hidden" name="reqHalaman" value="0" readonly />
            <input type="hidden" name="reqKode" value="0" readonly />
            Administrator: <input id="btn-input" type="text" name="message" style="width:80%; color:#000 !important" />
            <input type="submit" value="Submit" />
            <span id="daddy-shoutbox-response"></span>
            </form>
          </div>

		  <script type="text/javascript">
                var count = 0;
                var files = 'lib/shoutbox2/';
                var lastTime = 0;
                
                function prepare(response) {
                  var d = new Date();
                  count++;
                  d.setTime(response.time*1000);
                  var mytime = d.getHours()+':'+d.getMinutes()+':'+d.getSeconds();
                  var string = '<div class="shoutbox-list" id="list-'+count+'">'
                      + '<span class="shoutbox-list-time">'+mytime+'</span>'
                      + '<span class="shoutbox-list-nick">'+response.nickname+':</span>'
                      + '<span class="shoutbox-list-message">'+response.message+'</span>'
                      +'</div>';	
                  return string;
                }


                function success(response, status)  { 
                  if(status == 'success') {
                    lastTime = response.time;
                    $('#daddy-shoutbox-response').html('<img src="'+files+'images/accept.png" />');
                    $('#daddy-shoutbox-list').append(prepare(response));
                    $('#btn-input').val('');
                    $('#btn-input').focus();										
                    $('#list-'+count).fadeIn('slow');
                    timeoutID = setTimeout(refresh, 3000);
                  }
                }
                
                function validate(formData, jqForm, options) {
                  for (var i=0; i < formData.length; i++) { 
                      if (!formData[i].value) {
                          alert('Please fill in all the fields'); 
                          $('input[@name='+formData[i].name+']').css('background', 'red');
                          return false; 
                      } 
                  } 
                  $('#daddy-shoutbox-response').html('<img src="'+files+'images/loader.gif" />');
                  clearTimeout(timeoutID);
                }
        
                function refresh() {
                  $.getJSON("daddy_shoutbox/json/?reqId=<?=$reqId?>&action=view&time="+lastTime, function(json) {
                    if(json.length) {
                      for(i=0; i < json.length; i++) {
                        $('#daddy-shoutbox-list').append(prepare(json[i]));
                		$('#list-' + count).fadeIn('slow');
                      }
                      var j = i-1;
                      lastTime = json[j].time;
                    }
                    //alert(lastTime);
                  });
                  timeoutID = setTimeout(refresh, 3000);
                }
                
                // wait for the DOM to be loaded 
                $(document).ready(function() { 
                    var options = { 
                      dataType:       'json',
                      beforeSubmit:   validate,
                      success:        success
                    }; 
                    $('#daddy-shoutbox-form').ajaxForm(options);
                    timeoutID = setTimeout(refresh, 100);
                });
          </script> 
                    
       </div>
    </div>
</body>
</html>