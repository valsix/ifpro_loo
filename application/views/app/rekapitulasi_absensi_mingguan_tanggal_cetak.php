<?
/* INCLUDE FILE */
include_once("functions/PageNumber.php");
include_once("functions/date.func.php");
include_once("functions/string.func.php");
include_once("functions/default.func.php");
include_once("functions/recordcoloring.func.php");

$this->load->model('AbsensiRekap');
$this->load->model('HariLibur');
$this->load->model('Cabang');

require_once "excel/class.writeexcel_workbookbig.inc.php";
require_once "excel/class.writeexcel_worksheet.inc.php";

//set_time_limit(3);
ini_set("memory_limit","500M");
ini_set('max_execution_time', 520);

$fname = tempnam("/tmp", "cetak_rekapitulasi_absensi.xls");
$workbook = & new writeexcel_workbookbig($fname);
$worksheet = &$workbook->addworksheet();
$worksheet->hide_gridlines();
$absensi_rekap = new AbsensiRekap();
$hari_libur = new HariLibur();
$cabang = new Cabang();

$reqBulan = $this->input->get("reqBulan");
$reqTahun = $this->input->get("reqTahun");
$reqAwal = $this->input->get("reqAwal");
$reqAkhir = $this->input->get("reqAkhir");

$reqDepartemen = $this->input->get("reqDepartemen");
$reqStatusPegawai= $this->input->get("reqStatusPegawai");

$periode = $reqBulan.$reqTahun;

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
	
	
$absensi_rekap->selectByParamsRekapAbsensiCetak(array(), -1, -1, $statement, $periode, " ORDER BY A.NAMA ASC");

$worksheet->set_column(0, 0, 6.00);
$worksheet->set_column(1, 1, 30.00);

$cabang->selectByParams(array("CABANG_ID" => $reqCabangId), -1, -1);
$cabang->firstRow();

$hariAwal = $reqAwal;
$jumHari = $reqAkhir - $reqAwal + 1;

for ($j=2; $j<=($jumHari*3)+1; $j++){
	$worksheet->set_column($j, $j, 4.29);
}

$worksheet->set_column($j, $j, 10.00);
$worksheet->set_column($j, $j+1, 10.00);
$worksheet->set_column($j, $j+2, 10.00);
$worksheet->set_column($j, $j+3, 10.00);


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

$text_format_line_red =& $workbook->addformat(array(align => 'center', size => 8, font => 'Arial Narrow', fg_color => 0x0A));
$text_format_line_red->set_color('black');
$text_format_line_red->set_size(8);
$text_format_line_red->set_border_color('black');
$text_format_line_red->set_left(1);
$text_format_line_red->set_right(1);
$text_format_line_red->set_top(1);
$text_format_line_red->set_bottom(1);

$text_format_line_yellow =& $workbook->addformat(array(align => 'center', size => 8, font => 'Arial Narrow', fg_color => 0x0D));
$text_format_line_yellow->set_color('black');
$text_format_line_yellow->set_size(8);
$text_format_line_yellow->set_border_color('black');
$text_format_line_yellow->set_left(1);
$text_format_line_yellow->set_right(1);
$text_format_line_yellow->set_top(1);
$text_format_line_yellow->set_bottom(1);

$text_format_line_silver =& $workbook->addformat(array(align => 'center', size => 8, font => 'Arial Narrow', fg_color => 0x1F));
$text_format_line_silver->set_color('black');
$text_format_line_silver->set_size(8);
$text_format_line_silver->set_border_color('black');
$text_format_line_silver->set_left(1);
$text_format_line_silver->set_right(1);
$text_format_line_silver->set_top(1);
$text_format_line_silver->set_bottom(1);

$text_format_line_blue =& $workbook->addformat(array(align => 'center', size => 8, font => 'Arial Narrow', fg_color => 0x28));
$text_format_line_blue->set_color('black');
$text_format_line_blue->set_size(8);
$text_format_line_blue->set_border_color('black');
$text_format_line_blue->set_left(1);
$text_format_line_blue->set_right(1);
$text_format_line_blue->set_top(1);
$text_format_line_blue->set_bottom(1);


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

$worksheet->insert_bitmap('A1', 'images/logo_cetak.bmp', 5, 5);

$worksheet->write		(1, 14, "REKAP KEHADIRAN MINGGUAN PERIODE ".strtoupper(getNamePeriode($periode)), $text_format);
$worksheet->write		(2, 14, "UNIT ".$cabang->getField('NAMA')."", $text_format);


$worksheet->write(3, 0, "NO", $text_format_line_bold_no_bottom);
$worksheet->write(4, 0, "", $text_format_line_bold_no_top);
$worksheet->write(3, 1, "NAMA", $text_format_line_bold_no_bottom);
$worksheet->write(4, 1, "", $text_format_line_bold_no_top);
$colum = 2;
for ($i=2; $i<=($jumHari)+1; $i++)
{

	//if($i % 2 == 0)
	//{
		
		$worksheet->write(3, $colum, $hariAwal, $text_format_merge_line_bold);
		$worksheet->write(4, $colum, "IN", $text_format_line_bold);
		$hariAwal++;
		$colum++;
	//}
	//else
	//{
		//1 3 5 7 9 11 13 15 17 19 21 23 25 27 29 31
		//$worksheet->write		(3, $i, $i."dsd", $text_format_merge_line_bold);
		$worksheet->write_blank	(3, $colum, $text_format_merge_line_bold);
		$worksheet->write(4, $colum, "OUT", $text_format_line_bold);
		$colum++;
		
		$worksheet->write_blank	(3, $colum, $text_format_merge_line_bold);
		$worksheet->write(4, $colum, "JJ", $text_format_line_bold);
		$colum++;
	//}
}
$worksheet->write(3, $colum, "TOTAL JAM", $text_format_line_bold_no_bottom);
$worksheet->write(4, $colum, "", $text_format_line_bold_no_top);
$worksheet->write(3, $colum+1, "TEPAT WAKTU", $text_format_line_bold_no_bottom);
$worksheet->write(4, $colum+1, "", $text_format_line_bold_no_top);
$worksheet->write(3, $colum+2, "TERLAMBAT", $text_format_line_bold_no_bottom);
$worksheet->write(4, $colum+2, "", $text_format_line_bold_no_top);
$worksheet->write(3, $colum+3, "TIDAK MASUK", $text_format_line_bold_no_bottom);
$worksheet->write(4, $colum+3, "", $text_format_line_bold_no_top);

