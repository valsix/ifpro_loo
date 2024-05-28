<?php
require APPPATH . '/libraries/REST_Controller.php';
include_once("functions/image.func.php");
include_once("functions/string.func.php");

class Infopegawai extends REST_Controller {
 
    function __construct() {
        parent::__construct();

        $this->methods['index_get']['limit'] = 500; // 500 requests per hour per user/key
        $this->methods['index_post']['limit'] = 100; // 100 requests per hour per user/key
        $this->methods['index_put']['limit'] = 50; // 50 requests per hour per user/key
    }
 
    // show data entitas
    function index_get() {

    }

    
    // insert new data to entitas
    function index_post() {
        $this->load->model('Users');
        $this->load->model('Pegawai');

        ini_set('memory_limit', -1);
        ini_set('max_execution_time', -1);

        $reqUser = $this->input->post("reqUser");
        $reqPasswd = $this->input->post("reqPasswd");

        if(!empty($reqUser) AND !empty($reqPasswd))
        {
            // $reqUser= "wendy.priana@indonesiaferry.co.id";
            // $reqPasswd= "leedaehae";

            // $reqUser= "072124180";
            // $reqPasswd= "valsixasdpsurat";

            $username= $reqUser;
            $password= $reqPasswd;

            $pakaisso= "login";
            if( strpos($username, "@") AND strpos($username, ".") )
            {
                $pakaisso= "1";
            }

            if (strpos($username, "ADM_") !== false) 
            {
                $reqTokenFirebase= "admin";
            }

            if($pakaisso == "1")
            {
                $ch = curl_init();
                // $data = array("username" => "in_f1d145", 
                //  "password" => "@5dp-f1d145", 
                //  "c_password" => "@5dp-f1d145");

                $data = array("username" => "in_30ff1c3", 
                    "password" => "@5dp-30ff1c3", 
                    "c_password" => "@5dp-30ff1c3");

                curl_setopt($ch, CURLOPT_URL, "http://sso.indonesiaferry.id/api/login");
                curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: multipart/form-data'));
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $response = curl_exec($ch);
                curl_close($ch);

                $obj = json_decode($response);
                // print_r($obj);exit;

                $arrResult = array();
                if($obj->status == "200")
                {
                    $tokenUser = $obj->token;
                    $channelCode = $obj->channel_code;

                    $ch = curl_init();
                    $data = array("channel_code" => $channelCode, 
                                  "email" => $username,
                                  "password" => $password);
                    $payload = json_encode($data);
                                  
                    curl_setopt($ch, CURLOPT_URL, "http://sso.indonesiaferry.id/api/access");
                    curl_setopt($ch, CURLINFO_HEADER_OUT, true);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                        'Authorization: Bearer '.$tokenUser,
                        'Content-Type: application/json',
                        'Content-Length: ' . strlen($payload))
                    );
                    curl_setopt($ch, CURLOPT_POST, 1);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    $response = curl_exec($ch);
                    curl_close($ch);
                    $obj = json_decode($response);  
                    
                    // print_r($obj);exit;

                    if($obj->status == "200")
                    {
                        $pakaisso= "loginsso";
                    }
                }

            }

            // echo $pakaisso;exit;

