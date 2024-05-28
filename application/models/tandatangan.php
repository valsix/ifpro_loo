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

class TandaTangan extends Entity{ 

	var $query;
	/**
	* Class constructor.
	**/
	function TandaTangan()
	{
		$this->Entity(); 
	}

	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("TANDA_TANGAN_ID", $this->getNextId("TANDA_TANGAN_ID","TANDA_TANGAN")); 
		$str = "
			INSERT INTO TANDA_TANGAN(
				TANDA_TANGAN_ID, PEGAWAI_ID, NIP, NAMA, KETERANGAN, ATTACHMENT, 
				DIGIT_NOMOR, LAST_CREATE_USER, LAST_CREATE_DATE)
			VALUES (
				'".$this->getField("TANDA_TANGAN_ID")."', 
				'".$this->getField("PEGAWAI_ID")."', 
				'".$this->getField("NIP")."', 
				'".$this->getField("NAMA")."', 
				'".$this->getField("KETERANGAN")."', 
				'".$this->getField("ATTACHMENT")."', 
				'".$this->getField("DIGIT_NOMOR")."', 
				'".$this->getField("LAST_CREATE_USER")."', 
				CURRENT_DATE
				)
		"; 
		$this->id = $this->getField("TANDA_TANGAN_ID");
		$this->query = $str;
		// echo $str;exit();
		return $this->execQuery($str);
	}

	function update()
	{
		$str = "
			UPDATE TANDA_TANGAN
			SET 
					PEGAWAI_ID				='".$this->getField("PEGAWAI_ID")."', 
					NIP						='".$this->getField("NIP")."', 
					NAMA					='".$this->getField("NAMA")."', 
					KETERANGAN				='".$this->getField("KETERANGAN")."', 
					ATTACHMENT				='".$this->getField("ATTACHMENT")."', 
					DIGIT_NOMOR				='".$this->getField("DIGIT_NOMOR")."', 
					LAST_UPDATE_USER		='".$this->getField("LAST_UPDATE_USER")."', 
					LAST_UPDATE_DATE		= CURRENT_DATE
		  	WHERE TANDA_TANGAN_ID			='".$this->getField("TANDA_TANGAN_ID")."'
		 

		";

		$this->query = $str;
		// echo $str;exit();
		return $this->execQuery($str);
	}

	function updateByField()
	{
		$str = "UPDATE TANDA_TANGAN A SET
			".$this->getField("FIELD")."= '".$this->getField("FIELD_VALUE")."'
			WHERE TANDA_TANGAN_ID = ".$this->getField("TANDA_TANGAN_ID");

		$this->query = $str;
		// echo $str;exit();
		return $this->execQuery($str);
	}	

	function delete()
	{
		$str = "
			DELETE FROM TANDA_TANGAN
			WHERE TANDA_TANGAN_ID = '".$this->getField("TANDA_TANGAN_ID")."'"; 

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
	function selectByParams($paramsArray=array(), $limit=-1, $from=-1, $statement="", $order=" ORDER BY TANDA_TANGAN_ID ASC")
	{
		$str = "SELECT TANDA_TANGAN_ID, PEGAWAI_ID, NIP, NAMA, KETERANGAN, ATTACHMENT, 
				DIGIT_NOMOR, A.LAST_CREATE_USER, A.LAST_CREATE_DATE, A.LAST_UPDATE_USER, 
				A.LAST_UPDATE_DATE
			FROM TANDA_TANGAN A
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

	
	function selectByParamsMonitoring($paramsArray=array(), $limit=-1, $from=-1, $statement="", $order=" ORDER BY TANDA_TANGAN_ID ASC")
	{
		$str = "SELECT TANDA_TANGAN_ID, PEGAWAI_ID, NIP, NAMA, KETERANGAN, ATTACHMENT, 
				DIGIT_NOMOR, A.LAST_CREATE_USER, A.LAST_CREATE_DATE, A.LAST_UPDATE_USER, 
				A.LAST_UPDATE_DATE
			FROM TANDA_TANGAN A
			LEFT JOIN PEGAWAI B ON A.TANDA_TANGAN_ID=B.PEGAWAI_ID
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
		$str = "  SELECT TANDA_TANGAN_ID, PEGAWAI_ID, NIP, NAMA, KETERANGAN, ATTACHMENT, 
					DIGIT_NOMOR, A.LAST_CREATE_USER, A.LAST_CREATE_DATE, A.LAST_UPDATE_USER, 
					A.LAST_UPDATE_DATE
				  FROM TANDA_TANGAN A
				  WHERE 1 = 1
		";

		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key LIKE '%$val%' ";
		}

		$str .= $statement." ORDER BY TANDA_TANGAN_ID DESC";
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
			SELECT COUNT(TANDA_TANGAN_ID) AS ROWCOUNT FROM TANDA_TANGAN A 
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
		$str = "SELECT COUNT(TANDA_TANGAN_ID) AS ROWCOUNT FROM TANDA_TANGAN A
			LEFT JOIN PEGAWAI B ON A.TANDA_TANGAN_ID=B.PEGAWAI_ID
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