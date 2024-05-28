<?
/* *******************************************************************************************************
MODUL NAME 			: 
FILE NAME 			: string.func.php
AUTHOR				: 
VERSION				: 1.0
MODIFICATION DOC	:
DESCRIPTION			: Functions to handle string operation
***************************************************************************************************** */



/* fungsi untuk mengatur tampilan mata uang
 * $value = string
 * $digit = pengelompokan setiap berapa digit, default : 3
 * $symbol = menampilkan simbol mata uang (Rupiah), default : false
 * $minusToBracket = beri tanda kurung pada nilai negatif, default : true
 */

function ambilFoto($id, $jenisKelamin)
{
	$link = "uploads/profil/".$id.".jpg";
	if(file_exists($link))
	{}
	else
	{
		if($jenisKelamin == "F")
			$link = "uploads/profil/F.jpg";
		else		
			$link = "uploads/profil/M.jpg";
	}
	
	return $link;	 
	
}

function inforiwayatsurat($reqMode)
{
	if($reqMode == "kotak_keluar" || $reqMode == "kotak_keluar_detil" || $reqMode == "kotak_keluar_nota_dinas_detil" || $reqMode == "kotak_keluar_perintah_detil" || $reqMode == "kotak_keluar_surat_masuk_manual_detil" || $reqMode == "kotak_keluar_edaran_detil" || $reqMode == "kotak_keluar_surat_keluar_detil" || $reqMode == "kotak_keluar_surat_masuk_manual")
		return "";
	else 
		return "1";
}

function datatipemutasi()
{
	$arrdata= array(
	    array("val"=>"1", "label"=>"Mutasi Tukar Jabatan")
	    , array("val"=>"2", "label"=>"Mutasi")
	    , array("val"=>"3", "label"=>"Pensiun")
	    // , array("val"=>"4", "label"=>"Staff")
	);
	return $arrdata;
}

function infotipemutasi($reqTipe)
{
	$arrdata= datatipemutasi();
	$arrayKey= in_array_column($reqTipe, "val", $arrdata);
	if(!empty($arrayKey))
	{
		$index_data= $arrayKey[0];
		return $arrdata[$index_data]["label"];
	}
	else
	{
		return "";
	}
}

function infobuttonaksi($reqMode)
{
	if($reqMode == "kotak_keluar_disposisi" || $reqMode == "kotak_keluar_tanggapan")
		return "";
	else 
		return "1";
}

function infobuttonreply($reqMode)
{
	if($reqMode == "kotak_keluar" || $reqMode == "kotak_keluar_nota_dinas" || $reqMode == "kotak_keluar_surat_keluar" || $reqMode == "kotak_keluar_edaran" || $reqMode == "kotak_keluar_perintah" || $reqMode == "kotak_keluar_surat_keputusan_direksi" || $reqMode == "kotak_keluar_keputusan_direksi" || $reqMode == "kotak_keluar_instruksi_direksi" || $reqMode == "kotak_keluar_surat_masuk_manual")
		return "";
	else 
		return "1";
}

function infokembalimanual($reqMode, $reqId, $reqRowId, $reqStatusSurat)
{
	if($reqRowId == "null")
		$reqRowId= "";
	
	if(!empty($reqStatusSurat))
    {
    	$inforeload= 'main/index/'.$reqMode.'?reqStatusSurat='.$reqStatusSurat;
    }
    elseif(!empty($reqRowId))
    {
        if($reqMode == "kotak_masuk_tanggapan_detil")
        {
        	$inforeload= 'main/index/kotak_masuk_tanggapan?reqMode='.str_replace("_detil", "", $reqMode).'&reqId='.$reqId.'&reqRowId='.$reqRowId;
        }
        else if($reqMode == "kotak_masuk_disposisi_detil" || $reqMode == "kotak_keluar_disposisi_detil" || $reqMode == "kotak_keluar_tanggapan_detil")
        {
        	$inforeload= 'main/index/kotak_masuk_disposisi_detil?reqMode='.str_replace("_detil", "", $reqMode).'&reqId='.$reqId.'&reqRowId='.$reqRowId;
        }
        else if($reqMode == "kotak_masuk_nota_dinas_detil" || $reqMode == "kotak_masuk_edaran_detil" || $reqMode == "kotak_masuk_perintah_detil" || $reqMode == "kotak_masuk_surat_keluar_detil")
        {
        	$inforeload= 'main/index/kotak_masuk_detil?reqMode='.str_replace("_detil", "", $reqMode).'&reqId='.$reqId.'&reqRowId='.$reqRowId;
        }
        else
        {
        	$inforeload= 'main/index/'.$reqMode.'?reqMode='.str_replace("_detil", "", $reqMode).'&reqId='.$reqId.'&reqRowId='.$reqRowId;
        }
    }
    else
    {
    	$inforeload= 'main/index/'.$reqMode;
    }

    return $inforeload;
}

