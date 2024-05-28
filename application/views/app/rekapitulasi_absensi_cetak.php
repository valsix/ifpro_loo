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

$fname = tempnam("/tmp", "cetak_rekapitulasi_absensi.xls");
$workbook = & new writeexcel_workbookbig($fname);
$worksheet = &$workbook->addworksheet();
$worksheet->hide_gridlines();

$absensi_rekap = new AbsensiRekap();
$cabang = new Cabang();

$reqBulan = $this->input->get("reqBulan");
$reqTahun = $this->input->get("reqTahun");

$reqDepartemen = $this->input->get("reqDepartemen");
$reqStatusPegawai= $this->input->get("reqStatusPegawai");
$reqJenisPegawai= $this->input->get("reqJenisPegawai");

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

$absensi_rekap->selectByParamsRekapAbsensiCetak(array(), -1, -1, $statement, $periode, " ORDER BY A.NAMA, DEPARTEMEN, KELAS, NAMA ASC");
//echo $absensi_rekap->query; exit;

$cabang->selectByParams(array("CABANG_ID" => $reqCabangId), -1, -1);
$cabang->firstRow();
//echo $absensi_rekap->query;exit;

$worksheet->set_column(0, 0, 8.43);
$worksheet->set_column(1, 1, 4.43);
$worksheet->set_column(2, 2, 30.00);
$worksheet->set_column(3, 3, 40.00);
$worksheet->set_column(4, 4, 40.00);
$columnexcel = 5;
for ($j=$columnexcel; $j<=94; $j++){
	$worksheet->set_column($j, $j, 4.29);
}

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

$text_format_line_bold_no_top_bottom =& $workbook->addformat(array(align => 'center', size => 8, font => 'Arial Narrow'));
$text_format_line_bold_no_top_bottom->set_color('black');
$text_format_line_bold_no_top_bottom->set_size(8);
$text_format_line_bold_no_top_bottom->set_border_color('black');
$text_format_line_bold_no_top_bottom->set_bold(1);
$text_format_line_bold_no_top_bottom->set_left(1);
$text_format_line_bold_no_top_bottom->set_right(1);
$text_format_line_bold_no_top_bottom->set_top(0);
$text_format_line_bold_no_top_bottom->set_bottom(0);

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
$worksheet->write(1, 1, "REKAP KEHADIRAN", $text_format_merge);

for($i=2; $i<=35; $i++)
{
	$worksheet->write_blank(1, $i, $text_format_merge);
}

$worksheet->write(2, 1, "UNIT ".$cabang->getField('NAMA')."", $text_format_merge);

for($i=2; $i<=35; $i++)
{
	$worksheet->write_blank(2, $i, $text_format_merge);
}

$worksheet->write(3, 1, "BULAN ".strtoupper(getSelectFormattedDate($reqBulan))." ".$reqTahun."", $text_format_merge);

for($i=2; $i<=35; $i++)
{
	$worksheet->write_blank(3, $i, $text_format_merge);
}

//$worksheet->write		(1, 1, "REKAP KEHADIRAN BULAN ".strtoupper(getNamePeriode($periode)), $text_format);

$jumHari = cal_days_in_month(CAL_GREGORIAN, date($reqBulan), date('Y'));

$worksheet->write(5, 1, "NO", $text_format_line_bold_no_bottom);
$worksheet->write(6, 1, "", $text_format_line_bold_no_top);
$worksheet->write(5, 2, "NAMA", $text_format_line_bold_no_bottom);
$worksheet->write(6, 2, "", $text_format_line_bold_no_top);
$worksheet->write(5, 3, "NAMA DEPARTEMEN", $text_format_line_bold_no_bottom);
$worksheet->write(6, 3, "", $text_format_line_bold_no_top);
$worksheet->write(5, 4, "JABATAN", $text_format_line_bold_no_bottom);
$worksheet->write(6, 4, "", $text_format_line_bold_no_top);
$columnexcel = 5;
$tglseq = 1;
for ($i=3; $i<=($jumHari)+1+1+1; $i++)
{
		if ((($jumHari)+1+1) < $i) {
		$worksheet->write(5, $columnexcel, "TOTAL JJ", $text_format_line_bold);
		$worksheet->write(6, $columnexcel, "", $text_format_line_bold_no_top);
		$worksheet->write(5, $columnexcel+1, "TOTAL TP", $text_format_line_bold);
		$worksheet->write(6, $columnexcel+1, "", $text_format_line_bold_no_top);
		$worksheet->write(5, $columnexcel+1+1, "TOTAL ABSEN", $text_format_line_bold);
		$worksheet->write(6, $columnexcel+1+1, "", $text_format_line_bold_no_top);
		continue;
		}

//	if($i % 2 == 0)
//	{
		$worksheet->write(5, $columnexcel, $tglseq, $text_format_merge_line_bold);
		$worksheet->write(6, $columnexcel, "IN", $text_format_line_bold);
		$columnexcel++;
//	}
//	else
//	{
		//1 3 5 7 9 11 13 15 17 19 21 23 25 27 29 31
		//$worksheet->write		(3, $i, $i."dsd", $text_format_merge_line_bold);
		$worksheet->write_blank	(5, $columnexcel, $text_format_merge_line_bold);
		$worksheet->write(6, $columnexcel, "OUT", $text_format_line_bold);
		$columnexcel++;
		
		$worksheet->write_blank	(5, $columnexcel, $text_format_merge_line_bold);
		$worksheet->write(6, $columnexcel, "JJ", $text_format_line_bold);
		$columnexcel++;
		$tglseq++;
		
		
//	}
}

