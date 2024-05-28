<?
/* INCLUDE FILE */
include_once("functions/PageNumber.php");
include_once("functions/date.func.php");
include_once("functions/string.func.php");
include_once("functions/default.func.php");
include_once("functions/recordcoloring.func.php");

$this->load->model('Rekapitulasi');
$this->load->model('Cabang');

require_once "excel/class.writeexcel_workbookbig.inc.php";
require_once "excel/class.writeexcel_worksheet.inc.php";

//set_time_limit(3);
ini_set("memory_limit","500M");
ini_set('max_execution_time', 520);

$fname = tempnam("/tmp", "rekapitulasi_uang_makan_cetak.xls");
$workbook = & new writeexcel_workbookbig($fname);
$worksheet = &$workbook->addworksheet();

$rekapitulasi = new Rekapitulasi();
$cabang = new Cabang();

$reqBulan = $this->input->get("reqBulan");
$reqTahun = $this->input->get("reqTahun");
$reqDepartemen = $this->input->get("reqDepartemen");

$periode = $reqBulan.$reqTahun;

$day = maxHariPeriode($reqPeriode);
$nama_bulan = getSelectFormattedDate($reqBulan);

/* FILTER DEPARTEMEN */
$arrDepartemen = explode("-", $reqDepartemen);
$reqCabangId = $arrDepartemen[0];
$reqDepartemenId = $arrDepartemen[1];
$reqSubDepartemenId = $arrDepartemen[2];

if(trim($reqCabangId) !== '')
	$statement .= " AND A.CABANG_ID LIKE '".$reqCabangId."%'";
if (trim($reqDepartemenId) !== '')
	$statement .= " AND A.DEPARTEMEN_ID LIKE '".$reqDepartemenId."%'";
if (trim($reqSubDepartemenId) !== "")
	$statement .= " AND A.SUB_DEPARTEMEN_ID LIKE '".$reqSubDepartemenId."%' ";

$rekapitulasi->selectByParamsUangMakan($periode, array(), -1, -1, $statement, " ORDER BY A.NAMA ASC");
$cabang->selectByParams(array("CABANG_ID" => $reqCabangId), -1, -1);
$cabang->firstRow();
//echo $rekapitulasi->query;
//exit;

$worksheet->set_column(0, 0, 8.43);
$worksheet->set_column(1, 1, 7.00);
$worksheet->set_column(2, 2, 15.00);
$worksheet->set_column(3, 3, 35.00);
$worksheet->set_column(4, 4, 40.00);
$worksheet->set_column(5, 5, 20.00);
$worksheet->set_column(6, 6, 20.00);

$tanggal =& $workbook->addformat(array(num_format => ' dd mmmm yyy'));

$text_format =& $workbook->addformat(array( size => 10, font => 'Arial Narrow'));
$text_format_num =& $workbook->addformat(array( num_format => '###', size => 10, font => 'Arial Narrow', align => 'left'));

$text_format_left_none =& $workbook->addformat(array( size => 10, font => 'Arial Narrow'));
$text_format_left_none->set_color('black');

$text_format_merge =& $workbook->addformat(array(size => 8, font => 'Arial Narrow'));
$text_format_merge->set_color('black');
$text_format_merge->set_size(8);
$text_format_merge->set_border_color('black');
$text_format_merge->set_merge(1);
$text_format_merge->set_bold(1);

$text_format_merge_line_bold =& $workbook->addformat(array(size => 8, font => 'Arial Narrow'));
$text_format_merge_line_bold->set_color('black');
$text_format_merge_line_bold->set_size(8);
$text_format_merge_line_bold->set_border_color('black');
$text_format_merge_line_bold->set_merge(1);
$text_format_merge_line_bold->set_bold(1);
$text_format_merge_line_bold->set_left(1);
$text_format_merge_line_bold->set_right(1);
$text_format_merge_line_bold->set_top(1);
$text_format_merge_line_bold->set_bottom(1);

$text_format_merge_none =& $workbook->addformat(array(size => 8, font => 'Arial Narrow'));
$text_format_merge_none->set_color('black');
$text_format_merge_none->set_size(8);
$text_format_merge_none->set_border_color('black');
$text_format_merge_none->set_merge(1);

$text_format =& $workbook->addformat(array(align => 'left', size => 8, font => 'Arial Narrow'));
$text_format->set_color('black');
$text_format->set_size(8);
$text_format->set_bold(1);
$text_format->set_border_color('black');

$text_format_center =& $workbook->addformat(array(align => 'center', size => 8, font => 'Arial Narrow'));
$text_format_center->set_color('black');
$text_format_center->set_size(8);
$text_format_center->set_border_color('black');

