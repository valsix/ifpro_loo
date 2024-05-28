<?php
error_reporting(1);
require APPPATH . '/libraries/REST_Controller.php';
include_once("functions/string.func.php");
include_once("functions/date.func.php");
 
class surat_keluar_view_json extends REST_Controller {
 
    function __construct() {
        parent::__construct();
        $this->methods['index_get']['limit'] = 500; // 500 requests per hour per user/key
        $this->methods['index_post']['limit'] = 100; // 100 requests per hour per user/key
        $this->methods['index_put']['limit'] = 50; // 50 requests per hour per user/key
    }
 
    // show data entitas
	function index_get() {
        $aColumns = array("SURAT_BPPNFI_ID", "USER_REVISI_STATUS_INFO", "USER_TANDA_TANGAN_ID", "NOMOR_GENERATE", "USER_PEMOHON_ID", "USER_REVISI_ID", "USER_REVISI_INFO", "PEREVISI_STATUS_INFO", "PEREVISI_INFO", "PEREVISI_STATUS", "TIPE_NAMA", "TANGGAL", "TIPE", "SATUAN_KERJA_ID_POSISI", "SATUAN_KERJA_POSISI_INFO", "SATUAN_KERJA_ID_ASAL", "SATUAN_KERJA_ASAL_INFO", "JENIS_TUJUAN", "JENIS", "NOMOR", "NO_AGENDA", "KODE_HAL_ID", "KODE_HAL", "KODE_HAL_NAMA", "PERIHAL", "ISI", "CATATAN", "KETERANGAN_TUJUAN", "KOTA_TUJUAN", "INSTANSI_TUJUAN", "SATUAN_NAMA", "SATUAN_KERJA_ID_TUJUAN", "SURAT_MASUK_ID", "NO_MASUK", "TANDA_TANGAN_ID", "TANDA_TANGAN", "UNDANGAN_TANGGAL", "UNDANGAN_JAM", "UNDANGAN_TEMPAT", "UNDANGAN_ACARA", "SURAT_KETERANGAN_NAMA1", "SURAT_KETERANGAN_NIP1", "SURAT_KETERANGAN_PANGKAT1", "SURAT_KETERANGAN_JABATAN1", "SURAT_KETERANGAN_NAMA2", "SURAT_KETERANGAN_NIP2", "SURAT_KETERANGAN_PANGKAT2", "SURAT_KETERANGAN_JABATAN2", "ALAMAT", "NAMA_PERUSAHAAN", "ALAMAT_PERUSAHAAN", "NO_TELP", "TANGGAL_LAHIR", "TEMPAT", "PERTIMBANGAN", "DASAR", "KEPADA", "TEMBUSAN");

        $reqUserSatker = $this->input->get('reqUserSatker');
        $reqTipe = $this->input->get('reqTipe');
        $reqStatus = $this->input->get('reqStatus');
        $reqSearch = $this->input->get('reqSearch');

        $this->load->model('UserLoginMobile');
        $reqToken = $this->input->get("reqToken");
        $reqSuratBppnfiId = $this->input->get("reqSuratBppnfiId");

        //CEK PEGAWAI ID DARI TOKEN
        $user_login_mobile = new UserLoginMobile();
        // $reqPegawaiId = $user_login_mobile->getTokenPegawaiId(array("TOKEN" => $reqToken, "STATUS" => '1'));
        $reqPegawaiId = '1';

        if($reqSuratBppnfiId == "")
        {
            $this->response(array('status' => 'fail', 'message' => 'Anda Tidak berhak membuka menu ini.', 'code' => 502));
        }

        if($reqPegawaiId <> "0")
        {
            $this->load->model("base-surat/SuratBppnfi");
            $this->load->model("base-surat/SuratBppnfiAttachment");
            $this->load->model("base-surat/SuratTugasIsi");
            $surat_bppnfi = new SuratBppnfi();
                    
            $surat_bppnfi->selectByParams(array('A.SURAT_BPPNFI_ID'=>$reqSuratBppnfiId),-1,-1);
            // echo $surat_bppnfi->query;exit;
            $surat_bppnfi->firstRow();

            $lampiran = new SuratBppnfiAttachment();
            $lampiran->selectByParams(array("SURAT_BPPNFI_ID" => $reqSuratBppnfiId));
            $arrLampiran = "";
            $index_lampiran=0;
            while($lampiran->nextRow())
            {
                $arrLampiran[$index_lampiran]["SURAT_BPPNFI_ID"] = $lampiran->getField("SURAT_BPPNFI_ID");
                $arrLampiran[$index_lampiran]["SURAT_BPPNFI_ATTACHMENT_ID"] = $lampiran->getField("SURAT_BPPNFI_ATTACHMENT_ID");
                $arrLampiran[$index_lampiran]["NAMA"] = $lampiran->getField("NAMA");
                $arrLampiran[$index_lampiran]["TIPE"] = $lampiran->getField("TIPE");
                $arrLampiran[$index_lampiran]["UKURAN"] = $lampiran->getField("UKURAN");
                $arrLampiran[$index_lampiran]["ATTACHMENT"] = $lampiran->getField("ATTACHMENT");
                $index_lampiran++;
            }
            $tempJumlahLampiran = $index_lampiran;
            
            $arrIsi = "";
            $index_isi = 0; 
            $surat_tugas_isi = new SuratTugasIsi();
            $surat_tugas_isi->selectByParams(array("SURAT_BPPNFI_ID"=>$reqSuratBppnfiId));
            while($surat_tugas_isi->nextRow())
            {
                $arrIsi[$index_isi]["SURAT_TUGAS_ISI_ID"] = $surat_tugas_isi->getField("SURAT_TUGAS_ISI_ID");
                $arrIsi[$index_isi]["SURAT_BPPNFI_ID"] = $surat_tugas_isi->getField("SURAT_BPPNFI_ID");
                $arrIsi[$index_isi]["KETERANGAN"] = $surat_tugas_isi->getField("KETERANGAN");
                $index_isi++;
            }

            $jumlahIsi = $index_isi;

            $row = array();
            for ( $i=0 ; $i<count($aColumns) ; $i++ )
            {
                if($aColumns[$i] == "TANGGAL" || $aColumns[$i] == "UNDANGAN_TANGGAL" || $aColumns[$i] == "TANGGAL_LAHIR")
                    $row[trim($aColumns[$i])] = dateToPageCheck($surat_bppnfi->getField(trim($aColumns[$i])));    
                else
                    $row[trim($aColumns[$i])] = $surat_bppnfi->getField(trim($aColumns[$i]));
            }
            
            $result['data'] = $row;
            $result['lampiran'] = $arrLampiran;
            $result['isi'] = $arrIsi;

            $this->response(array('status' => 'success', 'message' => 'success', 'code' => 200, 'count' => count($aColumns) ,'result' => $result));
        }
        else
            $this->response(array('status' => 'fail', 'message' => 'Sesi anda telah berakhir', 'code' => 502));
        
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