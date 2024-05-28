<?
/* INCLUDE FILE */
include_once("functions/PageNumber.php");
include_once("functions/date.func.php");
include_once("functions/string.func.php");
include_once("functions/default.func.php");
include_once("functions/recordcoloring.func.php");

$this->load->model('PermohonanProyek');

require_once "excel/class.writeexcel_workbookbig.inc.php";
require_once "excel/class.writeexcel_worksheet.inc.php";

//set_time_limit(3);
ini_set("memory_limit","500M");
ini_set('max_execution_time', 520);

$permohonan_proyek = new PermohonanProyek();
$permohonan_proyek_nama = new PermohonanProyek();
$permohonan_proyek_cek = new PermohonanProyek();
$permohonan_proyek_count = new PermohonanProyek();

$reqPermohonanProyekId = $this->input->get("reqPermohonanProyekId");

$permohonan_proyek_nama->selectByParams(array("A.PERMOHONAN_PROYEK_ID" => $reqPermohonanProyekId));
$permohonan_proyek_nama->firstRow();
$reqNamaProyek = $permohonan_proyek_nama->getField("NAMA");

$total_periode = $permohonan_proyek_count->getCountByParamsProyekPeriode($reqPermohonanProyekId);

$fname = tempnam("/tmp", "rekapitulasi_kehadiran_proyek_pegawai_ttp_proyek.xls");
$workbook = & new writeexcel_workbookbig($fname);

$workbook = &new writeexcel_workbook($fname);

$permohonan_proyek_cek->selectByParamsProyekPeriode($reqPermohonanProyekId, array(), -1, -1);
$j = 0;
while($permohonan_proyek_cek->nextRow())
{
	$arrPeriode[$j]= $permohonan_proyek_cek->getField("PERIODE");
	$arrNama[$j]= strtoupper(getNamePeriode($permohonan_proyek_cek->getField("PERIODE")));
	$arrTanggalTerakhirPerbulan[$j]= $permohonan_proyek_cek->getField("TANGGAL_TERAKHIR_PERBULAN");
	$arrTanggalAwalProyek[$j]= $permohonan_proyek_cek->getField("TANGGAL_AWAL_PROYEK");
	$arrTanggalAkhirProyek[$j]= $permohonan_proyek_cek->getField("TANGGAL_AKHIR_PROYEK");
	$arrUrut[$j]= $j;
	$j++;
}