$row = 7;
$departemenTemp = "";
$numberDep = 1;
while($absensi_rekap->nextRow())
{
    if ($departemenTemp <> $absensi_rekap->getField('DEPARTEMEN')) {
    $worksheet->write($row, 1, integerToRoman($numberDep), $text_format_line_left);
	$worksheet->write($row, 2, $absensi_rekap->getField('DEPARTEMEN'), $text_format_merge_line_bold);
	
	$row++;
	$worksheet->write($row, 1, "", $text_format_line_bold_no_top_bottom);
	} else {
	$worksheet->write($row, 1, $numberDep, $text_format_line_bold_no_top_bottom);
	}
	
	$numberDep++;
	
	$worksheet->write($row, 2, $absensi_rekap->getField('NAMA'), $text_format_line_left);
	$worksheet->write($row, 3, $absensi_rekap->getField('NAMA_DEPARTEMEN'), $text_format_line_left);
	$worksheet->write($row, 4, $absensi_rekap->getField('JABATAN'), $text_format_line_left);
	$columnexcel = 5;
	$tglseq = 1;
	$totalterlambat = 0;
	$totalpulangcepat = 0;
	$hour_value = 0;
	$min_value = 0;
	for ($k=3; $k<=($jumHari)+1+1+1; $k++)
	{
	//	if($k % 2 == 0)
	//	{	
			if ((($jumHari)+1+1) < $k) {
				$hour_value += intval($min_value/60);
				$min_value = $min_value%60;
				
				$worksheet->write($row, $columnexcel, $hour_value  . ":" . $min_value, $text_format_line);
				$worksheet->write($row, $columnexcel+1, $totalterlambat + $totalpulangcepat, $text_format_line);
				$worksheet->write($row, $columnexcel+1+1, $absensi_rekap->getField('TOTAL_PRESENSI'), $text_format_line);
				continue;
			}
	
			$hari = $tglseq;
			$date = $reqTahun.'/'.$reqBulan.'/'.$hari; 
			$day = date('l', strtotime($date));
			if($day == "Saturday" || $day == "Sunday")
				$style = $text_format_line_red;
			else
			{
				if($absensi_rekap->getField('IN_'.$tglseq) == "")
					if($absensi_rekap->getField('OUT_'.$tglseq) == "")
						$style = $text_format_line_silver;
					else					
						$style = $text_format_line_blue;
				else
				{
					if(substr($absensi_rekap->getField('IN_'.$tglseq), 5, 1) == "Y") {
						$style = $text_format_line_yellow;
						$totalterlambat++;
					} else	{			
						$style = $text_format_line;
						}
				}
			}
			$worksheet->write($row, $columnexcel, substr($absensi_rekap->getField('IN_'.$tglseq), 0, 5), $style);
			$columnexcel++;
	//	}
	//	else
	//	{		
			$hari = $tglseq;
			$date = $reqTahun.'/'.$reqBulan.'/'.$hari; 
			$day = date('l', strtotime($date));
			if($day == "Saturday" || $day == "Sunday")
				$style = $text_format_line_red;
			else
			{
				if($absensi_rekap->getField('OUT_'.$tglseq) == "")
					if($absensi_rekap->getField('IN_'.$tglseq) == "")
						$style = $text_format_line_silver;
					else					
						$style = $text_format_line_blue;
				else
				{
					if(substr($absensi_rekap->getField('OUT_'.$tglseq), 5, 1) == "Y") {
						$style = $text_format_line_yellow;
						$totalpulangcepat++;
					} else	{			
						$style = $text_format_line;
						}
				}
			}
			$worksheet->write($row, $columnexcel, substr($absensi_rekap->getField('OUT_'.$tglseq), 0, 5), $style);
			$columnexcel++;
			$worksheet->write($row, $columnexcel, $absensi_rekap->getField('JJ_'.$tglseq), $style);
			$columnexcel++;
			
		   $point_pos = strpos($absensi_rekap->getField('JJ_'.$tglseq), ":");
		   $hour_value += substr($absensi_rekap->getField('JJ_'.$tglseq), 0, $point_pos);
		   $min_value += substr($absensi_rekap->getField('JJ_'.$tglseq), $point_pos +1, $point_pos+2);

			
	//	}
	$tglseq++;
	}
	$row++;
	$departemenTemp = $absensi_rekap->getField('DEPARTEMEN');
}

$workbook->close();

header("Content-Type: application/x-msexcel; name=\"cetak_rekapitulasi_absensi.xls\"");
header("Content-Disposition: inline; filename=\"cetak_rekapitulasi_absensi.xls\"");
$fh=fopen($fname, "rb");
fpassthru($fh);
unlink($fname);
?>