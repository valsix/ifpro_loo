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
		$this->setField("disposisi_id", $this->getNextId("DISPOSISI_ID","DISPOSISI"));
		$this->tanggal = date("Y-m-d");
		$this->NowYear = date("Y");
		//".$this->getField("tanggal_disposisi").",
		$str = "
		INSERT INTO DISPOSISI(DISPOSISI_ID, SURAT_MASUK_ID, TAHUN, SATUAN_KERJA_ID_ASAL, SATUAN_KERJA_ID_TUJUAN, TANGGAL_DISPOSISI, USER_ID,
                                NAMA_USER, ISI, TANGGAL_BATAS, TREE_ID, TREE_PARENT_ID, STATUS_KEMBALI, LAST_CREATE_USER, LAST_CREATE_DATE)
                    VALUES ('".$this->getField("disposisi_id")."',
                            '".$this->getField("surat_masuk_id")."',
                            ".$this->NowYear.",
                            '".$this->getField("satuan_kerja_id_asal")."',
                            '".$this->getField("SATUAN_KERJA_ID_TUJUAN")."',
                            TO_DATE(TO_CHAR(NOW(), 'yyyy/mm/dd hh24:mi:ss'), 'yyyy/mm/dd hh24:mi:ss'),
							'".$this->getField("user_id")."',
							'".$this->getField("NAMA_USER")."',
                            '".$this->getField("ISI")."',
                            ".$this->getField("TANGGAL_BATAS").",
							'".$this->getField("TREE_ID")."',
							'".$this->getField("TREE_PARENT_ID")."',
							".$this->getField("STATUS_KEMBALI").",
							'".$this->getField("LAST_CREATE_USER")."',
							CURRENT_DATE
			)
		";
				
		$this->query = $str;
		$this->id = $this->getField("disposisi_id");
		// echo $str; exit;
		return $this->execQuery($str);
    }
		
    /*function update()
	{
		$this->tanggal = date("Y-m-d");
		$this->NowYear = date("Y");
		//Auto-generate primary key(s) by next max value (integer) 		
		$str = "
		UPDATE surat_masuk SET  
			   tahun = ".$this->NowYear.",
			   nomor = '".$this->getField("nomor")."',
			   tanggal = '".$this->getField("tanggal")."',
			   tanggal_diteruskan = '".$this->getField("tanggal_diteruskan")."',
			   TANGGAL_BATAS = '".$this->getField("TANGGAL_BATAS")."',
			   JENIS = '".$this->getField("JENIS")."',
			   JENIS_TUJUAN = '".$this->getField("JENIS_TUJUAN")."',
			   KEPADA = '".$this->getField("KEPADA")."',
			   PERIHAL = '".$this->getField("PERIHAL")."',
			   klasifikasi_id = '".$this->getField("klasifikasi_id")."',
			   instansi_asal = '".$this->getField("instansi_asal")."',
			   alamat_asal = '".$this->getField("alamat_asal")."',
			   kota_asal = '".$this->getField("kota_asal")."',
			   keterangan_asal = '".$this->getField("keterangan_asal")."',
			   SATUAN_KERJA_ID_TUJUAN = '".$this->getField("SATUAN_KERJA_ID_TUJUAN")."',
			   isi = '".$this->getField("ISI")."',
			   catatan = '".$this->getField("catatan")."',
			   tanggal_entri = ".$this->tanggal.",
			   user_id = '".$this->getField("user_id")."',
			   NAMA_USER = '".$this->getField("NAMA_USER")."'
		   WHERE surat_masuk_id = '".$this->getField("surat_masuk_id")."'
				"; 
				$this->query = $str;
				$this->id = $this->getField("klasifikasi_id");
		//echo $str;
		return $this->execQuery($str);
    }
	*/
	
	function delete()
	{		
		$str1 = "
		 		DELETE FROM DISPOSISI
                WHERE 
                  DISPOSISI_ID = '".$this->getField("DISPOSISI_ID")."'";
				  
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
                  SURAT_MASUK_ID = '".$this->getField("surat_masuk_id")."' AND
                  SATUAN_KERJA_ID_TUJUAN = '".$this->getField("SATUAN_KERJA_ID_TUJUAN")."'";
				  
		$this->query = $str;
        return $this->execQuery($str);
    }
	
	function selectBy($paramsArray=array(),$limit=-1,$from=-1)
	{
		$str = "
		SELECT 
		DISPOSISI_ID, SURAT_MASUK_ID, TAHUN, 
		   SATUAN_KERJA_ID_ASAL, SATUAN_KERJA_ID_TUJUAN, TANGGAL_DISPOSISI, 
		   USER_ID, NAMA_USER, TERBACA, 
		   TERBALAS, TERDISPOSISI, TERPARAF, 
		   ISI, TANGGAL_BATAS, TERTANDA_TANGANI
		FROM DISPOSISI
		WHERE 1=1
		"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= " ";
		$this->query = $str;
		//echo $str; 
	
		return $this->selectLimit($str,$limit,$from); 
    }
	
    function selectByParams($paramsArray=array(),$limit=-1,$from=-1)
	{
		$str = "SELECT    
					A.TAHUN , A.NOMOR , A.TANGGAL , A.TANGGAL_DITERUSKAN , A.TANGGAL_BATAS , A.JENIS , A.JENIS_TUJUAN , A.KEPADA , 
					A.PERIHAL , A.KLASIFIKASI_ID , B.NAMA KLASIFIKASI_NAMA, A.INSTANSI_ASAL , A.ALAMAT_ASAL , A.KOTA_ASAL , A.KETERANGAN_ASAL , 
					A.SATUAN_KERJA_ID_TUJUAN , C.NAMA SATUAN_NAMA, A.ISI , D.INSTANSI , A.CATATAN , A.TANGGAL_ENTRI , A.USER_ID , A.NAMA_USER
        		FROM surat_masuk A  LEFT JOIN DAFTAR_ALAMAT D ON A.INSTANSI_ASAL = D.INSTANSI, KLASIFIKASI B, SATUAN_KERJA C
				WHERE A.KLASIFIKASI_ID = B.KLASIFIKASI_ID  AND A.SATUAN_KERJA_ID_TUJUAN = C.SATUAN_KERJA_ID            
			   "; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= " ";
		$this->query = $str;
		//echo $str; 
	
		return $this->selectLimit($str,$limit,$from); 
    }
	
	function selectByParamsDefault($paramsArray=array(),$limit=-1,$from=-1)
	{
		$str = "SELECT
					DISPOSISI_ID, SURAT_MASUK_ID , ISI , USER_ID , TERBACA TERBACA_TUJUAN,
					disposisi_id_generate(TREE_ID, SURAT_MASUK_ID) TREE_ID, 
					CASE WHEN TREE_ID IS NULL OR TREE_ID = '' THEN '0' ELSE TREE_ID END TREE_PARENT_ID
        		FROM DISPOSISI
				WHERE 1=1
			   "; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= " ";
		$this->query = $str;
		//echo $str; 
	
		return $this->selectLimit($str,$limit,$from); 
    }
	
	function selectByParamsReportSuratEntryMasuk($paramsArray=array(),$limit=-1,$from=-1, $statement = '')
	{
		$str = "
				SELECT surat_masuk_id \"No. Agenda\", NAMA_USER \"Instansi Entry\", nomor \"No. Surat Masuk\",
                TO_CHAR(a.tanggal, 'DD-MM-YYYY') \"Tgl Surat\", TO_CHAR(a.tanggal_diteruskan, 'DD-MM-YYYY') \"Tgl Diteruskan\", 
                TO_CHAR(a.TANGGAL_BATAS, 'DD-MM-YYYY') \"Tgl Reminten\",
                terdisposisi \"Disposisi\", terbalas \"Balas\", 
                (SELECT nomor FROM surat_keluar x WHERE a.surat_masuk_id = x.surat_masuk_id AND ROWNUM = 1) \"No. Surat Keluar\",
                instansi_asal \"Instansi Asal\", c.nama \"Instansi Tujuan\", a.klasifikasi_id \"Kode\", b.nama \"Klasifikasi\",
                a.alamat_asal \"Alamat Asal\", a.keterangan_asal \"Ket Asal\", a.jumlah_lampiran \"Jml Lap\", a.catatan \"catatan\", a.PERIHAL \"Perihal\"
                FROM surat_masuk a LEFT JOIN klasifikasi b ON a.klasifikasi_id = b.klasifikasi_id
                LEFT JOIN satuan_kerja c ON a.SATUAN_KERJA_ID_TUJUAN = c.satuan_kerja_id                
                WHERE 1=1
			   "; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ";
		$this->query = $str;
		//echo $str;
		return $this->selectLimit($str,$limit,$from); 
    }
	
	function selectByParamsReportDisposisi($paramsArray=array(),$paramsArray1=array(),$limit=-1,$from=-1, $statement = '', $statement1 = '')
	{
		//(SELECT X.NAMA FROM satuan_kerja X WHERE X.SATUAN_KERJA_ID = A.SATUAN_KERJA_ID_ASAL) ASAL, (SELECT X.NAMA FROM satuan_kerja X WHERE X.SATUAN_KERJA_ID = A.SATUAN_KERJA_ID_TUJUAN) TUJUAN,
		$str = "
		SELECT  ";
		$str .= $statement;
		$str .= "
				A.SATUAN_KERJA_ID_ASAL SATUAN_KERJA_ID_ASAL, AA.SATUAN_KERJA_ID_TUJUAN SATUAN_KERJA_ID_TUJUAN, A.SURAT_MASUK_ID SURAT_MASUK_ID, A.CATATAN ISI,
				G.NAMA ASAL, H.NAMA TUJUAN,
                TO_CHAR(A.TANGGAL_DITERUSKAN, 'DD-MM-YYYY') TGL_DISPOSISI,
				AA.TERBACA BACA, AA.TERBALAS BALAS, AA.TERDISPOSISI DISPOSISI, A.TERPARAF PARAF, 
				A.TERTANDA_TANGANI TANDA_TANGAN, A.SURAT_MASUK_ID DISPOSISI_ID, 'SURAT MASUK' STATUS, A.USER_ID USER_ID
        FROM surat_masuk A 
        LEFT JOIN SURAT_MASUK_TUJUAN AA ON A.SURAT_MASUK_ID = AA.SURAT_MASUK_ID
		LEFT JOIN satuan_kerja G ON G.SATUAN_KERJA_ID = A.SATUAN_KERJA_ID_ASAL
		LEFT JOIN satuan_kerja  H ON H.SATUAN_KERJA_ID = AA.SATUAN_KERJA_ID_TUJUAN
		WHERE 1=1
		";		
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= "
		UNION ALL
         SELECT ";
		$str .= $statement1;
		$str .= "
		 	A.SATUAN_KERJA_ID_ASAL SATUAN_KERJA_ID_ASAL, A.SATUAN_KERJA_ID_TUJUAN SATUAN_KERJA_ID_TUJUAN, A.SURAT_MASUK_ID SURAT_MASUK_ID, ISI ISI, 
            G.NAMA ASAL, H.NAMA TUJUAN,
            TO_CHAR(A.TANGGAL_DISPOSISI, 'DD-MM-YYYY') TGL_DISPOSISI, TERBACA BACA, TERBALAS BALAS, 
            TERDISPOSISI DISPOSISI, TERPARAF PARAF, TERTANDA_TANGANI TANDA_TANGAN, DISPOSISI_ID DISPOSISI_ID, 'DISPOSISI' STATUS, A.USER_ID USER_ID
         FROM DISPOSISI A 
		 LEFT JOIN satuan_kerja G ON G.SATUAN_KERJA_ID = A.SATUAN_KERJA_ID_ASAL
		 LEFT JOIN satuan_kerja H ON H.SATUAN_KERJA_ID = A.SATUAN_KERJA_ID_TUJUAN 
		 WHERE 1=1
		";
		
		while(list($key1,$val1) = each($paramsArray1))
		{
			$str .= " AND $key1 = '$val1' ";
		}
		
		$str .= " ";
		$this->query = $str;
		//echo $str;
		return $this->selectLimit($str,$limit,$from); 
    }
	
	function selectByParamsReportDisposisiAll($paramsArray=array(),$paramsArray1=array(),$limit=-1,$from=-1, $statement = '', $statement1 = '')
	{
		//(SELECT X.NAMA FROM satuan_kerja X WHERE X.SATUAN_KERJA_ID = A.SATUAN_KERJA_ID_ASAL) ASAL, (SELECT X.NAMA FROM satuan_kerja X WHERE X.SATUAN_KERJA_ID = A.SATUAN_KERJA_ID_TUJUAN) TUJUAN,
		$str = "
		SELECT A.*,  CASE WHEN A.STATUS = 'DISPOSISI' THEN TGL_DISPOSISI ELSE NULL END TANGGAL_DISPOSISI_INFO FROM
		(
		SELECT  ";
		$str .= $statement;
		$str .= "
				A.SATUAN_KERJA_ID_ASAL SATUAN_KERJA_ID_ASAL, A.SATUAN_KERJA_ID_TUJUAN SATUAN_KERJA_ID_TUJUAN, A.SURAT_MASUK_ID SURAT_MASUK_ID, A.CATATAN ISI,
				G.NAMA ASAL, H.NAMA TUJUAN,
				TO_CHAR(A.TANGGAL_ENTRI, 'YYYY-MM-DD HH24:MI') TGL_DISPOSISI,
				A.TERBACA BACA, A.TERBALAS BALAS, A.TERDISPOSISI DISPOSISI, A.TERPARAF PARAF, 
				A.TERTANDA_TANGANI TANDA_TANGAN, A.SURAT_MASUK_ID DISPOSISI_ID, 'SURAT MASUK' STATUS, A.USER_ID USER_ID
        FROM surat_masuk A 
		LEFT JOIN
		(
		SELECT A.NAMA || '-' || B.NAMA NAMA, A.SATUAN_KERJA_ID, B.USER_ID
		FROM satuan_kerja A
		INNER JOIN users B ON A.SATUAN_KERJA_ID = B.SATUAN_KERJA_ID
		WHERE 1=1
		) G ON G.SATUAN_KERJA_ID = A.SATUAN_KERJA_ID_ASAL AND A.USER_ID = G.USER_ID
		LEFT JOIN
		(
		SELECT A.NAMA || '-' || B.NAMA NAMA1, A.NAMA, A.SATUAN_KERJA_ID, B.USER_ID
		FROM satuan_kerja A
		INNER JOIN users B ON A.SATUAN_KERJA_ID = B.SATUAN_KERJA_ID
		WHERE 1=1
		) H ON H.SATUAN_KERJA_ID = A.SATUAN_KERJA_ID_TUJUAN 
		WHERE 1=1
		";		
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		$str .= "
		UNION ALL
         SELECT ";
		$str .= $statement1;
		$str .= "
		 	A.SATUAN_KERJA_ID_ASAL SATUAN_KERJA_ID_ASAL, A.SATUAN_KERJA_ID_TUJUAN SATUAN_KERJA_ID_TUJUAN, A.SURAT_MASUK_ID SURAT_MASUK_ID, ISI ISI, 
            G.NAMA ASAL, H.NAMA TUJUAN,
			TO_CHAR(A.TANGGAL_DISPOSISI, 'YYYY-MM-DD HH24:MI') TGL_DISPOSISI, TERBACA BACA, TERBALAS BALAS, 
            TERDISPOSISI DISPOSISI, TERPARAF PARAF, TERTANDA_TANGANI TANDA_TANGAN, DISPOSISI_ID DISPOSISI_ID, 'DISPOSISI' STATUS, A.USER_ID USER_ID
         FROM DISPOSISI A 
		 LEFT JOIN
		 (
		 SELECT A.NAMA || '-' || B.NAMA NAMA, A.SATUAN_KERJA_ID, B.USER_ID
		 FROM satuan_kerja A
		 INNER JOIN users B ON A.SATUAN_KERJA_ID = B.SATUAN_KERJA_ID
		 WHERE 1=1
		 ) G ON G.SATUAN_KERJA_ID = A.SATUAN_KERJA_ID_ASAL AND A.USER_ID = G.USER_ID
		 LEFT JOIN
		 (
		 SELECT A.NAMA || '-' || B.NAMA NAMA1, A.NAMA, A.SATUAN_KERJA_ID, B.USER_ID
		 FROM satuan_kerja A
		 INNER JOIN users B ON A.SATUAN_KERJA_ID = B.SATUAN_KERJA_ID
		 WHERE 1=1
		 ) H ON H.SATUAN_KERJA_ID = A.SATUAN_KERJA_ID_TUJUAN 
		 WHERE 1=1
		";
		
		while(list($key1,$val1) = each($paramsArray1))
		{
			$str .= " AND $key1 = '$val1' ";
		}
		
		$str .= " 
		) A WHERE DISPOSISI IS NULL
		";
		$this->query = $str;
		//echo $str;
		return $this->selectLimit($str,$limit,$from); 
    }
	
	function selectByParamsRiwayatDisposisiBak($paramsArray=array(),$limit=-1,$from=-1, $statement = '')
	{
		//(SELECT X.NAMA FROM satuan_kerja X WHERE X.SATUAN_KERJA_ID = A.SATUAN_KERJA_ID_ASAL) ASAL, 
		//(SELECT X.NAMA FROM satuan_kerja X WHERE X.SATUAN_KERJA_ID = A.SATUAN_KERJA_ID_TUJUAN) TUJUAN, 
		$str .= "
         SELECT DISTINCT 
                DISPOSISI_ID, A.SATUAN_KERJA_ID_ASAL SATUAN_KERJA_ID_ASAL, A.SATUAN_KERJA_ID_TUJUAN SATUAN_KERJA_ID_TUJUAN, A.SURAT_MASUK_ID SURAT_MASUK_ID, ISI ISI, 
               G.NAMA ASAL, H.NAMA TUJUAN,
               TO_CHAR(A.TANGGAL_DISPOSISI, 'DD-MM-YYYY') TGL_DISPOSISI, TERBACA BACA, TERBALAS BALAS, 
               TERDISPOSISI DISPOSISI, TERPARAF PARAF, TERTANDA_TANGANI TANDA_TANGAN, 'DISPOSISI' STATUS,
               A.STATUS_KEMBALI
         FROM DISPOSISI A 
         LEFT JOIN
         (
         SELECT A.NAMA || '-' || B.NAMA NAMA, A.SATUAN_KERJA_ID, B.USER_ID
         FROM satuan_kerja A
         INNER JOIN users B ON A.SATUAN_KERJA_ID = B.SATUAN_KERJA_ID
         WHERE 1=1
         ) G ON G.SATUAN_KERJA_ID = A.SATUAN_KERJA_ID_ASAL AND ROWNUM = 1
         LEFT JOIN
         (
         SELECT A.NAMA || '-' || B.NAMA NAMA1, A.NAMA, A.SATUAN_KERJA_ID, B.USER_ID
         FROM satuan_kerja A
         INNER JOIN users B ON A.SATUAN_KERJA_ID = B.SATUAN_KERJA_ID
         WHERE 1=1
         ) H ON H.SATUAN_KERJA_ID = A.SATUAN_KERJA_ID_TUJUAN AND ROWNUM = 1
		 LEFT JOIN SURAT_MASUK S ON S.SURAT_MASUK_ID = A.SURAT_MASUK_ID
         WHERE 1=1
		";
		
		while(list($key1,$val1) = each($paramsArray))
		{
			$str .= " AND $key1 = '$val1' ";
		}
		
		$str .= $statement."
		GROUP BY DISPOSISI_ID, A.SATUAN_KERJA_ID_ASAL, A.SATUAN_KERJA_ID_TUJUAN, A.SURAT_MASUK_ID, S.SATUAN_KERJA_ID_ASAL, ISI, 
               G.NAMA, H.NAMA, A.TANGGAL_DISPOSISI, TERBACA, TERBALAS, 
               TERDISPOSISI, TERPARAF, TERTANDA_TANGANI, DISPOSISI_ID, A.USER_ID, A.STATUS_KEMBALI
		";
		$this->query = $str;
		//echo $str;
		return $this->selectLimit($str,$limit,$from); 
    }
	
	function selectByParamsRiwayatDisposisi($paramsArray=array(),$limit=-1,$from=-1, $statement = '')
	{
		//(SELECT X.NAMA FROM satuan_kerja X WHERE X.SATUAN_KERJA_ID = A.SATUAN_KERJA_ID_ASAL) ASAL, 
		//(SELECT X.NAMA FROM satuan_kerja X WHERE X.SATUAN_KERJA_ID = A.SATUAN_KERJA_ID_TUJUAN) TUJUAN, 
		$str .= "
         SELECT DISTINCT 
                DISPOSISI_ID, A.SATUAN_KERJA_ID_ASAL SATUAN_KERJA_ID_ASAL, A.SATUAN_KERJA_ID_TUJUAN SATUAN_KERJA_ID_TUJUAN, A.SURAT_MASUK_ID SURAT_MASUK_ID, A.ISI ISI,
               AMBIL_SATUAN_KERJA_NAMA(A.SATUAN_KERJA_ID_ASAL) ASAL, AMBIL_SATUAN_KERJA_NAMA(A.SATUAN_KERJA_ID_TUJUAN) TUJUAN,
			   TO_CHAR(A.TANGGAL_DISPOSISI, 'YYYY-MM-DD HH24:MI') TGL_DISPOSISI, A.TERBACA BACA, A.TERBALAS BALAS, TREE_ID, TREE_PARENT_ID,
			   A.TERDISPOSISI DISPOSISI, A.TERPARAF PARAF, A.TERTANDA_TANGANI TANDA_TANGAN, 'DISPOSISI' STATUS, A.STATUS_KEMBALI
         FROM DISPOSISI A 
		 LEFT JOIN SURAT_MASUK S ON S.SURAT_MASUK_ID = A.SURAT_MASUK_ID
         WHERE 1=1
		";
		
		while(list($key1,$val1) = each($paramsArray))
		{
			$str .= " AND $key1 = '$val1' ";
		}
		
		$str .= $statement."
		GROUP BY DISPOSISI_ID, A.SATUAN_KERJA_ID_ASAL, A.SATUAN_KERJA_ID_TUJUAN, A.SURAT_MASUK_ID, S.SATUAN_KERJA_ID_ASAL, A.ISI, TREE_ID, TREE_PARENT_ID,
		A.TANGGAL_DISPOSISI, A.TERBACA, A.TERBALAS, A.TERDISPOSISI, A.TERPARAF, A.TERTANDA_TANGANI, DISPOSISI_ID, A.USER_ID, A.STATUS_KEMBALI
		";
		$this->query = $str;
		//echo $str;
		return $this->selectLimit($str,$limit,$from); 
    }
	
	function getCountByParamsRiwayatDisposisi($paramsArray=array(),$statement="")
	{
		$str = "
				 SELECT COUNT(*) ROWCOUNT
				 FROM
				 (
					 SELECT DISTINCT 
							DISPOSISI_ID, A.SATUAN_KERJA_ID_ASAL SATUAN_KERJA_ID_ASAL, A.SATUAN_KERJA_ID_TUJUAN SATUAN_KERJA_ID_TUJUAN, A.SURAT_MASUK_ID SURAT_MASUK_ID, ISI ISI, 
						   G.NAMA ASAL, H.NAMA TUJUAN,
						   TO_CHAR(A.TANGGAL_DISPOSISI, 'DD-MM-YYYY') TGL_DISPOSISI, TERBACA BACA, TERBALAS BALAS, 
						   TERDISPOSISI DISPOSISI, TERPARAF PARAF, TERTANDA_TANGANI TANDA_TANGAN, 'DISPOSISI' STATUS,
						   A.STATUS_KEMBALI
					 FROM DISPOSISI A 
					 LEFT JOIN
					 (
					 SELECT A.NAMA || '-' || B.NAMA NAMA, A.SATUAN_KERJA_ID, B.USER_ID
					 FROM satuan_kerja A
					 INNER JOIN users B ON A.SATUAN_KERJA_ID = B.SATUAN_KERJA_ID
					 WHERE 1=1 LIMIT 1
					 ) G ON G.SATUAN_KERJA_ID = A.SATUAN_KERJA_ID_ASAL 
					 LEFT JOIN
					 (
					 SELECT A.NAMA || '-' || B.NAMA NAMA1, A.NAMA, A.SATUAN_KERJA_ID, B.USER_ID
					 FROM satuan_kerja A
					 INNER JOIN users B ON A.SATUAN_KERJA_ID = B.SATUAN_KERJA_ID
					 WHERE 1=1 LIMIT 1
					 ) H ON H.SATUAN_KERJA_ID = A.SATUAN_KERJA_ID_TUJUAN
					 WHERE 1=1
				 "; 
		while(list($key,$val)=each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement."
		GROUP BY DISPOSISI_ID, A.SATUAN_KERJA_ID_ASAL, A.SATUAN_KERJA_ID_TUJUAN, A.SURAT_MASUK_ID, ISI, 
               G.NAMA, H.NAMA, A.TANGGAL_DISPOSISI, TERBACA, TERBALAS, 
               TERDISPOSISI, TERPARAF, TERTANDA_TANGANI, DISPOSISI_ID, A.USER_ID, A.STATUS_KEMBALI
			   ) A
		";
		$this->query = $str;
		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0; 
    }
	
    function getCountByParams($paramsArray=array(),$statement="")
	{
		$str = "SELECT COUNT(surat_masuk_id) AS ROWCOUNT FROM disposisi WHERE 1=1 "; 
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

    function getCountByParamsLike($paramsArray=array(), $varStatement="")
	{
		$str = "SELECT COUNT(klasifikasi_id) AS ROWCOUNT FROM klasifikasi WHERE klasifikasi_id IS NOT NULL "; 
		while(list($key,$val)=each($paramsArray))
		{
			$str .= " AND $key LIKE '%$val%' ";
		}
		
		$this->select($str); 
		//echo $str;	
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0; 
    }
	
  } 
?>