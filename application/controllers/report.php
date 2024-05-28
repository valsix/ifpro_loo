<?php
defined('BASEPATH') or exit('No direct script access allowed');
include_once("functions/image.func.php");
include_once("functions/string.func.php");

class report extends CI_Controller
{

	function __construct()
	{
		parent::__construct();
		/* GLOBAL VARIABLE */
		$this->db->query("SET DATESTYLE TO PostgreSQL,European;");
	}

	public function loadUrl()
	{

		$reqFolder = $this->uri->segment(3, "");
		$reqFilename = $this->uri->segment(4, "");
		$reqParse1 = $this->uri->segment(5, "");
		$reqParse2 = $this->uri->segment(6, "");
		$reqParse3 = $this->uri->segment(7, "");
		$reqParse4 = $this->uri->segment(8, "");
		$reqParse5 = $this->uri->segment(9, "");
		$data = array(
			'reqParse1' => urldecode($reqParse1),
			'reqParse2' => urldecode($reqParse2),
			'reqParse3' => urldecode($reqParse3),
			'reqParse4' => urldecode($reqParse4),
			'reqParse5' => urldecode($reqParse5)
		);
		$this->load->view('report/' . $reqFilename, $data);
	}

	public function loadTemplate()
	{

		$reqFilename = $this->uri->segment(3, "");
		$data = array(
			'reqParse1' => urldecode($reqParse1),
			'reqParse2' => urldecode($reqParse2),
			'reqParse3' => urldecode($reqParse3),
			'reqParse4' => urldecode($reqParse4),
			'reqParse5' => urldecode($reqParse5)
		);
		// $this->load->view('report-template/' . $reqFilename, $data);
		$this->load->view('report/' . $reqFilename, $data);
	}
}
