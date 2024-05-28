<? 
/* *******************************************************************************************************
MODUL NAME 			: MTSN LAWANG
FILE NAME 			: 
AUTHOR				: 
VERSION				: 1.0
MODIFICATION DOC	:
DESCRIPTION			: 
***************************************************************************************************** */

  /***
  * Entity-base class untuk mengimplementasikan tabel kategori.
  * 
  ***/
  include_once(APPPATH.'/models/Entity.php');

  class SettingMengetahui  extends Entity{ 

	var $query;
	var $id;	
    /**
    * Class constructor.
    **/
    function SettingMengetahui ()
	{
      $this->Entity(); 
    }
	
    function insert()
    {
    	$this->setField("SETTING_MENGETAHUI_ID", $this->getNextId("SETTING_MENGETAHUI_ID","SETTING_MENGETAHUI")); 

    	$str = "INSERT INTO SETTING_MENGETAHUI (SETTING_MENGETAHUI_ID, NAMA, STATUS,LAST_CREATE_USER)
    	VALUES (
    	'".$this->getField("SETTING_MENGETAHUI_ID")."',
    	'".$this->getField("NAMA")."',
    	".$this->getField("STATUS").",
    	'".$this->getField("LAST_CREATE_USER")."'  	
    )";

    $this->id = $this->getField("SETTING_MENGETAHUI_ID");
    $this->query= $str;
		//echo $str;exit();
    return $this->execQuery($str);
	}

	function insertdetil()
    {
    	$this->setField("SETTING_MENGETAHUI_DETIL_ID", $this->getNextId("SETTING_MENGETAHUI_DETIL_ID","SETTING_MENGETAHUI_DETIL")); 

    	$str = "INSERT INTO SETTING_MENGETAHUI_DETIL (SETTING_MENGETAHUI_DETIL_ID, SETTING_MENGETAHUI_ID, SATUAN_KERJA_ID, URUT)
    	VALUES (
    	".$this->getField("SETTING_MENGETAHUI_DETIL_ID").",
    	".$this->getField("SETTING_MENGETAHUI_ID").",
    	'".$this->getField("SATUAN_KERJA_ID")."',
    	".$this->getField("URUT")."
    )";

    $this->id = $this->getField("SETTING_MENGETAHUI_DETIL_ID");
    $this->query= $str;
		// echo $str;exit();
    return $this->execQuery($str);
	}

	function update()
	{
		$str = "
		UPDATE SETTING_MENGETAHUI
		SET    
		NAMA ='".$this->getField("NAMA")."',
		STATUS =".$this->getField("STATUS").",
		LAST_UPDATE_USER ='".$this->getField("LAST_UPDATE_USER")."'
		WHERE SETTING_MENGETAHUI_ID= '".$this->getField("SETTING_MENGETAHUI_ID")."'";
		$this->query = $str;
		  // echo $str;exit;
		return $this->execQuery($str);
	}

	function updateStatusNonAktif()
	{
		$str = "
		UPDATE SETTING_MENGETAHUI
		SET    
		STATUS = 1
		";
		$this->query = $str;
		  // echo $str;exit;
		return $this->execQuery($str);
	}

	function delete($statement= "")
	{
		$str = "DELETE FROM SETTING_MENGETAHUI
		WHERE SETTING_MENGETAHUI_ID= ".$this->getField("SETTING_MENGETAHUI_ID").""; 
		$this->query = $str;
		  // echo $str;exit();
		return $this->execQuery($str);
	}

	function deletedetil($statement= "")
	{
		$str = "DELETE FROM SETTING_MENGETAHUI_DETIL
		WHERE SETTING_MENGETAHUI_ID= ".$this->getField("SETTING_MENGETAHUI_ID").""; 
		$this->query = $str;
		  // echo $str;exit();
		return $this->execQuery($str);
	}

	function deletedetilbaris($statement= "")
	{
		$str = "DELETE FROM SETTING_MENGETAHUI_DETIL
		WHERE SETTING_MENGETAHUI_ID= ".$this->getField("SETTING_MENGETAHUI_ID")."
		AND SETTING_MENGETAHUI_DETIL_ID= ".$this->getField("SETTING_MENGETAHUI_DETIL_ID")."
		"; 
		$this->query = $str;
		  // echo $str;exit();
		return $this->execQuery($str);
	}

	function selectByParamsMonitoring($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order="ORDER BY A.SETTING_MENGETAHUI_ID ASC")
	{
		$str = "
		SELECT *
		FROM SETTING_MENGETAHUI A
		WHERE 1=1 ";
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val'";
		}

		$str .= $statement." ".$order;
		$this->query = $str;
		return $this->selectLimit($str,$limit,$from); 
	}

	function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order="ORDER BY A.SETTING_MENGETAHUI_ID ASC")
	{
		$str = "
		
		SELECT
		A.*,
		CASE WHEN A.STATUS IS  NULL THEN 'Aktif' 
		ELSE 'Tidak Aktif'
		END 
		STATUS_INFO
		FROM SETTING_MENGETAHUI A 
		WHERE 1=1

		 ";
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val'";
		}

		$str .= $statement." ".$order;
		$this->query = $str;
		return $this->selectLimit($str,$limit,$from); 
	}

	function selectByParamsDetil($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order="ORDER BY A.URUT ASC")
	{
		$str = "
		
		SELECT
		A.*
		FROM SETTING_MENGETAHUI_DETIL A 
		WHERE 1=1

		 ";
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val'";
		}

		$str .= $statement." ".$order;
		$this->query = $str;
		return $this->selectLimit($str,$limit,$from); 
	}


	function getCountByParams($paramsArray=array(), $statement="")
	{
		$str = "SELECT COUNT(1) AS ROWCOUNT FROM SETTING_MENGETAHUI A WHERE 1=1 ".$statement;
		while(list($key,$val)=each($paramsArray))
		{
			$str .= " AND $key = 	'$val' ";
		}
		$this->query = $str;
		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0; 
	}
} 
?>