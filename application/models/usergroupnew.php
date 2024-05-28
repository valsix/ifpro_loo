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
  * Entity-base class untuk mengimplementasikan tabel KAPAL_JENIS.
  * 
  ***/
  include_once("Entity.php");

  class UserGroupNew extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function UserGroupNew()
	{
      $this->Entity(); 
    }
	
	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("USER_GROUP_ID", $this->getNextId("USER_GROUP_ID","USER_GROUP"));

		$str = "
				INSERT INTO USER_GROUP (
				   USER_GROUP_ID, NAMA, KETERANGAN, 
				   AKSES_MASTER, AKSES_LAPORAN, AKSES_UNIT, 
				   AKSES_PROSES_REKAP, LAST_CREATE_USER, LAST_CREATE_DATE) 
				VALUES ( '".$this->getField("USER_GROUP_ID")."', '".$this->getField("NAMA")."', '".$this->getField("KETERANGAN")."',
					'".$this->getField("AKSES_MASTER")."', '".$this->getField("AKSES_LAPORAN")."', '".$this->getField("AKSES_UNIT")."',
					'".$this->getField("AKSES_PROSES_REKAP")."', '".$this->getField("LAST_CREATE_USER")."', CURRENT_DATE)
				"; 
		$this->id = $this->getField("USER_GROUP_ID");
		$this->query = $str;
		return $this->execQuery($str);
    }

    function update()
	{
		$str = "
				UPDATE USER_GROUP
				SET    NAMA               = '".$this->getField("NAMA")."',
					   KETERANGAN         = '".$this->getField("KETERANGAN")."',
					   AKSES_MASTER       = '".$this->getField("AKSES_MASTER")."',
					   AKSES_LAPORAN      = '".$this->getField("AKSES_LAPORAN")."',
					   AKSES_UNIT   = '".$this->getField("AKSES_UNIT")."',
					   AKSES_PROSES_REKAP = '".$this->getField("AKSES_PROSES_REKAP")."',
					   LAST_UPDATE_USER   = '".$this->getField("LAST_UPDATE_USER")."',
					   LAST_UPDATE_DATE   = CURRENT_DATE
				WHERE  USER_GROUP_ID      = '".$this->getField("USER_GROUP_ID")."'
			 "; 
		$this->query = $str;
		//echo $str;
		return $this->execQuery($str);
    }

	function delete()
	{
        $str = "DELETE FROM USER_GROUP
                WHERE 
                  USER_GROUP_ID = ".$this->getField("USER_GROUP_ID").""; 
				  
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
    function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order="ORDER BY USER_GROUP_ID ASC")
	{
		$str = "
				SELECT  
				*
				FROM USER_GROUP A
				  WHERE 1=1
				"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$order;
		$this->query = $str;
		
		return $this->selectLimit($str,$limit,$from); 
    }
    
	
    /** 
    * Hitung jumlah record berdasarkan parameter (array). 
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","IJIN_USAHA_ID"=>"yyy") 
    * @return long Jumlah record yang sesuai kriteria 
    **/ 
    function getCountByParams($paramsArray=array(), $statement="")
	{
		$str = "SELECT COUNT(USER_GROUP_ID) AS ROWCOUNT FROM USER_GROUP A
		        WHERE 1=1 ".$statement; 
		
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