<?
/* INCLUDE FILE */
include_once("functions/PageNumber.php");
include_once("functions/date.func.php");
include_once("functions/string.func.php");
include_once("functions/default.func.php");
include_once("functions/recordcoloring.func.php");

require_once "excel/class.writeexcel_workbookbig.inc.php";
require_once "excel/class.writeexcel_worksheet.inc.php";

//set_time_limit(3);
ini_set("memory_limit","500M");
ini_set('max_execution_time', 520);

$fname = tempnam("/tmp", "pegawai_pengangkatan_import.xls");
$workbook = & new writeexcel_workbookbig($fname);
$workbook = &new writeexcel_workbook($fname);

$arrayNama[0]="IMPORT DATA";

for ($i=0; $i < 2; $i++)
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
		$worksheet->set_column(1, 1, 15.00);
		$worksheet->set_column(2, 2, 25.00);
		$worksheet->set_column(3, 3, 25.00);
		$worksheet->set_column(4, 4, 10.00);
		$worksheet->set_column(5, 5, 25.00);
		$worksheet->set_column(6, 6, 14.00);
		
			$worksheet->write(0, 0, "NRP LAMA", $text_format_line_bold);
			$worksheet->write(0, 1, "NRP BARU", $text_format_line_bold);
			
		for($j=0; $j<100; $j++)
		{
			$worksheet->write($row, 0, "", $text_format);
			$worksheet->write($row, 1, "", $text_format);
			
			$j++;
		}	
	}
}

$workbook->close();

header("Content-Type: application/x-msexcel; name=\"pegawai_pengangkatan_import_template_excel.xls\"");
header("Content-Disposition: inline; filename=\"pegawai_pengangkatan_import_template_excel.xls\"");
$fh=fopen($fname, "rb");
fpassthru($fh);
unlink($fname);
?>