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

  class MeetingRequest extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function MeetingRequest()
	{
      $this->Entity(); 
    }
	
	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("MEETING_REQUEST_ID", $this->getNextId("MEETING_REQUEST_ID","AKTIFITAS.MEETING_REQUEST")); 
		$str = "
				INSERT INTO AKTIFITAS.MEETING_REQUEST (
				   MEETING_REQUEST_ID, MEETING_ROOM_ID, LOKASI_ID, PEGAWAI_ID, NAMA, KETERANGAN,
				   JAM_AWAL, JAM_AKHIR, TANGGAL_AWAL, TANGGAL_AKHIR, JUMLAH_PESERTA, LAST_CREATE_USER, LAST_CREATE_DATE) 
				VALUES ( 
					'".$this->getField("MEETING_REQUEST_ID")."', 
					'".$this->getField("MEETING_ROOM_ID")."', 
					'".$this->getField("LOKASI_ID")."', 
					'".$this->getField("PEGAWAI_ID")."', 
					'".$this->getField("NAMA")."', 
					'".$this->getField("KETERANGAN")."', 
					'".$this->getField("JAM_AWAL")."', 
					'".$this->getField("JAM_AKHIR")."', 
					".$this->getField("TANGGAL_AWAL").",  
					".$this->getField("TANGGAL_AKHIR").", 
					'".$this->getField("JUMLAH_PESERTA")."', 
					'".$this->getField("LAST_CREATE_USER")."', 
					CURRENT_DATE)"; 
		$this->id = $this->getField("MEETING_REQUEST_ID");
		$this->query = $str;

		return $this->execQuery($str);
    }

    function update()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "
			   UPDATE AKTIFITAS.MEETING_REQUEST
				SET    MEETING_ROOM_ID  = '".$this->getField("MEETING_ROOM_ID")."',
					   NAMA       		= '".$this->getField("NAMA")."',
					   PEGAWAI_ID       = '".$this->getField("PEGAWAI_ID")."',
					   KETERANGAN      	= '".$this->getField("KETERANGAN")."',
					   JAM_AWAL         = '".$this->getField("JAM_AWAL")."',
					   JAM_AKHIR        = '".$this->getField("JAM_AKHIR")."',
					   TANGGAL          = ".$this->getField("TANGGAL").",
					   STATUS           = '".$this->getField("STATUS")."',
					   LAST_UPDATE_USER = '".$this->getField("LAST_UPDATE_USER")."',
					   LAST_UPDATE_DATE = CURRENT_DATE
				WHERE  MEETING_REQUEST_ID    	= '".$this->getField("MEETING_REQUEST_ID")."'
				"; 
				$this->query = $str;
		return $this->execQuery($str);
    }
	
	

    function approval()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "
			   UPDATE AKTIFITAS.MEETING_REQUEST
				SET    APPROVAL  = '".$this->getField("APPROVAL")."',
					   APPROVAL_KETERANGAN	= '".$this->getField("APPROVAL_KETERANGAN")."',
					   APPROVAL_BY = '".$this->getField("APPROVAL_BY")."',
					   APPROVAL_DATE = CURRENT_DATE
				WHERE  MEETING_REQUEST_ID    	= '".$this->getField("MEETING_REQUEST_ID")."'
				"; 
				$this->query = $str;
		return $this->execQuery($str);
    }

    function updateByField()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "UPDATE AKTIFITAS.MEETING_REQUEST A SET
				  ".$this->getField("FIELD")." 		= '".$this->getField("FIELD_VALUE")."',
				  ".$this->getField("FIELD_VALIDATOR")." 	= '".$this->getField("FIELD_VALUE_VALIDATOR")."'
				WHERE MEETING_REQUEST_ID = ".$this->getField("MEETING_REQUEST_ID")."
				"; 
				$this->query = $str;
		return $this->execQuery($str);
    }	
	
	function delete()
	{
        $str = "DELETE FROM AKTIFITAS.MEETING_REQUEST
                WHERE 
                  MEETING_REQUEST_ID = ".$this->getField("MEETING_REQUEST_ID").""; 
				  
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
    function selectByParams($paramsArray=array(),$limit=-1,$from=-1,$statement="", $order=" ORDER BY TANGGAL_AWAL DESC, JAM_AWAL DESC")
	{	
		$str = " 
				SELECT 
				MEETING_REQUEST_ID, A.NAMA ACARA, A.KETERANGAN, B.NAMA, B.TEMPAT, B.KAPASITAS, A.PEGAWAI_ID, A.PEGAWAI, JUMLAH_PESERTA,
				   JAM_AWAL, JAM_AKHIR, TO_CHAR(TANGGAL_AWAL, 'DD-MM-YYYY') TANGGAL_AWAL, TO_CHAR(TANGGAL_AKHIR, 'DD-MM-YYYY') TANGGAL_AKHIR,
				   TO_CHAR(TANGGAL_AWAL, 'DD-MM-YYYY') || ' ' || JAM_AWAL TANGGAL_AWAL_JAM, TO_CHAR(TANGGAL_AKHIR, 'DD-MM-YYYY') || ' ' || JAM_AKHIR TANGGAL_AKHIR_JAM,
				   APPROVAL, APPROVAL_DATE, 
				   CASE 
				   	WHEN APPROVAL = 'X' THEN 'Menunggu persetujuan'
				   	WHEN APPROVAL = 'Y' THEN 'Permohonan disetujui'
				   	WHEN APPROVAL = 'T' THEN 'Permohonan ditolak dengan alasan : ' || APPROVAL_KETERANGAN END APPROVAL_KETERANGAN					
				FROM AKTIFITAS.MEETING_REQUEST A
				LEFT JOIN AKTIFITAS.MEETING_ROOM B ON A.MEETING_ROOM_ID = B.MEETING_ROOM_ID
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

    function selectByParamsApproval($paramsArray=array(),$limit=-1,$from=-1,$statement="", $order=" ORDER BY TANGGAL_AWAL DESC, JAM_AWAL DESC")
	{
		$str = "
				SELECT C.MEETING_REQUEST_ID, C.PEGAWAI_ID, B.NAMA, B.TEMPAT, B.LINK_FOTO, 
					C.JAM_AWAL, C.JAM_AKHIR, TO_CHAR(TANGGAL_AWAL, 'DD-MM-YYYY') TANGGAL_AWAL,
					TO_CHAR(TANGGAL_AKHIR, 'DD-MM-YYYY') TANGGAL_AKHIR, C.JUMLAH_PESERTA
				FROM AKTIFITAS.LOKASI_APPROVAL A
				LEFT JOIN AKTIFITAS.MEETING_ROOM B ON A.LOKASI_ID = B.LOKASI_ID
				LEFT JOIN AKTIFITAS.MEETING_REQUEST C ON B.MEETING_ROOM_ID = C.MEETING_ROOM_ID
				WHERE APPROVAL IS NULL
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
				MEETING_REQUEST_ID, B.NAMA, B.TEMPAT, A.PEGAWAI_ID,
				   JAM_AWAL, JAM_AKHIR, TANGGAL, STATUS, LAST_CREATE_USER, LAST_CREATE_DATE,
				   A.LAST_UPDATE_USER, A.LAST_UPDATE_DATE
				FROM AKTIFITAS.MEETING_REQUEST A
				LEFT JOIN AKTIFITAS.MEETING_ROOM B ON A.MEETING_ROOM_ID = B.MEETING_ROOM_ID
				WHERE 1 = 1
			"; 
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key LIKE '%$val%' ";
		}
		
		$str .= $statement." ORDER BY MEETING_REQUEST_ID DESC";
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
		$str = "SELECT COUNT(MEETING_REQUEST_ID) AS ROWCOUNT FROM AKTIFITAS.MEETING_REQUEST A WHERE 1 = 1 ".$statement; 
		while(list($key,$val)=each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		//echo $str;
		$this->query = $str;
		$this->select($str); 
		if($this->firstRow())
			return $this->getField("ROWCOUNT"); 
		else 
			return 0; 
    }
			
    function getCountByParamsLike($paramsArray=array())
	{
		$str = "SELECT COUNT(MEETING_REQUEST_ID) AS ROWCOUNT FROM AKTIFITAS.MEETING_REQUEST WHERE 1 = 1 "; 
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