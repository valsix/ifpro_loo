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
  * Entity-base class untuk mengimplementasikan tabel CHAT.
  * 
  ***/
  include_once("Entity.php");

  class Chat extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function Chat()
	{
      $this->Entity(); 
    }
	
	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("CHAT_ID", $this->getNextId("CHAT_ID","CHAT")); 		
		//'".$this->getField("FOTO")."',  FOTO,
		$str = "
				INSERT INTO CHAT (
				   CHAT_ID, PEGAWAI_ID_BY, PESAN, READ, PEGAWAI_ID_TO, TANGGAL
				   ) 
 			  	VALUES (
				  '".$this->getField("CHAT_ID")."',
				  '".$this->getField("PEGAWAI_ID_BY")."',
				  '".$this->getField("PESAN")."',
				  '".$this->getField("READ")."',
				  '".$this->getField("PEGAWAI_ID_TO")."',
				  ".$this->getField("TANGGAL")."
				)"; 
		$this->id = $this->getField("CHAT_ID");
		$this->query = $str;
		return $this->execQuery($str);
    }

    function update()
	{
		$str = "
				UPDATE CHAT
				SET    
    				  PEGAWAI_ID_BY = '".$this->getField("PEGAWAI_ID_BY")."',
    				  PESAN = '".$this->getField("PESAN")."',
    				  READ = '".$this->getField("READ")."',
    				  PEGAWAI_ID_TO = '".$this->getField("PEGAWAI_ID_TO")."',
    				  TANGGAL = ".$this->getField("TANGGAL")."
				WHERE  CHAT_ID     = '".$this->getField("CHAT_ID")."'
			 "; 
		$this->query = $str;
		return $this->execQuery($str);
    }

    function updateRead()
	{
		$str = "
				UPDATE CHAT
				SET    
    				  READ = '0'
				WHERE  PEGAWAI_ID_TO     = '".$this->getField("PEGAWAI_ID_TO")."'
			 "; 
		$this->query = $str;
		return $this->execQuery($str);
    }
	
	function delete()
	{
        $str = "DELETE FROM CHAT
                WHERE 
                  CHAT_ID = ".$this->getField("CHAT_ID").""; 
				  
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
    function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order="")
	{
		$str = "
				SELECT 
				CHAT_ID, PEGAWAI_ID_BY, PESAN, READ, PEGAWAI_ID_TO, TO_CHAR(TANGGAL, 'DD-MM-YYYY') TANGGAL, TO_CHAR(TANGGAL, 'HH24:MI') JAM
				FROM CHAT
				WHERE 1 = 1
				"; 
		//, FOTO
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$order;
		$this->query = $str;
		return $this->selectLimit($str,$limit,$from); 
    }
    
    function selectByParamsMonitoring($pegawaiId="", $paramsArray=array(),$limit=-1,$from=-1, $statement="", $order="")
	{
		$str = "
				SELECT DISTINCT A.CHAT_ID, B.PEGAWAI_ID, C.NAMA, PESAN, 
				CASE WHEN TO_DATE(A.TANGGAL, 'DD-MM-YYYY') = TO_DATE(CURRENT_DATE, 'DD-MM-YYYY') THEN TO_CHAR(A.TANGGAL, 'HH24:MI:SS') ELSE TO_CHAR(A.TANGGAL, 'DD-MM-YYYY') END TANGGAL_DISPLAY, A.TANGGAL, 
				CASE WHEN PEGAWAI_ID_TO = '".$pegawaiId."' THEN READ ELSE '0' END READ
				FROM CHAT A
				INNER JOIN 
				(
				SELECT CASE WHEN PEGAWAI_ID_BY = '".$pegawaiId."' THEN PEGAWAI_ID_TO ELSE PEGAWAI_ID_BY END PEGAWAI_ID, MAX(TANGGAL) TANGGAL 
				FROM CHAT A 
				GROUP BY CASE WHEN PEGAWAI_ID_BY = '".$pegawaiId."' THEN PEGAWAI_ID_TO ELSE PEGAWAI_ID_BY END 
				) B ON (A.PEGAWAI_ID_TO = B.PEGAWAI_ID OR A.PEGAWAI_ID_BY = B.PEGAWAI_ID) AND A.TANGGAL = B.TANGGAL
				LEFT JOIN PEGAWAI C ON B.PEGAWAI_ID = C.PEGAWAI_ID
				WHERE 1=1
				"; 
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$order;
		$this->query = $str;
		// echo $str;exit;
		return $this->selectLimit($str,$limit,$from); 
    }
    
	function selectByParamsLike($paramsArray=array(),$limit=-1,$from=-1, $statement="")
	{
		$str = "
				CHAT_ID, PEGAWAI_ID_BY, PESAN, READ, PEGAWAI_ID_TO, TANGGAL
				FROM CHAT
				WHERE 1 = 1
			    "; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key LIKE '%$val%' ";
		}
		
		$this->query = $str;
		$str .= $statement." ORDER BY TANGGAL ASC";
		return $this->selectLimit($str,$limit,$from); 
    }	


    function getCountByParamsMessage($paramsArray=array(), $statement="")
	{
		$str = "SELECT COUNT(CHAT_ID) AS ROWCOUNT FROM CHAT
		        WHERE CHAT_ID IS NOT NULL ".$statement; 
		
		while(list($key,$val)=each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$this->select($str);
		$this->query = $str; 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0; 
    }

    /** 
    * Hitung jumlah record berdasarkan parameter (array). 
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","IJIN_USAHA_ID"=>"yyy") 
    * @return long Jumlah record yang sesuai kriteria 
    **/ 
    function getCountByParams($paramsArray=array(), $statement="")
	{
		$str = "SELECT COUNT(CHAT_ID) AS ROWCOUNT FROM CHAT
		        WHERE CHAT_ID IS NOT NULL ".$statement; 
		
		while(list($key,$val)=each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$this->select($str);
		$this->query = $str; 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0; 
    }

    function getCountByParamsLike($paramsArray=array(), $statement="")
	{
		$str = "SELECT COUNT(CHAT_ID) AS ROWCOUNT FROM CHAT
		        WHERE CHAT_ID IS NOT NULL ".$statement; 
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