function infokembali($reqMode, $reqId, $reqRowId, $reqStatusSurat)
{
	if($reqRowId == "null")
		$reqRowId= "";

	if(!empty($reqStatusSurat))
    {
    	$inforeload= 'main/index/'.$reqMode.'?reqStatusSurat='.$reqStatusSurat;
    }
    elseif(!empty($reqRowId))
    {
        if($reqMode == "kotak_masuk_tanggapan_detil")
        {
        	$inforeload= 'main/index/kotak_masuk_tanggapan?reqMode='.str_replace("_detil", "", $reqMode).'&reqId='.$reqId.'&reqRowId='.$reqRowId;
        }
        elseif($reqMode == "kotak_masuk_surat_masuk_manual" || $reqMode == "kotak_keluar_surat_masuk_manual")
        {
        	$inforeload= 'main/index/surat_masuk_manual_lihat?reqMode='.$reqMode.'&reqId='.$reqId.'&reqRowId='.$reqRowId;
        }
        else if($reqMode == "kotak_masuk_disposisi_detil" || $reqMode == "kotak_keluar_disposisi_detil" || $reqMode == "kotak_keluar_tanggapan_detil")
        {
        	$inforeload= 'main/index/kotak_masuk_disposisi_detil?reqMode='.str_replace("_detil", "", $reqMode).'&reqId='.$reqId.'&reqRowId='.$reqRowId;
        }
        else if($reqMode == "kotak_masuk_nota_dinas_detil" || $reqMode == "kotak_masuk_edaran_detil" || $reqMode == "kotak_masuk_perintah_detil" || $reqMode == "kotak_masuk_surat_keluar_detil")
        {
        	$inforeload= 'main/index/kotak_masuk_detil?reqMode='.str_replace("_detil", "", $reqMode).'&reqId='.$reqId.'&reqRowId='.$reqRowId;
        }
        else
        {
        	$inforeload= 'main/index/'.$reqMode.'?reqMode='.str_replace("_detil", "", $reqMode).'&reqId='.$reqId.'&reqRowId='.$reqRowId;
        }
    }
    elseif(!empty($reqId))
    {
        if($reqMode == "kotak_keluar_detil" || $reqMode == "kotak_keluar_nota_dinas_detil" || $reqMode == "kotak_keluar_perintah_detil" || $reqMode == "kotak_keluar_edaran_detil" || $reqMode == "kotak_keluar_surat_keluar_detil")
        {
        	$inforeload= 'main/index/kotak_masuk_detil?reqMode='.str_replace("_detil", "", $reqMode).'&reqId='.$reqId.'&reqRowId='.$reqRowId;
        }
        elseif($reqMode == "kotak_masuk_surat_masuk_manual" || $reqMode == "kotak_keluar_surat_masuk_manual")
        {
        	$inforeload= 'main/index/surat_masuk_manual_lihat?reqMode='.$reqMode.'&reqId='.$reqId.'&reqRowId='.$reqRowId;
        }
        elseif($reqMode == "surat_masuk_manual_add" || $reqMode == "nota_dinas_add" || $reqMode == "surat_edaran_add" || $reqMode == "surat_keluar_add" || $reqMode == "surat_perintah_add" || $reqMode == "surat_keputusan_direksi_add" || $reqMode == "keputusan_direksi_add" || $reqMode == "instruksi_direksi_add" || $reqMode == "petikan_skd_add")
        {
        	$inforeload= 'main/index/'.$reqMode.'?reqId='.$reqId;
        }
        else
        {
        	$inforeload= 'main/index/'.$reqMode.'?reqMode='.str_replace("_detil", "", $reqMode).'&reqId='.$reqId;
        }
    }
    else
    {
    	$inforeload= 'main/index/'.$reqMode;
    }

    return $inforeload;
}

