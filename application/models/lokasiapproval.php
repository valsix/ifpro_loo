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

  class LokasiApproval extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function LokasiApproval()
	{
      $this->Entity(); 
    }
	
	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("LOKASI_APPROVAL_ID", $this->getNextId("LOKASI_APPROVAL_ID","AKTIFITAS.LOKASI_APPROVAL")); 
		$str = "
				INSERT INTO AKTIFITAS.LOKASI_APPROVAL (
				   LOKASI_APPROVAL_ID, LOKASI_ID, PEGAWAI_ID, LAST_CREATE_USER, LAST_CREATE_DATE) 
				VALUES ( '".$this->getField("LOKASI_APPROVAL_ID")."', '".$this->getField("LOKASI_ID")."', '".$this->getField("PEGAWAI_ID")."', '".$this->getField("LAST_CREATE_USER")."', CURRENT_DATE)"; 
		$this->id = $this->getField("LOKASI_APPROVAL_ID");
		$this->query = $str;

		return $this->execQuery($str);
    }

    function update()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "
			   UPDATE AKTIFITAS.LOKASI_APPROVAL
				SET    MEETING_ROOM_ID  = '".$this->getField("MEETING_ROOM_ID")."',
					   LOKASI_ID       	= '".$this->getField("NAMA")."',
					   PEGAWAI_ID       = '".$this->getField("PEGAWAI_ID")."',
					   LAST_UPDATE_USER = '".$this->getField("LAST_UPDATE_USER")."',
					   LAST_UPDATE_DATE = CURRENT_DATE
				WHERE  LOKASI_APPROVAL_ID    	= '".$this->getField("LOKASI_APPROVAL_ID")."'
				"; 
				$this->query = $str;
		return $this->execQuery($str);
    }

    function updateByField()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "UPDATE AKTIFITAS.LOKASI_APPROVAL A SET
				  ".$this->getField("FIELD")." 		= '".$this->getField("FIELD_VALUE")."',
				  ".$this->getField("FIELD_VALIDATOR")." 	= '".$this->getField("FIELD_VALUE_VALIDATOR")."'
				WHERE LOKASI_APPROVAL_ID = ".$this->getField("LOKASI_APPROVAL_ID")."
				"; 
				$this->query = $str;
		return $this->execQuery($str);
    }	
	
	function delete()
	{
        $str = "DELETE FROM AKTIFITAS.LOKASI_APPROVAL
                WHERE 
                  LOKASI_APPROVAL_ID = ".$this->getField("LOKASI_APPROVAL_ID").""; 
				  
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
    function selectByParams($paramsArray=array(),$limit=-1,$from=-1,$statement="", $order=" ORDER BY LOKASI_APPROVAL_ID ASC")
	{
		$str = "
				SELECT 
				LOKASI_APPROVAL_ID, LOKASI_ID, PEGAWAI_ID
				FROM AKTIFITAS.LOKASI_APPROVAL A
				WHERE 1 = 1
			"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$order;
		// echo($str);
		$this->query = $str;
		return $this->selectLimit($str,$limit,$from); 
    }
	
	function selectByParamsLike($paramsArray=array(),$limit=-1,$from=-1, $statement="")
	{
		$str = "    
				SELECT 
				LOKASI_APPROVAL_ID, LOKASI_ID, PEGAWAI_ID
				FROM AKTIFITAS.LOKASI_APPROVAL A
				WHERE 1 = 1
			"; 
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key LIKE '%$val%' ";
		}
		
		$str .= $statement." ORDER BY LOKASI_APPROVAL_ID DESC";
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
		$str = "SELECT COUNT(LOKASI_APPROVAL_ID) AS ROWCOUNT FROM AKTIFITAS.LOKASI_APPROVAL WHERE 1 = 1 ".$statement; 
		while(list($key,$val)=each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		echo $str;
		$this->query = $str;
		$this->select($str); 
		if($this->firstRow())
			return $this->getField("ROWCOUNT"); 
		else 
			return 0; 
    }
			
    function getCountByParamsLike($paramsArray=array())
	{
		$str = "SELECT COUNT(LOKASI_APPROVAL_ID) AS ROWCOUNT FROM AKTIFITAS.LOKASI_APPROVAL WHERE 1 = 1 "; 
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