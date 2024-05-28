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

  class MeetingRoom extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function MeetingRoom()
	{
      $this->Entity(); 
    }
	
	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("MEETING_ROOM_ID", $this->getNextId("MEETING_ROOM_ID","AKTIFITAS.MEETING_ROOM")); 
		$str = "
				INSERT INTO AKTIFITAS.MEETING_ROOM (
				   MEETING_ROOM_ID, NAMA, TEMPAT, KAPASITAS, KETERANGAN,
				   LINK_FOTO, LOKASI_ID, STATUS, LAST_CREATE_USER, LAST_CREATE_DATE) 
				VALUES ( '".$this->getField("MEETING_ROOM_ID")."', '".$this->getField("NAMA")."', '".$this->getField("TEMPAT")."',
					'".$this->getField("KAPASITAS")."', '".$this->getField("KETERANGAN")."', '".$this->getField("LINK_FOTO")."',
					'".$this->getField("LOKASI_ID")."',
					'".$this->getField("STATUS")."', '".$this->getField("LAST_CREATE_USER")."', 
					CURRENT_DATE)"; 
		$this->id = $this->getField("MEETING_ROOM_ID");
		$this->query = $str;

		return $this->execQuery($str);
    }

    function update()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "
			   UPDATE AKTIFITAS.MEETING_ROOM
				SET    NAMA         	= '".$this->getField("NAMA")."',
					   TEMPAT       	= '".$this->getField("TEMPAT")."',
					   KAPASITAS       	= '".$this->getField("KAPASITAS")."',
					   KETERANGAN      	= '".$this->getField("KETERANGAN")."',
					   LINK_FOTO           	= '".$this->getField("LINK_FOTO")."',
					   STATUS             	= '".$this->getField("STATUS")."',
					   LAST_UPDATE_USER   	= '".$this->getField("LAST_UPDATE_USER")."',
					   LAST_UPDATE_DATE   	= CURRENT_DATE
				WHERE  MEETING_ROOM_ID    	= '".$this->getField("MEETING_ROOM_ID")."'
				"; 
				$this->query = $str;
		return $this->execQuery($str);
    }

    function updateByField()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "UPDATE AKTIFITAS.MEETING_ROOM A SET
				  ".$this->getField("FIELD")." 		= '".$this->getField("FIELD_VALUE")."',
				  ".$this->getField("FIELD_VALIDATOR")." 	= '".$this->getField("FIELD_VALUE_VALIDATOR")."'
				WHERE MEETING_ROOM_ID = ".$this->getField("MEETING_ROOM_ID")."
				"; 
				$this->query = $str;
		return $this->execQuery($str);
    }	
	
	function delete()
	{
        $str = "DELETE FROM AKTIFITAS.MEETING_ROOM
                WHERE 
                  MEETING_ROOM_ID = ".$this->getField("MEETING_ROOM_ID").""; 
				  
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
    function selectByParams($paramsArray=array(),$limit=-1,$from=-1,$statement="", $order=" ORDER BY MEETING_ROOM_ID ASC")
	{
		$str = "
				SELECT 
				A.MEETING_ROOM_ID, A.NAMA, A.TEMPAT, A.KAPASITAS, A.KETERANGAN,
				   LINK_FOTO, A.STATUS, B.NAMA LOKASI
				FROM AKTIFITAS.MEETING_ROOM A
				LEFT JOIN SATUAN_KERJA B ON A.LOKASI_ID = B.SATUAN_KERJA_ID
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
				MEETING_ROOM_ID, NAMA, TEMPAT, KAPASITAS, KETERANGAN,
				   LINK_FOTO, STATUS, LAST_CREATE_USER, LAST_CREATE_DATE,
				   A.LAST_UPDATE_USER, A.LAST_UPDATE_DATE
				FROM AKTIFITAS.MEETING_ROOM A
				WHERE 1 = 1
			"; 
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key LIKE '%$val%' ";
		}
		
		$str .= $statement." ORDER BY MEETING_ROOM_ID DESC";
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
		$str = "SELECT COUNT(MEETING_ROOM_ID) AS ROWCOUNT FROM AKTIFITAS.MEETING_ROOM A WHERE 1 = 1 ".$statement; 
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
		$str = "SELECT COUNT(MEETING_ROOM_ID) AS ROWCOUNT FROM AKTIFITAS.MEETING_ROOM WHERE 1 = 1 "; 
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