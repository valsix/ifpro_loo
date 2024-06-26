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

  class TrLoo extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function TrLoo()
	{
      $this->Entity(); 
    }
	
	function insert()
	{
		$this->setField("TR_LOO_ID", $this->getNextId("TR_LOO_ID","tr_loo"));
		$str = "
		INSERT INTO tr_loo 
		(
			TR_LOO_ID, STATUS_DATA, USER_PEMBUAT_ID, SATUAN_KERJA_PENGIRIM_ID, PRODUK_ID
			, CUSTOMER_ID, PIC_PENANDATANGAN, JABATAN_PENANDATANGAN
			, LOKASI_LOO_ID, PPH, TOTAL_LUAS_INDOOR, TOTAL_LUAS_OUTDOOR
			, TOTAL_LUAS, TOTAL_DISKON_INDOOR_SEWA, TOTAL_DISKON_OUTDOOR_SEWA, TOTAL_DISKON_INDOOR_SERVICE
			, TOTAL_DISKON_OUTDOOR_SERVICE, HARGA_INDOOR_SEWA, HARGA_OUTDOOR_SEWA, HARGA_INDOOR_SERVICE
			, HARGA_OUTDOOR_SERVICE, DP, TOP, PERIODE_SEWA
			, SEWA_BIAYA_SATUAN_UNIT, SEWA_BIAYA_SATUAN_SERVICE, SEWA_TOTAL_BIAYA_UNIT, SEWA_BIAYA_PER_BULAN_UNIT
			, SEWA_BIAYA_PER_BULAN_SERVICE, SEWA_TOTAL_BIAYA_SERVICE, TOTAL_BIAYA_PER_BULAN_NO_PPN, TOTAL_BIAYA_PER_BULAN_PPN
			, TOTAL_BIAYA_NO_PPN, TOTAL_BIAYA_PPN, SECURITY_DEPOSIT, FITTING_OUT
		)
		VALUES 
		(
			".$this->getField("TR_LOO_ID")."
			, '".$this->getField("STATUS_DATA")."'
			, '".$this->getField("USER_PEMBUAT_ID")."'
			, '".$this->getField("SATUAN_KERJA_PENGIRIM_ID")."'
			, ".$this->getField("PRODUK_ID")."
			, ".$this->getField("CUSTOMER_ID")."
			, '".$this->getField("PIC_PENANDATANGAN")."'
			, '".$this->getField("JABATAN_PENANDATANGAN")."'
			, ".$this->getField("LOKASI_LOO_ID")."
			, ".$this->getField("PPH")."
			, ".$this->getField("TOTAL_LUAS_INDOOR")."
			, ".$this->getField("TOTAL_LUAS_OUTDOOR")."
			, ".$this->getField("TOTAL_LUAS")."
			, ".$this->getField("TOTAL_DISKON_INDOOR_SEWA")."
			, ".$this->getField("TOTAL_DISKON_OUTDOOR_SEWA")."
			, ".$this->getField("TOTAL_DISKON_INDOOR_SERVICE")."
			, ".$this->getField("TOTAL_DISKON_OUTDOOR_SERVICE")."
			, ".$this->getField("HARGA_INDOOR_SEWA")."
			, ".$this->getField("HARGA_OUTDOOR_SEWA")."
			, ".$this->getField("HARGA_INDOOR_SERVICE")."
			, ".$this->getField("HARGA_OUTDOOR_SERVICE")."
			, ".$this->getField("DP")."
			, ".$this->getField("TOP")."
			, ".$this->getField("PERIODE_SEWA")."
			, ".$this->getField("SEWA_BIAYA_SATUAN_UNIT")."
			, ".$this->getField("SEWA_BIAYA_SATUAN_SERVICE")."
			, ".$this->getField("SEWA_TOTAL_BIAYA_UNIT")."
			, ".$this->getField("SEWA_BIAYA_PER_BULAN_UNIT")."
			, ".$this->getField("SEWA_BIAYA_PER_BULAN_SERVICE")."
			, ".$this->getField("SEWA_TOTAL_BIAYA_SERVICE")."
			, ".$this->getField("TOTAL_BIAYA_PER_BULAN_NO_PPN")."
			, ".$this->getField("TOTAL_BIAYA_PER_BULAN_PPN")."
			, ".$this->getField("TOTAL_BIAYA_NO_PPN")."
			, ".$this->getField("TOTAL_BIAYA_PPN")."
			, ".$this->getField("SECURITY_DEPOSIT")."
			, ".$this->getField("FITTING_OUT")."
		)";
		$this->id = $this->getField("TR_LOO_ID");
		$this->query = $str;
		// echo $str;exit;
		return $this->execQuery($str);
    }

  	function update()
	{
		$str = "
		UPDATE tr_loo
		SET
		STATUS_DATA= '".$this->getField("STATUS_DATA")."'
		, SATUAN_KERJA_PENGIRIM_ID= '".$this->getField("SATUAN_KERJA_PENGIRIM_ID")."'
		, PRODUK_ID= ".$this->getField("PRODUK_ID")."
		, CUSTOMER_ID= ".$this->getField("CUSTOMER_ID")."
		, PIC_PENANDATANGAN= '".$this->getField("PIC_PENANDATANGAN")."'
		, JABATAN_PENANDATANGAN= '".$this->getField("JABATAN_PENANDATANGAN")."'
		, LOKASI_LOO_ID= ".$this->getField("LOKASI_LOO_ID")."
		, PPH= ".$this->getField("PPH")."
		, TOTAL_LUAS_INDOOR= ".$this->getField("TOTAL_LUAS_INDOOR")."
		, TOTAL_LUAS_OUTDOOR= ".$this->getField("TOTAL_LUAS_OUTDOOR")."
		, TOTAL_LUAS= ".$this->getField("TOTAL_LUAS")."
		, TOTAL_DISKON_INDOOR_SEWA= ".$this->getField("TOTAL_DISKON_INDOOR_SEWA")."
		, TOTAL_DISKON_OUTDOOR_SEWA= ".$this->getField("TOTAL_DISKON_OUTDOOR_SEWA")."
		, TOTAL_DISKON_INDOOR_SERVICE= ".$this->getField("TOTAL_DISKON_INDOOR_SERVICE")."
		, TOTAL_DISKON_OUTDOOR_SERVICE= ".$this->getField("TOTAL_DISKON_OUTDOOR_SERVICE")."
		, HARGA_INDOOR_SEWA= ".$this->getField("HARGA_INDOOR_SEWA")."
		, HARGA_OUTDOOR_SEWA= ".$this->getField("HARGA_OUTDOOR_SEWA")."
		, HARGA_INDOOR_SERVICE= ".$this->getField("HARGA_INDOOR_SERVICE")."
		, HARGA_OUTDOOR_SERVICE= ".$this->getField("HARGA_OUTDOOR_SERVICE")."
		, TOP= ".$this->getField("TOP")."
		, DP= ".$this->getField("DP")."
		, PERIODE_SEWA= ".$this->getField("PERIODE_SEWA")."
		, SEWA_BIAYA_SATUAN_UNIT= ".$this->getField("SEWA_BIAYA_SATUAN_UNIT")."
		, SEWA_BIAYA_SATUAN_SERVICE= ".$this->getField("SEWA_BIAYA_SATUAN_SERVICE")."
		, SEWA_TOTAL_BIAYA_UNIT= ".$this->getField("SEWA_TOTAL_BIAYA_UNIT")."
		, SEWA_BIAYA_PER_BULAN_UNIT= ".$this->getField("SEWA_BIAYA_PER_BULAN_UNIT")."
		, SEWA_BIAYA_PER_BULAN_SERVICE= ".$this->getField("SEWA_BIAYA_PER_BULAN_SERVICE")."
		, SEWA_TOTAL_BIAYA_SERVICE= ".$this->getField("SEWA_TOTAL_BIAYA_SERVICE")."
		, TOTAL_BIAYA_PER_BULAN_NO_PPN= ".$this->getField("TOTAL_BIAYA_PER_BULAN_NO_PPN")."
		, TOTAL_BIAYA_PER_BULAN_PPN= ".$this->getField("TOTAL_BIAYA_PER_BULAN_PPN")."
		, TOTAL_BIAYA_NO_PPN= ".$this->getField("TOTAL_BIAYA_NO_PPN")."
		, TOTAL_BIAYA_PPN= ".$this->getField("TOTAL_BIAYA_PPN")."
		, SECURITY_DEPOSIT= ".$this->getField("SECURITY_DEPOSIT")."
		, FITTING_OUT= ".$this->getField("FITTING_OUT")."
		WHERE TR_LOO_ID= ".$this->getField("TR_LOO_ID")."
		"; 
		// echo $str;exit;
		$this->query = $str;
		return $this->execQuery($str);
    }

    function updatetriger()
	{
		$str = "
		UPDATE tr_loo
		SET
		PAKSA_DB= '".$this->getField("PAKSA_DB")."'
		WHERE TR_LOO_ID= ".$this->getField("TR_LOO_ID")."
		"; 
		// echo $str;exit;
		$this->query = $str;
		return $this->execQuery($str);
    }

	function delete()
	{
        $str = "
        DELETE FROM tr_loo
        WHERE TR_LOO_ID = ".$this->getField("TR_LOO_ID")."
        ";
				  
		$this->query = $str;
        return $this->execQuery($str);
    }

    function paraf()
	{
		$str = "
		UPDATE tr_loo_paraf A SET
			STATUS_PARAF= '1',
			KODE_PARAF= '".$this->getField("KODE_PARAF")."',
			LAST_UPDATE_USER= '".$this->getField("LAST_UPDATE_USER")."',
			LAST_UPDATE_DATE= NOW()
		WHERE A.TR_LOO_ID= '".$this->getField("TR_LOO_ID")."'
		AND SATUAN_KERJA_ID_TUJUAN IN 
		(
			CASE WHEN STATUS_BANTU = 1 THEN 
			(
				SELECT SATUAN_KERJA_ID_TUJUAN FROM tr_loo_paraf a 
				WHERE A.TR_LOO_ID= '".$this->getField("TR_LOO_ID")."'
				AND A.USER_ID= '".$this->getField("USER_ID")."'
				AND A.SATUAN_KERJA_ID_TUJUAN = '".$this->getField("SATUAN_KERJA_ID_TUJUAN")."'
			) ELSE '".$this->getField("SATUAN_KERJA_ID_TUJUAN")."' END
		)
		AND A.TERBACA = 1
		";
		/*AND EXISTS
		(
			SELECT 1 FROM SURAT_MASUK_AKSES X WHERE X.TR_LOO_ID = A.TR_LOO_ID 
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
		UPDATE tr_loo_paraf A SET
		NEXT_URUT= 
		(
			SELECT
			COALESCE(MAX(NO_URUT),0) + 1
			FROM tr_loo_paraf A
			WHERE A.TR_LOO_ID = '".$this->getField("TR_LOO_ID")."'
			AND SATUAN_KERJA_ID_TUJUAN IN 
			(
				CASE WHEN STATUS_BANTU = 1 THEN 
				(
					SELECT SATUAN_KERJA_ID_TUJUAN FROM tr_loo_paraf a 
					WHERE A.TR_LOO_ID= '".$this->getField("TR_LOO_ID")."'
					AND A.USER_ID = '".$this->getField("USER_ID")."'
					AND A.SATUAN_KERJA_ID_TUJUAN = '".$this->getField("SATUAN_KERJA_ID_TUJUAN")."'
				) ELSE '".$this->getField("SATUAN_KERJA_ID_TUJUAN")."' END
			)
			AND A.STATUS_PARAF= '1'
		)
		WHERE A.TR_LOO_ID = '".$this->getField("TR_LOO_ID")."'
		";
			/*AND EXISTS
			(
				SELECT 1 FROM SURAT_MASUK_AKSES X WHERE X.TR_LOO_ID = A.TR_LOO_ID 
				AND X.USER_ID = A.USER_ID 
				AND X.USER_ID = '".$this->getField("USER_ID")."' 
				AND X.AKSES IN ('PEMARAF', 'PLHPEMARAF')
			)*/
		// echo $str1;exit;
		return $this->execQuery($str1);
	}

	function revisi()
	{

		$str = "
		UPDATE tr_loo SET
			   STATUS_DATA= 'REVISI'
			   --, REVISI= '".$this->getField("REVISI")."'
			   --, REVISI_BY= '".$this->getField("REVISI_BY")."'
			   --, REVISI_DATE= CURRENT_TIMESTAMP
		   WHERE TR_LOO_ID= '".$this->getField("TR_LOO_ID")."' 
		   --AND SATUAN_KERJA_ID_ASAL= '".$this->getField("SATUAN_KERJA_ID_ASAL")."'
		";
		$this->query = $str;
		// echo $str;exit;
		return $this->execQuery($str);
	}

    function insertlog()
	{
		$this->setField("TR_LOO_LOG_ID", $this->getNextId("TR_LOO_LOG_ID", "tr_loo_log"));
		$str = "
		INSERT INTO tr_loo_log
		(
			TR_LOO_LOG_ID, TR_LOO_ID, TANGGAL, STATUS_SURAT, INFORMASI, CATATAN
			, LAST_CREATE_USER, LAST_CREATE_DATE
		)
		VALUES 
		(
            ".$this->getField("TR_LOO_LOG_ID")."
            , ".$this->getField("TR_LOO_ID")."
            , CURRENT_TIMESTAMP
            , '".$this->getField("STATUS_SURAT")."'
            , '".$this->getField("INFORMASI")."'
            , '".$this->getField("CATATAN")."'
            , '".$this->getField("LAST_CREATE_USER")."'
            , CURRENT_DATE
        )";

		$this->query = $str;
		// echo $str; exit;
		$this->id = $this->getField("TR_LOO_LOG_ID");
		return $this->execQuery($str);
	}

	function insertAttachment()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("TR_LOO_ATTACHMENT_ID", $this->getNextId("TR_LOO_ATTACHMENT_ID", "tr_loo_attachment"));

		$str = "
		INSERT INTO tr_loo_attachment
		(
			TR_LOO_ATTACHMENT_ID, TR_LOO_ID, VMODE, ATTACHMENT, UKURAN, TIPE, NAMA, LAST_CREATE_USER, LAST_CREATE_DATE
		)
		VALUES 
		(
			'".$this->getField("TR_LOO_ATTACHMENT_ID")."'
			, '".$this->getField("TR_LOO_ID")."'
			, '".$this->getField("VMODE")."'
			, '".$this->getField("ATTACHMENT")."'
			, ".(int)$this->getField("UKURAN")."
			, '".$this->getField("TIPE")."'
			, '".$this->getField("NAMA")."'
			, '".$this->getField("LAST_CREATE_USER")."'
			, NOW()
		)";

		$this->query = $str;
		// echo $str;
		// exit;
		$this->id = $this->getField("TR_LOO_ID");
		return $this->execQuery($str);
	}

	function deleteAttachment()
	{
		$str= "
		DELETE FROM tr_loo_attachment
		WHERE
		TR_LOO_ID = '".$this->getField("TR_LOO_ID")."'";

		$this->query = $str;
		$this->execQuery($str);
	}

	function deleteModeAttachment()
	{
		$str= "
		DELETE FROM tr_loo_attachment
		WHERE VMODE = '".$this->getField("VMODE")."' AND TR_LOO_ID = '".$this->getField("TR_LOO_ID")."'
		";

		$this->query = $str;
		$this->execQuery($str);
	}

	function updateByField()
	{
		$str = "
		UPDATE tr_loo A SET
		".$this->getField("FIELD")."= '".$this->getField("FIELD_VALUE")."'
		--, LAST_UPDATE_USER= '".$this->getField("LAST_UPDATE_USER")."'
		--, LAST_UPDATE_DATE= NOW()
		WHERE TR_LOO_ID = ".$this->getField("TR_LOO_ID")."
		";
		$this->query = $str;
		// echo $str;exit;
		return $this->execQuery($str);
	}

	function updateByFieldValueTime()
	{
		$str = "
		UPDATE tr_loo A SET
		". $this->getField("FIELD")."= ". $this->getField("FIELD_VALUE")."
		WHERE TR_LOO_ID= ".$this->getField("TR_LOO_ID")."
		";
		$this->query = $str;
		// echo $str;exit;
		return $this->execQuery($str);
	}

	function updateByFieldTime()
	{
		$str = "
		UPDATE tr_loo A SET
		".$this->getField("FIELD")."= CURRENT_TIMESTAMP
		WHERE TR_LOO_ID= ".$this->getField("TR_LOO_ID")."
		";
		$this->query = $str;
		// echo $str;exit;
		return $this->execQuery($str);
	}

	function updateByFieldValidasiNomor()
	{
		$str = "
		UPDATE tr_loo A SET
		NOMOR= '".$this->getField("NOMOR")."',
		".$this->getField("FIELD")."= '".$this->getField("FIELD_VALUE")."'
		--, LAST_UPDATE_USER= '".$this->getField("LAST_UPDATE_USER")."'
		--, LAST_UPDATE_DATE= NOW()
		WHERE TR_LOO_ID= ".$this->getField("TR_LOO_ID")." AND
		(
			--SATUAN_KERJA_ID_ASAL = '".$this->getField("SATUAN_KERJA_ID_ASAL")."' OR
			USER_PEMBUAT_ID = '".$this->getField("USER_ID")."' 
			OR USER_LIHAT_STATUS LIKE '%".$this->getField("USER_ID")."%'
			--OR EXISTS(SELECT 1 FROM SURAT_MASUK_AKSES X WHERE X.TR_LOO_ID = A.TR_LOO_ID AND X.USER_ID = '".$this->getField("PEMARAF_ID")."')
		)
		";
		$this->query = $str;
		// echo $str;exit;
		return $this->execQuery($str);
	}

	function updateByFieldValidasi()
	{
		$str = "
		UPDATE tr_loo A SET
		".$this->getField("FIELD")."= '".$this->getField("FIELD_VALUE")."'
		--, LAST_UPDATE_USER= '".$this->getField("LAST_UPDATE_USER")."'
		--, LAST_UPDATE_DATE= NOW()
		WHERE TR_LOO_ID = ".$this->getField("TR_LOO_ID")." AND
		(
			--SATUAN_KERJA_ID_ASAL = '".$this->getField("SATUAN_KERJA_ID_ASAL")."' OR
			USER_PEMBUAT_ID = '".$this->getField("USER_ID")."'
			OR USER_LIHAT_STATUS LIKE '%".$this->getField("USER_ID")."%'
			--OR EXISTS(SELECT 1 FROM SURAT_MASUK_AKSES X WHERE X.TR_LOO_ID = A.TR_LOO_ID AND X.USER_ID = '".$this->getField("PEMARAF_ID")."')
		)
		";
		$this->query = $str;
		// echo $str;exit;
		return $this->execQuery($str);
	}
	
    /** 
    * Cari record berdasarkan array parameter dan limit tampilan 
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","nama"=>"yyy") 
    * @param int limit Jumlah maksimal record yang akan diambil 
    * @param int from Awal record yang diambil 
    * @return boolean True jika sukses, false jika tidak 
    **/ 
    function selectByParamsAttachment($paramsArray = array(), $limit = -1, $from = -1, $stat = '', $sOrder = " ORDER BY A.TR_LOO_ATTACHMENT_ID ASC ")
	{
		$str = "
		SELECT 
			A.*
		FROM tr_loo_attachment A
		WHERE 1 = 1
		";

		while (list($key, $val) = each($paramsArray)) {
			$str .= " AND $key = '$val' ";
		}

		$str .= " ".$stat." ".$sOrder;
		$this->query = $str;
		// echo $str;exit;
		return $this->selectLimit($str, $limit, $from);
	}

	function selectByParamsDataLog($paramsArray = array(), $limit = -1, $from = -1, $stat = '', $order = "ORDER BY A.TR_LOO_ID, A.TANGGAL DESC")
	{
		$str = "
		SELECT 
			TR_LOO_LOG_ID, TR_LOO_ID, TO_CHAR(TANGGAL, 'YYYY-MM-DD HH24:MI:SS') TANGGAL, STATUS_SURAT, INFORMASI, CATATAN
			, LAST_CREATE_USER, LAST_CREATE_DATE
		FROM tr_loo_log A
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

    function selectByParams($paramsArray=array(),$limit=-1,$from=-1,$statement="", $order=" ORDER BY A.TR_LOO_ID ASC")
	{
		$str = "
		SELECT 
		A.*
		FROM tr_loo A
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

    function selectdraft($paramsArray=array(),$limit=-1,$from=-1,$statement="", $order=" ORDER BY A.TR_LOO_ID ASC")
	{
		$str = "
		SELECT 
			A1.TELP, A1.EMAIL, A1.TEMPAT, A1.NAMA_PEMILIK, A1.NAMA_BRAND
			, A2.NAMA PRODUK_NAMA, A3.NAMA LOKASI_NAMA
			, A3.NAMA || '<br/>' || ambildetilloo(A.TR_LOO_ID, '<br/>') INFO_DETIL_NAMA
			, TO_CHAR(A.LAST_CREATE_DATE, 'YYYY-MM-DD HH24:MI:SS') INFO_LAST_CREATE_DATE
			, A.*
		FROM tr_loo A
		INNER JOIN customer A1 ON A.CUSTOMER_ID = A1.CUSTOMER_ID
		INNER JOIN produk A2 ON A.PRODUK_ID = A2.PRODUK_ID
		INNER JOIN lokasi_loo A3 ON A.LOKASI_LOO_ID = A3.LOKASI_LOO_ID
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

    function selectcetak($paramsArray=array(),$limit=-1,$from=-1,$statement="", $order=" ORDER BY A.TR_LOO_ID ASC")
	{
		$str = "
		SELECT 
			A1.TELP, A1.EMAIL, A1.TEMPAT, A1.NAMA_PEMILIK, A1.NAMA_BRAND
			, A2.NAMA PRODUK_NAMA, A3.NAMA LOKASI_NAMA
			, TO_CHAR(A.APPROVAL_QR_DATE, 'YYYY-MM-DD HH24:MI:SS') INFO_APPROVAL_QR_DATE
			, TO_CHAR(A.LAST_CREATE_DATE, 'YYYY-MM-DD HH24:MI:SS') INFO_LAST_CREATE_DATE
			, A.*
		FROM tr_loo A
		INNER JOIN customer A1 ON A.CUSTOMER_ID = A1.CUSTOMER_ID
		INNER JOIN produk A2 ON A.PRODUK_ID = A2.PRODUK_ID
		INNER JOIN lokasi_loo A3 ON A.LOKASI_LOO_ID = A3.LOKASI_LOO_ID
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
	
    /** 
    * Hitung jumlah record berdasarkan parameter (array). 
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","nama"=>"yyy") 
    * @return long Jumlah record yang sesuai kriteria 
    **/ 
    function getCountByParams($paramsArray=array(), $statement="")
	{
		$str = "SELECT COUNT(1) AS ROWCOUNT FROM tr_loo A WHERE 1 = 1 ".$statement; 
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

    function getStatusSurat($paramsArray = array(), $statement = "")
	{
		$str = "SELECT STATUS_DATA ROWINFO FROM tr_loo A WHERE 1=1 ";
		while (list($key, $val) = each($paramsArray)) {
			$str .= " AND $key = '$val' ";
		}

		$str .= $statement;
		$this->query = $str;
		$this->select($str);
		if ($this->firstRow())
			return $this->getField("ROWINFO");
		else
			return "";
	}

	function selectlampirandua($paramsArray=array(),$limit=-1,$from=-1,$statement="", $order=" ")
	{
		$str = "
		SELECT
		ANGSURAN_SISA_DIKURANG_DP / TOP ANGSURAN_SEWA_BULANAN
		, (A.TOTAL_SEWA_PERBULAN * A.TAHUN_SEWA) + (A.BAYAR_SC_BULANAN * A.TAHUN_SEWA) SECURITY_DEPOSIT
		, A.*
		FROM
		(
			SELECT
			TOTAL_SEWA_TAHUN_INC_PPN - DOWN_PAYMENT ANGSURAN_SISA_DIKURANG_DP
			, A.*
			FROM
			(
				SELECT
				ROUND(A.TOTAL_SEWA_TAHUN_INC_PPN * (A.DP / 100),2) DOWN_PAYMENT
				, A.*
				FROM
				(
					SELECT
					A.TOTAL_SEWA_TAHUN_EX_PPN * A.PPH TOTAL_SEWA_TAHUN_INC_PPN
					, A.*
					FROM
					(
						SELECT
						A.TOTAL_SEWA_PERBULAN * MASA_KERJA_SAMA TOTAL_SEWA_TAHUN_EX_PPN
						, SERVICE_CHARGE * LUAS_AREA BAYAR_SC_BULANAN
						, A.*
						FROM
						(
							SELECT
							A.TR_LOO_ID, A.DP, A.TOP, A.PPH, A.FITTING_OUT, A.SEWA_BIAYA_SATUAN_SERVICE SERVICE_CHARGE
							, A3.NAMA || '<br/>' || AMBILDETILLOO(A.TR_LOO_ID, '<br/>') INFO_DETIL_NAMA
							, A1.TEMPAT NAMA_PENYEWA, A1.NAMA_BRAND NAMA_TOKO, A2.NAMA LINE_BUSINES
							, A.TAHUN_SEWA
							, A.TOTAL_LUAS_INDOOR, A.TOTAL_LUAS_OUTDOOR, A.TOTAL_LUAS LUAS_AREA
							, A.PERIODE_SEWA MASA_KERJA_SAMA
							, COALESCE(S1.NILAI,0) HARGA_SEWA_UNIT_INDOOR
							, COALESCE(S2.NILAI,0) HARGA_SEWA_UNIT_OUTDOOR
							, A.SEWA_BIAYA_SATUAN_UNIT HARGA_SEWA_UNIT
							, A.TOTAL_LUAS * A.SEWA_BIAYA_SATUAN_UNIT TOTAL_SEWA_PERBULAN
							FROM (SELECT ROUND((PERIODE_SEWA / 12)) TAHUN_SEWA, * FROM tr_loo) A
							INNER JOIN customer A1 ON A.CUSTOMER_ID = A1.CUSTOMER_ID
							INNER JOIN produk A2 ON A.PRODUK_ID = A2.PRODUK_ID
							INNER JOIN lokasi_loo A3 ON A.LOKASI_LOO_ID = A3.LOKASI_LOO_ID
							LEFT JOIN
							(
								SELECT TR_LOO_ID, SUM(NILAI) NILAI
								FROM tr_loo_detil A
								WHERE 1 = 1
								AND LOWER(VMODE) = LOWER('TARIF_SEWA_UNIT_INDOOR_AFTER_DISKON')
								GROUP BY TR_LOO_ID
							) S1 ON S1.TR_LOO_ID = A.TR_LOO_ID
							LEFT JOIN
							(
								SELECT TR_LOO_ID, SUM(NILAI) NILAI
								FROM tr_loo_detil A
								WHERE 1 = 1
								AND LOWER(VMODE) = LOWER('TARIF_SEWA_UNIT_OUTDOOR_AFTER_DISKON')
								GROUP BY TR_LOO_ID
							) S2 ON S1.TR_LOO_ID = A.TR_LOO_ID
							WHERE 1 = 1
						) A
					) A
				) A
			) A
		) A
		WHERE 1=1
		"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$order;
		$this->query = $str;
		return $this->selectLimit($str,$limit,$from); 
    }

	function selectperhitungantabel($paramsArray=array(),$limit=-1,$from=-1,$statement="", $order=" ")
	{
		$str = "
		WITH RECURSIVE cte AS 
		(
			SELECT
			0 BULAN, A.TR_LOO_ID, A.ANGSURAN_SISA_DIKURANG_DP, A.MASA_KERJA_SAMA, A.BAYAR_SC_BULANAN
			, ANGSURAN_SISA_DIKURANG_DP / TOP ANGSURAN_SEWA_BULANAN
			, 'Saldo Awal' NAMA_ANGSURAN, NULL::NUMERIC VBULAN
			, ANGSURAN_SISA_DIKURANG_DP / TOP SEWA_INC_PPN
			, A.ANGSURAN_SISA_DIKURANG_DP TOTAL_SEWA
			, A.SERVICE_CHARGE, A.SERVICE_CHARGE_INC_PPN
			FROM
			(
				SELECT
				A.*, TOTAL_SEWA_TAHUN_INC_PPN - DOWN_PAYMENT ANGSURAN_SISA_DIKURANG_DP
				FROM
				(
					SELECT
					ROUND(A.TOTAL_SEWA_TAHUN_INC_PPN * (A.DP / 100),2) DOWN_PAYMENT
					, A.SERVICE_CHARGE * A.PPH SERVICE_CHARGE_INC_PPN
					, A.*
					FROM
					(
						SELECT
						A.TOTAL_SEWA_TAHUN_EX_PPN * A.PPH TOTAL_SEWA_TAHUN_INC_PPN
						, SERVICE_CHARGE * MASA_KERJA_SAMA BAYAR_SC_BULANAN
						, A.*
						FROM
						(
							SELECT
							A.TOTAL_SEWA_PERBULAN * MASA_KERJA_SAMA TOTAL_SEWA_TAHUN_EX_PPN
							, A.*
							FROM
							(
								SELECT
								A.TR_LOO_ID, A.DP, A.PPH, A.TOP, A.SEWA_BIAYA_SATUAN_SERVICE SERVICE_CHARGE
								, A.TAHUN_SEWA, A.TOTAL_LUAS LUAS_AREA
								, A.PERIODE_SEWA MASA_KERJA_SAMA
								, A.TOTAL_LUAS * A.SEWA_BIAYA_SATUAN_UNIT TOTAL_SEWA_PERBULAN
								FROM (SELECT ROUND((PERIODE_SEWA / 12)) TAHUN_SEWA, * FROM tr_loo) A
								WHERE 1 = 1
							) A
						) A
					) A
				) A
			) A
			WHERE 1=1
			".$statement."
			UNION ALL
			SELECT
			R.BULAN + 1 BULAN, R.TR_LOO_ID, R.ANGSURAN_SISA_DIKURANG_DP, R.MASA_KERJA_SAMA, R.BAYAR_SC_BULANAN, R.ANGSURAN_SEWA_BULANAN
			, 'Angsuran ' || (R.BULAN + 1)::TEXT NAMA_ANGSURAN, R.BULAN + 1 VBULAN
			, R.ANGSURAN_SEWA_BULANAN SEWA_INC_PPN, COALESCE(R.TOTAL_SEWA,0) - COALESCE(R.SEWA_INC_PPN,0) TOTAL_SEWA
			, R.SERVICE_CHARGE, R.SERVICE_CHARGE_INC_PPN
			FROM cte R
			WHERE R.BULAN < R.MASA_KERJA_SAMA
		)
		SELECT
		A.NAMA_ANGSURAN, A.VBULAN
		, CASE WHEN A.VBULAN > 0 THEN A.SEWA_INC_PPN::TEXT ELSE NULL::TEXT END SEWA_INC_PPN
		, CASE WHEN A.TOTAL_SEWA > 0 THEN A.TOTAL_SEWA::TEXT ELSE NULL::TEXT END TOTAL_SEWA
		, CASE WHEN A.VBULAN > 0 AND A.VBULAN <= 12 THEN A.SERVICE_CHARGE::TEXT WHEN A.VBULAN > 12 THEN 'TBA' ELSE NULL::TEXT END SERVICE_CHARGE
		, CASE WHEN A.VBULAN > 0 AND A.VBULAN <= 12 THEN A.SERVICE_CHARGE_INC_PPN::TEXT WHEN A.VBULAN > 12 THEN 'TBA' ELSE NULL::TEXT END SERVICE_CHARGE_INC_PPN
		FROM cte A
		WHERE 1=1
		"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= " ".$order;
		$this->query = $str;
		return $this->selectLimit($str,$limit,$from); 
    }
	
  } 
?>