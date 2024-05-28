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

$fname = tempnam("/tmp", "cetak_rekapitulasi_kehadiran.xls");
$workbook = & new writeexcel_workbookbig($fname);
$worksheet = &$workbook->addworksheet();

$absensi_rekap = new AbsensiRekap();

$reqDepartemen = $this->input->get("reqDepartemen");
$reqBulan = $this->input->get("reqBulan");
$reqTahun = $this->input->get("reqTahun");

$periode = $reqBulan.$reqTahun;

$reqTanggalAkhir = cal_days_in_month(CAL_GREGORIAN, (int)$reqBulan, $reqTahun);

//$statement = "AND A.CABANG_ID = '".$this->KODE_CABANG."'";

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

$absensi_rekap->selectByParamsRekapKehadiranKoreksi($periode, array(), -1, -1, $statement, " ORDER BY A.NAMA ASC");

$worksheet->set_column(0, 0, 8.43);
$worksheet->set_column(1, 1, 30.00);
$worksheet->set_column(2, 2, 10.57);
$worksheet->set_column(3, 3, 10.57);
$worksheet->set_column(4, 4, 10.57);
$worksheet->set_column(5, 5, 10.57);

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

//$worksheet->insert_bitmap('B1', 'images/logo_cetak.bmp', 5, 5);
$worksheet->write		(1, 1, "REKAP KEHADIRAN STAFF BULAN ".getNamePeriode($periode), $text_format);

$worksheet->write(3, 1, "NID", $text_format_line_bold);
$worksheet->write(3, 2, "Nama", $text_format_line_bold);
$worksheet->write(3, 3, "Hari Kerja", $text_format_line_bold);

$worksheet->write(3, 4, "Hadir", $text_format_merge_line_bold);

$worksheet->write(4, 4, "Jumlah", $text_format_line_bold);
$worksheet->write(4, 5, "H", $text_format_line_bold);
$worksheet->write(4, 6, "HT", $text_format_line_bold);
$worksheet->write(4, 7, "HPC", $text_format_line_bold);
$worksheet->write(4, 8, "HTPC", $text_format_line_bold);
$worksheet->write(4, 9, "HTAD", $text_format_line_bold);
$worksheet->write(4, 10, "HTAP", $text_format_line_bold);

$worksheet->write(3, 11, "Ijin", $text_format_merge_line_bold);

$worksheet->write(4, 11, "Jumlah", $text_format_line_bold);
$worksheet->write(4, 12, "IM", $text_format_line_bold);
$worksheet->write(4, 13, "IMKK", $text_format_line_bold);
$worksheet->write(4, 14, "IMMPAK", $text_format_line_bold);
$worksheet->write(4, 15, "IKM", $text_format_line_bold);
$worksheet->write(4, 16, "IKAK", $text_format_line_bold);
$worksheet->write(4, 17, "SDK", $text_format_line_bold);

$worksheet->write(3, 18, "Cuti Lainnya", $text_format_merge_line_bold);

$worksheet->write(4, 18, "Jumlah", $text_format_line_bold);
$worksheet->write(4, 19, "CB", $text_format_line_bold);
$worksheet->write(4, 20, "CBS", $text_format_line_bold);
$worksheet->write(4, 21, "CD", $text_format_line_bold);
$worksheet->write(4, 22, "CGK", $text_format_line_bold);
$worksheet->write(4, 23, "CH", $text_format_line_bold);
$worksheet->write(4, 24, "CIK", $text_format_line_bold);
$worksheet->write(4, 25, "CKW", $text_format_line_bold);

$worksheet->write(3, 26, "Cuti Tahunan", $text_format_merge_none);
$worksheet->write(3, 27, "Istirahat", $text_format_merge_none);
$worksheet->write(3, 28, "Dinas", $text_format_merge_none);
$worksheet->write(3, 29, "Alpha", $text_format_merge_none);

$row = 5;
while($absensi_rekap->nextRow())
{
	/*$worksheet->write($row, 1, $absensi_rekap->getField('NAMA'), $text_format_line_left);
	$worksheet->write($row, 2, $absensi_rekap->getField('HARI_KERJA'), $text_format_line);
	$worksheet->write($row, 3, $absensi_rekap->getField('MASUK'), $text_format_line);
	$worksheet->write($row, 4, $absensi_rekap->getField('CUTI'), $text_format_line);
	$worksheet->write($row, 5, $absensi_rekap->getField('IJIN'), $text_format_line);
	$row++;*/
}

$workbook->close();

header("Content-Type: application/x-msexcel; name=\"cetak_rekapitulasi_kehadiran.xls\"");
header("Content-Disposition: inline; filename=\"cetak_rekapitulasi_kehadiran.xls\"");
$fh=fopen($fname, "rb");
fpassthru($fh);
unlink($fname);
?>