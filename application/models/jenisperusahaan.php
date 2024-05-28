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

  class JenisPerusahaan extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function JenisPerusahaan()
	{
      $this->Entity(); 
    }
	
	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		// $this->setField("JENIS_PERUSAHAAN_ID", $this->getNextId("JENIS_PERUSAHAAN_ID","JENIS_PERUSAHAAN")); 
		$str = "
				INSERT INTO JENIS_PERUSAHAAN (
				   KODE, NAMA, DESKRIPSI) 
				VALUES (
					'".$this->getField("KODE")."', 
					'".$this->getField("NAMA")."', 
					'".$this->getField("DESKRIPSI")."'
				)"; 
		$this->id = $this->getField("JENIS_PERUSAHAAN_ID");
		$this->query = $str;

		return $this->execQuery($str);
    }
	
	
	function insertTemplate()
	{
		$str = "
				INSERT INTO SATUAN_KERJA_TEMPLATE (
				   JENIS_PERUSAHAAN_ID, SATUAN_KERJA_ID, ATTACHMENT, LAST_CREATE_USER, LAST_CREATE_DATE) 
				VALUES ( '".$this->getField("JENIS_PERUSAHAAN_ID")."', '".$this->getField("SATUAN_KERJA_ID")."', '".$this->getField("ATTACHMENT")."', 
				'".$this->getField("LAST_CREATE_USER")."', CURRENT_DATE)"; 
		$this->query = $str;
		return $this->execQuery($str);
    }

    function update()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "
			   UPDATE JENIS_PERUSAHAAN
				SET KODE= '".$this->getField("KODE")."',
					  NAMA= '".$this->getField("NAMA")."',
					  DESKRIPSI= '".$this->getField("DESKRIPSI")."'
				WHERE  JENIS_PERUSAHAAN_ID    	= '".$this->getField("JENIS_PERUSAHAAN_ID")."'
				"; 
		// echo $str;exit;
		$this->query = $str;
		return $this->execQuery($str);
    }

    function updateByField()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "UPDATE JENIS_PERUSAHAAN A SET
				  ".$this->getField("FIELD")." 		= '".$this->getField("FIELD_VALUE")."'
				WHERE JENIS_PERUSAHAAN_ID = ".$this->getField("JENIS_PERUSAHAAN_ID")."
				"; 
				$this->query = $str;
		return $this->execQuery($str);
    }	
	
	function delete()
	{
        $str = "DELETE FROM JENIS_PERUSAHAAN
                WHERE 
                  JENIS_PERUSAHAAN_ID = ".$this->getField("JENIS_PERUSAHAAN_ID").""; 
				  
		$this->query = $str;
        return $this->execQuery($str);
    }
	
	function deleteTemplate()
	{
        $str = "DELETE FROM SATUAN_KERJA_TEMPLATE
                WHERE 
                  SATUAN_KERJA_ID = '".$this->getField("SATUAN_KERJA_ID")."'"; 
				  
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
  function selectByParams($paramsArray=array(),$limit=-1,$from=-1,$statement="", $order=" ORDER BY JENIS_PERUSAHAAN_ID ASC")
	{
		$str = "
				SELECT 
					JENIS_PERUSAHAAN_ID, KODE, NAMA, DESKRIPSI
				FROM JENIS_PERUSAHAAN A
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
	
    function selectByParamsEntri($satuanKerjaId, $paramsArray=array(),$limit=-1,$from=-1,$statement="", $order=" ORDER BY A.JENIS_PERUSAHAAN_ID ASC")
	{
		$str = "
				SELECT 
				A.JENIS_PERUSAHAAN_ID, NAMA, B.ATTACHMENT
				FROM JENIS_PERUSAHAAN A
				LEFT JOIN SATUAN_KERJA_TEMPLATE B ON A.JENIS_PERUSAHAAN_ID = B.JENIS_PERUSAHAAN_ID AND B.SATUAN_KERJA_ID = '".$satuanKerjaId."'
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
	
	function selectByParamsLike($paramsArray=array(),$limit=-1,$from=-1, $statement="")
	{
		$str = "    
				SELECT 
				JENIS_PERUSAHAAN_ID, NAMA, KETERANGAN
				FROM JENIS_PERUSAHAAN A
				WHERE 1 = 1
			"; 
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key LIKE '%$val%' ";
		}
		
		$str .= $statement." ORDER BY JENIS_PERUSAHAAN_ID DESC";
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
		$str = "SELECT COUNT(JENIS_PERUSAHAAN_ID) AS ROWCOUNT FROM JENIS_PERUSAHAAN A WHERE 1 = 1 ".$statement; 
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
	
	
    function getJson($paramsArray=array(), $statement="")
	{

		$str = " SELECT ARRAY_TO_JSON(ARRAY_AGG(ROW_TO_JSON(T))) NILAI FROM (SELECT REPLACE(NAMA, ' ', '') \"value\", NAMA \"label\" FROM JENIS_PERUSAHAAN WHERE 1 = 1 ".$statement; 
		while(list($key,$val)=each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= " ) T ";
		
		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("NILAI"); 
		else 
			return "[]"; 
    }
			
    function getCountByParamsLike($paramsArray=array())
	{
		$str = "SELECT COUNT(JENIS_PERUSAHAAN_ID) AS ROWCOUNT FROM JENIS_PERUSAHAAN WHERE 1 = 1 "; 
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