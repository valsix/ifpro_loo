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

class MutasiPejabat extends Entity{ 

	var $query;
	/**
	* Class constructor.
	**/
	function MutasiPejabat()
	{
		$this->Entity(); 
	}

	function insert()
	{
		$this->setField("MUTASI_PEJABAT_ID", $this->getNextId("MUTASI_PEJABAT_ID","MUTASI_PEJABAT"));
		$str = "
		INSERT INTO MUTASI_PEJABAT
		(
			MUTASI_PEJABAT_ID, CABANG_ID, NO_SK, TIPE, TANGGAL_MUTASI
			, PEGAWAI_CABANG_ID, PEGAWAI_NIP, PEGAWAI_NAMA, UNIT_KERJA_ID, UNIT_KERJA_NAMA, PEGAWAI_JABATAN_NAMA
			, PEGAWAI_JABATAN_NIP_BARU, PEGAWAI_JABATAN_PEGAWAI_BARU, PEGAWAI_JABATAN_UNIT_KERJA_ID, PEGAWAI_JABATAN_UNIT_KERJA_NAMA
			, PEGAWAI_JABATAN_UNIT_KERJA_ENTRI_STATUS
			, PEGAWAI_JABATAN_UNIT_KERJA_ENTRI_ID_TUJUAN, PEGAWAI_JABATAN_UNIT_KERJA_ENTRI_ID, PEGAWAI_JABATAN_UNIT_KERJA_ENTRI
			, PEGAWAI_JABATAN_NAMA_BARU, AKSI_PEJABAT_PENGGANTI, LAST_CREATE_USER, LAST_CREATE_DATE
		)
		VALUES 
		(
			'".$this->getField("MUTASI_PEJABAT_ID")."',
			'".$this->getField("CABANG_ID")."',
			'".$this->getField("NO_SK")."',
			".$this->getField("TIPE").",
			".$this->getField("TANGGAL_MUTASI").",
			'".$this->getField("PEGAWAI_CABANG_ID")."',
			'".$this->getField("PEGAWAI_NIP")."',
			'".$this->getField("PEGAWAI_NAMA")."',
			'".$this->getField("UNIT_KERJA_ID")."',
			'".$this->getField("UNIT_KERJA_NAMA")."',
			'".$this->getField("PEGAWAI_JABATAN_NAMA")."',
			'".$this->getField("PEGAWAI_JABATAN_NIP_BARU")."',
			'".$this->getField("PEGAWAI_JABATAN_PEGAWAI_BARU")."',
			'".$this->getField("PEGAWAI_JABATAN_UNIT_KERJA_ID")."',
			'".$this->getField("PEGAWAI_JABATAN_UNIT_KERJA_NAMA")."',
			'".$this->getField("PEGAWAI_JABATAN_UNIT_KERJA_ENTRI_STATUS")."',
			'".$this->getField("PEGAWAI_JABATAN_UNIT_KERJA_ENTRI_ID_TUJUAN")."',
			'".$this->getField("PEGAWAI_JABATAN_UNIT_KERJA_ENTRI_ID")."',
			'".$this->getField("PEGAWAI_JABATAN_UNIT_KERJA_ENTRI")."',
			'".$this->getField("PEGAWAI_JABATAN_NAMA_BARU")."',
			".$this->getField("AKSI_PEJABAT_PENGGANTI").",
			'".$this->getField("LAST_CREATE_USER")."',
			NOW()
		)
		"; 
		$this->id = $this->getField("MUTASI_PEJABAT_ID");
		$this->query = $str;
		// echo $str;exit();
		$this->execQuery($str);

		$strproses= "
		SELECT pmutasipejabat(".$this->getField("MUTASI_PEJABAT_ID").")
		"; 
		$this->query = $strproses;
		// echo $str;exit();
        return $this->execQuery($strproses);
	}

