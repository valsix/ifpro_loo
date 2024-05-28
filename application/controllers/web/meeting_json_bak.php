<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once("functions/default.func.php");
include_once("functions/string.func.php");
include_once("functions/date.func.php");
include_once("application/libraries/google-api-php-client-2.4.0/vendor/autoload.php");
// include_once("lib/excel/excel_reader2.php");

class meeting_json extends CI_Controller {

	function __construct() {
		parent::__construct();
		
		if (!$this->kauth->getInstance()->hasIdentity())
		{
			//redirect('login');
		}    
		
		$this->db->query("SET DATESTYLE TO PostgreSQL,European;"); 
		$this->ID				= $this->kauth->getInstance()->getIdentity()->ID;   
		$this->NAMA				= $this->kauth->getInstance()->getIdentity()->NAMA;   
		$this->JABATAN			= $this->kauth->getInstance()->getIdentity()->JABATAN;   
		$this->HAK_AKSES		= $this->kauth->getInstance()->getIdentity()->HAK_AKSES;   
		$this->LAST_LOGIN		= $this->kauth->getInstance()->getIdentity()->LAST_LOGIN;   
		$this->USERNAME			= $this->kauth->getInstance()->getIdentity()->USERNAME;  
		$this->USER_LOGIN_ID	= $this->kauth->getInstance()->getIdentity()->USER_LOGIN_ID;  
		$this->USER_GROUP		= $this->kauth->getInstance()->getIdentity()->USER_GROUP;  
		$this->CABANG_ID		= $this->kauth->getInstance()->getIdentity()->CABANG_ID;  
		$this->CABANG		= $this->kauth->getInstance()->getIdentity()->CABANG;  
		
		
	}
	
	function surat_masuk_meeting() 
	{
		$this->load->model("SuratMasuk");
		$surat_masuk = new SuratMasuk();

		$surat_masuk->selectByParamsMeeting(array());
		// echo $surat_masuk->query;exit;
		$surat_masuk->firstRow();

		$arr_json['data']			= $this->surat_masuk_meeting_detil();
		
		echo json_encode($arr_json);
	}

	function surat_masuk_meeting_detil()
	{
		$this->load->model("SuratMasuk");
		$surat_masuk = new SuratMasuk();

		$surat_masuk->selectByParamsMeeting(array());
		// echo $surat_masuk->query;exit;
		$i = 0;
		$items = array();
		while($surat_masuk->nextRow())
		{
			$row['id']				= $surat_masuk->getField("SURAT_MASUK_ID");
			$row['start_date'] 		= $surat_masuk->getField("TANGGAL_KEGIATAN");
			$row['end_date'] 		= $surat_masuk->getField("TANGGAL_KEGIATAN_AKHIR");
			$row['text'] 			= $surat_masuk->getField("JENIS");
			$row['details'] 		= $surat_masuk->getField("PERIHAL");

			$i++;
			array_push($items, $row);
		}
		
		return $items;
	}

	function calendar()
	{
		$client = new Google_Client();
		// var_dump($client);
	}

	function getClient()
	{
	    $client = new Google_Client();
	    $client->setApplicationName('Google Calendar API PHP Quickstart');
	    $client->setScopes(Google_Service_Calendar::CALENDAR_READONLY);
	    $client->setAuthConfig('credentials.json');
	    $client->setAccessType('offline');
	    $client->setPrompt('select_account consent');
	    // Load previously authorized token from a file, if it exists.
	    // The file token.json stores the user's access and refresh tokens, and is
	    // created automatically when the authorization flow completes for the first
	    // time.
	    $tokenPath = 'token.json';
	    if (file_exists($tokenPath)) {
	        $accessToken = json_decode(file_get_contents($tokenPath), true);
	        $client->setAccessToken($accessToken);
	    }
	    // If there is no previous token or it's expired.
	    if ($client->isAccessTokenExpired()) {
	        // Refresh the token if possible, else fetch a new one.
	        if ($client->getRefreshToken()) {
	            $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
	        } else {
	            // Request authorization from the user.
	            $authUrl = $client->createAuthUrl();
	            printf("Open the following link in your browser:\n%s\n", $authUrl);
	            print 'Enter verification code: ';
	            $authCode = "4/rAE_na9kEYHMQqQqSOIT-6_WdYB7hFzP8fs1RyhI-wvNMJmCPxJlGTo";
	            // Exchange authorization code for an access token.
	            $accessToken = $client->fetchAccessTokenWithAuthCode($authCode);
	            $client->setAccessToken($accessToken);
	            // Check to see if there was an error.
	            if (array_key_exists('error', $accessToken)) {
	                throw new Exception(join(', ', $accessToken));
	            }
	        }
	        // Save the token to a file.
	        if (!file_exists(dirname($tokenPath))) {
	            mkdir(dirname($tokenPath), 0700, true);
	        }
	        file_put_contents($tokenPath, json_encode($client->getAccessToken()));
	    }
	    return $client;
	}

	
}

