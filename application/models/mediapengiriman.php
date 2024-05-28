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

class MediaPengiriman extends Entity{ 

	var $query;
	/**
	* Class constructor.
	**/
	function MediaPengiriman()
	{
		$this->Entity(); 
	}

	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("MEDIA_PENGIRIMAN_ID", $this->getNextId("MEDIA_PENGIRIMAN_ID","MEDIA_PENGIRIMAN")); 
		$str = "
			INSERT INTO MEDIA_PENGIRIMAN (
				MEDIA_PENGIRIMAN_ID, NAMA, KODE, 
				KETERANGAN, LAST_CREATE_USER, LAST_CREATE_DATE) 
			VALUES (
				'".$this->getField("MEDIA_PENGIRIMAN_ID")."', 
				'".$this->getField("NAMA")."', 
				'".$this->getField("KODE")."', 
				'".$this->getField("KETERANGAN")."', 
				'".$this->getField("LAST_CREATE_USER")."', 
				CURRENT_DATE
			)
		"; 
		$this->id = $this->getField("MEDIA_PENGIRIMAN_ID");
		$this->query = $str;
		// echo $str;exit();
		return $this->execQuery($str);
	}

	function update()
	{
		$str = "
			UPDATE MEDIA_PENGIRIMAN
			SET NAMA         		= '".$this->getField("NAMA")."',
			KODE      				= '".$this->getField("KODE")."',
			KETERANGAN    			= '".$this->getField("KETERANGAN")."',
			LAST_UPDATE_USER   		= '".$this->getField("LAST_UPDATE_USER")."',
			LAST_UPDATE_DATE   		= CURRENT_DATE
			WHERE MEDIA_PENGIRIMAN_ID    = '".$this->getField("MEDIA_PENGIRIMAN_ID")."'
		";

		$this->query = $str;
		// echo $str;exit();
		return $this->execQuery($str);
	}

	function updateByField()
	{
		$str = "UPDATE MEDIA_PENGIRIMAN A SET
			".$this->getField("FIELD")."= '".$this->getField("FIELD_VALUE")."'
			WHERE MEDIA_PENGIRIMAN_ID = ".$this->getField("MEDIA_PENGIRIMAN_ID");

		$this->query = $str;
		// echo $str;exit();
		return $this->execQuery($str);
	}	

	function delete()
	{
		$str = "
			DELETE FROM MEDIA_PENGIRIMAN
			WHERE MEDIA_PENGIRIMAN_ID = ".$this->getField("MEDIA_PENGIRIMAN_ID"); 

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
	function selectByParams($paramsArray=array(), $limit=-1, $from=-1, $statement="", $order=" ORDER BY MEDIA_PENGIRIMAN_ID ASC")
	{
		$str = "
			SELECT 
				MEDIA_PENGIRIMAN_ID, NAMA, KODE, KETERANGAN, 
				LAST_CREATE_USER, LAST_CREATE_DATE, LAST_UPDATE_USER, LAST_UPDATE_DATE
			FROM MEDIA_PENGIRIMAN A
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
				MEDIA_PENGIRIMAN_ID, NAMA, KODE, KETERANGAN
			FROM MEDIA_PENGIRIMAN A
			WHERE 1 = 1
		";

		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key LIKE '%$val%' ";
		}

		$str .= $statement." ORDER BY MEDIA_PENGIRIMAN_ID DESC";
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
			SELECT COUNT(MEDIA_PENGIRIMAN_ID) AS ROWCOUNT FROM MEDIA_PENGIRIMAN A 
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
			SELECT COUNT(MEDIA_PENGIRIMAN_ID) AS ROWCOUNT FROM MEDIA_PENGIRIMAN 
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