function infoiconlink($atttipe)
{
	$arrexcept= [];
	$arrexcept= array("pdf");
	if(in_array(strtolower($atttipe), $arrexcept))
	{
		return "fa-file-pdf-o";
	}

	$arrexcept= array("doc", "docx");
	if(in_array(strtolower($atttipe), $arrexcept))
	{
		return "fa-file-word-o";
	}

	$arrexcept= array("xlsx", "xls");
	if(in_array(strtolower($atttipe), $arrexcept))
	{
		return "fa-file-excel-o";
	}

	$arrexcept= array("ppt", "pptx");
	if(in_array(strtolower($atttipe), $arrexcept))
	{
		return "fa-file-powerpoint-o";
	}
	
	$arrexcept= array("jpg", "jpeg", "png", "gif");
	if(in_array(strtolower($atttipe), $arrexcept))
	{
		return "fa-file-image-o";
	}

	return "fa-file-o";
}

function in_array_column($text, $column, $array)
{
    if (!empty($array) && is_array($array))
    {
        for ($i=0; $i < count($array); $i++)
        {
            if ($array[$i][$column]==$text || strcmp($array[$i][$column],$text)==0) 
				$arr[] = $i;
        }
		return $arr;
    }
    return "";
}

function statusCentang($status)
{
    if((int)$status > 0)
		echo '<span class="fa fa-check" style="color:#4EAA2E;"></span>';
	else
		echo '<span class="fa fa-close" style="color:#F05154;"></span>';		
}
function unserialized($serialized)
{
	$arrSerialized = str_replace('@', '"', $serialized);			
	return unserialize($arrSerialized);
}
function makedirs($dirpath, $mode=0777)
{
    return is_dir($dirpath) || mkdir($dirpath, $mode, true);
}

function currencyToPage($value, $symbol=true, $minusToBracket=true, $minusLess=false, $digit=3)
{
	if($value < 0)
	{
		$neg = "-";
		$value = str_replace("-", "", $value);
	}
	else
		$neg = false;
		
	$cntValue = strlen($value);
	//$cntValue = strlen($value);
	
	if($cntValue <= $digit)
		$resValue =  $value;
	
	$loopValue = floor($cntValue / $digit);
	
	for($i=1; $i<=$loopValue; $i++)
	{
		$sub = 0 - $i; //ubah jadi negatif
		$tempValue = $endValue;
		$endValue = substr($value, $sub*$digit, $digit);
		$endValue = $endValue;
		
		if($i !== 1)
			$endValue .= ".";
		
		$endValue .= $tempValue;
	}
	
	$beginValue = substr($value, 0, $cntValue - ($loopValue * $digit));
	
	if($cntValue % $digit == 0)
		$resValue = $beginValue.$endValue;
	else if($cntValue > $digit)
		$resValue = $beginValue.".".$endValue;
	
	//additional
	if($symbol == true && $resValue !== "")
	{
		$resValue = "Rp. ".$resValue."";
	}
	
	if($minusToBracket && $neg)
	{
		$resValue = "(".$resValue.")";
		$neg = "";
	}
	
	if($minusLess == true)
	{
		$neg = "";
	}
	
	$resValue = $neg.$resValue;
	
	//$resValue = "<span style='white-space:nowrap'>".$resValue."</span>";
	$resValue = str_replace("..", ",", $resValue);
	return $resValue;
}
function generateFoto($id, $initial)
{
	$link = "uploads/profil/".$id.".jpg";
	if(file_exists($link))
		$link = "<img src='uploads/profil/".$id.".jpg'>";
	else
	{
		$arrInitial = explode(" ", $initial);
		if(count($arrInitial) > 1)
			$initial = substr($arrInitial[0], 0, 1).substr($arrInitial[1], 0, 1);
		else
			$initial = substr($arrInitial[0], 0, 1);
		
		$link = "<p data-letters='".strtoupper($initial)."'></p>";
	}
	
	return $link;	 
	
}

function generateFotoMobile($id, $initial)
{
	$link = "uploads/profil/".$id.".jpg";
	if(file_exists($link))
		$link = "<img src='uploads/profil/".$id.".jpg'>";
	else
	{
		$arrInitial = explode(" ", $initial);
		if(count($arrInitial) > 1)
			$initial = substr($arrInitial[0], 0, 1).substr($arrInitial[1], 0, 1);
		else
			$initial = substr($arrInitial[0], 0, 1);
		
		$link = strtoupper($initial);
	}
	
	return $link;	 
}

