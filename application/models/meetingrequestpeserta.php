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

  class MeetingRequestPeserta extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function MeetingRequestPeserta()
	{
      $this->Entity(); 
    }
	
	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("MEETING_REQUEST_PESERTA_ID", $this->getNextId("MEETING_REQUEST_PESERTA_ID","AKTIFITAS.MEETING_REQUEST_PESERTA")); 
		$str = "
				INSERT INTO AKTIFITAS.MEETING_REQUEST_PESERTA (
				   MEETING_REQUEST_PESERTA_ID, MEETING_REQUEST_ID, PEGAWAI_ID, LAST_CREATE_USER, LAST_CREATE_DATE) 
				VALUES ( 
					'".$this->getField("MEETING_REQUEST_PESERTA_ID")."', 
					'".$this->getField("MEETING_REQUEST_ID")."', 
					'".$this->getField("PEGAWAI_ID")."', 
					'".$this->getField("LAST_CREATE_USER")."', 
					CURRENT_DATE)"; 
		$this->id = $this->getField("MEETING_REQUEST_PESERTA_ID");
		$this->query = $str;

		return $this->execQuery($str);
    }

    function update()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "
			   UPDATE AKTIFITAS.MEETING_REQUEST_PESERTA
				SET    MEETING_REQUEST_PESERTA_ID = '".$this->getField("MEETING_REQUEST_PESERTA_ID")."',
					   PEGAWAI_ID      	= '".$this->getField("PEGAWAI_ID")."',
					   PEGAWAI      		= '".$this->getField("PEGAWAI")."',
					   LAST_UPDATE_USER = '".$this->getField("LAST_UPDATE_USER")."',
					   LAST_UPDATE_DATE = CURRENT_DATE
				WHERE  MEETING_REQUEST_PESERTA_ID    	= '".$this->getField("MEETING_REQUEST_PESERTA_ID")."'
				"; 
				$this->query = $str;
		return $this->execQuery($str);
    }

    function updateByField()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "UPDATE AKTIFITAS.MEETING_REQUEST_PESERTA A SET
				  ".$this->getField("FIELD")." 		= '".$this->getField("FIELD_VALUE")."',
				  ".$this->getField("FIELD_VALIDATOR")." 	= '".$this->getField("FIELD_VALUE_VALIDATOR")."'
				WHERE MEETING_REQUEST_PESERTA_ID = ".$this->getField("MEETING_REQUEST_PESERTA_ID")."
				"; 
				$this->query = $str;
		return $this->execQuery($str);
    }	
	
	function delete()
	{
        $str = "DELETE FROM AKTIFITAS.MEETING_REQUEST_PESERTA
                WHERE 
                  MEETING_REQUEST_PESERTA_ID = ".$this->getField("MEETING_REQUEST_PESERTA_ID").""; 
				  
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
    function selectByParams($paramsArray=array(),$limit=-1,$from=-1,$statement="", $order=" ORDER BY A.PEGAWAI_ID ASC")
	{
		$str = "
				SELECT 
				MEETING_REQUEST_PESERTA_ID, MEETING_REQUEST_ID, A.PEGAWAI_ID, PEGAWAI, JABATAN, HADIR,
				CASE WHEN HADIR = 'Y' THEN 'Hadir' WHEN HADIR = 'T' THEN 'Tidak Hadir' ELSE 'Belum Konfirmasi' END HADIR_KETERANGAN
				FROM AKTIFITAS.MEETING_REQUEST_PESERTA A 
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
		$str = "SELECT COUNT(MEETING_REQUEST_PESERTA_ID) AS ROWCOUNT FROM AKTIFITAS.MEETING_REQUEST_PESERTA WHERE 1 = 1 ".$statement; 
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
		$str = "SELECT COUNT(MEETING_REQUEST_PESERTA_ID) AS ROWCOUNT FROM AKTIFITAS.MEETING_REQUEST_PESERTA WHERE 1 = 1 "; 
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