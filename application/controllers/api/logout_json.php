<?php
 
require APPPATH . '/libraries/REST_Controller.php';
include_once("functions/string.func.php");
include_once("functions/date.func.php");
 
class logout_json extends REST_Controller {
 
    function __construct() {
        parent::__construct();
		
		
		$this->load->library('Kauth');
    }
 
    // show data entitas
	function index_get() {
		$reqToken = $this->input->get('reqToken');
		
        $this->load->model('UserLoginMobile');
		
        $user_login_mobile = new UserLoginMobile();
		
		$user_login_mobile->setField("TOKEN", $reqToken);
        $user_login_mobile->setField("STATUS", "0");
		
        $temp = array();
		if ($user_login_mobile->update()) {
			$this->response(array('status' => 'success', 'message' => 'Anda berhasil Logout.', 'code' => 200));
		} else {
			$this->response(array('status' => 'fail', 'message' => 'Anda gagal Logout.', 'code' => 502));
		}
    }
	
    // insert new data to entitas
    function index_post() {

        
		

    }
 
    // update data entitas
    function index_put() {
		/*
        $entitas_id = $this->put('entitas_id');
        $data = array(
                    'entitas_id'       => $this->put('entitas_id'),
                    'nama'      => $this->put('nama'),
                    'id_jurusan'=> $this->put('id_jurusan'),
                    'alamat'    => $this->put('alamat'));
        $this->db->where('entitas_id', $entitas_id);
        $update = $this->db->update('entitas', $data);
        if ($update) {
            $this->response($data, 200);
        } else {
            $this->response(array('status' => 'fail', 502));
        }
		*/
    }
 
    // delete entitas
    function index_delete() {
		/*
        $entitas_id = $this->delete('entitas_id');
        $this->db->where('entitas_id', $entitas_id);
        $delete = $this->db->delete('entitas');
        if ($delete) {
            $this->response(array('status' => 'success'), 201);
        } else {
            $this->response(array('status' => 'fail', 502));
        }
		*/
    }
 
}