function nomorDigit($value, $symbol=true, $minusToBracket=true, $minusLess=false, $digit=3)
{
	$arrValue = explode(".", $value);
	$value = $arrValue[0];
	if(count($arrValue) == 1)
		$belakang_koma = "";
	else
		$belakang_koma = $arrValue[1];
	if($value < 0)
	{
		$neg = "-";
		$value = str_replace("-", "", $value);
	}
	else
		$neg = false;
		
	$cntValue = strlen($value);
	//$cntValue = strlen($value);
	
	if($cntValue <= $digit)
		$resValue =  $value;
	
	$loopValue = floor($cntValue / $digit);
	
	for($i=1; $i<=$loopValue; $i++)
	{
		$sub = 0 - $i; //ubah jadi negatif
		$tempValue = $endValue;
		$endValue = substr($value, $sub*$digit, $digit);
		$endValue = $endValue;
		
		if($i !== 1)
			$endValue .= ".";
		
		$endValue .= $tempValue;
	}
	
	$beginValue = substr($value, 0, $cntValue - ($loopValue * $digit));
	
	if($cntValue % $digit == 0)
		$resValue = $beginValue.$endValue;
	else if($cntValue > $digit)
		$resValue = $beginValue.".".$endValue;
	
	//additional
	if($belakang_koma == "")
		$resValue = $symbol." ".$resValue;
	else
		$resValue = $symbol." ".$resValue.",".$belakang_koma;
	
	
	if($minusToBracket && $neg)
	{
		$resValue = "(".$resValue.")";
		$neg = "";
	}
	
	if($minusLess == true)
	{
		$neg = "";
	}
	
	$resValue = $neg.$resValue;
	
	//$resValue = "<span style='white-space:nowrap'>".$resValue."</span>";

	return $resValue;
}


function numberToIna($value, $symbol=true, $minusToBracket=true, $minusLess=false, $digit=3)
{
	$arr_value = explode(".", $value);
	
	if(count($arr_value) > 1)
		$value = $arr_value[0];
	
	if($value < 0)
	{
		$neg = "-";
		$value = str_replace("-", "", $value);
	}
	else
		$neg = false;
		
	$cntValue = strlen($value);
	//$cntValue = strlen($value);
	
	if($cntValue <= $digit)
		$resValue =  $value;
	
	$loopValue = floor($cntValue / $digit);
	
	for($i=1; $i<=$loopValue; $i++)
	{
		$sub = 0 - $i; //ubah jadi negatif
		$tempValue = $endValue;
		$endValue = substr($value, $sub*$digit, $digit);
		$endValue = $endValue;
		
		if($i !== 1)
			$endValue .= ".";
		
		$endValue .= $tempValue;
	}
	
	$beginValue = substr($value, 0, $cntValue - ($loopValue * $digit));
	
	if($cntValue % $digit == 0)
		$resValue = $beginValue.$endValue;
	else if($cntValue > $digit)
		$resValue = $beginValue.".".$endValue;
	
	//additional
	if($symbol == true && $resValue !== "")
	{
		$resValue = $resValue;
	}
	
	if($minusToBracket && $neg)
	{
		$resValue = "(".$resValue.")";
		$neg = "";
	}
	
	if($minusLess == true)
	{
		$neg = "";
	}

	if(count($arr_value) == 1)
		$resValue = $neg.$resValue;
	else
		$resValue = $neg.$resValue.",".$arr_value[1];
	

	
	//$resValue = "<span style='white-space:nowrap'>".$resValue."</span>";

	return $resValue;
}

function getNameValueYaTidak($number) {
	$number = (int)$number;
	$arrValue = array("0"=>"Tidak", "1"=>"Ya");
	return $arrValue[$number];
}

function getNameValueKategori($number) {
	$number = (int)$number;
	$arrValue = array("1"=>"Sangat Baik", "2"=>"Baik", "3"=>"Cukup", "4"=>"Kurang");
	return $arrValue[$number];
}	

function getNameValue($number) {
	$number = (int)$number;
	$arrValue = array("0"=>"Tidak", "1"=>"Ya");
	return $arrValue[$number];
}	