$hariAwal = $reqAwal;
$row = 5;
$no = 1;
while($absensi_rekap->nextRow())
{
	$hadir_tepat = 0;
	$terlambat = 0;
	$alpha = 0;
	$columnexcel = 2;
	$hour_value = 0;
	$min_value = 0;
	$worksheet->write($row, 0, $no, $text_format_line_left);
	$worksheet->write($row, 1, $absensi_rekap->getField('NAMA'), $text_format_line_left);
	for ($k=2; $k<=($jumHari)+1; $k++)
	{
		$hari_libur = new HariLibur();
		//if($k % 2 == 0)
		//{			
		
			$hari = $hariAwal;
			$cek_hari_libur = $hari_libur->getLibur(generateZero($hari, 2).substr($periode, 0, 2), generateZero($hari, 2).$periode);
			$date = $reqTahun.'/'.$reqBulan.'/'.$hari; 
			$day = date('l', strtotime($date));
			if($day == "Saturday" || $day == "Sunday" || $cek_hari_libur == 1)
			{
				$style = $text_format_line_red;
				
				if ($absensi_rekap->getField('NAMA_KAPAL') <> "") {
					if($absensi_rekap->getField('IN_'.$hari) == "")
				{
					if($absensi_rekap->getField('OUT_'.$hari) == "")
						$style = $text_format_line_silver;
					else					
						$style = $text_format_line_blue;
					
					$alpha += 1;
				}
				else
				{
					if(substr($absensi_rekap->getField('IN_'.$hari), 5, 1) == "Y")
					{
						$terlambat += 1;
						$style = $text_format_line_yellow;
					}
					else				
					{
						$hadir_tepat += 1;
						$style = $text_format_line;
					}
				}	
				}
			}
			else
			{
				if($absensi_rekap->getField('IN_'.$hari) == "")
				{
					if($absensi_rekap->getField('OUT_'.$hari) == "")
						$style = $text_format_line_silver;
					else					
						$style = $text_format_line_blue;
					
					$alpha += 1;
				}
				else
				{
					if(substr($absensi_rekap->getField('IN_'.$hari), 5, 1) == "Y")
					{
						$terlambat += 1;
						$style = $text_format_line_yellow;
					}
					else				
					{
						$hadir_tepat += 1;
						$style = $text_format_line;
					}
				}
			}
			$worksheet->write($row, $columnexcel, substr($absensi_rekap->getField('IN_'.$hari), 0, 5), $style);
			$columnexcel++;
		//}
		//else
		//{		
			$hari = $hariAwal;
			$cek_hari_libur = $hari_libur->getLibur(generateZero($hari, 2).substr($periode, 0, 2), generateZero($hari, 2).$periode);
			$date = $reqTahun.'/'.$reqBulan.'/'.$hari; 
			$day = date('l', strtotime($date));
			if($day == "Saturday" || $day == "Sunday" || $cek_hari_libur == 1)
				$style = $text_format_line_red;
			else
			{
				if($absensi_rekap->getField('OUT_'.$hari) == "")
					if($absensi_rekap->getField('IN_'.$hari) == "")
						$style = $text_format_line_silver;
					else					
						$style = $text_format_line_blue;
				else
				{
					if(substr($absensi_rekap->getField('OUT_'.$hari), 5, 1) == "Y")
						$style = $text_format_line_yellow;
					else				
						$style = $text_format_line;
				}
			}
			$worksheet->write($row, $columnexcel, substr($absensi_rekap->getField('OUT_'.$hari), 0, 5), $style);
			$columnexcel++;
			$worksheet->write($row, $columnexcel, $absensi_rekap->getField('JJ_'.$hari), $style);
			$columnexcel++;
			$hariAwal++;
			
		   $point_pos = strpos($absensi_rekap->getField('JJ_'.$hari), ":");
		   $hour_value += substr($absensi_rekap->getField('JJ_'.$hari), 0, $point_pos);
		   $min_value += substr($absensi_rekap->getField('JJ_'.$hari), $point_pos +1, $point_pos+2);
		//}
		unset($hari_libur);
	}
	
	$hour_value += intval($min_value/60);
	$min_value = $min_value%60;
	
	$worksheet->write($row, $columnexcel, $hour_value  . ":" . $min_value, $text_format_line);
	$columnexcel++;
	$worksheet->write($row, $columnexcel, $hadir_tepat, $text_format_line);
	$columnexcel++;
	$worksheet->write($row, $columnexcel, $terlambat, $text_format_line);
	$columnexcel++;
	$worksheet->write($row, $columnexcel, $alpha, $text_format_line);
	$row++;
	$hariAwal = $reqAwal;
	$no++;
}

$workbook->close();

header("Content-Type: application/x-msexcel; name=\"cetak_rekapitulasi_absensi.xls\"");
header("Content-Disposition: inline; filename=\"cetak_rekapitulasi_absensi.xls\"");
$fh=fopen($fname, "rb");
fpassthru($fh);
unlink($fname);
?>