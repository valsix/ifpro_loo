<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Callback extends CI_Controller {

	function __construct() {
		parent::__construct();
		$this->storagePath = "";
	}
	
	public function save()
	{
		$body_stream = file_get_contents('php://input');
		$data = json_decode($body_stream, TRUE); 
		$this->sendlog(json_encode($data), "webedior-ajax.log");
		$reqId = $this->input->get('reqId');

		if($data["url"] != ""){
		    $new_data = file_get_contents($data["url"]);
		    $this->storagePath = realpath("uploads/".$reqId);
		    if(!$this->storagePath){
		        mkdir("uploads/".$reqId, 0777, true);
		        $this->storagePath = realpath("uploads/".$reqId);
		    }
		    file_put_contents($this->storagePath."/".$reqId.".docx", $new_data, LOCK_EX);   
		    $this->convertPDF($data["url"], $reqId);  
		}
		$response_array['status'] = 'success';
		$response_array['error'] = 0;
		$response_array['path'] = realpath("uploads/".$reqId);
		die (json_encode($response_array));
	}	
	
	public function convertPDF($url, $reqId)
	{
		$time = time();

	    $form = array(
	        "async" => false,
	        "filetype" => "docx",
	        "outputtype" => "pdf",
	        "key" => $time,
	        "title" => "assadasdad",
	        "url" => $url
	    );
	    
	    $ch = curl_init ( 'http://192.168.1.6:81/ConvertService.ashx' );
	    curl_setopt_array ( $ch, array (
	        CURLOPT_POST => 1,
	        CURLOPT_RETURNTRANSFER => TRUE,
	        CURLOPT_FOLLOWLOCATION => TRUE,
	        CURLOPT_VERBOSE => TRUE,
	        CURLOPT_POSTFIELDS => json_encode($form),
	        CURLOPT_HTTPHEADER, array(
			    'Content-Type: application/json',
			    'Content-Length: ' . strlen(json_encode($form))
			)
	    ));

	    $result = curl_exec ( $ch );
	    $result = new SimpleXMLElement($result);
	    
	    // var_dump($result);

	    if($result->Error){
	    	
	    }else {
	    	$fileUrl = $result->FileUrl[0];
	    	$new_data = file_get_contents($fileUrl);
	    	file_put_contents($this->storagePath."/".$reqId.".pdf", $new_data, LOCK_EX);
	    }
	    
	}

	function sendlog($msg, $logFileName) {
	    $logsFolder = "logs/";
	    if (!file_exists($logsFolder)) {
	        mkdir($logsFolder);
	    }
	    file_put_contents($logsFolder . $logFileName, $msg . PHP_EOL, FILE_APPEND);
	}
		
}

