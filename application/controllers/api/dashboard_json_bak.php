<?php
require APPPATH . '/libraries/REST_Controller.php';
include_once("functions/string.func.php");
include_once("functions/date.func.php");
 
class dashboard_json extends REST_Controller {
 
    function __construct() {
        parent::__construct();
          
        $this->load->library('Kauth');

        $this->db->query("SET DATESTYLE TO PostgreSQL,European;");  
        
        $this->methods['index_get']['limit'] = 500; // 500 requests per hour per user/key
        $this->methods['index_post']['limit'] = 100; // 100 requests per hour per user/key
        $this->methods['index_put']['limit'] = 50; // 50 requests per hour per user/key
    }
 
    // show data entitas
	function index_get() {
        /* AMBIL TOKEN */
        $this->load->model('UserLoginMobile');
        $user_login_mobile = new UserLoginMobile();
        $reqToken = $this->input->get("reqToken");
        $reqPegawaiId = $user_login_mobile->getTokenPegawaiId(array("TOKEN" => $reqToken, "STATUS" => '1'));
        $reqUserGroup = $user_login_mobile->getUserGroupPegawaiId(array("TOKEN" => $reqToken, "STATUS" => '1'));
        
        if($reqPegawaiId === 0){
            $this->response(array('status' => 'fail', 'message' => 'Sesi anda telah berakhir', 'code' => 502));
            return; 
        }

        $this->load->library("usermobile"); 
        $userMobile = new usermobile();
        $userMobile->getInfo($reqPegawaiId, $reqToken, $reqUserGroup);
        /* END OF AMBIL TOKEN */

        $this->load->library('suratmasukinfo');
		$this->load->model("Konten");
        $this->load->model("Faq");
        $this->load->model("SuratMasuk");
        $this->load->model("SuratKeluar");
        $this->load->model("Disposisi");
        $this->load->model("Arsip");
        $suratmasukinfo = new suratmasukinfo();
		$konten = new Konten();
        $konten_slider = new Konten();
        $faq = new Faq();
        $surat_masuk = new SuratMasuk();
        $surat_keluar = new SuratKeluar();
        $disposisi = new Disposisi();
        $arsip = new Arsip();


        //kolom yang ditampilkan untuk looping
        $aColumns_konten = array("KODE","KETERANGAN","ATTACHMENT");
        $aColumns_faq = array("PERTANYAAN","JAWABAN");
        $result = array();

        //KONTEN
        $statement_konten = " AND NOT KODE = 'SLIDER' ";
		$konten->selectByParams(array(),-1,-1,$statement_konten);
        
		while($konten->nextRow()){
            for ($i=0;$i<count($aColumns_konten);$i++)
            {
                if($aColumns_konten[$i] == "ATTACHMENT"){
                    if($konten->getField(trim($aColumns_konten[$i])) == ""){
                        $row_konten["URI"] = "";
                    }
                    else{
                        $row_konten["URI"] = base_url()."uploads/konten/".$konten->getField(trim($aColumns_konten[$i]));
                    }
                }
                else{
                    $row_konten[trim($aColumns_konten[$i])] = $konten->getField(trim($aColumns_konten[$i]));
                }
            }
            
            $result[$konten->getField("KODE")] = $row_konten;
		}

        //SLIDER
        $konten_slider->selectByParams(array("KODE" => "SLIDER"));
        $konten_slider->firstRow();
        $arrSlider = explode(",", $konten_slider->getField("ATTACHMENT"));
        for($j=0;$j<count($arrSlider);$j++){
            $row_slider[$j]["URI"] = base_url()."uploads/konten/".$arrSlider[$j];
        }

        $result["SLIDER"] = $row_slider;

        //FAQ
        $resultFaq = array();
        $faq->selectByParams(array());
        while($faq->nextRow()){
            for($i=0;$i<count($aColumns_faq);$i++)
            {
                $row_faq[trim($aColumns_faq[$i])] = $faq->getField(trim($aColumns_faq[$i]));
            }
            $resultFaq[] = $row_faq;
        }

        $result["FAQ"] = $resultFaq;


        if($reqUserGroup == "TATAUSAHA"){
	        $result["JUMLAH_SURAT_MASUK"]   = $surat_masuk->getCountByParams(array());
	    }
	    else{
            $statement_jmlsurat .= " AND B.DISPOSISI_PARENT_ID = 0
                AND (
                    A.STATUS_SURAT = 'POSTING' OR
                    A.STATUS_SURAT = 'TU-NOMOR' OR
                    (
                        A.STATUS_SURAT = 'TU-IN' AND
                        EXISTS(SELECT 1 FROM SURAT_MASUK_ARSIP X WHERE X.SURAT_MASUK_ID = A.SURAT_MASUK_ID AND X.CABANG_ID = '".$userMobile->CABANG_ID."')
                    )
                )";

            if($userMobile->KD_LEVEL_PEJABAT == "")
            {
                $statement_jmlsurat.= " AND 
                (
                    (
                        ( B.USER_ID = '".$userMobile->ID."' OR B.USER_ID = '')
                        AND B.DISPOSISI_KELOMPOK_ID = 0
                    )
                    OR
                    EXISTS
                    (
                        SELECT 1
                        FROM
                        (
                            SELECT A.DISPOSISI_KELOMPOK_ID, A.SURAT_MASUK_ID
                            FROM disposisi_kelompok A 
                            INNER JOIN satuan_kerja_kelompok_group B ON A.SATUAN_KERJA_KELOMPOK_ID = B.SATUAN_KERJA_KELOMPOK_ID
                            WHERE B.KELOMPOK_JABATAN = '".$reqKelompokJabatan."'
                        ) X WHERE X.SURAT_MASUK_ID = B.SURAT_MASUK_ID AND X.DISPOSISI_KELOMPOK_ID = B.DISPOSISI_KELOMPOK_ID
                        AND B.SATUAN_KERJA_ID_TUJUAN = '".$userMobile->CABANG_ID."'

                        )
                    )
                    ";
            }
            else
            {
                $statement_jmlsurat.= " AND (B.SATUAN_KERJA_ID_TUJUAN = '".$userMobile->SATUAN_KERJA_ID_ASAL."' OR B.USER_ID = '".$userMobile->ID."' OR B.USER_ID_OBSERVER = '".$userMobile->ID."') ";
            }

	    	// $result["JUMLAH_SURAT_MASUK"]   = $surat_masuk->getCountByParamsSuratMasuk(array(), $statement_jmlsurat);
	    }

	  //   if($reqUserGroup == "TATAUSAHA"){
	  //   	$statement_privacy .= " AND (A.STATUS_SURAT IN ('TATAUSAHA','POSTING') OR A.STATUS_SURAT LIKE 'TU%') ";
	  //       $result["JUMLAH_SURAT_KELUAR"]   = $surat_masuk->getCountByParams(array(), $statement_privacy);
	  //   }
	  //   else{
	  //   	$statement_privacy .= " AND (A.USER_ATASAN_ID = '".$userMobile->ID_ATASAN."' OR A.USER_ID = '".$userMobile->ID_ATASAN."' OR A.USER_ATASAN_ID = '".$userMobile->ID."' OR A.USER_ID = '".$userMobile->ID."' OR A.USER_ID_OBSERVER = '".$userMobile->ID."' OR EXISTS(SELECT 1 FROM SURAT_MASUK_PARAF X WHERE X.SURAT_MASUK_ID = A.SURAT_MASUK_ID AND X.USER_ID = '".$userMobile->ID."')) ";
			
			// $statement_privacy .= " AND (A.STATUS_SURAT IN ('TATAUSAHA','POSTING') OR A.STATUS_SURAT LIKE 'TU%') ";

	  //   	$result["JUMLAH_SURAT_KELUAR"]   = $surat_masuk->getCountByParams(array(), $statement_privacy);
	  //   }

	  //   if($reqUserGroup == "TATAUSAHA"){
	  //   	$statement_privacy .= " AND A.STATUS_SURAT IN ('DRAFT','PARAF') ";
	  //       $result["JUMLAH_DRAFT"]   = $surat_masuk->getCountByParams(array(), $statement_privacy);
	  //   }
	  //   else{
	  //   	$statement_privacy .= " AND A.SATUAN_KERJA_ID_ASAL = '".$userMobile->SATUAN_KERJA_ID_ASAL."' ";
			// $statement_privacy .= " AND A.STATUS_SURAT IN ('DRAFT','PARAF') ";

	  //   	$result["JUMLAH_DRAFT"]   = $surat_masuk->getCountByParams(array(), $statement_privacy);
	  //   }

     //    if($reqUserGroup == "TATAUSAHA"){
	    //     $result["JUMLAH_ARSIP"]   	= $arsip->getCountByParams(array());
	    // }
	    // else{
	    // 	$result["JUMLAH_ARSIP"]   	= $arsip->getCountByParams(array("SATUAN_KERJA_ID" => $userMobile->SATUAN_KERJA_ID));
	    // }

     //    $result["JUMLAH_TAKAH"]         = $surat_masuk->getCountByParams(array());

        // $suratmasukinfo->getJumlahSurat($userMobile->ID, $userMobile->USER_GROUP, $userMobile->CABANG_ID);

        $suratmasukinfo->getModifJumlahSurat($userMobile->ID, $userMobile->USER_GROUP, $userMobile->CABANG_ID, $userMobile->ID_ATASAN, $userMobile->KELOMPOK_JABATAN);

        $result["JUMLAH_SURAT_MASUK"]       = $suratmasukinfo->JUMLAH_INBOX;
        $result["JUMLAH_DISPOSISI"]         = $surat_masuk->getCountByParamsInbox(array(), " AND (A.USER_ID = '".$userMobile->ID."' OR B.USER_ID = '".$userMobile->ID."') ");
        $result["JUMLAH_VALIDASI"]          = $suratmasukinfo->JUMLAH_VALIDASI;
        $result["JUMLAH_DELEGASI"]          = '1';

        $result["JUMLAH_BADGE_SURAT_MASUK"] = $suratmasukinfo->JUMLAH_KOTAK_MASUK_SEMUA;
        $result["JUMLAH_BADGE_VALIDASI"]    = $suratmasukinfo->JUMLAH_VALIDASI;

		$this->response(array('status' => 'success', 'message' => 'success', 'code' => 200, 'result' => $result));
    }
	
    // insert new data to entitas
    function index_post() {
        
    }
 
    // update data entitas
    function index_put() {
		
    }
 
    // delete entitas
    function index_delete() {
		
    }
 
}