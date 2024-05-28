<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once("functions/default.func.php");
include_once("functions/string.func.php");
include_once("functions/date.func.php");
// include('application/libraries/scheduler-google-calendar/src/google_proxy.php');
// include_once("lib/excel/excel_reader2.php");
require_once 'application/libraries/google-api-php-client-2.4.0/src/Google/autoload.php';

class meeting_json extends CI_Controller {
	var $client;
	var $calendarId = 'cohu8p4q74iks6dpilk7mrpvi4@group.calendar.google.com';
	
	function __construct() {
		parent::__construct();
		
		if (!$this->kauth->getInstance()->hasIdentity())
		{
			//redirect('login');
		}    
		$this->service = null;

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
		$this->CABANG			= $this->kauth->getInstance()->getIdentity()->CABANG;  
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

	function auth()
	{
		$this->load->library('GoogleClient');

		$google = new GoogleClient();

		$authCode = $this->input->get('code');

	    if($authCode <> ""){
	    	$client = $google->getClient($authCode);
	    }else{
	    	$client = $google->getClient();
	    }
	    if(!$client->isAccessTokenExpired()){
	    	echo "<script type='text/javascript'>
		        window.close();
				window.opener.location.reload(true);
		    </script>";
	    }

	}

	function meeting()
	{
		$this->load->library('GoogleClient');

		$google = new GoogleClient();

		$client = $google->getClient();

		$service = new Google_Service_Calendar($client);
		
		$optParams = array(
		);
		$results = $service->events->listEvents('primary', $optParams);
		
		$events = $results->getItems();
		
		$items = array();
		foreach ($events as $event)
		{
			$row['id']				= $event->id;
			$row['start_date'] 		= $event->start->dateTime;
			$row['end_date'] 		= $event->end->dateTime;
			$row['text'] 			= $event->summary;

			array_push($items, $row);
		}
		
		echo json_encode($items);
	}
	
	

	function meeting_add()
	{
		$id				= $this->input->post('id');
		$start_date 	= $this->input->post('start_date');
		$end_date		= $this->input->post('end_date');
		$text 			= $this->input->post('text');
		$attendees		= $this->input->post('attendees');
		
		$this->load->library('GoogleClient');

		$google = new GoogleClient();

		$client = $google->getClient();

		$service = new Google_Service_Calendar($client);

		/* https://developers.google.com/calendar/v3/reference/events/insert */
		$arrEvent = array(
		  'summary' => $text,
		  'description' => '',
		  'start' => array(
			'dateTime' => $start_date  
		  ),
		  'end' => array(
			'dateTime' => $end_date 
		  ),
		  'reminders' => array(
			'useDefault' => FALSE,
			'overrides' => array(
			  array('method' => 'email', 'minutes' => 24 * 60),
			  array('method' => 'popup', 'minutes' => 10),
			),
		  ),
		);

		// invite email
		// if(isset($attendees))
		// {
		// 	$arrEvent['attendees'] = array(
		// 		array('email' => $attendees)
		// 	);
		// }
		
		$event = new Google_Service_Calendar_Event($arrEvent);

		if($id <> ""){
			$event = $service->events->update($this->calendarId, $id, $event);
		}else{
			$event = $service->events->insert($this->calendarId, $event);
		}
		echo($event->id);
	}
	
	function meeting_delete()
	{
		$id	= $this->input->post('id');
		
		$this->load->library('GoogleClient');

		$google = new GoogleClient();

		$client = $google->getClient();

		$service = new Google_Service_Calendar($client);
		
		$event = $service->events->delete($this->calendarId, $id);
		
		echo($event->id);
	}
}

