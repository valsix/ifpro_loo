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

  class MeetingRequestMakanan extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function MeetingRequestMakanan()
	{
      $this->Entity(); 
    }
	
	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("MEETING_REQUEST_MAKANAN_ID", $this->getNextId("MEETING_REQUEST_MAKANAN_ID","AKTIFITAS.MEETING_REQUEST_MAKANAN")); 
		$str = "
				INSERT INTO AKTIFITAS.MEETING_REQUEST_MAKANAN (
				   MEETING_REQUEST_MAKANAN_ID, MEETING_REQUEST_ID, MAKANAN_ID, PORSI, LAST_CREATE_USER, LAST_CREATE_DATE) 
				VALUES ( 
					'".$this->getField("MEETING_REQUEST_MAKANAN_ID")."', 
					'".$this->getField("MEETING_REQUEST_ID")."', 
					'".$this->getField("MAKANAN_ID")."', 
					'".$this->getField("PORSI")."', 
					'".$this->getField("LAST_CREATE_USER")."', 
					CURRENT_DATE)"; 
		$this->id = $this->getField("MEETING_REQUEST_MAKANAN_ID");
		$this->query = $str;

		return $this->execQuery($str);
    }

    function update()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "
			   UPDATE AKTIFITAS.MEETING_REQUEST_MAKANAN
				SET    MEETING_REQUEST_MAKANAN_ID = '".$this->getField("MEETING_REQUEST_MAKANAN_ID")."',
					   MAKANAN_ID      	= '".$this->getField("MAKANAN_ID")."',
					   PORSI      		= '".$this->getField("PORSI")."',
					   LAST_UPDATE_USER = '".$this->getField("LAST_UPDATE_USER")."',
					   LAST_UPDATE_DATE = CURRENT_DATE
				WHERE  MEETING_REQUEST_MAKANAN_ID    	= '".$this->getField("MEETING_REQUEST_MAKANAN_ID")."'
				"; 
				$this->query = $str;
		return $this->execQuery($str);
    }

    function updateByField()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "UPDATE AKTIFITAS.MEETING_REQUEST_MAKANAN A SET
				  ".$this->getField("FIELD")." 		= '".$this->getField("FIELD_VALUE")."',
				  ".$this->getField("FIELD_VALIDATOR")." 	= '".$this->getField("FIELD_VALUE_VALIDATOR")."'
				WHERE MEETING_REQUEST_MAKANAN_ID = ".$this->getField("MEETING_REQUEST_MAKANAN_ID")."
				"; 
				$this->query = $str;
		return $this->execQuery($str);
    }	
	
	function delete()
	{
        $str = "DELETE FROM AKTIFITAS.MEETING_REQUEST_MAKANAN
                WHERE 
                  MEETING_REQUEST_MAKANAN_ID = ".$this->getField("MEETING_REQUEST_MAKANAN_ID").""; 
				  
		$this->query = $str;
        return $this->execQuery($str);
    }

    /** 
    * Cari record berdasarkan array parameter dan limit tampilan 
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","nama"=>"yyy") 
    * @param int limit Jumlah maksimal record yang akan diambil 
    * @param int from Awal record yang diambil 
    * @return boolean True jika sukses, false jika tidak 
    **/ 
    function selectByParams($paramsArray=array(),$limit=-1,$from=-1,$statement="", $order=" ORDER BY A.MAKANAN_ID ASC")
	{
		$str = "
				SELECT 
				MEETING_REQUEST_MAKANAN_ID, MEETING_REQUEST_ID, A.MAKANAN_ID, PORSI, B.NAMA MAKANAN
				FROM AKTIFITAS.MEETING_REQUEST_MAKANAN A INNER JOIN AKTIFITAS.MAKANAN B ON A.MAKANAN_ID = B.MAKANAN_ID
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
	
	function selectByParamsLike($paramsArray=array(),$limit=-1,$from=-1, $statement="")
	{
		$str = "    
				SELECT 
				MEETING_REQUEST_MAKANAN_ID, MEETING_REQUEST_ID, MAKANAN_ID, PORSI
				FROM AKTIFITAS.MEETING_REQUEST_MAKANAN A INNER JOIN AKTIFITAS.MAKANAN B ON A.MAKANAN_ID = B.MAKANAN_ID
				WHERE 1 = 1
			"; 
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key LIKE '%$val%' ";
		}
		
		$str .= $statement." ORDER BY MEETING_REQUEST_MAKANAN_ID DESC";
		$this->query = $str;		
		return $this->selectLimit($str,$limit,$from); 
    }	
    /** 
    * Hitung jumlah record berdasarkan parameter (array). 
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","nama"=>"yyy") 
    * @return long Jumlah record yang sesuai kriteria 
    **/ 
    function getCountByParams($paramsArray=array(), $statement="")
	{
		$str = "SELECT COUNT(MEETING_REQUEST_MAKANAN_ID) AS ROWCOUNT FROM AKTIFITAS.MEETING_REQUEST_MAKANAN WHERE 1 = 1 ".$statement; 
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
			
    function getCountByParamsLike($paramsArray=array())
	{
		$str = "SELECT COUNT(MEETING_REQUEST_MAKANAN_ID) AS ROWCOUNT FROM AKTIFITAS.MEETING_REQUEST_MAKANAN WHERE 1 = 1 "; 
		while(list($key,$val)=each($paramsArray))
		{
			$str .= " AND $key LIKE '%$val%' ";
		}
		
		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0; 
    }	
  } 
?>