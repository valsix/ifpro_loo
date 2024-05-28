<?
$this->load->model('Tiket');
$this->load->library('Pagination');
$tiket = new Tiket();
?>
<!DOCTYPE HTML>
<html lang="en">
<head>
<base href="<?=base_url();?>">
<title>Pagination with jQuery Ajax PHP and MySQL by CodexWorld</title>
<link href='css/pagination.css' rel='stylesheet' type='text/css'>
<script src="js/jquery.min.js"></script>
<script type="text/javascript">
// Show loading overlay when ajax request starts
$( document ).ajaxStart(function() {
    $('.loading-overlay').show();
});
// Hide loading overlay when ajax request completes
$( document ).ajaxStop(function() {
    $('.loading-overlay').hide();
});
</script>
</head>
<body>
<div class="post-wrapper">
    <div class="loading-overlay"><div class="overlay-content">Loading.....</div></div>
    <div id="posts_content">
    <?php
    
    
    $limit = 3;
    
    //get number of rows
	$rowCount = $tiket->getCountByParams(array(),$statement);	
    
    //initialize pagination class
    $pagConfig = array('baseURL'=>'app/loadUrl/pagination/getData/', 'totalRows'=>$rowCount, 'perPage'=>$limit, 'contentDiv'=>'posts_content');
    $pagination =  new Pagination($pagConfig);
    
    //get rows
	$tiket->selectByParams(array(), $limit, 0);
    
    if($rowCount > 0){ ?>
        <div class="posts_list">
        <?php
            while($tiket->nextRow()){ 
                $postID = $tiket->getField("TIKET_ID");;
        ?>
            <div class="list_item"><a href="javascript:void(0);"><h2><?php echo $tiket->getField("NAMA"); ?></h2></a></div>
        <?php } ?>
        </div>
        <?php echo $pagination->createLinks(); ?>
    <?php } ?>
    </div>
</div>
</body>
</html>