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

  class AbsensiManual extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function AbsensiIjin()
	{
      $this->Entity(); 
    }
	
	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("ABSENSI_MANUAL_ID", $this->getNextId("ABSENSI_MANUAL_ID","ABSENSI_MANUAL")); 
		$str = "
				INSERT INTO ABSENSI_MANUAL (
				   ABSENSI_MANUAL_ID, PEGAWAI_ID, STATUS, BUKTI,
				   JAM, KETERANGAN, LAST_CREATE_USER, LAST_CREATE_DATE) 
					VALUES(
					  ".$this->getField("ABSENSI_MANUAL_ID").",
					  '".$this->getField("PEGAWAI_ID")."',
					  '".$this->getField("STATUS")."',
					  '".$this->getField("BUKTI")."',
					  ".$this->getField("JAM").",
					  '".$this->getField("KETERANGAN")."',
				  	  '".$this->getField("LAST_CREATE_USER")."',
				      ".$this->getField("LAST_CREATE_DATE")."
				)"; 
		$this->id = $this->getField("ABSENSI_MANUAL_ID");
		$this->query = $str;
		return $this->execQuery($str);
    }

    function update()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "
			   UPDATE  ABSENSI_MANUAL
			   SET STATUS         	= '".$this->getField("STATUS")."',
				   PEGAWAI_ID		= '".$this->getField("PEGAWAI_ID")."',
				   BUKTI		= '".$this->getField("BUKTI")."',
				   JAM		= ".$this->getField("JAM").",
				   KETERANGAN= '".$this->getField("KETERANGAN")."',
				   LAST_UPDATE_USER	= '".$this->getField("LAST_UPDATE_USER")."',
				   LAST_UPDATE_DATE	= ".$this->getField("LAST_UPDATE_DATE")."
			 WHERE ABSENSI_MANUAL_ID = ".$this->getField("ABSENSI_MANUAL_ID")."
				"; 
				$this->query = $str;
		return $this->execQuery($str);
    }
	
    function updateByField()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "UPDATE ABSENSI_MANUAL A SET
				  ".$this->getField("FIELD")." 		= '".$this->getField("FIELD_VALUE")."',
				  ".$this->getField("FIELD_VALIDATOR")." 	= '".$this->getField("FIELD_VALUE_VALIDATOR")."'
				WHERE ABSENSI_MANUAL_ID = ".$this->getField("ABSENSI_MANUAL_ID")."
				"; 
				$this->query = $str;
				//echo $str;
		return $this->execQuery($str);
    }		
	
	function delete()
	{
        $str = "DELETE FROM ABSENSI_MANUAL
                WHERE 
                  ABSENSI_MANUAL_ID = ".$this->getField("ABSENSI_MANUAL_ID").""; 
				  
		$this->query = $str;
        return $this->execQuery($str);
    }
	
	function ApproveAbsensiManual($absensi_manual_id)
	{
		$absensi_id = $this->getNextId("ABSENSI_ID","ABSENSI");
		
        $str = "insert into ABSENSI (ABSENSI_ID, PEGAWAI_ID, DEPARTEMEN_ID, JAM, STATUS, VALIDASI, LAST_CREATE_DATE, LAST_UPDATE_USER,  MESIN_ID) (
			select " . $absensi_id . ", a.pegawai_id, B.DEPARTEMEN_ID , jam, status, 1, CURRENT_DATE, '". $this->getField("LAST_CREATE_USER") ."' , 99  
			from ABSENSI_MANUAL A JOIN PEGAWAI B ON A.PEGAWAI_ID = B.PEGAWAI_ID
			where absensi_manual_id = ". $absensi_manual_id . " AND REF_ID IS NULL) "; 
		
		$this->execQuery($str);
		
		$str = "update ABSENSI_MANUAL  set ref_id = " . $absensi_id . " WHERE REF_ID IS NULL AND absensi_manual_id = " . $absensi_manual_id;
		  
		$this->query = $str;
        return $this->execQuery($str);
    }
	
	function AbsensiManualHapus($absensi_id)
	{
        $str = "CALL DELETE_ABSENSI_MANUAL('". $absensi_id ."')"; 
				  
		$this->query = $str;
		//echo $str;
        return $this->execQuery($str);
    }

    /** 
    * Cari record berdasarkan array parameter dan limit tampilan 
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","nama"=>"yyy") 
    * @param int limit Jumlah maksimal record yang akan diambil 
    * @param int from Awal record yang diambil 
    * @return boolean True jika sukses, false jika tidak 
    **/ 
    function selectByParams($paramsArray=array(),$limit=-1,$from=-1,$statement="", $order="")
	{
		$str = "
				SELECT 
                ABSENSI_MANUAL_ID, A.PEGAWAI_ID, STATUS, 
                   TO_CHAR(JAM,'YYYY-MM-DD HH24:MI:SS') JAM, BUKTI, KETERANGAN, B.NAMA
                FROM ABSENSI_MANUAL A
				LEFT JOIN PEGAWAI B ON B.PEGAWAI_ID = A.PEGAWAI_ID
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
	
    function selectByParamsMonitoring($paramsArray=array(),$limit=-1,$from=-1,$statement="", $order=" ORDER BY JAM DESC ")
	{
		$str = "
                SELECT 
						ABSENSI_MANUAL_ID, B.NRP, A.PEGAWAI_ID, B.NAMA, C.NAMA DEPARTEMEN, CASE WHEN STATUS = 'I' THEN 'MASUK' ELSE 'KELUAR' END JENIS, 
						  TO_CHAR(JAM,'YYYY-MM-DD HH24:MI:SS') JAM, A.BUKTI,  A.KETERANGAN, CASE WHEN A.REF_ID IS NULL THEN 'Entri' ELSE 'Approve' END STATUS, A.REF_ID
						FROM ABSENSI_MANUAL A , PEGAWAI B, DEPARTEMEN C
						WHERE 1 = 1
						AND A.PEGAWAI_ID = B.PEGAWAI_ID
						AND B.DEPARTEMEN_ID = C.DEPARTEMEN_ID
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
                ABSENSI_MANUAL_ID, PEGAWAI_ID, STATUS, 
                   JAM, KETERANGAN
                FROM ABSENSI_MANUAL
                WHERE 1 = 1
			"; 
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key LIKE '%$val%' ";
		}
		
		$str .= $statement." ORDER BY ABSENSI_MANUAL_ID DESC";
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
		$str = "SELECT COUNT(ABSENSI_MANUAL_ID) AS ROWCOUNT FROM ABSENSI_MANUAL A , PEGAWAI B, DEPARTEMEN C
						WHERE 1 = 1
						AND A.PEGAWAI_ID = B.PEGAWAI_ID
						AND B.DEPARTEMEN_ID = C.DEPARTEMEN_ID  ".$statement; 
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
		$str = "SELECT COUNT(ABSENSI_MANUAL_ID) AS ROWCOUNT FROM ABSENSI_MANUAL WHERE 1 = 1 "; 
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