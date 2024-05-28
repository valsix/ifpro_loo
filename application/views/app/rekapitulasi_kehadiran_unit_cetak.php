<?
/* INCLUDE FILE */
include_once("functions/PageNumber.php");
include_once("functions/date.func.php");
include_once("functions/string.func.php");
include_once("functions/default.func.php");
include_once("functions/recordcoloring.func.php");

$this->load->model('AbsensiKoreksi');
$this->load->model('Cabang');

require_once "excel/class.writeexcel_workbookbig.inc.php";
require_once "excel/class.writeexcel_worksheet.inc.php";

//set_time_limit(3);
ini_set("memory_limit","500M");
ini_set('max_execution_time', 520);

$fname = tempnam("/tmp", "rekapitulasi_kehadiran_unit_cetak.xls");
$workbook = & new writeexcel_workbookbig($fname);
$worksheet = &$workbook->addworksheet();

$absensi_rekap = new AbsensiKoreksi();
$cabang = new Cabang();

$reqBulan = $this->input->get("reqBulan");
$reqTahun = $this->input->get("reqTahun");
$reqDepartemen = $this->input->get("reqDepartemen");

$periode = $reqBulan.$reqTahun;
		
/* FILTER DEPARTEMEN */
$arrDepartemen = explode("-", $reqDepartemen);
$reqCabangId = $arrDepartemen[0];
$reqDepartemenId = $arrDepartemen[1];
$reqSubDepartemenId = $arrDepartemen[2];

if(trim($reqCabangId) !== '')
	//$statement .= " AND A.CABANG_ID LIKE '".$reqCabangId."%'";
	$statement .= " AND A.CABANG_ID = '".$reqCabangId."'";
if (trim($reqDepartemenId) !== '')
	$statement .= " AND A.DEPARTEMEN_ID LIKE '".$reqDepartemenId."%'";
if (trim($reqSubDepartemenId) !== "")
	$statement .= " AND A.SUB_DEPARTEMEN_ID LIKE '".$reqSubDepartemenId."%' ";

$absensi_rekap->selectByParamsKehadiranUnit(array(), -1, -1, $statement, $periode, " ORDER BY A.NAMA ASC");
$cabang->selectByParams(array("CABANG_ID" => $reqCabangId), -1, -1);
$cabang->firstRow();
//echo $absensi_rekap->query;
//echo $absensi_rekap->query;
//exit;

$worksheet->set_column(0, 0, 8.43);
$worksheet->set_column(1, 1, 10.00);
$worksheet->set_column(2, 2, 20.00);
$worksheet->set_column(3, 3, 35.00);
$worksheet->set_column(4, 4, 50.00);

for ($j=5; $j<=35; $j++){
	$worksheet->set_column($j, $j, 4.29);
}
$worksheet->set_column(36, 36, 5.00);
$worksheet->set_column(37, 37, 5.00);
$worksheet->set_column(38, 38, 5.00);
$worksheet->set_column(39, 39, 5.00);
$worksheet->set_column(40, 40, 5.00);
$worksheet->set_column(41, 41, 5.00);
$worksheet->set_column(42, 42, 16.00);
$worksheet->set_column(43, 43, 14.00);

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

$text_format_line_bold_no_bottom =& $workbook->addformat(array(align => 'center', size => 8, font => 'Arial Narrow'));
$text_format_line_bold_no_bottom->set_color('black');
$text_format_line_bold_no_bottom->set_size(8);
$text_format_line_bold_no_bottom->set_border_color('black');
$text_format_line_bold_no_bottom->set_bold(1);
$text_format_line_bold_no_bottom->set_left(1);
$text_format_line_bold_no_bottom->set_right(1);
$text_format_line_bold_no_bottom->set_top(1);
$text_format_line_bold_no_bottom->set_bottom(0);

$text_format_line_bold_no_top =& $workbook->addformat(array(align => 'center', size => 8, font => 'Arial Narrow'));
$text_format_line_bold_no_top->set_color('black');
$text_format_line_bold_no_top->set_size(8);
$text_format_line_bold_no_top->set_border_color('black');
$text_format_line_bold_no_top->set_bold(1);
$text_format_line_bold_no_top->set_left(1);
$text_format_line_bold_no_top->set_right(1);
$text_format_line_bold_no_top->set_top(0);
$text_format_line_bold_no_top->set_bottom(1);

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
$worksheet->write(1, 1, "ABSENSI KEHADIRAN", $text_format_merge);
for($i=2; $i<=40; $i++)
{
	$worksheet->write_blank(1, $i, $text_format_merge);
}

