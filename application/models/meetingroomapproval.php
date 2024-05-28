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

  class MeetingRoomApproval extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function MeetingRoomApproval()
	{
      $this->Entity(); 
    }
	
	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("MEETING_ROOM_APPROVAL_ID", $this->getNextId("MEETING_ROOM_APPROVAL_ID","AKTIFITAS.MEETING_ROOM_APPROVAL")); 
		$str = "
				INSERT INTO AKTIFITAS.MEETING_ROOM_APPROVAL (
				   MEETING_ROOM_APPROVAL_ID, MEETING_ROOM_ID, PEGAWAI_ID, PEGAWAI, LAST_CREATE_USER, LAST_CREATE_DATE) 
				VALUES ( '".$this->getField("MEETING_ROOM_APPROVAL_ID")."', '".$this->getField("MEETING_ROOM_ID")."', '".$this->getField("PEGAWAI_ID")."',
					'".$this->getField("PEGAWAI")."', '".$this->getField("LAST_CREATE_USER")."', 
					CURRENT_DATE)"; 
		$this->id = $this->getField("MEETING_ROOM_APPROVAL_ID");
		$this->query = $str;

		return $this->execQuery($str);
    }


    function updateByField()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "UPDATE AKTIFITAS.MEETING_ROOM_APPROVAL A SET
				  ".$this->getField("FIELD")." 		= '".$this->getField("FIELD_VALUE")."'
				WHERE MEETING_ROOM_APPROVAL_ID = ".$this->getField("MEETING_ROOM_APPROVAL_ID")."
				"; 
				$this->query = $str;
		return $this->execQuery($str);
    }	
	
	
	function deleteParent()
	{
        $str = "DELETE FROM AKTIFITAS.MEETING_ROOM_APPROVAL
                WHERE 
                  MEETING_ROOM_ID = ".$this->getField("MEETING_ROOM_ID").""; 
				  
		$this->query = $str;
        return $this->execQuery($str);
    }
	
	function delete()
	{
        $str = "DELETE FROM AKTIFITAS.MEETING_ROOM_APPROVAL
                WHERE 
                  MEETING_ROOM_APPROVAL_ID = ".$this->getField("MEETING_ROOM_APPROVAL_ID").""; 
				  
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
    function selectByParams($paramsArray=array(),$limit=-1,$from=-1,$statement="", $order=" ORDER BY MEETING_ROOM_APPROVAL_ID ASC")
	{
		$str = "
				SELECT MEETING_ROOM_APPROVAL_ID, MEETING_ROOM_ID, PEGAWAI_ID, PEGAWAI, 
				   LAST_CREATE_USER, LAST_CREATE_DATE
			  FROM AKTIFITAS.MEETING_ROOM_APPROVAL A
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
		$str = "SELECT COUNT(MEETING_ROOM_APPROVAL_ID) AS ROWCOUNT FROM AKTIFITAS.MEETING_ROOM_APPROVAL A WHERE 1 = 1 ".$statement; 
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