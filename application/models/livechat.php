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

class LiveChat extends Entity{ 

	var $query;
	/**
	* Class constructor.
	**/
	function LiveChat()
	{
		$this->Entity(); 
	}

	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("PEJABAT_PENGGANTI_ID", $this->getNextId("PEJABAT_PENGGANTI_ID","PEJABAT_PENGGANTI")); 
		$str = "
				INSERT INTO PEJABAT_PENGGANTI(
					PEJABAT_PENGGANTI_ID, SATUAN_KERJA_ID, SATUAN_KERJA, PEGAWAI_ID, NAMA, PEGAWAI_ID_PENGGANTI, 
					NAMA_PENGGANTI, TANGGAL_MULAI, TANGGAL_SELESAI, LAST_CREATE_USER, 
					LAST_CREATE_DATE, AN_TAMBAHAN, STATUS_AKTIF)
				VALUES ('".$this->getField("PEJABAT_PENGGANTI_ID")."', 
						'".$this->getField("SATUAN_KERJA_ID")."', 
						'".$this->getField("SATUAN_KERJA")."', 
						'".$this->getField("PEGAWAI_ID")."', 
						'".$this->getField("NAMA")."', 
						'".$this->getField("PEGAWAI_ID_PENGGANTI")."', 
						'".$this->getField("NAMA_PENGGANTI")."', 
						".$this->getField("TANGGAL_MULAI").", 
						".$this->getField("TANGGAL_SELESAI").", 
						'".$this->getField("LAST_CREATE_USER")."', 
						CURRENT_DATE
						, '".$this->getField("AN_TAMBAHAN")."'
						, '".$this->getField("STATUS_AKTIF")."'
				)
		"; 
		$this->id = $this->getField("PEJABAT_PENGGANTI_ID");
		$this->query = $str;
		// echo $str;exit();
		return $this->execQuery($str);
	}

	function update()
	{
		$str = "UPDATE PEJABAT_PENGGANTI
				SET 
					PEGAWAI_ID				='".$this->getField("PEGAWAI_ID")."', 
					NAMA					='".$this->getField("NAMA")."', 
					PEGAWAI_ID_PENGGANTI	='".$this->getField("PEGAWAI_ID_PENGGANTI")."', 
					NAMA_PENGGANTI			='".$this->getField("NAMA_PENGGANTI")."', 
					TANGGAL_MULAI			=".$this->getField("TANGGAL_MULAI").", 
					TANGGAL_SELESAI			=".$this->getField("TANGGAL_SELESAI").", 
					LAST_UPDATE_USER		='".$this->getField("LAST_UPDATE_USER")."', 
					LAST_UPDATE_DATE		= CURRENT_DATE
					, AN_TAMBAHAN= '".$this->getField("AN_TAMBAHAN")."'
					, STATUS_AKTIF= '".$this->getField("STATUS_AKTIF")."'
				WHERE PEJABAT_PENGGANTI_ID	='".$this->getField("PEJABAT_PENGGANTI_ID")."'
		";

		$this->query = $str;
		// echo $str;exit();
		return $this->execQuery($str);
	}

	function updateByField()
	{
		$str = "UPDATE PEJABAT_PENGGANTI A SET
			".$this->getField("FIELD")."= '".$this->getField("FIELD_VALUE")."'
			WHERE PEJABAT_PENGGANTI_ID = ".$this->getField("PEJABAT_PENGGANTI_ID");

		$this->query = $str;
		// echo $str;exit();
		return $this->execQuery($str);
	}	

	function delete()
	{
		$str = "
			DELETE FROM PEJABAT_PENGGANTI
			WHERE PEJABAT_PENGGANTI_ID = '".$this->getField("PEJABAT_PENGGANTI_ID")."'"; 

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
	function selectByParamsTo($paramsArray=array(), $limit=-1, $from=-1, $statement="", $order=" ORDER BY A.LIVE_CHAT_ID")
	{
		$str = "SELECT LIVE_CHAT_ID, PEGAWAI_ID_BY, PEGAWAI_ID_TO, PESAN,B.NAMA
				FROM LIVE_CHAT A
				INNER JOIN PEGAWAI B ON A.PEGAWAI_ID_TO = B.PEGAWAI_ID
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

	function selectByParamsBy($paramsArray=array(), $limit=-1, $from=-1, $statement="", $order=" ORDER BY A.LIVE_CHAT_ID")
	{
		$str = "SELECT LIVE_CHAT_ID, PEGAWAI_ID_BY, PEGAWAI_ID_TO, PESAN,B.NAMA
				FROM LIVE_CHAT A
				INNER JOIN PEGAWAI B ON A.PEGAWAI_ID_BY = B.PEGAWAI_ID
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

	


	/** 
	* Hitung jumlah record berdasarkan parameter (array). 
	* @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","nama"=>"yyy") 
	* @return long Jumlah record yang sesuai kriteria 
	**/ 
	function getCountByParams($paramsArray=array(), $statement="")
	{
		$str = "
			SELECT COUNT(PEJABAT_PENGGANTI_ID) AS ROWCOUNT FROM PEJABAT_PENGGANTI A 
			WHERE 1 = 1 ".$statement; 

		while(list($key,$val)=each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}

		// echo $str;exit;
		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0; 
	}



} 
?>