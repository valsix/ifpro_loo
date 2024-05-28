<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Calendar extends CI_Controller {

	function __construct() {
		parent::__construct();
		//kauth
		
		if (!$this->kauth->getInstance()->hasIdentity())
		{
			redirect('login');
		}    

        $this->calendarId = $this->config->item('calendar_id');

	}
	
	public function index()
	{
		$this->load->library('GoogleClient');

		$google = new GoogleClient();

		$authCode = $this->input->get('code');

	    if($authCode <> ""){
	    	$client = $google->getClient($authCode);
	    	redirect('main/index/login');
	    }else{
	    	$client = $google->getClient();
	    	redirect('main/index/login');
	    }
	}



	function get()
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
	
	

	function add()
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
	
	function delete()
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

