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

  class Customer extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function Customer()
	{
      $this->Entity(); 
    }
	
	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("CUSTOMER_ID", $this->getNextId("CUSTOMER_ID","CUSTOMER")); 
		$str = "
		INSERT INTO CUSTOMER 
		(
			CUSTOMER_ID, PIC, TELP, EMAIL, TEMPAT, NAMA_PEMILIK, NAMA_BRAND, JENIS_PERUSAHAAN_ID
			, NPWP, NPWP_ALAMAT, NOMOR_NIOR, ALAMAT_DOMISILI
		) 
		VALUES
		(
			".$this->getField("CUSTOMER_ID")."
			, '".$this->getField("PIC")."'
			, '".$this->getField("TELP")."'
			, '".$this->getField("EMAIL")."'
			, '".$this->getField("TEMPAT")."'
			, '".$this->getField("NAMA_PEMILIK")."'
			, '".$this->getField("NAMA_BRAND")."'
			, '".$this->getField("JENIS_PERUSAHAAN_ID")."'
			, '".$this->getField("NPWP")."'
			, '".$this->getField("NPWP_ALAMAT")."'
			, '".$this->getField("NOMOR_NIOR")."'
			, '".$this->getField("ALAMAT_DOMISILI")."'
		)";
		$this->id = $this->getField("CUSTOMER_ID");
		$this->query = $str;

		return $this->execQuery($str);
    }
	
	function insertTemplate()
	{
		$str = "
				INSERT INTO SATUAN_KERJA_TEMPLATE (
				   CUSTOMER_ID, SATUAN_KERJA_ID, ATTACHMENT, LAST_CREATE_USER, LAST_CREATE_DATE) 
				VALUES ( '".$this->getField("CUSTOMER_ID")."', '".$this->getField("SATUAN_KERJA_ID")."', '".$this->getField("ATTACHMENT")."', 
				'".$this->getField("LAST_CREATE_USER")."', CURRENT_DATE)"; 
		$this->query = $str;
		return $this->execQuery($str);
    }

  	function update()
	{
		$str = "
		UPDATE CUSTOMER
		SET   
			PIC= '".$this->getField("PIC")."'
			, TELP= '".$this->getField("TELP")."'
			, EMAIL= '".$this->getField("EMAIL")."'
			, TEMPAT= '".$this->getField("TEMPAT")."'
			, NAMA_PEMILIK= '".$this->getField("NAMA_PEMILIK")."'
			, NAMA_BRAND= '".$this->getField("NAMA_BRAND")."'
			, JENIS_PERUSAHAAN_ID	= '".$this->getField("JENIS_PERUSAHAAN_ID")."'
			, NPWP= '".$this->getField("NPWP")."'
			, NPWP_ALAMAT= '".$this->getField("NPWP_ALAMAT")."'
			, NOMOR_NIOR= '".$this->getField("NOMOR_NIOR")."'
			, ALAMAT_DOMISILI= '".$this->getField("ALAMAT_DOMISILI")."'
		WHERE CUSTOMER_ID= '".$this->getField("CUSTOMER_ID")."'
		";
		// echo $str;exit;
		$this->query = $str;
		return $this->execQuery($str);
    }

    function updateByField()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "UPDATE CUSTOMER A SET
				  ".$this->getField("FIELD")." 		= '".$this->getField("FIELD_VALUE")."'
				WHERE CUSTOMER_ID = ".$this->getField("CUSTOMER_ID")."
				"; 
				$this->query = $str;
		return $this->execQuery($str);
    }	
	
	function delete()
	{
        $str = "DELETE FROM CUSTOMER
                WHERE 
                  CUSTOMER_ID = ".$this->getField("CUSTOMER_ID").""; 
				  
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
    function selectByParams($paramsArray=array(),$limit=-1,$from=-1,$statement="", $order=" ORDER BY A.CUSTOMER_ID ASC")
	{
		// A.CUSTOMER_ID, A.PIC, A.TELP, A.EMAIL, A.TEMPAT, A.NAMA_PEMILIK, A.NAMA_BRAND, A.JENIS_PERUSAHAAN_ID
		$str = "
		SELECT 
			B.NAMA NAMA_JENIS_PERUSAHAAN
			, A.*
		FROM CUSTOMER A
		LEFT JOIN JENIS_PERUSAHAAN B ON B.JENIS_PERUSAHAAN_ID = A.JENIS_PERUSAHAAN_ID
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
	
    function selectByParamsEntri($satuanKerjaId, $paramsArray=array(),$limit=-1,$from=-1,$statement="", $order=" ORDER BY A.CUSTOMER_ID ASC")
	{
		$str = "
				SELECT 
				A.CUSTOMER_ID, NAMA, B.ATTACHMENT
				FROM CUSTOMER A
				LEFT JOIN SATUAN_KERJA_TEMPLATE B ON A.CUSTOMER_ID = B.CUSTOMER_ID AND B.SATUAN_KERJA_ID = '".$satuanKerjaId."'
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
				CUSTOMER_ID, NAMA, KETERANGAN
				FROM CUSTOMER A
				WHERE 1 = 1
			"; 
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key LIKE '%$val%' ";
		}
		
		$str .= $statement." ORDER BY CUSTOMER_ID DESC";
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
		$str = "SELECT COUNT(CUSTOMER_ID) AS ROWCOUNT FROM CUSTOMER A WHERE 1 = 1 ".$statement; 
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

		$str = " SELECT ARRAY_TO_JSON(ARRAY_AGG(ROW_TO_JSON(T))) NILAI FROM (SELECT REPLACE(NAMA, ' ', '') \"value\", NAMA \"label\" FROM CUSTOMER WHERE 1 = 1 ".$statement; 
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
		$str = "SELECT COUNT(CUSTOMER_ID) AS ROWCOUNT FROM CUSTOMER WHERE 1 = 1 "; 
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