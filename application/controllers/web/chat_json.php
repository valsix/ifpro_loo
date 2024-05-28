<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once("functions/default.func.php");
include_once("functions/string.func.php");
include_once("functions/date.func.php");
// include_once("lib/excel/excel_reader2.php");

class chat_json extends CI_Controller {

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
	
	function json() 
	{
		$this->load->model("Chat");
		$chat = new Chat();

		$reqPegawaiIdTo = $this->input->get("reqPegawaiIdTo");
		// echo $reqKategori;exit;
		
		$aColumns		= array("CHAT_ID", "PEGAWAI_ID_BY", "PESAN", "READ", "PEGAWAI_ID_TO", "TANGGAL", "JAM");
		$aColumnsAlias	= array("CHAT_ID", "PEGAWAI_ID_BY", "PESAN", "READ", "PEGAWAI_ID_TO", "TANGGAL", "JAM");

		
		$statement = " AND (PEGAWAI_ID_BY = '".$this->ID."' AND PEGAWAI_ID_TO = '".$reqPegawaiIdTo."') OR (PEGAWAI_ID_BY = '".$reqPegawaiIdTo."' AND PEGAWAI_ID_TO = '".$this->ID."') ";
        $chat->selectByParams(array(), -1, -1, $statement, "ORDER BY TANGGAL ASC, JAM ASC");
        // echo $chat->query; exit;
        $result = array();
        $index = 0;
        while($chat->nextRow())
        {
            $row = array();
            for ( $i=0 ; $i<count($aColumns) ; $i++ )
            {
                if(trim($aColumns[$i]) == "TANGGAL"){
                    $row['date'] = $chat->getField(trim("TANGGAL"));
                    $row['time'] = $chat->getField("JAM");
                }
                else if(trim($aColumns[$i]) == "PEGAWAI_ID_BY"){
                    $created_by = $chat->getField(trim($aColumns[$i]));
                    
                    if($created_by == $this->ID){
                        $row['pos'] = 'right';
                        $row['class'] = 'new';
                    }else{
                        $row['pos'] = 'left';
                        $row['class'] = 'message-personal';
                    }
                }
                elseif(trim($aColumns[$i]) == "READ"){
                    if($chat->getField(trim($aColumns[$i])) == 1){
                        $row['read'] = true;
                    }else{
                        $row['read'] = false;
                    }
                }
                elseif(trim($aColumns[$i]) == "PESAN"){
                    $row['message'] = $chat->getField(trim($aColumns[$i]));
                }
                else
                    $row[trim($aColumns[$i])] = $chat->getField(trim($aColumns[$i]));

            }
            $result[$index] = $row;
            $index++;
        }


		echo json_encode($result);
	}
	
	function add() 
	{
		$this->load->model("Chat");
		$chat = new Chat();

		$reqPegawaiIdTo 			= $this->input->post("reqPegawaiIdTo");
		$reqPesan 			= $this->input->post("reqPesan");
		
		$chat->setField("PEGAWAI_ID_BY", $this->ID);
		$chat->setField("PEGAWAI_ID_TO", $reqPegawaiIdTo);
		$chat->setField("PESAN", $reqPesan);
		$chat->setField("READ", "0");
		$chat->setField("TANGGAL", "CURRENT_TIMESTAMP");
		
		$result['chatbox'] = $reqPegawaiIdTo;

		if($chat->insert())
		{
			$this->load->library('PushNotification'); 
			$pushNotification = new PushNotification();

			$pushNotification->send_to_topic($reqPegawaiIdTo, "CHAT", $this->ID, "CHAT", $this->NAMA, $reqPesan);
			$result['status'] = 'success';
		}
		else
		{
			$result['status'] = 'fail';
		}


		
		echo json_encode($result);
	
	}
	
	function delete() 
	{
		$reqId	= $this->input->get('reqId');
		$this->load->model("Makanan");
		$chat = new Makanan();

		
		$chat->setField("MAKANAN_ID", $reqId);
		if($chat->delete())
			$arrJson["PESAN"] = "Data berhasil dihapus.";
		else
			$arrJson["PESAN"] = "Data gagal dihapus.";		
		
		echo json_encode($arrJson);
	}	
	
	function combo() 
	{
		$this->load->model("Makanan");
		$chat = new Makanan();

		$chat->selectByParams(array());
		$i = 0;
		while($chat->nextRow())
		{
			$arr_json[$i]['id']		= $chat->getField("MAKANAN_ID");
			$arr_json[$i]['text']	= $chat->getField("NAMA");
			$i++;
		}
		
		echo json_encode($arr_json);
	}
	
}

