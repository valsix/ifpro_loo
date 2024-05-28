<? 

  /***
  * Entity-base class untuk mengimplementasikan tabel kategori.
  * 
  ***/
  include_once(APPPATH.'/models/Entity.php');

  class SuratMasuk extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function SuratMasuk()
	{
      $this->Entity(); 
    }
	
	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("SURAT_MASUK_ID", $this->getNextId("SURAT_MASUK_ID","SURAT_MASUK")); 
		$this->tanggal = date("Y-m-d");
		$this->NowYear = date("Y");
		
		$str = "
		INSERT INTO SURAT_MASUK(SURAT_MASUK_ID, NO_AGENDA, TAHUN, TANGGAL_KEGIATAN, TANGGAL_KEGIATAN_AKHIR, LOKASI_SIMPAN, 
				 NOMOR, TANGGAL,TANGGAL_DITERUSKAN,TANGGAL_BATAS,JENIS,
				 JENIS_TUJUAN,KEPADA,PERIHAL,KLASIFIKASI_ID,INSTANSI_ASAL,
				 ALAMAT_ASAL,KOTA_ASAL,KETERANGAN_ASAL,SATUAN_KERJA_ID_ASAL,SATUAN_KERJA_ID_TUJUAN,ISI,
				 CATATAN,TANGGAL_ENTRI,USER_ID,NAMA_USER,
				 WAITING_LIST, JUMLAH_LAMPIRAN, LAST_CREATE_USER, LAST_CREATE_DATE
            )
            VALUES ('".$this->getField("SURAT_MASUK_ID")."',
					'".$this->getField("NO_AGENDA")."',
					".$this->NowYear.",
					".$this->getField("TANGGAL_KEGIATAN").",
					".$this->getField("TANGGAL_KEGIATAN_AKHIR").",
					'".$this->getField("LOKASI_SIMPAN")."',
                    '".$this->getField("NOMOR")."',
                    ".$this->getField("TANGGAL").",
                    ".$this->getField("TANGGAL_DITERUSKAN").",
                    ".$this->getField("TANGGAL_BATAS").",
                    '".$this->getField("JENIS")."',
                    '".$this->getField("JENIS_TUJUAN")."',
                    '".$this->getField("KEPADA")."',
                    '".$this->getField("PERIHAL")."',
                    '".$this->getField("KLASIFIKASI_ID")."',
                    '".$this->getField("INSTANSI_ASAL")."',
                    '".$this->getField("ALAMAT_ASAL")."',
                    '".$this->getField("KOTA_ASAL")."',
                    '".$this->getField("KETERANGAN_ASAL")."',
                    '".$this->getField("SATUAN_KERJA_ID_ASAL")."',					
                    '".$this->getField("SATUAN_KERJA_ID_TUJUAN").";',
                    '".$this->getField("ISI")."',
                    '".$this->getField("CATATAN")."',
					TO_DATE(TO_CHAR(CURRENT_DATE, 'yyyy/mm/dd hh24:mi:ss'), 'yyyy/mm/dd hh24:mi:ss'),
                    '".$this->getField("user_id")."',
                    '".$this->getField("NAMA_USER")."',
					null,
					'0',
                    '".$this->getField("MENIMBANG")."',
                    '".$this->getField("MENGINGAT")."',
                    '".$this->getField("MENETAPKAN")."',
                    '".$this->getField("PERTAMA")."',
                    '".$this->getField("KEDUA")."',
                    '".$this->getField("KETIGA")."',
                    '".$this->getField("KEEMPAT")."',
                    '".$this->getField("KELIMA")."',
				  	'".$this->getField("LAST_CREATE_USER")."',
				  	CURRENT_DATE
				)";

		$this->query = $str;
		$this->reqSuratMasukId = $this->getField("SURAT_MASUK_ID");
		$this->id = $this->getField("KLASIFIKASI_ID");
		//echo $str;
		return $this->execQuery($str);
    }
	
	function insertArsip()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("ARSIP_ID", $this->getNextId("ARSIP_ID","arsip.ARSIP")); 
		$this->tanggal = date("Y-m-d");
		$this->NowYear = date("Y");
		
		$str = "
			INSERT INTO arsip.ARSIP(
			   SURAT_MASUK_ID, ARSIP_TAHUN, ARSIP_ID, ARSIP_KODE, ARSIP_JENIS, ARSIP_SIFAT,
			   ARSIP_ORGANISASI, ARSIP_NOMOR, ARSIP_TANGGAL, ARSIP_JUDUL, ARSIP_STATUS, ARSIP_AUTHOR, ARSIP_CREATE)
			SELECT SURAT_MASUK_ID, TAHUN, '".$this->getField("ARSIP_ID")."', NO_AGENDA, '01', 2, SATUAN_KERJA_ID_ASAL, NO_AGENDA, TANGGAL, NOMOR, 
				   'CREATE', '".$this->getField("ARSIP_AUTHOR")."', CURRENT_DATE 
			FROM surat_masuk WHERE SURAT_MASUK_ID =  ".$this->getField("SURAT_MASUK_ID")."
            ";
				
		$this->query = $str;
		$this->id = $this->getField("ARSIP_ID");
		return $this->execQuery($str);
    }
	
	function upload($table, $column, $blob, $id)
	{
		return $this->uploadBlob($table, $column, $blob, $id);
    }
	
	function updateArsip()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "UPDATE arsip.ARSIP A
				SET ARSIP_TAHUN			= B.TAHUN,
					ARSIP_KODE			= B.NO_AGENDA,
					ARSIP_ORGANISASI 	= B.SATUAN_KERJA_ID_ASAL, 
					ARSIP_NOMOR			= B.NO_AGENDA, 
					ARSIP_TANGGAL		= B.TANGGAL, 
					ARSIP_JUDUL			= B.NOMOR,
					ARSIP_JENIS			= '01', 
					ARSIP_SIFAT			= 2
				FROM (SELECT SURAT_MASUK_ID, TAHUN, NO_AGENDA, SATUAN_KERJA_ID_ASAL, TANGGAL, NOMOR
					  FROM surat_masuk) AS B
				WHERE A.SURAT_MASUK_ID = B.SURAT_MASUK_ID 
				AND A.SURAT_MASUK_ID = ".$this->getField("SURAT_MASUK_ID")."
				"; 
				$this->query = $str;
		//echo $str;
		return $this->execQuery($str);
    }
	
	function updatePosisi()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "UPDATE SURAT_MASUK A SET
				  POSISI_SURAT_MASUK = '".$this->getField("POSISI_SURAT_MASUK")."'
				WHERE SURAT_MASUK_ID = ".$this->getField("SURAT_MASUK_ID")."
				"; 
				$this->query = $str;
		//echo $str;
		return $this->execQuery($str);
    }
	
	function updatePosisiSms()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "UPDATE SURAT_MASUK A SET
				  INFO_SMS_TELEPON= '".$this->getField("INFO_SMS_TELEPON")."',
				  INFO_SMS_POSISI= '".$this->getField("INFO_SMS_POSISI")."',
				  INFO_SMS= '".$this->getField("INFO_SMS")."'
				WHERE SURAT_MASUK_ID = ".$this->getField("SURAT_MASUK_ID")."
				"; 
				$this->query = $str;
		//echo $str;
		return $this->execQuery($str);
    }
	
	function updatePosisiPenerima()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "UPDATE SURAT_MASUK A SET
				  PENERIMA_SURAT= '".$this->getField("PENERIMA_SURAT")."',
  				  PENERIMA_SURAT_TANGGAL= ".$this->getField("PENERIMA_SURAT_TANGGAL").",
  				  PENERIMA_SURAT_TTD= ".$this->getField("PENERIMA_SURAT_TTD")."
				WHERE SURAT_MASUK_ID = ".$this->getField("SURAT_MASUK_ID")."
				"; 
				$this->query = $str;
		//echo $str;
		return $this->execQuery($str);
    }
	
	function updateLokasiSimpan()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "UPDATE SURAT_MASUK A SET
				  LOKASI_SIMPAN= '".$this->getField("LOKASI_SIMPAN")."',
				  LOKASI_ORDNER= '".$this->getField("LOKASI_ORDNER")."',
				  LOKASI_ORDNER_LEMBAR= '".$this->getField("LOKASI_ORDNER_LEMBAR")."',
				  LOKASI_ORDNER_TAHUN= '".$this->getField("LOKASI_ORDNER_TAHUN")."'
				WHERE SURAT_MASUK_ID = ".$this->getField("SURAT_MASUK_ID")."
				"; 
				$this->query = $str;
		//echo $str;
		return $this->execQuery($str);
    }
	
	function update_dyna()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "UPDATE SURAT_MASUK A SET
				  ".$this->getField("FIELD")." = '".$this->getField("FIELD_VALUE")."'
				WHERE SURAT_MASUK_ID = ".$this->getField("SURAT_MASUK_ID")."
				"; 
				$this->query = $str;
		//echo $str;
		return $this->execQuery($str);
    }
	
	function update_baca_dyna($stat='')
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "UPDATE ".$this->getField("TABLE")." SET
				  TERBACA = '1'
				WHERE ".$this->getField("FIELD")." = '".$this->getField("FIELD_VALUE")."'
				".$stat; 
				$this->query = $str;
		//echo $str;
		return $this->execQuery($str);
    }
	
    function update()
	{
		$this->tanggal = date("Y-m-d");
		$this->NowYear = date("Y");
		//Auto-generate primary key(s) by next max value (integer) 		tahun = ".$this->NowYear.", tanggal_entri = ".$this->tanggal.",
		$str = "
		UPDATE SURAT_MASUK SET
			   TANGGAL_KEGIATAN			= ".$this->getField("TANGGAL_KEGIATAN").",
			   TANGGAL_KEGIATAN_AKHIR	= ".$this->getField("TANGGAL_KEGIATAN_AKHIR").",
			   LOKASI_SIMPAN			= '".$this->getField("LOKASI_SIMPAN")."',
			   NOMOR 					= '".$this->getField("NOMOR")."',
			   NO_AGENDA 				= '".$this->getField("NO_AGENDA")."',
			   TANGGAL 					= ".$this->getField("TANGGAL").",
			   TANGGAL_DITERUSKAN 		= ".$this->getField("TANGGAL_DITERUSKAN").",
			   TANGGAL_BATAS 			= ".$this->getField("TANGGAL_BATAS").",
			   JENIS 					= '".$this->getField("JENIS")."',
			   JENIS_TUJUAN 			= '".$this->getField("JENIS_TUJUAN")."',
			   KEPADA 					= '".$this->getField("KEPADA")."',
			   PERIHAL 					= '".$this->getField("PERIHAL")."',
			   KLASIFIKASI_ID 			= '".$this->getField("KLASIFIKASI_ID")."',
			   INSTANSI_ASAL 			= '".$this->getField("INSTANSI_ASAL")."',
			   ALAMAT_ASAL 				= '".$this->getField("ALAMAT_ASAL")."',
			   KOTA_ASAL 				= '".$this->getField("KOTA_ASAL")."',
			   KETERANGAN_ASAL 			= '".$this->getField("KETERANGAN_ASAL")."',
			   SATUAN_KERJA_ID_TUJUAN 	= '".$this->getField("SATUAN_KERJA_ID_TUJUAN")."',
			   ISI 						= '".$this->getField("ISI")."',
			   CATATAN 					= '".$this->getField("CATATAN")."',
			   NAMA_USER 				= '".$this->getField("NAMA_USER")."',
			   LAST_UPDATE_USER			= '".$this->getField("LAST_UPDATE_USER")."',
			   LAST_UPDATE_DATE			= CURRENT_DATE
		   WHERE SURAT_MASUK_ID 		= '".$this->getField("SURAT_MASUK_ID")."'
				"; // AND USER_ID = '".$this->getField("user_id")."'
				$this->query = $str;
				$this->id = $this->getField("KLASIFIKASI_ID");
		//echo $str;
		return $this->execQuery($str);
    }
		
	function delete()
	{	
		$str2 = "
		 		DELETE FROM surat_masuk_attachment
                WHERE 
                  SURAT_MASUK_ID = '".$this->getField("SURAT_MASUK_ID")."'";
				  
		$this->query = $str2;
        $this->execQuery($str2);
			
		$str = "
		 		DELETE FROM disposisi
                WHERE 
                  SURAT_MASUK_ID = '".$this->getField("SURAT_MASUK_ID")."'";
				  
		$this->query = $str;
        $this->execQuery($str);
		
		$str1 = "
		 		DELETE FROM surat_masuk
                WHERE 
                  SURAT_MASUK_ID = '".$this->getField("SURAT_MASUK_ID")."'";
				  
		$this->query = $str1;
        return $this->execQuery($str1);
    }
	
	function updateFinal()
	{
        $str = "UPDATE surat_masuk SET SURAT_MASUK_FINAL = CURRENT_DATE, SURAT_MASUK_STATUS = 'FINAL' 
				WHERE
                  SURAT_MASUK_ID = '".$this->getField("SURAT_MASUK_ID")."'"; 
		$this->query = $str;
        return $this->execQuery($str);
    }
	
	function deleteStatus()
	{
        $str = "UPDATE surat_masuk SET SURAT_MASUK_DELETE = CURRENT_DATE, SURAT_MASUK_STATUS = 'DELETE' 
				WHERE
                  SURAT_MASUK_ID = '".$this->getField("SURAT_MASUK_ID")."'"; 
		$this->query = $str;
        return $this->execQuery($str);
    }
	
	function updateSuratMasuk($SURAT_MASUK_ID)
	{
        $str = "UPDATE SURAT_MASUK SET terbaca = 1 WHERE SURAT_MASUK_ID = '".$SURAT_MASUK_ID."'";
				  
		$this->query = $str;
        return $this->execQuery($str);
    }

	function updateDisposisi($disposisi_id)
	{
        $str = "UPDATE disposisi SET terbaca = 1 WHERE disposisi_id = '".$disposisi_id."'";
				  
		$this->query = $str;
        return $this->execQuery($str);
    }

	function updateStatus($statement="")
	{
        $str = "UPDATE klasifikasi SET klasifikasi_STATUS = '".$this->getField("klasifikasi_STATUS")."' ".$statement."
                WHERE 
                  KLASIFIKASI_ID = '".$this->getField("KLASIFIKASI_ID")."'";
				  
		$this->query = $str;
        return $this->execQuery($str);
    }

	function updateArsipSuratMasuk($arsip_realisasi="")
	{
        $str = "UPDATE ARSIP_klasifikasi SET ARSIP_STATUS = '".$this->getField("ARSIP_STATUS")."'";
		if($arsip_realisasi != "")
			$str .= ", ARSIP_REALISASI = NOW() ";
		$str .= "
                WHERE 
                  KLASIFIKASI_ID = '".$this->getField("KLASIFIKASI_ID")."'";
				  
		$this->query = $str;
        return $this->execQuery($str);
    }

	
	function selectByParams($paramsArray=array(),$limit=-1,$from=-1,$stat='',$order="")
	{
		$str = "SELECT    
					A.INFO_SMS_POSISI INFO_SMS_POSISI_NAMA, INFO_SMS_POSISI, INFO_SMS, INFO_SMS_TELEPON, TANGGAL_KEGIATAN, 
					TANGGAL_KEGIATAN_AKHIR, LOKASI_SIMPAN, A.MENIMBANG, A.MENGINGAT, A.MENETAPKAN, A.PERTAMA, A.KEDUA, 
					A.KETIGA, A.KEEMPAT, A.KELIMA, 
					PENERIMA_SURAT, PENERIMA_SURAT_TANGGAL, PENERIMA_SURAT_TTD,
					UPPER(D.NAMA) PEMBUAT_SURAT, coalesce(UPPER(E.NAMA), 'BELUM DI TENTUKAN')POSISI_SURAT, A.POSISI_SURAT_MASUK,
					A.SURAT_MASUK_ID, A.NO_AGENDA, A.TERBACA TERBACA_TUJUAN, A.TAHUN, A.NOMOR, A.TANGGAL, A.TANGGAL_DITERUSKAN, 
					A.TANGGAL_BATAS, A.JENIS, A.JENIS_TUJUAN, A.KEPADA, 
					A.PERIHAL, A.KLASIFIKASI_ID, B.NAMA KLASIFIKASI_NAMA, A.INSTANSI_ASAL, A.ALAMAT_ASAL, A.KOTA_ASAL, A.KETERANGAN_ASAL, 
					A.SATUAN_KERJA_ID_TUJUAN, ambil_satuan_kerja(A .SATUAN_KERJA_ID_TUJUAN) SATUAN_NAMA, A.ISI, A.CATATAN, 
					TO_CHAR(A.TANGGAL_ENTRI, 'YYYY-MM-DD HH24:MI') TANGGAL_ENTRI, A.USER_ID, A.NAMA_USER, A.LOKASI_ORDNER_LEMBAR, A.LOKASI_ORDNER, A.LOKASI_ORDNER_TAHUN,
					COALESCE(G.NAMA, A.INSTANSI_ASAL) ASAL, COALESCE(H.NAMA, C.NAMA) TUJUAN, AA.TERBACA BACA, 
					AA.TERBALAS BALAS, AA.TERDISPOSISI DISPOSISI, AA.SATUAN_KERJA_ID_TUJUAN NAMA_TUJUAN
        		FROM surat_masuk A  
        		LEFT JOIN SURAT_MASUK_TUJUAN AA ON AA.SURAT_MASUK_ID = A.SURAT_MASUK_ID
				LEFT JOIN SATUAN_KERJA C ON  AA.SATUAN_KERJA_ID_TUJUAN = C.SATUAN_KERJA_ID 
				LEFT JOIN KLASIFIKASI B ON A.KLASIFIKASI_ID = B.KLASIFIKASI_ID  
				LEFT JOIN users D ON D.USER_ID = A.USER_ID
                LEFT JOIN SATUAN_KERJA E ON  A.POSISI_SURAT_MASUK = E.SATUAN_KERJA_ID
				LEFT JOIN SATUAN_KERJA F ON  A.INFO_SMS_POSISI = F.SATUAN_KERJA_ID
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
				WHERE 1=1 LIMIT 1
				) H ON H.SATUAN_KERJA_ID = A.SATUAN_KERJA_ID_TUJUAN  
				WHERE 1 = 1 AND A.SURAT_MASUK_DELETE IS NULL "; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= " ".$stat."  ".$order;
		$this->query = $str;
	
		return $this->selectLimit($str,$limit,$from); 
    }
	
	function selectByParamsOrdner($paramsArray=array(),$limit=-1,$from=-1,$stat='', $sOrder="")
	{
		$str = "SELECT  LOKASI_ORDNER, LOKASI_ORDNER_LEMBAR, LOKASI_ORDNER_LEMBAR_JUMLAH, LOKASI_ORDNER_LEMBAR_BARU, LOKASI_ORDNER_BARU, LOKASI_ORDNER_TAHUN
				FROM
				(
					SELECT MAX(LOKASI_ORDNER) LOKASI_ORDNER, MAX(LOKASI_ORDNER_LEMBAR) LOKASI_ORDNER_LEMBAR, COUNT(LOKASI_ORDNER_LEMBAR) LOKASI_ORDNER_LEMBAR_JUMLAH, LOKASI_ORDNER_TAHUN,
					CASE 
					WHEN COUNT(LOKASI_ORDNER_LEMBAR) >= 2 AND MAX(LOKASI_ORDNER_LEMBAR) = 100 THEN 1 
					WHEN COUNT(LOKASI_ORDNER_LEMBAR) >= 2 THEN MAX(LOKASI_ORDNER_LEMBAR) + 1
					ELSE MAX(LOKASI_ORDNER_LEMBAR) END LOKASI_ORDNER_LEMBAR_BARU,
					CASE
					WHEN COUNT(LOKASI_ORDNER_LEMBAR) >= 2 AND MAX(LOKASI_ORDNER_LEMBAR) = 100 THEN MAX(LOKASI_ORDNER) + 1 
					ELSE MAX(LOKASI_ORDNER) END LOKASI_ORDNER_BARU
					FROM surat_masuk
					WHERE LOKASI_ORDNER_LEMBAR IS NOT NULL AND SURAT_MASUK_DELETE IS NULL 
					GROUP BY LOKASI_ORDNER_LEMBAR, LOKASI_ORDNER_TAHUN, LOKASI_SIMPAN, LOKASI_ORDNER
					ORDER BY LOKASI_ORDNER_LEMBAR DESC
				) A
				WHERE 1=1
			   "; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= " ".$stat." ".$sOrder;
		$this->query = $str;
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
					FROM surat_masuk A
					WHERE 1=1 AND A.SURAT_MASUK_DELETE IS NULL ".$statement."
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
	
    function selectByParamsUser($paramsArray=array(),$limit=-1,$from=-1, $statement="", $id="", $order="")
	{
		$str = "
				  SELECT DISTINCT
									CASE
									WHEN C.STATUS_KEMBALI = 1 AND C.SATUAN_KERJA_ID_TUJUAN = '".$id."' AND COALESCE (C.TERDISPOSISI, 0) = 0 THEN CASE COALESCE(C.TERBACA,0) WHEN 0 THEN 'belum di baca' ELSE 'telah di baca' END
									WHEN AA.SATUAN_KERJA_ID_TUJUAN = '".$id."' AND COALESCE (AA.TERDISPOSISI, 0) = 0 THEN CASE COALESCE(AA.TERBACA,0) WHEN 0 THEN 'belum di baca' ELSE 'telah di baca' END
									WHEN AA.SATUAN_KERJA_ID_TUJUAN = '".$id."' AND COALESCE (AA.TERDISPOSISI, 0) = 1 THEN 'terdisposisi'
									WHEN C.SATUAN_KERJA_ID_TUJUAN = '".$id."' AND COALESCE (C.TERDISPOSISI, 0) = 1 THEN 'terdisposisi'
									WHEN AA.SATUAN_KERJA_ID_TUJUAN = '".$id."' THEN 'Surat Masuk'
									ELSE 'Disposisi'
								 END STATUS_SURAT_INFO,
									C.STATUS_KEMBALI, C.SATUAN_KERJA_ID_ASAL, C.SATUAN_KERJA_ID_TUJUAN, B.SATUAN_KERJA_ID, G.NAMA ASAL, H.NAMA TUJUAN,
									A.SURAT_MASUK_ID, A.TAHUN, A.NO_AGENDA, A.NOMOR, 
									CASE WHEN C.STATUS_KEMBALI = 1 THEN 'Disposisi' WHEN AA.SATUAN_KERJA_ID_TUJUAN = '".$id."' THEN 'Surat Masuk' ELSE 'Disposisi' END JENIS_SURAT,
									A.TANGGAL, TO_CHAR(C.TANGGAL_DISPOSISI, 'YYYY-MM-DD HH24:MI') TANGGAL_DISPOSISI,
									CASE WHEN C.STATUS_KEMBALI = 1 THEN 1 WHEN C.SATUAN_KERJA_ID_ASAL = '".$id."' THEN 1 ELSE 0 END STATUS_DISPOSISI,
									CASE 
									WHEN C.STATUS_KEMBALI = 1 THEN COALESCE (C.TERDISPOSISI, 0)
									WHEN AA.SATUAN_KERJA_ID_TUJUAN = '".$id."' THEN
										(SELECT COALESCE (X.TERDISPOSISI, 0) FROM surat_masuk X WHERE X.SURAT_MASUK_ID=A.SURAT_MASUK_ID) 
									ELSE 
										(SELECT COALESCE (X.TERDISPOSISI, 0) FROM DISPOSISI X WHERE X.SATUAN_KERJA_ID_TUJUAN = '".$id."' AND X.SURAT_MASUK_ID=A.SURAT_MASUK_ID  LIMIT 1) 
									END
									N_DISPOSISI, 
									CASE 
									WHEN C.SATUAN_KERJA_ID_TUJUAN = '".$id."' AND COALESCE (C.TERDISPOSISI, 0) = 0
									THEN 
									CASE COALESCE (C.TERBACA, 0) WHEN 0 THEN 1 ELSE 0 END
									WHEN AA.SATUAN_KERJA_ID_TUJUAN = '".$id."' AND COALESCE (AA.TERDISPOSISI, 0) = 0
									THEN CASE COALESCE (AA.TERBACA, 0) WHEN 0 THEN 0 ELSE 1 END
									END BACA_DISPOSISI,
									CASE 
									WHEN C.STATUS_KEMBALI = 1 THEN 1
									WHEN AA.SATUAN_KERJA_ID_TUJUAN = '".$id."' THEN 0 ELSE 1 END BACA_DISPOSISIBAK, 
									CASE 
									WHEN C.STATUS_KEMBALI = 1 THEN COALESCE (C.TERBACA, 0) 
									WHEN AA.SATUAN_KERJA_ID_TUJUAN = '".$id."' THEN COALESCE (AA.TERBACA, 0)
									ELSE  COALESCE (C.TERBACA, 0)
									END N_BACA,
									CASE 
									WHEN C.STATUS_KEMBALI = 1 THEN COALESCE (C.TERBACA, 0) 
									WHEN AA.SATUAN_KERJA_ID_TUJUAN = '".$id."' THEN COALESCE (AA.TERBACA, 0)
									ELSE  COALESCE (C.TERBACA, 0)
									END STATUS_BACA,
									CASE WHEN D.SURAT_KELUAR_ID IS NULL THEN 0 ELSE 1 END STATUS_BALAS, A.PERIHAL,
									A.INSTANSI_ASAL, B.NAMA SATUAN_KERJA_TUJUAN, 
									(SELECT COUNT(FEEDBACK_ID) FROM FEEDBACK X WHERE X.SURAT_MASUK_ID = A.SURAT_MASUK_ID) FEEDBACK,
								CASE WHEN C.TANGGAL_BATAS IS NULL THEN 
										CASE WHEN TO_CHAR(A.TANGGAL_BATAS,'YYYY-MM-DD') < TO_CHAR(CURRENT_DATE,'YYYY-MM-DD') AND COALESCE(AA.TERBACA, 0) = 0 THEN 0 
										WHEN TO_CHAR(A.TANGGAL_BATAS,'YYYY-MM-DD') >= TO_CHAR(CURRENT_DATE,'YYYY-MM-DD') AND COALESCE(AA.TERBACA, 0) = 0 THEN 1 
										ELSE 2 END  
									ELSE
										CASE WHEN TO_CHAR(C.TANGGAL_BATAS,'YYYY-MM-DD') < TO_CHAR(CURRENT_DATE,'YYYY-MM-DD') AND COALESCE(C.TERBACA, 0) = 0 AND C.SATUAN_KERJA_ID_TUJUAN = '".$id."' THEN 0 
										WHEN TO_CHAR(C.TANGGAL_BATAS,'YYYY-MM-DD') >=  TO_CHAR(CURRENT_DATE,'YYYY-MM-DD') AND COALESCE(C.TERBACA, 0) = 0  AND C.SATUAN_KERJA_ID_TUJUAN = '".$id."' THEN 1             
										ELSE 2 END          
									END REMINTEN, A.TANGGAL_BATAS,
									D.SURAT_KELUAR_ID
							  FROM surat_masuk A 
							  	   LEFT JOIN SURAT_MASUK_TUJUAN AA ON AA.SURAT_MASUK_ID = A.SURAT_MASUK_ID
								   LEFT JOIN SATUAN_KERJA B ON A.SATUAN_KERJA_ID_TUJUAN = B.SATUAN_KERJA_ID 
								   LEFT JOIN disposisi C ON A.SURAT_MASUK_ID = C.SURAT_MASUK_ID 
								   AND ((C.SATUAN_KERJA_ID_ASAL = '".$id."' AND C.STATUS_KEMBALI = '1') OR (C.SATUAN_KERJA_ID_TUJUAN = '".$id."')) AND C.SATUAN_KERJA_ID_TUJUAN = '".$id."'
								   LEFT JOIN SURAT_KELUAR D ON A.SURAT_MASUK_ID = D.SURAT_MASUK_ID 
								   LEFT JOIN
								   (
								   SELECT A.NAMA || '-' || B.NAMA NAMA, A.SATUAN_KERJA_ID, B.USER_ID
								   FROM satuan_kerja A
								   INNER JOIN users B ON A.SATUAN_KERJA_ID = B.SATUAN_KERJA_ID
								   WHERE 1=1
								   ) G ON G.SATUAN_KERJA_ID = C.SATUAN_KERJA_ID_ASAL AND C.USER_ID = G.USER_ID
								   LEFT JOIN
								   (
								   SELECT A.NAMA || '-' || B.NAMA NAMA1, A.NAMA, A.SATUAN_KERJA_ID, B.USER_ID
								   FROM satuan_kerja A
								   INNER JOIN users B ON A.SATUAN_KERJA_ID = B.SATUAN_KERJA_ID
								   WHERE 1=1 LIMIT 1
								   ) H ON H.SATUAN_KERJA_ID = C.SATUAN_KERJA_ID_TUJUAN  
							  WHERE 1 = 1 AND A.SURAT_MASUK_DELETE IS NULL 
									AND (AA.SATUAN_KERJA_ID_TUJUAN = '".$id."' OR C.SATUAN_KERJA_ID_TUJUAN = '".$id."')
									   ".$statement;
			   //AND A.SURAT_MASUK_ID IN (2011, 2012)
			 	//AND (C.SATUAN_KERJA_ID_ASAL = ".$id." OR C.SATUAN_KERJA_ID_TUJUAN = ".$id.") OR (STATUS_KEMBALI = 1 AND C.SATUAN_KERJA_ID_ASAL = ".$id.")
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= "
					GROUP BY G.NAMA, H.NAMA, C.TERDISPOSISI, C.STATUS_KEMBALI, A.SURAT_MASUK_ID, A.TAHUN, A.NO_AGENDA, A.NOMOR, A.TANGGAL,
							   A.INSTANSI_ASAL, B.NAMA, A.PERIHAL, C.TANGGAL_DISPOSISI, B.SATUAN_KERJA_ID,
							   AA.SATUAN_KERJA_ID_TUJUAN, C.SATUAN_KERJA_ID_ASAL, 
							   AA.TERBACA, D.SURAT_KELUAR_ID, A.TANGGAL_BATAS, C.TANGGAL_BATAS, C.TERBACA, C.SATUAN_KERJA_ID_TUJUAN, AA.TERDISPOSISI, C.TERDISPOSISI ".$order;
		$this->query = $str;
		// echo $str; exit;
	
		return $this->selectLimit($str,$limit,$from); 
    }
	
    function selectByParamsAdminSurat($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order="")
	{
		$str = "
				SELECT A.SURAT_MASUK_ID, A.TAHUN, A.NO_AGENDA, A.NOMOR, A.LOKASI_SIMPAN,
									UPPER(E.NAMA) PEMBUAT_SURAT, COALESCE(UPPER(F.NAMA), 'BELUM DI TENTUKAN') POSISI_SURAT,
									COALESCE((SELECT MAX(1) FROM surat_masuk_ATTACHMENT X WHERE X.SURAT_MASUK_ID = A.SURAT_MASUK_ID), 0) LAMPIRAN,
						'Surat Masuk' JENIS_SURAT, A.TANGGAL, TO_CHAR(A.TANGGAL_ENTRI, 'YYYY-MM-DD HH24:MI') TANGGAL_DITERUSKAN,
						COALESCE((SELECT MAX(1) FROM DISPOSISI X WHERE X.SURAT_MASUK_ID = A.SURAT_MASUK_ID), 0) STATUS_DISPOSISI,
						CASE WHEN D.SURAT_KELUAR_ID IS NULL THEN 0 ELSE 1 END STATUS_BALAS, COALESCE(A.TERBACA, 0) STATUS_BACA, A.PERIHAL,
						A.INSTANSI_ASAL, B.NAMA SATUAN_KERJA_TUJUAN, 
						(SELECT COUNT(FEEDBACK_ID) FROM FEEDBACK X WHERE X.SURAT_MASUK_ID = A.SURAT_MASUK_ID) FEEDBACK,
						CASE WHEN TO_CHAR(A.TANGGAL_BATAS,'YYYY-MM-DD') < TO_CHAR(CURRENT_DATE,'YYYY-MM-DD') AND COALESCE(A.TERBACA, 0) = 0 THEN 0 
							WHEN TO_CHAR(A.TANGGAL_BATAS,'YYYY-MM-DD') >= TO_CHAR(CURRENT_DATE,'YYYY-MM-DD') AND COALESCE(A.TERBACA, 0) = 0 THEN 1 
						ELSE 2 END  
						REMINTEN, A.TANGGAL_BATAS, D.SURAT_KELUAR_ID, A.PENERIMA_SURAT, A.PENERIMA_SURAT_TANGGAL
				  FROM surat_masuk A 
					   LEFT JOIN SATUAN_KERJA B ON A.SATUAN_KERJA_ID_TUJUAN = B.SATUAN_KERJA_ID 
					   LEFT JOIN SURAT_KELUAR D    ON A.SURAT_MASUK_ID = D.SURAT_MASUK_ID 
							   LEFT JOIN users E ON E.USER_ID = A.USER_ID
					   LEFT JOIN SATUAN_KERJA F ON  A.POSISI_SURAT_MASUK = F.SATUAN_KERJA_ID
				  WHERE 1 = 1 AND A.SURAT_MASUK_DELETE IS NULL 		
			   ".$statement; 
			 	
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= "
   					GROUP BY A.SURAT_MASUK_ID, A.TAHUN, A.NO_AGENDA, A.NOMOR, A.TANGGAL, A.TANGGAL_ENTRI, E.NAMA, F.NAMA,
							   A.INSTANSI_ASAL, B.NAMA, A.PERIHAL, A.LOKASI_SIMPAN,
							   A.SATUAN_KERJA_ID_TUJUAN, 
							   A.TERBACA, D.SURAT_KELUAR_ID, A.TANGGAL_BATAS, A.PENERIMA_SURAT, A.PENERIMA_SURAT_TANGGAL
		";//.$order;
		$this->query = $str;
		//echo $str; 
	
		return $this->selectLimit($str,$limit,$from); 
    }	
	

    function selectByParamsEntri($paramsArray=array(),$limit=-1,$from=-1, $statement="", $id="", $order="")
	{
		$str = "
				 SELECT DISTINCT A.SURAT_MASUK_ID, A.TAHUN, A.NO_AGENDA, A.NOMOR, A.KEPADA,
				 		COALESCE((SELECT MAX(1) FROM surat_masuk_ATTACHMENT X WHERE X.SURAT_MASUK_ID = A.SURAT_MASUK_ID), 0) LAMPIRAN,
						'Surat Masuk' JENIS_SURAT, A.TANGGAL, A.TANGGAL_DITERUSKAN,
						C.TANGGAL_DISPOSISI, '' STATUS_DISPOSISI,
						CASE WHEN D.SURAT_KELUAR_ID IS NULL THEN 0 ELSE 1 END STATUS_BALAS, COALESCE(A.TERBACA, 0) STATUS_BACA, A.PERIHAL,
						CASE 
						WHEN A.SATUAN_KERJA_ID_TUJUAN = '".$id."' THEN COALESCE (A.TERBACA, 0)
						ELSE 0
						END N_BACA,
						A.INSTANSI_ASAL, B.NAMA SATUAN_KERJA_TUJUAN, 
						(SELECT COUNT(FEEDBACK_ID) FROM FEEDBACK X WHERE X.SURAT_MASUK_ID = A.SURAT_MASUK_ID) FEEDBACK,
    				    CASE WHEN C.TANGGAL_BATAS IS NULL THEN 
							CASE WHEN TO_CHAR(A.TANGGAL_BATAS,'YYYY-MM-DD') < TO_CHAR(CURRENT_DATE,'YYYY-MM-DD') AND A.TERBACA = 0 THEN 0 
							WHEN TO_CHAR(A.TANGGAL_BATAS,'YYYY-MM-DD') >= TO_CHAR(CURRENT_DATE,'YYYY-MM-DD') AND A.TERBACA = 0 THEN 1 
							ELSE 2 END  
						ELSE
							CASE WHEN TO_CHAR(C.TANGGAL_BATAS,'YYYY-MM-DD') < TO_CHAR(CURRENT_DATE,'YYYY-MM-DD') AND C.TERBACA = 0 THEN 0 
							WHEN TO_CHAR(C.TANGGAL_BATAS,'YYYY-MM-DD') >= TO_CHAR(CURRENT_DATE,'YYYY-MM-DD') AND C.TERBACA = 0 THEN 1             
							ELSE 2 END          
						END REMINTEN, A.TANGGAL_BATAS, SURAT_MASUK_STATUS, CASE WHEN SURAT_MASUK_FINAL IS NOT NULL THEN 'Sudah' ELSE 'Belum' END STATUS_ARSIP
				  FROM surat_masuk A 
					   LEFT JOIN SATUAN_KERJA B ON A.SATUAN_KERJA_ID_TUJUAN = B.SATUAN_KERJA_ID 
					   LEFT JOIN disposisi C    ON A.SURAT_MASUK_ID = C.SURAT_MASUK_ID 
					   LEFT JOIN SURAT_KELUAR D    ON A.SURAT_MASUK_ID = D.SURAT_MASUK_ID 
				  WHERE 1 = 1 AND A.SURAT_MASUK_DELETE IS NULL
			   ".$statement; 
			 	
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= "
					GROUP BY A.SURAT_MASUK_ID, A.TAHUN, A.NO_AGENDA, A.NOMOR, A.TANGGAL, A.TANGGAL_DITERUSKAN,
							   A.INSTANSI_ASAL, B.NAMA, A.PERIHAL, C.TANGGAL_DISPOSISI,
							   A.SATUAN_KERJA_ID_TUJUAN, C.SATUAN_KERJA_ID_ASAL, 
							   A.TERBACA, D.SURAT_KELUAR_ID, A.TANGGAL_BATAS, C.TANGGAL_BATAS, C.TERBACA
		".$order;
		$this->query = $str;
		// echo $str; 
	
		return $this->selectLimit($str,$limit,$from); 
    }	
   	
	function selectByParamsCapaianEntri($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order="")
	{
		$str = "
				 
				 SELECT DISTINCT A.SURAT_MASUK_ID, A.TAHUN, A.NO_AGENDA, A.NOMOR, 
				 		COALESCE((SELECT MAX(1) FROM surat_masuk_ATTACHMENT X WHERE X.SURAT_MASUK_ID = A.SURAT_MASUK_ID), 0) LAMPIRAN,
						'Surat Masuk' JENIS_SURAT, A.TANGGAL, A.TANGGAL_DITERUSKAN,
						C.TANGGAL_DISPOSISI, '' STATUS_DISPOSISI,
						CASE WHEN D.SURAT_KELUAR_ID IS NULL THEN 0 ELSE 1 END STATUS_BALAS, COALESCE(A.TERBACA, 0) STATUS_BACA, A.PERIHAL,
						COALESCE(A.TERBACA, 0) N_BACA,
						A.INSTANSI_ASAL, B.NAMA SATUAN_KERJA_TUJUAN, 
						(SELECT COUNT(FEEDBACK_ID) FROM FEEDBACK X WHERE X.SURAT_MASUK_ID = A.SURAT_MASUK_ID) FEEDBACK,
    				    CASE WHEN C.TANGGAL_BATAS IS NULL THEN 
							CASE WHEN TO_CHAR(A.TANGGAL_BATAS,'YYYY-MM-DD') < TO_CHAR(CURRENT_DATE,'YYYY-MM-DD') AND A.TERBACA = 0 THEN 0 
							WHEN TO_CHAR(A.TANGGAL_BATAS,'YYYY-MM-DD') >= TO_CHAR(CURRENT_DATE,'YYYY-MM-DD') AND A.TERBACA = 0 THEN 1 
							ELSE 2 END  
						ELSE
							CASE WHEN TO_CHAR(C.TANGGAL_BATAS,'YYYY-MM-DD') < TO_CHAR(CURRENT_DATE,'YYYY-MM-DD') AND C.TERBACA = 0 THEN 0 
							WHEN TO_CHAR(C.TANGGAL_BATAS,'YYYY-MM-DD') >= TO_CHAR(CURRENT_DATE,'YYYY-MM-DD') AND C.TERBACA = 0 THEN 1             
							ELSE 2 END          
						END REMINTEN, A.TANGGAL_BATAS
				  FROM surat_masuk A 
					   LEFT JOIN SATUAN_KERJA B ON A.SATUAN_KERJA_ID_TUJUAN = B.SATUAN_KERJA_ID 
					   LEFT JOIN disposisi C    ON A.SURAT_MASUK_ID = C.SURAT_MASUK_ID 
					   LEFT JOIN SURAT_KELUAR D    ON A.SURAT_MASUK_ID = D.SURAT_MASUK_ID 
				  WHERE 1 = 1 AND A.SURAT_MASUK_DELETE IS NULL
				  ".$statement; 
			 	
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= "
					GROUP BY A.SURAT_MASUK_ID, A.TAHUN, A.NO_AGENDA, A.NOMOR, A.TANGGAL, A.TANGGAL_DITERUSKAN,
							   A.INSTANSI_ASAL, B.NAMA, A.PERIHAL, C.TANGGAL_DISPOSISI,
							   A.SATUAN_KERJA_ID_TUJUAN, C.SATUAN_KERJA_ID_ASAL, 
							   A.TERBACA, D.SURAT_KELUAR_ID, A.TANGGAL_BATAS, C.TANGGAL_BATAS, C.TERBACA
		".$order;
		$this->query = $str;
		//echo $str; 
	
		return $this->selectLimit($str,$limit,$from); 
    }
	
	function selectByParamsTree($paramsArray=array(),$limit=-1,$from=-1,$statement="", $order="")
	{
		$str = "SELECT disposisi_id_generate('0', SURAT_MASUK_ID) TREE_ID, '0' TREE_PARENT_ID
					FROM surat_masuk a
				WHERE 1 = 1
			"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$order;
		$this->query = $str;
		return $this->selectLimit($str,$limit,$from); 
    }
	
	function getCountByParams($paramsArray=array(),$statement="")
	{
		$str = "SELECT COUNT(SURAT_MASUK_ID) AS ROWCOUNT FROM surat_masuk WHERE 1=1 "; 
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
	
	function getCountByParamsCapaianKinerja($paramsArray=array(), $statement='', $json="")
	{
		$str = "
				SELECT COUNT(*) ROWCOUNT
				FROM USERS A
				INNER JOIN
				(
					SELECT A.USER_ID, COUNT(1) JUMLAH_DATA 
					FROM surat_masuk A
					WHERE 1=1 AND A.SURAT_MASUK_DELETE IS NULL ".$statement."
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
				 SELECT A.SURAT_MASUK_ID
				  FROM surat_masuk A 
					   LEFT JOIN SATUAN_KERJA B ON A.SATUAN_KERJA_ID_TUJUAN = B.SATUAN_KERJA_ID 
					   LEFT JOIN disposisi C    ON A.SURAT_MASUK_ID = C.SURAT_MASUK_ID 
					   LEFT JOIN SURAT_KELUAR D    ON A.SURAT_MASUK_ID = D.SURAT_MASUK_ID 
				  WHERE 1 = 1 AND A.SURAT_MASUK_DELETE IS NULL ".$statement."
				  GROUP BY A.SURAT_MASUK_ID) A
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
				 FROM
				 (
				 SELECT A.SURAT_MASUK_ID
				  FROM surat_masuk A 
					   LEFT JOIN SATUAN_KERJA B ON A.SATUAN_KERJA_ID_TUJUAN = B.SATUAN_KERJA_ID 
					   LEFT JOIN disposisi C ON A.SURAT_MASUK_ID = C.SURAT_MASUK_ID 
					   LEFT JOIN SURAT_KELUAR D ON A.SURAT_MASUK_ID = D.SURAT_MASUK_ID 
				  WHERE 1 = 1 AND A.SURAT_MASUK_DELETE IS NULL ".$statement." 
				  GROUP BY A.SURAT_MASUK_ID) A
		"; 
		while(list($key,$val)=each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		$str .= ' ';
		
		$this->select($str); 
		$this->query = $str;
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0; 
    }
	
    function getCountByParamsUser($paramsArray=array(), $statement='', $id='')
	{
		$str = " SELECT COUNT(*) ROWCOUNT
				 FROM
				 (
				 SELECT A.SURAT_MASUK_ID
				  FROM surat_masuk A 
				  	   LEFT JOIN SURAT_MASUK_TUJUAN AA ON AA.SURAT_MASUK_ID = A.SURAT_MASUK_ID
					   LEFT JOIN SATUAN_KERJA B ON A.SATUAN_KERJA_ID_TUJUAN = B.SATUAN_KERJA_ID 
					   LEFT JOIN disposisi C ON A.SURAT_MASUK_ID = C.SURAT_MASUK_ID 
					   AND ((C.SATUAN_KERJA_ID_ASAL = '".$id."' AND C.STATUS_KEMBALI = '1') OR (C.SATUAN_KERJA_ID_TUJUAN = '".$id."')) AND C.SATUAN_KERJA_ID_TUJUAN = '".$id."'
					   LEFT JOIN SURAT_KELUAR D    ON A.SURAT_MASUK_ID = D.SURAT_MASUK_ID 
					   LEFT JOIN
					   (
					   SELECT A.NAMA || '-' || B.NAMA NAMA, A.SATUAN_KERJA_ID, B.USER_ID
					   FROM satuan_kerja A
					   INNER JOIN users B ON A.SATUAN_KERJA_ID = B.SATUAN_KERJA_ID
					   WHERE 1=1
					   ) G ON G.SATUAN_KERJA_ID = C.SATUAN_KERJA_ID_ASAL AND C.USER_ID = G.USER_ID
					   LEFT JOIN
					   (
					   SELECT A.NAMA || '-' || B.NAMA NAMA1, A.NAMA, A.SATUAN_KERJA_ID, B.USER_ID
					   FROM satuan_kerja A
					   INNER JOIN users B ON A.SATUAN_KERJA_ID = B.SATUAN_KERJA_ID
					   WHERE 1=1 LIMIT 1
					   ) H ON H.SATUAN_KERJA_ID = C.SATUAN_KERJA_ID_TUJUAN
				  WHERE 1 = 1 AND A.SURAT_MASUK_DELETE IS NULL 
						AND (A.SATUAN_KERJA_ID_TUJUAN = '".$id."' OR C.SATUAN_KERJA_ID_TUJUAN = '".$id."') ".$statement." 
				  GROUP BY A.SURAT_MASUK_ID) A
		"; 
		while(list($key,$val)=each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		$str .= ' ';
		
		$this->query = $str;
		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0; 
    }
	
    function getCountByParamsAdminSurat($paramsArray=array(), $statement='', $id='')
	{
		$str = " SELECT COUNT(*) ROWCOUNT
				 FROM
				 (
				 SELECT A.SURAT_MASUK_ID
				  FROM surat_masuk A 
					   LEFT JOIN SATUAN_KERJA B ON A.SATUAN_KERJA_ID_TUJUAN = B.SATUAN_KERJA_ID 
					   LEFT JOIN disposisi C ON A.SURAT_MASUK_ID = C.SURAT_MASUK_ID 
					   LEFT JOIN SURAT_KELUAR D ON A.SURAT_MASUK_ID = D.SURAT_MASUK_ID 
				  WHERE 1 = 1 AND A.SURAT_MASUK_DELETE IS NULL 
						".$statement." 
				  GROUP BY A.SURAT_MASUK_ID) A
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
		
	function getMaxNoAgendaByParams($paramsArray=array(), $varStatement="")
	{
		$str = "SELECT MAX(NO_AGENDA) AS ROWCOUNT FROM surat_masuk WHERE SURAT_MASUK_ID IS NOT NULL "; 
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

  function insertBarang()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("SURAT_MASUK_BARANG_ID", $this->getNextId("SURAT_MASUK_BARANG_ID","SURAT_MASUK_BARANG")); 
		$str = "
					INSERT INTO SURAT_MASUK_BARANG(SURAT_MASUK_BARANG_ID,SURAT_MASUK_ID, NAMA, KODE, SATUAN, QTY, KETERANGAN
            )
          VALUES (
          	'".$this->getField("SURAT_MASUK_BARANG_ID")."',					
          	'".$this->getField("SURAT_MASUK_ID")."',					
          	'".$this->getField("NAMA")."',					
          	'".$this->getField("KODE")."',					
          	'".$this->getField("SATUAN")."',					
          	".$this->getField("QTY").",					
          	'".$this->getField("KETERANGAN")."'					
				)";

		$this->query = $str;
		// $this->reqSuratMasukId = $this->getField("SURAT_MASUK_BARANG_ID");
		// $this->id = $this->getField("KLASIFIKASI_ID");
		//echo $str;
		return $this->execQuery($str);
  }
	
	function updateBarang()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "UPDATE SURAT_MASUK_BARANG 
				SET NAMA			= '".$this->getField("NAMA")."',
					KODE 	= '".$this->getField("KODE")."',
					SATUAN			= '".$this->getField("SATUAN")."',
					QTY		= ".$this->getField("QTY").",
					KETERANGAN			= '".$this->getField("KETERANGAN")."'
				where SURAT_MASUK_ID = ".$this->getField("SURAT_MASUK_ID")."
				"; 
				$this->query = $str;
		//echo $str;
		return $this->execQuery($str);
   }
	
} 
?>