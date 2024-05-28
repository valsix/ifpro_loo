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

class Peraturan extends Entity{ 

	var $query;
	/**
	* Class constructor.
	**/
	function Peraturan()
	{
		$this->Entity(); 
	}

	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("PERATURAN_ID", $this->getNextId("PERATURAN_ID","PERATURAN")); 
		$str = "
			INSERT INTO PERATURAN( 
				PERATURAN_ID, NAMA, NOMOR, URUT, CABANG_ID, LINK_FILE,
				LAST_CREATE_USER, 
				LAST_CREATE_DATE)
			VALUES (".$this->getField("PERATURAN_ID").", 
				'".$this->getField("NAMA")."', 
				'".$this->getField("NOMOR")."', 
				".$this->getField("URUT").", 
				'".$this->getField("CABANG_ID")."', 
				'".$this->getField("LINK_FILE")."', 
				'".$this->getField("LAST_CREATE_USER")."', 
				CURRENT_DATE
			)
		";

		$this->id = $this->getField("PERATURAN_ID");
		$this->query = $str;
		// echo $str;exit();
		return $this->execQuery($str);
	}

	function update()
	{
		$str = "
			UPDATE PERATURAN
				SET 
				NAMA			='".$this->getField("NAMA")."', 
				NOMOR				='".$this->getField("NOMOR")."', 
				URUT				=".$this->getField("URUT").", 
				CABANG_ID			='".$this->getField("CABANG_ID")."', 
				LINK_FILE			='".$this->getField("LINK_FILE")."', 
				LAST_UPDATE_USER	='".$this->getField("LAST_UPDATE_USER")."', 
				LAST_UPDATE_DATE	= CURRENT_DATE
				WHERE PERATURAN_ID		=".$this->getField("PERATURAN_ID")."
		";

		$this->query = $str;
		// echo $str;exit();
		return $this->execQuery($str);
	}

	function updateByField()
	{
		$str = "UPDATE PERATURAN A SET
			".$this->getField("FIELD")."= '".$this->getField("FIELD_VALUE")."'
			WHERE PERATURAN_ID = ".$this->getField("PERATURAN_ID");

		$this->query = $str;
		// echo $str;exit();
		return $this->execQuery($str);
	}	

	function delete()
	{
		$str = "
			DELETE FROM PERATURAN
			WHERE PERATURAN_ID = '".$this->getField("PERATURAN_ID")."'"; 

		$this->query = $str;
		// echo $str;exit();
		return $this->execQuery($str);
	}

	/** 
	* Cari record berdasarkan array parameter dan limit tampilan 
	* @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","NOMOR"=>"yyy") 
	* @param int limit Jumlah maksimal record yang akan diambil 
	* @param int from Awal record yang diambil 
	* @return boolean True jika sukses, false jika tidak 
	**/ 
	function selectByParams($paramsArray=array(), $limit=-1, $from=-1, $statement="", $order=" ORDER BY PERATURAN_ID ASC")
	{
		$str = "
			SELECT PERATURAN_ID, NAMA, NOMOR, URUT, CABANG_ID, LINK_FILE, 
				LAST_CREATE_USER, LAST_CREATE_DATE, LAST_UPDATE_USER, LAST_UPDATE_DATE
			FROM PERATURAN A
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

	
	function selectByParamsMonitoring($paramsArray=array(), $limit=-1, $from=-1, $statement="", $order=" ORDER BY PERATURAN_ID ASC")
	{
		$str = "
			SELECT PERATURAN_ID, NAMA, NOMOR, URUT, CABANG_ID, LINK_FILE, 
				LAST_CREATE_USER, LAST_CREATE_DATE, LAST_UPDATE_USER, LAST_UPDATE_DATE
			FROM PERATURAN A
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
			SELECT PERATURAN_ID, NAMA, NOMOR, URUT, CABANG_ID, LINK_FILE,
				LAST_CREATE_USER, LAST_CREATE_DATE, LAST_UPDATE_USER, LAST_UPDATE_DATE
			FROM PERATURAN A
 			WHERE 1 = 1
		";

		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key LIKE '%$val%' ";
		}

		$str .= $statement." ORDER BY PERATURAN_ID DESC";
		$this->query = $str;
		// echo $str;exit();		
		return $this->selectLimit($str,$limit,$from); 
	}

	/** 
	* Hitung jumlah record berdasarkan parameter (array). 
	* @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","NOMOR"=>"yyy") 
	* @return long Jumlah record yang sesuai kriteria 
	**/ 
	function getCountByParams($paramsArray=array(), $statement="")
	{
		$str = "
			SELECT COUNT(PERATURAN_ID) AS ROWCOUNT FROM PERATURAN A 
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
		$str = "SELECT COUNT(PERATURAN_ID) AS ROWCOUNT FROM PERATURAN A
			LEFT JOIN CABANG B ON A.CABANG_ID=B.CABANG_ID
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