	function update()
	{
		$str = "UPDATE PEJABAT_PENGGANTI
				SET 
					NAMA					='".$this->getField("NAMA")."', 
					PEGAWAI_ID_PENGGANTI	='".$this->getField("PEGAWAI_ID_PENGGANTI")."', 
					NAMA_PENGGANTI			='".$this->getField("NAMA_PENGGANTI")."', 
					TANGGAL_MULAI			=".$this->getField("TANGGAL_MULAI").", 
					TANGGAL_SELESAI			=".$this->getField("TANGGAL_SELESAI").", 
					LAST_UPDATE_USER		='".$this->getField("LAST_UPDATE_USER")."', 
					LAST_UPDATE_DATE		= CURRENT_DATE
					, AN_TAMBAHAN= '".$this->getField("AN_TAMBAHAN")."'
					, STATUS_AKTIF= '".$this->getField("STATUS_AKTIF")."'
				WHERE PEJABAT_PENGGANTI_ID	='".$this->getField("PEJABAT_PENGGANTI_ID")."'
		";

		$this->query = $str;
		// echo $str;exit();
		return $this->execQuery($str);
	}

	function updateByField()
	{
		$str = "UPDATE PEJABAT_PENGGANTI A SET
			".$this->getField("FIELD")."= '".$this->getField("FIELD_VALUE")."'
			WHERE PEJABAT_PENGGANTI_ID = ".$this->getField("PEJABAT_PENGGANTI_ID");

		$this->query = $str;
		// echo $str;exit();
		return $this->execQuery($str);
	}	

	function delete()
	{
		$str = "
		DELETE FROM MUTASI_PEJABAT
		WHERE MUTASI_PEJABAT_ID = '".$this->getField("MUTASI_PEJABAT_ID")."'
		";

		$this->query = $str;
		 //echo $str;exit();
		return $this->execQuery($str);
	}

	/** 
	* Cari record berdasarkan array parameter dan limit tampilan 
	* @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","nama"=>"yyy") 
	* @param int limit Jumlah maksimal record yang akan diambil 
	* @param int from Awal record yang diambil 
	* @return boolean True jika sukses, false jika tidak 
	**/ 
	function selectByParams($paramsArray=array(), $limit=-1, $from=-1, $statement="", $order=" ORDER BY MUTASI_PEJABAT_ID ASC")
	{
		$str = "
		SELECT 
			MUTASI_PEJABAT_ID, CABANG_ID, NO_SK, TIPE, PEGAWAI_CABANG_ID
			, PEGAWAI_NIP, PEGAWAI_NAMA, PEGAWAI_JABATAN_NAMA
			, UNIT_KERJA_ID, UNIT_KERJA_NAMA
			, PEGAWAI_JABATAN_NIP_BARU, PEGAWAI_JABATAN_PEGAWAI_BARU, PEGAWAI_JABATAN_NAMA_BARU
			, PEGAWAI_JABATAN_UNIT_KERJA_ID, PEGAWAI_JABATAN_UNIT_KERJA_NAMA
			, PEGAWAI_JABATAN_UNIT_KERJA_ENTRI
			, TANGGAL_MUTASI, AKSI_PEJABAT_PENGGANTI, STATUS
			, LAST_CREATE_USER, LAST_CREATE_DATE, LAST_UPDATE_USER, LAST_UPDATE_DATE
			, CASE 
			WHEN COALESCE(NULLIF(A.PEGAWAI_JABATAN_UNIT_KERJA_ENTRI, ''), NULL) IS NOT NULL 
			THEN PEGAWAI_JABATAN_NAMA_BARU || 
				CASE WHEN COALESCE(NULLIF(A.PEGAWAI_JABATAN_NIP_BARU, ''), NULL) IS NOT NULL 
				THEN '<br/>Pejabat lama di mutasi ke '
				ELSE '<br/>'
				END
				|| PEGAWAI_JABATAN_UNIT_KERJA_ENTRI
			ELSE PEGAWAI_JABATAN_NAMA_BARU
			END PEGAWAI_JABATAN_NAMA_BARU_INFO
			, CASE TIPE WHEN 1 THEN 'Mutasi Tukar Jabatan' WHEN 2 THEN 'Mutasi' WHEN 3 THEN 'Pensiun' WHEN 4 THEN 'Staff' END TIPE_INFO
		FROM MUTASI_PEJABAT A
		WHERE 1 = 1
		"; 

		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}

