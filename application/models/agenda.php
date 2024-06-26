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

  class Agenda extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function Agenda()
	{
      $this->Entity(); 
    }
	
	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("AGENDA_ID", $this->getNextId("AGENDA_ID","AGENDA")); 		

		$str = "
				INSERT INTO AGENDA (
				   AGENDA_ID, EVENT_ID, PEGAWAI_ID, NAMA, TANGGAL, KETERANGAN, STATUS, LAST_CREATE_USER, LAST_CREATE_DATE) 
 			  	VALUES (
				  ".$this->getField("AGENDA_ID").",
				  '".$this->getField("EVENT_ID")."',
  				  '".$this->getField("PEGAWAI_ID")."',
				  '".$this->getField("NAMA")."',
   				  ".$this->getField("TANGGAL").",
				  '".$this->getField("KETERANGAN")."',
				  '".$this->getField("STATUS")."',
				  '".$this->getField("LAST_CREATE_USER")."',
				  CURRENT_DATE
				)"; 
		$this->query = $str;
		//echo $str;
		return $this->execQuery($str);
    }

    function update()
	{
		$str = "
				UPDATE AGENDA
				SET    EVENT_ID 		= '".$this->getField("EVENT_ID")."',
					   PEGAWAI_ID 	= '".$this->getField("PEGAWAI_ID")."',
					   NAMA          	= '".$this->getField("NAMA")."',
					   TANGGAL       	= ".$this->getField("TANGGAL").",
					   KETERANGAN    	= '".$this->getField("KETERANGAN")."',
					   KETERANGAN    	= '".$this->getField("KETERANGAN")."',
					   STATUS			= '".$this->getField("STATUS")."',
					   LAST_UPDATE_DATE	= CURRENT_DATE					   
				WHERE  AGENDA_ID     = '".$this->getField("AGENDA_ID")."'

			 "; 
		$this->query = $str;
		//echo $str;
		return $this->execQuery($str);
    }
	
    function updateByField()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "UPDATE AGENDA A SET
				  ".$this->getField("FIELD")." = '".$this->getField("FIELD_VALUE")."'
				WHERE AGENDA_ID = ".$this->getField("AGENDA_ID")."
				"; 
				$this->query = $str;
	
		return $this->execQuery($str);
    }	

	function delete()
	{
        $str = "DELETE FROM AGENDA
                WHERE 
                  AGENDA_ID = ".$this->getField("AGENDA_ID").""; 
				  
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
		$str = "
					SELECT 
						   A.AGENDA_ID, A.NAMA, A.EVENT_ID,
						   A.PEGAWAI_ID, A.NAMA, A.TANGGAL, A.KETERANGAN, A.STATUS
					FROM AGENDA A WHERE AGENDA_ID IS NOT NULL
				"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ORDER BY A.TANGGAL DESC";
		$this->query = $str;
		return $this->selectLimit($str,$limit,$from); 
    }
    
	function selectByParamsLike($paramsArray=array(),$limit=-1,$from=-1, $statement="")
	{
		$str = "	SELECT 
						   AGENDA_ID, EVENT_ID, PEGAWAI_ID, 
						   NAMA, TANGGAL, KETERANGAN, STATUS
					FROM AGENDA A WHERE AGENDA_ID IS NOT NULL
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
		$str = "SELECT COUNT(AGENDA_ID) AS ROWCOUNT FROM AGENDA A
		        WHERE AGENDA_ID IS NOT NULL ".$statement; 
		
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
		$str = "SELECT COUNT(AGENDA_ID) AS ROWCOUNT FROM AGENDA A
		        WHERE AGENDA_ID IS NOT NULL ".$statement; 
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