<? 

  /***
  * Entity-base class untuk mengimplementasikan tabel kategori.
  * 
  ***/
  include_once(APPPATH.'/models/Entity.php');

  class Disposisi extends Entity{ 

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
		$this->setField("DISPOSISI_ID", $this->getNextId("DISPOSISI_ID","DISPOSISI"));
		$this->tanggal = date("Y-m-d");
		$this->NowYear = date("Y");
		$str = "
		INSERT INTO DISPOSISI(DISPOSISI_ID, DISPOSISI_PARENT_ID, SURAT_MASUK_ID, 
			TAHUN, SATUAN_KERJA_ID_ASAL, SATUAN_KERJA_ID_TUJUAN, TANGGAL_DISPOSISI, 
			ISI, KETERANGAN, STATUS_DISPOSISI, LAST_CREATE_USER, LAST_CREATE_DATE, SIFAT_NAMA)
        VALUES ('".$this->getField("DISPOSISI_ID")."',
                '".(int)$this->getField("DISPOSISI_PARENT_ID")."',
                '".$this->getField("SURAT_MASUK_ID")."',
                ".$this->NowYear.",
                '".$this->getField("SATUAN_KERJA_ID_ASAL")."',
                '".$this->getField("SATUAN_KERJA_ID_TUJUAN")."',
                CURRENT_TIMESTAMP,
                '".$this->getField("ISI")."',
                '".$this->getField("KETERANGAN")."',
                '".$this->getField("STATUS_DISPOSISI")."',
				'".$this->getField("LAST_CREATE_USER")."',
				CURRENT_DATE,
				'".$this->getField("SIFAT_NAMA")."'
			)
		";
				
		// echo $str; exit;
		$this->query = $str;
		$this->id = $this->getField("DISPOSISI_ID");
		return $this->execQuery($str);
    }

    function insertbalas()
	{
		$str = "
		UPDATE DISPOSISI
		SET POSISI_TANGGAPAN= NULL
		WHERE DISPOSISI_ID= '".$this->getField("DISPOSISI_PARENT_ID")."'
		"; 
		$this->query = $str;
		$this->execQuery($str);

		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("DISPOSISI_ID", $this->getNextId("DISPOSISI_ID","DISPOSISI"));
		$this->tanggal = date("Y-m-d");
		$this->NowYear = date("Y");
		$str = "
		INSERT INTO DISPOSISI(DISPOSISI_ID, DISPOSISI_PARENT_ID, SURAT_MASUK_ID, 
			TAHUN, SATUAN_KERJA_ID_ASAL, SATUAN_KERJA_ID_TUJUAN, TANGGAL_DISPOSISI, 
			ISI, KETERANGAN, STATUS_DISPOSISI, LAST_CREATE_USER, LAST_CREATE_DATE, SIFAT_NAMA, POSISI_TANGGAPAN)
        VALUES ('".$this->getField("DISPOSISI_ID")."',
                '".(int)$this->getField("DISPOSISI_PARENT_ID")."',
                '".$this->getField("SURAT_MASUK_ID")."',
                ".$this->NowYear.",
                '".$this->getField("SATUAN_KERJA_ID_ASAL")."',
                '".$this->getField("SATUAN_KERJA_ID_TUJUAN")."',
                CURRENT_TIMESTAMP,
                '".$this->getField("ISI")."',
                '".$this->getField("KETERANGAN")."',
                '".$this->getField("STATUS_DISPOSISI")."',
				'".$this->getField("LAST_CREATE_USER")."',
				CURRENT_DATE,
				'".$this->getField("SIFAT_NAMA")."'
				, 1
			)
		";
				
		// echo $str; exit;
		$this->query = $str;
		$this->id = $this->getField("DISPOSISI_ID");
		return $this->execQuery($str);
    }
	
	function insertAttachment()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("DISPOSISI_ATTACHMENT_ID", $this->getNextId("DISPOSISI_ATTACHMENT_ID","DISPOSISI_ATTACHMENT")); 
		$str = "
			INSERT INTO DISPOSISI_ATTACHMENT(
						DISPOSISI_ATTACHMENT_ID, SURAT_MASUK_ID, DISPOSISI_ID, ATTACHMENT, 
						UKURAN, TIPE, NAMA, LAST_CREATE_USER, LAST_CREATE_DATE
            )
            VALUES ('".$this->getField("DISPOSISI_ATTACHMENT_ID")."',
					'".$this->getField("SURAT_MASUK_ID")."',
					'".$this->getField("DISPOSISI_ID")."',
					'".$this->getField("ATTACHMENT")."',
                    ".(int)$this->getField("UKURAN").",
                    '".$this->getField("TIPE")."',
                    '".$this->getField("NAMA")."',
                    '".$this->getField("LAST_CREATE_USER")."',
				  	CURRENT_DATE
				)";
				
		$this->query = $str;
		$this->id = $this->getField("DISPOSISI_ATTACHMENT_ID");
		return $this->execQuery($str);			
	}
	
    function updateByField()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "UPDATE DISPOSISI A SET
				  ".$this->getField("FIELD")." 		= '".$this->getField("FIELD_VALUE")."',
				  LAST_UPDATE_USER		 	= '".$this->getField("LAST_UPDATE_USER")."',
				  LAST_UPDATE_DATE			= CURRENT_DATE
				WHERE DISPOSISI_ID = '".$this->getField("DISPOSISI_ID")."'
				"; 
				$this->query = $str;
		return $this->execQuery($str);
    }

    function updatestatusterbaca()
	{
		$str = "
		UPDATE DISPOSISI 
		SET
		TERBACA= 1
		WHERE DISPOSISI_ID = '".$this->getField("DISPOSISI_ID")."'
		"; 
		$this->query = $str;
		return $this->execQuery($str);
    }

    function updateterbaca()
	{
		$str = "
		UPDATE DISPOSISI 
		SET
		TERBACA_INFO= '".$this->getField("TERBACA_INFO")."'
		WHERE DISPOSISI_ID = '".$this->getField("DISPOSISI_ID")."'
		"; 
		$this->query = $str;
		return $this->execQuery($str);
    }

    function updateByFieldValidasiSatker()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "UPDATE DISPOSISI A SET
				  ".$this->getField("FIELD")." 		= '".$this->getField("FIELD_VALUE")."',
				  LAST_UPDATE_USER		 	= '".$this->getField("LAST_UPDATE_USER")."',
				  LAST_UPDATE_DATE			= CURRENT_DATE
				WHERE SURAT_MASUK_ID = '".$this->getField("SURAT_MASUK_ID")."' AND
					  SATUAN_KERJA_ID_TUJUAN		 = '".$this->getField("SATUAN_KERJA_ID_TUJUAN")."'
				"; 
				$this->query = $str;
		return $this->execQuery($str);
    }	
	
    function updateByFieldValidasiUser()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "UPDATE DISPOSISI A SET
				  ".$this->getField("FIELD")." 		= '".$this->getField("FIELD_VALUE")."',
				  LAST_UPDATE_USER		 	= '".$this->getField("LAST_UPDATE_USER")."',
				  LAST_UPDATE_DATE			= CURRENT_DATE
				WHERE SURAT_MASUK_ID = '".$this->getField("SURAT_MASUK_ID")."' AND
					  USER_ID		 = '".$this->getField("USER_ID")."'
				"; 
				$this->query = $str;
		return $this->execQuery($str);
    }

    function updateByFieldValidasiUserBaca()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "UPDATE DISPOSISI A SET
				  ".$this->getField("FIELD")." 		= '".$this->getField("FIELD_VALUE")."',
				  LAST_UPDATE_USER		 	= '".$this->getField("LAST_UPDATE_USER")."',
				  LAST_UPDATE_DATE			= CURRENT_DATE
				WHERE SURAT_MASUK_ID = '".$this->getField("SURAT_MASUK_ID")."'
					AND DISPOSISI_ID		 = '".$this->getField("DISPOSISI_ID")."'
					AND USER_ID		 = '".$this->getField("USER_ID")."'
				"; 
				$this->query = $str;
		return $this->execQuery($str);
    }		
	
	function delete()
	{		
		$str1 = "
		 		DELETE FROM DISPOSISI
                WHERE 
                  DISPOSISI_ID = '".$this->getField("DISPOSISI_ID")."'";
				  
		$this->query = $str1;
        return $this->execQuery($str1);
    }

    function deletestatusdisposisi()
	{		
		$str1 = "
		DELETE FROM DISPOSISI
		WHERE 
		SURAT_MASUK_ID = '".$this->getField("SURAT_MASUK_ID")."'
		AND STATUS_DISPOSISI = '".$this->getField("STATUS_DISPOSISI")."'
		";
				  
		$this->query = $str1;
        return $this->execQuery($str1);
    }
	
	function deleteParent()
	{		
		$str1 = "
		 		DELETE FROM DISPOSISI
                WHERE 
                  SURAT_MASUK_ID = '".$this->getField("SURAT_MASUK_ID")."' ";
				  
		$this->query = $str1;
        return $this->execQuery($str1);
    }
	
	function deleteModif()
	{	
		$str= "
		 		UPDATE DISPOSISI SET TERDISPOSISI = NULL
                WHERE 
                  DISPOSISI_ID = '".$this->getField("DISPOSISI_PARENT_ID")."'";
				  
		$this->query = $str;
        $this->execQuery($str);
			
		$str1 = "
		 		DELETE FROM DISPOSISI
                WHERE 
                  DISPOSISI_ID = '".$this->getField("DISPOSISI_ID")."'";
				  
		$this->query = $str1;
        return $this->execQuery($str1);
    }
	
	function updateIsiDisposisi()
	{
        $str = "UPDATE disposisi SET 
				isi = '".$this->getField("ISI")."'
                WHERE disposisi_id = '".$this->getField("disposisi_id")."'
				";
				  
		$this->query = $str;
        return $this->execQuery($str);
    }
	
	function updateDisposisi()
	{
        $str = "UPDATE DISPOSISI SET TERDISPOSISI = 1
                WHERE 
				  SURAT_MASUK_ID = '".$this->getField("surat_masuk_id")."' AND
                  SATUAN_KERJA_ID_TUJUAN = '".$this->getField("SATUAN_KERJA_ID_TUJUAN")."'";
				  
		$this->query = $str;
        return $this->execQuery($str);
    }
	
	function updateDisposisiAwal()
	{
        $str = "UPDATE SURAT_MASUK_TUJUAN SET TERDISPOSISI = 1
                WHERE 
                  SURAT_MASUK_ID = '".$this->getField("SURAT_MASUK_ID")."' AND
                  SATUAN_KERJA_ID_TUJUAN = '".$this->getField("SATUAN_KERJA_ID_TUJUAN")."'";
				  
		$this->query = $str;
        return $this->execQuery($str);
    }
	
					
					
	function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order=" ORDER BY CASE 
																						WHEN A.STATUS_DISPOSISI = 'TUJUAN' THEN 1 
																						WHEN A.STATUS_DISPOSISI = 'DISPOSISI' THEN 2
																						WHEN A.STATUS_DISPOSISI = 'TEMBUSAN' THEN 3
																						WHEN A.STATUS_DISPOSISI = 'DISPOSISI_TEMBUSAN' THEN 4 
																						ELSE 5 END ASC ")
	{
		$str = "
		SELECT 
			DISPOSISI_ID, DISPOSISI_PARENT_ID, DISPOSISI_KELOMPOK_ID, SURAT_MASUK_ID, TAHUN, STATUS_DISPOSISI,
			SATUAN_KERJA_ID_ASAL, SATUAN_KERJA_ID_TUJUAN, TO_CHAR(TANGGAL_DISPOSISI, 'DD-MM-YYYY HH24:MI') TANGGAL_DISPOSISI, 
			USER_ID, NAMA_USER, TERBACA, NAMA_USER_ASAL,
			TERBALAS, TERDISPOSISI, TERPARAF, TERUSKAN,
			ISI, KETERANGAN, TANGGAL_BATAS, TERTANDA_TANGANI, NAMA_SATKER, NAMA_SATKER_ASAL
			, TO_CHAR(TANGGAL_DISPOSISI, 'YYYY-MM-DD HH24:MI') INFO_TANGGAL_DISPOSISI
			, 
			CASE STATUS_DISPOSISI 
			WHEN 'DISPOSISI' THEN 'Disposisi' 
			WHEN 'BALASAN' THEN 'Tanggapan' 
			ELSE '-' END INFO_STATUS_DISPOSISI
			, A.TERBACA_INFO, A.STATUS_BANTU, A.NIP_MUTASI
			, SF.PEJABAT_REHAT_SEKARANG_NIP, SF.PEJABAT_REHAT_CHECK
		FROM DISPOSISI A
		LEFT JOIN
		(
			SELECT 
			CASE WHEN COALESCE(NULLIF(NIP, ''), 'X') = 'X' THEN NIP_OBSERVER ELSE NIP END PEJABAT_REHAT_SEKARANG_NIP
			, CHECK_ADA_PEJABAT PEJABAT_REHAT_CHECK
			, SATUAN_KERJA_ID PEJABAT_REHAT_SATUAN_KERJA
			FROM SATUAN_KERJA_FIX
		) SF ON SF.PEJABAT_REHAT_SATUAN_KERJA = A.SATUAN_KERJA_ID_TUJUAN
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
	
	function selectByParamsPara($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order="")
	{
		$str = "
		SELECT 
		A.DISPOSISI_ID, A.DISPOSISI_PARENT_ID, A.SURAT_MASUK_ID, A.TAHUN, A.STATUS_DISPOSISI,
		A.SATUAN_KERJA_ID_ASAL, A.SATUAN_KERJA_ID_TUJUAN, TO_CHAR(A.TANGGAL_DISPOSISI, 'DD-MM-YYYY HH24:MI') TANGGAL_DISPOSISI, 
		A.USER_ID, A.NAMA_USER, A.TERBACA, A.NAMA_USER_ASAL,
		A.TERBALAS, A.TERDISPOSISI, A.TERPARAF, A.TERUSKAN,
		A.ISI, A.KETERANGAN, A.TANGGAL_BATAS, A.TERTANDA_TANGANI, A.NAMA_SATKER, A.NAMA_SATKER_ASAL
		, TO_CHAR(A.TANGGAL_DISPOSISI, 'YYYY-MM-DD HH24:MI') INFO_TANGGAL_DISPOSISI
		, 
		CASE A.STATUS_DISPOSISI 
		WHEN 'DISPOSISI' THEN 'Disposisi' 
		WHEN 'BALASAN' THEN 'Tanggapan' 
		ELSE '-' END INFO_STATUS_DISPOSISI
		, A.TERBACA_INFO, XXX.PEGAWAI_ID
		FROM DISPOSISI A
		INNER JOIN SURAT_MASUK Y ON A.SURAT_MASUK_ID = Y.SURAT_MASUK_ID
		INNER JOIN 
		(
			SELECT 
			A_1.SURAT_MASUK_ID, XXX_1.PEGAWAI_ID
			FROM DISPOSISI_KELOMPOK A_1
			JOIN SATUAN_KERJA_KELOMPOK_GROUP B ON A_1.SATUAN_KERJA_KELOMPOK_ID = B.SATUAN_KERJA_KELOMPOK_ID
			JOIN 
			( 
				SELECT XXX_2.PEGAWAI_ID,
				XXX_2.KELOMPOK_JABATAN
				FROM 
				(
					SELECT NIP PEGAWAI_ID, KELOMPOK_JABATAN
					FROM SATUAN_KERJA
					UNION ALL
					SELECT 
					PEGAWAI_ID, CASE UPPER(USER_GROUP_ID) WHEN UPPER('PEGAWAI') THEN UPPER('KARYAWAN')
					ELSE USER_GROUP_ID END KELOMPOK_JABATAN
					FROM USER_LOGIN_CABANG
				) XXX_2
				GROUP BY XXX_2.PEGAWAI_ID, XXX_2.KELOMPOK_JABATAN
			) XXX_1 ON B.KELOMPOK_JABATAN = XXX_1.KELOMPOK_JABATAN
		) XXX ON A.SURAT_MASUK_ID = XXX.SURAT_MASUK_ID
		WHERE 1=1
		"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= " ".$statement.$order;
		$this->query = $str;
		// echo $str;exit();
	
		return $this->selectLimit($str,$limit,$from); 
    }

	function selectByParamsAttachment($paramsArray=array(),$limit=-1,$from=-1,$stat='', $sOrder=" ORDER BY DISPOSISI_ATTACHMENT_ID ASC ")
	{
		$str = "SELECT DISPOSISI_ATTACHMENT_ID, DISPOSISI_ID, ATTACHMENT, CATATAN, 
					   UKURAN, TIPE, NAMA, LAST_CREATE_USER, LAST_CREATE_DATE, 
					   LAST_UPDATE_USER, LAST_UPDATE_DATE
				  FROM DISPOSISI_ATTACHMENT A
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
	
	
	
    function getKode($paramsArray=array(),$statement="")
	{
		$str = "
			SELECT SURAT_MASUK_ID, SATUAN_KERJA_ID_TUJUAN SATUAN_KERJA_ID FROM DISPOSISI A
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
			(SELECT SURAT_MASUK_ID, DISPOSISI_ID, DISPOSISI_PARENT_ID, STATUS_DISPOSISI, SATUAN_KERJA_ID_TUJUAN SATUAN_KERJA_ID, NAMA_SATKER SATUAN_KERJA, NAMA_SATKER JABATAN, NAMA_USER NAMA_PEGAWAI, B.NAMA CABANG 
			FROM DISPOSISI A
			INNER JOIN SATUAN_KERJA B ON A.CABANG_ID_TUJUAN=B.SATUAN_KERJA_ID
			WHERE DISPOSISI_KELOMPOK_ID = 0) A
			WHERE 1 = 1
		"; 
		while(list($key,$val)=each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement;
		$this->query = $str;
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

    function getreplyjson($paramsArray=array(),$statement="")
	{
		$str = "
			SELECT ROW_TO_JSON(A) JSON 
			FROM 
			(
				SELECT
					SURAT_MASUK_ID, STATUS_DISPOSISI, SATUAN_KERJA_ID_ASAL SATUAN_KERJA_ID, NAMA_SATKER_ASAL SATUAN_KERJA
				FROM DISPOSISI A
				GROUP BY SURAT_MASUK_ID, STATUS_DISPOSISI, SATUAN_KERJA_ID_ASAL, NAMA_SATKER_ASAL
			) A
			WHERE 1 = 1
		"; 
		// WHERE DISPOSISI_KELOMPOK_ID = 0
		
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
		$str = "SELECT COUNT(surat_masuk_id) AS ROWCOUNT FROM disposisi WHERE 1=1 "; 
		while(list($key,$val)=each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement;

		// echo $str;exit;
		$this->query = $str;
		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0; 
    }
	
	
	function getPesan($paramsArray=array(),$statement="")
	{
		$str = "SELECT ISI FROM DISPOSISI A WHERE 1=1 "; 
		while(list($key,$val)=each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement;
		$this->query = $str;
		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("ISI"); 
		else 
			return ""; 
    }
	
	
	
  } 
?>