for ($z=0; $z < $total_periode; $z++)
{

	$worksheet = &$workbook->addworksheet($arrNama[$z]);
	
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
	
	$bg_color =& $workbook->addformat();
	$bg_color->set_color('black');
	$bg_color->set_size(8);
	$bg_color->set_border_color('black');
	$bg_color->set_left(1);
	$bg_color->set_right(1);
	$bg_color->set_top(1);
	$bg_color->set_bottom(1);
	$bg_color->set_bg_color('silver');

	if ($z == $arrUrut[$z])
	{
		$jumHari = cal_days_in_month(CAL_GREGORIAN, date(getBulanPeriode($arrPeriode[$z])), date(getTahunPeriode($arrPeriode[$z])));
		
		if($z==0)
		{
			$reqAwal = $arrTanggalAwalProyek[$z];
			$reqAkhir = $jumHari;
		}
		elseif($z-1 == count($arrUrut[$z]-1))
		{
			$reqAwal = 1;
			$reqAkhir = $arrTanggalAkhirProyek[$z];
		}
		else
		{
			$reqAwal = 1;
			$reqAkhir = $jumHari;
		}
		
		$worksheet->set_column(0, 0, 8.43);
		$worksheet->set_column(1, 1, 10.00);
		$worksheet->set_column(2, 2, 20.00);
		$worksheet->set_column(3, 3, 35.00);
		$worksheet->set_column(4, 4, 35.00);
		
		for ($j=5; $j<=$jumHari+4; $j++){
			$worksheet->set_column($j, $j, 4.29);
		}
		
		$worksheet->set_column($jumHari+5, $jumHari+5, 5.00);
		$worksheet->set_column($jumHari+6, $jumHari+6, 5.00);
		$worksheet->set_column($jumHari+7, $jumHari+7, 5.00);
		$worksheet->set_column($jumHari+8, $jumHari+8, 5.00);
		$worksheet->set_column($jumHari+9, $jumHari+9, 5.00);
		$worksheet->set_column($jumHari+10, $jumHari+10, 16.00);
		$worksheet->set_column($jumHari+11, $jumHari+11, 14.00);
			
			$worksheet->insert_bitmap('B1', 'images/logo_cetak.bmp', 5, 5);
			$worksheet->write(1, 1, "ABSENSI KEHADIRAN KARYAWAN", $text_format_merge);
			for($i=2; $i<=40; $i++)
			{
				$worksheet->write_blank(1, $i, $text_format_merge);
			}
			
			$worksheet->write(2, 1, "".$reqNamaProyek."", $text_format_merge);
			for($i=2; $i<=40; $i++)
			{
				$worksheet->write_blank(2, $i, $text_format_merge);
			}
			
			$worksheet->write(5, 1, "NO", $text_format_line_bold_no_bottom);
			$worksheet->write(6, 1, "", $text_format_line_bold_no_top);
			$worksheet->write(5, 2, "NID", $text_format_line_bold_no_bottom);
			$worksheet->write(6, 2, "", $text_format_line_bold_no_top);
			$worksheet->write(5, 3, "NAMA", $text_format_line_bold_no_bottom);
			$worksheet->write(6, 3, "", $text_format_line_bold_no_top);
			$worksheet->write(5, 4, "KETERANGAN", $text_format_line_bold_no_bottom);
			$worksheet->write(6, 4, "", $text_format_line_bold_no_top);
			$worksheet->write(5, 5, "BULAN ".strtoupper(getNamePeriode($arrPeriode[$z]))."", $text_format_merge_line_bold);
			
			for($i=5; $i<=$jumHari+4; $i++)
			{
			$worksheet->write_blank	(5, $i, $text_format_merge_line_bold);
			}
			
			for ($i=5; $i<=$jumHari+4; $i++)
			{
				$worksheet->write(6, $i, $i-4, $text_format_merge_line_bold);
			}
			
			$worksheet->write(5, $jumHari+5, "KETIDAK HADIRAN ", $text_format_merge_line_bold);
			
			for($j=$jumHari+5; $j<=$jumHari+9; $j++)
			{
				$worksheet->write_blank	(5, $j, $text_format_merge_line_bold);
			}
			
			$worksheet->write(6, $jumHari+5, "SAKIT", $text_format_merge_line_bold);
			$worksheet->write(6, $jumHari+6, "IZIN", $text_format_merge_line_bold);
			$worksheet->write(6, $jumHari+7, "ALFA", $text_format_merge_line_bold);
			$worksheet->write(6, $jumHari+8, "CUTI", $text_format_merge_line_bold);
			$worksheet->write(6, $jumHari+9, "DINAS", $text_format_merge_line_bold);
			
			$worksheet->write(5, $jumHari+10, "TOTAL TIDAK HADIR", $text_format_line_bold_no_bottom);
			$worksheet->write(6, $jumHari+10, "", $text_format_line_bold_no_top);
			$worksheet->write(5, $jumHari+11, "TOTAL KEHADIRAN", $text_format_line_bold_no_bottom);
			$worksheet->write(6, $jumHari+11, "", $text_format_line_bold_no_top);
			
			
			$row = 7;
			$no = 1;
			$statement = " AND A.PERMOHONAN_PROYEK_ID = '".$reqPermohonanProyekId."' AND F.PERIODE = '".$arrPeriode[$z]."' ";
			$permohonan_proyek->selectByParamsKehadiranProyek(array(), -1, -1, $statement, " ORDER BY D.NAMA ASC");
			while($permohonan_proyek->nextRow())
			{
				$worksheet->write($row, 1, $no, $text_format_line_left);
				$worksheet->write($row, 2, $permohonan_proyek->getField('PEGAWAI_ID'), $text_format_line_left);
				$worksheet->write($row, 3, $permohonan_proyek->getField('NAMA'), $text_format_line_left);
				$worksheet->write($row, 4, $permohonan_proyek->getField('JABATAN'), $text_format_line_left);
				for ($k=5; $k<=$jumHari+4; $k++)
				{
					if($k-4 >= $reqAwal && $k-4 <= $reqAkhir)
						$worksheet->write($row, $k, $permohonan_proyek->getField('HARI_'.($k-4)), $text_format_line);
					else
						$worksheet->write($row, $k, "", $bg_color);
				}
				$worksheet->write($row, $jumHari+5, $permohonan_proyek->getField('SAKIT'), $text_format_line_left);
				$worksheet->write($row, $jumHari+6, $permohonan_proyek->getField('IJIN'), $text_format_line_left);
				$worksheet->write($row, $jumHari+7, $permohonan_proyek->getField('ALPHA'), $text_format_line_left);
				$worksheet->write($row, $jumHari+8, $permohonan_proyek->getField('CUTI'), $text_format_line_left);
				$worksheet->write($row, $jumHari+9, $permohonan_proyek->getField('DINAS'), $text_format_line_left);
				$worksheet->write($row, $jumHari+10, $permohonan_proyek->getField('TIDAK_HADIR'), $text_format_line_left);
				$worksheet->write($row, $jumHari+11, $permohonan_proyek->getField('HADIR'), $text_format_line_left);
				$no++;
				$row++;
			}
			
	}
	/*
	elseif($z==1)
	{
		
		$jumHari = cal_days_in_month(CAL_GREGORIAN, date(getBulanPeriode($arrPeriode[$z])), date(getTahunPeriode($arrPeriode[$z])));
		
		$worksheet->set_column(0, 0, 8.43);
		$worksheet->set_column(1, 1, 10.00);
		$worksheet->set_column(2, 2, 20.00);
		$worksheet->set_column(3, 3, 35.00);
		$worksheet->set_column(4, 4, 35.00);
		
		for ($j=5; $j<=$jumHari+4; $j++){
			$worksheet->set_column($j, $j, 4.29);
		}
		$worksheet->set_column($jumHari+5, $jumHari+5, 5.00);
		$worksheet->set_column($jumHari+6, $jumHari+6, 5.00);
		$worksheet->set_column($jumHari+7, $jumHari+7, 5.00);
		$worksheet->set_column($jumHari+8, $jumHari+8, 5.00);
		$worksheet->set_column($jumHari+9, $jumHari+9, 5.00);
		$worksheet->set_column($jumHari+10, $jumHari+10, 16.00);
		$worksheet->set_column($jumHari+11, $jumHari+11, 14.00);
			
			
			$worksheet->insert_bitmap('B1', 'images/logo_cetak.bmp', 5, 5);
			$worksheet->write(1, 1, "ABSENSI KEHADIRAN KARYAWAN", $text_format_merge);
			for($i=2; $i<=40; $i++)
			{
				$worksheet->write_blank(1, $i, $text_format_merge);
			}
			
			$worksheet->write(2, 1, "".$reqNamaProyek."", $text_format_merge);
			for($i=2; $i<=40; $i++)
			{
				$worksheet->write_blank(2, $i, $text_format_merge);
			}
			
			$worksheet->write(5, 1, "NO", $text_format_line_bold_no_bottom);
			$worksheet->write(6, 1, "", $text_format_line_bold_no_top);
			$worksheet->write(5, 2, "NID", $text_format_line_bold_no_bottom);
			$worksheet->write(6, 2, "", $text_format_line_bold_no_top);
			$worksheet->write(5, 3, "NAMA", $text_format_line_bold_no_bottom);
			$worksheet->write(6, 3, "", $text_format_line_bold_no_top);
			$worksheet->write(5, 4, "KETERANGAN", $text_format_line_bold_no_bottom);
			$worksheet->write(6, 4, "", $text_format_line_bold_no_top);
			$worksheet->write(5, 5, "BULAN ".strtoupper(getNamePeriode('052017'))."", $text_format_merge_line_bold);
			
			for($i=5; $i<=$jumHari+4; $i++)
			{
			$worksheet->write_blank	(5, $i, $text_format_merge_line_bold);
			}
			
			for ($i=5; $i<=$jumHari+4; $i++)
			{
				$worksheet->write(6, $i, $i-4, $text_format_merge_line_bold);
			}
			
			$worksheet->write(5, $jumHari+5, "KETIDAK HADIRAN ", $text_format_merge_line_bold);
			
			for($j=$jumHari+5; $j<=$jumHari+9; $j++)
			{
				$worksheet->write_blank	(5, $j, $text_format_merge_line_bold);
			}
			
			$worksheet->write(6, $jumHari+5, "SAKIT", $text_format_merge_line_bold);
			$worksheet->write(6, $jumHari+6, "IZIN", $text_format_merge_line_bold);
			$worksheet->write(6, $jumHari+7, "ALFA", $text_format_merge_line_bold);
			$worksheet->write(6, $jumHari+8, "CUTI", $text_format_merge_line_bold);
			$worksheet->write(6, $jumHari+9, "DINAS", $text_format_merge_line_bold);
			
			$worksheet->write(5, $jumHari+10, "TOTAL TIDAK HADIR", $text_format_line_bold_no_bottom);
			$worksheet->write(6, $jumHari+10, "", $text_format_line_bold_no_top);
			$worksheet->write(5, $jumHari+11, "TOTAL KEHADIRAN", $text_format_line_bold_no_bottom);
			$worksheet->write(6, $jumHari+11, "", $text_format_line_bold_no_top);
			
			
			$row = 7;
			$no = 1;
			while($permohonan_proyek->nextRow())
			{
				$worksheet->write($row, 1, $no, $text_format_line_left);
				$worksheet->write($row, 2, $permohonan_proyek->getField('PEGAWAI_ID'), $text_format_line_left);
				$worksheet->write($row, 3, $permohonan_proyek->getField('NAMA'), $text_format_line_left);
				$worksheet->write($row, 4, $permohonan_proyek->getField('JABATAN'), $text_format_line_left);
				for ($k=5; $k<=$jumHari+4; $k++)
				{
					if($k-4 >= $reqAwal && $k-4 <= $reqAkhir)
						$worksheet->write($row, $k, $permohonan_proyek->getField('HARI_'.($k-4)), $text_format_line);
					else
						$worksheet->write($row, $k, "", $bg_color);
				}
				$worksheet->write($row, $jumHari+5, $permohonan_proyek->getField('SAKIT'), $text_format_line_left);
				$worksheet->write($row, $jumHari+6, $permohonan_proyek->getField('IJIN'), $text_format_line_left);
				$worksheet->write($row, $jumHari+7, $permohonan_proyek->getField('ALPHA'), $text_format_line_left);
				$worksheet->write($row, $jumHari+8, $permohonan_proyek->getField('CUTI'), $text_format_line_left);
				$worksheet->write($row, $jumHari+9, $permohonan_proyek->getField('DINAS'), $text_format_line_left);
				$worksheet->write($row, $jumHari+10, $permohonan_proyek->getField('TIDAK_HADIR'), $text_format_line_left);
				$worksheet->write($row, $jumHari+11, $permohonan_proyek->getField('HADIR'), $text_format_line_left);
				$no++;
				$row++;
			}
			
	}
	*/
	//$hari=getDay($reqTanggalAwal);
	
	//$worksheet->write($row, 20, $hari, $text_format);
	//$row++;
	//$reqTanggalAwal = date ("Y-m-d", strtotime("+1 day", strtotime($reqTanggalAwal)));
//}
}
$workbook->close();

header("Content-Type: application/x-msexcel; name=\"rekapitulasi_kehadiran_proyek_pegawai_ttp_proyek.xls\"");
header("Content-Disposition: inline; filename=\"rekapitulasi_kehadiran_proyek_pegawai_ttp_proyek.xls\"");
$fh=fopen($fname, "rb");
fpassthru($fh);
unlink($fname);
?>