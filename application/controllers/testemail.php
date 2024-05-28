<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class testemail extends CI_Controller {

	function __construct() {
		parent::__construct();


		$this->db->query("SET DATESTYLE TO PostgreSQL,European;");  
				
		$this->load->library('kauth');     
	}
	
	public function index()
	{
		$this->load->library("KMail");
		$reqEmail="wendy.priana@indonesiaferry.co.id";
		$reqNama="Wendy";
		// $reqEmail=$email;
		// $reqNama=$nama;
		$body= $jenis_surat.' dengan subject "'.$perihal.'. ('.$no_surat.')" untuk anda.<br>Dari/Pengirim: '.$pengirim.'.<br><br>Klik Link dibawah untuk membuka document '.$jenis_surat.' Anda: <br>'.$linkAja.' <br><br>emailnya: '.$email.'       namanya: '.$nama;

		$mail = new KMail();

		$mail->AddAddress($reqEmail, $reqNama);
		$mail->Subject  = "Surat Masuk - ". $perihal;
		$mail->MsgHTML($body);
		if (!$mail->Send()) {
			$pesan.= $reqEmail.' Gagal dikirim, silahkan hubungi administrator.';
		} else {
			$pesan.= "<center><br>Notifikasi Email telah kami kirimkan.<br></center>";
			$pesan.= "<center>PERHATIAN:</center><br><center>Jika tidak ditemukan pada INBOX email anda, periksa juga pada folder SPAM, dan tandai bukan spam email verifikasi ini.</center>";
		}


		echo $pesan."dsajdabas";
		// $this->load->view('registrasi/index');
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
			'reqParse1' => $reqParse1,
			'reqParse2' => $reqParse2,
			'reqParse3' => $reqParse3,
			'reqParse4' => $reqParse4,
			'reqParse5' => urldecode($reqParse5)
		);
		$this->load->view($reqFolder.'/'.$reqFilename, $data);
	}	
}