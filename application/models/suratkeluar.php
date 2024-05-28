<?

/***
 * Entity-base class untuk mengimplementasikan tabel kategori.
 * 
 ***/
include_once(APPPATH . '/models/Entity.php');

class SuratKeluar extends Entity
{

	var $query;
	/**
	 * Class constructor.
	 **/
	function SuratKeluar()
	{
		$this->Entity();
	}

	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("SURAT_KELUAR_ID", $this->getNextId("SURAT_KELUAR_ID", "SURAT_KELUAR"));
		$this->tanggal = date("Y-m-d");
		$this->NowYear = date("Y");

		$str = "INSERT INTO SURAT_KELUAR(SURAT_KELUAR_ID, SURAT_KELUAR_REF_ID, JENIS_TUJUAN, NO_AGENDA, TAHUN, LOKASI_SIMPAN, 
				 NOMOR, TANGGAL, JENIS_NASKAH_ID,
				 SIFAT_NASKAH,STATUS_SURAT,PERIHAL,KLASIFIKASI_ID,INSTANSI_ASAL,
				 ALAMAT_ASAL,KOTA_ASAL,KETERANGAN_ASAL,SATUAN_KERJA_ID_ASAL,SATUAN_KERJA_ID_TUJUAN,ISI,
				 CATATAN,TANGGAL_ENTRI,USER_ID,NAMA_USER, PENYAMPAIAN_SURAT, CABANG_ID,
				 TANGGAL_KEGIATAN, TANGGAL_KEGIATAN_AKHIR,
				 IS_MEETING, IS_EMAIL,  PRIORITAS_SURAT_ID, MEDIA_PENGIRIMAN_ID,
				 LAST_CREATE_USER, LAST_CREATE_DATE
            )
            VALUES ('" . $this->getField("SURAT_KELUAR_ID") . "',
					'" . (int)$this->getField("SURAT_KELUAR_REF_ID") . "',
					'" . $this->getField("JENIS_TUJUAN") . "',
					'" . $this->getField("NO_AGENDA") . "',
					" . $this->NowYear . ",
					'" . $this->getField("LOKASI_SIMPAN") . "',
                    '" . $this->getField("NOMOR") . "',
                    " . $this->getField("TANGGAL") . ",
                    '" . $this->getField("JENIS_NASKAH_ID") . "',
                    '" . $this->getField("SIFAT_NASKAH") . "',
                    '" . $this->getField("STATUS_SURAT") . "',
                    '" . $this->getField("PERIHAL") . "',
                    '" . $this->getField("KLASIFIKASI_ID") . "',
                    '" . $this->getField("INSTANSI_ASAL") . "',
                    '" . $this->getField("ALAMAT_ASAL") . "',
                    '" . $this->getField("KOTA_ASAL") . "',
                    '" . $this->getField("KETERANGAN_ASAL") . "',
                    '" . $this->getField("SATUAN_KERJA_ID_ASAL") . "',					
                    '" . $this->getField("SATUAN_KERJA_ID_TUJUAN") . "',
                    '" . $this->getField("ISI") . "',
                    '" . $this->getField("CATATAN") . "',
					CURRENT_TIMESTAMP,
                    '" . $this->getField("USER_ID") . "',
                    '" . $this->getField("NAMA_USER") . "',
                    '" . $this->getField("PENYAMPAIAN_SURAT") . "',
                    '" . $this->getField("CABANG_ID") . "',
                    " . $this->getField("TANGGAL_KEGIATAN") . ",
                    " . $this->getField("TANGGAL_KEGIATAN_AKHIR") . ",
                    '" . $this->getField("IS_MEETING") . "',
                    '" . $this->getField("IS_EMAIL") . "',
                    '" . $this->getField("PRIORITAS_SURAT_ID") . "',
                    '" . $this->getField("MEDIA_PENGIRIMAN_ID") . "',
				  	'" . $this->getField("LAST_CREATE_USER") . "',
				  	CURRENT_DATE
				)";

		$this->query = $str;
		// echo $str;exit;
		$this->id = $this->getField("SURAT_KELUAR_ID");
		return $this->execQuery($str);
	}

	function insertAttachment()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("SURAT_KELUAR_ATTACHMENT_ID", $this->getNextId("SURAT_KELUAR_ATTACHMENT_ID", "SURAT_KELUAR_ATTACHMENT"));
		$str = "
			INSERT INTO SURAT_KELUAR_ATTACHMENT(
						SURAT_KELUAR_ATTACHMENT_ID, SURAT_KELUAR_ID, ATTACHMENT, 
						UKURAN, TIPE, NAMA, LAST_CREATE_USER, LAST_CREATE_DATE
            )
            VALUES ('" . $this->getField("SURAT_KELUAR_ATTACHMENT_ID") . "',
					'" . $this->getField("SURAT_KELUAR_ID") . "',
					'" . $this->getField("ATTACHMENT") . "',
                    " . (int)$this->getField("UKURAN") . ",
                    '" . $this->getField("TIPE") . "',
                    '" . $this->getField("NAMA") . "',
                    '" . $this->getField("LAST_CREATE_USER") . "',
				  	CURRENT_DATE
				)";

		$this->query = $str;
		$this->id = $this->getField("SURAT_KELUAR_ID");
		return $this->execQuery($str);
	}

	function insertArsip()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("ARSIP_ID", $this->getNextId("ARSIP_ID", "arsip.ARSIP"));
		$this->tanggal = date("Y-m-d");
		$this->NowYear = date("Y");

		$str = "
			INSERT INTO arsip.ARSIP(
			   SURAT_KELUAR_ID, ARSIP_TAHUN, ARSIP_ID, ARSIP_KODE, ARSIP_JENIS, ARSIP_SIFAT,
			   ARSIP_ORGANISASI, ARSIP_NOMOR, ARSIP_TANGGAL, ARSIP_JUDUL, ARSIP_STATUS, ARSIP_AUTHOR, ARSIP_CREATE)
			SELECT SURAT_KELUAR_ID, TAHUN, '" . $this->getField("ARSIP_ID") . "', NO_AGENDA, '01', 2, SATUAN_KERJA_ID_ASAL, NO_AGENDA, TANGGAL, NOMOR, 
				   'CREATE', '" . $this->getField("ARSIP_AUTHOR") . "', CURRENT_DATE 
			FROM SURAT_KELUAR WHERE SURAT_KELUAR_ID =  " . $this->getField("SURAT_KELUAR_ID") . "
            ";

		$this->query = $str;
		$this->id = $this->getField("ARSIP_ID");
		return $this->execQuery($str);
	}

	function upload($table, $column, $blob, $id)
	{
		return $this->uploadBlob($table, $column, $blob, $id);
	}

	function update()
	{

		$str = "
		UPDATE SURAT_KELUAR SET
			   LOKASI_SIMPAN			= '" . $this->getField("LOKASI_SIMPAN") . "',
			   NOMOR 					= '" . $this->getField("NOMOR") . "',
			   TANGGAL 					= " . $this->getField("TANGGAL") . ",
			   JENIS_NASKAH_ID 			= '" . $this->getField("JENIS_NASKAH_ID") . "',
			   SIFAT_NASKAH 					= '" . $this->getField("SIFAT_NASKAH") . "',
			   STATUS_SURAT 			= '" . $this->getField("STATUS_SURAT") . "',
			   PERIHAL 					= '" . $this->getField("PERIHAL") . "',
			   KLASIFIKASI_ID 			= '" . $this->getField("KLASIFIKASI_ID") . "',
			   INSTANSI_ASAL 			= '" . $this->getField("INSTANSI_ASAL") . "',
			   ALAMAT_ASAL 				= '" . $this->getField("ALAMAT_ASAL") . "',
			   KOTA_ASAL 				= '" . $this->getField("KOTA_ASAL") . "',
			   KETERANGAN_ASAL 			= '" . $this->getField("KETERANGAN_ASAL") . "',
			   ISI 						= '" . $this->getField("ISI") . "',
			   CATATAN 					= '" . $this->getField("CATATAN") . "',
			   PENYAMPAIAN_SURAT		= '" . $this->getField("PENYAMPAIAN_SURAT") . "',
			   LAST_UPDATE_USER			= '" . $this->getField("LAST_UPDATE_USER") . "',
			   TANGGAL_KEGIATAN 		= " . $this->getField("TANGGAL_KEGIATAN") . ",
			   TANGGAL_KEGIATAN_AKHIR 	= " . $this->getField("TANGGAL_KEGIATAN_AKHIR") . ",
			   IS_MEETING				= '" . $this->getField("IS_MEETING") . "',
			   IS_EMAIL					= '" . $this->getField("IS_EMAIL") . "',
			   PRIORITAS_SURAT_ID		= '" . $this->getField("PRIORITAS_SURAT_ID") . "',
			   MEDIA_PENGIRIMAN_ID		= '" . $this->getField("MEDIA_PENGIRIMAN_ID") . "',
			   LAST_UPDATE_DATE			= CURRENT_DATE
		   WHERE SURAT_KELUAR_ID 		= '" . $this->getField("SURAT_KELUAR_ID") . "'
				";
		$this->query = $str;


		return $this->execQuery($str);
	}



	function paraf()
	{

		$str = "
		UPDATE SURAT_KELUAR_PARAF SET
			   STATUS_PARAF 		= '1',
			   KODE_PARAF 			= '" . $this->getField("KODE_PARAF") . "',
			   LAST_UPDATE_USER			= '" . $this->getField("LAST_UPDATE_USER") . "',
			   LAST_UPDATE_DATE			= CURRENT_DATE
		   WHERE SURAT_KELUAR_ID = '" . $this->getField("SURAT_KELUAR_ID") . "' AND
	 			 USER_ID 		= '" . $this->getField("USER_ID") . "'
			   ";
		$this->query = $str;
		return $this->execQuery($str);
	}



	function revisi()
	{

		$str = "
		UPDATE SURAT_KELUAR SET
			   STATUS_SURAT 		= 'REVISI',
			   REVISI 				= '" . $this->getField("REVISI") . "',
			   REVISI_BY			= '" . $this->getField("REVISI_BY") . "',
			   REVISI_DATE			= CURRENT_TIMESTAMP
		   WHERE SURAT_KELUAR_ID 		= '" . $this->getField("SURAT_KELUAR_ID") . "' AND
	 			 SATUAN_KERJA_ID_ASAL 	= '" . $this->getField("SATUAN_KERJA_ID_ASAL") . "'
				";
		$this->query = $str;
		return $this->execQuery($str);
	}


	function updateByField()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "UPDATE SURAT_KELUAR A SET
				  " . $this->getField("FIELD") . " 	= '" . $this->getField("FIELD_VALUE") . "',
				  LAST_UPDATE_USER		 		= '" . $this->getField("LAST_UPDATE_USER") . "',
				  LAST_UPDATE_DATE				= CURRENT_DATE
				WHERE SURAT_KELUAR_ID = " . $this->getField("SURAT_KELUAR_ID") . "
				";
		$this->query = $str;
		return $this->execQuery($str);
	}


	function updateByFieldValidasi()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "UPDATE SURAT_KELUAR A SET
				  " . $this->getField("FIELD") . " 		= '" . $this->getField("FIELD_VALUE") . "',
				  LAST_UPDATE_USER		 	= '" . $this->getField("LAST_UPDATE_USER") . "',
				  LAST_UPDATE_DATE			= CURRENT_DATE
				WHERE SURAT_KELUAR_ID = " . $this->getField("SURAT_KELUAR_ID") . " AND
					(
					  SATUAN_KERJA_ID_ASAL = '" . $this->getField("SATUAN_KERJA_ID_ASAL") . "' OR
					  EXISTS(SELECT 1 FROM SURAT_KELUAR_PARAF X WHERE X.SURAT_KELUAR_ID = A.SURAT_KELUAR_ID AND X.USER_ID = '" . $this->getField("PEMARAF_ID") . "')
					)
				";
		$this->query = $str;
		//echo $str;
		return $this->execQuery($str);
	}

	function deleteAttachment()
	{
		$str2 = "
		 		DELETE FROM SURAT_KELUAR_ATTACHMENT
                WHERE 
                  SURAT_KELUAR_ID = '" . $this->getField("SURAT_KELUAR_ID") . "'";

		$this->query = $str2;
		$this->execQuery($str2);
	}

	function delete()
	{
		$str2 = "
		 		DELETE FROM SURAT_KELUAR_ATTACHMENT
                WHERE 
                  SURAT_KELUAR_ID = '" . $this->getField("SURAT_KELUAR_ID") . "' AND 
				  LAST_CREATE_USER = '" . $this->getField("LAST_CREATE_USER") . "' ";

		$this->query = $str2;
		$this->execQuery($str2);

		$str = "
		 		DELETE FROM DISPOSISI_KELUAR
                WHERE 
                  SURAT_KELUAR_ID = '" . $this->getField("SURAT_KELUAR_ID") . "' AND 
				  LAST_CREATE_USER = '" . $this->getField("LAST_CREATE_USER") . "' ";

		$this->query = $str;
		$this->execQuery($str);

		$str1 = "
		 		DELETE FROM SURAT_KELUAR
                WHERE 
                  SURAT_KELUAR_ID = '" . $this->getField("SURAT_KELUAR_ID") . "' AND 
				  LAST_CREATE_USER = '" . $this->getField("LAST_CREATE_USER") . "' ";

		$this->query = $str1;
		return $this->execQuery($str1);
	}


	function selectByParams($paramsArray = array(), $limit = -1, $from = -1, $stat = '', $order = "")
	{
		$str = "SELECT SURAT_KELUAR_ID, SURAT_KELUAR_REF_ID, TAHUN, NOMOR, NO_AGENDA, TANGGAL, TANGGAL_DITERUSKAN, 
					   TANGGAL_BATAS, JENIS, JENIS_TUJUAN, KEPADA, PERIHAL, KLASIFIKASI_ID, 
					   INSTANSI_ASAL, ALAMAT_ASAL, KOTA_ASAL, KETERANGAN_ASAL, SATUAN_KERJA_ID_TUJUAN, 
					   ISI, JUMLAH_LAMPIRAN, CATATAN, TERBALAS, TERDISPOSISI, SATUAN_KERJA_ID_ASAL, 
					   TANGGAL_ENTRI, USER_ID, NAMA_USER, TANGGAL_UPDATE, NO_URUT, TERBACA, 
					   TERPARAF, TERTANDA_TANGANI, TGL_FISIK, POSISI_SURAT_FISIK, WAITING_LIST, 
					   KLASIFIKASI_JENIS, POSISI_SURAT_KELUAR, INFO_SMS_POSISI, INFO_SMS, 
					   INFO_SMS_NAMA, INFO_SMS_TELEPON, PENERIMA_SURAT, PENERIMA_SURAT_TANGGAL, 
					   PENERIMA_SURAT_TTD, STATUS_PENERIMA, STATUS_PENERIMA_TANGGAL, 
					   LOKASI_SIMPAN, TANGGAL_KEGIATAN, TANGGAL_KEGIATAN_AKHIR, LOKASI_ORDNER, 
					   LOKASI_ORDNER_LEMBAR, LOKASI_ORDNER_TAHUN, SURAT_KELUAR_STATUS, 
					   SURAT_KELUAR_DELETE, LAST_CREATE_USER, LAST_CREATE_DATE, LAST_UPDATE_USER, 
					   LAST_UPDATE_DATE, SURAT_KELUAR_FINAL, SIFAT_NASKAH, JENIS_NASKAH_ID, STATUS_SURAT, PENYAMPAIAN_SURAT, USER_ATASAN_ID, REVISI,
					   SURAT_PDF, TO_CHAR(TANGGAL_KEGIATAN, 'DD-MM-YYYY') TANGGAL_KEGIATAN_EDIT, TO_CHAR(TANGGAL_KEGIATAN_AKHIR, 'DD-MM-YYYY') TANGGAL_KEGIATAN_AKHIR_EDIT, 
					   TO_CHAR(TANGGAL_KEGIATAN, 'HH24:MI') JAM_KEGIATAN_EDIT, TO_CHAR(TANGGAL_KEGIATAN_AKHIR, 'HH24:MI') JAM_KEGIATAN_AKHIR_EDIT,
					   IS_EMAIL, IS_MEETING, PRIORITAS_SURAT_ID, PRIORITAS_SURAT, 
					   MEDIA_PENGIRIMAN_ID, MEDIA_PENGIRIMAN
				  FROM SURAT_KELUAR A
				  WHERE 1 = 1
 				";

		while (list($key, $val) = each($paramsArray)) {
			$str .= " AND $key = '$val' ";
		}

		$str .= " " . $stat . "  " . $order;
		$this->query = $str;
		// echo $str;
		// exit;
		return $this->selectLimit($str, $limit, $from);
	}


	function selectByParamsLihat($paramsArray = array(), $limit = -1, $from = -1, $stat = '', $order = "")
	{
		$str = "SELECT SURAT_KELUAR_ID, SURAT_KELUAR_REF_ID, TAHUN, NOMOR, NO_AGENDA, TANGGAL, TANGGAL_DITERUSKAN, 
					   TANGGAL_BATAS, JENIS, JENIS_TUJUAN, 
						AMBIL_SURAT_KELUAR_TUJUAN(A.SURAT_KELUAR_ID, 'TUJUAN') KEPADA, 
						AMBIL_SURAT_KELUAR_TUJUAN(A.SURAT_KELUAR_ID, 'TEMBUSAN') TEMBUSAN, 
					   PERIHAL, A.KLASIFIKASI_ID, 
					   INSTANSI_ASAL, ALAMAT_ASAL, KOTA_ASAL, KETERANGAN_ASAL, SATUAN_KERJA_ID_TUJUAN, 
					   ISI, JUMLAH_LAMPIRAN, CATATAN, TERBALAS, TERDISPOSISI, SATUAN_KERJA_ID_ASAL, 
					   TANGGAL_ENTRI, USER_ID, NAMA_USER, TANGGAL_UPDATE, A.NO_URUT, TERBACA, 
					   TERPARAF, TERTANDA_TANGANI, TGL_FISIK, POSISI_SURAT_FISIK, WAITING_LIST, 
					   KLASIFIKASI_JENIS, POSISI_SURAT_KELUAR, INFO_SMS_POSISI, INFO_SMS, 
					   SURAT_KELUAR_FINAL, SIFAT_NASKAH, JENIS_NASKAH_ID, STATUS_SURAT, PENYAMPAIAN_SURAT, USER_ATASAN_ID, REVISI,
					   SURAT_PDF, TO_CHAR(TANGGAL_KEGIATAN, 'DD-MM-YYYY') TANGGAL_KEGIATAN_EDIT, TO_CHAR(TANGGAL_KEGIATAN_AKHIR, 'DD-MM-YYYY') TANGGAL_KEGIATAN_AKHIR_EDIT, 
					   TO_CHAR(TANGGAL_KEGIATAN, 'HH24:MI') JAM_KEGIATAN_EDIT, TO_CHAR(TANGGAL_KEGIATAN_AKHIR, 'HH24:MI') JAM_KEGIATAN_AKHIR_EDIT,
					   IS_EMAIL, IS_MEETING, B.KODE KLASIFIKASI_KODE, AMBIL_KLASIFIKASI(B.KODE) KLASIFIKASI
				  FROM SURAT_KELUAR A
				  LEFT JOIN KLASIFIKASI B ON A.KLASIFIKASI_ID = B.KLASIFIKASI_ID
				  WHERE 1 = 1 
 				";

		while (list($key, $val) = each($paramsArray)) {
			$str .= " AND $key = '$val' ";
		}

		$str .= " " . $stat . "  " . $order;
		$this->query = $str;

		return $this->selectLimit($str, $limit, $from);
	}


	function selectByParamsMeeting($paramsArray = array(), $limit = -1, $from = -1, $stat = '', $order = "")
	{
		$str = "SELECT SURAT_KELUAR_ID, SURAT_KELUAR_REF_ID, TAHUN, NOMOR, NO_AGENDA, TANGGAL, TANGGAL_DITERUSKAN, 
					   TANGGAL_BATAS, JENIS, JENIS_TUJUAN, KEPADA, PERIHAL, KLASIFIKASI_ID, 
					   INSTANSI_ASAL, ALAMAT_ASAL, KOTA_ASAL, KETERANGAN_ASAL, SATUAN_KERJA_ID_TUJUAN, 
					   ISI, JUMLAH_LAMPIRAN, CATATAN, TERBALAS, TERDISPOSISI, SATUAN_KERJA_ID_ASAL, 
					   TANGGAL_ENTRI, USER_ID, NAMA_USER, TANGGAL_UPDATE, NO_URUT, TERBACA, 
					   TERPARAF, TERTANDA_TANGANI, TGL_FISIK, POSISI_SURAT_FISIK, WAITING_LIST, 
					   KLASIFIKASI_JENIS, POSISI_SURAT_KELUAR, INFO_SMS_POSISI, INFO_SMS, 
					   INFO_SMS_NAMA, INFO_SMS_TELEPON, PENERIMA_SURAT, PENERIMA_SURAT_TANGGAL, 
					   PENERIMA_SURAT_TTD, STATUS_PENERIMA, STATUS_PENERIMA_TANGGAL, 
					   TO_CHAR(TANGGAL_KEGIATAN, 'YYYY-MM-DD HH24:MI:SS') TANGGAL_KEGIATAN, LOKASI_SIMPAN, TO_CHAR(TANGGAL_KEGIATAN_AKHIR, 'YYYY-MM-DD HH24:MI:SS') TANGGAL_KEGIATAN_AKHIR, LOKASI_ORDNER, 
					   LOKASI_ORDNER_LEMBAR, LOKASI_ORDNER_TAHUN, SURAT_KELUAR_STATUS, 
					   SURAT_KELUAR_DELETE, LAST_CREATE_USER, LAST_CREATE_DATE, LAST_UPDATE_USER, 
					   LAST_UPDATE_DATE, SURAT_KELUAR_FINAL, SIFAT_NASKAH, JENIS_NASKAH_ID, STATUS_SURAT, PENYAMPAIAN_SURAT, USER_ATASAN_ID, REVISI,
					   SURAT_PDF
				  FROM SURAT_KELUAR A
				  WHERE 1 = 1
 				";

		while (list($key, $val) = each($paramsArray)) {
			$str .= " AND $key = '$val' ";
		}

		$str .= " " . $stat . "  " . $order;
		$this->query = $str;

		return $this->selectLimit($str, $limit, $from);
	}


	function selectByParamsSuratDokumen($paramsArray = array(), $limit = -1, $from = -1, $stat = '', $order = "")
	{
		$str = "SELECT A.SURAT_KELUAR_ID, SURAT_KELUAR_REF_ID, TAHUN, NOMOR, NO_AGENDA, TANGGAL, TANGGAL_DITERUSKAN, 
					   TANGGAL_BATAS, JENIS, JENIS_TUJUAN, KEPADA, PERIHAL, KLASIFIKASI_ID, 
					   INSTANSI_ASAL, ALAMAT_ASAL, KOTA_ASAL, KETERANGAN_ASAL, SATUAN_KERJA_ID_TUJUAN, 
					   ISI, JUMLAH_LAMPIRAN, A.CATATAN, TERBALAS, TERDISPOSISI, SATUAN_KERJA_ID_ASAL, 
					   TANGGAL_ENTRI, USER_ID, NAMA_USER, TANGGAL_UPDATE, A.NO_URUT, TERBACA, 
					   TERPARAF, TERTANDA_TANGANI, TGL_FISIK, POSISI_SURAT_FISIK, WAITING_LIST, 
					   KLASIFIKASI_JENIS, POSISI_SURAT_KELUAR, INFO_SMS_POSISI, INFO_SMS, 
					   INFO_SMS_NAMA, INFO_SMS_TELEPON, PENERIMA_SURAT, PENERIMA_SURAT_TANGGAL, 
					   PENERIMA_SURAT_TTD, STATUS_PENERIMA, STATUS_PENERIMA_TANGGAL, 
					   TANGGAL_KEGIATAN, LOKASI_SIMPAN, TANGGAL_KEGIATAN_AKHIR, LOKASI_ORDNER, 
					   LOKASI_ORDNER_LEMBAR, LOKASI_ORDNER_TAHUN, SURAT_KELUAR_STATUS, 
					   SURAT_KELUAR_DELETE, A.LAST_CREATE_USER, A.LAST_CREATE_DATE, A.LAST_UPDATE_USER, 
					   A.LAST_UPDATE_DATE, SURAT_KELUAR_FINAL, SIFAT_NASKAH, JENIS_NASKAH_ID, STATUS_SURAT, 
					   PENYAMPAIAN_SURAT, USER_ATASAN_ID, REVISI, SURAT_PDF, ATTACHMENT 
				  FROM SURAT_KELUAR A
				  LEFT JOIN SURAT_KELUAR_ATTACHMENT B ON B.SURAT_KELUAR_ID=A.SURAT_KELUAR_ID 
				  WHERE 1 = 1
 				";

		while (list($key, $val) = each($paramsArray)) {
			$str .= " AND $key = '$val' ";
		}

		$str .= " " . $stat . "  " . $order;
		$this->query = $str;

		return $this->selectLimit($str, $limit, $from);
	}


	function selectByParamsSurat($paramsArray = array(), $limit = -1, $from = -1, $stat = '', $order = "")
	{
		$str = "SELECT A.SURAT_KELUAR_ID, SURAT_KELUAR_REF_ID, TAHUN, NOMOR, NO_AGENDA, TANGGAL, TANGGAL_DITERUSKAN, 
					   TANGGAL_BATAS, JENIS, JENIS_TUJUAN, AMBIL_SURAT_KELUAR_KEPADA(A.SURAT_KELUAR_ID) KEPADA, PERIHAL, KLASIFIKASI_ID, 
					   INSTANSI_ASAL, ALAMAT_ASAL, KOTA_ASAL, KETERANGAN_ASAL, SATUAN_KERJA_ID_TUJUAN, 
					   ISI, C.JUMLAH_LAMPIRAN, CATATAN, TERBALAS, TERDISPOSISI, SATUAN_KERJA_ID_ASAL, 
					   TANGGAL_ENTRI, USER_ID, NAMA_USER, TANGGAL_UPDATE, A.NO_URUT, TERBACA, 
					   TERPARAF, TERTANDA_TANGANI, TGL_FISIK, POSISI_SURAT_FISIK, WAITING_LIST, 
					   KLASIFIKASI_JENIS, POSISI_SURAT_KELUAR, INFO_SMS_POSISI, INFO_SMS, 
					   INFO_SMS_NAMA, INFO_SMS_TELEPON, PENERIMA_SURAT, PENERIMA_SURAT_TANGGAL, 
					   PENERIMA_SURAT_TTD, STATUS_PENERIMA, STATUS_PENERIMA_TANGGAL, 
					   TANGGAL_KEGIATAN, LOKASI_SIMPAN, TANGGAL_KEGIATAN_AKHIR, LOKASI_ORDNER, 
					   LOKASI_ORDNER_LEMBAR, LOKASI_ORDNER_TAHUN, SURAT_KELUAR_STATUS, 
					   SURAT_KELUAR_DELETE, SURAT_KELUAR_FINAL, SIFAT_NASKAH, JENIS_NASKAH_ID, STATUS_SURAT, PENYAMPAIAN_SURAT, USER_ATASAN_ID, REVISI,
					   SURAT_PDF, AMBIL_SURAT_KELUAR_TEMBUSAN(A.SURAT_KELUAR_ID) TEMBUSAN, B.LOKASI LOKASI_SURAT, TTD_KODE, USER_ATASAN, USER_ATASAN_JABATAN,
					   D.ALAMAT ALAMAT_UNIT, D.TELEPON TELEPON_UNIT, D.FAX FAX_UNIT, D.NAMA NAMA_UNIT, D.LOKASI LOKASI_UNIT
				  FROM SURAT_KELUAR A
				  INNER JOIN SATUAN_KERJA B ON A.CABANG_ID = B.SATUAN_KERJA_ID
				  LEFT JOIN SURAT_KELUAR_JUMLAH_LAMP C ON A.SURAT_KELUAR_ID = C.SURAT_KELUAR_ID
				  LEFT JOIN KODE_UNIT_KERJA D ON D.KODE = B.SATUAN_KERJA_ID
				  WHERE 1 = 1

 				";

		while (list($key, $val) = each($paramsArray)) {
			$str .= " AND $key = '$val' ";
		}

		$str .= " " . $stat . "  " . $order;
		$this->query = $str;
		return $this->selectLimit($str, $limit, $from);
	}


	function selectByParamsMonitoring($paramsArray = array(), $limit = -1, $from = -1, $stat = '', $sOrder = " ORDER BY TANGGAL_ENTRI DESC ")
	{
		$str = "SELECT SURAT_KELUAR_ID, STATUS_SURAT, NOMOR, NO_AGENDA, TO_CHAR(TANGGAL_ENTRI, 'DD-MM-YYYY HH24:MI') TANGGAL_ENTRI, TANGGAL, 
				JENIS JENIS_NASKAH, PERIHAL, SIFAT_NASKAH, 
				AMBIL_SURAT_KELUAR_TUJUAN(A.SURAT_KELUAR_ID, 'TUJUAN') KEPADA, 
				AMBIL_SURAT_KELUAR_TUJUAN(A.SURAT_KELUAR_ID, 'TEMBUSAN') TEMBUSAN, 
				INSTANSI_ASAL, TERBALAS, TERDISPOSISI, TERBACA, USER_ID, USER_ATASAN_ID, ISI, TERBACA_VALIDASI
							  FROM SURAT_KELUAR A
				  WHERE 1 = 1
			   ";

		while (list($key, $val) = each($paramsArray)) {
			$str .= " AND $key = '$val' ";
		}

		$str .= " " . $stat . " " . $sOrder;
		$this->query = $str;
		return $this->selectLimit($str, $limit, $from);
	}


	function selectByParamsDetil($paramsArray = array(), $limit = -1, $from = -1, $stat = '', $sOrder = " ORDER BY TANGGAL_ENTRI DESC ")
	{
		$str = "SELECT SURAT_KELUAR_ID, STATUS_SURAT, NOMOR, NO_AGENDA, TO_CHAR(TANGGAL_ENTRI, 'DD-MM-YYYY HH24:MI') TANGGAL_ENTRI, TANGGAL, 
				JENIS JENIS_NASKAH, PERIHAL, SIFAT_NASKAH, 
				AMBIL_SURAT_KELUAR_TUJUAN(A.SURAT_KELUAR_ID, 'TUJUAN') KEPADA, 
				AMBIL_SURAT_KELUAR_TUJUAN(A.SURAT_KELUAR_ID, 'TEMBUSAN') TEMBUSAN, 
				JENIS_TUJUAN JENIS_TUJUAN_ID,
				CASE 
					WHEN JENIS_TUJUAN = 'NI' THEN 'Surat Internal'
					WHEN JENIS_TUJUAN = 'AGD' THEN 'Surat Masuk'
					WHEN JENIS_TUJUAN = 'PB' THEN 'Pemberitahuan' END JENIS_TUJUAN,
				CASE 
					WHEN JENIS_TUJUAN = 'NI' THEN 'surat_internal_validasi'
					WHEN JENIS_TUJUAN = 'PB' THEN 'pemberitahuan_validasi' END LINK_VALIDASI,	
				INSTANSI_ASAL, KOTA_ASAL, ALAMAT_ASAL, TERBALAS, TERDISPOSISI, TERTANDA_TANGANI, 
				TERBACA, USER_ID, NAMA_USER, PENYAMPAIAN_SURAT, ISI, SATUAN_KERJA_ID_ASAL
							  FROM SURAT_KELUAR A
				  WHERE 1 = 1
			   ";

		while (list($key, $val) = each($paramsArray)) {
			$str .= " AND $key = '$val' ";
		}

		$str .= " " . $stat . " " . $sOrder;
		$this->query = $str;
		return $this->selectLimit($str, $limit, $from);
	}



	function selectByParamsAkses($paramsArray = array(), $limit = -1, $from = -1, $stat = '', $sOrder = " ORDER BY A.SURAT_KELUAR_ID ASC ")
	{


		$str = " SELECT A.SURAT_KELUAR_ID, A.USER_ID, AKSES, B.SURAT_PDF, C.ATTACHMENT TEMPLATE_SURAT_WORD, C.LINK_URL TEMPLATE_SURAT,
						A.TERBACA, A.TERBALAS, A.TERDISPOSISI, A.STATUS_PARAF, A.TERBACA_VALIDASI, A.STATUS_SURAT
					FROM SURAT_KELUAR_AKSES A 
					INNER JOIN SURAT_KELUAR B ON A.SURAT_KELUAR_ID = B.SURAT_KELUAR_ID
					LEFT JOIN JENIS_NASKAH C ON C.JENIS_NASKAH_ID = B.JENIS_NASKAH_ID
					WHERE 1 = 1
			   ";

		while (list($key, $val) = each($paramsArray)) {
			$str .= " AND $key = '$val' ";
		}

		$str .= " " . $stat . " " . $sOrder;
		$this->query = $str;
		return $this->selectLimit($str, $limit, $from);
	}




	function selectByParamsInbox($paramsArray = array(), $limit = -1, $from = -1, $stat = '', $sOrder = " ORDER BY A.TANGGAL_ENTRI DESC ")
	{
		$str = "
				SELECT A.SURAT_KELUAR_ID, A.INSTANSI_ASAL, A.USER_ATASAN, USER_ATASAN_JABATAN, B.NAMA_SATKER_ASAL, A.JENIS, A.ALAMAT_ASAL, B.DISPOSISI_ID,  NOMOR, 
				TO_CHAR(TANGGAL_ENTRI, 'DD-MM-YYYY HH24:MI') TANGGAL_ENTRI, 
				TANGGAL, 
				JENIS_TUJUAN JENIS_TUJUAN_ID,
				CASE 
					WHEN JENIS_TUJUAN = 'NI' THEN 'Surat Internal'
					WHEN JENIS_TUJUAN = 'AGD' THEN 'Surat Masuk'
					WHEN JENIS_TUJUAN = 'PB' THEN 'Pemberitahuan' END JENIS_TUJUAN, A.ISI,
				CASE 
					WHEN B.STATUS_DISPOSISI = 'DISPOSISI' THEN '[DISPOSISI] ' 
					WHEN B.STATUS_DISPOSISI = 'TEMBUSAN' THEN '[TEMBUSAN] ' 
					WHEN B.STATUS_DISPOSISI = 'DISPOSISI_TEMBUSAN' THEN '[TEMBUSAN DISPOSISI] ' 
					ELSE '' END || PERIHAL PERIHAL, JENIS JENIS_NASKAH, SIFAT_NASKAH, 
				INSTANSI_ASAL, B.TERBALAS, B.TERDISPOSISI, B.TERBACA, B.STATUS_DISPOSISI, B.ISI DISPOSISI, B.DISPOSISI_PARENT_ID, A.PENYAMPAIAN_SURAT,
				C.KODE KLASIFIKASI_KODE, AMBIL_KLASIFIKASI(C.KODE) KLASIFIKASI
							  FROM SURAT_KELUAR A
				INNER JOIN DISPOSISI B ON A.SURAT_KELUAR_ID = B.SURAT_KELUAR_ID
				LEFT JOIN KLASIFIKASI C ON A.KLASIFIKASI_ID = C.KLASIFIKASI_ID
				  WHERE 1 = 1

			   ";

		while (list($key, $val) = each($paramsArray)) {
			$str .= " AND $key = '$val' ";
		}

		$str .= " " . $stat . " " . $sOrder;
		$this->query = $str;
		return $this->selectLimit($str, $limit, $from);
	}


	function selectByParamsDraft($paramsArray = array(), $limit = -1, $from = -1, $stat = '', $sOrder = " ORDER BY A.TANGGAL_ENTRI DESC ")
	{
		$str = "SELECT SURAT_KELUAR_ID, STATUS_SURAT, NOMOR, NO_AGENDA, TO_CHAR(TANGGAL_ENTRI, 'DD-MM-YYYY HH24:MI') TANGGAL_ENTRI, TANGGAL, 
				JENIS JENIS_NASKAH, 
				CASE 
					WHEN A.STATUS_SURAT = 'PARAF' THEN '[PARAF] ' 
					WHEN A.STATUS_SURAT = 'VALIDASI' THEN '[VALIDASI] ' 
					ELSE '' END || PERIHAL PERIHAL,  
				SIFAT_NASKAH, 
				AMBIL_SURAT_KELUAR_TUJUAN(A.SURAT_KELUAR_ID, 'TUJUAN') KEPADA, 
				AMBIL_SURAT_KELUAR_TUJUAN(A.SURAT_KELUAR_ID, 'TEMBUSAN') TEMBUSAN, 
				JENIS_TUJUAN JENIS_TUJUAN_ID,
				CASE 
					WHEN JENIS_TUJUAN = 'NI' THEN 'Surat Internal'
					WHEN JENIS_TUJUAN = 'AGD' THEN 'Surat Masuk'
					WHEN JENIS_TUJUAN = 'PB' THEN 'Pemberitahuan' END JENIS_TUJUAN,
				CASE 
					WHEN JENIS_TUJUAN = 'NI' THEN 'surat_internal_' || LOWER(A.STATUS_SURAT)
					WHEN JENIS_TUJUAN = 'PB' THEN 'pemberitahuan_' || LOWER(A.STATUS_SURAT) END LINK_VALIDASI,	
				INSTANSI_ASAL, TERBALAS, TERDISPOSISI, TERTANDA_TANGANI, TERBACA, USER_ID, NAMA_USER
							  FROM SURAT_KELUAR A
				  WHERE 1 = 1
			   ";

		while (list($key, $val) = each($paramsArray)) {
			$str .= " AND $key = '$val' ";
		}

		$str .= " " . $stat . " " . $sOrder;
		$this->query = $str;
		return $this->selectLimit($str, $limit, $from);
	}


	function selectByParamsSent($paramsArray = array(), $limit = -1, $from = -1, $stat = '', $sOrder = " ORDER BY A.TANGGAL_ENTRI DESC ")
	{
		$str = "SELECT SURAT_KELUAR_ID, STATUS_SURAT, NOMOR, NO_AGENDA, TO_CHAR(TANGGAL_ENTRI, 'DD-MM-YYYY HH24:MI') TANGGAL_ENTRI, TANGGAL, 
				JENIS JENIS_NASKAH, 
				CASE 
					WHEN A.STATUS_SURAT = 'PARAF' THEN '[MENUNGGU PARAF] ' 
					WHEN A.STATUS_SURAT = 'POSTING' THEN '[POSTING] ' 
					ELSE '' END || PERIHAL PERIHAL, 
				SIFAT_NASKAH, 
				AMBIL_SURAT_KELUAR_TUJUAN(A.SURAT_KELUAR_ID, 'TUJUAN') KEPADA, 
				AMBIL_SURAT_KELUAR_TUJUAN(A.SURAT_KELUAR_ID, 'TEMBUSAN') TEMBUSAN, 
				JENIS_TUJUAN JENIS_TUJUAN_ID,
				CASE 
					WHEN JENIS_TUJUAN = 'NI' THEN 'Surat Internal'
					WHEN JENIS_TUJUAN = 'AGD' THEN 'Surat Masuk'
					WHEN JENIS_TUJUAN = 'PB' THEN 'Pemberitahuan' END JENIS_TUJUAN,
				CASE 
					WHEN JENIS_TUJUAN = 'NI' THEN 'surat_internal_sent'
					WHEN JENIS_TUJUAN = 'PB' THEN 'pemberitahuan_sent' END LINK_VALIDASI,	
				INSTANSI_ASAL, TERBALAS, TERDISPOSISI, TERTANDA_TANGANI, TERBACA, USER_ID, NAMA_USER
							  FROM SURAT_KELUAR A
				  WHERE 1 = 1
			   ";

		while (list($key, $val) = each($paramsArray)) {
			$str .= " AND $key = '$val' ";
		}

		$str .= " " . $stat . " " . $sOrder;
		$this->query = $str;
		return $this->selectLimit($str, $limit, $from);
	}


	function selectByParamsAttachment($paramsArray = array(), $limit = -1, $from = -1, $stat = '', $sOrder = " ORDER BY SURAT_KELUAR_ATTACHMENT_ID ASC ")
	{
		$str = "SELECT SURAT_KELUAR_ATTACHMENT_ID, SURAT_KELUAR_ID, ATTACHMENT, CATATAN, 
					   UKURAN, TIPE, NAMA, NO_URUT, LAST_CREATE_USER, LAST_CREATE_DATE, 
					   LAST_UPDATE_USER, LAST_UPDATE_DATE
				  FROM SURAT_KELUAR_ATTACHMENT A
				  WHERE 1 = 1
			   ";

		while (list($key, $val) = each($paramsArray)) {
			$str .= " AND $key = '$val' ";
		}

		$str .= " " . $stat . " " . $sOrder;
		$this->query = $str;
		return $this->selectLimit($str, $limit, $from);
	}



	function selectByParamsJumlahSurat($userId)
	{

		//  AND COALESCE(TERBACA_VALIDASI, 0) = 0 HILANGKAN TERBACA SUPAYA ATASAN TAU ADA SURAT
		$str = "SELECT (
					SELECT COUNT(SURAT_KELUAR_ID) JUMLAH FROM DISPOSISI WHERE USER_ID = '" . $userId . "' AND COALESCE(TERBACA, 0) = 0
					) JUMLAH_INBOX,
					(SELECT COUNT(SURAT_KELUAR_ID) JUMLAH FROM SURAT_KELUAR WHERE USER_ATASAN_ID = '" . $userId . "' AND STATUS_SURAT = 'VALIDASI' 
					) JUMLAH_VALIDASI,
					(SELECT COUNT(SURAT_KELUAR_ID) JUMLAH FROM SURAT_KELUAR WHERE USER_ID = '" . $userId . "' AND STATUS_SURAT IN ('DRAFT', 'REVISI') 
					) JUMLAH_DRAFT
			   ";

		return $this->selectLimit($str, -1, -1);
	}


	function getCountByParams($paramsArray = array(), $statement = "")
	{
		$str = "SELECT COUNT(SURAT_KELUAR_ID) AS ROWCOUNT FROM SURAT_KELUAR A WHERE 1=1 ";
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


	function getAkses($paramsArray = array(), $statement = "")
	{
		$str = "SELECT COUNT(1) AS ROWCOUNT FROM SURAT_KELUAR_AKSES A WHERE 1=1 ";
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

	function getTtdSurat($paramsArray = array(), $statement = "")
	{
		$str = "SELECT TTD_KODE FROM SURAT_KELUAR A WHERE 1=1 ";
		while (list($key, $val) = each($paramsArray)) {
			$str .= " AND $key = '$val' ";
		}

		$str .= $statement;
		$this->query = $str;
		$this->select($str);
		if ($this->firstRow())
			return $this->getField("TTD_KODE");
		else
			return "";
	}

	function getStatusSurat($paramsArray = array(), $statement = "")
	{
		$str = "SELECT STATUS_SURAT FROM SURAT_KELUAR A WHERE 1=1 ";
		while (list($key, $val) = each($paramsArray)) {
			$str .= " AND $key = '$val' ";
		}

		$str .= $statement;
		$this->query = $str;
		$this->select($str);
		if ($this->firstRow())
			return $this->getField("STATUS_SURAT");
		else
			return "";
	}

	function getCountByParamsInbox($paramsArray = array(), $statement = "")
	{
		$str = "SELECT COUNT(A.SURAT_KELUAR_ID) AS ROWCOUNT 
				FROM SURAT_KELUAR A
						INNER JOIN DISPOSISI B ON A.SURAT_KELUAR_ID = B.SURAT_KELUAR_ID
						  WHERE 1 = 1 ";
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

	function getCountByParamsMonitoring($paramsArray = array(), $statement = "")
	{
		$str = "SELECT COUNT(SURAT_KELUAR_ID) AS ROWCOUNT FROM SURAT_KELUAR A WHERE 1=1 ";
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


	function getNoSurat($reqSatkerId, $reqJenisNaskahId, $reqJenisTujuan, $reqTanggal)
	{
		$str = "SELECT AMBIL_NOMOR_SURAT('" . $reqSatkerId . "', '" . $reqJenisNaskahId . "', '" . $reqJenisTujuan . "', TO_DATE('" . $reqTanggal . "', 'DD-MM-YYYY')) AS KODE ";

		$str .= $statement;
		$this->query = $str;
		$this->select($str);
		if ($this->firstRow())
			return $this->getField("KODE");
		else
			return "";
	}


	function getMaxNoAgendaByParams($paramsArray = array(), $varStatement = "")
	{
		$str = "SELECT MAX(NO_AGENDA) AS ROWCOUNT FROM SURAT_KELUAR WHERE SURAT_KELUAR_ID IS NOT NULL ";
		while (list($key, $val) = each($paramsArray)) {
			$str .= " AND $key = '$val' ";
		}

		$this->select($str);

		if ($this->firstRow())
			return $this->getField("ROWCOUNT");
		else
			return 0;
	}
}
