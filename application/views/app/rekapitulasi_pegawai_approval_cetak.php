<?
/* INCLUDE FILE */
include_once("functions/PageNumber.php");
include_once("functions/date.func.php");
include_once("functions/string.func.php");
include_once("functions/default.func.php");
include_once("functions/recordcoloring.func.php");

$this->load->model('Pegawai');

require_once "excel/class.writeexcel_workbookbig.inc.php";
require_once "excel/class.writeexcel_worksheet.inc.php";

//set_time_limit(3);
ini_set("memory_limit","500M");
ini_set('max_execution_time', 520);

$fname = tempnam("/tmp", "rekapitulasi_pegawai_approval_cetak.xls");
$workbook = & new writeexcel_workbookbig($fname);
$worksheet = &$workbook->addworksheet();

$pegawai = new Pegawai();

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

$pegawai->selectByParamsPegawaiApproval(array(), -1, -1, $statement, $periode, " ORDER BY A.NAMA ASC");
//echo $pegawai->query;
//exit;

$worksheet->set_column(0, 0, 8.43);
$worksheet->set_column(1, 1, 7.00);
$worksheet->set_column(2, 2, 15.00);
$worksheet->set_column(3, 3, 35.00);
$worksheet->set_column(4, 4, 30.00);
$worksheet->set_column(5, 5, 35.00);
$worksheet->set_column(6, 6, 35.00);

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

$worksheet->write(1, 1, "REKAPITULASI PEGAWAI APPROVAL", $text_format_merge);

$worksheet->write(3, 1, "NO", $text_format_line_bold);
$worksheet->write(3, 2, "NID", $text_format_line_bold);
$worksheet->write(3, 3, "NAMA", $text_format_line_bold);
$worksheet->write(3, 4, "JABATAN", $text_format_line_bold);
$worksheet->write(3, 5, "APPROVAL 1", $text_format_line_bold);
$worksheet->write(3, 6, "APPROVAL 2", $text_format_line_bold);


$row = 4;
$no = 1;
while($pegawai->nextRow())
{
	$worksheet->write($row, 1, $no, $text_format_line_left);
	$worksheet->write($row, 2, $pegawai->getField('PEGAWAI_ID'), $text_format_line_left);
	$worksheet->write($row, 3, $pegawai->getField('NAMA'), $text_format_line_left);
	$worksheet->write($row, 4, $pegawai->getField('JABATAN'), $text_format_line_left);
	$worksheet->write($row, 5, $pegawai->getField('APPROVAL1'), $text_format_line_left);
	$worksheet->write($row, 6, $pegawai->getField('APPROVAL2'), $text_format_line_left);
	$row++;
	$no++;
}

$workbook->close();

header("Content-Type: application/x-msexcel; name=\"rekapitulasi_pegawai_approval_cetak.xls\"");
header("Content-Disposition: inline; filename=\"rekapitulasi_pegawai_approval_cetak.xls\"");
$fh=fopen($fname, "rb");
fpassthru($fh);
unlink($fname);
?>