<? 

  /***
  * Entity-base class untuk mengimplementasikan tabel kategori.
  * 
  ***/
  include_once(APPPATH.'/models/Entity.php');

  class SuratKeluar extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function SuratKeluar()
	{
      $this->Entity(); 
    }
	
	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("surat_keluar_id", $this->getNextId("SURAT_KELUAR_ID","SURAT_KELUAR")); 
		//$this->setField("no_agenda", $this->getNextId("no_agenda","surat_masuk", " satuan_kerja_id_asal = '".$this->getField("satuan_kerja_id_asal")."'")); 
		$this->tanggal = date("Y-m-d");
		$this->NowYear = date("Y");
	
		$str = "
		INSERT INTO SURAT_KELUAR (
					   SURAT_KELUAR_ID, NO_AGENDA, SURAT_MASUK_ID, 
					   TAHUN, NOMOR, TANGGAL, 
					   JENIS, KEPADA, KLASIFIKASI_ID, 
					   PERIHAL, TAHUN_SURAT_MASUK, INSTANSI_TUJUAN, 
					   ALAMAT_TUJUAN, KOTA_TUJUAN, KETERANGAN_TUJUAN, 
					   ISI, JUMLAH_LAMPIRAN, CATATAN, 
					   SATUAN_KERJA_ID_ASAL, SATUAN_KERJA_ID_PEMBUAT, SATUAN_KERJA_ID_POSISI, 
					   SATUAN_KERJA_ID_AKHIR, IS_NEW, IS_FINAL, 
					   IS_ACC, IS_REV, TANGGAL_AKSI, USER_ID, 
					   NAMA_USER, TANGGAL_ENTRI, ID_TTD) 
            VALUES (
					'".$this->getField("surat_keluar_id")."', '".$this->getField("no_agenda")."', ".$this->getField("surat_masuk_id").",
					".$this->NowYear.", '".$this->getField("nomor")."', ".$this->getField("tanggal").",
                    '".$this->getField("jenis")."',	'".$this->getField("kepada")."', '".$this->getField("klasifikasi_id")."',
                    '".$this->getField("perihal")."', '".$this->getField("tahun_surat_masuk")."', '".$this->getField("instansi_tujuan")."',
                    '".$this->getField("alamat_tujuan")."', '".$this->getField("kota_tujuan")."', '".$this->getField("keterangan_tujuan")."',
					'".$this->getField("isi")."', '".$this->getField("jumlah_lampiran")."', '".$this->getField("catatan")."',
                    '".$this->getField("satuan_kerja_id_asal")."', '".$this->getField("satuan_kerja_id_pembuat")."', '".$this->getField("satuan_kerja_id_posisi")."',
                    '".$this->getField("user_id")."', 1, 0,
					0, 0, '".$this->tanggal."', '".$this->getField("user_id")."',
					'".$this->getField("nama_user")."', '".$this->tanggal."', '".$this->getField("id_ttd")."'
				)";
				
		$this->query = $str;
		$this->reqSuratKeluarId = $this->getField("surat_keluar_id");
		//echo $str;
		return $this->execQuery($str);
    }
	
	function updateStatusBalasDisposisi(){
		$str = "
		UPDATE DISPOSISI SET TERBALAS = '1'
            WHERE SURAT_MASUK_ID = ".$this->getField("surat_masuk_id")." AND SATUAN_KERJA_ID_TUJUAN = '".$this->getField("satuan_kerja_id_tujuan")."' ";
		$this->query = $str;
		//echo $str;
        return $this->execQuery($str);
	}
	
	function update_dyna()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "UPDATE SURAT_KELUAR A SET
				  ".$this->getField("FIELD")." = '".$this->getField("FIELD_VALUE")."'
				WHERE SURAT_KELUAR_ID = ".$this->getField("surat_keluar_id")."
				"; 
				$this->query = $str;
		//echo $str;
		return $this->execQuery($str);
    }
	
	function update($case='')
	{
		$this->tanggal = "TO_DATE('".date("Y-m-d")."', 'YYYY-MM-DD')";
		$this->sysDate = date("Y-m-d H:m:s");
		$this->NowYear = date("Y");
		//Auto-generate primary key(s) by next max value (integer)
		$str = "
		UPDATE SURAT_KELUAR SET
		";//perevisi = ".$this->getField("perevisi").",
			if($case == 1){
			$str .= "
			   TGL_REVISI = '".$this->tanggal."',
			   REVISI = '".$this->getField("revisi")."',
			";}
			
			$str .= "
			   NO_AGENDA = '".$this->getField("no_agenda")."',
			   NOMOR = '".$this->getField("nomor")."',
			   tanggal = ".$this->getField("tanggal").",
			   JENIS = '".$this->getField("jenis")."',
			   KEPADA = '".$this->getField("kepada")."',
			   PERIHAL = '".$this->getField("perihal")."',
			   KLASIFIKASI_ID = '".$this->getField("klasifikasi_id")."',
			   INSTANSI_TUJUAN = '".$this->getField("instansi_tujuan")."',
			   ALAMAT_TUJUAN = '".$this->getField("alamat_tujuan")."',
			   KOTA_TUJUAN = '".$this->getField("kota_tujuan")."',
			   KETERANGAN_TUJUAN = '".$this->getField("keterangan_tujuan")."',
			   ISI = '".$this->getField("isi")."',
			   CATATAN = '".$this->getField("catatan")."',
			   tanggal_update = ".$this->tanggal.",
			   ID_TTD = '".$this->getField("id_ttd")."'
		   WHERE SURAT_KELUAR_ID = '".$this->getField("surat_keluar_id")."'
				"; 
				$this->query = $str;
				$this->SuratKeluarId = $this->getField("surat_keluar_id");
		//echo $str;
		//nama_user = '".$this->getField("nama_user")."',
		return $this->execQuery($str);
    }
		
	function delete()
	{	
		$str2 = "
		 		DELETE FROM surat_masuk_attachment
                WHERE 
                  surat_masuk_id = '".$this->getField("surat_masuk_id")."'";
				  
		$this->query = $str2;
        $this->execQuery($str2);
			
		$str = "
		 		DELETE FROM disposisi
                WHERE 
                  surat_masuk_id = '".$this->getField("surat_masuk_id")."'";
				  
		$this->query = $str;
        $this->execQuery($str);
		
		$str1 = "
		 		DELETE FROM surat_masuk
                WHERE 
                  surat_masuk_id = '".$this->getField("surat_masuk_id")."'";
				  
		$this->query = $str1;
        return $this->execQuery($str1);
    }
	
	function selectByParams($paramsArray=array(),$limit=-1,$from=-1)
	{
		$str = "
                SELECT 
					A.SATUAN_KERJA_ID_AKHIR,
					A.TEMPLATE, (SELECT NAMA FROM SATUAN_KERJA S WHERE S.SATUAN_KERJA_ID=A.SATUAN_KERJA_ID_PEMBUAT) SATUAN_NAMA,
					A.KETERANGAN_TUJUAN, A.KOTA_TUJUAN, A.ALAMAT_TUJUAN, A.ISI, A.CATATAN, A.ISI,
					(SELECT NAMA FROM KLASIFIKASI X WHERE X.KLASIFIKASI_ID=A.KLASIFIKASI_ID) KLASIFIKASI_NAMA,
				   A.KLASIFIKASI_ID, A.ID_TTD, (SELECT (KODE || ' - ' || (SELECT nama FROM SATUAN_KERJA s WHERE kt.SATUAN_KERJA_ID = s.SATUAN_KERJA_ID)) FROM TANDA_TANGAN kt WHERE A.ID_TTD = kt.TANDA_TANGAN_ID) NAMA_TTD,
                    A.CATATAN, A.TAHUN,
                   A.NOMOR, A.SURAT_KELUAR_ID, A.SURAT_MASUK_ID, B.NO_AGENDA NO_REFERENSI_SURAT, A.NO_AGENDA, A.TANGGAL,  
                   A.JENIS, A.KEPADA, A.PERIHAL, INSTANSI_TUJUAN, A.JUMLAH_LAMPIRAN, A.SATUAN_KERJA_ID_ASAL,                    
                   CASE WHEN IS_NEW = 1 THEN 'Baru' WHEN IS_FINAL = 1 THEN 'Finalisasi' WHEN IS_ACC = 1 THEN 'Disetujui' WHEN IS_REV = 1 THEN 'Revisi' END 
                FROM SURAT_KELUAR A LEFT JOIN SURAT_MASUK B ON A.SURAT_MASUK_ID = B.SURAT_MASUK_ID 
                WHERE 1 = 1  		    
			   "; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= " ";
		$this->query = $str;
	
		return $this->selectLimit($str,$limit,$from); 
    }
	
	function selectByParamsFinalisasi($paramsArray=array(),$limit=-1,$from=-1, $statement="", $id="", $order="", $userlevel='')
	{
		$str = "
                SELECT 
                   A.SURAT_KELUAR_ID, A.SURAT_MASUK_ID, B.NO_AGENDA NO_REFERENSI_SURAT, A.NO_AGENDA, A.TANGGAL,  
                   A.JENIS, A.KEPADA, A.PERIHAL, A.INSTANSI_TUJUAN, A.JUMLAH_LAMPIRAN, A.SATUAN_KERJA_ID_PEMBUAT,                    
                   CASE WHEN IS_NEW = 1 THEN 'Baru' WHEN IS_FINAL = 1 THEN 'Finalisasi' WHEN IS_ACC = 1 THEN 'Disetujui' WHEN IS_REV = 1 THEN 'Revisi' END STATUS,
				   CASE WHEN STATUS_KIRIM = 1 THEN 'Sudah di kirim' ELSE 'Belum di kirim' END STATUS_KIRIM
                FROM SURAT_KELUAR A LEFT JOIN SURAT_MASUK B ON A.SURAT_MASUK_ID = B.SURAT_MASUK_ID 
                WHERE 1 = 1  
				";
			  
		$str .= " AND (
						A.SATUAN_KERJA_ID_PEMBUAT = ".$id." OR 
						EXISTS(SELECT 1 FROM DISPOSISI X WHERE X.SURAT_MASUK_ID = A.SURAT_MASUK_ID AND X.SATUAN_KERJA_ID_ASAL = ".$id.")
						OR EXISTS ( SELECT 1 FROM SURAT_KELUAR X WHERE X.SURAT_MASUK_ID IS NULL AND 2 = ".$userlevel." )
					  ) ";   
		
		$str .= $statement; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= " ".$order;
		$this->query = $str;
		
	
		
		return $this->selectLimit($str,$limit,$from); 
    }	
	
	function selectByParamsRevisi($paramsArray=array(),$limit=-1,$from=-1, $statement="", $id="", $order="")
	{
		$str = "
                SELECT 
				   (SELECT C.NAMA FROM SATUAN_KERJA C WHERE C.SATUAN_KERJA_ID = A.SATUAN_KERJA_ID_POSISI) POSISI,
				   (SELECT C.SATUAN_KERJA_ID_TUJUAN FROM DISPOSISI C WHERE C.SURAT_MASUK_ID = A.SURAT_MASUK_ID AND C.SATUAN_KERJA_ID_TUJUAN = ".$id.") ID_POSISI,
                   A.SURAT_KELUAR_ID, A.SURAT_MASUK_ID, B.NO_AGENDA NO_REFERENSI_SURAT, A.NO_AGENDA, A.TANGGAL,  
                   A.JENIS, A.KEPADA, A.PERIHAL, A.INSTANSI_TUJUAN, A.JUMLAH_LAMPIRAN, A.SATUAN_KERJA_ID_PEMBUAT,                    
                   CASE WHEN IS_NEW = 1 THEN 'Baru' WHEN IS_FINAL = 1 THEN 'Finalisasi' WHEN IS_ACC = 1 THEN 'Disetujui' WHEN IS_REV = 1 THEN 'Revisi' END STATUS
                FROM SURAT_KELUAR A LEFT JOIN SURAT_MASUK B ON A.SURAT_MASUK_ID = B.SURAT_MASUK_ID 
                WHERE 1 = 1  AND A.SATUAN_KERJA_ID_POSISI = ".$id."    
			   ".$statement; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= " ".$order;
		$this->query = $str;
	
		return $this->selectLimit($str,$limit,$from); 
    }		
	
	function selectByParamsEntri($paramsArray=array(),$limit=-1,$from=-1, $statement="", $id="", $order="")
	{
		$str = "
                SELECT 
                   A.SURAT_MASUK_ID, A.SURAT_KELUAR_ID, A.SATUAN_KERJA_ID_POSISI, B.NO_AGENDA NO_REFERENSI_SURAT, A.NO_AGENDA, A.TANGGAL,  
                   A.JENIS, A.KEPADA, A.PERIHAL, INSTANSI_TUJUAN, A.JUMLAH_LAMPIRAN, C.NAMA POSISI,                    
                   CASE WHEN IS_NEW = 1 THEN 'Baru' WHEN IS_FINAL = 1 THEN 'Finalisasi' WHEN IS_ACC = 1 THEN 'Disetujui' WHEN IS_REV = 1 THEN 'Revisi' END STATUS
                FROM SURAT_KELUAR A LEFT JOIN SURAT_MASUK B ON A.SURAT_MASUK_ID = B.SURAT_MASUK_ID 
                LEFT JOIN SATUAN_KERJA C ON C.SATUAN_KERJA_ID = A.SATUAN_KERJA_ID_POSISI
                WHERE 1 = 1  AND A.SATUAN_KERJA_ID_PEMBUAT = ".$id."			
			   ".$statement; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= " ".$order;
		$this->query = $str;
	
		return $this->selectLimit($str,$limit,$from); 
    }	
	
	function selectByParamsCapaianEntri($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order="")
	{
		$str = "
				 SELECT DISTINCT A.SURAT_BPPNFI_ID, A.NO_AGENDA, A.NOMOR, TANGGAL
                  FROM SURAT_BPPNFI A 
                  WHERE 1 = 1
			   ".$statement; 
			 	
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= " ".$order;
		$this->query = $str;
		//echo $str; 
	
		return $this->selectLimit($str,$limit,$from); 
    }	
	
	function selectByParamsCapaianKinerja($paramsArray=array(),$limit=-1,$from=-1,$statement='', $json="")
	{
		$str = "
				SELECT A.NAMA, B.JUMLAH_DATA, A.USER_ID
				FROM USERS A
				INNER JOIN
				(
					SELECT A.USER_ID, COUNT(1) JUMLAH_DATA 
					FROM SURAT_BPPNFI A
					WHERE 1=1 ".$statement."
					GROUP BY A.USER_ID
				) B ON A.USER_ID = B.USER_ID 
				WHERE 1=1
			   ".$json; 
		
		//AND TO_CHAR(TANGGAL_DITERUSKAN, 'MMYYYY') = '082014'  AND TO_CHAR(TANGGAL_DITERUSKAN, 'MMYYYY') BETWEEN '072014' AND '082014'
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= " ".$stat;
		$this->query = $str;
	
		return $this->selectLimit($str,$limit,$from); 
    }
	
	function getCountByParamsCapaianKinerja($paramsArray=array(), $statement='', $json="")
	{
		$str = "
				SELECT COUNT(*) ROWCOUNT
				FROM USERS A
				INNER JOIN
				(
					SELECT A.USER_ID, COUNT(1) JUMLAH_DATA 
					FROM SURAT_BPPNFI A
					WHERE 1=1 ".$statement."
					GROUP BY A.USER_ID
				) B ON A.USER_ID = B.USER_ID 
				WHERE 1=1
			   ".$json; 
		
		//AND TO_CHAR(TANGGAL_DITERUSKAN, 'MMYYYY') = '082014'  AND TO_CHAR(TANGGAL_DITERUSKAN, 'MMYYYY') BETWEEN '072014' AND '082014'
		while(list($key,$val)=each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		$str .= ' ';
		
		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0; 
    }
	
	function getCountByParamsCapaianEntri($paramsArray=array(), $statement='')
	{
		$str = " SELECT COUNT(*) ROWCOUNT
				 FROM
				 (
				 SELECT A.SURAT_BPPNFI_ID
				  FROM SURAT_BPPNFI A 
				  WHERE 1 = 1 ".$statement.") A
		"; 
		while(list($key,$val)=each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		$str .= ' ';
		
		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0; 
    }
		

    function getCountByParamsEntri($paramsArray=array(), $statement='', $id='')
	{
		$str = " SELECT COUNT(*) ROWCOUNT
				 FROM SURAT_KELUAR A LEFT JOIN SURAT_MASUK B ON A.SURAT_MASUK_ID = B.SURAT_MASUK_ID 
                 WHERE 1 = 1  AND A.SATUAN_KERJA_ID_PEMBUAT = ".$id."    
		"; 
		while(list($key,$val)=each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		$str .= " ".$statement;
		
		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0; 
    }
	
    function getCountByParamsRevisi($paramsArray=array(), $statement='', $id='')
	{
		$str = " SELECT COUNT(*) ROWCOUNT
				 FROM SURAT_KELUAR A LEFT JOIN SURAT_MASUK B ON A.SURAT_MASUK_ID = B.SURAT_MASUK_ID 
                 WHERE 1 = 1  AND A.SATUAN_KERJA_ID_POSISI = ".$id."    
		"; 
		while(list($key,$val)=each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		$str .= " ".$statement;
		
		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0; 
    }
    
    function getCountByParamsFinalisasi($paramsArray=array(), $statement='', $id='')
	{
		$str = " SELECT COUNT(*) ROWCOUNT
				 FROM SURAT_KELUAR A LEFT JOIN SURAT_MASUK B ON A.SURAT_MASUK_ID = B.SURAT_MASUK_ID 
                 WHERE 1 = 1  AND (A.SATUAN_KERJA_ID_PEMBUAT = ".$id." OR EXISTS(SELECT 1 FROM DISPOSISI X WHERE X.SURAT_MASUK_ID = A.SURAT_MASUK_ID AND X.SATUAN_KERJA_ID_ASAL = ".$id."))   
		"; 
		while(list($key,$val)=each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		$str .= " ".$statement;
		
		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0; 
    }
	
	function getCountByParamsSimple($paramsArray=array(), $varStatement="")
	{
		$str = "SELECT MAX(NO_AGENDA) AS ROWCOUNT FROM SURAT_KELUAR WHERE SURAT_KELUAR_ID IS NOT NULL "; 
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
	
	function getCountByParamsNomor($paramsArray=array(), $varStatement="")
	{
		$str = "SELECT COUNT(NOMOR) AS ROWCOUNT FROM SURAT_KELUAR WHERE SURAT_KELUAR_ID IS NOT NULL "; 
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