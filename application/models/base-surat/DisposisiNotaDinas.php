<? 

  /***
  * Entity-base class untuk mengimplementasikan tabel kategori.
  * 
  ***/
  include_once(APPPATH.'/models/Entity.php');

  class DisposisiNotaDinas extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function DisposisiNotaDinas()
	{
      $this->Entity(); 
    }
	
	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("disposisi_id", $this->getNextId("NOTA_DINAS_DISPOSISI_ID","NOTA_DINAS_DISPOSISI"));
		$this->tanggal = date("Y-m-d");
		$this->NowYear = date("Y");
		
		$str = "
		INSERT INTO NOTA_DINAS_DISPOSISI(NOTA_DINAS_DISPOSISI_ID, NOTA_DINAS_ID, TAHUN, SATUAN_KERJA_ID_ASAL, SATUAN_KERJA_ID_TUJUAN, TANGGAL_DISPOSISI, USER_ID,
                                NAMA_USER, ISI, TANGGAL_BATAS, NOTA_TUJUAN_ID)
                    VALUES ('".$this->getField("disposisi_id")."',
                            '".$this->getField("nota_dinas_id")."',
                            ".$this->NowYear.",
                            '".$this->getField("satuan_kerja_id_asal")."',
                            '".$this->getField("satuan_kerja_id_tujuan")."',
                            ".$this->getField("tanggal_disposisi").",
							'".$this->getField("user_id")."',
							'".$this->getField("nama_user")."',
                            '".$this->getField("isi")."',
                            ".$this->getField("tanggal_batas").",
							".$this->getField("nota_tujuan_id")."
							)
		";
				
		$this->query = $str;
		$this->id = $this->getField("disposisi_id");
		//echo $str;
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
			   tanggal_batas = '".$this->getField("tanggal_batas")."',
			   jenis = '".$this->getField("jenis")."',
			   jenis_tujuan = '".$this->getField("jenis_tujuan")."',
			   kepada = '".$this->getField("kepada")."',
			   perihal = '".$this->getField("perihal")."',
			   klasifikasi_id = '".$this->getField("klasifikasi_id")."',
			   instansi_asal = '".$this->getField("instansi_asal")."',
			   alamat_asal = '".$this->getField("alamat_asal")."',
			   kota_asal = '".$this->getField("kota_asal")."',
			   keterangan_asal = '".$this->getField("keterangan_asal")."',
			   satuan_kerja_id_tujuan = '".$this->getField("satuan_kerja_id_tujuan")."',
			   isi = '".$this->getField("isi")."',
			   catatan = '".$this->getField("catatan")."',
			   tanggal_entri = ".$this->tanggal.",
			   user_id = '".$this->getField("user_id")."',
			   nama_user = '".$this->getField("nama_user")."'
		   WHERE nota_dinas_id = '".$this->getField("nota_dinas_id")."'
				"; 
				$this->query = $str;
				$this->id = $this->getField("klasifikasi_id");
		//echo $str;
		return $this->execQuery($str);
    }
		
	function delete()
	{		
		$str1 = "
		 		DELETE FROM klasifikasi
                WHERE 
                  klasifikasi_id = '".$this->getField("klasifikasi_id")."'";
				  
		$this->query = $str1;
        return $this->execQuery($str1);
    }*/
	

	function updateDisposisiNotaDinas()
	{
        $str = "UPDATE NOTA_DINAS_DISPOSISI SET TERDISPOSISI = 1
                WHERE 
				  NOTA_DINAS_ID = '".$this->getField("nota_dinas_id")."' AND
                  SATUAN_KERJA_ID_TUJUAN = '".$this->getField("satuan_kerja_id_tujuan")."'";
				  
		$this->query = $str;
        return $this->execQuery($str);
    }
	
	function resetTerbalasSuratKeluar()
	{
        $str = "UPDATE NOTA_DINAS_DISPOSISI SET TERBALAS = 0
                WHERE 
				  NOTA_DINAS_ID = '".$this->getField("nota_dinas_id")."' AND
                  SATUAN_KERJA_ID_TUJUAN = '".$this->getField("satuan_kerja_id_tujuan")."'";
				  
		$this->query = $str;
        return $this->execQuery($str);
    }
	
	function updateDisposisiNotaDinasAwal()
	{
        $str = "UPDATE NOTA_DINAS_TUJUAN SET TERDISPOSISI = 1
                WHERE 
                  NOTA_DINAS_ID = '".$this->getField("nota_dinas_id")."'
				  AND SATUAN_KERJA_ID = '".$this->getField("satuan_kerja_id")."'
				";
				  
		$this->query = $str;
        return $this->execQuery($str);
    }
	
	function selectBy($paramsArray=array(),$limit=-1,$from=-1)
	{
		$str = "
		SELECT 
		NOTA_DINAS_DISPOSISI_ID, NOTA_DINAS_ID, TAHUN, 
		   SATUAN_KERJA_ID_ASAL, SATUAN_KERJA_ID_TUJUAN, TANGGAL_DISPOSISI, 
		   USER_ID, NAMA_USER, TERBACA, 
		   TERBALAS, TERDISPOSISI, TERPARAF, 
		   ISI, TANGGAL_BATAS, TERTANDA_TANGANI
		FROM NOTA_DINAS_DISPOSISI
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
        		FROM NOTA_DINAS A  LEFT JOIN DAFTAR_ALAMAT D ON A.INSTANSI_ASAL = D.INSTANSI, KLASIFIKASI B, SATUAN_KERJA C
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
					NOTA_DINAS_DISPOSISI_ID , NOTA_DINAS_ID , ISI , USER_ID , TERBACA TERBACA_TUJUAN
        		FROM NOTA_DINAS_DISPOSISI
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
				SELECT nota_dinas_id \"No. Agenda\", nama_user \"Instansi Entry\", nomor \"No. Surat Masuk\",
                TO_CHAR(a.tanggal, 'DD-MM-YYYY') \"Tgl Surat\", TO_CHAR(a.tanggal_diteruskan, 'DD-MM-YYYY') \"Tgl Diteruskan\", 
                TO_CHAR(a.tanggal_batas, 'DD-MM-YYYY') \"Tgl Reminten\",
                terdisposisi \"DisposisiNotaDinas\", terbalas \"Balas\", 
                (SELECT nomor FROM surat_keluar x WHERE a.nota_dinas_id = x.nota_dinas_id AND ROWNUM = 1) \"No. Surat Keluar\",
                instansi_asal \"Instansi Asal\", c.nama \"Instansi Tujuan\", a.klasifikasi_id \"Kode\", b.nama \"Klasifikasi\",
                a.alamat_asal \"Alamat Asal\", a.keterangan_asal \"Ket Asal\", a.jumlah_lampiran \"Jml Lap\", a.catatan \"catatan\", a.perihal \"Perihal\"
                FROM surat_masuk a LEFT JOIN klasifikasi b ON a.klasifikasi_id = b.klasifikasi_id
                LEFT JOIN satuan_kerja c ON a.satuan_kerja_id_tujuan = c.satuan_kerja_id                
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
	
	  function selectByParamsReportDisposisiNotaDinas($paramsArray=array(),$paramsArray1=array(),$limit=-1,$from=-1, $statement = '', $statement1 = '')
	{
		$str = "
		SELECT  ";
		$str .= $statement;
		$str .= "
				A.SATUAN_KERJA_ID_ASAL SATUAN_KERJA_ID_ASAL, A.SATUAN_KERJA_ID_TUJUAN SATUAN_KERJA_ID_TUJUAN, A.NOTA_DINAS_ID NOTA_DINAS_ID, A.CATATAN ISI,
				(SELECT X.NAMA FROM SATUAN_KERJA X WHERE X.SATUAN_KERJA_ID = A.SATUAN_KERJA_ID_ASAL) ASAL,
                (SELECT X.NAMA FROM SATUAN_KERJA X WHERE X.SATUAN_KERJA_ID = A.SATUAN_KERJA_ID_TUJUAN) TUJUAN,
                TO_CHAR(A.TANGGAL_DITERUSKAN, 'DD-MM-YYYY') TGL_NOTA_DINAS_DISPOSISI,
				A.TERBACA BACA, A.TERBALAS BALAS, A.TERDISPOSISI NOTA_DINAS_DISPOSISI, TERPARAF PARAF, 
				TERTANDA_TANGANI TANDA_TANGAN, NOTA_DINAS_ID NOTA_DINAS_DISPOSISI_ID, 'SURAT MASUK' STATUS, USER_ID USER_ID
        FROM NOTA_DINAS A WHERE 1=1
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
		 		 A.SATUAN_KERJA_ID_ASAL SATUAN_KERJA_ID_ASAL, A.SATUAN_KERJA_ID_TUJUAN SATUAN_KERJA_ID_TUJUAN, A.NOTA_DINAS_ID NOTA_DINAS_ID, ISI ISI, 
                 (SELECT X.NAMA FROM SATUAN_KERJA X WHERE X.SATUAN_KERJA_ID = A.SATUAN_KERJA_ID_ASAL) ASAL, 
               (SELECT X.NAMA FROM SATUAN_KERJA X WHERE X.SATUAN_KERJA_ID = A.SATUAN_KERJA_ID_TUJUAN) TUJUAN, 
               TO_CHAR(A.TANGGAL_DISPOSISI, 'DD-MM-YYYY') TGL_NOTA_DINAS_DISPOSISI, TERBACA BACA, TERBALAS BALAS, 
               TERDISPOSISI NOTA_DINAS_DISPOSISI, TERPARAF PARAF, TERTANDA_TANGANI TANDA_TANGAN, NOTA_DINAS_DISPOSISI_ID NOTA_DINAS_DISPOSISI_ID, 'NOTA_DINAS_DISPOSISI' STATUS, USER_ID USER_ID
         FROM NOTA_DINAS_DISPOSISI A WHERE 1=1
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
	
	function selectByParamsRiwayatDisposisiNotaDinas($paramsArray=array(),$limit=-1,$from=-1, $statement = '')
	{
		
		$str .= "
         SELECT 
		 		 NOTA_DINAS_DISPOSISI_ID, A.SATUAN_KERJA_ID_ASAL SATUAN_KERJA_ID_ASAL, A.SATUAN_KERJA_ID_TUJUAN SATUAN_KERJA_ID_TUJUAN, A.NOTA_DINAS_ID NOTA_DINAS_ID, ISI ISI, 
                 (SELECT X.NAMA FROM SATUAN_KERJA X WHERE X.SATUAN_KERJA_ID = A.SATUAN_KERJA_ID_ASAL) ASAL, 
               (SELECT X.NAMA FROM SATUAN_KERJA X WHERE X.SATUAN_KERJA_ID = A.SATUAN_KERJA_ID_TUJUAN) TUJUAN, 
			   A.SATUAN_KERJA_ID_TUJUAN, A.SATUAN_KERJA_ID_ASAL,
               TO_CHAR(A.TANGGAL_DISPOSISI, 'DD-MM-YYYY') TGL_DISPOSISI, TERBACA BACA, TERBALAS BALAS, 
               TERDISPOSISI DISPOSISI, TERPARAF PARAF, TERTANDA_TANGANI TANDA_TANGAN, NOTA_DINAS_DISPOSISI_ID NOTA_DINAS_DISPOSISI_ID, 'NOTA DINAS DISPOSISI' STATUS, USER_ID USER_ID
         FROM NOTA_DINAS_DISPOSISI A WHERE 1=1
		";
		
		while(list($key1,$val1) = each($paramsArray))
		{
			$str .= " AND $key1 = '$val1' ";
		}
		$str .= $statement;
		$this->query = $str;
		//echo $str;
		return $this->selectLimit($str,$limit,$from); 
    }
	
    function getCountByParams($paramsArray=array())
	{
		$str = "SELECT COUNT(nota_dinas_id) AS ROWCOUNT FROM disposisi WHERE 1=1 "; 
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