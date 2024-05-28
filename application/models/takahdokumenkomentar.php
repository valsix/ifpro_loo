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

  class TakahDokumenKomentar extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function TakahDokumenKomentar()
	{
      $this->Entity(); 
    }
	
	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("TAKAH_DOKUMEN_KOMENTAR_ID", $this->getNextId("TAKAH_DOKUMEN_KOMENTAR_ID","TAKAH_DOKUMEN_KOMENTAR")); 		

		$str = "  
            INSERT INTO TAKAH_DOKUMEN_KOMENTAR(
                TAKAH_DOKUMEN_KOMENTAR_ID, TAKAH_ID, TAKAH_DOKUMEN_ID, 
                SATUAN_KERJA_ID_ASAL, KOMENTAR, TANGGAL, 
                LAST_CREATE_USER, LAST_CREATE_DATE)
            VALUES ( '".$this->getField("TAKAH_DOKUMEN_KOMENTAR_ID")."', '".$this->getField("TAKAH_ID")."', '".$this->getField("TAKAH_DOKUMEN_ID")."', '".$this->getField("SATUAN_KERJA_ID_ASAL")."', '".$this->getField("KOMENTAR")."', ".$this->getField("TANGGAL").", '".$this->getField("LAST_CREATE_USER")."', CURRENT_DATE
            )";

        $this->id = $this->getField("TAKAH_DOKUMEN_KOMENTAR_ID");
		$this->query = $str;
		//echo $str;
		return $this->execQuery($str);
    }

    function update()
	{
		$str = "
            UPDATE TAKAH_DOKUMEN_KOMENTAR
                SET 
                TAKAH_ID            	= '".$this->getField("TAKAH_ID")."', 
                TAKAH_DOKUMEN_ID        = '".$this->getField("TAKAH_DOKUMEN_ID")."', 
                SATUAN_KERJA_ID_ASAL    = '".$this->getField("SATUAN_KERJA_ID_ASAL")."', 
                KOMENTAR  				= '".$this->getField("KOMENTAR")."', 
                TANGGAL  				= ".$this->getField("TANGGAL").",
                LAST_UPDATE_USER		= '".$this->getField("LAST_UPDATE_USER")."',
				LAST_UPDATE_DATE		= CURRENT_DATE
            WHERE TAKAH_DOKUMEN_KOMENTAR_ID = '".$this->getField("TAKAH_DOKUMEN_KOMENTAR_ID")."'

		"; 

		$this->query = $str;
		//echo $str;
		return $this->execQuery($str);
    }

	
    function updateByField()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "
			UPDATE TAKAH_DOKUMEN_KOMENTAR  SET
				".$this->getField("FIELD")." = '".$this->getField("FIELD_VALUE")."'
			WHERE TAKAH_DOKUMEN_KOMENTAR_ID = ".$this->getField("TAKAH_DOKUMEN_KOMENTAR_ID")."
		"; 
		
		$this->query = $str;
	
		return $this->execQuery($str);
    }	

	function delete()
	{
        $str = "DELETE FROM TAKAH_DOKUMEN_KOMENTAR
                WHERE 
                TAKAH_DOKUMEN_KOMENTAR_ID = ".$this->getField("TAKAH_DOKUMEN_KOMENTAR_ID").""; 
				  
		$this->query = $str;
        return $this->execQuery($str);
    }

    function deleteTakah()
	{
        $str = "DELETE FROM TAKAH_DOKUMEN_KOMENTAR
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

    function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order=" ORDER BY SATUAN_KERJA_ID_ASAL DESC")
	{
		$str = "    
			SELECT TAKAH_DOKUMEN_KOMENTAR_ID, TAKAH_ID, SATUAN_KERJA_ID_ASAL, KOMENTAR, TANGGAL, TAKAH_DOKUMEN_ID,
				LAST_CREATE_USER, LAST_CREATE_DATE, LAST_UPDATE_USER, LAST_UPDATE_DATE 
			FROM TAKAH_DOKUMEN_KOMENTAR A
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
    
    function selectByParamsMonitoring($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order=" ORDER BY SATUAN_KERJA_ID_ASAL DESC")
	{
		$str = "    
			SELECT TAKAH_DOKUMEN_KOMENTAR_ID, TAKAH_ID, SATUAN_KERJA_ID_ASAL, KOMENTAR, TANGGAL, TAKAH_DOKUMEN_ID,
				A.LAST_CREATE_USER, A.LAST_CREATE_DATE, A.LAST_UPDATE_USER, A.LAST_UPDATE_DATE, B.NAMA SATUAN_KERJA 
			FROM TAKAH_DOKUMEN_KOMENTAR A
			LEFT JOIN SATUAN_KERJA B ON A.SATUAN_KERJA_ID_ASAL=B.SATUAN_KERJA_ID
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
	
    /** 
    * Hitung jumlah record berdasarkan parameter (array). 
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","IJIN_USAHA_ID"=>"yyy") 
    * @return long Jumlah record yang sesuai kriteria 
    **/ 
    function getCountByParams($paramsArray=array(), $statement="")
	{
		$str = "
			SELECT COUNT(TAKAH_DOKUMEN_KOMENTAR_ID) AS ROWCOUNT FROM TAKAH_DOKUMEN_KOMENTAR A
		    WHERE TAKAH_DOKUMEN_KOMENTAR_ID IS NOT NULL ".$statement; 
		
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
			SELECT COUNT(TAKAH_DOKUMEN_KOMENTAR_ID) AS ROWCOUNT FROM TAKAH_DOKUMEN_KOMENTAR A
		    WHERE TAKAH_DOKUMEN_KOMENTAR_ID IS NOT NULL ".$statement;
		
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