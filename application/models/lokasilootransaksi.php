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
  include_once("Entity.php");

  class LokasiLooTransaksi extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function LokasiLooTransaksi()
	{
      $this->Entity(); 
    }
	
	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("LANTAI_LOO_DETIL_id", $this->getNextId("LANTAI_LOO_DETIL_id","LANTAI_LOO_DETIL")); 
		$str = "
				INSERT INTO LANTAI_LOO_DETIL (
				   LANTAI_LOO_DETIL_id, LANTAI_LOO_id, AWAL, AKHIR, NILAI, LOKASI_LOO_ID, AREA
				   ) 
				VALUES (
					".$this->getField("LANTAI_LOO_DETIL_id").", 
					".$this->getField("LANTAI_LOO_ID").", 
					".$this->getField("AWAL").", 
					".$this->getField("AKHIR").",
					".$this->getField("NILAI").",
					".$this->getField("LOKASI_LOO_ID").",
					'".$this->getField("AREA")."'
				)"; 
		$this->id = $this->getField("LANTAI_LOO_DETIL_id");
		$this->query = $str;
		// echo $str;exit;

		return $this->execQuery($str);
  }
	
  function update()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "
			   UPDATE LANTAI_LOO_DETIL
				SET   
					   LANTAI_LOO_ID      	= ".$this->getField("LANTAI_LOO_ID").",
					   AWAL      	= ".$this->getField("AWAL").",
					   AKHIR      	= ".$this->getField("AKHIR").",
					   NILAI		= ".$this->getField("NILAI")."
					   LOKASI_LOO_ID		= ".$this->getField("LOKASI_LOO_ID")."
					   AREA		= '".$this->getField("AREA")."'
				WHERE  LANTAI_LOO_DETIL_id    	= ".$this->getField("LANTAI_LOO_DETIL_id")."
				"; 
		// echo $str;exit;
		$this->query = $str;
		return $this->execQuery($str);
  }

  
	function delete()
	{
        $str = "DELETE FROM LANTAI_LOO_DETIL
                WHERE 
                  LANTAI_LOO_DETIL_id = ".$this->getField("LANTAI_LOO_DETIL_id").""; 
				  
		$this->query = $str;
        return $this->execQuery($str);
  }
	
	function selectByParamsComboLantai($paramsArray=array(),$limit=-1,$from=-1,$statement="", $order=" ORDER BY A.Lantai_LOO_ID ASC")
	{
		$str = "
				SELECT 
					A.*
				FROM Lantai_LOO A
				WHERE 1 = 1
			"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$order;
		$this->query = $str;
		return $this->selectLimit($str,$limit,$from); 
  }

  function selectByParams($paramsArray=array(),$limit=-1,$from=-1,$statement="", $order=" ORDER BY A.LANTAI_LOO_DETIL_id ASC")
	{
		$str = "
				SELECT 
					A.*
				FROM LANTAI_LOO_DETIL A
				WHERE 1 = 1
			"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$order;
		$this->query = $str;
		return $this->selectLimit($str,$limit,$from); 
  }
	
  function getCountByParams($paramsArray=array(), $statement="")
	{
		$str = "SELECT COUNT(LOKASI_LOO_ID) AS ROWCOUNT FROM LOKASI_LOO A WHERE 1 = 1 ".$statement; 
		while(list($key,$val)=each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		
		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0; 
  }
}
?>