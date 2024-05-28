<? 

  /***
  * Entity-base class untuk mengimplementasikan tabel kategori.
  * 
  ***/
  include_once(APPPATH.'/models/Entity.php');

  class DisposisiKeluar extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function Disposisi()
	{
      $this->Entity(); 
    }
	
	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("DISPOSISI_KELUAR_ID", $this->getNextId("DISPOSISI_KELUAR_ID","DISPOSISI_KELUAR"));
		$this->tanggal = date("Y-m-d");
		$this->NowYear = date("Y");
		$str = "
		INSERT INTO DISPOSISI_KELUAR(DISPOSISI_KELUAR_ID, DISPOSISI_KELUAR_PARENT_ID, SURAT_KELUAR_ID, TAHUN, SATUAN_KERJA_ID_ASAL, SATUAN_KERJA_ID_TUJUAN, TANGGAL_DISPOSISI_KELUAR, 
							  ISI, STATUS_DISPOSISI_KELUAR, LAST_CREATE_USER, LAST_CREATE_DATE)
                    VALUES ('".$this->getField("DISPOSISI_KELUAR_ID")."',
                            '".(int)$this->getField("DISPOSISI_KELUAR_PARENT_ID")."',
                            '".$this->getField("SURAT_KELUAR_ID")."',
                            ".$this->NowYear.",
                            '".$this->getField("SATUAN_KERJA_ID_ASAL")."',
                            '".$this->getField("SATUAN_KERJA_ID_TUJUAN")."',
                            TO_DATE(TO_CHAR(NOW(), 'yyyy/mm/dd hh24:mi:ss'), 'yyyy/mm/dd hh24:mi:ss'),
                            '".$this->getField("ISI")."',
                            '".$this->getField("STATUS_DISPOSISI_KELUAR")."',
							'".$this->getField("LAST_CREATE_USER")."',
							CURRENT_DATE
			)
		";
				
		$this->query = $str;
		$this->id = $this->getField("DISPOSISI_KELUAR_ID");
		// echo $str; exit;
		return $this->execQuery($str);
    }
	
	function insertAttachment()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("DISPOSISI_KELUAR_ATTACHMENT_ID", $this->getNextId("DISPOSISI_KELUAR_ATTACHMENT_ID","DISPOSISI_KELUAR_ATTACHMENT")); 
		$str = "
			INSERT INTO DISPOSISI_KELUAR_ATTACHMENT(
						DISPOSISI_KELUAR_ATTACHMENT_ID, SURAT_KELUAR_ID, DISPOSISI_KELUAR_ID, ATTACHMENT, 
						UKURAN, TIPE, NAMA, LAST_CREATE_USER, LAST_CREATE_DATE
            )
            VALUES ('".$this->getField("DISPOSISI_KELUAR_ATTACHMENT_ID")."',
					'".$this->getField("SURAT_KELUAR_ID")."',
					'".$this->getField("DISPOSISI_KELUAR_ID")."',
					'".$this->getField("ATTACHMENT")."',
                    ".(int)$this->getField("UKURAN").",
                    '".$this->getField("TIPE")."',
                    '".$this->getField("NAMA")."',
                    '".$this->getField("LAST_CREATE_USER")."',
				  	CURRENT_DATE
				)";
				
		$this->query = $str;
		$this->id = $this->getField("DISPOSISI_KELUAR_ATTACHMENT_ID");
		return $this->execQuery($str);			
	}
	
    function updateByField()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "UPDATE DISPOSISI_KELUAR A SET
				  ".$this->getField("FIELD")." 		= '".$this->getField("FIELD_VALUE")."',
				  LAST_UPDATE_USER		 	= '".$this->getField("LAST_UPDATE_USER")."',
				  LAST_UPDATE_DATE			= CURRENT_DATE
				WHERE DISPOSISI_KELUAR_ID = '".$this->getField("DISPOSISI_KELUAR_ID")."'
				"; 
				$this->query = $str;
		return $this->execQuery($str);
    }	
	
	
    function updateByFieldValidasiSatker()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "UPDATE DISPOSISI_KELUAR A SET
				  ".$this->getField("FIELD")." 		= '".$this->getField("FIELD_VALUE")."',
				  LAST_UPDATE_USER		 	= '".$this->getField("LAST_UPDATE_USER")."',
				  LAST_UPDATE_DATE			= CURRENT_DATE
				WHERE SURAT_KELUAR_ID = '".$this->getField("SURAT_KELUAR_ID")."' AND
					  SATUAN_KERJA_ID_TUJUAN		 = '".$this->getField("SATUAN_KERJA_ID_TUJUAN")."'
				"; 
				$this->query = $str;
		return $this->execQuery($str);
    }	
	
    function updateByFieldValidasiUser()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "UPDATE DISPOSISI_KELUAR A SET
				  ".$this->getField("FIELD")." 		= '".$this->getField("FIELD_VALUE")."',
				  LAST_UPDATE_USER		 	= '".$this->getField("LAST_UPDATE_USER")."',
				  LAST_UPDATE_DATE			= CURRENT_DATE
				WHERE SURAT_KELUAR_ID = '".$this->getField("SURAT_KELUAR_ID")."' AND
					  USER_ID		 = '".$this->getField("USER_ID")."'
				"; 
				$this->query = $str;
		return $this->execQuery($str);
    }	
	
	function delete()
	{		
		$str1 = "
		 		DELETE FROM DISPOSISI_KELUAR
                WHERE 
                  DISPOSISI_KELUAR_ID = '".$this->getField("DISPOSISI_KELUAR_ID")."'";
				  
		$this->query = $str1;
        return $this->execQuery($str1);
    }
	
	function deleteParent()
	{		
		$str1 = "
		 		DELETE FROM DISPOSISI_KELUAR
                WHERE 
                  SURAT_KELUAR_ID = '".$this->getField("SURAT_KELUAR_ID")."' ";
				  
		$this->query = $str1;
        return $this->execQuery($str1);
    }
	
	function deleteModif()
	{	
		$str= "
		 		UPDATE DISPOSISI_KELUAR SET TERDISPOSISI_KELUAR = NULL
                WHERE 
                  DISPOSISI_KELUAR_ID = '".$this->getField("DISPOSISI_KELUAR_PARENT_ID")."'";
				  
		$this->query = $str;
        $this->execQuery($str);
			
		$str1 = "
		 		DELETE FROM DISPOSISI_KELUAR
                WHERE 
                  DISPOSISI_KELUAR_ID = '".$this->getField("DISPOSISI_KELUAR_ID")."'";
				  
		$this->query = $str1;
        return $this->execQuery($str1);
    }
	
	function updateIsiDisposisi()
	{
        $str = "UPDATE DISPOSISI_KELUAR SET 
				isi = '".$this->getField("ISI")."'
                WHERE DISPOSISI_KELUAR_ID = '".$this->getField("DISPOSISI_KELUAR_ID")."'
				";
				  
		$this->query = $str;
        return $this->execQuery($str);
    }
	
	function updateDisposisi()
	{
        $str = "UPDATE DISPOSISI_KELUAR SET TERDISPOSISI_KELUAR = 1
                WHERE 
				  SURAT_KELUAR_ID = '".$this->getField("surat_masuk_id")."' AND
                  SATUAN_KERJA_ID_TUJUAN = '".$this->getField("SATUAN_KERJA_ID_TUJUAN")."'";
				  
		$this->query = $str;
        return $this->execQuery($str);
    }
	
	function updateDisposisiAwal()
	{
        $str = "UPDATE SURAT_KELUAR_TUJUAN SET TERDISPOSISI_KELUAR = 1
                WHERE 
                  SURAT_KELUAR_ID = '".$this->getField("SURAT_KELUAR_ID")."' AND
                  SATUAN_KERJA_ID_TUJUAN = '".$this->getField("SATUAN_KERJA_ID_TUJUAN")."'";
				  
		$this->query = $str;
        return $this->execQuery($str);
    }
	
					
					
	function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order=" ORDER BY CASE 
																						WHEN A.STATUS_DISPOSISI_KELUAR = 'TUJUAN' THEN 1 
																						WHEN A.STATUS_DISPOSISI_KELUAR = 'DISPOSISI_KELUAR' THEN 2
																						WHEN A.STATUS_DISPOSISI_KELUAR = 'TEMBUSAN' THEN 3
																						WHEN A.STATUS_DISPOSISI_KELUAR = 'DISPOSISI_KELUAR_TEMBUSAN' THEN 4 
																						ELSE 5 END ASC ")
	{
		$str = "
		SELECT 
		DISPOSISI_KELUAR_ID, SURAT_KELUAR_ID, TAHUN, STATUS_DISPOSISI_KELUAR,
		   SATUAN_KERJA_ID_ASAL, SATUAN_KERJA_ID_TUJUAN, TANGGAL_DISPOSISI_KELUAR, 
		   USER_ID, NAMA_USER, TERBACA, 
		   TERBALAS, TERDISPOSISI_KELUAR, TERPARAF, 
		   ISI, TANGGAL_BATAS, TERTANDA_TANGANI, NAMA_SATKER, NAMA_SATKER_ASAL
		FROM DISPOSISI_KELUAR A
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
	
	
	function selectByParamsAttachment($paramsArray=array(),$limit=-1,$from=-1,$stat='', $sOrder=" ORDER BY DISPOSISI_KELUAR_ATTACHMENT_ID ASC ")
	{
		$str = "SELECT DISPOSISI_KELUAR_ATTACHMENT_ID, DISPOSISI_KELUAR_ID, ATTACHMENT, CATATAN, 
					   UKURAN, TIPE, NAMA, LAST_CREATE_USER, LAST_CREATE_DATE, 
					   LAST_UPDATE_USER, LAST_UPDATE_DATE
				  FROM DISPOSISI_KELUAR_ATTACHMENT A
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
	
	
	
    function getJson($paramsArray=array(),$statement="")
	{
		$str = "
			SELECT ROW_TO_JSON(A) JSON FROM 
			(SELECT SURAT_KELUAR_ID, DISPOSISI_KELUAR_PARENT_ID, STATUS_DISPOSISI_KELUAR, SATUAN_KERJA_ID_TUJUAN SATUAN_KERJA_ID, NAMA_SATKER SATUAN_KERJA, NAMA_SATKER JABATAN, NAMA_USER NAMA_PEGAWAI FROM DISPOSISI_KELUAR) A
			WHERE 1 = 1
		"; 
		while(list($key,$val)=each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement;
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
		$str = "SELECT COUNT(surat_masuk_id) AS ROWCOUNT FROM DISPOSISI_KELUAR WHERE 1=1 "; 
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