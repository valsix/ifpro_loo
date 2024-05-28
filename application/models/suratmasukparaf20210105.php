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

class SuratMasukParaf extends Entity
{

	var $query;
	/**
	 * Class constructor.
	 **/
	function SuratMasukParaf()
	{
		$this->Entity();
	}

	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("SURAT_MASUK_PARAF_ID", $this->getNextId("SURAT_MASUK_PARAF_ID", "SURAT_MASUK_PARAF"));
		$str = "INSERT INTO SURAT_MASUK_PARAF (
				   SURAT_MASUK_PARAF_ID, SURAT_MASUK_ID, SATUAN_KERJA_ID_TUJUAN, LAST_CREATE_USER, LAST_CREATE_DATE) 
				VALUES ( '" . $this->getField("SURAT_MASUK_PARAF_ID") . "', '" . $this->getField("SURAT_MASUK_ID") . "', 
						 '" . $this->getField("SATUAN_KERJA_ID_TUJUAN") . "', '" . $this->getField("LAST_CREATE_USER") . "', NOW())";

		$this->id = $this->getField("SURAT_MASUK_PARAF_ID");
		$this->query = $str;

		return $this->execQuery($str);
	}

	function insertbantu()
	{
		$this->setField("SURAT_MASUK_PARAF_ID", $this->getNextId("SURAT_MASUK_PARAF_ID", "SURAT_MASUK_PARAF"));
		$str = "INSERT INTO SURAT_MASUK_PARAF (
				   SURAT_MASUK_PARAF_ID, SURAT_MASUK_ID, SATUAN_KERJA_ID_TUJUAN, LAST_CREATE_USER, LAST_CREATE_DATE, STATUS_BANTU) 
				VALUES ( '" . $this->getField("SURAT_MASUK_PARAF_ID") . "', '" . $this->getField("SURAT_MASUK_ID") . "', 
						 '" . $this->getField("SATUAN_KERJA_ID_TUJUAN") . "', '" . $this->getField("LAST_CREATE_USER") . "', NOW(), 1)";

		$this->id = $this->getField("SURAT_MASUK_PARAF_ID");
		$this->query = $str;

		return $this->execQuery($str);
	}

	function update()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "
			   UPDATE SURAT_MASUK_PARAF
				SET    SATUAN_KERJA_ID_TUJUAN      	= '" . $this->getField("SATUAN_KERJA_ID_TUJUAN") . "',
					   LAST_UPDATE_USER   	= '" . $this->getField("LAST_UPDATE_USER") . "',
					   LAST_UPDATE_DATE   	= NOW()
				WHERE  SURAT_MASUK_PARAF_ID    	= '" . $this->getField("SURAT_MASUK_PARAF_ID") . "'
				";
		$this->query = $str;
		return $this->execQuery($str);
	}


	function paraf()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "
			   UPDATE SURAT_MASUK_PARAF
				SET    TERBACA      		= 1,
					   LAST_UPDATE_USER   	= '" . $this->getField("LAST_UPDATE_USER") . "',
					   LAST_UPDATE_DATE   	= NOW()
				WHERE  SURAT_MASUK_ID    	= '" . $this->getField("SURAT_MASUK_ID") . "' AND
	 				   USER_ID    			= '" . $this->getField("USER_ID") . "'
	 				   AND TERBACA IS NULL
				";
				// tambahan khusus
		$this->query = $str;
		return $this->execQuery($str);
	}

	function updateByField()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "UPDATE SURAT_MASUK_PARAF A SET
				  " . $this->getField("FIELD") . " 		= '" . $this->getField("FIELD_VALUE") . "',
				  LAST_UPDATE_USER 	= '" . $this->getField("LAST_UPDATE_USER") . "'
				WHERE SURAT_MASUK_PARAF_ID = " . $this->getField("SURAT_MASUK_PARAF_ID") . "
				";
		$this->query = $str;
		return $this->execQuery($str);
	}

	function tukarurutanparalel()
	{
		$str1= "
		UPDATE SURAT_MASUK_PARAF
		SET NO_URUT = ".$this->getField("NO_URUT_BANTU")."
		WHERE STATUS_BANTU = 1 AND KONDISI_PARAF = 'PARALEL' AND SURAT_MASUK_ID = ".$this->getField("SURAT_MASUK_ID")."
		";

		$this->query = $str1;
		$this->execQuery($str1);
		// echo $str1;

		$str2= "
		UPDATE SURAT_MASUK_PARAF
		SET NO_URUT = ".$this->getField("NO_URUT_DIREKSI")."
		WHERE STATUS_BANTU IS NULL AND KONDISI_PARAF = 'PARALEL' AND SURAT_MASUK_ID = ".$this->getField("SURAT_MASUK_ID")."
		";

		$this->query = $str2;
		// echo $str2;exit;
		return $this->execQuery($str2);
	}

	function deleteParent()
	{
		$str = "DELETE FROM SURAT_MASUK_PARAF
                WHERE 
                  SURAT_MASUK_ID = " . $this->getField("SURAT_MASUK_ID") . " AND COALESCE(NULLIF(STATUS_PARAF, ''), 'X') = 'X' ";

		$this->query = $str;
		return $this->execQuery($str);
	}

	function delete()
	{
		$str = "DELETE FROM SURAT_MASUK_PARAF
                WHERE 
                  SURAT_MASUK_PARAF_ID = " . $this->getField("SURAT_MASUK_PARAF_ID") . "";

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
	function selectByParams($paramsArray = array(), $limit = -1, $from = -1, $statement = "", $order = " ORDER BY SURAT_MASUK_PARAF_ID ASC")
	{
		$str = "
		SELECT 
		SURAT_MASUK_ID, SATUAN_KERJA_ID_TUJUAN, USER_ID, NAMA_USER, NAMA_SATKER, 
		STATUS_PARAF, KODE_PARAF, LAST_CREATE_USER, LAST_CREATE_DATE, 
		LAST_UPDATE_USER, LAST_UPDATE_DATE, NO_URUT
		FROM SURAT_MASUK_PARAF A
		WHERE 1 = 1
		";

		while (list($key, $val) = each($paramsArray)) {
			$str .= " AND $key = '$val' ";
		}

		$str .= $statement . " " . $order;
		$this->query = $str;
		return $this->selectLimit($str, $limit, $from);
	}
	/** 
	 * Hitung jumlah record berdasarkan parameter (array). 
	 * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","nama"=>"yyy") 
	 * @return long Jumlah record yang sesuai kriteria 
	 **/

	function getJson($paramsArray = array(), $statement = "")
	{
		$str = "
			SELECT ROW_TO_JSON(A) JSON FROM 
			(SELECT SURAT_MASUK_ID, SATUAN_KERJA_ID_TUJUAN SATUAN_KERJA_ID, NAMA_SATKER SATUAN_KERJA, NAMA_USER NAMA_PEGAWAI, STATUS_PARAF, KODE_PARAF FROM SURAT_MASUK_PARAF) A
			WHERE 1 = 1
		";
		while (list($key, $val) = each($paramsArray)) {
			$str .= " AND $key = '$val' ";
		}

		$str .= $statement;
		// echo $str;exit;
		$this->selectLimit($str, -1, -1);
		$hasil = "[";
		$i = 0;
		while ($this->nextRow()) {
			if ($i == 0)
				$hasil .= $this->getField("JSON");
			else
				$hasil .= "," . $this->getField("JSON");
			$i++;
		}
		$hasil .= "]";
		$hasil = str_replace("null", '""', $hasil);
		return strtoupper($hasil);
	}


	function getParaf($paramsArray = array(), $statement = "")
	{
		$str = "
			SELECT SURAT_MASUK_ID, SATUAN_KERJA_ID_TUJUAN SATUAN_KERJA_ID FROM SURAT_MASUK_PARAF A
			WHERE 1 = 1 AND A.STATUS_BANTU IS NULL
		";
		while (list($key, $val) = each($paramsArray)) {
			$str .= " AND $key = '$val' ";
		}

		$str .= $statement." ORDER BY SURAT_MASUK_PARAF_ID";
		$this->selectLimit($str, -1, -1);
		$i = 0;
		while ($this->nextRow()) {
			if ($i == 0)
				$hasil .= "'" . $this->getField("SATUAN_KERJA_ID") . "'";
			else
				$hasil .= "," . "'" . $this->getField("SATUAN_KERJA_ID") . "'";
			$i++;
		}
		if ($i == 0)
			$hasil = "''";

		return strtoupper($hasil);
	}

	function getCountByParams($paramsArray = array(), $statement = "")
	{
		$str = "SELECT COUNT(SURAT_MASUK_PARAF_ID) AS ROWCOUNT FROM SURAT_MASUK_PARAF A WHERE 1 = 1 " . $statement;
		while (list($key, $val) = each($paramsArray)) {
			$str .= " AND $key = '$val' ";
		}


		$this->select($str);
		if ($this->firstRow())
			return $this->getField("ROWCOUNT");
		else
			return 0;
	}

	function getNextParaf($statement= "")
	{
		$str = "
		SELECT
		CASE WHEN NO_URUT < COALESCE(NEXT_URUT,1) THEN 0 ELSE COALESCE(NEXT_URUT,1) END AS ROWCOUNT 
		FROM SURAT_MASUK_PARAF A
		WHERE 1 = 1 " . $statement;

		// echo $str;exit;
		$this->query = $str;
		$this->select($str);
		if ($this->firstRow())
			return $this->getField("ROWCOUNT");
		else
			return "";
	}
}