$worksheet->write(2, 1, "UNIT ".$cabang->getField('NAMA')."", $text_format_merge);
for($i=2; $i<=40; $i++)
{
	$worksheet->write_blank(2, $i, $text_format_merge);
}

$worksheet->write(3, 1, "BULAN ".strtoupper(getSelectFormattedDate($reqBulan))." ".$reqTahun."", $text_format_merge);
for($i=2; $i<=40; $i++)
{
	$worksheet->write_blank(3, $i, $text_format_merge);
}



$worksheet->write(5, 1, "NO", $text_format_line_bold_no_bottom);
$worksheet->write(6, 1, "", $text_format_line_bold_no_top);
$worksheet->write(5, 2, "NID", $text_format_line_bold_no_bottom);
$worksheet->write(6, 2, "", $text_format_line_bold_no_top);
$worksheet->write(5, 3, "NAMA", $text_format_line_bold_no_bottom);
$worksheet->write(6, 3, "", $text_format_line_bold_no_top);
$worksheet->write(5, 4, "JABATAN", $text_format_line_bold_no_bottom);
$worksheet->write(6, 4, "", $text_format_line_bold_no_top);
$worksheet->write(5, 5, "BULAN ".strtoupper(getSelectFormattedDate($reqBulan))." ".$reqTahun."", $text_format_merge_line_bold);

for($i=5; $i<=35; $i++)
{
$worksheet->write_blank	(5, $i, $text_format_merge_line_bold);
}

for ($i=5; $i<=35; $i++)
{
	$worksheet->write(6, $i, $i-4, $text_format_merge_line_bold);
}

$worksheet->write(5, 36, "KETIDAK HADIRAN ", $text_format_merge_line_bold);

for($j=37; $j<=41; $j++)
{
	$worksheet->write_blank	(5, $j, $text_format_merge_line_bold);
}

$worksheet->write(6, 36, "SAKIT", $text_format_merge_line_bold);
$worksheet->write(6, 37, "IZIN", $text_format_merge_line_bold);
$worksheet->write(6, 38, "ALFA", $text_format_merge_line_bold);
$worksheet->write(6, 39, "CUTI", $text_format_merge_line_bold);
$worksheet->write(6, 40, "DINAS", $text_format_merge_line_bold);
$worksheet->write(6, 41, "IJIN URGENT", $text_format_merge_line_bold);

$worksheet->write(5, 42, "TOTAL TIDAK HADIR", $text_format_line_bold_no_bottom);
$worksheet->write(6, 42, "", $text_format_line_bold_no_top);
$worksheet->write(5, 43, "TOTAL KEHADIRAN", $text_format_line_bold_no_bottom);
$worksheet->write(6, 43, "", $text_format_line_bold_no_top);

$row = 7;
$no = 1;
while($absensi_rekap->nextRow())
{
	$worksheet->write($row, 1, $no, $text_format_line_left);
	$worksheet->write($row, 2, $absensi_rekap->getField('PEGAWAI_ID'), $text_format_line_left);
	$worksheet->write($row, 3, $absensi_rekap->getField('NAMA'), $text_format_line_left);
	$worksheet->write($row, 4, $absensi_rekap->getField('NAMA_JABATAN'), $text_format_line_left);
	for ($k=5; $k<=35; $k++)
	{
			$worksheet->write($row, $k, $absensi_rekap->getField('HARI_'.($k-4)), $text_format_line);
	}
	$worksheet->write($row, 36, $absensi_rekap->getField('SAKIT'), $text_format_line_left);
	$worksheet->write($row, 37, $absensi_rekap->getField('IJIN'), $text_format_line_left);
	$worksheet->write($row, 38, $absensi_rekap->getField('ALPHA'), $text_format_line_left);
	$worksheet->write($row, 39, $absensi_rekap->getField('CUTI'), $text_format_line_left);
	$worksheet->write($row, 40, $absensi_rekap->getField('DINAS'), $text_format_line_left);
	$worksheet->write($row, 41, $absensi_rekap->getField('IJIN_URGENT'), $text_format_line_left);
	$worksheet->write($row, 42, $absensi_rekap->getField('TIDAK_HADIR'), $text_format_line_left);
	$worksheet->write($row, 43, $absensi_rekap->getField('HADIR'), $text_format_line_left);
	$no++;
	$row++;
}

$workbook->close();

header("Content-Type: application/x-msexcel; name=\"rekapitulasi_kehadiran_unit_cetak.xls\"");
header("Content-Disposition: inline; filename=\"rekapitulasi_kehadiran_unit_cetak.xls\"");
$fh=fopen($fname, "rb");
fpassthru($fh);
unlink($fname);
?>