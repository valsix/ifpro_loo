<?php

defined('BASEPATH') OR exit('No direct script access allowed');

include_once("functions/string.func.php");

class Upload extends CI_Controller {

	function __construct() {
		parent::__construct();
	}
	
	public function index()
	{
		if (!file_exists('uploads/froala')) {
		    mkdir('uploads/froala', 0777, true);
		}
		$link = "uploads/froala/".date("Ymdhis").".".getExtension($_FILES["image_param"]["name"]);
		if(move_uploaded_file($_FILES["image_param"]["tmp_name"], $link)){
			$response = array("link" => $link);
			echo stripslashes(json_encode($response));
		} else {
			http_response_code(404);
		}
	}	
		
}

