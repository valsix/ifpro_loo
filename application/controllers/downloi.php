<?php

defined('BASEPATH') OR exit('No direct script access allowed');

include_once("functions/string.func.php");

class Downloi extends CI_Controller {

	function __construct() {
		parent::__construct();
	}

    function getappexe($tipe)
    {
        switch ($tipe) {
          case "pdf": $ctype="application/pdf"; break;
          case "exe": $ctype="application/octet-stream"; break;
          case "zip": $ctype="application/zip"; break;
          case "doc": $ctype="application/msword"; break;
          case "xls": $ctype="application/vnd.ms-excel"; break;
          case "ppt": $ctype="application/vnd.ms-powerpoint"; break;
          case "gif": $ctype="image/gif"; break;
          case "png": $ctype="image/png"; break;
          case "jpeg": $ctype="image/jpeg"; break;
          case "jpg": $ctype="image/jpg"; break;
          case "xlsx": $ctype="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"; break;
          case "docx": $ctype="application/vnd.openxmlformats-officedocument.wordprocessingml.document"; break;
          default: $ctype="application/force-download";
      } 

      return $ctype;
    }
	
	public function index()
	{
        $this->load->model("TrLoo");
        $this->load->model("TrLoi");
        $this->load->model("TrPsm");
		$this->load->model("LokasiLoo");

        $reqTemplate = $this->input->get("reqMode");
        $reqAttachId = $this->input->get("reqAttachId");

        $vfolder= "";
        if($reqTemplate == "loo")
        {
            $vfolder= "uploadsloo";
            $vfield= "TR_LOO_ID";
            $set= new TrLoo();
            $statement= " AND A.TR_LOO_ATTACHMENT_ID = ".(int)$reqAttachId;
        }
        else if($reqTemplate == "loi")
        {
            $vfolder= "uploadsloi";
            $vfield= "TR_LOI_ID";
            $set= new TrLoi();
            $statement= " AND A.TR_LOI_ATTACHMENT_ID = ".(int)$reqAttachId;
        }
        else if($reqTemplate == "psm")
        {
            $vfolder= "uploadspsm";
            $vfield= "TR_PSM_ID";
            $set= new TrPsm();
            $statement= " AND A.TR_PSM_ATTACHMENT_ID = ".(int)$reqAttachId;
        }
        else if($reqTemplate == "lokasiloo")
        {
            $vfolder= "uploadslokasiloo";
            $vfield= "LOKASI_LOO_ID";
            $set= new LokasiLoo();
            $statement= " AND A.LOKASI_LOO_ATTACHMENT_ID = ".(int)$reqAttachId;
        }

        if(empty($vfolder))
        {
            exit;
        }
        
        $set->selectByParamsAttachment(array(), -1, -1, $statement);
        $set->firstRow();

        $atttipe= $set->getField("TIPE");
        $reqId= $set->getField($vfield);
        // echo $infotipe;exit;
        $infotipe= $this->getappexe($atttipe);
        // echo $atttipe;exit;

        $link= $vfolder."/".$reqId."/".$set->getField("ATTACHMENT");
        $nama= $set->getField("NAMA");
        // echo $link;exit;

        $arrexcept= [];
        $arrexcept= array("xlsx", "xls", "doc", "docx", "ppt", "pptx", "txt");

        if(in_array(strtolower($atttipe), $arrexcept))
        {
            ob_clean();
            ob_end_flush();
            header("Cache-Control: no-cache, must-revalidate");
            header('Content-Type: '.$infotipe.';\n');
            header("Content-Transfer-Encoding: Binary");
            header("Content-Disposition: attachment; filename=\"".basename($nama)."\"");
            readfile($link);
        }
        else
        {
        // if (file_exists($link)) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename='.basename($nama));
            header('Content-Transfer-Encoding: binary');
            header('Expires: 0');
            header('Cache-Control: private');
            header('Pragma: private');
            header('Content-Length: '.filesize($link));
            ob_clean();
            flush();
            readfile($link);
        }
        exit;

	}

	public function cetak_agenda()
	{
		$this->load->library('CetakAgendaPDF');
		$this->load->model("SuratMasuk");

		$reqId = $this->input->get("reqId");
        $reqRowId = $this->input->get("reqRowId");
		$reqMode = $this->input->get("reqMode");
		$reqNamaDok = $this->input->get("reqNamaDok");

		$report = new CetakAgendaPDF();
		$docPDF = $report->generate($reqId, $reqRowId, $reqMode, $reqNamaDok);
		// echo $docPDF;exit();

        $link= "uploads/" . $reqId . "/" . $docPDF;

        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename='.basename($link));
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: private');
        header('Pragma: private');
        header('Content-Length: ' . filesize($link));
        ob_clean();
        flush();
        readfile($link);
        
        exit;
	}	

    public function cetak_riwayat()
    {
        $this->load->library('CetakRiwayatPDF');

        $reqId = $this->input->get("reqId");
        $reqRowId = $this->input->get("reqRowId");
        $reqMode = $this->input->get("reqMode");
        // $reqNamaDok = $this->input->get("reqNamaDok");
        $report = new CetakRiwayatPDF();
        $docPDF = $report->generate($reqId, $reqRowId, $reqMode);
        // echo $docPDF;exit();

        $link= "uploads/" . $reqId . "/" . $docPDF;

        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename='.basename($link));
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: private');
        header('Pragma: private');
        header('Content-Length: ' . filesize($link));
        ob_clean();
        flush();
        readfile($link);
        
        exit;
    }
		
}

