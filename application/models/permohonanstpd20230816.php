<? 
/* *******************************************************************************************************
MODUL NAME 			: IMASYS
FILE NAME 			: 
AUTHOR				: 
VERSION				: 1.0
MODIFICATION DOC	:
DESCRIPTION			: 
***************************************************************************************************** */

  /***
  * Entity-base class untuk mengimplementasikan tabel AGENDA.
  * 
  ***/
  include_once("Entity.php");

  class PermohonanStpd extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function PermohonanStpd()
	{
      $this->Entity(); 
    }
	
	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("PERMOHONAN_STPD_ID", $this->getNextId("PERMOHONAN_STPD_ID","PERMOHONAN_STPD")); 		

		$str = "
		INSERT INTO PERMOHONAN_STPD 
		(
			PERMOHONAN_STPD_ID, PEMIMPIN_ID, PELAKSANA_ID, PENGAJUAN_DISIAPKAN_ID
			, PENGAJUAN_DISETUJUI_ID, REALISASI_DISIAPKAN_ID, REALISASI_MENGETAHUI_ID
			, REALISASI_DISETUJUI_ID, NOMOR, TANGGAL, DOKUMEN_ACUAN, JUMLAH_PELAKSANA
			, LOKASI_DINAS, TANGGAL_BERANGKAT, TANGGAL_KEMBALI, TOTAL_PERIODE_HARI
			, TOTAL_PERIODE_MALAM, TOTAL_HARI, STATUS_SURAT, LAST_CREATE_USER
			, LAST_CREATE_DATE,SATUAN_KERJA_ID_ASAL, TOTAL_REALISASI
		) 
		VALUES
		(
			".$this->getField("PERMOHONAN_STPD_ID")."
			, ".$this->getField("PEMIMPIN_ID")."
			, ".$this->getField("PELAKSANA_ID")."
			, ".$this->getField("PENGAJUAN_DISIAPKAN_ID")."
			, '".$this->getField("PENGAJUAN_DISETUJUI_ID")."'
			, ".$this->getField("REALISASI_DISIAPKAN_ID")."
			, ".$this->getField("REALISASI_MENGETAHUI_ID")."
			, ".$this->getField("REALISASI_DISETUJUI_ID")."
			, '".$this->getField("NOMOR")."'
			, ".$this->getField("TANGGAL")."
			, '".$this->getField("DOKUMEN_ACUAN")."'
			, ".$this->getField("JUMLAH_PELAKSANA")."
			, '".$this->getField("LOKASI_DINAS")."'
			, ".$this->getField("TANGGAL_BERANGKAT")."
			, ".$this->getField("TANGGAL_KEMBALI")."
			, ".$this->getField("TOTAL_PERIODE_HARI")."
			, ".$this->getField("TOTAL_PERIODE_MALAM")."
			, ".$this->getField("TOTAL_HARI")."
			, '".$this->getField("STATUS_SURAT")."'
			, '".$this->getField("LAST_CREATE_USER")."'
			, CURRENT_DATE
			, '".$this->getField("SATUAN_KERJA_ID_ASAL")."'
			, ".$this->getField("TOTAL_REALISASI")."
		)"; 
		$this->id = $this->getField("PERMOHONAN_STPD_ID");
		$this->query = $str;

		// echo $str;exit;
		return $this->execQuery($str);
    }

    function insertbiaya()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("PERMOHONAN_STPD_BIAYA_DINAS_ID", $this->getNextId("PERMOHONAN_STPD_BIAYA_DINAS_ID","PERMOHONAN_STPD_BIAYA_DINAS")); 		

		$str = "
		INSERT INTO PERMOHONAN_STPD_BIAYA_DINAS 
		(
			PERMOHONAN_STPD_BIAYA_DINAS_ID, PERMOHONAN_STPD_ID, ALOKASI_BIAYA
			, PENGAJUAN_BIAYA, REALISASI,KELOMPOK_ID,KELOMPOK_ORANG
		) 
		VALUES
		(
			".$this->getField("PERMOHONAN_STPD_BIAYA_DINAS_ID")."
			, ".$this->getField("PERMOHONAN_STPD_ID")."
			, '".$this->getField("ALOKASI_BIAYA")."'
			, ".$this->getField("PENGAJUAN_BIAYA")."
			, ".$this->getField("REALISASI")."
			, ".$this->getField("KELOMPOK_ID")."
			, ".$this->getField("KELOMPOK_ORANG")."
		)"; 
		$this->query = $str;
		// echo $str;exit;
		return $this->execQuery($str);
    }

    function updaterealisasibiaya()
	{
		$str = "
		UPDATE PERMOHONAN_STPD_BIAYA_DINAS
		SET
			REALISASI= ".$this->getField("REALISASI")."
		WHERE PERMOHONAN_STPD_BIAYA_DINAS_ID= '".$this->getField("PERMOHONAN_STPD_BIAYA_DINAS_ID")."'
		"; 
		$this->query = $str;
		// echo $str;exit;
		return $this->execQuery($str);
    }

    function insertuntuk()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("PERMOHONAN_STPD_UNTUK_ID", $this->getNextId("PERMOHONAN_STPD_UNTUK_ID","PERMOHONAN_STPD_UNTUK")); 		

		$str = "
		INSERT INTO PERMOHONAN_STPD_UNTUK 
		(
			PERMOHONAN_STPD_UNTUK_ID, PERMOHONAN_STPD_ID, SATUAN_KERJA_ID,STATUS
		) 
		VALUES 
		(
			".$this->getField("PERMOHONAN_STPD_UNTUK_ID")."
			, ".$this->getField("PERMOHONAN_STPD_ID")."
			, '".$this->getField("SATUAN_KERJA_ID")."'
			, ".$this->getField("STATUS")."
		)"; 
		$this->query = $str;
		// echo $str;exit;
		return $this->execQuery($str);
    }

    function update()
	{
		$str = "
		UPDATE PERMOHONAN_STPD
		SET
			PEMIMPIN_ID=".$this->getField("PEMIMPIN_ID")."
			, PELAKSANA_ID= ".$this->getField("PELAKSANA_ID")."
			, PENGAJUAN_DISIAPKAN_ID= ".$this->getField("PENGAJUAN_DISIAPKAN_ID")."
			, PENGAJUAN_DISETUJUI_ID= '".$this->getField("PENGAJUAN_DISETUJUI_ID")."'
			, REALISASI_DISIAPKAN_ID= ".$this->getField("REALISASI_DISIAPKAN_ID")."
			, REALISASI_MENGETAHUI_ID= ".$this->getField("REALISASI_MENGETAHUI_ID")."
			, REALISASI_DISETUJUI_ID= ".$this->getField("REALISASI_DISETUJUI_ID")."
			, NOMOR= '".$this->getField("NOMOR")."'
			, TANGGAL= ".$this->getField("TANGGAL")."
			, DOKUMEN_ACUAN= '".$this->getField("DOKUMEN_ACUAN")."'
			, JUMLAH_PELAKSANA= ".$this->getField("JUMLAH_PELAKSANA")."
			, LOKASI_DINAS= '".$this->getField("LOKASI_DINAS")."'
			, TANGGAL_BERANGKAT= ".$this->getField("TANGGAL_BERANGKAT")."
			, TANGGAL_KEMBALI= ".$this->getField("TANGGAL_KEMBALI")."
			, TOTAL_PERIODE_HARI= ".$this->getField("TOTAL_PERIODE_HARI")."
			, TOTAL_PERIODE_MALAM= ".$this->getField("TOTAL_PERIODE_MALAM")."
			, TOTAL_HARI= ".$this->getField("TOTAL_HARI")."
			, STATUS_SURAT= '".$this->getField("STATUS_SURAT")."'
			, LAST_UPDATE_USER= '".$this->getField("LAST_UPDATE_USER")."'
			, LAST_UPDATE_DATE= CURRENT_DATE
			, SATUAN_KERJA_ID_ASAL= '".$this->getField("SATUAN_KERJA_ID_ASAL")."'
			, TOTAL_REALISASI= ".$this->getField("TOTAL_REALISASI")."
			, KET_REALISASI= '".$this->getField("KET_REALISASI")."'
		WHERE PERMOHONAN_STPD_ID= '".$this->getField("PERMOHONAN_STPD_ID")."'
		"; 
		$this->query = $str;
		// echo $str;exit;
		return $this->execQuery($str);
    }

    function updateuntuk()
	{
		$str = "
		UPDATE PERMOHONAN_STPD_UNTUK
		SET
			PERMOHONAN_STPD_ID=   ".$this->getField("PERMOHONAN_STPD_ID")."
			, SATUAN_KERJA_ID= '".$this->getField("SATUAN_KERJA_ID")."'
			, STATUS= ".$this->getField("STATUS")."
		WHERE PERMOHONAN_STPD_UNTUK_ID= '".$this->getField("PERMOHONAN_STPD_UNTUK_ID")."'
		"; 
		$this->query = $str;
		//echo $str;
		return $this->execQuery($str);
    }

    function updatestatus()
	{
		$str = "
		UPDATE PERMOHONAN_STPD
		SET
			STATUS_SURAT= '".$this->getField("STATUS_SURAT")."'
			, KET_REALISASI= '".$this->getField("KET_REALISASI")."'
			, FILE_REALISASI= '".$this->getField("FILE_REALISASI")."'
			, TOTAL_REALISASI= ".$this->getField("TOTAL_REALISASI")."
			, LOKASI_DINAS= '".$this->getField("LOKASI_DINAS")."'
			, TANGGAL_KEMBALI= ".$this->getField("TANGGAL_KEMBALI")."
			, TANGGAL_BERANGKAT= ".$this->getField("TANGGAL_BERANGKAT")."
		WHERE  PERMOHONAN_STPD_ID     = '".$this->getField("PERMOHONAN_STPD_ID")."'
		"; 
		$this->query = $str;
		// echo $str;exit;
		return $this->execQuery($str);
    }

    function updatestatusdetil()
	{
		$str = "
		UPDATE PERMOHONAN_STPD_UNTUK
		SET
			STATUS= '".$this->getField("STATUS")."'
		WHERE PERMOHONAN_STPD_ID= '".$this->getField("PERMOHONAN_STPD_ID")."'
		AND SATUAN_KERJA_ID = '".$this->getField("SATUAN_KERJA_ID")."'
		"; 
		$this->query = $str;
		// echo $str;exit;
		return $this->execQuery($str);
    }
   
	function delete()
	{
        $str = "
        DELETE FROM PERMOHONAN_STPD
        WHERE 
        PERMOHONAN_STPD_ID = ".$this->getField("PERMOHONAN_STPD_ID")."";

		$this->query = $str;
        return $this->execQuery($str);
    }

    function deletebiaya()
	{
        $str = "DELETE FROM PERMOHONAN_STPD_BIAYA_DINAS
                WHERE 
                  PERMOHONAN_STPD_ID = ".$this->getField("PERMOHONAN_STPD_ID").""; 
				  
		$this->query = $str;
        return $this->execQuery($str);
    }

    function deletebiayabaris()
	{
        $str = "DELETE FROM PERMOHONAN_STPD_BIAYA_DINAS
                WHERE 
                  PERMOHONAN_STPD_BIAYA_DINAS_ID = ".$this->getField("PERMOHONAN_STPD_BIAYA_DINAS_ID").""; 
				  
		$this->query = $str;
        return $this->execQuery($str);
    }

    function deleteuntuk()
	{
        $str = "DELETE FROM PERMOHONAN_STPD_UNTUK
                WHERE 
                  PERMOHONAN_STPD_ID = ".$this->getField("PERMOHONAN_STPD_ID").""; 
				  
		$this->query = $str;
        return $this->execQuery($str);
    }

    /** 
    * Cari record berdasarkan array parameter dan limit tampilan 
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","IJIN_USAHA_ID"=>"yyy") 
    * @param int limit Jumlah maksimal record yang akan diambil 
    * @param int from Awal record yang diambil 
    * @return boolean True jika sukses, false jika tidak 
    **/ 

    function selectsimple($paramsArray=array(),$limit=-1,$from=-1, $statement="")
	{
		$str = "
		SELECT 
		A.*
		FROM PERMOHONAN_STPD A
		WHERE 1=1
		";
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ORDER BY A.PERMOHONAN_STPD_ID ASC";
		$this->query = $str;
		return $this->selectLimit($str,$limit,$from); 
    }

    function selectByParamsDraft($paramsArray=array(),$limit=-1,$from=-1, $statement="")
	{
		$str = "
		SELECT
			sk.nama nama_ketua, p.jabatan jabatan_ketua, A1.KELOMPOK_JABATAN PEMIMPIN_KELOMPOK
			, TO_CHAR(TANGGAL_BERANGKAT::DATE, 'DD-MM-YYYY HH:MM') AS DATESTART
			, TO_CHAR(TANGGAL_KEMBALI::DATE, 'DD-MM-YYYY HH:MM') AS DATEEND
			, A.*
		FROM PERMOHONAN_STPD A
		LEFT JOIN
		(
			SELECT SATUAN_KERJA_ID, KELOMPOK_JABATAN
			FROM SATUAN_KERJA_FIX
		) A1 ON A.PEMIMPIN_ID = A1.SATUAN_KERJA_ID
		LEFT JOIN satuan_kerja SK on sk.satuan_kerja_id = a.pemimpin_id
		LEFT JOIN pegawai p on p.pegawai_id = sk.nip
		WHERE 1=1
		";
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ORDER BY A.PERMOHONAN_STPD_ID ASC";
		$this->query = $str;
		return $this->selectLimit($str,$limit,$from); 
    }

    function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement="")
	{
		$str = "
		SELECT 
			B.SATUAN_KERJA_ID 
			, A.*
		FROM PERMOHONAN_STPD A
		INNER JOIN PERMOHONAN_STPD_UNTUK B ON B.PERMOHONAN_STPD_ID = A.PERMOHONAN_STPD_ID
		WHERE 1=1
		"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ORDER BY A.PERMOHONAN_STPD_ID ASC";
		$this->query = $str;
		return $this->selectLimit($str,$limit,$from); 
    }

    function selectByParamsKeluar($paramsArray=array(),$limit=-1,$from=-1, $statement="")
	{
		$str = "
		SELECT 
			A.*,
			case when a.status_surat='SETUJUDRAFT' then 'DRAFT'
			when a.status_surat='SETUJU' then 'DRAFT'
			when a.status_surat='SETUJUREVISI' then 'REVISI'
			else a.status_surat
			end STATUS_REALISASI
		FROM PERMOHONAN_STPD A
		left join satuan_kerja sk on sk.satuan_kerja_id = a.PEMIMPIN_ID
		left join permohonan_stpd_untuk psu on a.permohonan_stpd_id =psu.permohonan_stpd_id
		left join satuan_kerja sk1 on sk1.satuan_kerja_id = psu.satuan_kerja_id
		WHERE 1=1
		"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ORDER BY A.PERMOHONAN_STPD_ID ASC";
		$this->query = $str;
		return $this->selectLimit($str,$limit,$from); 
    }


    function selectByParamsBiaya($paramsArray=array(),$limit=-1,$from=-1, $statement="")
	{
		$str = "
		SELECT
		CASE WHEN COALESCE(KELOMPOK_ORANG,0) > 0 THEN (PENGAJUAN_BIAYA / COALESCE(KELOMPOK_ORANG,0)) ELSE 0 END BIAYA_AWAL
		, A.* 
		FROM PERMOHONAN_STPD_BIAYA_DINAS A 
		WHERE 1=1
		"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ORDER BY A.PERMOHONAN_STPD_ID ASC";
		$this->query = $str;
		return $this->selectLimit($str,$limit,$from); 
    }

    function selectByParamsUntuk($paramsArray=array(),$limit=-1,$from=-1, $statement="")
	{
		$str = "
		SELECT 
			B.NAMA, B.KELOMPOK_JABATAN, b.nama_pegawai
			, A.*
		FROM PERMOHONAN_STPD_UNTUK A 
		INNER JOIN SATUAN_KERJA B ON A.SATUAN_KERJA_ID = B.SATUAN_KERJA_ID
		WHERE 1=1
		"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ORDER BY A.SATUAN_KERJA_ID ASC";
		$this->query = $str;
		return $this->selectLimit($str,$limit,$from); 
    }

    function selectmengetahuisetting($paramsArray=array(),$limit=-1,$from=-1, $statement="", $sorder= "ORDER BY A.SETTING_MENGETAHUI_ID, A.URUT")
	{
		$str = "
		SELECT
			A1.JABATAN, A1.NAMA_PEGAWAI
			, A.*
		FROM setting_mengetahui_detil A
		INNER JOIN satuan_kerja_fix A1 ON A.SATUAN_KERJA_ID = A1.SATUAN_KERJA_ID
		WHERE 1=1
		"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ";
		$this->query = $str;
		return $this->selectLimit($str,$limit,$from); 
    }

    function selectrealisasiparaf($paramsArray=array(),$limit=-1,$from=-1, $statement="", $sorder= "ORDER BY A.PERMOHONAN_STPD_ID, A.NO_URUT DESC")
	{
		$str = "
		SELECT 
			A.*
		FROM permohonan_stpd_realisasi_paraf A
		WHERE 1=1
		"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$sorder;
		$this->query = $str;
		// echo $str;exit;

		return $this->selectLimit($str,$limit,$from); 
    }

    function selectByParamsStatus($paramsArray=array(),$limit=-1,$from=-1, $statement="")
	{
		$str = "
		SELECT 
			B.SATUAN_KERJA_ID_INFO, REPLACE(B.SATUAN_KERJA_ID_NAMA, ',', '<br/>') SATUAN_KERJA_ID_NAMA
			, CASE
			WHEN B1.JENIS_SURAT = 1 THEN 'Permohonan'
			WHEN B1.JENIS_SURAT = 2 THEN 'Realisasi'
			ELSE '' END JENIS_SURAT_NAMA
			, CASE
			WHEN UPPER(A.STATUS_SURAT) IN (UPPER('DRAFT')) THEN 'Draft' 
			WHEN UPPER(A.STATUS_SURAT) IN (UPPER('REVISI')) THEN 'Revisi'
			WHEN UPPER(A.STATUS_SURAT) = UPPER('KIRIM') THEN 'Terkirim'
			WHEN UPPER(A.STATUS_SURAT) IN (UPPER('SETUJU')) THEN 'Di Setujui Draft'
			WHEN UPPER(A.STATUS_SURAT) IN (UPPER('SETUJUDRAFT')) THEN 'Di Setujui Draft'
			WHEN UPPER(A.STATUS_SURAT) IN (UPPER('SETUJUREVISI')) THEN 'Di Setujui Revisi'
			WHEN UPPER(A.STATUS_SURAT) = UPPER('SETUJUKIRIM') THEN 'Terkirim'
			WHEN UPPER(A.STATUS_SURAT) = UPPER('SELESAI') THEN ''
			ELSE 'BELUM DI TENTUKAN' END STATUS_NAMA
			, B1.POSISI_SURAT_INFO
			, A.*
		FROM PERMOHONAN_STPD A
		LEFT JOIN 
		(
			SELECT
				A.PERMOHONAN_STPD_ID
				, STRING_AGG(A.SATUAN_KERJA_ID::text, ', ') AS SATUAN_KERJA_ID_INFO
				, STRING_AGG(B.NAMA::text, ', ') AS SATUAN_KERJA_ID_NAMA
			FROM PERMOHONAN_STPD_UNTUK A
			INNER JOIN SATUAN_KERJA B ON B.SATUAN_KERJA_ID = A.SATUAN_KERJA_ID
			WHERE A.STATUS IS NULL
			GROUP BY A.PERMOHONAN_STPD_ID
		) B ON B.PERMOHONAN_STPD_ID = A.PERMOHONAN_STPD_ID
		LEFT JOIN PERMOHONAN_STPD_DETIL B1 ON B1.PERMOHONAN_STPD_ID = A.PERMOHONAN_STPD_ID
		WHERE 1=1
		"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ";
		$this->query = $str;
		// echo $str;exit;
		return $this->selectLimit($str,$limit,$from); 
    }

    function selectaksesparaf($paramsArray=array(),$limit=-1,$from=-1, $statement="")
	{
		$str = "
		SELECT A.*
		FROM PERMOHONAN_STPD_REALISASI_PARAF A
		INNER JOIN
		(
			SELECT
			PERMOHONAN_STPD_ID, COALESCE(NEXT_URUT,1) NEXT_URUT
			FROM
			(
				SELECT PERMOHONAN_STPD_ID, NEXT_URUT FROM PERMOHONAN_STPD_REALISASI_PARAF GROUP BY PERMOHONAN_STPD_ID, NEXT_URUT
			) A1
		) A1 ON A.PERMOHONAN_STPD_ID = A1.PERMOHONAN_STPD_ID AND A.NO_URUT = A1.NEXT_URUT
		WHERE 1=1
		"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ";
		$this->query = $str;
		// echo $str;exit;
		return $this->selectLimit($str,$limit,$from); 
    }

    function selectByParamsPersetujuan($paramsArray=array(),$limit=-1,$from=-1, $statement="")
	{
		$str = "
		SELECT
			B.SATUAN_KERJA_ID_INFO, sk.nama nama_pemimpin
			, REPLACE(B.SATUAN_KERJA_ID_NAMA, ',', '<br/>') SATUAN_KERJA_ID_NAMA
			, A.*
		FROM PERMOHONAN_STPD A
		LEFT JOIN
		(
			SELECT A.*
			FROM PERMOHONAN_STPD_REALISASI_PARAF A
			INNER JOIN
			(
				SELECT
				PERMOHONAN_STPD_ID, COALESCE(NEXT_URUT,1) NEXT_URUT
				FROM
				(
					SELECT PERMOHONAN_STPD_ID, NEXT_URUT FROM PERMOHONAN_STPD_REALISASI_PARAF GROUP BY PERMOHONAN_STPD_ID, NEXT_URUT
				) A1
			) A1 ON A.PERMOHONAN_STPD_ID = A1.PERMOHONAN_STPD_ID AND A.NO_URUT = A1.NEXT_URUT
		) A1 ON A.PERMOHONAN_STPD_ID = A1.PERMOHONAN_STPD_ID
		LEFT JOIN 
		(
			SELECT
				A.PERMOHONAN_STPD_ID
				, STRING_AGG(A.SATUAN_KERJA_ID::text, ', ') AS SATUAN_KERJA_ID_INFO
				, STRING_AGG(B.NAMA::text, ', ') AS SATUAN_KERJA_ID_NAMA
			FROM PERMOHONAN_STPD_UNTUK A
			INNER JOIN SATUAN_KERJA B ON B.SATUAN_KERJA_ID = A.SATUAN_KERJA_ID
			where a.status is null
			GROUP BY A.PERMOHONAN_STPD_ID
		) B ON B.PERMOHONAN_STPD_ID = A.PERMOHONAN_STPD_ID
		left join satuan_kerja sk on sk.satuan_Kerja_id=a.pemimpin_id
		WHERE 1=1
		"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ";
		$this->query = $str;
		// echo $str;exit;
		return $this->selectLimit($str,$limit,$from); 
    }

    function getCountByParamsPersetujuan($paramsArray=array(), $statement="")
	{
		$str = "
		SELECT COUNT(1) AS ROWCOUNT
		FROM PERMOHONAN_STPD A
		LEFT JOIN
		(
			SELECT A.*
			FROM PERMOHONAN_STPD_REALISASI_PARAF A
			INNER JOIN
			(
				SELECT
				PERMOHONAN_STPD_ID, COALESCE(NEXT_URUT,1) NEXT_URUT
				FROM
				(
					SELECT PERMOHONAN_STPD_ID, NEXT_URUT FROM PERMOHONAN_STPD_REALISASI_PARAF GROUP BY PERMOHONAN_STPD_ID, NEXT_URUT
				) A1
			) A1 ON A.PERMOHONAN_STPD_ID = A1.PERMOHONAN_STPD_ID AND A.NO_URUT = A1.NEXT_URUT
		) A1 ON A.PERMOHONAN_STPD_ID = A1.PERMOHONAN_STPD_ID
		WHERE 1=1 ".$statement; 
		
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
	
    /** 
    * Hitung jumlah record berdasarkan parameter (array). 
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","IJIN_USAHA_ID"=>"yyy") 
    * @return long Jumlah record yang sesuai kriteria 
    **/ 
    function getCountByParams($paramsArray=array(), $statement="")
	{
		$str = "SELECT COUNT(1) AS ROWCOUNT FROM PERMOHONAN_STPD A
		        WHERE 1=1 ".$statement; 
		
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

    function getCountByParamsUntuk($paramsArray=array(), $statement="")
	{
		$str = "SELECT COUNT(1) AS ROWCOUNT FROM PERMOHONAN_STPD_UNTUK A
		        WHERE 1=1 ".$statement; 
		
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

    function updateByFieldTime()
	{
		$str = "
		UPDATE PERMOHONAN_STPD A SET
		".$this->getField("FIELD")."= CURRENT_TIMESTAMP
		WHERE PERMOHONAN_STPD_ID = ".$this->getField("PERMOHONAN_STPD_ID")."
		";
		$this->query = $str;
		return $this->execQuery($str);
	}

	function selectByParamsGetInfoTtdSurat($paramsArray = array(), $limit = -1, $from = -1, $stat = '', $sOrder = "  ")
	{
		$str = "
		SELECT 
			A.TTD_KODE, A.APPROVAL_JABATAN ||' ('||A.APPROVAL_NAMA||')' APPROVED_BY
			, A.NOMOR, A.PERMOHONAN_STPD_ID
			, TO_CHAR(A.APPROVAL_QR_DATE, 'YYYY-MM-DD HH:MM:SS') APPROVAL_QR_DATE
		FROM PERMOHONAN_STPD A 
		WHERE 1=1 
		";

		while (list($key, $val) = each($paramsArray)) {
			$str .= " AND $key = '$val' ";
		}

		$str .= " " . $stat . " " . $sOrder;
		$this->query = $str;
		return $this->selectLimit($str, $limit, $from);
	}

	function insertlog()
	{
		$this->setField("PERMOHONAN_STPD_LOG_ID", $this->getNextId("PERMOHONAN_STPD_LOG_ID", "PERMOHONAN_STPD_LOG"));
		$str = "
		INSERT INTO PERMOHONAN_STPD_LOG
		(
			PERMOHONAN_STPD_LOG_ID, PERMOHONAN_STPD_ID, TANGGAL, STATUS_SURAT, INFORMASI, CATATAN
			, LAST_CREATE_USER, LAST_CREATE_DATE
		)
		VALUES 
		(
            " . $this->getField("PERMOHONAN_STPD_LOG_ID") . ",
            " . $this->getField("PERMOHONAN_STPD_ID") . ",
            CURRENT_TIMESTAMP,
            '" . $this->getField("STATUS_SURAT") . "',
            '" . $this->getField("INFORMASI") . "',
            '" . $this->getField("CATATAN") . "',
            '" . $this->getField("LAST_CREATE_USER") . "',
            CURRENT_DATE
        )";

		$this->query = $str;
		// echo $str; exit;
		$this->id = $this->getField("PERMOHONAN_STPD_LOG_ID");
		return $this->execQuery($str);
	}

	function selectByParamsDataLog($paramsArray = array(), $limit = -1, $from = -1, $stat = '', $order = "ORDER BY A.PERMOHONAN_STPD_LOG_ID, A.TANGGAL DESC")
	{
		$str = "
		SELECT 
			PERMOHONAN_STPD_LOG_ID, PERMOHONAN_STPD_ID, TO_CHAR(TANGGAL, 'YYYY-MM-DD HH24:MI:SS') TANGGAL, STATUS_SURAT, INFORMASI, CATATAN
			, LAST_CREATE_USER, LAST_CREATE_DATE
		FROM PERMOHONAN_STPD_LOG A
		WHERE 1=1
		";

		while (list($key, $val) = each($paramsArray)) {
			$str .= " AND $key = '$val' ";
		}

		$str .= " " . $stat . "  " . $order;
		$this->query = $str;
		//	echo $str; exit;
		return $this->selectLimit($str, $limit, $from);
	}

	function paraf()
	{
		$str = "
		UPDATE permohonan_stpd_realisasi_paraf A SET
			STATUS_PARAF= '1',
			KODE_PARAF= '".$this->getField("KODE_PARAF")."',
			LAST_UPDATE_USER= '".$this->getField("LAST_UPDATE_USER")."',
			LAST_UPDATE_DATE= NOW()
		WHERE A.PERMOHONAN_STPD_ID= '".$this->getField("PERMOHONAN_STPD_ID")."'
		AND SATUAN_KERJA_ID_TUJUAN IN 
		(
			CASE WHEN STATUS_BANTU = 1 THEN 
			(
				select satuan_kerja_id_tujuan from permohonan_stpd_realisasi_paraf a 
				where a.permohonan_stpd_id= '".$this->getField("PERMOHONAN_STPD_ID")."'
				and a.user_id = '".$this->getField("USER_ID")."'
			) ELSE '".$this->getField("SATUAN_KERJA_ID_TUJUAN")."' END
		)
		";
		/*AND EXISTS
		(
			SELECT 1 FROM SURAT_MASUK_AKSES X WHERE X.PERMOHONAN_STPD_ID = A.PERMOHONAN_STPD_ID 
			AND X.USER_ID = A.USER_ID 
			AND X.USER_ID = '".$this->getField("USER_ID")."' 
			AND X.AKSES IN ('PEMARAF', 'PLHPEMARAF')
		)*/
		$this->query = $str;
		// echo $str;exit;
		$this->execQuery($str);

		// tambahan khusus
		// update next urut
		$str1= "
		UPDATE permohonan_stpd_realisasi_paraf A SET
		NEXT_URUT= 
		(
			SELECT NO_URUT + 1 
			FROM permohonan_stpd_realisasi_paraf A
			WHERE A.PERMOHONAN_STPD_ID = '".$this->getField("PERMOHONAN_STPD_ID")."'
			AND SATUAN_KERJA_ID_TUJUAN IN 
			(
				CASE WHEN STATUS_BANTU = 1 THEN 
				(
					select satuan_kerja_id_tujuan from permohonan_stpd_realisasi_paraf a 
					where a.permohonan_stpd_id= '".$this->getField("PERMOHONAN_STPD_ID")."'
					and a.user_id = '".$this->getField("USER_ID")."'
				) ELSE '".$this->getField("SATUAN_KERJA_ID_TUJUAN")."' END
			)
			AND A.STATUS_PARAF= '1'
		)
		WHERE A.PERMOHONAN_STPD_ID = '".$this->getField("PERMOHONAN_STPD_ID")."'
		";
		/*AND EXISTS
		(
			SELECT 1 FROM SURAT_MASUK_AKSES X WHERE X.PERMOHONAN_STPD_ID = A.PERMOHONAN_STPD_ID 
			AND X.USER_ID = A.USER_ID 
			AND X.USER_ID = '".$this->getField("USER_ID")."' 
			AND X.AKSES IN ('PEMARAF', 'PLHPEMARAF')
		)*/
		// echo $str1;exit;
		return $this->execQuery($str1);
	}
  
  } 
?>