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

  class Produk extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function Produk()
	{
      $this->Entity(); 
    }
	
	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		// $this->setField("PRODUK_ID", $this->getNextId("PRODUK_ID","PRODUK")); 
		$str = "
				INSERT INTO PRODUK (
				   KODE, NAMA, DESKRIPSI, CUSTOMER_ID) 
				VALUES (
					'".$this->getField("KODE")."', 
					'".$this->getField("NAMA")."', 
					'".$this->getField("DESKRIPSI")."',
					'".$this->getField("CUSTOMER_ID")."'
				)"; 
		$this->id = $this->getField("PRODUK_ID");
		$this->query = $str;

		return $this->execQuery($str);
    }
	
	
	function insertTemplate()
	{
		$str = "
				INSERT INTO SATUAN_KERJA_TEMPLATE (
				   PRODUK_ID, SATUAN_KERJA_ID, ATTACHMENT, LAST_CREATE_USER, LAST_CREATE_DATE) 
				VALUES ( '".$this->getField("PRODUK_ID")."', '".$this->getField("SATUAN_KERJA_ID")."', '".$this->getField("ATTACHMENT")."', 
				'".$this->getField("LAST_CREATE_USER")."', CURRENT_DATE)"; 
		$this->query = $str;
		return $this->execQuery($str);
    }

    function update()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "
			   UPDATE PRODUK
				SET KODE= '".$this->getField("KODE")."',
					  NAMA= '".$this->getField("NAMA")."',
					  DESKRIPSI= '".$this->getField("DESKRIPSI")."',
					  CUSTOMER_ID= '".$this->getField("CUSTOMER_ID")."'
				WHERE  PRODUK_ID    	= '".$this->getField("PRODUK_ID")."'
				"; 
		// echo $str;exit;
		$this->query = $str;
		return $this->execQuery($str);
    }

    function updateByField()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "UPDATE PRODUK A SET
				  ".$this->getField("FIELD")." 		= '".$this->getField("FIELD_VALUE")."'
				WHERE PRODUK_ID = ".$this->getField("PRODUK_ID")."
				"; 
				$this->query = $str;
		return $this->execQuery($str);
    }	
	
	function delete()
	{
        $str = "DELETE FROM PRODUK
                WHERE 
                  PRODUK_ID = ".$this->getField("PRODUK_ID").""; 
				  
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
  function selectByParams($paramsArray=array(),$limit=-1,$from=-1,$statement="", $order=" ORDER BY A.PRODUK_ID ASC")
	{
		$str = "
				SELECT 
					A.PRODUK_ID, A.KODE, A.NAMA, A.DESKRIPSI, B.NAMA_BRAND NAMA_BRAND_CUSTOMER, A.CUSTOMER_ID
				FROM PRODUK A
				LEFT JOIN CUSTOMER B ON B.CUSTOMER_ID = A.CUSTOMER_ID
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
	
    function selectByParamsEntri($satuanKerjaId, $paramsArray=array(),$limit=-1,$from=-1,$statement="", $order=" ORDER BY A.PRODUK_ID ASC")
	{
		$str = "
				SELECT 
				A.PRODUK_ID, NAMA, B.ATTACHMENT
				FROM PRODUK A
				LEFT JOIN SATUAN_KERJA_TEMPLATE B ON A.PRODUK_ID = B.PRODUK_ID AND B.SATUAN_KERJA_ID = '".$satuanKerjaId."'
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
				PRODUK_ID, NAMA, KETERANGAN
				FROM PRODUK A
				WHERE 1 = 1
			"; 
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key LIKE '%$val%' ";
		}
		
		$str .= $statement." ORDER BY PRODUK_ID DESC";
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
		$str = "SELECT COUNT(PRODUK_ID) AS ROWCOUNT FROM PRODUK A WHERE 1 = 1 ".$statement; 
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

		$str = " SELECT ARRAY_TO_JSON(ARRAY_AGG(ROW_TO_JSON(T))) NILAI FROM (SELECT REPLACE(NAMA, ' ', '') \"value\", NAMA \"label\" FROM PRODUK WHERE 1 = 1 ".$statement; 
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
		$str = "SELECT COUNT(PRODUK_ID) AS ROWCOUNT FROM PRODUK WHERE 1 = 1 "; 
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