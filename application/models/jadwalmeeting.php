<? 
/* *******************************************************************************************************
MODUL NAME 			: IMASYS
FILE NAME 			: 
AUTHOR				: 
VERSION				: 1.0
MODIFICATION DOC	:
DESCRIPTION			: 
***************************************************************************************************** */

  /***
  * Entity-base class untuk mengimplementasikan tabel AGENDA.
  * 
  ***/
  include_once("Entity.php");

  class JadwalMeeting extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function JadwalMeeting()
	{
      $this->Entity(); 
    }
	
	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("MEETING_ID", $this->getNextId("MEETING_ID","MEETING")); 		

		$str = "  
                  INSERT INTO MEETING(
                    MEETING_ID, CABANG_ID, SATUAN_KERJA_ID, PEGAWAI_ID, NAMA, KETERANGAN, 
                    LOKASI, JAM_AWAL, JAM_AKHIR, TANGGAL_AWAL, TANGGAL_AKHIR, JUMLAH_PESERTA, 
                    LAST_CREATE_USER, LAST_CREATE_DATE, 
                    LAST_APPROVAL_USER, LAST_APPROVAL_DATE)
                  VALUES ('".$this->getField("MEETING_ID")."', '".$this->getField("CABANG_ID")."', '".$this->getField("SATUAN_KERJA_ID")."', 
                    '".$this->getField("PEGAWAI_ID")."', '".$this->getField("NAMA")."', '".$this->getField("KETERANGAN")."', 
                    '".$this->getField("LOKASI")."', '".$this->getField("JAM_AWAL")."', '".$this->getField("JAM_AKHIR")."', 
                    ".$this->getField("TANGGAL_AWAL").", ".$this->getField("TANGGAL_AKHIR").", '".$this->getField("JUMLAH_PESERTA")."', 
                    '".$this->getField("LAST_CREATE_USER")."', CURRENT_DATE,  
                   '".$this->getField("LAST_APPROVAL_USER")."', CURRENT_DATE

        )"; 
        
		$this->query = $str;
		//echo $str;
		return $this->execQuery($str);
    }

    function update()
	{
		$str = "

                UPDATE MEETING
                SET 
                    CABANG_ID           ='".$this->getField("CABANG_ID")."', 
                    SATUAN_KERJA_ID     ='".$this->getField("SATUAN_KERJA_ID")."', 
                    PEGAWAI_ID          ='".$this->getField("PEGAWAI_ID")."', 
                    NAMA                ='".$this->getField("NAMA")."', 
                    KETERANGAN          ='".$this->getField("KETERANGAN")."', 
                    LOKASI              ='".$this->getField("LOKASI")."', 
                    JAM_AWAL            ='".$this->getField("JAM_AWAL")."', 
                    JAM_AKHIR           ='".$this->getField("JAM_AKHIR")."', 
                    TANGGAL_AWAL        =".$this->getField("TANGGAL_AWAL").", 
                    TANGGAL_AKHIR       =".$this->getField("TANGGAL_AKHIR").", 
                    JUMLAH_PESERTA      ='".$this->getField("JUMLAH_PESERTA")."',  
                    LAST_UPDATE_USER    ='".$this->getField("LAST_UPDATE_USER")."', 
                    LAST_UPDATE_DATE    = CURRENT_DATE, 
                    LAST_APPROVAL_USER  ='".$this->getField("LAST_APPROVAL_USER")."', 
                    LAST_APPROVAL_DATE  = CURRENT_DATE
                WHERE MEETING_ID        ='".$this->getField("MEETING_ID")."' 

			 "; 
		$this->query = $str;
		//echo $str;
		return $this->execQuery($str);
    }
	
    function updateByField()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "UPDATE MEETING A SET
				  ".$this->getField("FIELD")." = '".$this->getField("FIELD_VALUE")."'
				WHERE MEETING_ID = ".$this->getField("MEETING_ID")."
				"; 
				$this->query = $str;
	
		return $this->execQuery($str);
    }	

	function delete()
	{
        $str = "DELETE FROM MEETING
                WHERE 
                MEETING_ID = ".$this->getField("MEETING_ID").""; 
				  
		$this->query = $str;
        return $this->execQuery($str);
    }

    /** 
    * Cari record berdasarkan array parameter dan limit tampilan 
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","IJIN_USAHA_ID"=>"yyy") 
    * @param int limit Jumlah maksimal record yang akan diambil 
    * @param int from Awal record yang diambil 
    * @return boolean True jika sukses, false jika tidak 
    **/ 


    
    function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement="")
	{
		$str = "   SELECT MEETING_ID, CABANG_ID, SATUAN_KERJA_ID, PEGAWAI_ID, NAMA, KETERANGAN, 
                  LOKASI, JAM_AWAL, JAM_AKHIR, TANGGAL_AWAL, TANGGAL_AKHIR, JUMLAH_PESERTA, 
                  LAST_CREATE_USER, LAST_CREATE_DATE, LAST_UPDATE_USER, LAST_UPDATE_DATE, 
                  LAST_APPROVAL_USER, LAST_APPROVAL_DATE, TO_CHAR(TANGGAL_AWAL, 'DD-MM-YYYY') TANGGAL_AWAL_EDIT, 
                  TO_CHAR(TANGGAL_AKHIR, 'DD-MM-YYYY') TANGGAL_AKHIR_EDIT
              FROM MEETING A
				      WHERE MEETING_ID IS NOT NULL
				"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ORDER BY A.MEETING_ID DESC";
		$this->query = $str;
		return $this->selectLimit($str,$limit,$from); 
    }
    
	function selectByParamsLike($paramsArray=array(),$limit=-1,$from=-1, $statement="")
	{
		$str = "   SELECT MEETING_ID, CABANG_ID, SATUAN_KERJA_ID, PEGAWAI_ID, NAMA, KETERANGAN, 
                  LOKASI, JAM_AWAL, JAM_AKHIR, TANGGAL_AWAL, TANGGAL_AKHIR, JUMLAH_PESERTA, 
                  LAST_CREATE_USER, LAST_CREATE_DATE, LAST_UPDATE_USER, LAST_UPDATE_DATE, 
                  LAST_APPROVAL_USER, LAST_APPROVAL_DATE
               FROM MEETING A
               WHERE MEETING_ID IS NOT NULL
			    "; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key LIKE '%$val%' ";
		}
		
		$this->query = $str;
		$str .= $statement." ORDER BY A.NAMA ASC";
		return $this->selectLimit($str,$limit,$from); 
    }	
    /** 
    * Hitung jumlah record berdasarkan parameter (array). 
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","IJIN_USAHA_ID"=>"yyy") 
    * @return long Jumlah record yang sesuai kriteria 
    **/ 
    function getCountByParams($paramsArray=array(), $statement="")
	{
		$str = "SELECT COUNT(MEETING_ID) AS ROWCOUNT FROM MEETING A
		        WHERE MEETING_ID IS NOT NULL ".$statement; 
		
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

    function getCountByParamsLike($paramsArray=array(), $statement="")
	{
		$str = "SELECT COUNT(MEETING_ID) AS ROWCOUNT FROM MEETING A
		        WHERE MEETING_ID IS NOT NULL ".$statement; 
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