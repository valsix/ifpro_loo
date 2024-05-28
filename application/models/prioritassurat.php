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

class PrioritasSurat extends Entity{ 

	var $query;
	/**
	* Class constructor.
	**/
	function PrioritasSurat()
	{
		$this->Entity(); 
	}

	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("PRIORITAS_SURAT_ID", $this->getNextId("PRIORITAS_SURAT_ID","PRIORITAS_SURAT")); 
		$str = "
			INSERT INTO PRIORITAS_SURAT (
				PRIORITAS_SURAT_ID, NAMA, KODE, LAMA_HARI,  
				KETERANGAN, LAST_CREATE_USER, LAST_CREATE_DATE) 
			VALUES (
				'".$this->getField("PRIORITAS_SURAT_ID")."', 
				'".$this->getField("NAMA")."', 
				'".$this->getField("KODE")."', 
				'".$this->getField("LAMA_HARI")."', 
				'".$this->getField("KETERANGAN")."', 
				'".$this->getField("LAST_CREATE_USER")."', 
				CURRENT_DATE
			)
		"; 
		$this->id = $this->getField("PRIORITAS_SURAT_ID");
		$this->query = $str;
		// echo $str;exit();
		return $this->execQuery($str);
	}

	function update()
	{
		$str = "
			UPDATE PRIORITAS_SURAT
			SET NAMA         		= '".$this->getField("NAMA")."',
			KODE      				= '".$this->getField("KODE")."',
			LAMA_HARI  				= '".$this->getField("LAMA_HARI")."',
			KETERANGAN    			= '".$this->getField("KETERANGAN")."',
			LAST_UPDATE_USER   		= '".$this->getField("LAST_UPDATE_USER")."',
			LAST_UPDATE_DATE   		= CURRENT_DATE
			WHERE PRIORITAS_SURAT_ID    = '".$this->getField("PRIORITAS_SURAT_ID")."'
		";

		$this->query = $str;
		// echo $str;exit();
		return $this->execQuery($str);
	}

	function updateByField()
	{
		$str = "UPDATE PRIORITAS_SURAT A SET
			".$this->getField("FIELD")."= '".$this->getField("FIELD_VALUE")."'
			WHERE PRIORITAS_SURAT_ID = ".$this->getField("PRIORITAS_SURAT_ID");

		$this->query = $str;
		// echo $str;exit();
		return $this->execQuery($str);
	}	

	function delete()
	{
		$str = "
			DELETE FROM PRIORITAS_SURAT
			WHERE PRIORITAS_SURAT_ID = ".$this->getField("PRIORITAS_SURAT_ID"); 

		$this->query = $str;
		// echo $str;exit();
		return $this->execQuery($str);
	}

	/** 
	* Cari record berdasarkan array parameter dan limit tampilan 
	* @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","nama"=>"yyy") 
	* @param int limit Jumlah maksimal record yang akan diambil 
	* @param int from Awal record yang diambil 
	* @return boolean True jika sukses, false jika tidak 
	**/ 
	function selectByParams($paramsArray=array(), $limit=-1, $from=-1, $statement="", $order=" ORDER BY PRIORITAS_SURAT_ID ASC")
	{
		$str = "
			SELECT 
				PRIORITAS_SURAT_ID, NAMA, KODE, LAMA_HARI, KETERANGAN, 
				LAST_CREATE_USER, LAST_CREATE_DATE, LAST_UPDATE_USER, LAST_UPDATE_DATE
			FROM PRIORITAS_SURAT A
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
				PRIORITAS_SURAT_ID, NAMA, KODE, LAMA_HARI, KETERANGAN
			FROM PRIORITAS_SURAT A
			WHERE 1 = 1
		";

		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key LIKE '%$val%' ";
		}

		$str .= $statement." ORDER BY PRIORITAS_SURAT_ID DESC";
		$this->query = $str;
		// echo $str;exit();		
		return $this->selectLimit($str,$limit,$from); 
	}

	/** 
	* Hitung jumlah record berdasarkan parameter (array). 
	* @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","nama"=>"yyy") 
	* @return long Jumlah record yang sesuai kriteria 
	**/ 
	function getCountByParams($paramsArray=array(), $statement="")
	{
		$str = "
			SELECT COUNT(PRIORITAS_SURAT_ID) AS ROWCOUNT FROM PRIORITAS_SURAT A 
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
			SELECT COUNT(PRIORITAS_SURAT_ID) AS ROWCOUNT FROM PRIORITAS_SURAT 
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