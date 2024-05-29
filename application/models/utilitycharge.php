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

  class UtilityCharge extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function UtilityCharge()
	{
      $this->Entity(); 
    }
	
	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("Utility_Charge_id", $this->getNextId("Utility_Charge_id","Utility_Charge")); 
		$str = "
				INSERT INTO Utility_Charge (
				   Utility_Charge_id, NAMA
				   ) 
				VALUES (
					'".$this->getField("Utility_Charge_id")."', 
					'".$this->getField("NAMA")."'
				)"; 
		$this->id = $this->getField("Utility_Charge_id");
		$this->query = $str;
		// echo$str;exit;

		return $this->execQuery($str);
  }

  function update()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "
			   UPDATE Utility_Charge
				SET   
					   NAMA      	= '".$this->getField("NAMA")."'
				WHERE  Utility_Charge_id    	= '".$this->getField("Utility_Charge_id")."'
				"; 
		// echo $str;exit;
		$this->query = $str;
		return $this->execQuery($str);
  }

	
	function delete()
	{
        $str = "DELETE FROM Utility_Charge
                WHERE 
                  Utility_Charge_id = ".$this->getField("Utility_Charge_id").""; 
				  
		$this->query = $str;
        return $this->execQuery($str);
  }

  function selectByParams($paramsArray=array(),$limit=-1,$from=-1,$statement="", $order=" ORDER BY A.Utility_Charge_id ASC")
	{
		$str = "
				SELECT 
					A.*
				FROM Utility_Charge A
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
		$str = "SELECT COUNT(1) AS ROWCOUNT FROM Utility_Charge A WHERE 1 = 1 ".$statement; 
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