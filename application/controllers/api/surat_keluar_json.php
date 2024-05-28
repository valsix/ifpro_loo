<?php
error_reporting(1);
require APPPATH . '/libraries/REST_Controller.php';
include_once("functions/string.func.php");
include_once("functions/date.func.php");
include_once("functions/default.func.php");
 
class surat_keluar_json extends REST_Controller {
 
    function __construct() {
        parent::__construct();
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
                
        $this->load->model("SuratMasuk");
        $this->load->model("Disposisi");
        $surat_masuk = new SuratMasuk();
        $disposisi = new Disposisi();

        $aColumns = array("SURAT_KELUAR_ID","DISPOSISI_ID","TERBACA","NAMA_USER_ASAL","NAMA_SATKER_ASAL",
            "PERIHAL","ISI","JENIS_NASKAH","NOMOR","SIFAT_NASKAH","PRIORITAS_SURAT","TANGGAL_ENTRI");

        $statement_privacy .= " AND (A.USER_ATASAN_ID = '".$userMobile->ID_ATASAN."' OR A.USER_ID = '".$userMobile->ID_ATASAN."' 
                                OR A.USER_ATASAN_ID = '".$userMobile->ID."' OR A.USER_ID = '".$userMobile->ID."' OR A.USER_ID_OBSERVER = '".$userMobile->ID."'
                                OR EXISTS( SELECT 1 FROM SURAT_MASUK_PARAF X WHERE X.SURAT_MASUK_ID = A.SURAT_MASUK_ID AND X.USER_ID = '".$userMobile->ID."')) 
                                ";
        $statement_privacy .= " AND (A.STATUS_SURAT IN ('TATAUSAHA','POSTING') OR A.STATUS_SURAT LIKE 'TU%') ";

        $sOrder=" ORDER BY TANGGAL_ENTRI::TIMESTAMP DESC ";

        $surat_masuk->selectByParamsMonitoringSent(array(), -1, -1, $statement_privacy.$statement, $sOrder); 
        // echo $surat_masuk->query;exit;
        $result = array();
        while($surat_masuk->nextRow())
        {
            $row = array();
            for ( $i=0 ; $i<count($aColumns) ; $i++ )
            {
                if($aColumns[$i] == "TANGGAL" || $aColumns[$i] == "TANGGAL_DISPOSISI" || $aColumns[$i] == "TANGGAL_DITERUSKAN")
                    $row[trim($aColumns[$i])] = getFormattedDate($surat_masuk->getField(trim($aColumns[$i]))); 
                elseif($aColumns[$i] == "STATUS_DISPOSISI" || $aColumns[$i] == "STATUS_BALAS" || $aColumns[$i] == "STATUS_BACA" || $aColumns[$i] == "LAMPIRAN")
                {
                    if($surat_masuk->getField(trim($aColumns[$i])) == 1)
                        $row[trim($aColumns[$i])] = "check.png";  
                    else
                        $row[trim($aColumns[$i])] = "uncentang.png";              
                }
                else
                    $row[trim($aColumns[$i])] = $surat_masuk->getField(trim($aColumns[$i]));
            }
            
            $result[] = $row;

        }
        
