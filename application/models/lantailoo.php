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

  class LantaiLoo extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function LantaiLoo()
	{
      $this->Entity(); 
    }
	
	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		// $this->setField("LANTAI_LOO_ID", $this->getNextId("LANTAI_LOO_ID","LANTAI_LOO")); 
		$str = "
				INSERT INTO LANTAI_LOO (
				   KODE, NAMA, DESKRIPSI) 
				VALUES (
					'".$this->getField("KODE")."', 
					'".$this->getField("NAMA")."', 
					'".$this->getField("DESKRIPSI")."'
				)"; 
		$this->id = $this->getField("LANTAI_LOO_ID");
		$this->query = $str;

		return $this->execQuery($str);
    }
	

  function update()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "
			   UPDATE LANTAI_LOO
				SET KODE= '".$this->getField("KODE")."',
					  NAMA= '".$this->getField("NAMA")."',
					  DESKRIPSI= '".$this->getField("DESKRIPSI")."'
				WHERE  LANTAI_LOO_ID    	= '".$this->getField("LANTAI_LOO_ID")."'
				"; 
		// echo $str;exit;
		$this->query = $str;
		return $this->execQuery($str);
    }

    function updateByField()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "UPDATE LANTAI_LOO A SET
				  ".$this->getField("FIELD")." 		= '".$this->getField("FIELD_VALUE")."'
				WHERE LANTAI_LOO_ID = ".$this->getField("LANTAI_LOO_ID")."
				"; 
				$this->query = $str;
		return $this->execQuery($str);
    }	
	
	function delete()
	{
        $str = "DELETE FROM LANTAI_LOO
                WHERE 
                  LANTAI_LOO_ID = ".$this->getField("LANTAI_LOO_ID").""; 
				  
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
  function selectByParams($paramsArray=array(),$limit=-1,$from=-1,$statement="", $order=" ORDER BY LANTAI_LOO_ID ASC")
	{
		$str = "
				SELECT 
					LANTAI_LOO_ID, KODE, NAMA, DESKRIPSI
				FROM LANTAI_LOO A
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
	
    function selectByParamsEntri($satuanKerjaId, $paramsArray=array(),$limit=-1,$from=-1,$statement="", $order=" ORDER BY A.LANTAI_LOO_ID ASC")
	{
		$str = "
				SELECT 
				A.LANTAI_LOO_ID, NAMA, B.ATTACHMENT
				FROM LANTAI_LOO A
				LEFT JOIN SATUAN_KERJA_TEMPLATE B ON A.LANTAI_LOO_ID = B.LANTAI_LOO_ID AND B.SATUAN_KERJA_ID = '".$satuanKerjaId."'
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
				LANTAI_LOO_ID, NAMA, KETERANGAN
				FROM LANTAI_LOO A
				WHERE 1 = 1
			"; 
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key LIKE '%$val%' ";
		}
		
		$str .= $statement." ORDER BY LANTAI_LOO_ID DESC";
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
		$str = "SELECT COUNT(LANTAI_LOO_ID) AS ROWCOUNT FROM LANTAI_LOO A WHERE 1 = 1 ".$statement; 
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

		$str = " SELECT ARRAY_TO_JSON(ARRAY_AGG(ROW_TO_JSON(T))) NILAI FROM (SELECT REPLACE(NAMA, ' ', '') \"value\", NAMA \"label\" FROM LANTAI_LOO WHERE 1 = 1 ".$statement; 
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
		$str = "SELECT COUNT(LANTAI_LOO_ID) AS ROWCOUNT FROM LANTAI_LOO WHERE 1 = 1 "; 
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