function getNameValueAktif($number) {
	$number = (int)$number;
	$arrValue = array("0"=>"Tidak Aktif", "1"=>"Aktif");
	return $arrValue[$number];
}

function getNameValidasi($number) {
	$number = (int)$number;
	$arrValue = array("0"=>"Menunggu Konfirmasi","1"=>"Disetujui", "2"=>"Ditolak");
	return $arrValue[$number];
}	

function getNameInputOutput($char) {
	$arrValue = array("I"=>"Datang", "O"=>"Pulang");
	return $arrValue[$char];
}		
	
function dotToComma($varId)
{
	$newId = str_replace(".", ",", $varId);	
	return $newId;
}

function CommaToQuery($varId)
{
	$newId = str_replace(",", "','", $varId);	
	return $newId;
}

function dotToNo($varId)
{
	$newId = str_replace(".", "", $varId);	
	$newId = str_replace(",", ".", $newId);	
	return $newId;
}
function CommaToNo($varId)
{
	$newId = str_replace(",", "", $varId);	
	return $newId;
}

function CrashToNo($varId)
{
	$newId = str_replace("#", "", $varId);	
	return $newId;
}

function StarToNo($varId)
{
	$newId = str_replace("* ", "", $varId);	
	return $newId;
}

function NullDotToNo($varId)
{
	$newId = str_replace(".00", "", $varId);
	return $newId;
}

function ExcelToNo($varId)
{
	$newId = NullDotToNo($varId);
	$newId = StarToNo($newId);
	return $newId;
}

function ValToNo($varId)
{
	$newId = NullDotToNo($varId);
	$newId = CommaToNo($newId);
	$newId = StarToNo($newId);
	return $newId;
}

function ValToNull($varId)
{
	if($varId == '')
		return 0;
	else
		return $varId;
}

function ValToNullDB($varId)
{
	if($varId == '')
		return 'NULL';
	elseif($varId == 'null')
		return 'NULL';
	else
		return "'".$varId."'";
}

function setQuote($var, $status='')
{	
	if($status == 1)
		$tmp= str_replace("\'", "''", $var);
	else
		$tmp= str_replace("'", "''", $var);
	return $tmp;
}

// fungsi untuk generate nol untuk melengkapi digit

function generateZero($varId, $digitGroup, $digitCompletor = "0")
{
	$newId = "";
	
	$lengthZero = $digitGroup - strlen($varId);
	
	for($i = 0; $i < $lengthZero; $i++)
	{
		$newId .= $digitCompletor;
	}
	
	$newId = $newId.$varId;
	
	return $newId;
}

// truncate text into desired word counts.
// to support dropDirtyHtml function, include default.func.php
function truncate($text, $limit, $dropDirtyHtml=true)
{
	$tmp_truncate = array();
	$text = str_replace("&nbsp;", " ", $text);
	$tmp = explode(" ", $text);
	
	for($i = 0; $i <= $limit; $i++)		//truncate how many words?
	{
		$tmp_truncate[$i] = $tmp[$i];
	}
	
	$truncated = implode(" ", $tmp_truncate);
	
	if ($dropDirtyHtml == true and function_exists('dropAllHtml'))
		return dropAllHtml($truncated);
	else
		return $truncated;
}

function arrayMultiCount($array, $field_name, $search)
{
	$summary = 0;
	for($i = 0; $i < count($array); $i++)
	{
		if($array[$i][$field_name] == $search)
			$summary += 1;
	}
	return $summary;
}

function getValueArray($var)
{
	//$tmp = "";
	for($i=0;$i<count($var);$i++)
	{			
		if($i == 0)
			$tmp .= $var[$i];
		else
			$tmp .= ",".$var[$i];
	}
	
	return $tmp;
}

function getValueArrayMonth($var)
{
	//$tmp = "";
	for($i=0;$i<count($var);$i++)
	{			
		if($i == 0)
			$tmp .= "'".$var[$i]."'";
		else
			$tmp .= ", '".$var[$i]."'";
	}
	
	return $tmp;
}

function getColoms($var)
{
	$tmp = "";
	if($var == 0)	$tmp = 'D';
	elseif($var == 1)	$tmp = 'E';
	elseif($var == 2)	$tmp = 'F';
	elseif($var == 3)	$tmp = 'G';
	elseif($var == 4)	$tmp = 'H';
	elseif($var == 5)	$tmp = 'I';
	elseif($var == 6)	$tmp = 'J';
	elseif($var == 7)	$tmp = 'K';
	
	return $tmp;
}

