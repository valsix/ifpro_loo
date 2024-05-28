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

  class Takah extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function Takah()
	{
      $this->Entity(); 
    }
	
	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("TAKAH_ID", $this->getNextId("TAKAH_ID","TAKAH")); 		

		$str = "  
            INSERT INTO TAKAH(
                TAKAH_ID, CABANG_ID, SATUAN_KERJA_ID, KODE, NAMA,
                KETERANGAN, TANGGAL, LAST_CREATE_USER, LAST_CREATE_DATE)
            VALUES ( '".$this->getField("TAKAH_ID")."', '".$this->getField("CABANG_ID")."', '".$this->getField("SATUAN_KERJA_ID")."', '".$this->getField("KODE")."', '".$this->getField("NAMA")."', '".$this->getField("KETERANGAN")."', ".$this->getField("TANGGAL").", '".$this->getField("LAST_CREATE_USER")."', CURRENT_DATE
            )";

        $this->id = $this->getField("TAKAH_ID");
		$this->query = $str;
		//echo $str;
		return $this->execQuery($str);
    }

    function update()
	{
		$str = "
            UPDATE TAKAH
                SET 
                CABANG_ID             	= '".$this->getField("CABANG_ID")."', 
                SATUAN_KERJA_ID       	= '".$this->getField("SATUAN_KERJA_ID")."', 
                KODE            		= '".$this->getField("KODE")."', 
                NAMA         			= '".$this->getField("NAMA")."', 
                KETERANGAN         		= '".$this->getField("KETERANGAN")."', 
                TANGGAL       			= ".$this->getField("TANGGAL").", 
                LAST_UPDATE_USER		= '".$this->getField("LAST_UPDATE_USER")."',
				LAST_UPDATE_DATE		= CURRENT_DATE
            WHERE TAKAH_ID = '".$this->getField("TAKAH_ID")."'

		"; 

		$this->query = $str;
		//echo $str;
		return $this->execQuery($str);
    }
	
	
    function updateByField()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "UPDATE TAKAH  SET
				  ".$this->getField("FIELD")." = '".$this->getField("FIELD_VALUE")."'
				WHERE TAKAH_ID = ".$this->getField("TAKAH_ID")."
				"; 
				$this->query = $str;
	
		return $this->execQuery($str);
    }	

	function delete()
	{
        $str = "DELETE FROM TAKAH
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
			SELECT TAKAH_ID, CABANG_ID, SATUAN_KERJA_ID, KODE, NAMA,
                KETERANGAN, TANGGAL, LAST_CREATE_USER, LAST_CREATE_DATE,
                LAST_UPDATE_USER, LAST_UPDATE_DATE 
			FROM TAKAH A
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
			SELECT TAKAH_ID, CABANG_ID, A.SATUAN_KERJA_ID, D.KODE || ' - ' || D.NAMA AS KODE, A.NAMA,
                A.KETERANGAN, TANGGAL, A.LAST_CREATE_USER, A.LAST_CREATE_DATE,
                A.LAST_UPDATE_USER, A.LAST_UPDATE_DATE 
			FROM TAKAH A
			LEFT JOIN SATUAN_KERJA B ON A.SATUAN_KERJA_ID=B.SATUAN_KERJA_ID
			LEFT JOIN KODE_UNIT_KERJA C ON A.CABANG_ID=C.KODE_UNIT_KERJA_ID
			LEFT JOIN KLASIFIKASI D ON A.KODE = D.KLASIFIKASI_ID
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
			SELECT COUNT(TAKAH_ID) AS ROWCOUNT FROM TAKAH A
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
			SELECT COUNT(TAKAH_ID) AS ROWCOUNT FROM TAKAH A
			LEFT JOIN SATUAN_KERJA B ON A.SATUAN_KERJA_ID=B.SATUAN_KERJA_ID
			LEFT JOIN KODE_UNIT_KERJA C ON A.CABANG_ID=C.KODE_UNIT_KERJA_ID
			LEFT JOIN KLASIFIKASI D ON A.KODE = D.KLASIFIKASI_ID
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