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
  include_once(APPPATH.'/models/Entity.php');

  class TandaTangan extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function TandaTangan()
	{
      $this->Entity(); 
    }
	
	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("TANDA_TANGAN_ID", $this->getNextId("TANDA_TANGAN_ID","TANDA_TANGAN")); 
		$str = "
				INSERT INTO TANDA_TANGAN (TANDA_TANGAN_ID, KODE, NAMA, JABATAN, JABATAN_ENG, NIP, STATUS, USER_TANDA_TANGAN_ID, LAST_CREATE_USER, LAST_CREATE_DATE) 
				VALUES(
				  ".$this->getField("TANDA_TANGAN_ID").",
				  '".$this->getField("KODE")."',
				  '".$this->getField("NAMA")."',
				  '".$this->getField("JABATAN")."',
				  '".$this->getField("JABATAN_ENG")."',
				  '".$this->getField("NIP")."',
				  ".$this->getField("STATUS").",
				  ".$this->getField("USER_TANDA_TANGAN_ID").",
				  '".$this->getField("LAST_CREATE_USER")."',
				  CURRENT_DATE
				)"; 
		$this->id = $this->getField("TANDA_TANGAN_ID");
		$this->query = $str;
		return $this->execQuery($str);
    }

    function update()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "UPDATE TANDA_TANGAN SET
				  KODE 					= '".$this->getField("KODE")."',
				  NAMA 					= '".$this->getField("NAMA")."',
				  JABATAN				= '".$this->getField("JABATAN")."',
				  JABATAN_ENG			= '".$this->getField("JABATAN_ENG")."',
				  NIP					= '".$this->getField("NIP")."',
				  STATUS				= ".$this->getField("STATUS").",
				  USER_TANDA_TANGAN_ID	= ".$this->getField("USER_TANDA_TANGAN_ID").",
				  LAST_UPDATE_USER		= '".$this->getField("LAST_UPDATE_USER")."',
				  LAST_UPDATE_DATE		= CURRENT_DATE
				WHERE TANDA_TANGAN_ID 	= '".$this->getField("TANDA_TANGAN_ID")."'
				"; 
				$this->query = $str;
		return $this->execQuery($str);
    }
	
	function delete()
	{
        $str = "DELETE FROM TANDA_TANGAN
                WHERE 
                  TANDA_TANGAN_ID = '".$this->getField("TANDA_TANGAN_ID")."'"; 
				  
		$this->query = $str;
        return $this->execQuery($str);
    }
	
	function uploadFile()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "UPDATE TANDA_TANGAN SET
				  	LINK_GAMBAR = '".$this->getField("LINK_GAMBAR")."'
				WHERE TANDA_TANGAN_ID = ".$this->getField("TANDA_TANGAN_ID")."
				"; 
				
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
    function selectByParams($paramsArray=array(),$limit=-1,$from=-1,$statement="", $order="")
	{
		$str = "
					SELECT 
					TANDA_TANGAN_ID, KODE, NAMA, JABATAN, JABATAN_ENG, NIP, STATUS
					FROM TANDA_TANGAN
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
    
	function selectByParamsMonitoring($paramsArray=array(),$limit=-1,$from=-1,$statement="", $order="")
	{
		$str = "
					SELECT 
                    		A.TANDA_TANGAN_ID, A.KODE, A.NAMA, A.JABATAN, A.JABATAN_ENG, A.NIP, 
							A.STATUS, A.USER_TANDA_TANGAN_ID, B.NAMA USER_TANDA_TANGAN_NAMA, A.LINK_GAMBAR
                    FROM TANDA_TANGAN A
                    LEFT JOIN USERS B ON B.USER_ID = A.USER_TANDA_TANGAN_ID
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
	
	function selectByParamsLike($paramsArray=array(),$limit=-1,$from=-1, $statement="")
	{
		$str = "
				SELECT 
				TANDA_TANGAN_ID, KODE, NAMA
				FROM TANDA_TANGAN		
				WHERE 1 = 1
				"; 
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key LIKE '%$val%' ";
		}
		
		$str .= $statement." ORDER BY TANDA_TANGAN_ID DESC";
		$this->query = $str;		
		return $this->selectLimit($str,$limit,$from); 
    }	
    /** 
    * Hitung jumlah record berdasarkan parameter (array). 
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","nama"=>"yyy") 
    * @return long Jumlah record yang sesuai kriteria 
    **/ 
    function getCountByParamsMonitoring($paramsArray=array(), $statement="")
	{
		$str = "
		SELECT COUNT(1) AS ROWCOUNT 
		FROM TANDA_TANGAN A
		LEFT JOIN USERS B ON B.USER_ID = A.USER_TANDA_TANGAN_ID
		WHERE 1=1 
		".$statement; 
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
	
	function getCountByParams($paramsArray=array(), $statement="")
	{
		$str = "SELECT COUNT(TANDA_TANGAN_ID) AS ROWCOUNT FROM TANDA_TANGAN WHERE 1=1 ".$statement; 
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
		$str = "SELECT COUNT(TANDA_TANGAN_ID) AS ROWCOUNT FROM TANDA_TANGAN WHERE 1 = 1 "; 
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