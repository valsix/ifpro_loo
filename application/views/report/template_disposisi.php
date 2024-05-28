<?
include_once("libraries/vendor/autoload.php");
$this->load->library('DisposisiPDF');
$this->load->library('suratmasukinfo'); $suratmasukinfo = new suratmasukinfo();

$reqId = $this->input->get("reqId");
$reqDisposisiId = $this->input->get("reqDisposisiId");

/* GET AKSES */
$suratmasukinfo->getAkses($reqId, $this->ID);
$aksesSurat = $suratmasukinfo->AKSES;
$linkPDF 	= $suratmasukinfo->PDF;
$templateSurat 	= $suratmasukinfo->TEMPLATE;

if($aksesSurat == "")
{
	echo "Anda tidak mempunyai akses surat.";	
	exit;
}

$report = new DisposisiPDF();
$docPDF = $report->generate($reqId, $reqDisposisiId);


$url = 'uploads/'.$reqId.'/'.$docPDF;

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