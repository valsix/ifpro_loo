<?
/* INCLUDE FILE */
include_once("functions/PageNumber.php");
include_once("functions/date.func.php");
include_once("functions/string.func.php");
include_once("functions/default.func.php");
include_once("functions/recordcoloring.func.php");

$this->load->model('AbsensiRekap');
$this->load->model('Cabang');

require_once "excel/class.writeexcel_workbookbig.inc.php";
require_once "excel/class.writeexcel_worksheet.inc.php";

//set_time_limit(3);
ini_set("memory_limit","500M");
ini_set('max_execution_time', 520);

$fname = tempnam("/tmp", "rekapitulasi_jkk_jks_cetak.xls");
$workbook = & new writeexcel_workbookbig($fname);
$worksheet = &$workbook->addworksheet();

$absensi_rekap = new AbsensiRekap();
$cabang = new Cabang();

$reqBulan = $this->input->get("reqBulan");
$reqTahun = $this->input->get("reqTahun");
$reqDepartemen = $this->input->get("reqDepartemen");

if($reqBulan == '01')
	$smt = 'SEMESTER I';
else
	$smt = 'SEMESTER II';

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


$absensi_rekap->selectByParamsRekapJKKJKS(array(), -1, -1, $statement, $reqTahun, " ORDER BY NAMA ASC");
$cabang->selectByParams(array("CABANG_ID" => $reqCabangId), -1, -1);
$cabang->firstRow();
//echo $absensi_rekap->query;exit;

$worksheet->set_column(0, 0, 8.43);
$worksheet->set_column(1, 1, 10.00);
$worksheet->set_column(2, 2, 20.00);
$worksheet->set_column(3, 3, 30.00);
$worksheet->set_column(4, 4, 6.00);
$worksheet->set_column(5, 5, 6.00);
$worksheet->set_column(6, 6, 20.00);
$worksheet->set_column(7, 7, 10.00);
$worksheet->set_column(8, 8, 10.00);
$worksheet->set_column(9, 9, 10.00);
$worksheet->set_column(10, 10, 10.00);
$worksheet->set_column(11, 11, 10.00);
$worksheet->set_column(12, 12, 10.00);
$worksheet->set_column(13, 13, 10.00);
$worksheet->set_column(14, 14, 10.00);
$worksheet->set_column(15, 15, 20.00);
$worksheet->set_column(16, 16, 10.00);
$worksheet->set_column(17, 17, 10.00);


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
$worksheet->write(1, 1, "REKAP CUTI KARYAWAN ORGANIK PJBS ".$cabang->getField('NAMA')."", $text_format_merge);
for($i=2; $i<=17; $i++)
{
	$worksheet->write_blank(1, $i, $text_format_merge);
}

$worksheet->write(2, 1, "SEMESTER II TAHUN 2014", $text_format_merge);
for($i=2; $i<=17; $i++)
{
	$worksheet->write_blank(2, $i, $text_format_merge);
}

$worksheet->write(4, 1, "No.", $text_format_line_bold);
$worksheet->write(4, 2, "NID", $text_format_line_bold);
$worksheet->write(4, 3, "NAMA", $text_format_line_bold);
$worksheet->write(4, 4, "JKK", $text_format_line_bold);
$worksheet->write(4, 5, "JKS", $text_format_line_bold);
$worksheet->write(4, 6, "JKK/JKS", $text_format_line_bold);
$worksheet->write(4, 7, "CUTI", $text_format_line_bold);
$worksheet->write(4, 8, "JAM", $text_format_line_bold);
$worksheet->write(4, 9, "REKAP CUTI / TIDAK MASUK ".$smt." ".$reqTahun."", $text_format_merge_line_bold);
for($i=10; $i<=14; $i++)
{
	$worksheet->write_blank	(4, $i, $text_format_merge_line_bold);
}


$worksheet->write(5, 1, "1", $text_format_line_bold);
$worksheet->write(5, 2, "2", $text_format_line_bold);
$worksheet->write(5, 3, "3", $text_format_line_bold);
$worksheet->write(5, 4, "4", $text_format_line_bold);
$worksheet->write(5, 5, "5", $text_format_line_bold);
$worksheet->write(5, 6, "6 = 4/5", $text_format_line_bold);
$worksheet->write(5, 7, "7", $text_format_line_bold);
$worksheet->write(5, 8, "8 = 7*8jam", $text_format_line_bold);
$worksheet->write(5, 9, "I", $text_format_line_bold);
$worksheet->write(5, 10, "II", $text_format_line_bold);
$worksheet->write(5, 11, "III", $text_format_line_bold);
$worksheet->write(5, 12, "IV", $text_format_line_bold);
$worksheet->write(5, 13, "V", $text_format_line_bold);
$worksheet->write(5, 14, "VI", $text_format_line_bold);


