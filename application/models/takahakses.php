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

  class TakahAkses extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function TakahAkses()
	{
      $this->Entity(); 
    }
	
	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("TAKAH_AKSES_ID", $this->getNextId("TAKAH_AKSES_ID","TAKAH_AKSES")); 		

		$str = "  
            INSERT INTO TAKAH_AKSES(
                TAKAH_AKSES_ID, TAKAH_ID, SATUAN_KERJA, SATUAN_KERJA_ID_TUJUAN, 
                LAST_CREATE_USER, LAST_CREATE_DATE)
            VALUES ( '".$this->getField("TAKAH_AKSES_ID")."', '".$this->getField("TAKAH_ID")."', '".$this->getField("SATUAN_KERJA")."', '".$this->getField("SATUAN_KERJA_ID_TUJUAN")."', '".$this->getField("LAST_CREATE_USER")."', CURRENT_DATE
            )";

        $this->id = $this->getField("TAKAH_AKSES_ID");
		$this->query = $str;
		//echo $str;
		return $this->execQuery($str);
    }

    function update()
	{
		$str = "
            UPDATE TAKAH_AKSES
                SET 
                SATUAN_KERJA           	= '".$this->getField("SATUAN_KERJA")."', 
                SATUAN_KERJA_ID_TUJUAN  = '".$this->getField("SATUAN_KERJA_ID_TUJUAN")."', 
                TAKAH_ID            	= '".$this->getField("TAKAH_ID")."', 
                LAST_UPDATE_USER		= '".$this->getField("LAST_UPDATE_USER")."',
				LAST_UPDATE_DATE		= CURRENT_DATE
            WHERE TAKAH_AKSES_ID = '".$this->getField("TAKAH_AKSES_ID")."'

		"; 

		$this->query = $str;
		//echo $str;
		return $this->execQuery($str);
    }

	
    function updateByField()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "UPDATE TAKAH_AKSES  SET
				  ".$this->getField("FIELD")." = '".$this->getField("FIELD_VALUE")."'
				WHERE TAKAH_AKSES_ID = ".$this->getField("TAKAH_AKSES_ID")."
				"; 
				$this->query = $str;
	
		return $this->execQuery($str);
    }	

	function delete()
	{
        $str = "DELETE FROM TAKAH_AKSES
                WHERE 
                TAKAH_AKSES_ID = ".$this->getField("TAKAH_AKSES_ID").""; 
				  
		$this->query = $str;
        return $this->execQuery($str);
    }

    function deleteTakah()
	{
        $str = "DELETE FROM TAKAH_AKSES
                WHERE 
                TAKAH_ID = ".$this->getField("TAKAH_ID").""; 
				  
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

    function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order=" ORDER BY TAKAH_ID")
	{
		$str = "    
			SELECT TAKAH_AKSES_ID, SATUAN_KERJA, SATUAN_KERJA_ID_TUJUAN, TAKAH_ID, 
				LAST_CREATE_USER, LAST_CREATE_DATE, LAST_UPDATE_USER, LAST_UPDATE_DATE 
			FROM TAKAH_AKSES A
            WHERE 1 = 1
		"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement.$order;
		
		$this->query = $str;
		//echo $str;exit;
		return $this->selectLimit($str,$limit,$from); 
    }
    
    function selectByParamsMonitoring($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order=" ORDER BY TAKAH_ID")
	{
		$str = "    
			SELECT TAKAH_AKSES_ID, SATUAN_KERJA, SATUAN_KERJA_ID_TUJUAN, TAKAH_ID, 
				LAST_CREATE_USER, LAST_CREATE_DATE, LAST_UPDATE_USER, LAST_UPDATE_DATE 
			FROM TAKAH_AKSES A
            WHERE 1 = 1
		"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement.$order;
		
		$this->query = $str;
		//echo $str;exit;
		return $this->selectLimit($str,$limit,$from); 
    }
	
    function getKode($paramsArray=array(),$statement="")
	{
		$str = "
			SELECT TAKAH_ID, SATUAN_KERJA_ID_TUJUAN SATUAN_KERJA_ID FROM TAKAH_AKSES A
			WHERE 1 = 1
		"; 
		while(list($key,$val)=each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement;
		$this->selectLimit($str,-1,-1); 
		$i = 0;
		while($this->nextRow())
		{
			if($i == 0)
				$hasil .= "'".$this->getField("SATUAN_KERJA_ID")."'";
			else
				$hasil .= ","."'".$this->getField("SATUAN_KERJA_ID")."'";
			$i++;		
		}
		if($i == 0)
			$hasil = "''";
				
		return strtoupper($hasil);
		
    }

    /** 
    * Hitung jumlah record berdasarkan parameter (array). 
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","IJIN_USAHA_ID"=>"yyy") 
    * @return long Jumlah record yang sesuai kriteria 
    **/ 
    function getCountByParams($paramsArray=array(), $statement="")
	{
		$str = "
			SELECT COUNT(TAKAH_ID) AS ROWCOUNT FROM TAKAH_AKSES A
		    WHERE TAKAH_ID IS NOT NULL ".$statement; 
		
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

    function getCountByParamsMonitoring($paramsArray=array(), $statement="")
	{
		$str = "
			SELECT COUNT(TAKAH_ID) AS ROWCOUNT FROM TAKAH_AKSES A
		    WHERE TAKAH_ID IS NOT NULL ".$statement;
		
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