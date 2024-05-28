<?
/* *******************************************************************************************************
MODUL NAME 			: SIMWEB
FILE NAME 			: date.func.php
AUTHOR				: MRF
VERSION				: 1.0
MODIFICATION DOC	:
DESCRIPTION			: Functions to handle date operations
***************************************************************************************************** */

	function dateToPage($_date){
		$arrDate = explode("-", $_date);
		$_date = $arrDate[2]."-".$arrDate[1]."-".$arrDate[0];
		return $_date;
	}

	function addWIB($_date){
		if($_date == "")
			return $_date;
		else
			return ", ".$_date." WIB";		
	}

	function datetimeToPage($_date, $_type){
		if($_date == "")
			return "";
		$arrDateTime = explode(" ", $_date);
		if($_type == "date")
		{
			$arrDate = explode("-", $arrDateTime[0]);
			$_date = $arrDate[2]."-".$arrDate[1]."-".$arrDate[0];
			return $_date;
		}
		else
		{
			$_date = $arrDateTime[1];
			$arrTime = explode(":", $_date);
			if($_type == "hour")
				return $arrTime[0];
			elseif($_type == "minutes")
				return $arrTime[1];						
			else
				return $_date;							
		}
	}
	
	function dateToPageCheck($_date){
		if($_date == "")
		{
			return "";	
		}
		$arrDate = explode("-", $_date);
		$_date = $arrDate[2]."-".$arrDate[1]."-".$arrDate[0];
		return $_date;
	}
	
	function dateToPageCheck2($_date){
		if($_date == "")
		{
			return "";	
		}
		$arrDate = explode("/", $_date);
		$_date = $arrDate[2]."-".$arrDate[1]."-".$arrDate[0];
		return $_date;
	}
	
	function dateTimeToPageCheck($_date){
		if($_date == "")
		{
			return "";	
		}
		$arrDateTime = explode(" ", $_date);
		$arrDate = explode("-", $arrDateTime[0]);
		
		if($arrDateTime[1] == "")
		{
			$_date = $arrDate[2]."-".generateZeroDate($arrDate[1],2)."-".generateZeroDate($arrDate[0], 2);
		}
		else
		{
			$_date = $arrDate[2]."-".generateZeroDate($arrDate[1],2)."-".generateZeroDate($arrDate[0], 2)." ".$arrDateTime[1];
		}
		return $_date;
	}
	
	function dateToDB($_date){
		$arrDate = explode("-", $_date);
		$_date = $arrDate[2]."-".$arrDate[1]."-".$arrDate[0];
		return $_date;
	}
	
	function dateToDBCheck($_date){
		if($_date == "")
		{
			return "NULL";	
		}
		$arrDate = explode("-", $_date);
		$_date = $arrDate[2]."-".generateZeroDate($arrDate[1],2)."-".generateZeroDate($arrDate[0], 2);
		// return "TO_DATE('".$_date."', 'YYYY-MM-f')";
		return "TO_DATE('".$_date."', 'YYYY-MM-DD')";
	}
	
	function dateToDBCheckMsql($_date){
		if($_date == "")
		{
			return "NULL";	
		}
		$arrDate = explode("-", $_date);
		$_date = $arrDate[2]."-".generateZeroDate($arrDate[1],2)."-".generateZeroDate($arrDate[0], 2);
		return "STR_TO_DATE('".$_date."', '%Y-%m-%d')";
	}

	function dateTimeToDBCheck($_date){
		if($_date == "")
		{
			return "NULL";	
		}
		$arrDateTime = explode(" ", $_date);
		$arrDate = explode("-", $arrDateTime[0]);
		
		$_date = $arrDate[2]."-".generateZeroDate($arrDate[1],2)."-".generateZeroDate($arrDate[0], 2);
		return "TO_TIMESTAMP('".$_date." ".$arrDateTime[1]."', 'YYYY-MM-DD HH24:MI:SS')";
	}

	function dateTimeToDBCheck2($_date){
		if($_date == "")
		{
			return "NULL";	
		}
		$arrDateTime = explode(" ", $_date);
		$arrDate = explode("/", $arrDateTime[0]);
		
		$_date = generateZeroDate($arrDate[0], 2)."/".generateZeroDate($arrDate[1],2)."/".$arrDate[2];
		return "TO_TIMESTAMP('".$_date." ".$arrDateTime[1]."', 'DD/MM/YYYY HH24:MI:SS')";
	}
	
	function generateZeroDate($varId, $digitGroup, $digitCompletor = "0")
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
	
	function setTime($varId, $tempVal)
	{
		if($tempVal == "")
			return "";
		else
		{
			$value= date('Y-m-d H:i', $varId * 86400 + mktime(0, 0, 0));
			$arrVarId= explode(" ",$value);
			$hari= $arrVarId[0];
			$time= $arrVarId[1];
			
			//$temp= $value;
			//$temp= $varId;
			$hari_sekarang= date('Y-m-d');
			if($hari_sekarang == $hari)
			{
				if(strlen($time) == 5)
					$temp= $time;
				else
					$temp= '0'.$time;
			}
			else
			{
				$temp= "24:00";
			}
			//$temp= $value;
			return $temp;
		}
	}
	function dateMixToDB($_date){
		$arrDate = explode("/", $_date);
		$_date = $arrDate[2]."-".$arrDate[1]."-".$arrDate[0];
		return $_date;
	}
	
	function datetimeToDB($_datetime){
		if($_datetime == "")
		{
			return "NULL";	
		}
//		$arrDate = explode("-", $_date);
//		$_date = $arrDate[0]."-".$arrDate[1]."-".$arrDate[2];
		return "TO_DATE('".$_datetime."', 'DD-MM-YYYY:HH24:MI')";
		//return "'".$_date."'";
	}		
	
	function getDayMonth($_date) {
		$tanggal = substr($_date,0,2);
		$bulan = substr($_date,2,4)*1;
		
		return $tanggal.' '.getNameMonth($bulan);
	}
		
	function getDay($_date) {
		$arrDate = explode("-", $_date);
		return $arrDate[2];
	}
	
	function getMonth($_date) {
		$arrDate = explode("-", $_date);
		return $arrDate[1];
	}
	
	function getYear($_date) {
		$arrDate = explode("-", $_date);
		return $arrDate[0];
	}

	function getNamePeriode($periode) {
		$bulan = substr($periode, 0,2);
		$tahun = substr($periode, 2,4);
		 
		return getNameMonth((int)$bulan)." ".$tahun;
	}

	function getNamePeriodeExt($periode) {
		$bulan = substr($periode, 0,2);
		$tahun = substr($periode, 2,4);
		 
		return getExtMonth((int)$bulan)." ".$tahun;
	}

	function getTahunPeriode($periode) {
		$bulan = substr($periode, 0,2);
		$tahun = substr($periode, 2,4);
		 
		return $tahun;
	}

	function getBulanPeriode($periode) {
		$bulan = substr($periode, 0,2);
		$tahun = substr($periode, 2,4);
		 
		return $bulan;
	}

	function getNameMonth($number) {
		$arrMonth = array("1"=>"Januari", "2"=>"Februari", "3"=>"Maret", "4"=>"April", "5"=>"Mei", 
						  "6"=>"Juni", "7"=>"Juli", "8"=>"Agustus", "9"=>"September", "10"=>"Oktober", 
						  "11"=>"November", "12"=>"Desember");
		return $arrMonth[$number];
	}

	function getExtMonth($number) {
		$arrMonth = array("1"=>"Jan", "2"=>"Feb", "3"=>"Mar", "4"=>"Apr", "5"=>"Mei", 
						  "6"=>"Jun", "7"=>"Jul", "8"=>"Agt", "9"=>"Sept", "10"=>"Okt", 
						  "11"=>"Nov", "12"=>"Des");
		return $arrMonth[$number];
	}

	function getFormattedInfoDateTimeCheck($_date, $param="-", $showTime=true)
	{
		if($_date == "")
		{
			return "";	
		}
		
		$_date = explode(" ", $_date);
		$explodedDate = $_date[0];
		$explodedTime = $_date[1];
		
		$arrDate = explode("-", $explodedDate);
		$_month = intval($arrDate[1]);
		
		$date = $arrDate[2].$param.$_month.$param.$arrDate[0];
		$time = $explodedTime;

		if($showTime == true)
			$datetime = $date.' '.$time;
		else
			$datetime = $date;
		return $datetime;
	}

	function getFormattedExtDateTimeCheck($_date, $showTime=true)
	{
		if($_date == "")
		{
			return "";	
		}
		
		$_date = explode(" ", $_date);
		$explodedDate = $_date[0];
		$explodedTime = $_date[1];
		
		$arrDate = explode("-", $explodedDate);
		$_month = intval($arrDate[1]);
		
		$date = $arrDate[2].' '.getExtMonth($_month).' '.$arrDate[0];
		$time = $explodedTime;

		if($showTime == true)
			$datetime = $date.', '.$time;
		else
			$datetime = $date;
		return $datetime;
	}

	function getRomawiMonth($number) {
		$arrMonth = array("1"=>"I", "2"=>"II", "3"=>"III", "4"=>"IV", "5"=>"V", 
						  "6"=>"VI", "7"=>"VII", "8"=>"VIII", "9"=>"IX", "10"=>"X", 
						  "11"=>"XI", "12"=>"XII");
		return $arrMonth[$number];
	}
	
	// date input : database
	function getFormattedDateJson($_date)
	{
		$arrMonth = array("1"=>"Januari", "2"=>"Februari", "3"=>"Maret", "4"=>"April", "5"=>"Mei", 
						  "6"=>"Juni", "7"=>"Juli", "8"=>"Agustus", "9"=>"September", "10"=>"Oktober", 
						  "11"=>"November", "12"=>"Desember");

		$arrDate = explode("-", $_date);
		$_month = intval($arrDate[1]);

		$date = $arrDate[2].' '.$arrMonth[$_month].' '.$arrDate[0];
		return $date;
	}
	
	function getValueDate($_date)
	{		
		$arrDate = explode("-", $_date);
		$_month = intval($arrDate[1]);
		
		$jumHari = cal_days_in_month(CAL_GREGORIAN, $_month, $arrDate[0]);	
		$date = $jumHari;
		
		return $date;
	}
	
	function getFormattedDate($_date)
	{
		$arrMonth = array("1"=>"Januari", "2"=>"Februari", "3"=>"Maret", "4"=>"April", "5"=>"Mei", 
						  "6"=>"Juni", "7"=>"Juli", "8"=>"Agustus", "9"=>"September", "10"=>"Oktober", 
						  "11"=>"November", "12"=>"Desember");

		$arrDate = explode("-", $_date);
		$_month = intval($arrDate[1]);

		$date = ''.$arrDate[2].' '.$arrMonth[$_month].' '.$arrDate[0].'';
		return $date;
	}

	function getFormattedDate2($_date)
	{
		$arrMonth = array("1"=>"Januari", "2"=>"Februari", "3"=>"Maret", "4"=>"April", "5"=>"Mei", 
						  "6"=>"Juni", "7"=>"Juli", "8"=>"Agustus", "9"=>"September", "10"=>"Oktober", 
						  "11"=>"November", "12"=>"Desember");

		$arrDate = explode("-", $_date);
		$_month = intval($arrDate[1]);

		$date = ''.$arrDate[0].' '.$arrMonth[$_month].' '.$arrDate[2].'';
		return $date;
	}
	
	// date input : database
	function getFormattedDateTime($_date, $showTime=true)
	{
		$_date = explode(" ", $_date);
		$explodedDate = $_date[0];
		$explodedTime = $_date[1];
		
		$arrMonth = array("1"=>"Januari", "2"=>"Februari", "3"=>"Maret", "4"=>"April", "5"=>"Mei", 
						  "6"=>"Juni", "7"=>"Juli", "8"=>"Agustus", "9"=>"September", "10"=>"Oktober", 
						  "11"=>"November", "12"=>"Desember");

		$arrDate = explode("-", $explodedDate);
		$_month = intval($arrDate[1]);
		
		$date = $arrDate[2].' '.$arrMonth[$_month].' '.$arrDate[0];
		$time = $explodedTime;

		if($showTime == true)
			$datetime = $date.',&nbsp;'.$time;
		else
			// $datetime = '<span style="white-space:nowrap">'.$date.'</span>';
			$datetime = $date;
		return $datetime;
	}
	
	// date input : database
	function getFormattedDateTimeCheck($_date, $showTime=true)
	{
		if($_date == "")
		{
			return "";	
		}
		
		$_date = explode(" ", $_date);
		$explodedDate = $_date[0];
		$explodedTime = $_date[1];
		
		$arrMonth = array("1"=>"Januari", "2"=>"Februari", "3"=>"Maret", "4"=>"April", "5"=>"Mei", 
						  "6"=>"Juni", "7"=>"Juli", "8"=>"Agustus", "9"=>"September", "10"=>"Oktober", 
						  "11"=>"November", "12"=>"Desember");

		$arrDate = explode("-", $explodedDate);
		$_month = intval($arrDate[1]);
		
		$date = $arrDate[2].' '.$arrMonth[$_month].' '.$arrDate[0];
		$time = $explodedTime;

		if($showTime == true)
			$datetime = $date.',&nbsp;'.$time;
		else
			$datetime = '<span style="white-space:nowrap">'.$date.'</span>';
		return $datetime;
	}

	// date input : database
	function getFormattedDateTimeNoSpace($_date, $showTime=true)
	{
		$_date = explode(" ", $_date);
		$explodedDate = $_date[0];
		$explodedTime = $_date[1];
		
		$arrMonth = array("1"=>"Januari", "2"=>"Februari", "3"=>"Maret", "4"=>"April", "5"=>"Mei", 
						  "6"=>"Juni", "7"=>"Juli", "8"=>"Agustus", "9"=>"September", "10"=>"Oktober", 
						  "11"=>"November", "12"=>"Desember");

		$arrDate = explode("-", $explodedDate);
		$_month = intval($arrDate[1]);
		
		$date = $arrDate[2].' '.$arrMonth[$_month].' '.$arrDate[0];
		$time = $explodedTime;

		if($showTime == true)
			$datetime = $date.' '.substr($time, 0, 5);
		else
			$datetime = '<span style="white-space:nowrap">'.$date.'</span>';
		return $datetime;
	}	
	
	function getJumlahHariTanpaWeekend($tanggal_awal, $tanggal_akhir)
	{
		$tanggal = $tanggal_awal;
		while($tanggal == $tanggal_akhir)
		{
			
		}	
			
	}
	function add_date($givendate,$day=0,$mth=0,$yr=0) {
		$cd = strtotime($givendate);
		$newdate = date('Y-m-d h:i:s', mktime(date('h',$cd),
		date('i',$cd), date('s',$cd), date('m',$cd)+$mth,
		date('d',$cd)+$day, date('Y',$cd)+$yr));
		
		return $newdate;
    }
	
	function getSelectFormattedDate($_date)
	{
		$arrMonth = array("01"=>"Januari", "02"=>"Februari", "03"=>"Maret", "04"=>"April", "05"=>"Mei", 
						  "06"=>"Juni", "07"=>"Juli", "08"=>"Agustus", "09"=>"September", "10"=>"Oktober", 
						  "11"=>"November", "12"=>"Desember");

		$date = $arrMonth[$_date];
		return $date;
	}
	
	function maxHariPeriode($reqPeriode)
	{
		$reqTahun= substr($reqPeriode,2,4);
		$reqBulan= substr($reqPeriode,0,2);
		$date=$reqTahun.'-'.$reqBulan;
		return date("t",strtotime($date));
	}
	
	function getNamaHari($hari, $bulan, $tahun)
	{
		//$x= mktime(0, 0, 0, date("m"), date("d"), date("Y"));
		$x= mktime(0, 0, 0, $bulan, $hari, $tahun);
		$namahari = date("l", $x);
		
		if ($namahari == "Sunday") $namahari = "Minggu";
		else if ($namahari == "Monday") $namahari = "Senin";
		else if ($namahari == "Tuesday") $namahari = "Selasa";
		else if ($namahari == "Wednesday") $namahari = "Rabu";
		else if ($namahari == "Thursday") $namahari = "Kamis";
		else if ($namahari == "Friday") $namahari = "Jumat";
		else if ($namahari == "Saturday") $namahari = "Sabtu";
		
		return $namahari;
	}
?>