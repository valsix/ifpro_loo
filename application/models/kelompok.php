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

  class Kelompok  extends Entity{ 

	var $query;
	var $id;	
    /**
    * Class constructor.
    **/
    function Kelompok ()
	{
      $this->Entity(); 
    }
	
    function insert()
    {
    	$this->setField("KELOMPOK_ID", $this->getNextId("KELOMPOK_ID","KELOMPOK")); 

    	$str = "INSERT INTO KELOMPOK (KELOMPOK_ID, NAMA, BIAYA)
    	VALUES 
    	(
	    	'".$this->getField("KELOMPOK_ID")."',
	    	'".$this->getField("NAMA")."',
	    	".$this->getField("BIAYA")."    	
	    )";

	    $this->id = $this->getField("KELOMPOK_ID");
	    $this->query= $str;
			//echo $str;exit();
	    return $this->execQuery($str);
	}

	function update()
	{
		$str = "
		UPDATE KELOMPOK
		SET    
		NAMA ='".$this->getField("NAMA")."',
		BIAYA =".$this->getField("BIAYA")."
		WHERE KELOMPOK_ID= '".$this->getField("KELOMPOK_ID")."'";
		$this->query = $str;
		  // echo $str;exit;
		return $this->execQuery($str);
	}

	function delete($statement= "")
	{
		$str = "DELETE FROM KELOMPOK
		WHERE KELOMPOK_ID= ".$this->getField("KELOMPOK_ID").""; 
		$this->query = $str;
		  // echo $str;exit();
		return $this->execQuery($str);
	}

	function selectByParamsMonitoring($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order="ORDER BY A.KELOMPOK_ID ASC")
	{
		$str = "
		SELECT *
		FROM KELOMPOK A
		WHERE 1=1 ";
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val'";
		}

		$str .= $statement." ".$order;
		$this->query = $str;
		return $this->selectLimit($str,$limit,$from); 
	}

	function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order="ORDER BY A.KELOMPOK_ID ASC")
	{
		$str = "
		SELECT *
		FROM KELOMPOK A
		WHERE 1=1 ";
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val'";
		}

		$str .= $statement." ".$order;
		$this->query = $str;
		return $this->selectLimit($str,$limit,$from); 
	}

	function selectByParamsStpd($paramsArray=array(),$limit=-1,$from=-1, $statement="", $statementdetil="", $order="ORDER BY A.KELOMPOK_ID ASC")
	{
		$str = "
		SELECT
			B.KELOMPOK_ID KELOMPOK_ID_STPD, KELOMPOK_ORANG, PENGAJUAN_BIAYA, ALOKASI_BIAYA, B.REALISASI
			, A.*
		FROM KELOMPOK A
		LEFT JOIN PERMOHONAN_STPD_BIAYA_DINAS B ON B.KELOMPOK_ID = A.KELOMPOK_ID ".$statementdetil."
		WHERE 1=1 ";
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
		$str = "SELECT COUNT(1) AS ROWCOUNT FROM KELOMPOK A WHERE 1=1 ".$statement;
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