$text_format_line =& $workbook->addformat(array(align => 'center', size => 8, font => 'Arial Narrow'));
$text_format_line->set_color('black');
$text_format_line->set_size(8);
$text_format_line->set_border_color('black');
$text_format_line->set_left(1);
$text_format_line->set_right(1);
$text_format_line->set_top(1);
$text_format_line->set_bottom(1);

$text_format_line_left =& $workbook->addformat(array(align => 'left', size => 8, font => 'Arial Narrow'));
$text_format_line_left->set_color('black');
$text_format_line_left->set_size(8);
$text_format_line_left->set_border_color('black');
$text_format_line_left->set_left(1);
$text_format_line_left->set_right(1);
$text_format_line_left->set_top(1);
$text_format_line_left->set_bottom(1);

$text_format_line_bold =& $workbook->addformat(array(align => 'center', size => 8, font => 'Arial Narrow'));
$text_format_line_bold->set_color('black');
$text_format_line_bold->set_size(8);
$text_format_line_bold->set_border_color('black');
$text_format_line_bold->set_bold(1);
$text_format_line_bold->set_left(1);
$text_format_line_bold->set_right(1);
$text_format_line_bold->set_top(1);
$text_format_line_bold->set_bottom(1);

$text_format_wrapping =& $workbook->addformat(array( size => 8, font => 'Arial Narrow'));
$text_format_wrapping->set_text_wrap();
$text_format_wrapping->set_color('black');
$text_format_wrapping->set_size(8);
$text_format_wrapping->set_border_color('black');
$text_format_wrapping->set_left(1);
$text_format_wrapping->set_right(1);
$text_format_wrapping->set_top(1);

$uang =& $workbook->addformat(array(num_format => '#,##0', size => 8, font => 'Arial Narrow'));
$uang->set_color('black');
$uang->set_size(8);
$uang->set_border_color('black');

$uang_line =& $workbook->addformat(array(num_format => '#,##0', size => 8, font => 'Arial Narrow'));
$uang_line->set_color('black');
$uang_line->set_size(8);
$uang_line->set_border_color('black');
$uang_line->set_left(1);
$uang_line->set_right(1);
$uang_line->set_top(1);
$uang_line->set_bottom(1);

$worksheet->insert_bitmap('B1', 'images/logo_cetak.bmp', 5, 5);
$worksheet->write(1, 1, "REKAPITULASI UANG MAKAN", $text_format_merge);
for($i=2; $i<=6; $i++)
{
	$worksheet->write_blank(1, $i, $text_format_merge);
}

$worksheet->write(2, 1, "(PERIODE 1 - ".$day." ".$nama_bulan." ".$reqTahun." )", $text_format_merge);
for($i=2; $i<=6; $i++)
{
	$worksheet->write_blank(2, $i, $text_format_merge);
}

$worksheet->write(3, 1, "UNIT KERJA : ".$cabang->getField('NAMA')."", $text_format_merge);
for($i=2; $i<=6; $i++)
{
	$worksheet->write_blank(3, $i, $text_format_merge);
}

$worksheet->write(5, 1, "NO", $text_format_line_bold);
$worksheet->write(5, 2, "NID", $text_format_line_bold);
$worksheet->write(5, 3, "NAMA", $text_format_line_bold);
$worksheet->write(5, 4, "JABATAN", $text_format_line_bold);
$worksheet->write(5, 5, "JATAH UANG MAKAN", $text_format_line_bold);
// $worksheet->write(5, 6, "TIDAK DAPAT", $text_format_line_bold);


$row = 6;
$no = 1;
while($rekapitulasi->nextRow())
{
	$worksheet->write($row, 1, $no, $text_format_line_left);
	$worksheet->write($row, 2, $rekapitulasi->getField('PEGAWAI_ID'), $text_format_line_left);
	$worksheet->write($row, 3, $rekapitulasi->getField('NAMA'), $text_format_line_left);
	$worksheet->write($row, 4, $rekapitulasi->getField('JABATAN'), $text_format_line_left);
	$worksheet->write($row, 5, $rekapitulasi->getField('JATAH_MAKAN'), $text_format_line_left);
	// $worksheet->write($row, 6, $rekapitulasi->getField('TIDAK_MAKAN'), $text_format_line_left);
	$row++;
	$no++;
}

$workbook->close();

header("Content-Type: application/x-msexcel; name=\"rekapitulasi_uang_makan_cetak.xls\"");
header("Content-Disposition: inline; filename=\"rekapitulasi_uang_makan_cetak.xls\"");
$fh=fopen($fname, "rb");
fpassthru($fh);
unlink($fname);
?>