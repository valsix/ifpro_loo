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

class TrLooParaf extends Entity
{

	var $query;
	/**
	 * Class constructor.
	 **/
	function TrLooParaf()
	{
		$this->Entity();
	}

	function insert()
	{
		$this->setField("TR_LOO_PARAF_ID", $this->getNextId("TR_LOO_PARAF_ID", "tr_loo_paraf"));
		$str = "
		INSERT INTO tr_loo_paraf 
		(
			TR_LOO_PARAF_ID, TR_LOO_ID, SATUAN_KERJA_ID_TUJUAN
			--, LAST_CREATE_USER, LAST_CREATE_DATE
		)
		VALUES
		(
			'".$this->getField("TR_LOO_PARAF_ID")."'
			, '".$this->getField("TR_LOO_ID")."'
			, '".$this->getField("SATUAN_KERJA_ID_TUJUAN")."'
			--, '".$this->getField("LAST_CREATE_USER")."', NOW()
		)";

		$this->id = $this->getField("TR_LOO_PARAF_ID");
		$this->query = $str;

		return $this->execQuery($str);
	}

	function insertbantu()
	{
		$this->setField("TR_LOO_PARAF_ID", $this->getNextId("TR_LOO_PARAF_ID", "tr_loo_paraf"));
		$str = "
		INSERT INTO tr_loo_paraf
		(
			TR_LOO_PARAF_ID, TR_LOO_ID, SATUAN_KERJA_ID_TUJUAN
			--, LAST_CREATE_USER, LAST_CREATE_DATE
			, STATUS_BANTU
		)
		VALUES
		(
			'".$this->getField("TR_LOO_PARAF_ID")."'
			, '".$this->getField("TR_LOO_ID")."'
			, '".$this->getField("SATUAN_KERJA_ID_TUJUAN")."'
			--, '".$this->getField("LAST_CREATE_USER")."', NOW()
			, 1
		)";

		$this->id = $this->getField("TR_LOO_PARAF_ID");
		$this->query = $str;

		return $this->execQuery($str);
	}

	function update()
	{
		$str = "
		UPDATE tr_loo_paraf
		SET
			SATUAN_KERJA_ID_TUJUAN= '".$this->getField("SATUAN_KERJA_ID_TUJUAN")."'
			--, LAST_UPDATE_USER= '".$this->getField("LAST_UPDATE_USER")."'
			--, LAST_UPDATE_DATE= NOW()
		WHERE  TR_LOO_PARAF_ID= '".$this->getField("TR_LOO_PARAF_ID")."'
		";
		$this->query = $str;
		return $this->execQuery($str);
	}

	function paraf()
	{
		$str = "
		UPDATE tr_loo_paraf
		SET
			TERBACA= 1
			--, LAST_UPDATE_USER= '".$this->getField("LAST_UPDATE_USER")."'
			--, LAST_UPDATE_DATE= NOW()
		WHERE TR_LOO_ID= '".$this->getField("TR_LOO_ID")."'
		AND USER_ID= '".$this->getField("USER_ID")."' AND TERBACA IS NULL
		";
		// tambahan khusus
		$this->query = $str;
		return $this->execQuery($str);
	}

	function updateByField()
	{
		$str = "
		UPDATE tr_loo_paraf A SET
		".$this->getField("FIELD")."= '".$this->getField("FIELD_VALUE")."'
		--, LAST_UPDATE_USER= '".$this->getField("LAST_UPDATE_USER")."'
		WHERE TR_LOO_PARAF_ID = ".$this->getField("TR_LOO_PARAF_ID")."
		";
		$this->query = $str;
		return $this->execQuery($str);
	}

	function tukarurutanparalel()
	{
		$str1= "
		UPDATE tr_loo_paraf
		SET NO_URUT = ".$this->getField("NO_URUT_BANTU")."
		WHERE STATUS_BANTU = 1 AND KONDISI_PARAF = 'PARALEL' AND TR_LOO_ID = ".$this->getField("TR_LOO_ID")."
		";

		$this->query = $str1;
		$this->execQuery($str1);
		// echo $str1;

		$str2= "
		UPDATE tr_loo_paraf
		SET NO_URUT = ".$this->getField("NO_URUT_DIREKSI")."
		WHERE STATUS_BANTU IS NULL AND KONDISI_PARAF = 'PARALEL' AND TR_LOO_ID = ".$this->getField("TR_LOO_ID")."
		";

		$this->query = $str2;
		// echo $str2;exit;
		return $this->execQuery($str2);
	}

	function deleteParent()
	{
		$str = "
		DELETE FROM tr_loo_paraf
		WHERE TR_LOO_ID = ".$this->getField("TR_LOO_ID")." AND COALESCE(NULLIF(STATUS_PARAF, ''), 'X') = 'X' ";

		$this->query = $str;
		return $this->execQuery($str);
	}

	function delete()
	{
		$str = "
		DELETE FROM tr_loo_paraf
		WHERE TR_LOO_PARAF_ID = ".$this->getField("TR_LOO_PARAF_ID")."";

		$this->query = $str;
		return $this->execQuery($str);
	}

