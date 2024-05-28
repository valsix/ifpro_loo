<? 

  /***
  * Entity-base class untuk mengimplementasikan tabel kategori.
  * 
  ***/
  include_once(APPPATH.'/models/Entity.php');

  class DisposisiKelompok extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function DisposisiKelompok()
	{
      $this->Entity(); 
    }
	
	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("DISPOSISI_KELOMPOK_ID", $this->getNextId("DISPOSISI_KELOMPOK_ID","DISPOSISI_KELOMPOK"));
		$this->tanggal = date("Y-m-d");
		$this->NowYear = date("Y");
		$str = "
		INSERT INTO DISPOSISI_KELOMPOK(DISPOSISI_KELOMPOK_ID, SURAT_MASUK_ID, SATUAN_KERJA_ID_ASAL, SATUAN_KERJA_KELOMPOK_ID,  
							  STATUS_DISPOSISI, LAST_CREATE_USER, LAST_CREATE_DATE)
                    VALUES ('".$this->getField("DISPOSISI_KELOMPOK_ID")."',
                            '".$this->getField("SURAT_MASUK_ID")."',
                            '".$this->getField("SATUAN_KERJA_ID_ASAL")."',
                            '".$this->getField("SATUAN_KERJA_KELOMPOK_ID")."',
                            '".$this->getField("STATUS_DISPOSISI")."',
							'".$this->getField("LAST_CREATE_USER")."',
							CURRENT_DATE
			)
		";
				
		$this->query = $str;
		$this->id = $this->getField("DISPOSISI_KELOMPOK_ID");
		// echo $str; exit;
		return $this->execQuery($str);
    }
	
    function updateByField()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "UPDATE DISPOSISI_KELOMPOK A SET
				  ".$this->getField("FIELD")." 		= '".$this->getField("FIELD_VALUE")."',
				  LAST_UPDATE_USER		 	= '".$this->getField("LAST_UPDATE_USER")."',
				  LAST_UPDATE_DATE			= CURRENT_DATE
				WHERE DISPOSISI_KELOMPOK_ID = '".$this->getField("DISPOSISI_KELOMPOK_ID")."'
				"; 
				$this->query = $str;
		return $this->execQuery($str);
    }	
	
	function delete()
	{		
		$str1 = "
		 		DELETE FROM DISPOSISI_KELOMPOK
                WHERE 
                  DISPOSISI_KELOMPOK_ID = '".$this->getField("DISPOSISI_KELOMPOK_ID")."'";
				  
		$this->query = $str1;
        return $this->execQuery($str1);
    }
	
	function deleteParent()
	{		
		$str1 = "
		 		DELETE FROM DISPOSISI_KELOMPOK
                WHERE 
                  SURAT_MASUK_ID = '".$this->getField("SURAT_MASUK_ID")."' ";
				  
		$this->query = $str1;
        return $this->execQuery($str1);
    }
	
	function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order=" ORDER BY DISPOSISI_KELOMPOK_ID ")
	{
		$str = "
				SELECT DISPOSISI_KELOMPOK_ID, SURAT_MASUK_ID, SATUAN_KERJA_ID_ASAL, 
					   SATUAN_KERJA_KELOMPOK_ID, KODE, NAMA, STATUS_DISPOSISI, LAST_CREATE_USER, 
					   LAST_CREATE_DATE, LAST_UPDATE_USER, LAST_UPDATE_DATE
				  FROM DISPOSISI_KELOMPOK A
				WHERE 1=1
		"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= " ".$statement.$order;
		$this->query = $str;
		//echo $str; 
	
		return $this->selectLimit($str,$limit,$from); 
    }
	
	
    function getKode($paramsArray=array(),$statement="")
	{
		$str = "
			SELECT SURAT_MASUK_ID, SATUAN_KERJA_KELOMPOK_ID SATUAN_KERJA_ID FROM DISPOSISI_KELOMPOK A
			WHERE 1 = 1
		"; 
		while(list($key,$val)=each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement;
		$this->selectLimit($str,-1,-1); 
		$i = 0;
		while($this->nextRow())
		{
			if($i == 0)
				$hasil .= "'".$this->getField("SATUAN_KERJA_ID")."'";
			else
				$hasil .= ","."'".$this->getField("SATUAN_KERJA_ID")."'";
			$i++;		
		}
		if($i == 0)
			$hasil = "''";
				
		return strtoupper($hasil);
		
    }
	
    function getJson($paramsArray=array(),$statement="")
	{
		$str = "
			SELECT ROW_TO_JSON(A) JSON FROM 
			(
				SELECT A.SURAT_MASUK_ID, B.DISPOSISI_ID, A.STATUS_DISPOSISI,  'KELOMPOK' || A.SATUAN_KERJA_KELOMPOK_ID SATUAN_KERJA_KELOMPOK_ID
				, A.NAMA NAMA_KELOMPOK 
				FROM DISPOSISI_KELOMPOK A
				INNER JOIN
				(
					SELECT MAX(DISPOSISI_ID) DISPOSISI_ID, SURAT_MASUK_ID, STATUS_DISPOSISI, DISPOSISI_KELOMPOK_ID
					FROM DISPOSISI
					WHERE DISPOSISI_KELOMPOK_ID > 0 GROUP BY SURAT_MASUK_ID, STATUS_DISPOSISI, DISPOSISI_KELOMPOK_ID
				) B ON A.DISPOSISI_KELOMPOK_ID = B.DISPOSISI_KELOMPOK_ID
				AND A.SURAT_MASUK_ID = B.SURAT_MASUK_ID AND A.STATUS_DISPOSISI = B.STATUS_DISPOSISI
			) A
			WHERE 1 = 1
		"; 
		while(list($key,$val)=each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement;
		// echo $str;exit;
		$this->selectLimit($str,-1,-1); 
		$hasil = "[";
		$i = 0;
		while($this->nextRow())
		{
			if($i == 0)
				$hasil .= $this->getField("JSON");
			else
				$hasil .= ",".$this->getField("JSON");
			$i++;		
		}
		$hasil .= "]";		
		$hasil = str_replace("null", '""', $hasil);
		return strtoupper($hasil);
		
    }
	
	
	
    function getCountByParams($paramsArray=array(),$statement="")
	{
		$str = "SELECT COUNT(surat_masuk_id) AS ROWCOUNT FROM DISPOSISI_KELOMPOK WHERE 1=1 "; 
		while(list($key,$val)=each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement;
		$this->query = $str;
		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0; 
    }
	
  } 
?>