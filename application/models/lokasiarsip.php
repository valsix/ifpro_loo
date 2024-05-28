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

class LokasiArsip extends Entity{ 

	var $query;
	/**
	* Class constructor.
	**/
	function LokasiArsip()
	{
		$this->Entity(); 
	}

	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("LOKASI_ARSIP_ID", $this->getNextId("LOKASI_ARSIP_ID","LOKASI_ARSIP")); 
		$str = "
			INSERT INTO LOKASI_ARSIP (
				LOKASI_ARSIP_ID, LOKASI_ARSIP_PARENT_ID, CABANG_ID, SATUAN_KERJA_ID,
				NAMA, KODE, KETERANGAN, LAST_CREATE_USER, LAST_CREATE_DATE) 
			VALUES (
				'".$this->getField("LOKASI_ARSIP_ID")."', 
				'".$this->getField("LOKASI_ARSIP_PARENT_ID")."', 
				'".$this->getField("CABANG_ID")."', 
				'".$this->getField("SATUAN_KERJA_ID")."', 
				'".$this->getField("NAMA")."', 
				'".$this->getField("KODE")."', 
				'".$this->getField("KETERANGAN")."', 
				'".$this->getField("LAST_CREATE_USER")."', 
				CURRENT_DATE
			)
		"; 
		$this->id = $this->getField("LOKASI_ARSIP_ID");
		$this->query = $str;
		// echo $str;exit();
		return $this->execQuery($str);
	}

	function update()
	{
		$str = "
			UPDATE LOKASI_ARSIP
			SET NAMA         		= '".$this->getField("NAMA")."',
			KODE      				= '".$this->getField("KODE")."',
			KETERANGAN    			= '".$this->getField("KETERANGAN")."',
			CABANG_ID    			= '".$this->getField("CABANG_ID")."',
			SATUAN_KERJA_ID    		= '".$this->getField("SATUAN_KERJA_ID")."',
			LAST_UPDATE_USER   		= '".$this->getField("LAST_UPDATE_USER")."',
			LAST_UPDATE_DATE   		= CURRENT_DATE
			WHERE LOKASI_ARSIP_ID    = '".$this->getField("LOKASI_ARSIP_ID")."'
		";

		$this->query = $str;
		// echo $str;exit();
		return $this->execQuery($str);
	}

	function updateByField()
	{
		$str = "UPDATE LOKASI_ARSIP A SET
			".$this->getField("FIELD")."= '".$this->getField("FIELD_VALUE")."'
			WHERE LOKASI_ARSIP_ID = ".$this->getField("LOKASI_ARSIP_ID");

		$this->query = $str;
		// echo $str;exit();
		return $this->execQuery($str);
	}	

	function delete()
	{
		$str = "
			DELETE FROM LOKASI_ARSIP
			WHERE LOKASI_ARSIP_ID = '".$this->getField("LOKASI_ARSIP_ID")."'"; 

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
	function selectByParams($paramsArray=array(), $limit=-1, $from=-1, $statement="", $order=" ORDER BY LOKASI_ARSIP_ID ASC")
	{
		$str = "
			SELECT 
				LOKASI_ARSIP_ID, LOKASI_ARSIP_PARENT_ID, CABANG_ID, A.SATUAN_KERJA_ID,
				A.NAMA, KODE, KETERANGAN,
				A.LAST_CREATE_USER, A.LAST_CREATE_DATE, A.LAST_UPDATE_USER, A.LAST_UPDATE_DATE
			FROM LOKASI_ARSIP A
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

	
	function selectByParamsMonitoring($paramsArray=array(), $limit=-1, $from=-1, $statement="", $order=" ORDER BY LOKASI_ARSIP_ID ASC")
	{
		$str = "
			SELECT 
				LOKASI_ARSIP_ID, LOKASI_ARSIP_PARENT_ID, CABANG_ID, A.SATUAN_KERJA_ID,
				A.NAMA NAMA_LOKASI_ARSIP, KODE, KETERANGAN, B.NAMA SATUAN_KERJA, 
				A.LAST_CREATE_USER, A.LAST_CREATE_DATE, A.LAST_UPDATE_USER, A.LAST_UPDATE_DATE
			FROM LOKASI_ARSIP A
			LEFT JOIN SATUAN_KERJA B ON A.SATUAN_KERJA_ID=B.SATUAN_KERJA_ID
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
				LOKASI_ARSIP_ID, LOKASI_ARSIP_PARENT_ID, CABANG_ID, SATUAN_KERJA_ID,
				NAMA, KODE, KETERANGAN 
			FROM LOKASI_ARSIP A
			WHERE 1 = 1
		";

		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key LIKE '%$val%' ";
		}

		$str .= $statement." ORDER BY LOKASI_ARSIP_ID DESC";
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
			SELECT COUNT(LOKASI_ARSIP_ID) AS ROWCOUNT FROM LOKASI_ARSIP A 
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
		$str = "
			SELECT COUNT(LOKASI_ARSIP_ID) AS ROWCOUNT FROM LOKASI_ARSIP A
			LEFT JOIN SATUAN_KERJA B ON A.SATUAN_KERJA_ID=B.SATUAN_KERJA_ID 
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

    function generateKode($reqParent, $satuanKerjaId)
	{
		$str = "SELECT LPAD(COALESCE((RIGHT(MAX(KODE), 2)::INT + 1), 1)::VARCHAR, 2, '0')  ROWCOUNT 
					FROM LOKASI_ARSIP WHERE LOKASI_ARSIP_PARENT_ID = '".$reqParent."' AND SATUAN_KERJA_ID = '".$satuanKerjaId."' ";
					
		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return "01"; 
    }
	
	
	
	function getCountByParamsLike($paramsArray=array())
	{
		$str = "
			SELECT COUNT(LOKASI_ARSIP_ID) AS ROWCOUNT FROM LOKASI_ARSIP A
			LEFT JOIN SATUAN_KERJA B ON A.SATUAN_KERJA_ID=B.SATUAN_KERJA_ID 
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