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

  class TakahDokumenTujuan extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function TakahDokumenTujuan()
	{
      $this->Entity(); 
    }
	
	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("TAKAH_DOKUMEN_TUJUAN_ID", $this->getNextId("TAKAH_DOKUMEN_TUJUAN_ID","TAKAH_DOKUMEN_TUJUAN")); 		

		$str = "  
            INSERT INTO TAKAH_DOKUMEN_TUJUAN(
                TAKAH_DOKUMEN_TUJUAN_ID, TAKAH_DOKUMEN_ID, TANGGAL_KIRIM, TANGGAL_KEMBALI,
                SATUAN_KERJA_ID_TUJUAN, SATUAN_KERJA, LAST_CREATE_USER, LAST_CREATE_DATE)
            VALUES (
            	'".$this->getField("TAKAH_DOKUMEN_TUJUAN_ID")."', 
            	'".$this->getField("TAKAH_DOKUMEN_ID")."', 
            	".$this->getField("TANGGAL_KIRIM").", 
            	".$this->getField("TANGGAL_KEMBALI").", 
            	'".$this->getField("SATUAN_KERJA_ID_TUJUAN")."', 
            	'".$this->getField("SATUAN_KERJA")."',  
            	'".$this->getField("LAST_CREATE_USER")."', 
            	CURRENT_DATE
            )";

        $this->id = $this->getField("TAKAH_DOKUMEN_TUJUAN_ID");
		$this->query = $str;
		//echo $str;
		return $this->execQuery($str);
    }

    function update()
	{
		$str = "
            UPDATE TAKAH_DOKUMEN_TUJUAN
                SET 
                TAKAH_DOKUMEN_ID        = '".$this->getField("TAKAH_DOKUMEN_ID")."', 
                TANGGAL_KIRIM           = ".$this->getField("TANGGAL_KIRIM").", 
                TANGGAL_KEMBALI         = ".$this->getField("TANGGAL_KEMBALI").", 
                SATUAN_KERJA_ID_TUJUAN  = '".$this->getField("SATUAN_KERJA_ID_TUJUAN")."', 
                SATUAN_KERJA  			= '".$this->getField("SATUAN_KERJA")."', 
                LAST_UPDATE_USER		= '".$this->getField("LAST_UPDATE_USER")."',
				LAST_UPDATE_DATE		= CURRENT_DATE
            WHERE TAKAH_DOKUMEN_TUJUAN_ID = '".$this->getField("TAKAH_DOKUMEN_TUJUAN_ID")."'

		"; 

		$this->query = $str;
		//echo $str;
		return $this->execQuery($str);
    }

	
    function updateByField()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "
			UPDATE TAKAH_DOKUMEN_TUJUAN  SET
				".$this->getField("FIELD")." = '".$this->getField("FIELD_VALUE")."'
			WHERE TAKAH_DOKUMEN_TUJUAN_ID = ".$this->getField("TAKAH_DOKUMEN_TUJUAN_ID")."
		"; 
		
		$this->query = $str;
	
		return $this->execQuery($str);
    }	

	function delete()
	{
        $str = "DELETE FROM TAKAH_DOKUMEN_TUJUAN
                WHERE 
                TAKAH_DOKUMEN_TUJUAN_ID = ".$this->getField("TAKAH_DOKUMEN_TUJUAN_ID").""; 
				  
		$this->query = $str;
        return $this->execQuery($str);
    }

    function deleteTakah()
	{
        $str = "DELETE FROM TAKAH_DOKUMEN_TUJUAN
                WHERE 
                TAKAH_DOKUMEN_ID = ".$this->getField("TAKAH_DOKUMEN_ID").""; 
				  
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

    function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order=" ORDER BY TAKAH_DOKUMEN_TUJUAN_ID ASC")
	{
		$str = "    
			SELECT TAKAH_DOKUMEN_TUJUAN_ID, TAKAH_DOKUMEN_ID, TANGGAL_KIRIM, TANGGAL_KEMBALI, 
				SATUAN_KERJA_ID_TUJUAN, SATUAN_KERJA, 
				LAST_CREATE_USER, LAST_CREATE_DATE, LAST_UPDATE_USER, LAST_UPDATE_DATE 
			FROM TAKAH_DOKUMEN_TUJUAN A
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
    
    function selectByParamsMonitoring($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order=" ORDER BY TAKAH_DOKUMEN_TUJUAN_ID ASC")
	{
		$str = "    
			SELECT TAKAH_DOKUMEN_TUJUAN_ID, TAKAH_DOKUMEN_ID, TANGGAL_KIRIM, TANGGAL_KEMBALI, 
				SATUAN_KERJA_ID_TUJUAN, SATUAN_KERJA, 
				A.LAST_CREATE_USER, A.LAST_CREATE_DATE, A.LAST_UPDATE_USER, A.LAST_UPDATE_DATE 
			FROM TAKAH_DOKUMEN_TUJUAN A
			LEFT JOIN SATUAN_KERJA B ON A.SATUAN_KERJA_ID_TUJUAN=B.SATUAN_KERJA_ID
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
			SELECT COUNT(TAKAH_DOKUMEN_TUJUAN_ID) AS ROWCOUNT FROM TAKAH_DOKUMEN_TUJUAN A
		    WHERE TAKAH_DOKUMEN_TUJUAN_ID IS NOT NULL ".$statement; 
		
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
			SELECT COUNT(TAKAH_DOKUMEN_TUJUAN_ID) AS ROWCOUNT FROM TAKAH_DOKUMEN_TUJUAN A
		    WHERE TAKAH_DOKUMEN_TUJUAN_ID IS NOT NULL ".$statement;
		
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