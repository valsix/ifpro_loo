<?
/* INCLUDE FILE */
include_once("functions/PageNumber.php");
include_once("functions/date.func.php");
include_once("functions/string.func.php");
include_once("functions/default.func.php");
include_once("functions/recordcoloring.func.php");

$this->load->model('AbsensiRekap');

require_once "excel/class.writeexcel_workbookbig.inc.php";
require_once "excel/class.writeexcel_worksheet.inc.php";

//set_time_limit(3);
ini_set("memory_limit","500M");
ini_set('max_execution_time', 520);

$fname = tempnam("/tmp", "rekapitulasi_absensi_per_tanggal_excel.xls");
$workbook = & new writeexcel_workbookbig($fname);
$worksheet = &$workbook->addworksheet();
$worksheet->hide_gridlines();
$absensi_rekap = new AbsensiRekap();

$reqBulan = $this->input->get("reqBulan");
$reqTahun = $this->input->get("reqTahun");
$reqHari = $this->input->get("reqHari");
if($reqHari == "")
	$reqHari = date('d');

$reqDepartemen = $this->input->get("reqDepartemen");

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

$absensi_rekap->selectByParamsRekapDatangCepat(array(), -1, -1, $reqBulan.$reqTahun, (int)$reqHari, generateZero($reqHari, 2), $statement);
//echo $absensi_rekap->query; exit;

$worksheet->set_column(0, 0, 8.43);
$worksheet->set_column(1, 1, 3.71);
$worksheet->set_column(2, 2, 12.00);
$worksheet->set_column(3, 3, 27.00);
$worksheet->set_column(4, 4, 31.00);
$worksheet->set_column(5, 5, 8.00);
$worksheet->set_column(6, 6, 10.00);
$worksheet->set_column(7, 7, 10.00);
$worksheet->set_column(8, 8, 10.00);
$worksheet->set_column(9, 9, 19.00);

$tanggal =& $workbook->addformat(array(num_format => ' dd mmmm yyy'));

$text_format =& $workbook->addformat(array( size => 10, font => 'Arial Narrow'));
$text_format_num =& $workbook->addformat(array( num_format => '###', size => 8, font => 'Arial Narrow', align => 'center'));
$text_format_num->set_left(1);
$text_format_num->set_right(1);
$text_format_num->set_top(1);
$text_format_num->set_bottom(1);

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

//$worksheet->insert_bitmap('B1', 'images/logo_cetak.bmp', 5, 5);
$worksheet->insert_bitmap('B1', 'images/logo_cetak.bmp', 5, 5);

$worksheet->write(1, 1, "DAFTAR ABSENSI PEGAWAI TANGGAL ".$reqHari." ".strtoupper(getNamePeriode($reqBulan.$reqTahun))."", $text_format_merge);
$worksheet->write_blank(1, 2, $text_format_merge);
$worksheet->write_blank(1, 3, $text_format_merge);
$worksheet->write_blank(1, 4, $text_format_merge);
$worksheet->write_blank(1, 5, $text_format_merge);
$worksheet->write_blank(1, 6, $text_format_merge);
$worksheet->write_blank(1, 7, $text_format_merge);
$worksheet->write_blank(1, 8, $text_format_merge);
$worksheet->write_blank(1, 9, $text_format_merge);

$worksheet->write(2, 1, "PT PEMBANGKITAN JAWA BALI SERVICES", $text_format_merge);
$worksheet->write_blank(2, 2, $text_format_merge);
$worksheet->write_blank(2, 3, $text_format_merge);
$worksheet->write_blank(2, 4, $text_format_merge);
$worksheet->write_blank(2, 5, $text_format_merge);
$worksheet->write_blank(2, 6, $text_format_merge);
$worksheet->write_blank(2, 7, $text_format_merge);
$worksheet->write_blank(2, 8, $text_format_merge);
$worksheet->write_blank(2, 9, $text_format_merge);

$worksheet->write(4, 1, "NO", $text_format_line_bold);
$worksheet->write(4, 2, "NID", $text_format_line_bold);
$worksheet->write(4, 3, "NAMA", $text_format_line_bold);
$worksheet->write(4, 4, "CABANG", $text_format_line_bold);
$worksheet->write(4, 5, "TANGGAL", $text_format_line_bold);
$worksheet->write(4, 6, "AWAL TUGAS", $text_format_line_bold);
$worksheet->write(4, 7, "MASUK", $text_format_line_bold);
$worksheet->write(4, 8, "TELAT", $text_format_line_bold);
$worksheet->write(4, 9, "KETERANGAN", $text_format_line_bold);

$row = 5;
$no = 1;
while($absensi_rekap->nextRow())
{

	$worksheet->write($row, 1, $no, $text_format_line);
	$worksheet->write($row, 2, $absensi_rekap->getField("NRP"), $text_format_line);
	$worksheet->write($row, 3, $absensi_rekap->getField("NAMA"), $text_format_line_left);
	$worksheet->write($row, 4, $absensi_rekap->getField("NAMA_CABANG"), $text_format_line_left);
	$worksheet->write($row, 5, $reqHari.$reqPeriode, $text_format_line);
	$worksheet->write($row, 6, $absensi_rekap->getField("AWAL_TUGAS"), $text_format_line);
	$worksheet->write($row, 7, $absensi_rekap->getField("DATANG"), $text_format_line);
	$worksheet->write($row, 8, $absensi_rekap->getField("TELAT"), $text_format_line);
	$worksheet->write($row, 9, $absensi_rekap->getField("KETERANGAN"), $text_format_line);
	$row++;
	$no++;
}
$workbook->close();

header("Content-Type: application/x-msexcel; name=\"rekapitulasi_absensi_per_tanggal_excel.xls\"");
header("Content-Disposition: inline; filename=\"rekapitulasi_absensi_per_tanggal_excel.xls\"");
$fh=fopen($fname, "rb");
fpassthru($fh);
unlink($fname);
?>