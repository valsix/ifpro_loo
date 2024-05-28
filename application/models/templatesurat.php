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

class TemplateSurat extends Entity{ 

	var $query;
	/**
	* Class constructor.
	**/
	function TemplateSurat()
	{
		$this->Entity(); 
	}

	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("TEMPLATE_SURAT_ID", $this->getNextId("TEMPLATE_SURAT_ID","TEMPLATE_SURAT")); 
		$str = "
			INSERT INTO TEMPLATE_SURAT (
				TEMPLATE_SURAT_ID, JENIS_NASKAH_ID, NAMA, 
				KETERANGAN, LAST_CREATE_USER, LAST_CREATE_DATE) 
			VALUES (
				'".$this->getField("TEMPLATE_SURAT_ID")."', 
				'".$this->getField("JENIS_NASKAH_ID")."', 
				'".$this->getField("NAMA")."', 
				'".$this->getField("KETERANGAN")."', 
				'".$this->getField("LAST_CREATE_USER")."', 
				CURRENT_DATE
			)
		"; 
		$this->id = $this->getField("TEMPLATE_SURAT_ID");
		$this->query = $str;
		// echo $str;exit();
		return $this->execQuery($str);
	}

	function insertAttachment()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("TEMPLATE_SURAT_ATTACHMENT_ID", $this->getNextId("TEMPLATE_SURAT_ATTACHMENT_ID","TEMPLATE_SURAT_ATTACHMENT")); 
		$str = "
			INSERT INTO TEMPLATE_SURAT_ATTACHMENT(
						TEMPLATE_SURAT_ATTACHMENT_ID, TEMPLATE_SURAT_ID, ATTACHMENT, 
						UKURAN, TIPE, NAMA, LAST_CREATE_USER, LAST_CREATE_DATE
            )
            VALUES ('".$this->getField("TEMPLATE_SURAT_ATTACHMENT_ID")."',
					'".$this->getField("TEMPLATE_SURAT_ID")."',
					'".$this->getField("ATTACHMENT")."',
                    ".(int)$this->getField("UKURAN").",
                    '".$this->getField("TIPE")."',
                    '".$this->getField("NAMA")."',
                    '".$this->getField("LAST_CREATE_USER")."',
				  	CURRENT_DATE
				)";
				
		$this->query = $str;
		$this->id = $this->getField("TEMPLATE_SURAT_ID");
		return $this->execQuery($str);			
	}

	function update()
	{
		$str = "
			UPDATE TEMPLATE_SURAT
			SET JENIS_NASKAH_ID         = '".$this->getField("JENIS_NASKAH_ID")."',
			NAMA      					= '".$this->getField("NAMA")."',
			KETERANGAN    				= '".$this->getField("KETERANGAN")."',
			LAST_UPDATE_USER   			= '".$this->getField("LAST_UPDATE_USER")."',
			LAST_UPDATE_DATE   			= CURRENT_DATE
			WHERE TEMPLATE_SURAT_ID    	= '".$this->getField("TEMPLATE_SURAT_ID")."'
		";

		$this->query = $str;
		// echo $str;exit();
		return $this->execQuery($str);
	}

	function updateByField()
	{
		$str = "UPDATE TEMPLATE_SURAT A SET
			".$this->getField("FIELD")."= '".$this->getField("FIELD_VALUE")."'
			WHERE TEMPLATE_SURAT_ID = ".$this->getField("TEMPLATE_SURAT_ID");

		$this->query = $str;
		// echo $str;exit();
		return $this->execQuery($str);
	}	

	function delete()
	{
		$str = "
			DELETE FROM TEMPLATE_SURAT
			WHERE TEMPLATE_SURAT_ID = ".$this->getField("TEMPLATE_SURAT_ID"); 

		$this->query = $str;
		// echo $str;exit();
		return $this->execQuery($str);
	}

	/** 
	* Cari record berdasarkan array parameter dan limit tampilan 
	* @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","JENIS_NASKAH_ID"=>"yyy") 
	* @param int limit Jumlah maksimal record yang akan diambil 
	* @param int from Awal record yang diambil 
	* @return boolean True jika sukses, false jika tidak 
	**/ 
	function selectByParams($paramsArray=array(), $limit=-1, $from=-1, $statement="", $order=" ORDER BY TEMPLATE_SURAT_ID ASC")
	{
		$str = "
			SELECT 
				TEMPLATE_SURAT_ID, A.JENIS_NASKAH_ID, A.NAMA, B.NAMA JENIS_NASKAH, 
				A.KETERANGAN, A.LAST_CREATE_USER, A.LAST_CREATE_DATE, 
				A.LAST_UPDATE_USER, A.LAST_UPDATE_DATE
			FROM TEMPLATE_SURAT A
			LEFT JOIN JENIS_NASKAH B ON A.JENIS_NASKAH_ID=B.JENIS_NASKAH_ID
			WHERE 1 = 1
		"; 

		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}

		$str .= $statement." ".$order;
		$this->query = $str;
		// echo $str;exit();
		return $this->selectLimit($str,$limit,$from); 
	}

	function selectByParamsLike($paramsArray=array(), $limit=-1, $from=-1, $statement="")
	{
		$str = "    
			SELECT 
				TEMPLATE_SURAT_ID, JENIS_NASKAH_ID, NAMA, KETERANGAN
			FROM TEMPLATE_SURAT A
			WHERE 1 = 1
		";

		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key LIKE '%$val%' ";
		}

		$str .= $statement." ORDER BY TEMPLATE_SURAT_ID DESC";
		$this->query = $str;
		// echo $str;exit();		
		return $this->selectLimit($str,$limit,$from); 
	}

	function selectByParamsAttachment($paramsArray=array(),$limit=-1,$from=-1,$stat='', $sOrder=" ORDER BY TEMPLATE_SURAT_ATTACHMENT_ID ASC ")
	{
		$str = "SELECT TEMPLATE_SURAT_ATTACHMENT_ID, TEMPLATE_SURAT_ID, ATTACHMENT, CATATAN, 
					   UKURAN, TIPE, NAMA, NO_URUT, LAST_CREATE_USER, LAST_CREATE_DATE, 
					   LAST_UPDATE_USER, LAST_UPDATE_DATE
				  FROM TEMPLATE_SURAT_ATTACHMENT A
				  WHERE 1 = 1
			   "; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= " ".$stat." ".$sOrder;
		$this->query = $str;
		return $this->selectLimit($str,$limit,$from); 
    }

	/** 
	* Hitung jumlah record berdasarkan parameter (array). 
	* @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","JENIS_NASKAH_ID"=>"yyy") 
	* @return long Jumlah record yang sesuai kriteria 
	**/ 
	function getCountByParams($paramsArray=array(), $statement="")
	{
		$str = "
			SELECT COUNT(TEMPLATE_SURAT_ID) AS ROWCOUNT FROM TEMPLATE_SURAT A 
			WHERE 1 = 1 ".$statement; 

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

	function getCountByParamsLike($paramsArray=array())
	{
		$str = "
			SELECT COUNT(TEMPLATE_SURAT_ID) AS ROWCOUNT FROM TEMPLATE_SURAT 
			WHERE 1 = 1 "; 
		
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