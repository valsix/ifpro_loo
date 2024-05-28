<?
include_once("libraries/vendor/autoload.php");
$this->load->library('Report');
$this->load->library('ReportKeluarPDF');
$this->load->library('suratkeluarinfo'); $suratkeluarinfo = new suratkeluarinfo();

$reqId = $this->input->get("reqId");

/* GET AKSES */
$suratkeluarinfo->getAkses($reqId, $this->ID);
$aksesSurat = $suratkeluarinfo->AKSES;
$linkPDF 	= $suratkeluarinfo->PDF;
$templateSurat 	= $suratkeluarinfo->TEMPLATE;

if($aksesSurat == "")
{
	echo "Anda tidak mempunyai akses surat.";	
	exit;
}

if($aksesSurat == "DISPOSISI")
{
	if($linkPDF == "")
	{
		echo "Anda tidak mempunyai akses surat.";	
		exit;
	}
}

if($templateSurat == "")
{
	echo "Template surat belum dibuat.";	
	exit;
}

if($linkPDF == "")
{
	//$report = new Report($reqId, $templateSurat);
	//$docPDF = $report->generate();
	$report = new ReportKeluarPDF($reqId, $templateSurat);
	$docPDF = $report->generate();
	
}
else
	$docPDF = $linkPDF;

$url = 'uploads/eksternal/'.$reqId.'/'.$docPDF;

$content = file_get_contents($url);

header('Content-Type: application/pdf');
header('Content-Length: ' . strlen($content));
header('Content-Disposition: inline; filename="'.$docPDF.'"');
header('Cache-Control: private, max-age=0, must-revalidate');
header('Pragma: public');
ini_set('zlib.output_compression','0');
/**/
die($content);
?>