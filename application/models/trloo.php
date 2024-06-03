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
			TR_LOO_ID, STATUS_DATA, USER_PEMBUAT_ID, SATUAN_KERJA_PENGIRIM_ID, PRODUK_ID, CUSTOMER_ID, LOKASI_LOO_ID
			, PPH, TOTAL_LUAS_INDOOR, TOTAL_LUAS_OUTDOOR
			, TOTAL_LUAS, TOTAL_DISKON_INDOOR_SEWA, TOTAL_DISKON_OUTDOOR_SEWA, TOTAL_DISKON_INDOOR_SERVICE
			, TOTAL_DISKON_OUTDOOR_SERVICE, HARGA_INDOOR_SEWA, HARGA_OUTDOOR_SEWA, HARGA_INDOOR_SERVICE
			, HARGA_OUTDOOR_SERVICE, DP, PERIODE_SEWA
		)
		VALUES 
		(
			".$this->getField("TR_LOO_ID")."
			, '".$this->getField("STATUS_DATA")."'
			, '".$this->getField("USER_PEMBUAT_ID")."'
			, '".$this->getField("SATUAN_KERJA_PENGIRIM_ID")."'
			, ".$this->getField("PRODUK_ID")."
			, ".$this->getField("CUSTOMER_ID")."
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
			, ".$this->getField("PERIODE_SEWA")."
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
		, DP= ".$this->getField("DP")."
		, PERIODE_SEWA= ".$this->getField("PERIODE_SEWA")."
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
			SELECT NO_URUT + 1 
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
		WHERE A.TR_LOO_ID = '" . $this->getField("TR_LOO_ID") . "'
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
	
    /** 
    * Cari record berdasarkan array parameter dan limit tampilan 
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","nama"=>"yyy") 
    * @param int limit Jumlah maksimal record yang akan diambil 
    * @param int from Awal record yang diambil 
    * @return boolean True jika sukses, false jika tidak 
    **/ 
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
			, A2.NAMA PRODUK_NAMA, A3.NAMA LOKASI_NAMA, A.TOTAL_LUAS
			, A.HARGA_INDOOR_SEWA, A.HARGA_OUTDOOR_SEWA, A.HARGA_INDOOR_SERVICE, A.HARGA_OUTDOOR_SERVICE
			, A.DP, A.PERIODE_SEWA
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
	
  } 
?>