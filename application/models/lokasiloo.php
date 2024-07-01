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

  class LokasiLoo extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function LokasiLoo()
	{
      $this->Entity(); 
    }
	
	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		// $this->setField("LOKASI_LOO_ID", $this->getNextId("LOKASI_LOO_ID","LOKASI_LOO")); 
		$str = "
		INSERT INTO LOKASI_LOO 
		(
			KODE, NAMA, SERVICE_CHARGE, DESKRIPSI, EMAIL, TELEPON, NAMA_PJ
			, NAMA_BANK, REKENING_BANK, ATAS_NAMA_BANK, NAMA_CABANG, TERLETAK, LOKASI_GEDUNG, DASAR_HUKUM
			, Utility_Charge
		) 
		VALUES 
		(
			'".$this->getField("KODE")."'
			, '".$this->getField("NAMA")."'
			, '".$this->getField("SERVICE_CHARGE")."'
			, '".$this->getField("DESKRIPSI")."'
			, '".$this->getField("EMAIL")."'
			, '".$this->getField("TELEPON")."'
			, '".$this->getField("NAMA_PJ")."'
			, '".$this->getField("NAMA_BANK")."'
			, '".$this->getField("REKENING_BANK")."'
			, '".$this->getField("ATAS_NAMA_BANK")."'
			, '".$this->getField("NAMA_CABANG")."'
			, '".$this->getField("TERLETAK")."'
			, '".$this->getField("LOKASI_GEDUNG")."'
			, '".$this->getField("DASAR_HUKUM")."'
			, '".$this->getField("Utility_Charge")."'
		)"; 
		$this->id = $this->getField("LOKASI_LOO_ID");
		$this->query = $str;

		return $this->execQuery($str);
    }

  function insertUtilityCharge()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("LOO_UTILITY_CHARGE_ID", $this->getNextId("LOO_UTILITY_CHARGE_ID","LOO_UTILITY_CHARGE")); 
		$str = "
				INSERT INTO LOO_UTILITY_CHARGE (
				   LOO_UTILITY_CHARGE_ID, HARGA, Utility_Charge_id, LOKASI_LOO_ID
				   ) 
				VALUES (
					'".$this->getField("LOO_UTILITY_CHARGE_ID")."', 
					'".$this->getField("HARGA")."', 
					'".$this->getField("Utility_Charge_id")."', 
					'".$this->getField("LOKASI_LOO_ID")."'
				)"; 
		$this->id = $this->getField("LOO_UTILITY_CHARGE_ID");
		$this->query = $str;
		// echo $str;exit;

		return $this->execQuery($str);
    }
	
	
	function insertTemplate()
	{
		$str = "
				INSERT INTO SATUAN_KERJA_TEMPLATE (
				   LOKASI_LOO_ID, SATUAN_KERJA_ID, ATTACHMENT, LAST_CREATE_USER, LAST_CREATE_DATE) 
				VALUES ( '".$this->getField("LOKASI_LOO_ID")."', '".$this->getField("SATUAN_KERJA_ID")."', '".$this->getField("ATTACHMENT")."', 
				'".$this->getField("LAST_CREATE_USER")."', CURRENT_DATE)"; 
		$this->query = $str;
		return $this->execQuery($str);
    }

  function update()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "
		UPDATE LOKASI_LOO
		SET   
			KODE= '".$this->getField("KODE")."'
			, NAMA= '".$this->getField("NAMA")."'
			, SERVICE_CHARGE= '".$this->getField("SERVICE_CHARGE")."'
			, Utility_Charge= '".$this->getField("Utility_Charge")."'
			, EMAIL= '".$this->getField("EMAIL")."'
			, TELEPON= '".$this->getField("TELEPON")."'
			, NAMA_PJ= '".$this->getField("NAMA_PJ")."'
			, NAMA_BANK= '".$this->getField("NAMA_BANK")."'
			, REKENING_BANK= '".$this->getField("REKENING_BANK")."'
			, ATAS_NAMA_BANK= '".$this->getField("ATAS_NAMA_BANK")."'
			, NAMA_CABANG= '".$this->getField("NAMA_CABANG")."'
			, TERLETAK= '".$this->getField("TERLETAK")."'
			, LOKASI_GEDUNG= '".$this->getField("LOKASI_GEDUNG")."'
			, DASAR_HUKUM= '".$this->getField("DASAR_HUKUM")."'
			, DESKRIPSI= '".$this->getField("DESKRIPSI")."'
		WHERE  LOKASI_LOO_ID    	= '".$this->getField("LOKASI_LOO_ID")."'
		"; 
		// echo $str;exit;
		$this->query = $str;
		return $this->execQuery($str);
    }

    function updateByField()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "UPDATE LOKASI_LOO A SET
				  ".$this->getField("FIELD")." 		= '".$this->getField("FIELD_VALUE")."'
				WHERE LOKASI_LOO_ID = ".$this->getField("LOKASI_LOO_ID")."
				"; 
				$this->query = $str;
		return $this->execQuery($str);
    }	
	
	function delete()
	{
        $str = "DELETE FROM LOKASI_LOO
                WHERE 
                  LOKASI_LOO_ID = ".$this->getField("LOKASI_LOO_ID").""; 
				  
		$this->query = $str;
        return $this->execQuery($str);
    }

    function deleteUtilityCharge()
	{
        $str = "DELETE FROM LOO_UTILITY_CHARGE
                WHERE 
                  LOKASI_LOO_ID = ".$this->getField("LOKASI_LOO_ID").""; 
				  
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
    function selectByParams($paramsArray=array(),$limit=-1,$from=-1,$statement="", $order=" ORDER BY A.LOKASI_LOO_ID ASC")
	{
		$str = "
				SELECT 
					A.*
				FROM LOKASI_LOO A
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

  function selectByParamsLooUtilityCharge($paramsArray=array(),$limit=-1,$from=-1,$statement="", $order=" ORDER BY A.LOO_UTILITY_CHARGE_ID ASC")
	{
		$str = "
				SELECT 
					A.*, B.NAMA NAMA_UTILITY_CHARGE, B.KETERANGAN
				FROM LOO_UTILITY_CHARGE A
				LEFT JOIN UTILITY_CHARGE B ON B.UTILITY_CHARGE_ID = A.UTILITY_CHARGE_ID
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
	
    function selectByParamsEntri($satuanKerjaId, $paramsArray=array(),$limit=-1,$from=-1,$statement="", $order=" ORDER BY A.LOKASI_LOO_ID ASC")
	{
		$str = "
				SELECT 
				A.LOKASI_LOO_ID, NAMA, B.ATTACHMENT
				FROM LOKASI_LOO A
				LEFT JOIN SATUAN_KERJA_TEMPLATE B ON A.LOKASI_LOO_ID = B.LOKASI_LOO_ID AND B.SATUAN_KERJA_ID = '".$satuanKerjaId."'
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
				LOKASI_LOO_ID, NAMA, KETERANGAN
				FROM LOKASI_LOO A
				WHERE 1 = 1
			"; 
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key LIKE '%$val%' ";
		}
		
		$str .= $statement." ORDER BY LOKASI_LOO_ID DESC";
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
		$str = "SELECT COUNT(LOKASI_LOO_ID) AS ROWCOUNT FROM LOKASI_LOO A WHERE 1 = 1 ".$statement; 
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

		$str = " SELECT ARRAY_TO_JSON(ARRAY_AGG(ROW_TO_JSON(T))) NILAI FROM (SELECT REPLACE(NAMA, ' ', '') \"value\", NAMA \"label\" FROM LOKASI_LOO WHERE 1 = 1 ".$statement; 
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
		$str = "SELECT COUNT(LOKASI_LOO_ID) AS ROWCOUNT FROM LOKASI_LOO WHERE 1 = 1 "; 
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

    function insertAttachment()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("LOKASI_LOO_ATTACHMENT_ID", $this->getNextId("LOKASI_LOO_ATTACHMENT_ID", "lokasi_loo_attachment"));

		$str = "
		INSERT INTO lokasi_loo_attachment
		(
			LOKASI_LOO_ATTACHMENT_ID, LOKASI_LOO_ID, ATTACHMENT, UKURAN, TIPE, NAMA, LAST_CREATE_USER, LAST_CREATE_DATE
		)
		VALUES 
		(
			'".$this->getField("LOKASI_LOO_ATTACHMENT_ID")."'
			, '".$this->getField("LOKASI_LOO_ID")."'
			, '".$this->getField("ATTACHMENT")."'
			, ".(int)$this->getField("UKURAN")."
			, '".$this->getField("TIPE")."'
			, '".$this->getField("NAMA")."'
			, '".$this->getField("LAST_CREATE_USER")."'
			, NOW()
		)";

		$this->query = $str;
		// echo $str;
		// exit;
		$this->id = $this->getField("LOKASI_LOO_ATTACHMENT_ID");
		return $this->execQuery($str);
	}

    function deleteAttachment()
	{
		$str= "
		DELETE FROM lokasi_loo_attachment
		WHERE
		LOKASI_LOO_ID = '".$this->getField("LOKASI_LOO_ID")."'";

		$this->query = $str;
		$this->execQuery($str);
	}

    function selectByParamsAttachment($paramsArray = array(), $limit = -1, $from = -1, $stat = '', $sOrder = " ORDER BY A.LOKASI_LOO_ATTACHMENT_ID ASC ")
	{
		$str = "
		SELECT 
			A.*
		FROM lokasi_loo_attachment A
		WHERE 1 = 1
		";

		while (list($key, $val) = each($paramsArray)) {
			$str .= " AND $key = '$val' ";
		}

		$str .= " ".$stat." ".$sOrder;
		$this->query = $str;
		// echo $str;exit;
		return $this->selectLimit($str, $limit, $from);
	}

  } 
?>