            if($pakaisso == "login" || $pakaisso == "loginsso")
            {
                $users= new Users();
                $users->selectByIdPassword($reqUser, md5($reqPasswd));
                if($users->firstRow())
                {
                    $result = array();
                    $infopegawaid= $users->getField("PEGAWAI_ID");
                    // echo $infopegawaid;exit;
                    $arrdata= array(
                        array("kolom"=>"PEGAWAI_ID", "info"=>"id")
                        , array("kolom"=>"NAMA", "info"=>"nama")
                        , array("kolom"=>"JENIS_KELAMIN", "info"=>"jeniskelamin")
                        , array("kolom"=>"EMAIL", "info"=>"email")
                        , array("kolom"=>"JABATAN", "info"=>"jabatan")
                        , array("kolom"=>"SATUAN_KERJA", "info"=>"cabang")
                        , array("kolom"=>"SATUAN_KERJA_ID", "info"=>"cabangid")
                        , array("kolom"=>"INFO_DEPARTEMEN_NAMA", "info"=>"departemen")
                        , array("kolom"=>"DEPARTEMEN_ID", "info"=>"departemenid")
                    );
                    // print_r($arrdata);exit;

                    $set= new Pegawai();
                    $set->selectByParams(array("A.PEGAWAI_ID"=>$infopegawaid));
                    $set->firstRow();
                    // echo $set->query;exit;
                    for($i=0 ; $i<count($arrdata); $i++)
                    {
                        $row[trim($arrdata[$i]["info"])] = $set->getField(trim($arrdata[$i]["kolom"]));
                    }
                    $result[]= $row;

                    $this->response(array('status' => 'success', 'message' => 'success', 'code' => 200, 'count' => count($result) ,'result' => $result));
                }
                else 
                {
                    $this->response(array('status' => 'fail', 'message' => 'Gagal login, pastikan user dan password anda benar.', 'code' => 502));
                }
            }
            else 
            {
                $this->response(array('status' => 'fail', 'message' => 'Gagal login, pastikan user dan password anda benar.', 'code' => 502));
            }
        }
        else
        {
            $this->response(array('status' => 'fail', 'message' => 'Masukkan Username atau Password.', 'code' => 502));
        }

        /*$this->load->model('UserLoginLog');
        $this->load->model('base-absensi/Absensi');
        $this->load->model('base/Pegawai');
        $this->load->model('base/SatuanKerja');

        $user_login_log= new UserLoginLog;
        
        $reqToken = $this->input->get("reqToken");
        $reqMode = $this->input->get("reqMode");
        // $id = $this->input->get("id");
        $nip= $this->input->get("nip");
        $tanggalmulai= dateToPageCheck($this->input->get("tanggalmulai"));
        $tanggalakhir= dateToPageCheck($this->input->get("tanggalakhir"));

        //CEK PEGAWAI ID DARI TOKEN
        // e6088b42e1f40f4083b0df2a349ed198
        // $user_login_log = new UserLoginLog();
        // $reqSatuanKerjaId = $user_login_log->getTokenSatuanKerjaId(array("TOKEN" => $reqToken, "STATUS" => '1'));
        // echo $statement;exit;

        // if($reqSatuanKerjaId == "")
        // if($reqToken !== "e6088b42e1f40f4083b0df2a349ed198" || empty($nip) || empty($tanggalmulai) || empty($tanggalakhir))
        if($reqToken !== "e6088b42e1f40f4083b0df2a349ed198" || empty($tanggalmulai) || empty($tanggalakhir))
        {
            $this->response(array('status' => 'fail', 'message' => 'Sesi anda telah berakhir', 'code' => 502));
        }
        else
        {
            $total = 0;
            $aColumns = array("PEGAWAI_ID", "NIP_BARU", "NAMA_LENGKAP", "TANGGAL", "MASUK", "PULANG", "EX_MASUK", "TERLAMBAT", "PULANG_CEPAT");

            if(!empty($nip))
            {
                $statementsatuankerja= " AND ( A.NIP_BARU = '".$nip."' OR A.PEGAWAI_ID = ".$nip.") ";
            }

            // $statementsatuankerja= " AND A.PEGAWAI_ID IN (8300, 13782) ";
            $statementsatuankerja.= " 
            AND
            (
                A.STATUS_PEGAWAI_ID IN (1,2)
                OR
                (
                    A.STATUS_PEGAWAI_ID IN (3,4,5)
                    AND 
                    EXISTS
                    (
                        SELECT 1
                        FROM
                        (
                            SELECT PEGAWAI_STATUS_ID
                            FROM pegawai_status
                            WHERE TMT >= TO_DATE('01".getMonth($tanggalmulai).getYear($tanggalmulai)."', 'DDMMYYYY')
                        ) XXX WHERE A.PEGAWAI_STATUS_ID = XXX.PEGAWAI_STATUS_ID
                    )
                )
            )";

            $start= strtotime(getYear($tanggalmulai)."-".getMonth($tanggalmulai)."-01");
            $end= strtotime(getYear($tanggalakhir)."-".getMonth($tanggalakhir)."-01");
            while($start <= $end)
            {
                $infoperiode= date('mY', $start);
                $infotanggalperiode=getTahunPeriode($infoperiode)."-".getBulanPeriode($infoperiode);
                // echo $infotanggalperiode."<br/>";
                // exit;
                $set= new Absensi();
                $set->selectByDataPeriode(array(), -1, -1, $infoperiode, $statementsatuankerja, "ORDER BY A.ESELON_ID ASC, A.PANGKAT_ID DESC, A.PANGKAT_RIWAYAT_TMT ASC");
                $infocheck= $set->errorMsg;
                if(!empty($infocheck)){}
                else
                {
                    // echo $set->query;exit;
                    // $set->firstRow();
                    while ($set->nextRow())
                    {
                        for($n=1; $n <= 31; $n++)
                        {
                            $infohari= generateZeroDate($n,2);
                            $infotanggal= $infotanggalperiode."-".$infohari;

                            $today_time= strtotime($infotanggal);
                            $expire_time= strtotime($tanggalakhir);
                            // echo $infotanggal."<br/>";
                            if($expire_time < $today_time)
                            {
                                // echo $today_time."--".$expire_time."<br/>";
                                break;
                            }
                            // echo $infotanggal;exit;
                            if(validateDate($infotanggal))
                            {
                                $row = array();
                                for($i=0 ; $i<count($aColumns); $i++)
                                {
                                    if($aColumns[$i] == "TANGGAL")
                                        $row[trim($aColumns[$i])] = dateToPageCheck($infotanggal);
                                    elseif($aColumns[$i] == "MASUK" || $aColumns[$i] == "PULANG" || $aColumns[$i] == "EX_MASUK" || $aColumns[$i] == "TERLAMBAT" || $aColumns[$i] == "PULANG_CEPAT")
                                    {
                                        $infodata= $set->getField(trim($aColumns[$i]."_".$n));
                                        $infodata= coalesce($infodata, "-");
                                        $infoarray= trim($aColumns[$i]);
                                        if($infoarray == "EX_MASUK")
                                            $infoarray= "ASK";

                                        $row[$infoarray] = $infodata;
                                    }
                                    else
                                        $row[trim($aColumns[$i])] = $set->getField(trim($aColumns[$i]));
                                }
                                $result[] = $row;
                            }
                            // echo $infohari;
                        }
                        // print_r($result);exit;
                    }
                    $total++;
                }

                $start = strtotime("+1 month", $start);
            }
            // print_r($result);exit;
            // exit;
            
            if($total == 0)
            {
                for ( $i=0 ; $i<count($aColumns) ; $i++ )
                {
                    $row[trim($aColumns[$i])] = "";
                }
                $result[] = $row;
            }
            
            $this->response(array('status' => 'success', 'message' => 'success', 'code' => 200, 'count' => $total ,'result' => $result));
        }*/
    }
 
    // update data entitas
    function index_put() {
    }
 
    // delete entitas
    function index_delete() {
    }
 
}