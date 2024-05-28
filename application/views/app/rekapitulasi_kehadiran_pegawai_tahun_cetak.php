<?
/* INCLUDE FILE */
include_once("functions/PageNumber.php");
include_once("functions/date.func.php");
include_once("functions/string.func.php");
include_once("functions/default.func.php");
include_once("functions/recordcoloring.func.php");

$this->load->model('KehadiranPegawaiTahunRekap');
$this->load->model('Cabang');

require_once "excel/class.writeexcel_workbookbig.inc.php";
require_once "excel/class.writeexcel_worksheet.inc.php";

//set_time_limit(3);
ini_set("memory_limit","500M");
ini_set('max_execution_time', 520);

$fname = tempnam("/tmp", "cetak_rekapitulasi_kehadiran_pegawai_tahun.xls");
$workbook = & new writeexcel_workbookbig($fname);
$worksheet = &$workbook->addworksheet();

$rekapitulasi_kehadiran_pegawai_tahun = new KehadiranPegawaiTahunRekap();
$cabang = new Cabang();

$reqBulan = $this->input->get("reqBulan");
$reqTahun = $this->input->get("reqTahun");
$reqDepartemen = $this->input->get("reqDepartemen");

$periode = $reqTahun;

$reqTanggalAkhir = cal_days_in_month(CAL_GREGORIAN, (int)$reqBulan, $reqTahun);

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

// $statement .= " AND A.TAHUN = '".$reqTahun."' ";

$rekapitulasi_kehadiran_pegawai_tahun->selectByParams($reqTahun, array(), -1, -1, " ORDER BY A.NAMA ASC");
$cabang->selectByParams(array("CABANG_ID" => $reqCabangId), -1, -1);
$cabang->firstRow();

$worksheet->set_column(0, 0, 8.43);
$worksheet->set_column(1, 1, 30.00);
$worksheet->set_column(2, 2, 30.00);
$worksheet->set_column(3, 3, 10.57);
$worksheet->set_column(4, 4, 10.57);
$worksheet->set_column(5, 5, 10.57);
$worksheet->set_column(6, 6, 10.57);
$worksheet->set_column(7, 7, 10.57);
$worksheet->set_column(8, 8, 15.57);

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

$worksheet->write(1, 4, "REKAP KEHADIRAN TAHUN ".$reqTahun, $text_format);
$worksheet->write(2, 5, "CABANG/UNIT ".$cabang->getField('NAMA')."", $text_format_merge);

$worksheet->write(4, 1, "NAMA", $text_format_line_bold);
$worksheet->write(4, 2, "JABATAN", $text_format_line_bold);
$worksheet->write(4, 3, "TAHUN", $text_format_line_bold);
$worksheet->write(4, 4, "EFEKTIF", $text_format_line_bold);
$worksheet->write(4, 5, "ALPHA", $text_format_line_bold);
$worksheet->write(4, 6, "IJIN", $text_format_line_bold);
$worksheet->write(4, 7, "CUTI_TAHUNAN", $text_format_line_bold);
$worksheet->write(4, 8, "CUTI_LAINNYA", $text_format_line_bold);

$row = 5;
while($rekapitulasi_kehadiran_pegawai_tahun->nextRow())
{
	$worksheet->write($row, 1, $rekapitulasi_kehadiran_pegawai_tahun->getField('NAMA'), $text_format_line_left);
	$worksheet->write($row, 2, $rekapitulasi_kehadiran_pegawai_tahun->getField('JABATAN'), $text_format_line);
	$worksheet->write($row, 3, $rekapitulasi_kehadiran_pegawai_tahun->getField('TAHUN'), $text_format_line);
	$worksheet->write($row, 4, $rekapitulasi_kehadiran_pegawai_tahun->getField('EFEKTIF'), $text_format_line);
	$worksheet->write($row, 5, $rekapitulasi_kehadiran_pegawai_tahun->getField('ALPHA'), $text_format_line);
	$worksheet->write($row, 6, $rekapitulasi_kehadiran_pegawai_tahun->getField('IJIN'), $text_format_line);
	$worksheet->write($row, 7, $rekapitulasi_kehadiran_pegawai_tahun->getField('CUTI_TAHUNAN'), $text_format_line);
	$worksheet->write($row, 8, $rekapitulasi_kehadiran_pegawai_tahun->getField('CUTI_LAINNYA'), $text_format_line);
	$row++;
}

$workbook->close();

header("Content-Type: application/x-msexcel; name=\"cetak_rekapitulasi_kehadiran_pegawai_tahun.xls\"");
header("Content-Disposition: inline; filename=\"cetak_rekapitulasi_kehadiran_pegawai_tahun.xls\"");
$fh=fopen($fname, "rb");
fpassthru($fh);
unlink($fname);
?>