function setNULL($var)
{	
	if($var == '')
		$tmp = 'NULL';
	else
		$tmp = $var;
	
	return $tmp;
}

function setNULLModif($var)
{	
	if($var == '')
		$tmp = 'NULL';
	else
		$tmp = "'".$var."'";
	
	return $tmp;
}

function setVal_0($var)
{	
	if($var == '')
		$tmp = '0';
	else
		$tmp = $var;
	
	return $tmp;
}

function get_null_10($varId)
{
	if($varId == '') return '';
	if($varId < 10)	$temp= '0'.$varId;
	else			$temp= $varId;
			
	return $temp;
}

function _ip( )
{
    return ( preg_match( "/^([d]{1,3}).([d]{1,3}).([d]{1,3}).([d]{1,3})$/", $_SERVER['HTTP_X_FORWARDED_FOR'] ) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'] );
}

function getFotoProfile($id)
{
	$filename = "uploads/foto_fix/".$id.".jpg";
	if (file_exists($filename)) {
	} else {
		$filename = "images/foto-profile.jpg";
	}	
	return $filename;
}

function searchWordDelimeter($varSource, $varSearch, $varDelimeter=",")
{

	$arrSource = explode($varDelimeter, $varSource);
	
	for($i=0; $i<count($arrSource);$i++)
	{
		if(trim($arrSource[$i]) == $varSearch)
			return true;
	}
	
	return false;
}

function getZodiac($day,$month){
	if(($month==1 && $day>20)||($month==2 && $day<20)){
	$mysign = "Aquarius";
	}
	if(($month==2 && $day>18 )||($month==3 && $day<21)){
	$mysign = "Pisces";
	}
	if(($month==3 && $day>20)||($month==4 && $day<21)){
	$mysign = "Aries";
	}
	if(($month==4 && $day>20)||($month==5 && $day<22)){
	$mysign = "Taurus";
	}
	if(($month==5 && $day>21)||($month==6 && $day<22)){
	$mysign = "Gemini";
	}
	if(($month==6 && $day>21)||($month==7 && $day<24)){
	$mysign = "Cancer";
	}
	if(($month==7 && $day>23)||($month==8 && $day<24)){
	$mysign = "Leo";
	}
	if(($month==8 && $day>23)||($month==9 && $day<24)){
	$mysign = "Virgo";
	}
	if(($month==9 && $day>23)||($month==10 && $day<24)){
	$mysign = "Libra";
	}
	if(($month==10 && $day>23)||($month==11 && $day<23)){
	$mysign = "Scorpio";
	}
	if(($month==11 && $day>22)||($month==12 && $day<23)){
	$mysign = "Sagitarius";
	}
	if(($month==12 && $day>22)||($month==1 && $day<21)){
	$mysign = "Capricorn";
	}
	return $mysign;
}

function getValueANDOperator($var)
{
	$tmp = ' AND ';
	
	return $tmp;
}

function getValueKoma($var)
{
	if($var == '')
		$tmp = '';
	else
		$tmp = ',';	
	
	return $tmp;
}

function import_format($val)
{
	if($val == ":02")
	{
		$temp= str_replace(":02","24:00",$val);
	}
	else
	{	
		$temp="";
		if($val == "[hh]:mm" || $val == "[h]:mm"){}
		else
			$temp= $val;
	}
	return $temp;
	//return $val;
}

function kekata($x) 
{
	$x = abs($x);
	$angka = array("", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
	$temp = "";
	if ($x <12) 
	{
		$temp = " ". $angka[$x];
	} 
	else if ($x <20) 
	{
		$temp = kekata($x - 10). " belas";
	} 
	else if ($x <100) 
	{
		$temp = kekata($x/10)." puluh". kekata($x % 10);
	} 
	else if ($x <200) 
	{
		$temp = " seratus" . kekata($x - 100);
	} 
	else if ($x <1000) 
	{
		$temp = kekata($x/100) . " ratus" . kekata($x % 100);
	} 
	else if ($x <2000) 
	{
		$temp = " seribu" . kekata($x - 1000);
	} 
	else if ($x <1000000) 
	{
		$temp = kekata($x/1000) . " ribu" . kekata($x % 1000);
	} 
	else if ($x <1000000000) 
	{
		$temp = kekata($x/1000000) . " juta" . kekata($x % 1000000);
	} 
	else if ($x <1000000000000) 
	{
		$temp = kekata($x/1000000000) . " milyar" . kekata(fmod($x,1000000000));
	} 
	else if ($x <1000000000000000) 
	{
		$temp = kekata($x/1000000000000) . " trilyun" . kekata(fmod($x,1000000000000));
	}      
	
	return $temp;
}

function terbilang($x, $style=4) 
{
	if($x < 0) 
	{
		$hasil = "minus ". trim(kekata($x));
	} 
	else 
	{
		$hasil = trim(kekata($x));
	}      
	switch ($style) 
	{
		case 1:
			$hasil = strtoupper($hasil);
			break;
		case 2:
			$hasil = strtolower($hasil);
			break;
		case 3:
			$hasil = ucwords($hasil);
			break;
		default:
			$hasil = ucfirst($hasil);
			break;
	}      
	return $hasil;
}

function romanic_number($integer, $upcase = true)
{
    $table = array('M'=>1000, 'CM'=>900, 'D'=>500, 'CD'=>400, 'C'=>100, 'XC'=>90, 'L'=>50, 'XL'=>40, 'X'=>10, 'IX'=>9, 'V'=>5, 'IV'=>4, 'I'=>1);
    $return = '';
    while($integer > 0)
    {
        foreach($table as $rom=>$arb)
        {
            if($integer >= $arb)
            {
                $integer -= $arb;
                $return .= $rom;
                break;
            }
        }
    }

    return $return;
}

function getExe($tipe)
{
	switch ($tipe) {
	  case "application/pdf": $ctype="pdf"; break;
	  case "application/octet-stream": $ctype="exe"; break;
	  case "application/zip": $ctype="zip"; break;
	  case "application/msword": $ctype="doc"; break;
	  case "application/vnd.ms-excel": $ctype="xls"; break;
	  case "application/vnd.ms-powerpoint": $ctype="ppt"; break;
	  case "image/gif": $ctype="gif"; break;
	  case "image/png": $ctype="png"; break;
	  case "image/jpeg": $ctype="jpeg"; break;
	  case "image/jpg": $ctype="jpg"; break;
	  case "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet": $ctype="xlsx"; break;
	  case "application/vnd.openxmlformats-officedocument.wordprocessingml.document": $ctype="docx"; break;
	  default: $ctype="application/force-download";
	} 
	
	return $ctype;
} 

function getExtension($varSource)
{
	$temp = explode(".", $varSource);
	return end($temp);
}


function coalesce($varSource, $varReplace)
{
	if($varSource == "")
		return $varReplace;
		
	return $varSource;
}

function getJenisNaskah($reqJenisNaskahId)
{
	if($reqJenisNaskahId == "1")
	    $linkUbah = "surat_masuk_manual_add";
	else if($reqJenisNaskahId == "2")
	    $linkUbah = "nota_dinas_add";
	else if($reqJenisNaskahId == "13")
	    $linkUbah = "surat_edaran_add";
	else if($reqJenisNaskahId == "15")
	    $linkUbah = "surat_keluar_add";
	else if($reqJenisNaskahId == "18")
	    $linkUbah = "surat_perintah_add";
	else if($reqJenisNaskahId == "17")
	    $linkUbah = "surat_keputusan_direksi_add";
	else if($reqJenisNaskahId == "8")
	    $linkUbah = "keputusan_direksi_add";
	else if($reqJenisNaskahId == "19")
	    $linkUbah = "instruksi_direksi_add";
	else if($reqJenisNaskahId == "20")
	    $linkUbah = "petikan_skd_add";
	else if($reqJenisNaskahId == "21")
	    $linkUbah = "surat_pengantar_pengiriman";
	else if($reqJenisNaskahId == "22")
	    $linkUbah = "bon_permintaan_barang";
	else if($reqJenisNaskahId == "23")
	    $linkUbah = "laporan_kerusakan_inventaris";
	else if($reqJenisNaskahId == "24")
	    $linkUbah = "laporan_kerusakan_kendaraan	";
		
	return $linkUbah;
}

function infonomor($nomorpasal, $reqJenisNaskah)
{
	if($reqJenisNaskah == "17" || $reqJenisNaskah == "19" || $reqJenisNaskah == "20")
		$arrdata= array("", "Pertama", "Kedua", "Ketiga", "Keempat", "Kelima", "Keenam", "Ketujuh", "Kedelapan", "Kesembilan", "Kesepuluh", "Kesebelas", "Keduabelas", "Ketigabelas", "Keempatbelas", "Kelimabelas", "Keenambelas", "Ketujuhbelas", "Kedelapanbelas", "Kesembilanbelas", "Keduapuluh", "Keduapuluhsatu", "Keduapuluhdua", "Keduapuluhtiga", "Keduapuluhempat", "Keduapuluhlima");
	// else if($reqJenisNaskah == "8")
	// 	$arrdata= array("", "Pasal 1", "Pasal 2", "Pasal 3", "Pasal 4", "Pasal 5", "Pasal 6", "Pasal 7", "Pasal 8", "Pasal 9", "Pasal 10", "Pasal 11", "Pasal 12", "Pasal 13", "Pasal 14", "Pasal 15", "Pasal 16", "Pasal 17", "Pasal 18", "Pasal 19", "Pasal 20", "Pasal 21", "Pasal 22", "Pasal 23", "Pasal 24", "Pasal 25");
	else if($reqJenisNaskah == "8")
		$arrdata= array("", "BAB I", "BAB II", "BAB III", "BAB IV", "BAB V", "BAB VI", "BAB VII", "BAB VIII", "BAB IX", "BAB X", "BAB XI", "BAB XII", "BAB XIII", "BAB XIV", "BAB XV", "BAB XVI", "BAB XVII", "BAB XVIII", "BAB XIX", "BAB XX", "BAB XXI", "BAB XXII", "BAB XXIII", "BAB XXIV", "BAB XXV");
	else if($reqJenisNaskah == "9")
		$arrdata= array("", "PASAL 1", "PASAL 2", "PASAL 3", "PASAL 4", "PASAL 5", "PASAL 6", "PASAL 7", "PASAL 8", "PASAL 9", "PASAL 10", "PASAL 11", "PASAL 12", "PASAL 13", "PASAL 14", "PASAL 15", "PASAL 16", "PASAL 17", "PASAL 18", "PASAL 19", "PASAL 20", "PASAL 21", "PASAL 22", "PASAL 23", "PASAL 24", "PASAL 25");
	else
		$arrdata= [];

	if(!empty($arrdata[$nomorpasal]))
		return $arrdata[$nomorpasal];
	else
		return "-";
}

function infokelompok()
{

	$arrField= array();

	$pejabat_pengganti = new SatuanKerja();
    $pejabat_pengganti->selectByParamsKelompok(array(),-1,-1,$statement,$order);
    while($pejabat_pengganti->nextRow()){
    	array_push($arrField,array("id"=>$pejabat_pengganti->getField("KELOMPOK_ID"), "nama"=>$pejabat_pengganti->getField("NAMA")));
    }

	// $arrField= array(
	//   array("id"=>"DIREKSI", "nama"=>"Direksi")
	//   , array("id"=>"GM", "nama"=>"General Manager")
	//   , array("id"=>"VP", "nama"=>"VP")
	//   , array("id"=>"SGM", "nama"=>"Senior General Manager")
	//   , array("id"=>"SUPERVISI", "nama"=>"Supervisor")
	//   , array("id"=>"SM", "nama"=>"Senior Manager")
	//   , array("id"=>"MAN", "nama"=>"Manager")
	//   , array("id"=>"ASSISTANT", "nama"=>"Asisstant")
	//   , array("id"=>"KOOR", "nama"=>"Koordinator")
	//   , array("id"=>"NAH", "nama"=>"Nahkoda")
	//   , array("id"=>"KARYAWAN", "nama"=>"Staff")
	//   , array("id"=>"SEK", "nama"=>"SEKERTARIS")
	// );
	// print_r($arrField); exit;
	return $arrField;
}

function infobiayadinas()
{
	$arrField= array(
	  array("nama"=>"Perjalanan antar wilayah (Tiket Pesawat/Kereta/Bus)")
	  , array( "nama"=>"Perjalanan dalam wilayah")
	  , array( "nama"=>"Penginapan")
	  , array( "nama"=>"Lain-lain")
	);
	return $arrField;
}
?>