		$str .= $statement." ".$order;
		$this->query = $str;
		// echo $str;exit();
		return $this->selectLimit($str,$limit,$from); 
	}

	function selectByParamsSatuanKerja($paramsArray=array(), $limit=-1, $from=-1, $statement="", $order=" ORDER BY SATUAN_KERJA_ID ASC")
	{
		$str = "
		SELECT 
			SATUAN_KERJA_ID, SATUAN_KERJA_ID_PARENT, AMBIL_SATUAN_KERJA_NAMA(SATUAN_KERJA_ID_PARENT) UNIT_KERJA,
			NAMA, NIP, KODE_SURAT, KODE_SO, NAMA_PEGAWAI, EMAIL, HP, JABATAN,
			CASE WHEN COALESCE(NULLIF(JABATAN,'') , NULL ) IS NULL THEN NAMA ELSE CONCAT(JABATAN, ' ', NAMA) END NAMA_INFO,
			TREE_ID, TREE_PARENT, KODE_LEVEL, KODE_LEVEL, KODE_PARENT, KELOMPOK_JABATAN, STATUS_AKTIF,
			CASE WHEN STATUS_AKTIF = '1' THEN 'Aktif' ELSE 'Non-Aktif' END STATUS_AKTIF_DESC
				, CASE A.SATUAN_KERJA_ID_PARENT
			WHEN '0'
			THEN
			' <a onClick=\"window.location =(''main/index/jabatan_struktural_add?reqId=' || A.SATUAN_KERJA_ID || ''')\"><img src=\"images/icn_edit.png\"></a>'
			ELSE
			'<a onClick=\"window.location =(''main/index/jabatan_struktural_add?reqId=' || A.SATUAN_KERJA_ID || ''')\"><img src=\"images/icn_edit.png\"></a>'
			END
			LINK_URL
			, USER_BANTU, USER_BANTU_NAMA
		FROM SATUAN_KERJA_FIX A
		WHERE 1 = 1
		"; 

		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}

		$str .= $statement." ".$order;
		$this->query = $str;
		// echo $str;exit();
		return $this->selectLimit($str,$limit,$from); 
	}

	function getCountByParamsSatuanKerja($paramsArray=array(), $statement="")
	{
		$str = "
		SELECT COUNT(1) AS ROWCOUNT 
		FROM SATUAN_KERJA_FIX A
		-- INNER JOIN (SELECT SATUAN_KERJA_ID SK_ID, SATUAN_KERJA_ID_PARENT SK_CABANG_ID FROM SATUAN_KERJA) SK ON A.SATUAN_KERJA_ID = SK.SK_ID
		WHERE 1 = 1 ".$statement; 

		while(list($key,$val)=each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}

		// echo $str;exit;
		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0; 
	}
	
	function selectByParamsMonitoring($paramsArray=array(), $limit=-1, $from=-1, $statement="", $order=" ORDER BY PEJABAT_PENGGANTI_ID ASC")
	{
		$str = "SELECT PEJABAT_PENGGANTI_ID, SATUAN_KERJA_ID, CABANG_ID, SATUAN_KERJA, PEGAWAI_ID, NAMA, PEGAWAI_ID_PENGGANTI, 
					NAMA_PENGGANTI, TANGGAL_MULAI, TANGGAL_SELESAI, A.LAST_CREATE_USER, 
					A.LAST_CREATE_DATE, A.LAST_UPDATE_USER, A.LAST_UPDATE_DATE
					, A.AN_TAMBAHAN, A.STATUS_AKTIF
				FROM PEJABAT_PENGGANTI A
				LEFT JOIN PEGAWAI B ON A.PEJABAT_PENGGANTI_ID=B.PEGAWAI_ID
				WHERE 1 = 1
		"; 

		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		$str .= $statement." ".$order;
		$this->query = $str;
		// echo $str;exit();
		return $this->selectLimit($str,$limit,$from); 
	}


	/** 
	* Hitung jumlah record berdasarkan parameter (array). 
	* @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","nama"=>"yyy") 
	* @return long Jumlah record yang sesuai kriteria 
	**/ 
	function getCountByParams($paramsArray=array(), $statement="")
	{
		$str = "
			SELECT COUNT(MUTASI_PEJABAT_ID) AS ROWCOUNT FROM MUTASI_PEJABAT A 
			WHERE 1 = 1 ".$statement; 

		while(list($key,$val)=each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}

		// echo $str;exit;
		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0; 
	}

	function getCountByParamsMonitoring($paramsArray=array(), $statement="")
	{
		$str = "SELECT COUNT(PEJABAT_PENGGANTI_ID) AS ROWCOUNT FROM PEJABAT_PENGGANTI A
			LEFT JOIN PEGAWAI B ON A.PEJABAT_PENGGANTI_ID=B.PEGAWAI_ID
			WHERE 1 = 1 ".$statement; 

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