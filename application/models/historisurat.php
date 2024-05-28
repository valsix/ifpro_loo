<?

/***
 * Entity-base class untuk mengimplementasikan tabel kategori.
 * 
 ***/
include_once(APPPATH . '/models/Entity.php');

class HistoriSurat extends Entity
{

	var $query;
	/**
	 * Class constructor.
	 **/
	function HistoriSurat()
	{
		$this->Entity();
	}

	function selectByParamsTahun()
	{
		$str = "
		SELECT TAHUN
		FROM
		(
			SELECT TAHUN FROM surat_old
		) A
		GROUP BY TAHUN
		ORDER BY TAHUN DESC
		";
		$this->query = $str;
		//	echo $str; exit;
		return $this->selectLimit($str, -1, -1);
	}

	function selectByParams($paramsArray = array(), $limit = -1, $from = -1, $statement = '', $sOrder = "")
	{
		$str = "
		SELECT
			A.SURAT_OLD_ID, A.TAHUN, A.TIPE_SURAT, A.NOMOR_SURAT, A.NOMOR_SURAT_KONVERSI
			, A.FILE_PATH, A.QRCODE_PATH
			, TO_CHAR(A.TANGGAL_SURAT, 'YYYY-MM-DD') TANGGAL_SURAT, A.PERIHAL, A.PEMBUAT_NIP, A.PEMBUAT_NAMA
			, ambilhistorikepada(A.SURAT_OLD_ID) NAMA_TUJUAN
			, CASE A.TIPE_SURAT			
			WHEN 'suratmasukmanual' THEN 'Surat Masuk Manual'
			WHEN 'suratedaran' THEN 'Surat Edaran'
			WHEN 'suratkeluar' THEN 'Surat Keluar'
			WHEN 'notadinas' THEN 'Nota Dinas' END TIPE_NAMA
			, REPLACE(FILE_PATH, '\', '/') FILE_PATH_NEW
			, REPLACE(QRCODE_PATH, '\', '/') QRCODE_NEW
		FROM surat_old A
		WHERE 1 = 1
		";

		while (list($key, $val) = each($paramsArray)) {
			$str .= " AND $key = '$val' ";
		}

		$str .= " " . $statement . " " . $sOrder;
		$this->query = $str;
		// echo $str; exit;
		return $this->selectLimit($str, $limit, $from);
	}

	function getCountByParams($paramsArray = array(), $statement = "")
	{
		$str = "
		SELECT COUNT(1) AS ROWCOUNT
		FROM surat_old A
		WHERE 1=1 ";

		while (list($key, $val) = each($paramsArray)) {
			$str .= " AND $key = '$val' ";
		}

		$str .= $statement;
		$this->query = $str;
		$this->select($str);
		if ($this->firstRow())
			return $this->getField("ROWCOUNT");
		else
			return 0;
	}

	function selectByParamsLampiran($paramsArray = array(), $limit = -1, $from = -1, $statement = '', $sOrder = "")
	{
		$str = "
		SELECT
			A.*
			, REPLACE(file_path, '\', '/') FILE_PATH_NEW
		FROM surat_old_lampiran A
		WHERE 1 = 1
		";

		while (list($key, $val) = each($paramsArray)) {
			$str .= " AND $key = '$val' ";
		}

		$str .= " " . $statement . " " . $sOrder;
		$this->query = $str;
		// echo $str; exit;
		return $this->selectLimit($str, $limit, $from);
	}

	function selectByParamsDisposisi($paramsArray = array(), $limit = -1, $from = -1, $statement = '', $sOrder = "ORDER BY A.SURAT_OLD_DETIL_ID")
	{
		$str = "
		SELECT
			A.*
			, REPLACE(path_disposisi, '\', '/') PATH_DISPOSISI_NEW
		FROM surat_old_detil A
		WHERE 1 = 1
		";

		while (list($key, $val) = each($paramsArray)) {
			$str .= " AND $key = '$val' ";
		}

		$str .= " " . $statement . " " . $sOrder;
		$this->query = $str;
		// echo $str; exit;
		return $this->selectLimit($str, $limit, $from);
	}
	
}