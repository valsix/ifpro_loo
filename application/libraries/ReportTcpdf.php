<?php



include_once("libraries/phpqrcode/qrlib.php");
include_once("lib/vendor/autoload.php");


include_once("functions/string.func.php");
include_once("functions/date.func.php");
include_once("lib/TCPDF/tcpdf.php");
	
class MYPDF extends TCPDF {
	 protected $last_page_flag = false;
	 public function Close() {
    		$this->last_page_flag = true;
    		parent::Close();
  	 }
  	 public function Header() {
           $html= '<div style="text-align: center;">
                <img src="images/logo-teluk-lamong.png" style="height: 70px;">
            </div>';
        $this->writeHTMLCell(
         

            $w = 0, $h = 0, $x = '', $y = '',''
           , $border = 0, $ln = 0, $fill = 0,
            $reseth = true, $align = 'top', $autopadding = true);
    }
    // Page footer
    // public function Footer() {
    //     // Position at 15 mm from bottom
    //     $this->SetY(-25);
    //     if ($this->last_page_flag) {}
    //     else
    //     {
    //         $this->SetLineStyle(array('width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0));
    //         $this->Cell(0,10,'','T','',0,0);
    //     }
    //     $this->SetY(-25);
    //     $this->writeHTMLCell(
    //     $w = 0, $h = 0, $x = '', $y = '',
    //         '
    //         <div style="text-align: center; font-family: sans-serif; font-size: 7pt;">
    //             <div>PT Terminal Teluk Lamong<br>
    //             Jl. Raya Tambak Osowilangun Km. 12 Surabaya<br>
    //             Telepon/Faksimili : (031) 99001500 / (031) 99001490<br>
    //             Website : <span style="text-decoration: underline; color: blue;">www.teluklamong.co.id</span> Email : terminal@teluklamong.co.id 
    //         </div>
    //         ', $border = 0, $ln = 1, $fill = 0,
    //         $reseth = true, $align = 'top', $autopadding = true);
    //     $this->SetY(-10);
    //     $this->Cell(0, 0,  'halaman '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, $ln=0, 'R', 0, '', 0, false, 'B', 'B');
    // }
}
class ReportTcpdf {

	var $reqId;
	var $reqTemplate;
	var $reqJenisReport;

	function generate($reqId='', $reqTemplate='', $reqJenisReport = ""){
		$this->reqId = $reqId;
		$this->reqTemplate = $reqTemplate;
		$this->reqJenisReport = $reqJenisReport;
		$FILE_DIR_TEMPLATE = "uploads/";
		$FILE_DIR 		   = "uploads/" . $this->reqId . "/";

		if (!file_exists($FILE_DIR)) {
				// mkdir($FILE_DIR, 0777, true);
			makedirs($FILE_DIR, 0777, true);
		}


		$CI = &get_instance();
		$CI->load->library("suratmasukinfo");
		$suratmasukinfo = new suratmasukinfo();
		// var_dump($this->reqId);exit;
		$suratmasukinfo->getInfo($this->reqId);
		// var_dump($suratmasukinfo);
			$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

		$pdf->SetCreator(PDF_CREATOR);
		$pdf->AddPage('P', 'A4');
		$saveAs = (generateZero($suratmasukinfo->SURAT_MASUK_ID, 6) . generateZero($suratmasukinfo->SATUAN_KERJA_ID_ASAL, 6));
		$html = file_get_contents($CI->config->item('base_report') . "report/loadUrl/report/" . $this->reqTemplate . "/?reqJenisSurat=INTERNAL&reqId=" . $this->reqId);

		ob_end_clean();
		$pdf->SetFont('Helvetica', '', 10);
		$pdf->writeHTML($html, true, 0, true, true);
			// $pdf->Output($saveAs.'.pdf', 'I');
		$pdf->Output($saveAs.'.pdf', 'F');
		return $saveAs . ".pdf";
	}



	

	

		


}