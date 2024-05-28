<?
/* INCLUDE FILE */
include_once("functions/PageNumber.php");
include_once("functions/date.func.php");
include_once("functions/string.func.php");
include_once("functions/default.func.php");
include_once("functions/recordcoloring.func.php");

$this->load->model('Cabang');
$this->load->model('Departemen');
$this->load->model('SubDepartemen');
$this->load->model('Fungsi');
$this->load->model('Staff');


$cabang = new Cabang();
$departemen = new Departemen();
$sub_departemen = new SubDepartemen();
$fungsi = new Fungsi();
$staff = new Staff();

$cabang->selectByParams(array(), -1, -1, "", "ORDER BY CABANG_ID ASC");
$departemen->selectByParams(array(), -1, -1, "", "ORDER BY NAMA ASC");
$sub_departemen->selectByParams(array(), -1, -1, "", "ORDER BY NAMA ASC");
$fungsi->selectByParams(array(), -1, -1, "", "ORDER BY NAMA ASC");
$staff->selectByParams(array(), -1, -1, "", "ORDER BY NAMA ASC");

require_once "excel/class.writeexcel_workbookbig.inc.php";
require_once "excel/class.writeexcel_worksheet.inc.php";

//set_time_limit(3);
ini_set("memory_limit","500M");
ini_set('max_execution_time', 520);

$fname = tempnam("/tmp", "cetak_rekapitulasi_absensi.xls");
$workbook = & new writeexcel_workbookbig($fname);
$workbook = &new writeexcel_workbook($fname);

$arrayNama[0]="IMPORT DATA";
$arrayNama[1]="CABANG";
$arrayNama[2]="DIREKTORAT";
$arrayNama[3]="SUBDIREKTORAT";
$arrayNama[4]="FUNGSI";
$arrayNama[5]="STAFF";

for ($i=0; $i < 6; $i++)
{

	$worksheet = &$workbook->addworksheet($arrayNama[$i]);

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


	if ($i==0)
	{
		$worksheet->set_column(0, 0, 15.00);
		$worksheet->set_column(1, 1, 30.00);
		$worksheet->set_column(2, 2, 14.00);
		$worksheet->set_column(3, 3, 14.00);
		$worksheet->set_column(4, 4, 17.00);
		$worksheet->set_column(5, 5, 14.00);
		$worksheet->set_column(6, 6, 14.00);
		
			$worksheet->write(0, 0, "NID", $text_format_line_bold);
			$worksheet->write(0, 1, "NAMA PEGAWAI", $text_format_line_bold);
			$worksheet->write(0, 2, "CABANG ID", $text_format_line_bold);
			$worksheet->write(0, 3, "DIREKTORAT ID", $text_format_line_bold);
			$worksheet->write(0, 4, "SUB DIREKTORAT ID", $text_format_line_bold);
			$worksheet->write(0, 5, "FUNGSI ID", $text_format_line_bold);
			$worksheet->write(0, 6, "STAFF ID", $text_format_line_bold);
			
		for($j=0; $j<100; $j++)
		{
			$worksheet->write($row, 0, "", $text_format);
			$worksheet->write($row, 1, "", $text_format);
			$worksheet->write($row, 2, "", $text_format);
			$worksheet->write($row, 3, "", $text_format);
			$worksheet->write($row, 4, "", $text_format);
			$worksheet->write($row, 5, "", $text_format);
			$worksheet->write($row, 6, "", $text_format);
			
			$j++;
		}	
	}
	elseif($i==1)
	{
		$worksheet->set_column(0, 0, 35.00);
		$worksheet->set_column(1, 1, 10.00);
		
			$worksheet->write(0, 0, "NAMA CABANG", $text_format_line_bold);
			$worksheet->write(0, 1, "CABANG ID", $text_format_line_bold);
		
		$row = 1;	
		while($cabang->nextRow())
		{
			$worksheet->write($row, 0, $cabang->getField("NAMA"), $text_format_line_left);
			$worksheet->write_string($row, 1, $cabang->getField("CABANG_ID"), $text_format_line_left);
			
			$row++;
		}	
	}
	elseif($i==2)
	{
		$worksheet->set_column(0, 0, 25.00);
		$worksheet->set_column(1, 1, 14.00);
		
			$worksheet->write(0, 0, "NAMA DIREKTORAT", $text_format_line_bold);
			$worksheet->write(0, 1, "DIREKTORAT ID", $text_format_line_bold);
		
		$row = 1;	
		while($departemen->nextRow())
		{
			$worksheet->write($row, 0, $departemen->getField("NAMA"), $text_format_line_left);
			$worksheet->write_string($row, 1, $departemen->getField("DEPARTEMEN_ID"), $text_format_line_left);
			
			$row++;
		}
	}
	elseif($i==3)
	{
		$worksheet->set_column(0, 0, 30.00);
		$worksheet->set_column(1, 1, 14.00);
		
			$worksheet->write(0, 0, "NAMA SUBDIREKTORAT", $text_format_line_bold);
			$worksheet->write(0, 1, "SUBDIREKTORAT ID", $text_format_line_bold);
		
		$row = 1;	
		while($sub_departemen->nextRow())
		{
			$worksheet->write($row, 0, $sub_departemen->getField("NAMA"), $text_format_line_left);
			$worksheet->write($row, 1, $sub_departemen->getField("SUB_DEPARTEMEN_ID"), $text_format_line_left);
			
			$row++;
		}
	}
	elseif($i==4)
	{
		$worksheet->set_column(0, 0, 30.00);
		$worksheet->set_column(1, 1, 14.00);
		
			$worksheet->write(0, 0, "FUNGSI", $text_format_line_bold);
			$worksheet->write(0, 1, "FUNGSI ID", $text_format_line_bold);
		
		$row = 1;	
		while($fungsi->nextRow())
		{
			$worksheet->write($row, 0, $fungsi->getField("NAMA"), $text_format_line_left);
			$worksheet->write_string($row, 1, $fungsi->getField("FUNGSI_ID"), $text_format_line_left);
			
			$row++;
		}
	}
	elseif($i==5)
	{
		$worksheet->set_column(0, 0, 21.00);
		$worksheet->set_column(1, 1, 14.00);
		
			$worksheet->write(0, 0, "STAFF", $text_format_line_bold);
			$worksheet->write(0, 1, "STAFF ID", $text_format_line_bold);
		
		$row = 1;	
		while($staff->nextRow())
		{
			$worksheet->write($row, 0, $staff->getField("NAMA"), $text_format_line_left);
			$worksheet->write_string($row, 1, $staff->getField("STAFF_ID"), $text_format_line_left);
			
			$row++;
		}
	}
	
}

$workbook->close();

header("Content-Type: application/x-msexcel; name=\"pegawai_pihak_ketiga_import_tempalte.xls\"");
header("Content-Disposition: inline; filename=\"pegawai_pihak_ketiga_import_tempalte.xls\"");
$fh=fopen($fname, "rb");
fpassthru($fh);
unlink($fname);
?>