$worksheet->write(4, 15, "ANGKATAN MASUK", $text_format_line_bold_no_bottom);
$worksheet->write(5, 15, "", $text_format_line_bold_no_top);

$worksheet->write(4, 16, "HIRE DATE", $text_format_line_bold_no_bottom);
$worksheet->write(5, 16, "", $text_format_line_bold_no_top);


$row = 6;
$no = 1;
while($absensi_rekap->nextRow())
{
	$worksheet->write($row, 1, $no, $text_format_line_left);
	$worksheet->write($row, 2, $absensi_rekap->getField('PEGAWAI_ID'), $text_format_line_left);
	$worksheet->write($row, 3, $absensi_rekap->getField('NAMA'), $text_format_line_left);
	if($reqBulan == "01")
	{
		$worksheet->write($row, 4, $absensi_rekap->getField('JKK_01'), $text_format_line_left);
		$worksheet->write($row, 5, $absensi_rekap->getField('JKS_01'), $text_format_line_left);
		$worksheet->write($row, 6, $absensi_rekap->getField('JKK_JKS_01'), $text_format_line_left);
		$worksheet->write($row, 7, $absensi_rekap->getField('CUTI_01'), $text_format_line_left);
		$worksheet->write($row, 8, $absensi_rekap->getField('JAM_01'), $text_format_line_left);
		$worksheet->write($row, 9, $absensi_rekap->getField('BLN_01'), $text_format_line_left);
		$worksheet->write($row, 10, $absensi_rekap->getField('BLN_02'), $text_format_line_left);
		$worksheet->write($row, 11, $absensi_rekap->getField('BLN_03'), $text_format_line_left);
		$worksheet->write($row, 12, $absensi_rekap->getField('BLN_04'), $text_format_line_left);
		$worksheet->write($row, 13, $absensi_rekap->getField('BLN_05'), $text_format_line_left);
		$worksheet->write($row, 14, $absensi_rekap->getField('BLN_06'), $text_format_line_left);
		
	} else {
		$worksheet->write($row, 4, $absensi_rekap->getField('JKK_02'), $text_format_line_left);
		$worksheet->write($row, 5, $absensi_rekap->getField('JKS_02'), $text_format_line_left);
		$worksheet->write($row, 6, $absensi_rekap->getField('JKK_JKS_02'), $text_format_line_left);
		$worksheet->write($row, 7, $absensi_rekap->getField('CUTI_02'), $text_format_line_left);
		$worksheet->write($row, 8, $absensi_rekap->getField('JAM_02'), $text_format_line_left);
		$worksheet->write($row, 9, $absensi_rekap->getField('BLN_07'), $text_format_line_left);
		$worksheet->write($row, 10, $absensi_rekap->getField('BLN_08'), $text_format_line_left);
		$worksheet->write($row, 11, $absensi_rekap->getField('BLN_09'), $text_format_line_left);
		$worksheet->write($row, 12, $absensi_rekap->getField('BLN_10'), $text_format_line_left);
		$worksheet->write($row, 13, $absensi_rekap->getField('BLN_11'), $text_format_line_left);
		$worksheet->write($row, 14, $absensi_rekap->getField('BLN_12'), $text_format_line_left);
	}
	
	$worksheet->write($row, 15, $absensi_rekap->getField('STATUS_PEGAWAI'), $text_format_line_left);
	$worksheet->write($row, 16, $absensi_rekap->getField('TANGGAL_MASUK'), $text_format_line_left);
	$no++;
	$row++;
}

$workbook->close();

header("Content-Type: application/x-msexcel; name=\"rekapitulasi_jkk_jks_cetak.xls\"");
header("Content-Disposition: inline; filename=\"rekapitulasi_jkk_jks_cetak.xls\"");
$fh=fopen($fname, "rb");
fpassthru($fh);
unlink($fname);
?>