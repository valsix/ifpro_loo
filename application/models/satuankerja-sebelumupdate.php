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
include_once(APPPATH . '/models/Entity.php');

class SatuanKerja extends Entity
{

	var $query;
	/**
	 * Class constructor.
	 **/
	function SatuanKerja()
	{
		$this->Entity();
	}

	function insert()
	{

		$str = "SELECT COUNT(SATUAN_KERJA_ID) AS ROWCOUNT FROM SATUAN_KERJA WHERE 1 = 1 
				AND TREE_ID = '" . $this->getField("TREE_ID") . "'  
				AND TREE_PARENT = '" . $this->getField("TREE_PARENT") . "' 
				AND KODE_LEVEL = '" . $this->getField("KODE_LEVEL") . "' 
				AND KODE_LEVEL = '" . $this->getField("KODE_LEVEL") . "' ";

		$this->firstRow();
		$adaData = $this->getField("ROWCOUNT");

		if ($adaData == 0) {
			/*Auto-generate primary key(s) by next max value (integer) */
			//$this->setField("SATUAN_KERJA_ID", $this->getNextId("SATUAN_KERJA_ID","SATUAN_KERJA")); 
			$str = "INSERT INTO SATUAN_KERJA (
					   SATUAN_KERJA_ID, SATUAN_KERJA_ID_PARENT, NAMA, NAMA_PEGAWAI,
					 	  TREE_ID, TREE_PARENT, KODE_LEVEL, KODE_LEVEL) 
					VALUES(
					  '" . $this->getField("SATUAN_KERJA_ID") . "',
					  		'SATKER',
					  '" . $this->getField("NAMA") . "',
					  '" . $this->getField("NAMA_PEGAWAI") . "',
					  '" . $this->getField("TREE_ID") . "',
					  '" . $this->getField("TREE_PARENT") . "',
					  '" . $this->getField("KODE_LEVEL") . "',
					  '" . $this->getField("KODE_LEVEL") . "'
					)";
			$this->id = $this->getField("SATUAN_KERJA_ID");
			$this->query = $str;
			return $this->execQuery($str);
		} else
			return true;
	}

	function insertHCIS()
	{

		$str = "SELECT COUNT(SATUAN_KERJA_ID) AS ROWCOUNT FROM SATUAN_KERJA WHERE 1 = 1 
				AND TREE_ID = '" . $this->getField("TREE_ID") . "' ";

		$query = $this->db->query($str);
		$row = $query->first_row();
		$adaData = $row->ROWCOUNT;

		if ($adaData == 0) {
			/*Auto-generate primary key(s) by next max value (integer) */
			//$this->setField("SATUAN_KERJA_ID", $this->getNextId("SATUAN_KERJA_ID","SATUAN_KERJA_HCIS")); 
			$str = "INSERT INTO SATUAN_KERJA (
					   SATUAN_KERJA_ID, SATUAN_KERJA_ID_PARENT, URUT, KODE_SO, KODE_PARENT, NAMA, NAMA_PEGAWAI,
					   NIP,
					   TREE_ID, TREE_PARENT, KODE_LEVEL, KODE_SURAT, KODE_SURAT_KELUAR) 
					VALUES(
					  '" . $this->getField("SATUAN_KERJA_ID") . "',
					  '" . $this->getField("SATUAN_KERJA_ID_PARENT") . "',
					  " . $this->getField("URUT") . ",
					  '" . $this->getField("KODE_SO") . "',
					  '" . $this->getField("KODE_PARENT") . "',
					  '" . $this->getField("NAMA") . "',
					  '" . $this->getField("NAMA_PEGAWAI") . "',
					  (SELECT MAX(NIP) FROM PEGAWAI WHERE UPPER(DEPARTEMEN) = UPPER('" . $this->getField("NAMA") . "') AND 
					  	(
							UPPER(JABATAN) LIKE '%SENIOR%' OR 
							UPPER(JABATAN) LIKE '%PRESIDENT%' OR 
			     			UPPER(JABATAN) LIKE '%MANAGER%' OR 
			     			UPPER(JABATAN) LIKE '%DIREKTUR%' OR 
			     			UPPER(JABATAN) LIKE '%HEAD'
						) AND
						SATUAN_KERJA_ID = '" . $this->getField("SATUAN_KERJA_ID_PARENT") . "'
					  ),
					  '" . $this->getField("TREE_ID") . "',
					  '" . $this->getField("TREE_PARENT") . "',
					  '" . $this->getField("KODE_LEVEL") . "',
					  '" . $this->getField("KODE_SURAT") . "',
					  '" . $this->getField("KODE_SURAT_KELUAR") . "'
					)";
		} else {
			$str = "UPDATE SATUAN_KERJA SET
				  NIP = (SELECT MAX(NIP) FROM PEGAWAI WHERE UPPER(DEPARTEMEN) = UPPER('" . $this->getField("NAMA") . "') AND 
							(
								UPPER(JABATAN) LIKE '%SENIOR%' OR 
								UPPER(JABATAN) LIKE '%PRESIDENT%' OR 
								UPPER(JABATAN) LIKE '%MANAGER%' OR 
								UPPER(JABATAN) LIKE '%DIREKTUR%' OR 
								UPPER(JABATAN) LIKE '%HEAD'
							) AND
							SATUAN_KERJA_ID = '" . $this->getField("SATUAN_KERJA_ID_PARENT") . "'
						  )
					WHERE SATUAN_KERJA_ID = '" . $this->getField("SATUAN_KERJA_ID") . "'
				";
		}

		$this->id = $this->getField("SATUAN_KERJA_ID");
		$this->query = $str;
		return $this->execQuery($str);
	}

	function insertJabatanStruktural()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		// $this->setField("SATUAN_KERJA_ID", $this->getNextId("SATUAN_KERJA_ID","SATUAN_KERJA_HCIS")); 
		$nextSatKerId= $this->nextSatuanKerjaId($this->getField("SATUAN_KERJA_ID"));

		$str = "INSERT INTO SATUAN_KERJA (
				   SATUAN_KERJA_ID, SATUAN_KERJA_ID_PARENT, KODE_SO, KODE_PARENT, NAMA
				   , KODE_SURAT, KODE_SURAT_KELUAR, JABATAN, TREE_ID, TREE_PARENT
				   , KELOMPOK_JABATAN, LAST_CREATE_USER, LAST_CREATE_DATE,LOKASI ) 
				VALUES(
				  '" . $nextSatKerId . "',
				  '" . $this->getField("SATUAN_KERJA_ID_PARENT") . "',
				  '" . $nextSatKerId . "',
				  '" . $this->getField("SATUAN_KERJA_ID") . "',
				  '" . $this->getField("NAMA") . "',
				  '" . $this->getField("KODE_SURAT") . "',
				  '" . $this->getField("KODE_SURAT") . "',
				  '" . $this->getField("JABATAN") . "',
				  '" . $nextSatKerId . "',
				  '" . $this->getField("SATUAN_KERJA_ID") . "',
				  '" . $this->getField("KELOMPOK_JABATAN") . "',
				  '" . $this->getField("LAST_CREATE_USER") . "',
				  CURRENT_DATE,
				  '" . $this->getField("LOKASI") . "'				  
				)";
		
		$this->id = $nextSatKerId;
		$this->query = $str;
		// echo $str;exit();
		return $this->execQuery($str);
	}

	function nextSatuanKerjaId($satkerid)
	{
		$str = "SELECT generatesatuankerjaid('".$satkerid."') AS NEXT_SATKER_ID";

		$query = $this->db->query($str);
		$row = $query->first_row();

		return $row->next_satker_id;
	}

	function update()
	{
		//SATUAN_KERJA_ID_PARENT = '".$this->getField("SATUAN_KERJA_ID_PARENT")."',
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "UPDATE SATUAN_KERJA SET
				  NAMA = '" . $this->getField("NAMA") . "',
				  NIP = '" . $this->getField("NIP") . "',
				  KODE_SURAT = '" . $this->getField("KODE_SURAT") . "',
				  KODE_SO = '" . $this->getField("KODE_SO") . "',
				  NAMA_PEGAWAI = '" . $this->getField("NAMA_PEGAWAI") . "',
				  EMAIL = '" . $this->getField("EMAIL") . "',
				  HP= '" . $this->getField("HP") . "',
				  JABATAN = '" . $this->getField("JABATAN") . "'
				WHERE SATUAN_KERJA_ID = '" . $this->getField("SATUAN_KERJA_ID") . "'
				";
		$this->query = $str;
		// echo $str;exit;
		return $this->execQuery($str);
	}

	function updateJabatanStruktural()
	{
		//SATUAN_KERJA_ID_PARENT = '".$this->getField("SATUAN_KERJA_ID_PARENT")."',
		/*Auto-generate primary key(s) by next max value (integer) */
		// tambahan khusus
		$str = "UPDATE SATUAN_KERJA SET
				  NIP= '" . $this->getField("NIP") . "',
				  NAMA = '" . $this->getField("NAMA") . "',
				  KODE_SURAT = '" . $this->getField("KODE_SURAT") . "',
				  NAMA_PEGAWAI = '" . $this->getField("NAMA_PEGAWAI") . "',
				  JABATAN = '" . $this->getField("JABATAN") . "',
				  KELOMPOK_JABATAN = '" . $this->getField("KELOMPOK_JABATAN") . "'
				  , USER_BANTU= '" . $this->getField("USER_BANTU") . "'
				  , STATUS_AKTIF= '" . $this->getField("STATUS_AKTIF") . "'
				  , LOKASI= '" . $this->getField("LOKASI") . "'
				WHERE SATUAN_KERJA_ID = '" . $this->getField("SATUAN_KERJA_ID") . "'
				";
		$this->query = $str;
		// echo $str;exit;
		return $this->execQuery($str);
	}


	function updateAlamat()
	{
		//SATUAN_KERJA_ID_PARENT = '".$this->getField("SATUAN_KERJA_ID_PARENT")."',
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "UPDATE KODE_UNIT_KERJA SET
				  ALAMAT = '" . $this->getField("ALAMAT") . "',
				  KETERANGAN = '" . $this->getField("KETERANGAN") . "',
				  KODE = '" . $this->getField("KODE") . "',
				  FAX = '" . $this->getField("FAX") . "',
				  TELEPON = '" . $this->getField("TELEPON") . "',
				  LOKASI = '" . $this->getField("LOKASI") . "',
				  LAST_UPDATE_USER = '" . $this->getField("LAST_UPDATE_USER") . "',
				  LAST_UPDATE_DATE = CURRENT_DATE
				WHERE KODE = '" . $this->getField("KODE") . "'
				";
		$this->query = $str;
		return $this->execQuery($str);
	}

	function updateAlamatBaru()
	{
		//SATUAN_KERJA_ID_PARENT = '".$this->getField("SATUAN_KERJA_ID_PARENT")."',
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "UPDATE KODE_UNIT_KERJA SET
				  ALAMAT = '" . $this->getField("ALAMAT") . "',
				  KETERANGAN = '" . $this->getField("KETERANGAN") . "',
				  FAX = '" . $this->getField("FAX") . "',
				  TELEPON = '" . $this->getField("TELEPON") . "',
				  LOKASI = '" . $this->getField("LOKASI") . "',
				  LAST_UPDATE_USER = '" . $this->getField("LAST_UPDATE_USER") . "',
				  LAST_UPDATE_DATE = CURRENT_DATE
				WHERE KODE_UNIT_KERJA_ID = '" . $this->getField("KODE_ID") . "'
				";
		$this->query = $str;
		return $this->execQuery($str);
	}

	//tambahan update satuan kerja
	function updateSatker()
	{
		//SATUAN_KERJA_ID_PARENT = '".$this->getField("SATUAN_KERJA_ID_PARENT")."',
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "UPDATE SATUAN_KERJA SET
				  KODE_SURAT = '" . $this->getField("KODE_SURAT") . "',
				  KODE_SURAT_KELUAR = '" . $this->getField("KODE_SURAT_KELUAR") . "'
				WHERE SATUAN_KERJA_ID_PARENT = 'SATKER' 
				AND SATUAN_KERJA_ID = '" . $this->getField("SATKER_ID") . "' 				";
		$this->query = $str;
		// echo $str;exit;
		return $this->execQuery($str);
	}

	function updateNama()
	{
		//SATUAN_KERJA_ID_PARENT = '".$this->getField("SATUAN_KERJA_ID_PARENT")."',
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "UPDATE SATUAN_KERJA A SET
				  NAMA_PEGAWAI = (SELECT NAMA FROM PEGAWAI X WHERE X.NIP = A.NIP),
				  JABATAN = (SELECT JABATAN FROM PEGAWAI X WHERE X.NIP = A.NIP)
				WHERE NOT COALESCE(NULLIF(A.NIP, ''), 'X') = 'X'
				";
		$this->query = $str;
		return $this->execQuery($str);
	}

	function updateByField()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "UPDATE SATUAN_KERJA A SET
				  " . $this->getField("FIELD") . " 		= '" . $this->getField("FIELD_VALUE") . "',
				  LAST_UPDATE_USER	= '" . $this->getField("LAST_UPDATE_USER") . "',
				  LAST_UPDATE_DATE	= CURRENT_DATE
				WHERE SATUAN_KERJA_ID = '" . $this->getField("SATUAN_KERJA_ID") . "'
				";
		$this->query = $str;
		return $this->execQuery($str);
	}

	function updateByField2()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "UPDATE SATUAN_KERJA A SET
				  " . $this->getField("FIELD1") . " 		= '" . $this->getField("FIELD_VALUE1") . "',
				  " . $this->getField("FIELD2") . " 		= '" . $this->getField("FIELD_VALUE2") . "',
				  LAST_UPDATE_USER	= '" . $this->getField("LAST_UPDATE_USER") . "',
				  LAST_UPDATE_DATE	= CURRENT_DATE
				WHERE SATUAN_KERJA_ID = '" . $this->getField("SATUAN_KERJA_ID") . "'
				";
		$this->query = $str;
		//echo $str; exit;
		return $this->execQuery($str);
	}

	function updateByField3()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "UPDATE SATUAN_KERJA A SET
				  " . $this->getField("FIELD1") . " 		= '" . $this->getField("FIELD_VALUE1") . "',
				  " . $this->getField("FIELD2") . " 		= '" . $this->getField("FIELD_VALUE2") . "',
				  " . $this->getField("FIELD3") . " 		= '" . $this->getField("FIELD_VALUE3") . "',
				  LAST_UPDATE_USER	= '" . $this->getField("LAST_UPDATE_USER") . "',
				  LAST_UPDATE_DATE	= CURRENT_DATE
				WHERE SATUAN_KERJA_ID = '" . $this->getField("SATUAN_KERJA_ID") . "'
				";
		$this->query = $str;
		// echo $str;
		// exit;
		return $this->execQuery($str);
	}

	function delete()
	{
		$str = "DELETE FROM SATUAN_KERJA
                WHERE 
                  SATUAN_KERJA_ID = '" . $this->getField("SATUAN_KERJA_ID") . "'";

		$this->query = $str;
		return $this->execQuery($str);
	}


	function deleteHCIS()
	{
		$str = "DELETE FROM SATUAN_KERJA";

		$this->query = $str;
		return $this->execQuery($str);
	}

	/** 
	 * Cari record berdasarkan array parameter dan limit tampilan 
	 * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","nama"=>"yyy") 
	 * @param int limit Jumlah maksimal record yang akan diambil 
	 * @param int from Awal record yang diambil 
	 * @return boolean True jika sukses, false jika tidak 
	 **/
	function selectByParams($paramsArray=array(),$limit=-1,$from=-1,$statement="", $order=" ORDER BY SATUAN_KERJA_ID ASC")
	{
		$str = "
				SELECT 
					lokasi_nama, lokasi,SATUAN_KERJA_ID, SATUAN_KERJA_ID_PARENT, AMBIL_SATUAN_KERJA_NAMA(SATUAN_KERJA_ID_PARENT) UNIT_KERJA,
					NAMA, NIP, KODE_SURAT, KODE_SO, NAMA_PEGAWAI, EMAIL, HP, JABATAN,
					CASE WHEN COALESCE(NULLIF(JABATAN,'') , NULL ) IS NULL THEN NAMA ELSE CONCAT(JABATAN, ' ', NAMA) END NAMA_INFO,
					TREE_ID, TREE_PARENT, KODE_LEVEL, KODE_LEVEL, KODE_PARENT, KELOMPOK_JABATAN, STATUS_AKTIF,
					CASE WHEN STATUS_AKTIF = '1' THEN 'Aktif' ELSE 'Non-Aktif' END STATUS_AKTIF_DESC
					, CASE A.SATUAN_KERJA_ID_PARENT
					WHEN '0'
					THEN
					'<a title=\"Tambah\" onClick=\"window.location =(''main/index/jabatan_struktural_add?reqId=' || A.SATUAN_KERJA_ID || '&reqParentId=' || A.SATUAN_KERJA_ID_PARENT || '&reqMode=insert'')\"><img src=\"images/tree-add.png\"></a> | <a title=\"Edit\" onClick=\"window.location =(''main/index/jabatan_struktural_add?reqId=' || A.SATUAN_KERJA_ID || '&reqParentId=' || A.SATUAN_KERJA_ID_PARENT || ''')\"><img src=\"images/tree-edit.png\"></a>'
					ELSE
					'<a title=\"Tambah\" onClick=\"window.location =(''main/index/jabatan_struktural_add?reqId=' || A.SATUAN_KERJA_ID || '&reqParentId=' || A.SATUAN_KERJA_ID_PARENT || '&reqMode=insert'')\"><img src=\"images/tree-add.png\"></a> | <a title=\"Edit\" onClick=\"window.location =(''main/index/jabatan_struktural_add?reqId=' || A.SATUAN_KERJA_ID || '&reqParentId=' || A.SATUAN_KERJA_ID_PARENT || ''')\"><img src=\"images/tree-edit.png\"></a>'
					END
					LINK_URL
					,  '<a style=\"cursor:pointer\" title=\"Tambah\" onClick=\"window.location =(''app/loadUrl/main/pegawai_add_satuan_kerja_add?reqId=' || A.SATUAN_KERJA_ID || '&reqParentId=' || A.SATUAN_KERJA_ID_PARENT || '&reqMode=insert'')\"><img src=\"images/tree-add.png\"></a>' LINK_URL_PEGAWAI
					, USER_BANTU, USER_BANTU_NAMA, CHECK_ADA_PEJABAT
					, UNIT_KERJA_NAMA
				FROM SATUAN_KERJA_FIX A
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

    function selectByParamsNew($paramsArray=array(),$limit=-1,$from=-1,$statement="", $order=" ORDER BY SATUAN_KERJA_ID ASC")
	{
		$str = "
				
				SELECT 
					SATUAN_KERJA_ID, SATUAN_KERJA_ID_PARENT, AMBIL_SATUAN_KERJA_NAMA(SATUAN_KERJA_ID_PARENT) UNIT_KERJA,
					A.NAMA, NIP, KODE_SURAT, KODE_SO, NAMA_PEGAWAI, EMAIL, HP, JABATAN,
					CASE WHEN COALESCE(NULLIF(JABATAN,'') , NULL ) IS NULL THEN A.NAMA ELSE CONCAT(JABATAN, ' ', A.NAMA) END NAMA_INFO,
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
					,B.KODE_UNIT_KERJA_ID
				FROM SATUAN_KERJA_FIX A
				LEFT JOIN KODE_UNIT_KERJA B ON B.KODE = A.SATUAN_KERJA_ID
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


	function selectByParamsAlamat($paramsArray = array(), $limit = -1, $from = -1, $statement = "", $order = "")
	{
		$str = "
				SELECT KODE_UNIT_KERJA_ID, KODE_UNIT_KERJA_ID_PARENT, NAMA, KETERANGAN, 
					   NO_URUT, KODE, LAST_CREATE_USER, LAST_CREATE_DATE, LAST_UPDATE_USER, 
					   LAST_UPDATE_DATE, ALAMAT, TELEPON, FAX, LOKASI
				  FROM KODE_UNIT_KERJA A
				WHERE 1 = 1
			";

		while (list($key, $val) = each($paramsArray)) {
			$str .= " AND $key = '$val' ";
		}
		$str .= $statement . " " . $order;
		$this->query = $str;
		return $this->selectLimit($str, $limit, $from);
	}

	function selectByParamsAktif($paramsArray = array(), $limit = -1, $from = -1, $statement = "", $order = "")
	{
		$str = "
				SELECT 
					SATUAN_KERJA_ID, SATUAN_KERJA_ID_PARENT, NAMA, NIP, KODE_SURAT, KODE_SO, NAMA_PEGAWAI, EMAIL, HP, JABATAN,
					CASE WHEN COALESCE(NULLIF(JABATAN,'') , NULL ) IS NULL THEN NAMA ELSE CONCAT(JABATAN, ' ', NAMA) END NAMA_INFO,
					TREE_ID, TREE_PARENT, KODE_LEVEL, KODE_LEVEL, KODE_PARENT, KELOMPOK_JABATAN, STATUS_AKTIF,
					CASE WHEN STATUS_AKTIF = '1' THEN 'Aktif' ELSE 'Non-Aktif' END STATUS_AKTIF_DESC
				FROM SATUAN_KERJA A
				WHERE 1 = 1 AND STATUS_AKTIF = '1'
			";

		while (list($key, $val) = each($paramsArray)) {
			$str .= " AND $key = '$val' ";
		}
		$str .= $statement . " " . $order;
		$this->query = $str;
		return $this->selectLimit($str, $limit, $from);
	}



	function selectByParamsFix($paramsArray = array(), $limit = -1, $from = -1, $statement = "", $order = "")
	{
		$str = "
				SELECT 
					SATUAN_KERJA_ID, SATUAN_KERJA_ID_PARENT, NAMA, NIP, KODE_SURAT, KODE_SO, NAMA_PEGAWAI, EMAIL, HP, JABATAN,
					CASE WHEN COALESCE(NULLIF(JABATAN,'') , NULL ) IS NULL THEN NAMA ELSE CONCAT(JABATAN, ' ', NAMA) END NAMA_INFO,
					TREE_ID, TREE_PARENT, KODE_LEVEL, KODE_LEVEL, KODE_PARENT, KELOMPOK_JABATAN
				FROM SATUAN_KERJA_FIX A
				WHERE 1 = 1
			";

		while (list($key, $val) = each($paramsArray)) {
			$str .= " AND $key = '$val' ";
		}
		$str .= $statement . " " . $order;
		$this->query = $str;
		return $this->selectLimit($str, $limit, $from);
	}


	function selectByParamsSimple($paramsArray = array(), $limit = -1, $from = -1, $statement = "", $order = "")
	{
		$str = "
				SELECT 
					SATUAN_KERJA_ID, SATUAN_KERJA_ID_PARENT, NAMA, NAMA_PEGAWAI, AMBIL_HIRARKI(SATUAN_KERJA_ID) HIRARKI
				FROM SATUAN_KERJA A
				WHERE 1 = 1
			";

		while (list($key, $val) = each($paramsArray)) {
			$str .= " AND $key = '$val' ";
		}
		$str .= $statement . " " . $order;
		$this->query = $str;
		return $this->selectLimit($str, $limit, $from);
	}



	function selectByParamsHirarki($satuanKerjaId, $statement = "", $limit = -1, $from = -1)
	{
		$str = "
				SELECT SATUAN_KERJA_ID, NAMA, NIP, NAMA_PEGAWAI, JABATAN, KODE_SO, KODE_SURAT, KODE_SURAT_KELUAR, KODE_LEVEL
				, KELOMPOK_JABATAN
				, CASE WHEN COALESCE(NULLIF(A.LOKASI, ''), NULL) IS NULL THEN 'Data belum di isi, pada master satuan kerja' ELSE A.LOKASI END LOKASI
				FROM SATUAN_KERJA_FIX A
				WHERE EXISTS(SELECT 1 FROM (SELECT TRIM(UPPER(regexp_split_to_table(AMBIL_HIRARKI('" . $satuanKerjaId . "'), ','))) AS SATUAN_KERJA_ID) X 
							 WHERE X.SATUAN_KERJA_ID = A.SATUAN_KERJA_ID)
			" . $statement;

		$str .= " ORDER BY SATUAN_KERJA_ID ";

		$this->query = $str;
		// echo $str; exit;
		return $this->selectLimit($str, $limit, $from);
	}



	function selectByParamsHirarkiSO($satuanKerjaId, $statement = "", $limit = -1, $from = -1)
	{
		$str = "
				SELECT SATUAN_KERJA_ID, NAMA, NIP, NAMA_PEGAWAI, JABATAN, KODE_SO, KODE_SURAT, KODE_SURAT_KELUAR, KODE_LEVEL FROM SATUAN_KERJA_FIX A
				WHERE EXISTS(SELECT 1 FROM (SELECT TRIM(UPPER(regexp_split_to_table(AMBIL_HIRARKI_SO('" . $satuanKerjaId . "'), ','))) AS SATUAN_KERJA_ID) X 
							 WHERE X.SATUAN_KERJA_ID = A.SATUAN_KERJA_ID)
			" . $statement;

		$str .= " ORDER BY SATUAN_KERJA_ID ";

		$this->query = $str;
		// echo $str; exit;
		return $this->selectLimit($str, $limit, $from);
	}

	function selectByParamsHirarkiSO2($satuanKerjaId, $statement = "", $limit = -1, $from = -1)
	{
		$str = "
				SELECT SATUAN_KERJA_ID, NAMA, NIP, NAMA_PEGAWAI, JABATAN, KODE_SO, KODE_SURAT, KODE_SURAT_KELUAR, KODE_LEVEL FROM SATUAN_KERJA_FIX A
				WHERE 1=1
			" . $statement;

		$str .= " ORDER BY SATUAN_KERJA_ID ";

		$this->query = $str;
		// echo $str; exit;
		return $this->selectLimit($str, $limit, $from);
	}


	function selectByParamsHirarkiJabatan($satuanKerjaId, $statement = "", $limit = -1, $from = -1)
	{
		$str = "
				SELECT SATUAN_KERJA_ID, NAMA, NIP, NAMA_PEGAWAI, JABATAN, KODE_SO, KODE_SURAT, KODE_SURAT_KELUAR, KODE_LEVEL FROM SATUAN_KERJA A
				WHERE EXISTS(SELECT 1 FROM (SELECT TRIM(UPPER(regexp_split_to_table(AMBIL_HIRARKI('" . $satuanKerjaId . "'), ','))) AS SATUAN_KERJA_ID) X 
							 WHERE X.SATUAN_KERJA_ID = A.SATUAN_KERJA_ID)
			" . $statement;

		$str .= " ORDER BY SATUAN_KERJA_ID ";

		$this->query = $str;
		//echo $str;
		return $this->selectLimit($str, $limit, $from);
	}


	function selectByParamsLike($paramsArray = array(), $limit = -1, $from = -1, $statement = "")
	{
		$str = "
				SELECT 
				SATUAN_KERJA_ID, SATUAN_KERJA_ID_PARENT, NAMA, NIP, KODE_SURAT, KODE_SO, NAMA_PEGAWAI, EMAIL
				FROM SATUAN_KERJA
				WHERE 1 = 1
				";
		while (list($key, $val) = each($paramsArray)) {
			$str .= " AND $key LIKE '%$val%' ";
		}

		$str .= $statement . " ORDER BY SATUAN_KERJA_ID DESC";
		$this->query = $str;
		return $this->selectLimit($str, $limit, $from);
	}
	/** 
	 * Hitung jumlah record berdasarkan parameter (array). 
	 * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","nama"=>"yyy") 
	 * @return long Jumlah record yang sesuai kriteria 
	 **/
	function getCountByParamsFix($paramsArray = array(), $statement = "")
	{
		$str = "SELECT COUNT(1) AS ROWCOUNT FROM SATUAN_KERJA_FIX WHERE 1 = 1 " . $statement;
		while (list($key, $val) = each($paramsArray)) {
			$str .= " AND $key = '$val' ";
		}

		$this->select($str);
		if ($this->firstRow())
			return $this->getField("ROWCOUNT");
		else
			return 0;
	}

	function getCountByParams($paramsArray = array(), $statement = "")
	{
		$str = "SELECT COUNT(SATUAN_KERJA_ID) AS ROWCOUNT FROM SATUAN_KERJA WHERE 1 = 1 " . $statement;
		while (list($key, $val) = each($paramsArray)) {
			$str .= " AND $key = '$val' ";
		}
		$this->query = $str;
		$this->select($str);
		if ($this->firstRow())
			return $this->getField("ROWCOUNT");
		else
			return 0;
	}

	function getCountByParamsNew($paramsArray = array(), $statement = "")
	{
		$str = "SELECT COUNT(SATUAN_KERJA_ID) AS ROWCOUNT FROM SATUAN_KERJA WHERE 1 = 1 " . $statement;
		while (list($key, $val) = each($paramsArray)) {
			$str .= " AND $key = '$val' ";
		}

		$this->select($str);
		if ($this->firstRow())
			return $this->getField("ROWCOUNT");
		else
			return 0;
	}

	function getHirarki($reqSatuanKerjaId)
	{
		$str = "select AMBIL_HIRARKI('" . $reqSatuanKerjaId . "') HIRARKI  ";


		$this->select($str);
		if ($this->firstRow())
			return $this->getField("HIRARKI");
		else
			return "";
	}

	function getNipAtasan($reqSatuanKerjaId)
	{
		$str = "SELECT NIP FROM SATUAN_KERJA WHERE SATUAN_KERJA_ID = '" . $reqSatuanKerjaId . "' ";


		$this->select($str);
		if ($this->firstRow())
			return $this->getField("NIP");
		else
			return "";
	}


	function getStatusPembuatSurat($reqSatuanKerjaId, $reqNipLogin)
	{
		$str = "SELECT NIP FROM SATUAN_KERJA WHERE SATUAN_KERJA_ID = '" . $reqSatuanKerjaId . "' ";


		$this->select($str);
		if ($this->firstRow()) {
			if (trim($reqNipLogin) == trim($this->getField("NIP")))
				return "POSTING";
			else
				return "VALIDASI";
		} else
			return "";
	}

	function getCountByParamsLike($paramsArray = array())
	{
		$str = "SELECT COUNT(SATUAN_KERJA_ID) AS ROWCOUNT FROM SATUAN_KERJA WHERE 1 = 1 ";
		while (list($key, $val) = each($paramsArray)) {
			$str .= " AND $key LIKE '%$val%' ";
		}

		$this->select($str);
		if ($this->firstRow())
			return $this->getField("ROWCOUNT");
		else
			return 0;
	}

	function selectByParamsKelompokJabatan($statement = "", $limit = -1, $from = -1)
	{
		$str = "
		SELECT KELOMPOK_JABATAN
		FROM SATUAN_KERJA
		WHERE COALESCE(NULLIF(KELOMPOK_JABATAN, ''), NULL) IS NOT NULL " . $statement;

		$str .= " GROUP BY KELOMPOK_JABATAN ORDER BY KELOMPOK_JABATAN";
		$this->query = $str;
		// echo $str; exit;
		return $this->selectLimit($str, $limit, $from);
	}

	function selectsatuankerjafix($paramsArray=array(),$limit=-1,$from=-1,$statement="", $order=" ORDER BY SATUAN_KERJA_ID ASC")
	{
		$str = "
		SELECT 
		A.*
		FROM SATUAN_KERJA_FIX A
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

    function selectsatuankerjafixkelompok($paramsArray=array(),$limit=-1,$from=-1,$statement="", $order=" ORDER BY SATUAN_KERJA_ID ASC")
	{
		$str = "
		SELECT 
		A.*,B.BIAYA
		FROM SATUAN_KERJA_FIX A
		LEFT JOIN KELOMPOK B ON B.KELOMPOK_ID = A.KELOMPOK_JABATAN
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

    function selectsatuankerjatree($paramsArray = array(), $limit = -1, $from = -1, $nip="", $statement = "", $order = "ORDER BY DEPTH DESC")
	{
		$str = "
		WITH RECURSIVE PARENTS AS (
			SELECT SATUAN_KERJA_ID, TREE_ID, TREE_PARENT, NAMA, NAMA_PEGAWAI, NIP, JABATAN, KELOMPOK_JABATAN, 0 AS DEPTH
			FROM satuan_kerja_fix
			WHERE SATUAN_KERJA_ID ~ '^[0-9\.]+$'
			AND SATUAN_KERJA_ID IN (SELECT KODE_PARENT FROM SATUAN_KERJA_FIX WHERE NIP = '".$nip."')
			UNION
			SELECT OP.SATUAN_KERJA_ID, OP.TREE_ID, OP.TREE_PARENT, OP.NAMA, OP.NAMA_PEGAWAI, OP.NIP, OP.JABATAN, OP.KELOMPOK_JABATAN, DEPTH - 1
			FROM satuan_kerja_fix OP
			JOIN PARENTS P ON OP.TREE_ID = P.TREE_PARENT
			WHERE OP.SATUAN_KERJA_ID ~ '^[0-9\.]+$'
		)
		SELECT * FROM PARENTS
		WHERE 1=1
		";

		while (list($key, $val) = each($paramsArray)) {
			$str .= " AND $key = '$val' ";
		}
		$str .= $statement . " " . $order;
		$this->query = $str;
		return $this->selectLimit($str, $limit, $from);
	}

}
