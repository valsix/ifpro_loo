<?php
$this->load->model('Tiket');
$tiket = new Tiket();

if(isset($_POST['page'])){
    //Include pagination class file
    include('Pagination.php');
    
    //Include database configuration file
    //include('dbConfig.php');
    
    $start = !empty($_POST['page'])?$_POST['page']:0;
    $limit = 3;
    
    //get number of rows	
	$rowCount = $tiket->getCountByParams(array(),$statement);	
    
    //initialize pagination class
    $pagConfig = array('baseURL'=>'app/loadUrl/pagination/getData/', 'totalRows'=>$rowCount, 'currentPage'=>$start, 'perPage'=>$limit, 'contentDiv'=>'posts_content');
    $pagination =  new Pagination($pagConfig);
    
    //get rows
    //$query = $db->query("SELECT * FROM posts ORDER BY id DESC LIMIT $start,$limit");

	$tiket->selectByParams(array(), $limit, $start);
    
    if($rowCount > 0){ ?>
        <div class="posts_list">
        <?php
            while($tiket->nextRow()){ 
                $postID = $tiket->getField("TIKET_ID");
        ?>
            <div class="list_item"><a href="javascript:void(0);"><h2><?php echo $tiket->getField("NAMA"); ?></h2></a></div>
        <?php } ?>
        </div>
        <?php echo $pagination->createLinks(); ?>
<?php }
}
?>