	function resetparalelnourut()
	{
		$str1= "
		UPDATE tr_loo_paraf
		SET NO_URUT = (COALESCE((SELECT MAX(NO_URUT) FROM tr_loo_paraf WHERE TR_LOO_ID = ".$this->getField("TR_LOO_ID")." AND COALESCE(NULLIF(KONDISI_PARAF, ''), NULL) IS NULL),0) + 1)
		WHERE TR_LOO_ID = ".$this->getField("TR_LOO_ID")."
		AND UPPER(KONDISI_PARAF) = UPPER('PARALEL')
		AND STATUS_BANTU = 1
		";
		$this->query = $str1;
		// echo $str1;exit;
		$this->execQuery($str1);

		$str2= "
		UPDATE tr_loo_paraf
		SET NO_URUT = (COALESCE((SELECT MAX(NO_URUT) FROM tr_loo_paraf WHERE TR_LOO_ID = ".$this->getField("TR_LOO_ID")." AND UPPER(KONDISI_PARAF) = UPPER('PARALEL') AND STATUS_BANTU = 1),0) + 1)
		WHERE TR_LOO_ID = ".$this->getField("TR_LOO_ID")."
		AND UPPER(KONDISI_PARAF) = UPPER('PARALEL')
		AND STATUS_BANTU IS NULL
		";
		$this->query = $str2;
		// echo $str2;exit;
		return $this->execQuery($str2);
	}

	function resetnourut()
	{
		$str= "
		UPDATE tr_loo_paraf
		SET NO_URUT = ".$this->getField("NO_URUT")."
		WHERE TR_LOO_PARAF_ID = ".$this->getField("TR_LOO_PARAF_ID")."
		AND COALESCE(NULLIF(KONDISI_PARAF, ''), NULL) IS NULL
		";
		$this->query = $str;
		// echo $str;exit;
		return $this->execQuery($str);
	}

	function deleteuserbantu()
	{
		$str = "
		delete from surat_masuk_paraf a
		where 1=1
		and exists
		(
			select 1
			from
			(
				select surat_masuk_id, user_id, count(1)
				from surat_masuk_paraf
				where surat_masuk_id = ".$this->getField("TR_LOO_ID")."
				group by surat_masuk_id, user_id having count(1) > 1
			) xxx where a.surat_masuk_id = xxx.surat_masuk_id and a.user_id = xxx.user_id
		) and status_bantu is null
		";

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
	function selectByParams($paramsArray = array(), $limit = -1, $from = -1, $statement = "", $order = " ORDER BY TR_LOO_PARAF_ID ASC")
	{
		$str = "
		SELECT 
		A.*
		FROM tr_loo_paraf A
		WHERE 1 = 1
		";

		while (list($key, $val) = each($paramsArray)) {
			$str .= " AND $key = '$val' ";
		}

		$str .= $statement." ".$order;
		$this->query = $str;
		return $this->selectLimit($str, $limit, $from);
	}

	function selectByParamsGetEmail($paramsArray = array(), $limit = -1, $from = -1, $statement = "", $order = " ORDER BY NO_URUT")
	{
		$str = "
		SELECT 
		B.EMAIL, A.*
		FROM tr_loo_paraf A
		LEFT JOIN PEGAWAI B ON A.USER_ID = B.PEGAWAI_ID
		WHERE 1 = 1
		";

		while (list($key, $val) = each($paramsArray)) {
			$str .= " AND $key = '$val' ";
		}

		$str .= $statement." ".$order;
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
			(SELECT TR_LOO_ID, SATUAN_KERJA_ID_TUJUAN SATUAN_KERJA_ID, NAMA_SATKER SATUAN_KERJA, NAMA_USER NAMA_PEGAWAI, STATUS_PARAF, KODE_PARAF FROM tr_loo_paraf) A
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
				$hasil .= ",".$this->getField("JSON");
			$i++;
		}
		$hasil .= "]";
		$hasil = str_replace("null", '""', $hasil);
		return strtoupper($hasil);
	}


	function getParaf($paramsArray = array(), $statement = "")
	{
		$str = "
			SELECT TR_LOO_ID, SATUAN_KERJA_ID_TUJUAN SATUAN_KERJA_ID FROM tr_loo_paraf A
			WHERE 1 = 1 AND A.STATUS_BANTU IS NULL
		";
		while (list($key, $val) = each($paramsArray)) {
			$str .= " AND $key = '$val' ";
		}

		$str .= $statement." ORDER BY TR_LOO_PARAF_ID";
		$this->selectLimit($str, -1, -1);
		$i = 0;
		while ($this->nextRow()) {
			if ($i == 0)
				$hasil .= "'".$this->getField("SATUAN_KERJA_ID")."'";
			else
				$hasil .= ","."'".$this->getField("SATUAN_KERJA_ID")."'";
			$i++;
		}
		if ($i == 0)
			$hasil = "''";

		return strtoupper($hasil);
	}

	function getCountByParams($paramsArray = array(), $statement = "")
	{
		$str = "SELECT COUNT(TR_LOO_PARAF_ID) AS ROWCOUNT FROM tr_loo_paraf A WHERE 1 = 1 ".$statement;
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
		FROM tr_loo_paraf A
		WHERE 1 = 1 ".$statement;

		// echo $str;exit;
		$this->query = $str;
		$this->select($str);
		if ($this->firstRow())
			return $this->getField("ROWCOUNT");
		else
			return "";
	}
}