        $this->response(array('status' => 'success', 'message' => 'success', 'code' => 200, 'count' => count($aColumns) ,'result' => $result));
    }
    
    // insert new data to entitas
    function index_post() {
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

        $this->load->model("SuratMasuk");
        $this->load->model("Disposisi");
        $this->load->model("DisposisiKelompok");
        $surat_masuk = new SuratMasuk();
        $disposisi = new Disposisi();
        $disposisi_kelompok = new DisposisiKelompok();

        $reqMode                    = $this->input->post("reqMode");
        $reqId                      = $this->input->post("reqId");
        $refDisposisiId             = $this->input->post("refDisposisiId");
        
        if($refDisposisiId == ""){
            $reqIdRef = "";
        }
        else
        {
            $surat_masuk_ref = new SuratMasuk();
            $surat_masuk_ref->selectByParams(array(), -1, -1, " AND EXISTS(SELECT 1 FROM DISPOSISI X WHERE X.SURAT_MASUK_ID = A.SURAT_MASUK_ID AND MD5('BALAS' || X.DISPOSISI_ID) = '".$refDisposisiId."') ");
            $surat_masuk_ref->firstRow();
            $reqIdRef = $surat_masuk_ref->getField("SURAT_MASUK_ID");
        }

        $reqJenisTujuan             = $this->input->post("reqJenisTujuan");
        $reqJenisNaskah             = $this->input->post("reqJenisNaskah");
        $reqKdLevel                 = $this->input->post("reqKdLevel");
        $reqNoAgenda                = $this->input->post("reqNoAgenda");
        $reqNoSurat                 = $this->input->post("reqNoSurat");
        $reqTanggal                 = $this->input->post("reqTanggal");
        $reqPerihal                 = $this->input->post("reqPerihal");
        $reqKeterangan              = $_POST["reqKeterangan"];
        $reqSifatNaskah             = $this->input->post("reqSifatNaskah");
        $reqStatusSurat             = $this->input->post("reqStatusSurat");
        
        $reqAsalSuratNama           =  $this->input->post("reqAsalSuratNama");
        $reqAsalSuratKota           =  $this->input->post("reqAsalSuratKota");
        $reqAsalSuratAlamat         =  $this->input->post("reqAsalSuratAlamat");
        $reqAsalSuratInstansi       =  $this->input->post("reqAsalSuratInstansi");
        $reqLokasiSurat             =  $this->input->post("reqLokasiSurat");
        $reqSatuanKerjaIdTujuan     =  $this->input->post("reqSatuanKerjaIdTujuan");
        $reqSatuanKerjaIdTembusan   =  $this->input->post("reqSatuanKerjaIdTembusan");  
        $reqSatuanKerjaIdParaf      =  $this->input->post("reqSatuanKerjaIdParaf"); 
        $reqKlasifikasiId           =  $this->input->post("reqKlasifikasiId");
        $reqPenyampaianSurat        =  $this->input->post("reqPenyampaianSurat");
        $reqSatuanKerjaId           =  $this->input->post("reqSatuanKerjaId");
        
        $reqTanggalKegiatan         =  $this->input->post("reqTanggalKegiatan");
        $reqTanggalKegiatanAkhir    =  $this->input->post("reqTanggalKegiatanAkhir");
        $reqJamKegiatan             =  $this->input->post("reqJamKegiatan");
        $reqJamKegiatanAkhir        =  $this->input->post("reqJamKegiatanAkhir");
        $reqIsEmail                 =  $this->input->post("reqIsEmail");
        $reqIsMeeting               =  $this->input->post("reqIsMeeting");
        $reqRevisi                  =  $this->input->post("reqRevisi");
        $reqPrioritasSuratId        =  $this->input->post("reqPrioritasSuratId");
        $reqPermohonanNomorId       =  $this->input->post("reqPermohonanNomorId");
        $reqArsip                   =  $this->input->post("reqArsip");
        $reqArsipId                 =  $this->input->post("reqArsipId");
        $reqJenisTTD                =  $this->input->post("reqJenisTTD");
        
        $reqLinkFileNaskah          = $_FILES["reqLinkFileNaskah"];
        $reqLinkFileNaskahTemp      =  $this->input->post("reqLinkFileNaskahTemp");

        
        $reqTarget                  =  $this->input->post("reqTarget");
        if($reqTarget == ""){
            $reqTarget = "INTERNAL";
        }
        
        if($reqJenisTTD == "BASAH" && $reqStatusSurat == "POSTING")
        {
            if($reqMode == "insert")
            {
                $this->response(array('status' => 'success', 'code' => 200, 'message' => "0-Simpan sebagai DRAFT terlebih dahulu untuk generate Naskah."));
                return;
            }
        }
        
        
        if(count($reqSatuanKerjaIdTujuan) == 0)
        {
            $this->response(array('status' => 'success', 'code' => 200, 'message' => "0-Tujuan surat belum ditentukan."));    
            return;
        }
        if(trim($reqPerihal) == "")
        {
            $this->response(array('status' => 'success', 'code' => 200, 'message' => "0-Judul surat belum diisi."));  
            return;
        }
        
        $reqTanggalKeg = "NULL";
        $reqTanggalKegAkhir = "NULL";
        if($reqIsMeeting == "Y")
        {
            if($reqTanggalKegiatan == "")
            {
                $reqTanggalKeg = "NULL";
                $reqTanggalKegAkhir = "NULL";
            }
            else
            {
                if($reqJamKegiatan == "")
                    $reqTanggalKeg = "TO_TIMESTAMP('".$reqTanggalKegiatan."', 'DD-MM-YYYY')";
                else
                    $reqTanggalKeg = "TO_TIMESTAMP('".$reqTanggalKegiatan." ".$reqJamKegiatan."', 'DD-MM-YYYY HH24:MI')";
                
                if($reqTanggalKegiatanAkhir == "")
                {
                    $reqTanggalKegAkhir = "NULL";
                }
                else
                {
                    if($reqJamKegiatanAkhir == "")
                        $reqTanggalKegAkhir = "TO_TIMESTAMP('".$reqTanggalKegiatanAkhir."', 'DD-MM-YYYY')";
                    else
                        $reqTanggalKegAkhir = "TO_TIMESTAMP('".$reqTanggalKegiatanAkhir." ".$reqJamKegiatanAkhir."', 'DD-MM-YYYY HH24:MI')";
                }
                
            }
        }
        
        $surat_masuk->setField("TANGGAL_KEGIATAN", $reqTanggalKeg);
        $surat_masuk->setField("TANGGAL_KEGIATAN_AKHIR", $reqTanggalKegAkhir);
        $surat_masuk->setField("IS_MEETING", $reqIsMeeting);
        $surat_masuk->setField("IS_EMAIL", $reqIsEmail);
        $surat_masuk->setField("PRIORITAS_SURAT_ID", $reqPrioritasSuratId);
        $surat_masuk->setField("ARSIP_ID", $reqArsipId);
        $surat_masuk->setField("ARSIP", $reqArsip);
        $surat_masuk->setField("JENIS_TTD", $reqJenisTTD);
        
        
        if($userMobile->USER_GROUP == "SEKRETARIS"){
            $surat_masuk->setField("PENERIMA_SURAT", $userMobile->SATUAN_KERJA_ID_ASAL);
        }
        elseif($userMobile->USER_GROUP == "TATAUSAHA"){
            $surat_masuk->setField("PENERIMA_SURAT", $userMobile->CABANG_ID);
        }
        
        $surat_masuk->setField("PERMOHONAN_NOMOR_ID", $reqPermohonanNomorId);
        $surat_masuk->setField("PENYAMPAIAN_SURAT", $reqPenyampaianSurat);
        $surat_masuk->setField("JENIS_TUJUAN", $reqJenisTujuan);
        $surat_masuk->setField("SURAT_MASUK_REF_ID", $reqIdRef);
        $surat_masuk->setField("SURAT_MASUK_ID", $reqId);
        $surat_masuk->setField("NO_AGENDA", $reqNoAgenda);
        $surat_masuk->setField("LOKASI_SIMPAN", $reqLokasiSurat);
        $surat_masuk->setField("NOMOR", $reqNoSurat);
        $surat_masuk->setField("TANGGAL", "CURRENT_DATE");//dateToDbCheck($reqTanggal));
        $surat_masuk->setField("JENIS_NASKAH_ID", $reqJenisNaskah);
        $surat_masuk->setField("JENIS_NASKAH_LEVEL", $reqKdLevel);
        $surat_masuk->setField("SIFAT_NASKAH", $reqSifatNaskah); 
        $surat_masuk->setField("STATUS_SURAT", $reqStatusSurat);
        $surat_masuk->setField("PERIHAL", $reqPerihal);
        $surat_masuk->setField("KLASIFIKASI_ID", $reqKlasifikasiId);
        $surat_masuk->setField("SATUAN_KERJA_ID_ASAL", $reqSatuanKerjaId);
        $surat_masuk->setField("INSTANSI_ASAL", $reqAsalSuratInstansi);
        $surat_masuk->setField("ALAMAT_ASAL", $reqAsalSuratAlamat);
        $surat_masuk->setField("KOTA_ASAL", $reqAsalSuratKota);
        $surat_masuk->setField("KETERANGAN_ASAL", $reqAsalSuratNama);
        $surat_masuk->setField("ISI", str_replace("'", "&quot;", $reqKeterangan));
        $surat_masuk->setField("CATATAN", "");
        $surat_masuk->setField("USER_ID", $userMobile->ID);
        $surat_masuk->setField("NAMA_USER", $userMobile->NAMA);
        $surat_masuk->setField("CABANG_ID", $userMobile->CABANG_ID);
        $surat_masuk->setField("TARGET", $reqTarget);
        
        
        $reqTanggalKegiatan      =  $this->input->post("reqTanggalKegiatan");
        $reqTanggalKegiatanAkhir =  $this->input->post("reqTanggalKegiatanAkhir");
        $reqJamKegiatan          =  $this->input->post("reqJamKegiatan");
        $reqJamKegiatanAkhir     =  $this->input->post("reqJamKegiatanAkhir");
        $reqIsEmail       =  $this->input->post("reqIsEmail");
        $reqIsMeeting     =  $this->input->post("reqIsMeeting");
        
        if($reqMode == "insert")
        {
            $surat_masuk->setField("LAST_CREATE_USER", $userMobile->ID);
            $surat_masuk->insert();
            $reqId = $surat_masuk->id;
        }
        else
        {
            $surat_masuk->setField("LAST_UPDATE_USER", $userMobile->ID);
            $surat_masuk->update();
        }
        
        
        if($reqTarget == "INTERNAL")
        {
            if($reqJenisTTD == "BASAH" && $reqStatusSurat == "POSTING")
            {
                /* CEK APAKAH PEMBUAT / SEKRETARIS NYA */
                $surat_masuk_asal = new SuratMasuk();
                $pemilikSurat = $surat_masuk_asal->getPemilikSurat(array("SURAT_MASUK_ID" => $reqId));
                
                if($userMobile->ID == $pemilikSurat || $userMobile->ID_ATASAN == $pemilikSurat)
                {
                    
                    if($reqLinkFileNaskah["name"] == "" && $reqLinkFileNaskahTemp == "")
                    {
                        $this->response(array('status' => 'success', 'code' => 200, 'message' => "0-Upload naskah yang sudah ditandatangani terlebih dahulu."));
                        return;
                    }
                    else
                    {
        
        
                        /* WAJIB UNTUK UPLOAD DATA */
                        $this->load->library("FileHandler");
                        $file = new FileHandler();
                        $FILE_DIR= "uploads/".$reqId."/";
                        makedirs($FILE_DIR);
                        
                        $reqLinkFileNaskah      = $_FILES["reqLinkFileNaskah"];
                        $reqLinkFileNaskahTemp  =  $this->input->post("reqLinkFileNaskahTemp");
                
                
                        $reqJenis = "NASKAHTTD".generateZero($reqId, 5);
                        $renameFileNaskah = $reqJenis.date("Ymdhis").rand().".".getExtension($reqLinkFileNaskah['name']);
                        
                        if($file->uploadToDir('reqLinkFileNaskah', $FILE_DIR, $renameFileNaskah))
                            $insertLinkFileNaskah =  $renameFileNaskah;
                        else
                            $insertLinkFileNaskah =  $reqLinkFileNaskahTemp;                                
                            
                        /*  UPDATE KE SURAT_PDF */  
                        $surat_pdf = new SuratMasuk();
                        $surat_pdf->setField("FIELD", "SURAT_PDF");
                        $surat_pdf->setField("FIELD_VALUE", $insertLinkFileNaskah);
                        $surat_pdf->setField("LAST_UPDATE_USER", $userMobile->ID);
                        $surat_pdf->setField("SURAT_MASUK_ID", $reqId);
                        $surat_pdf->updateByField();
                        
                    }
                }   
            }
        }
        
        
        /* JIKA ADA PERMOHONAN NYA MAKA UPDATE NOMORNYA */  
        if($reqPermohonanNomorId == "")
        {}
        else
        {   
            $this->load->model("PermohonanNomor");
            $permohonan_nomor = new PermohonanNomor();
    
            //echo $reqMode;
            $permohonan_nomor->setField("FIELD", "SURAT_MASUK_ID");
            $permohonan_nomor->setField("FIELD_VALUE", $reqId);
            $permohonan_nomor->setField("PERMOHONAN_NOMOR_ID", $reqPermohonanNomorId);
            $permohonan_nomor->updateByField();
            
        }
        
        /* WAJIB UNTUK UPLOAD DATA */
        $this->load->library("FileHandler");
        $file = new FileHandler();
        $FILE_DIR= "uploads/".$reqId."/";
        makedirs($FILE_DIR);
        
        $reqLinkFile = $_FILES["reqLinkFile"];
        $reqLinkFileTempSize    =  $this->input->post("reqLinkFileTempSize");
        $reqLinkFileTempTipe    =  $this->input->post("reqLinkFileTempTipe");
        $reqLinkFileTemp        =  $this->input->post("reqLinkFileTemp");
        $reqLinkFileTempNama    =  $this->input->post("reqLinkFileTempNama");


        $surat_masuk_attachement = new SuratMasuk();
        $surat_masuk_attachement->setField("SURAT_MASUK_ID", $reqId);
        $surat_masuk_attachement->deleteAttachment();


        $reqJenis = $reqJenisTujuan.generateZero($reqId, 5);
        for($i=0;$i<count($reqLinkFile);$i++)
        {
            $renameFile = $reqJenis.date("Ymdhis").rand().".".getExtension($reqLinkFile['name'][$i]);
        
            if($file->uploadToDirArray('reqLinkFile', $FILE_DIR, $renameFile, $i))
            {   
                $insertLinkSize = $file->uploadedSize;
                $insertLinkTipe =  $file->uploadedExtension;
                $insertLinkFile =  $renameFile;
                
                if($insertLinkFile == "")
                {}
                else
                {
                    $surat_masuk_attachement = new SuratMasuk();
                    $surat_masuk_attachement->setField("SURAT_MASUK_ID", $reqId);
                    $surat_masuk_attachement->setField("ATTACHMENT", $renameFile);
                    $surat_masuk_attachement->setField("UKURAN", $insertLinkSize);
                    $surat_masuk_attachement->setField("TIPE", $insertLinkTipe);
                    $surat_masuk_attachement->setField("NAMA", $reqLinkFile['name'][$i]);
                    $surat_masuk_attachement->setField("LAST_CREATE_USER", $userMobile->ID);
                    $surat_masuk_attachement->insertAttachment();
                }
            }
            
        }

        for($i=0;$i<count($reqLinkFileTemp);$i++)
        { 
            $insertLinkSize = $reqLinkFileTempSize[$i];
            $insertLinkTipe =  $reqLinkFileTempTipe[$i];
            $insertLinkFile =  $reqLinkFileTemp[$i];
            $insertLinkNama =  $reqLinkFileTempNama[$i];
            
            if($insertLinkFile == "")
            {}
            else
            {
                $surat_masuk_attachement = new SuratMasuk();
                $surat_masuk_attachement->setField("SURAT_MASUK_ID", $reqId);
                $surat_masuk_attachement->setField("ATTACHMENT", $insertLinkFile);
                $surat_masuk_attachement->setField("UKURAN", $insertLinkSize);
                $surat_masuk_attachement->setField("TIPE", $insertLinkTipe);
                $surat_masuk_attachement->setField("NAMA", $insertLinkNama);
                $surat_masuk_attachement->setField("LAST_CREATE_USER", $userMobile->ID);
                $surat_masuk_attachement->insertAttachment();
            }
            
        }


        $disposisi = new Disposisi();
        $disposisi->setField("SURAT_MASUK_ID", $reqId);
        $disposisi->setField("LAST_CREATE_USER", $userMobile->ID);
        $disposisi->deleteParent();
        
        
        $disposisi_kelompok = new DisposisiKelompok();
        $disposisi_kelompok->setField("SURAT_MASUK_ID", $reqId);
        $disposisi_kelompok->setField("LAST_CREATE_USER", $userMobile->ID);
        $disposisi_kelompok->deleteParent();
        
        for($i=0;$i<count($reqSatuanKerjaIdTujuan);$i++)
        {
            if($reqSatuanKerjaIdTujuan[$i] == "")
            {}
            else
            {
                /* JIKA TUJUAN KELOMPOK TAMPUNG SAJA DI DISPOSISI KELOMPOK */
                if(stristr($reqSatuanKerjaIdTujuan[$i], "KELOMPOK"))
                {
                    $disposisi_kelompok = new DisposisiKelompok();
                    $disposisi_kelompok->setField("SURAT_MASUK_ID", $reqId);
                    $disposisi_kelompok->setField("SATUAN_KERJA_ID_ASAL", $reqSatuanKerjaId);
                    $disposisi_kelompok->setField("SATUAN_KERJA_KELOMPOK_ID", str_replace("KELOMPOK", "", $reqSatuanKerjaIdTujuan[$i]));
                    $disposisi_kelompok->setField("STATUS_DISPOSISI", "TUJUAN");
                    $disposisi_kelompok->setField("LAST_CREATE_USER", $userMobile->ID);
                    $disposisi_kelompok->insert();
                }
                else
                {
                    $disposisi = new Disposisi();
                    $disposisi->setField("SURAT_MASUK_ID", $reqId);
                    $disposisi->setField("SATUAN_KERJA_ID_ASAL", $reqSatuanKerjaId);
                    $disposisi->setField("SATUAN_KERJA_ID_TUJUAN", $reqSatuanKerjaIdTujuan[$i]);
                    $disposisi->setField("STATUS_DISPOSISI", "TUJUAN");
                    $disposisi->setField("LAST_CREATE_USER", $userMobile->ID);
                    $disposisi->insert();
                }
            }
        }
        
        for($i=0;$i<count($reqSatuanKerjaIdTembusan);$i++)
        {
            if($reqSatuanKerjaIdTembusan[$i] == "")
            {}
            else
            {
                
                /* JIKA TUJUAN KELOMPOK TAMPUNG SAJA DI DISPOSISI KELOMPOK */
                if(stristr($reqSatuanKerjaIdTembusan[$i], "KELOMPOK"))
                {
                    $disposisi_kelompok = new DisposisiKelompok();
                    $disposisi_kelompok->setField("SURAT_MASUK_ID", $reqId);
                    $disposisi_kelompok->setField("SATUAN_KERJA_ID_ASAL", $reqSatuanKerjaId);
                    $disposisi_kelompok->setField("SATUAN_KERJA_KELOMPOK_ID", str_replace("KELOMPOK", "", $reqSatuanKerjaIdTembusan[$i]));
                    $disposisi_kelompok->setField("STATUS_DISPOSISI", "TEMBUSAN");
                    $disposisi_kelompok->setField("LAST_CREATE_USER", $userMobile->ID);
                    $disposisi_kelompok->insert();
                }
                else
                {
                    $disposisi = new Disposisi();
                    $disposisi->setField("SURAT_MASUK_ID", $reqId);
                    $disposisi->setField("SATUAN_KERJA_ID_ASAL", $reqSatuanKerjaId);
                    $disposisi->setField("SATUAN_KERJA_ID_TUJUAN", $reqSatuanKerjaIdTembusan[$i]);
                    $disposisi->setField("STATUS_DISPOSISI", "TEMBUSAN");
                    $disposisi->setField("LAST_CREATE_USER", $userMobile->ID);
                    $disposisi->insert();
                }
            }
        }
        
        $this->load->model("SuratMasukParaf");
        $surat_masuk_paraf = new SuratMasukParaf();
        $surat_masuk_paraf->setField("SURAT_MASUK_ID", $reqId);
        $surat_masuk_paraf->setField("LAST_CREATE_USER", $userMobile->ID);
        $surat_masuk_paraf->deleteParent();
        
        for($i=0;$i<count($reqSatuanKerjaIdParaf);$i++)
        {
            if($reqSatuanKerjaIdParaf[$i] == "")
            {}
            else
            {
                $surat_masuk_paraf = new SuratMasukParaf();
                
                $adaData = $surat_masuk_paraf->getCountByParams(array("SURAT_MASUK_ID" => $reqId, "SATUAN_KERJA_ID_TUJUAN" => $reqSatuanKerjaIdParaf[$i]));
                
                if($adaData == 0)
                {
                    $surat_masuk_paraf->setField("SURAT_MASUK_ID", $reqId);
                    $surat_masuk_paraf->setField("SATUAN_KERJA_ID_TUJUAN", $reqSatuanKerjaIdParaf[$i]);
                    $surat_masuk_paraf->setField("LAST_CREATE_USER", $userMobile->ID);
                    $surat_masuk_paraf->insert();
                }
                
            }
        }
        
        if($reqStatusSurat == "DRAFT")
        {
             $this->response(array('status' => 'success', 'code' => 200, 'message' => $reqId."-Naskah berhasil disimpan sebagai DRAFT."));
            return;
        }
        elseif($reqStatusSurat == "VALIDASI")
        {
             $this->response(array('status' => 'success', 'code' => 200, 'message' => $reqId."-Naskah berhasil disimpan sebagai DRAFT."));
            return;
        }
        elseif($reqStatusSurat == "REVISI")
        {
            $surat_masuk->setField("SURAT_MASUK_ID", $reqId);
            $surat_masuk->setField("REVISI", $reqRevisi);
            $surat_masuk->setField("SATUAN_KERJA_ID_ASAL", $reqSatuanKerjaId);
            $surat_masuk->setField("REVISI_BY", $userMobile->USERNAME);
            if($surat_masuk->revisi())
            {
                $this->revisi_notifikasi($reqId);   
                 $this->response(array('status' => 'success', 'code' => 200, 'message' => "Naskah telah dikembalikan ke pembuat surat.")); 
                return;
            }
        }
        
        /* CEK DULU AKSES SURAT */
        $surat_akses = new SuratMasuk();
        $aksesSurat = $surat_akses->getAksesSurat(array("A.SURAT_MASUK_ID" => $reqId, "A.USER_ID" => $userMobile->ID));
        
        if($aksesSurat == "PEMARAF")
        {
            $this->paraf_proses($reqId, "APPROVAL");    
            return;
        }
        
        $this->response(array('status' => 'success', 'code' => 200, 'message' => $surat_masuk));
        // $this->response(array('status' => 'success', 'code' => 200, 'message' => $aksesSurat));
        /* JIKA BUKAN DRAFT YANG HANDLE ADALAH POSTING_PROSES */
        $this->posting_proses($reqId);
    }
 
    // update data entitas
    function index_put() {

    }
 
    // delete entitas
    function index_delete() {

    }
 
}