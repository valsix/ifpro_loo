<?php
require APPPATH . '/libraries/REST_Controller.php';
include_once("functions/string.func.php");
include_once("functions/date.func.php");

class jenis_naskah_json extends REST_Controller
{

    function __construct()
    {
        parent::__construct();

        $this->load->library('Kauth');

        $this->db->query("SET DATESTYLE TO PostgreSQL,European;");

        $this->methods['index_get']['limit'] = 500; // 500 requests per hour per user/key
        $this->methods['index_post']['limit'] = 100; // 100 requests per hour per user/key
        $this->methods['index_put']['limit'] = 50; // 50 requests per hour per user/key
    }

    // show data entitas
    function index_get()
    {
        /* AMBIL TOKEN */
        $this->load->model('UserLoginMobile');
        $user_login_mobile = new UserLoginMobile();
        $reqToken = $this->input->get("reqToken");
        $reqPegawaiId = $user_login_mobile->getTokenPegawaiId(array("TOKEN" => $reqToken, "STATUS" => '1'));
        $reqUserGroup = $user_login_mobile->getUserGroupPegawaiId(array("TOKEN" => $reqToken, "STATUS" => '1'));

        if ($reqPegawaiId === 0) {
            $this->response(array('status' => 'fail', 'message' => 'Sesi anda telah berakhir', 'code' => 502));
            return;
        }

        $this->load->library("usermobile");
        $userMobile = new usermobile();
        $userMobile->getInfo($reqPegawaiId, $reqToken, $reqUserGroup);
        /* END OF AMBIL TOKEN */

        $this->load->model("JenisNaskah");
        $jenis_naskah = new JenisNaskah();

        $reqId = $this->input->get("reqId");


        $statement = " AND TIPE_NASKAH LIKE '%" . $reqId . "%' ";
        $statement .= " AND NOT COALESCE(NULLIF(KODE_SURAT, ''), 'X') = 'X' ";

        $arr_json = array();
        $jenis_naskah->selectByParams(array("NOT JENIS_NASKAH_ID" => "0"), -1, -1, $statement);
        $i = 0;
        while ($jenis_naskah->nextRow()) {
            $arr_json[$i]['id']     = $jenis_naskah->getField("JENIS_NASKAH_ID");
            $arr_json[$i]['text']   = $jenis_naskah->getField("NAMA");
            $i++;
        }

        $result = $arr_json;

        $this->response(array('status' => 'success', 'message' => 'success', 'code' => 200, 'result' => $result));
    }

    // insert new data to entitas
    function index_post()
    {
    }

    // update data entitas
    function index_put()
    {
    }

    // delete entitas
    function index_delete()
    {
    }
}
