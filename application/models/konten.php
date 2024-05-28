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

class Konten extends Entity{ 

	var $query;
	/**
	* Class constructor.
	**/
	function Konten()
	{
		$this->Entity(); 
	}

	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("KONTEN_ID", $this->getNextId("KONTEN_ID","KONTEN")); 
		$str = "
				INSERT INTO KONTEN(
					KONTEN_ID, 
					KODE, 
					NAMA, 
					KETERANGAN, 
					ATTACHMENT, 
					CREATED_BY, 
					CREATED_DATE)
				VALUES ('".$this->getField("KONTEN_ID")."', 
				'".$this->getField("KODE")."', 
				'".$this->getField("NAMA")."', 
				'".$this->getField("KETERANGAN")."', 
				'".$this->getField("ATTACHMENT")."', 
				'".$this->getField("CREATED_BY")."', 
				CURRENT_DATE
				)
		"; 
		$this->id = $this->getField("KONTEN_ID");
		$this->query = $str;
		// echo $str;exit();
		return $this->execQuery($str);
	}

	function update()
	{
		$str = "UPDATE KONTEN
				SET 
				KODE					='".$this->getField("KODE")."', 
				NAMA					='".$this->getField("NAMA")."', 
				KETERANGAN				='".$this->getField("KETERANGAN")."', 
				ATTACHMENT				='".$this->getField("ATTACHMENT")."', 
				UPDATED_BY				='".$this->getField("UPDATED_BY")."', 
				UPDATED_DATE			= CURRENT_DATE
				WHERE KONTEN_ID			='".$this->getField("KONTEN_ID")."' 
		";

		$this->query = $str;
		// echo $str;exit();
		return $this->execQuery($str);
	}

	function updateByField()
	{
		$str = "UPDATE KONTEN A SET
			".$this->getField("FIELD")."= '".$this->getField("FIELD_VALUE")."'
			WHERE KONTEN_ID = ".$this->getField("KONTEN_ID");

		$this->query = $str;
		// echo $str;exit();
		return $this->execQuery($str);
	}	

	function delete()
	{
		$str = "
			DELETE FROM KONTEN
			WHERE KONTEN_ID = '".$this->getField("KONTEN_ID")."'"; 

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
	function selectByParams($paramsArray=array(), $limit=-1, $from=-1, $statement="", $order=" ORDER BY KONTEN_ID ASC")
	{
		$str = "SELECT KONTEN_ID, KODE, NAMA, KETERANGAN, ATTACHMENT, CREATED_BY, CREATED_DATE, 
						UPDATED_BY, UPDATED_DATE
				FROM KONTEN A
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

	
	function selectByParamsMonitoring($paramsArray=array(), $limit=-1, $from=-1, $statement="", $order=" ORDER BY KONTEN_ID ASC")
	{
		$str = "SELECT KONTEN_ID, KODE, NAMA, KETERANGAN, ATTACHMENT, CREATED_BY, CREATED_DATE, 
						UPDATED_BY, UPDATED_DATE
				FROM KONTEN A
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
		$str = " SELECT KONTEN_ID, KODE, NAMA, KETERANGAN, ATTACHMENT, CREATED_BY, CREATED_DATE, 
						UPDATED_BY, UPDATED_DATE
				FROM KONTEN A
				  WHERE 1 = 1
		";

		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key LIKE '%$val%' ";
		}

		$str .= $statement." ORDER BY KONTEN_ID DESC";
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
			SELECT COUNT(KONTEN_ID) AS ROWCOUNT FROM KONTEN A 
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

	function getCountByParamsMonitoring($paramsArray=array(), $statement="")
	{
		$str = "SELECT COUNT(KONTEN_ID) AS ROWCOUNT FROM KONTEN A
			LEFT JOIN PEGAWAI B ON A.KONTEN_ID=B.PEGAWAI_ID
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


} 
?>