<?

/***
 * Entity-base class untuk mengimplementasikan tabel kategori.
 * 
 ***/
include_once(APPPATH . '/models/Entity.php');

class SuratMasuk extends Entity
{

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
		$this->setField("SURAT_MASUK_ID", $this->getNextId("SURAT_MASUK_ID", "SURAT_MASUK"));
		$this->tanggal = date("Y-m-d");
		$this->NowYear = date("Y");

		$str = "INSERT INTO SURAT_MASUK(SURAT_MASUK_ID, SURAT_MASUK_REF_ID, PERMOHONAN_NOMOR_ID, JENIS_TUJUAN, NO_AGENDA, TAHUN, LOKASI_SIMPAN, 
				 NOMOR, TANGGAL, JENIS_NASKAH_ID, JENIS_NASKAH_LEVEL, 
				 SIFAT_NASKAH,STATUS_SURAT,PERIHAL,KLASIFIKASI_ID,INSTANSI_ASAL,
				 ALAMAT_ASAL,KOTA_ASAL,KETERANGAN_ASAL,SATUAN_KERJA_ID_ASAL,SATUAN_KERJA_ID_ASAL_PETIKAN,SATUAN_KERJA_ID_TUJUAN, ISI,
				 CATATAN,TANGGAL_ENTRI,USER_ID,NAMA_USER, PENYAMPAIAN_SURAT, CABANG_ID,
				 TANGGAL_KEGIATAN, TANGGAL_KEGIATAN_AKHIR,
				 IS_MEETING, IS_EMAIL, PRIORITAS_SURAT_ID, 
				 ARSIP_ID, ARSIP,  JENIS_TTD,
				 LAST_CREATE_USER, LAST_CREATE_DATE, TARGET,
				 PENERIMA_SURAT, AN_STATUS, AN_NAMA, BUTUH_AKSI_ID, PEMESAN_SATUAN_KERJA_ID, PEMESAN_SATUAN_KERJA_ISI
				 , EKSTERNAL_KEPADA_ID, EKSTERNAL_KEPADA, EKSTERNAL_TEMBUSAN_ID, EKSTERNAL_TEMBUSAN
				 , KOTA_TUJUAN
				 , DASAR
				 , ISI_PERINTAH
				 , LAIN_LAIN,MENIMBANG,MENGINGAT,MEMPERHATIKAN,MENETAPKAN,PERTAMA,KEDUA,KETIGA,KEEMPAT, KELIMA
				 , KEENAM, KETUJUH, KEDELAPAN, KESEMBILAN, KESEPULUH
				 , KESEBELAS, KEDUABELAS, KETIGABELAS, KEEMPATBELAS, KELIMABELAS, KEENAMBELAS, KETUJUHBELAS, KEDELAPANBELAS
				 , KESEMBILANBELAS, KEDUAPULUH, KEDUAPULUHSATU, KEDUAPULUHDUA, KEDUAPULUHTIGA, KEDUAPULUHEMPAT, KEDUAPULUHLIMA
				 , SATUAN_KERJA_ID_PERINTAH, DARI_INFO, NOMOR_SURAT_INFO, LAMPIRAN_DRIVE
            )
            VALUES ('" . $this->getField("SURAT_MASUK_ID") . "',
					'" . (int)$this->getField("SURAT_MASUK_REF_ID") . "',
					'" . (int)$this->getField("PERMOHONAN_NOMOR_ID") . "',
					'" . $this->getField("JENIS_TUJUAN") . "',
					'" . $this->getField("NO_AGENDA") . "',
					" . $this->NowYear . ",
					'" . $this->getField("LOKASI_SIMPAN") . "',
                    '" . $this->getField("NOMOR") . "',
                    " . $this->getField("TANGGAL") . ",
                    '" . $this->getField("JENIS_NASKAH_ID") . "',
                    '" . $this->getField("JENIS_NASKAH_LEVEL") . "',
                    '" . $this->getField("SIFAT_NASKAH") . "',
                    '" . $this->getField("STATUS_SURAT") . "',
                    '" . $this->getField("PERIHAL") . "',
                    '" . $this->getField("KLASIFIKASI_ID") . "',
                    '" . $this->getField("INSTANSI_ASAL") . "',
                    '" . $this->getField("ALAMAT_ASAL") . "',
                    '" . $this->getField("KOTA_ASAL") . "',
                    '" . $this->getField("KETERANGAN_ASAL") . "',
                    '" . $this->getField("SATUAN_KERJA_ID_ASAL") . "',					
                    '" . $this->getField("SATUAN_KERJA_ID_ASAL_PETIKAN") . "',					
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
                    " . $this->getField("PRIORITAS_SURAT_ID") . ",
                    '" . (int)$this->getField("ARSIP_ID") . "',
                    '" . $this->getField("ARSIP") . "',
                    '" . $this->getField("JENIS_TTD") . "',
				  	'" . $this->getField("LAST_CREATE_USER") . "',
				  	NOW(),
                    '" . $this->getField("TARGET") . "',
                    '" . $this->getField("PENERIMA_SURAT") . "'
                    , '".$this->getField("AN_STATUS")."'
                    , '".$this->getField("AN_NAMA")."'
                    , ".$this->getField("BUTUH_AKSI_ID")."
                    , ". $this->getField("PEMESAN_SATUAN_KERJA_ID")."
					, '". $this->getField("PEMESAN_SATUAN_KERJA_ISI")."'
					, '". $this->getField("EKSTERNAL_KEPADA_ID")."'
					, '". $this->getField("EKSTERNAL_KEPADA")."'
					, '". $this->getField("EKSTERNAL_TEMBUSAN_ID")."'
					, '". $this->getField("EKSTERNAL_TEMBUSAN")."'
					, '". $this->getField("KOTA_TUJUAN")."'
					, '". $this->getField("DASAR")."'
					, '". $this->getField("ISI_PERINTAH")."'
					, '". $this->getField("LAIN_LAIN")."',
					'".$this->getField("MENIMBANG")."',
                    '".$this->getField("MENGINGAT")."',
                    '".$this->getField("MEMPERHATIKAN")."',
                    '".$this->getField("MENETAPKAN")."',
                    '".$this->getField("PERTAMA")."',
                    '".$this->getField("KEDUA")."',
                    '".$this->getField("KETIGA")."',
                    '".$this->getField("KEEMPAT")."',
                    '".$this->getField("KELIMA")."'
                    ,'".$this->getField("KEENAM")."'
                    ,'".$this->getField("KETUJUH")."'
                    ,'".$this->getField("KEDELAPAN")."'
                    ,'".$this->getField("KESEMBILAN")."'
                    ,'".$this->getField("KESEPULUH")."'
                    , '".$this->getField("KESEBELAS")."'
                    , '".$this->getField("KEDUABELAS")."'
                    , '".$this->getField("KETIGABELAS")."'
                    , '".$this->getField("KEEMPATBELAS")."'
                    , '".$this->getField("KELIMABELAS")."'
                    , '".$this->getField("KEENAMBELAS")."'
                    , '".$this->getField("KETUJUHBELAS")."'
                    , '".$this->getField("KEDELAPANBELAS")."'
                    , '".$this->getField("KESEMBILANBELAS")."'
                    , '".$this->getField("KEDUAPULUH")."'
                    , '".$this->getField("KEDUAPULUHSATU")."'
                    , '".$this->getField("KEDUAPULUHDUA")."'
                    , '".$this->getField("KEDUAPULUHTIGA")."'
                    , '".$this->getField("KEDUAPULUHEMPAT")."'
                    , '".$this->getField("KEDUAPULUHLIMA")."'
                    ,'" . $this->getField("SATUAN_KERJA_ID_PERINTAH") . "'
                    , '" . $this->getField("DARI_INFO") . "'
                    , '" . $this->getField("NOMOR_SURAT_INFO") . "'
                    , '" . $this->getField("LAMPIRAN_DRIVE") . "'
				)";

		$this->query = $str;
		// echo $str; exit;
		$this->id = $this->getField("SURAT_MASUK_ID");
		return $this->execQuery($str);
	}

	function insertlog()
	{
		$this->setField("SURAT_MASUK_LOG_ID", $this->getNextId("SURAT_MASUK_LOG_ID", "SURAT_MASUK_LOG"));
		$str = "
		INSERT INTO SURAT_MASUK_LOG
		(
			SURAT_MASUK_LOG_ID, SURAT_MASUK_ID, TANGGAL, STATUS_SURAT, INFORMASI, CATATAN
			, LAST_CREATE_USER, LAST_CREATE_DATE
		)
		VALUES 
		(
            " . $this->getField("SURAT_MASUK_LOG_ID") . ",
            " . $this->getField("SURAT_MASUK_ID") . ",
            CURRENT_TIMESTAMP,
            '" . $this->getField("STATUS_SURAT") . "',
            '" . $this->getField("INFORMASI") . "',
            '" . $this->getField("CATATAN") . "',
            '" . $this->getField("LAST_CREATE_USER") . "',
            CURRENT_DATE
        )";

		$this->query = $str;
		// echo $str; exit;
		$this->id = $this->getField("SURAT_MASUK_LOG_ID");
		return $this->execQuery($str);
	}

	function disposisiteruskan()
	{
		$str = "
		INSERT INTO DISPOSISI
		(
			DISPOSISI_ID, SURAT_MASUK_ID, TAHUN, SATUAN_KERJA_ID_ASAL, SATUAN_KERJA_ID_TUJUAN
			, TANGGAL_DISPOSISI, USER_ID, NAMA_USER
			, TERPARAF, ISI, TANGGAL_BATAS, TERTANDA_TANGANI, STATUS_KEMBALI
			, LAST_CREATE_USER, LAST_CREATE_DATE
			, STATUS_DISPOSISI, NAMA_SATKER
			, NAMA_SATKER_ASAL, DISPOSISI_PARENT_ID, DISPOSISI_KELOMPOK_ID
			, TERUSKAN, NAMA_USER_ASAL, USER_ID_OBSERVER, CABANG_ID_TUJUAN
			, KETERANGAN, SIFAT_NAMA, STATUS_BANTU, STATUS_BANTU_TRIGER
		)
		SELECT
		(COALESCE((SELECT MAX(DISPOSISI_ID) FROM DISPOSISI),0) + 1) DISPOSISI_ID
		, SURAT_MASUK_ID, TAHUN, SATUAN_KERJA_ID_ASAL, SATUAN_KERJA_ID_TUJUAN
		, NOW() TANGGAL_DISPOSISI, USER_ID, NAMA_USER
		, TERPARAF, ISI, TANGGAL_BATAS, TERTANDA_TANGANI, STATUS_KEMBALI
		, '".$this->getField("LAST_CREATE_USER")."' LAST_CREATE_USER, CURRENT_DATE LAST_CREATE_DATE
		, STATUS_DISPOSISI, NAMA_SATKER
		, NAMA_SATKER_ASAL, DISPOSISI_PARENT_ID, DISPOSISI_KELOMPOK_ID
		, TERUSKAN, NAMA_USER_ASAL, USER_ID_OBSERVER, CABANG_ID_TUJUAN
		, KETERANGAN, SIFAT_NAMA, -1 STATUS_BANTU, NULL STATUS_BANTU_TRIGER
		FROM DISPOSISI A
		WHERE 1=1
		AND A.STATUS_BANTU = 1
		AND A.SURAT_MASUK_ID = ". $this->getField("SURAT_MASUK_ID")." AND A.DISPOSISI_ID = ". $this->getField("DISPOSISI_ID")."
		";

		$this->query = $str;
		// echo $str; exit;
		return $this->execQuery($str);
	}

	function insertAttachment()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("SURAT_MASUK_ATTACHMENT_ID", $this->getNextId("SURAT_MASUK_ATTACHMENT_ID", "SURAT_MASUK_ATTACHMENT"));

		$str = "INSERT INTO SURAT_MASUK_ATTACHMENT(
						SURAT_MASUK_ATTACHMENT_ID, SURAT_MASUK_ID, ATTACHMENT, 
						UKURAN, TIPE, NAMA, LAST_CREATE_USER, LAST_CREATE_DATE
            )
            VALUES ('" . $this->getField("SURAT_MASUK_ATTACHMENT_ID") . "',
					'" . $this->getField("SURAT_MASUK_ID") . "',
					'" . $this->getField("ATTACHMENT") . "',
                    " . (int)$this->getField("UKURAN") . ",
                    '" . $this->getField("TIPE") . "',
                    '" . $this->getField("NAMA") . "',
                    '" . $this->getField("LAST_CREATE_USER") . "',
				  	NOW()
				)";

		$this->query = $str;
		// echo $str;
		// exit;
		$this->id = $this->getField("SURAT_MASUK_ID");
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
			   SURAT_MASUK_ID, ARSIP_TAHUN, ARSIP_ID, ARSIP_KODE, ARSIP_JENIS, ARSIP_SIFAT,
			   ARSIP_ORGANISASI, ARSIP_NOMOR, ARSIP_TANGGAL, ARSIP_JUDUL, ARSIP_STATUS, ARSIP_AUTHOR, ARSIP_CREATE)
			SELECT SURAT_MASUK_ID, TAHUN, '" . $this->getField("ARSIP_ID") . "', NO_AGENDA, '01', 2, SATUAN_KERJA_ID_ASAL, NO_AGENDA, TANGGAL, NOMOR, 
				   'CREATE', '" . $this->getField("ARSIP_AUTHOR") . "', NOW() 
			FROM surat_masuk WHERE SURAT_MASUK_ID =  " . $this->getField("SURAT_MASUK_ID") . "
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

		$str = "UPDATE SURAT_MASUK SET
					LOKASI_SIMPAN			= '" . $this->getField("LOKASI_SIMPAN") . "',
					PERMOHONAN_NOMOR_ID		= '" . (int)$this->getField("PERMOHONAN_NOMOR_ID") . "',
					TANGGAL 					= " . $this->getField("TANGGAL") . ",
					JENIS_NASKAH_ID 			= '" . $this->getField("JENIS_NASKAH_ID") . "',
					JENIS_NASKAH_LEVEL 		= '" . $this->getField("JENIS_NASKAH_LEVEL") . "',
					SIFAT_NASKAH 			= '" . $this->getField("SIFAT_NASKAH") . "',
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
					PRIORITAS_SURAT_ID		= " . $this->getField("PRIORITAS_SURAT_ID") . ",
					ARSIP_ID					= '" . (int)$this->getField("ARSIP_ID") . "',
					ARSIP					= '" . $this->getField("ARSIP") . "',
					JENIS_TTD				= '" . $this->getField("JENIS_TTD") . "',
					MENIMBANG=	'".$this->getField("MENIMBANG")."',
                   	MENGINGAT= '".$this->getField("MENGINGAT")."',
                   	MEMPERHATIKAN= '".$this->getField("MEMPERHATIKAN")."',
                   	MENETAPKAN= '".$this->getField("MENETAPKAN")."',
                   	PERTAMA= '".$this->getField("PERTAMA")."',
                   	KEDUA= '".$this->getField("KEDUA")."',
                   	KETIGA= '".$this->getField("KETIGA")."',
                   	KEEMPAT= '".$this->getField("KEEMPAT")."',
                    KELIMA='".$this->getField("KELIMA")."',
                    KEENAM='".$this->getField("KEENAM")."',
                    KETUJUH='".$this->getField("KETUJUH")."',
                    KEDELAPAN='".$this->getField("KEDELAPAN")."',
                    KESEMBILAN='".$this->getField("KESEMBILAN")."',
                    KESEPULUH='".$this->getField("KESEPULUH")."'
                    , KESEBELAS= '".$this->getField("KESEBELAS")."'
                    , KEDUABELAS= '".$this->getField("KEDUABELAS")."'
                    , KETIGABELAS= '".$this->getField("KETIGABELAS")."'
                    , KEEMPATBELAS= '".$this->getField("KEEMPATBELAS")."'
                    , KELIMABELAS= '".$this->getField("KELIMABELAS")."'
                    , KEENAMBELAS= '".$this->getField("KEENAMBELAS")."'
                    , KETUJUHBELAS= '".$this->getField("KETUJUHBELAS")."'
                    , KEDELAPANBELAS= '".$this->getField("KEDELAPANBELAS")."'
                    , KESEMBILANBELAS= '".$this->getField("KESEMBILANBELAS")."'
                    , KEDUAPULUH= '".$this->getField("KEDUAPULUH")."'
                    , KEDUAPULUHSATU= '".$this->getField("KEDUAPULUHSATU")."'
                    , KEDUAPULUHDUA= '".$this->getField("KEDUAPULUHDUA")."'
                    , KEDUAPULUHTIGA= '".$this->getField("KEDUAPULUHTIGA")."'
                    , KEDUAPULUHEMPAT= '".$this->getField("KEDUAPULUHEMPAT")."'
                    , KEDUAPULUHLIMA= '".$this->getField("KEDUAPULUHLIMA")."'
                    , LAST_UPDATE_DATE			= NOW()
					, SATUAN_KERJA_ID_ASAL= '". $this->getField("SATUAN_KERJA_ID_ASAL")."'
					, SATUAN_KERJA_ID_ASAL_PETIKAN= '". $this->getField("SATUAN_KERJA_ID_ASAL_PETIKAN")."'
					, PEMESAN_SATUAN_KERJA_ID= ". $this->getField("PEMESAN_SATUAN_KERJA_ID")."
					, PEMESAN_SATUAN_KERJA_ISI= '". $this->getField("PEMESAN_SATUAN_KERJA_ISI")."'
					, AN_STATUS= '".$this->getField("AN_STATUS")."'
                    , AN_NAMA= '".$this->getField("AN_NAMA")."'
                    , BUTUH_AKSI_ID= ".$this->getField("BUTUH_AKSI_ID")."
                    , EKSTERNAL_KEPADA_ID= '". $this->getField("EKSTERNAL_KEPADA_ID")."'
                    , EKSTERNAL_KEPADA= '". $this->getField("EKSTERNAL_KEPADA")."'
                    , EKSTERNAL_TEMBUSAN_ID= '". $this->getField("EKSTERNAL_TEMBUSAN_ID")."'
					, EKSTERNAL_TEMBUSAN= '". $this->getField("EKSTERNAL_TEMBUSAN")."'
					, KOTA_TUJUAN= '". $this->getField("KOTA_TUJUAN")."'
					, DASAR= '". $this->getField("DASAR")."'
					, ISI_PERINTAH= '". $this->getField("ISI_PERINTAH")."'
					, LAIN_LAIN= '". $this->getField("LAIN_LAIN")."'
					, SATUAN_KERJA_ID_PERINTAH= '". $this->getField("SATUAN_KERJA_ID_PERINTAH")."'
					, DARI_INFO= '" . $this->getField("DARI_INFO") . "'
					, NOMOR_SURAT_INFO= '" . $this->getField("NOMOR_SURAT_INFO") . "'
					, LAMPIRAN_DRIVE= '" . $this->getField("LAMPIRAN_DRIVE") . "'
		   WHERE SURAT_MASUK_ID 		= '" . $this->getField("SURAT_MASUK_ID") . "'
				";
		$this->query = $str;
		// echo $str;exit;
		return $this->execQuery($str);
	}

	function updatePerintah()
	{

		$str = "UPDATE SURAT_MASUK SET
					
					 SATUAN_KERJA_ID_ASAL= '". $this->getField("SATUAN_KERJA_ID_ASAL")."'
					, SATUAN_KERJA_ID_PERINTAH= '". $this->getField("SATUAN_KERJA_ID_PERINTAH")."'
		   WHERE SURAT_MASUK_ID 		= '" . $this->getField("SURAT_MASUK_ID") . "'
				";
		$this->query = $str;
		// echo $str;exit;
		return $this->execQuery($str);
	}

	function paraf()
	{
		$str = "
		UPDATE SURAT_MASUK_PARAF A SET
			   STATUS_PARAF 		= '1',
			   KODE_PARAF 			= '" . $this->getField("KODE_PARAF") . "',
			   LAST_UPDATE_USER			= '" . $this->getField("LAST_UPDATE_USER") . "',
			   LAST_UPDATE_DATE			= NOW()
		   WHERE A.SURAT_MASUK_ID = '" . $this->getField("SURAT_MASUK_ID") . "' 
		   AND EXISTS
		   (
			   SELECT 1 FROM SURAT_MASUK_AKSES X WHERE X.SURAT_MASUK_ID = A.SURAT_MASUK_ID 
			   AND X.USER_ID = A.USER_ID 
			   AND X.USER_ID = '" . $this->getField("USER_ID") . "' 
			   AND X.AKSES IN ('PEMARAF', 'PLHPEMARAF')
		   )
		";
		$this->query = $str;
		// echo $str;exit;
		$this->execQuery($str);

		// tambahan khusus
		// update next urut
		$str1= "
		UPDATE SURAT_MASUK_PARAF A SET
		NEXT_URUT= 
		(
			SELECT NO_URUT + 1 
			FROM surat_masuk_paraf A
			WHERE A.SURAT_MASUK_ID = '" . $this->getField("SURAT_MASUK_ID") . "' 
			AND EXISTS
			(
				SELECT 1 FROM SURAT_MASUK_AKSES X WHERE X.SURAT_MASUK_ID = A.SURAT_MASUK_ID 
				AND X.USER_ID = A.USER_ID 
				AND X.USER_ID = '" . $this->getField("USER_ID") . "' 
				AND X.AKSES IN ('PEMARAF', 'PLHPEMARAF')
			)
		)
		WHERE A.SURAT_MASUK_ID = '" . $this->getField("SURAT_MASUK_ID") . "'
		";
		return $this->execQuery($str1);
	}

	function revisi()
	{

		$str = "
		UPDATE SURAT_MASUK SET
			   STATUS_SURAT 		= 'REVISI',
			   REVISI 				= '" . $this->getField("REVISI") . "',
			   REVISI_BY			= '" . $this->getField("REVISI_BY") . "',
			   REVISI_DATE			= CURRENT_TIMESTAMP
		   WHERE SURAT_MASUK_ID 		= '" . $this->getField("SURAT_MASUK_ID") . "' AND
	 			 SATUAN_KERJA_ID_ASAL 	= '" . $this->getField("SATUAN_KERJA_ID_ASAL") . "'
				";
		$this->query = $str;
		// echo $str;exit;
		return $this->execQuery($str);
	}

	function revisiinfo()
	{
		$str = "
		UPDATE SURAT_MASUK SET
		REVISI= '" . $this->getField("REVISI") . "',
		REVISI_BY= '" . $this->getField("REVISI_BY") . "',
		REVISI_DATE= CURRENT_TIMESTAMP
		WHERE SURAT_MASUK_ID = '" . $this->getField("SURAT_MASUK_ID") . "'
		";
		$this->query = $str;
		return $this->execQuery($str);
	}

	function kirimSuratKeluar()
	{

		$str = "
		UPDATE SURAT_MASUK SET
			   ARSIP_TU_ID 			= '" . $this->getField("ARSIP_ID") . "',
			   ARSIP_TU				= '" . $this->getField("ARSIP") . "',
			   MEDIA_PENGIRIMAN_ID	= '" . $this->getField("MEDIA_PENGIRIMAN_ID") . "',
			   STATUS_SURAT			= '" . $this->getField("STATUS_SURAT") . "',
			   MEDIA_PENGIRIMAN		= (SELECT NAMA FROM MEDIA_PENGIRIMAN WHERE MEDIA_PENGIRIMAN_ID = '" . $this->getField("MEDIA_PENGIRIMAN_ID") . "'),
			   SENT_BY				= '" . $this->getField("LAST_UPDATE_USER") . "',
			   SENT_DATE			= CURRENT_TIMESTAMP
		   WHERE SURAT_MASUK_ID 	= '" . $this->getField("SURAT_MASUK_ID") . "' 
				";
		$this->query = $str;
		return $this->execQuery($str);
	}

	function approvalSurat()
	{

		$str = "
		UPDATE SURAT_MASUK SET
			STATUS_SURAT			= '" . $this->getField("STATUS_SURAT") . "',
			APPROVAL_DATE			= CURRENT_TIMESTAMP
		   	WHERE SURAT_MASUK_ID 	= '" . $this->getField("SURAT_MASUK_ID") . "' 
		";
		// echo $str;exit;		
		$this->query = $str;
		return $this->execQuery($str);
	}

	function teruskanSuratMasuk()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("SURAT_MASUK_ARSIP_ID", $this->getNextId("SURAT_MASUK_ARSIP_ID", "SURAT_MASUK_ARSIP"));

		$str = "
			INSERT INTO SURAT_MASUK_ARSIP(
            SURAT_MASUK_ARSIP_ID, SURAT_MASUK_ID, CABANG_ID, ARSIP_TU_ID, 
            ARSIP_TU, ARSIP_BY, ARSIP_DATE)
		    VALUES ('" . $this->getField("SURAT_MASUK_ARSIP_ID") . "', '" . $this->getField("SURAT_MASUK_ID") . "', '" . $this->getField("CABANG_ID") . "', '" . $this->getField("ARSIP_TU_ID") . "', 
            '" . $this->getField("ARSIP_TU") . "', '" . $this->getField("ARSIP_BY") . "', CURRENT_TIMESTAMP)
            ";
		$this->query = $str;
		return $this->execQuery($str);
	}

	function updateByField()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "UPDATE SURAT_MASUK A SET
				  " . $this->getField("FIELD") . " 	= '" . $this->getField("FIELD_VALUE") . "',
				  LAST_UPDATE_USER		 		= '" . $this->getField("LAST_UPDATE_USER") . "',
				  LAST_UPDATE_DATE				= NOW()
				WHERE SURAT_MASUK_ID = " . $this->getField("SURAT_MASUK_ID") . "
				";
		$this->query = $str;
		return $this->execQuery($str);
	}

	function updateByFieldValueTime()
	{
		$str = "
		UPDATE SURAT_MASUK A SET
		". $this->getField("FIELD")." = ". $this->getField("FIELD_VALUE")."
		WHERE SURAT_MASUK_ID = " . $this->getField("SURAT_MASUK_ID") . "
		";
		$this->query = $str;
		return $this->execQuery($str);
	}

	function updateByFieldTime()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "UPDATE SURAT_MASUK A SET
				  " . $this->getField("FIELD") . " 	= CURRENT_TIMESTAMP
				WHERE SURAT_MASUK_ID = " . $this->getField("SURAT_MASUK_ID") . "
				";
		$this->query = $str;
		return $this->execQuery($str);
	}

	function updateByFieldValidasi()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "UPDATE SURAT_MASUK A SET
				  " . $this->getField("FIELD") . " 		= '" . $this->getField("FIELD_VALUE") . "',
				  LAST_UPDATE_USER		 	= '" . $this->getField("LAST_UPDATE_USER") . "',
				  LAST_UPDATE_DATE			= NOW()
				WHERE SURAT_MASUK_ID = " . $this->getField("SURAT_MASUK_ID") . " AND
					(
					  SATUAN_KERJA_ID_ASAL = '" . $this->getField("SATUAN_KERJA_ID_ASAL") . "' OR
					  USER_ID = '" . $this->getField("USER_ID") . "' OR
					  EXISTS(SELECT 1 FROM SURAT_MASUK_AKSES X WHERE X.SURAT_MASUK_ID = A.SURAT_MASUK_ID AND X.USER_ID = '" . $this->getField("PEMARAF_ID") . "')
					)
				";
		$this->query = $str;
		// echo $str;
		return $this->execQuery($str);
	}

	function deleteAttachment()
	{
		$str2 = "
		 		DELETE FROM surat_masuk_attachment
                WHERE 
                  SURAT_MASUK_ID = '" . $this->getField("SURAT_MASUK_ID") . "'";

		$this->query = $str2;
		$this->execQuery($str2);
	}

	function delete()
	{
		$str2 = "
		 		DELETE FROM surat_masuk_attachment
                WHERE 
                  SURAT_MASUK_ID = '" . $this->getField("SURAT_MASUK_ID") . "' AND 
				  LAST_CREATE_USER = '" . $this->getField("LAST_CREATE_USER") . "' ";

		$this->query = $str2;
		$this->execQuery($str2);

		$str = "
		 		DELETE FROM disposisi
                WHERE 
                  SURAT_MASUK_ID = '" . $this->getField("SURAT_MASUK_ID") . "' AND 
				  LAST_CREATE_USER = '" . $this->getField("LAST_CREATE_USER") . "' ";

		$this->query = $str;
		$this->execQuery($str);

		$str1 = "
		 		DELETE FROM surat_masuk
                WHERE 
                  SURAT_MASUK_ID = '" . $this->getField("SURAT_MASUK_ID") . "' AND 
				  LAST_CREATE_USER = '" . $this->getField("LAST_CREATE_USER") . "' ";

		$this->query = $str1;
		return $this->execQuery($str1);
	}

	function selectByParams($paramsArray = array(), $limit = -1, $from = -1, $stat = '', $order = "")
	{
		$str = "
		SELECT 
		SURAT_MASUK_ID, SURAT_MASUK_REF_ID, TAHUN, NOMOR, NO_AGENDA, TANGGAL, TANGGAL_DITERUSKAN, A.MENIMBANG, A.MENGINGAT, A.MEMPERHATIKAN, A.MENETAPKAN, 
		A.PERTAMA, A.KEDUA, A.KETIGA, A.KEEMPAT, A.KELIMA, A.KEENAM, A.KETUJUH, A.KEDELAPAN, A.KESEMBILAN, A.KESEPULUH, 
		TANGGAL_BATAS, JENIS, JENIS_TUJUAN, KEPADA, PERIHAL, KLASIFIKASI_ID, 
		INSTANSI_ASAL, ALAMAT_ASAL, KOTA_ASAL, KETERANGAN_ASAL, SATUAN_KERJA_ID_TUJUAN, 
		ISI, JUMLAH_LAMPIRAN, CATATAN, TERBALAS, TERDISPOSISI, SATUAN_KERJA_ID_ASAL, SATUAN_KERJA_ID_ASAL_PETIKAN, 
		TANGGAL_ENTRI, USER_ID, NAMA_USER, TANGGAL_UPDATE, NO_URUT, TERBACA, 
		TERPARAF, TERTANDA_TANGANI, TGL_FISIK, POSISI_SURAT_FISIK, WAITING_LIST, 
		KLASIFIKASI_JENIS, POSISI_SURAT_MASUK, INFO_SMS_POSISI, INFO_SMS, 
		INFO_SMS_NAMA, INFO_SMS_TELEPON, PENERIMA_SURAT, PENERIMA_SURAT_TANGGAL, 
		PENERIMA_SURAT_TTD, STATUS_PENERIMA, STATUS_PENERIMA_TANGGAL, 
		LOKASI_SIMPAN, TANGGAL_KEGIATAN, TANGGAL_KEGIATAN_AKHIR, LOKASI_ORDNER, 
		LOKASI_ORDNER_LEMBAR, LOKASI_ORDNER_TAHUN, SURAT_MASUK_STATUS, 
		SURAT_MASUK_DELETE, LAST_CREATE_USER, LAST_CREATE_DATE, LAST_UPDATE_USER, 
		LAST_UPDATE_DATE, SURAT_MASUK_FINAL, SIFAT_NASKAH, JENIS_NASKAH_ID, STATUS_SURAT, PENYAMPAIAN_SURAT, USER_ATASAN_ID, REVISI,
		SURAT_PDF, TO_CHAR(TANGGAL_KEGIATAN, 'DD-MM-YYYY') TANGGAL_KEGIATAN_EDIT
		, TO_CHAR(TANGGAL_KEGIATAN_AKHIR, 'DD-MM-YYYY') TANGGAL_KEGIATAN_AKHIR_EDIT
		, TO_CHAR(TANGGAL_ENTRI, 'YYYY-MM-DD HH24:MI:SS') TANGGAL_ENTRI_INFO
		, TO_CHAR(TANGGAL_KEGIATAN, 'HH24:MI') JAM_KEGIATAN_EDIT, TO_CHAR(TANGGAL_KEGIATAN_AKHIR, 'HH24:MI') JAM_KEGIATAN_AKHIR_EDIT,
		IS_EMAIL, IS_MEETING, PRIORITAS_SURAT_ID, PRIORITAS_SURAT, PERMOHONAN_NOMOR_ID, JENIS_NASKAH_LEVEL,
		ARSIP_ID, ARSIP, JENIS_TTD, LAMPIRAN_DRIVE
		, INFO_JENIS_NASKAH_NAMA, APPROVAL_DATE, AN_STATUS, AN_NAMA, BUTUH_AKSI_ID
		, INFO_GENERATE_NOMOR_SURAT
		(
			CABANG_ID
			, SATUAN_KERJA_ID_ASAL
			, JENIS_NASKAH_ID
			, TANGGAL_ENTRI::DATE
			, 0::INTEGER
			, KL.KL_KODE::CHARACTER VARYING
			, TARGET
			, SURAT_MASUK_ID::INTEGER
			, 0::INTEGER
		) INFO_NOMOR_SURAT
		, PEMESAN_SATUAN_KERJA_ID, PEMESAN_SATUAN_KERJA_ISI, TARGET JENIS_SURAT
		, EKSTERNAL_KEPADA_ID, EKSTERNAL_KEPADA, EKSTERNAL_TEMBUSAN_ID, EKSTERNAL_TEMBUSAN
		, A.KOTA_TUJUAN
		, A.DASAR
		, A.ISI_PERINTAH
		, A.LAIN_LAIN, A.DARI_INFO, A.NOMOR_SURAT_INFO
		FROM SURAT_MASUK A
		LEFT JOIN (SELECT JENIS_NASKAH_ID INFO_JENIS_NASKAH_ID, NAMA INFO_JENIS_NASKAH_NAMA FROM JENIS_NASKAH) B ON JENIS_NASKAH_ID = INFO_JENIS_NASKAH_ID
		LEFT JOIN (SELECT KLASIFIKASI_ID KL_ID, KODE KL_KODE FROM KLASIFIKASI) KL ON A.KLASIFIKASI_ID = KL.KL_ID
		WHERE 1 = 1
		";

		while (list($key, $val) = each($paramsArray)) {
			$str .= " AND $key = '$val' ";
		}

		$str .= " " . $stat . "  " . $order;
		$this->query = $str;
		//	echo $str; exit;
		return $this->selectLimit($str, $limit, $from);
	}


	function selectByParamsLihat($paramsArray = array(), $limit = -1, $from = -1, $stat = '', $order = "")
	{
		$str = "SELECT SURAT_MASUK_ID, SURAT_MASUK_REF_ID, TAHUN, NOMOR, NO_AGENDA, TANGGAL, TANGGAL_DITERUSKAN, 
					   TANGGAL_BATAS, JENIS, JENIS_TUJUAN, 
						AMBIL_SURAT_MASUK_TUJUAN(A.SURAT_MASUK_ID, 'TUJUAN') KEPADA, 
						AMBIL_SURAT_MASUK_TUJUAN(A.SURAT_MASUK_ID, 'TEMBUSAN') TEMBUSAN, 
					   PERIHAL, A.KLASIFIKASI_ID, 
					   INSTANSI_ASAL, ALAMAT_ASAL, KOTA_ASAL, KETERANGAN_ASAL, SATUAN_KERJA_ID_TUJUAN, 
					   ISI, JUMLAH_LAMPIRAN, CATATAN, TERBALAS, TERDISPOSISI, SATUAN_KERJA_ID_ASAL, 
					   TANGGAL_ENTRI, USER_ID, NAMA_USER, TANGGAL_UPDATE, A.NO_URUT, TERBACA, 
					   TERPARAF, TERTANDA_TANGANI, TGL_FISIK, POSISI_SURAT_FISIK, WAITING_LIST, 
					   KLASIFIKASI_JENIS, POSISI_SURAT_MASUK, INFO_SMS_POSISI, INFO_SMS, 
					   SURAT_MASUK_FINAL, SIFAT_NASKAH, JENIS_NASKAH_ID, STATUS_SURAT, PENYAMPAIAN_SURAT, USER_ATASAN_ID, REVISI,
					   SURAT_PDF, TO_CHAR(TANGGAL_KEGIATAN, 'DD-MM-YYYY') TANGGAL_KEGIATAN_EDIT, TO_CHAR(TANGGAL_KEGIATAN_AKHIR, 'DD-MM-YYYY') TANGGAL_KEGIATAN_AKHIR_EDIT, 
					   TO_CHAR(TANGGAL_KEGIATAN, 'HH24:MI') JAM_KEGIATAN_EDIT, TO_CHAR(TANGGAL_KEGIATAN_AKHIR, 'HH24:MI') JAM_KEGIATAN_AKHIR_EDIT,
					   IS_EMAIL, IS_MEETING, B.KODE KLASIFIKASI_KODE, AMBIL_KLASIFIKASI(B.KODE) KLASIFIKASI, PRIORITAS_SURAT
				  FROM SURAT_MASUK A
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
		$str = "SELECT SURAT_MASUK_ID, SURAT_MASUK_REF_ID, TAHUN, NOMOR, NO_AGENDA, TANGGAL, TANGGAL_DITERUSKAN, 
					   TANGGAL_BATAS, JENIS, JENIS_TUJUAN, KEPADA, PERIHAL, KLASIFIKASI_ID, 
					   INSTANSI_ASAL, ALAMAT_ASAL, KOTA_ASAL, KETERANGAN_ASAL, SATUAN_KERJA_ID_TUJUAN, 
					   ISI, JUMLAH_LAMPIRAN, CATATAN, TERBALAS, TERDISPOSISI, SATUAN_KERJA_ID_ASAL, 
					   TANGGAL_ENTRI, USER_ID, NAMA_USER, TANGGAL_UPDATE, NO_URUT, TERBACA, 
					   TERPARAF, TERTANDA_TANGANI, TGL_FISIK, POSISI_SURAT_FISIK, WAITING_LIST, 
					   KLASIFIKASI_JENIS, POSISI_SURAT_MASUK, INFO_SMS_POSISI, INFO_SMS, 
					   INFO_SMS_NAMA, INFO_SMS_TELEPON, PENERIMA_SURAT, PENERIMA_SURAT_TANGGAL, 
					   PENERIMA_SURAT_TTD, STATUS_PENERIMA, STATUS_PENERIMA_TANGGAL, 
					   TO_CHAR(TANGGAL_KEGIATAN, 'YYYY-MM-DD HH24:MI:SS') TANGGAL_KEGIATAN, LOKASI_SIMPAN, TO_CHAR(TANGGAL_KEGIATAN_AKHIR, 'YYYY-MM-DD HH24:MI:SS') TANGGAL_KEGIATAN_AKHIR, LOKASI_ORDNER, 
					   LOKASI_ORDNER_LEMBAR, LOKASI_ORDNER_TAHUN, SURAT_MASUK_STATUS, 
					   SURAT_MASUK_DELETE, LAST_CREATE_USER, LAST_CREATE_DATE, LAST_UPDATE_USER, 
					   LAST_UPDATE_DATE, SURAT_MASUK_FINAL, SIFAT_NASKAH, JENIS_NASKAH_ID, STATUS_SURAT, PENYAMPAIAN_SURAT, USER_ATASAN_ID, REVISI,
					   SURAT_PDF
				  FROM SURAT_MASUK A
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
		$str = "SELECT A.SURAT_MASUK_ID, SURAT_MASUK_REF_ID, TAHUN, NOMOR, NO_AGENDA, TANGGAL, TANGGAL_DITERUSKAN, 
					   TANGGAL_BATAS, JENIS, JENIS_TUJUAN, KEPADA, PERIHAL, KLASIFIKASI_ID, 
					   INSTANSI_ASAL, ALAMAT_ASAL, KOTA_ASAL, KETERANGAN_ASAL, SATUAN_KERJA_ID_TUJUAN, 
					   ISI, JUMLAH_LAMPIRAN, A.CATATAN, TERBALAS, TERDISPOSISI, SATUAN_KERJA_ID_ASAL, 
					   TANGGAL_ENTRI, USER_ID, NAMA_USER, TANGGAL_UPDATE, A.NO_URUT, TERBACA, 
					   TERPARAF, TERTANDA_TANGANI, TGL_FISIK, POSISI_SURAT_FISIK, WAITING_LIST, 
					   KLASIFIKASI_JENIS, POSISI_SURAT_MASUK, INFO_SMS_POSISI, INFO_SMS, 
					   INFO_SMS_NAMA, INFO_SMS_TELEPON, PENERIMA_SURAT, PENERIMA_SURAT_TANGGAL, 
					   PENERIMA_SURAT_TTD, STATUS_PENERIMA, STATUS_PENERIMA_TANGGAL, 
					   TANGGAL_KEGIATAN, LOKASI_SIMPAN, TANGGAL_KEGIATAN_AKHIR, LOKASI_ORDNER, 
					   LOKASI_ORDNER_LEMBAR, LOKASI_ORDNER_TAHUN, SURAT_MASUK_STATUS, 
					   SURAT_MASUK_DELETE, A.LAST_CREATE_USER, A.LAST_CREATE_DATE, A.LAST_UPDATE_USER, 
					   A.LAST_UPDATE_DATE, SURAT_MASUK_FINAL, SIFAT_NASKAH, JENIS_NASKAH_ID, STATUS_SURAT, 
					   PENYAMPAIAN_SURAT, USER_ATASAN_ID, REVISI, SURAT_PDF, ATTACHMENT
				  FROM SURAT_MASUK A
				  LEFT JOIN SURAT_MASUK_ATTACHMENT B ON B.SURAT_MASUK_ID=A.SURAT_MASUK_ID 
				  WHERE 1 = 1
 				";

		while (list($key, $val) = each($paramsArray)) {
			$str .= " AND $key = '$val' ";
		}

		$str .= " " . $stat . "  " . $order;
		$this->query = $str;

		return $this->selectLimit($str, $limit, $from);
	}

	function selectByParamsSuratDokumenKlasifikasi($paramsArray = array(), $limit = -1, $from = -1, $stat = '', $order = "")
	{
		$str = "SELECT A.SURAT_MASUK_ID, SURAT_MASUK_REF_ID, TAHUN, NOMOR, NO_AGENDA, TANGGAL, TANGGAL_DITERUSKAN, 
					   TANGGAL_BATAS, JENIS, JENIS_TUJUAN, KEPADA, PERIHAL, A.KLASIFIKASI_ID, 
					   INSTANSI_ASAL, ALAMAT_ASAL, KOTA_ASAL, KETERANGAN_ASAL, SATUAN_KERJA_ID_TUJUAN, 
					   ISI, JUMLAH_LAMPIRAN, A.CATATAN, TERBALAS, TERDISPOSISI, SATUAN_KERJA_ID_ASAL, 
					   TANGGAL_ENTRI, USER_ID, NAMA_USER, TANGGAL_UPDATE, A.NO_URUT, TERBACA, 
					   TERPARAF, TERTANDA_TANGANI, TGL_FISIK, POSISI_SURAT_FISIK, WAITING_LIST, 
					   KLASIFIKASI_JENIS, POSISI_SURAT_MASUK, INFO_SMS_POSISI, INFO_SMS, 
					   INFO_SMS_NAMA, INFO_SMS_TELEPON, PENERIMA_SURAT, PENERIMA_SURAT_TANGGAL, 
					   PENERIMA_SURAT_TTD, STATUS_PENERIMA, STATUS_PENERIMA_TANGGAL, 
					   TANGGAL_KEGIATAN, LOKASI_SIMPAN, TANGGAL_KEGIATAN_AKHIR, LOKASI_ORDNER, 
					   LOKASI_ORDNER_LEMBAR, LOKASI_ORDNER_TAHUN, SURAT_MASUK_STATUS, 
					   SURAT_MASUK_DELETE, A.LAST_CREATE_USER, A.LAST_CREATE_DATE, A.LAST_UPDATE_USER, 
					   A.LAST_UPDATE_DATE, SURAT_MASUK_FINAL, SIFAT_NASKAH, JENIS_NASKAH_ID, STATUS_SURAT, 
					   PENYAMPAIAN_SURAT, USER_ATASAN_ID, REVISI, SURAT_PDF, ATTACHMENT 
				  FROM SURAT_MASUK A
				  LEFT JOIN SURAT_MASUK_ATTACHMENT B ON B.SURAT_MASUK_ID=A.SURAT_MASUK_ID 
				  INNER JOIN KLASIFIKASI C ON A.KLASIFIKASI_ID = C.KLASIFIKASI_ID 
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
		$str = "
		SELECT 
			A.SURAT_MASUK_ID, SURAT_MASUK_REF_ID, TAHUN, NOMOR, NO_AGENDA, TANGGAL, TANGGAL_DITERUSKAN, 
			TANGGAL_BATAS, JENIS, JENIS_TUJUAN, AMBIL_SURAT_MASUK_TUJUAN(A.SURAT_MASUK_ID, 'TUJUAN') KEPADA, PERIHAL, KLASIFIKASI_ID, 
			INSTANSI_ASAL, ALAMAT_ASAL, KOTA_ASAL, KETERANGAN_ASAL, SATUAN_KERJA_ID_TUJUAN, 
			ISI, C.JUMLAH_LAMPIRAN, CATATAN, TERBALAS, TERDISPOSISI, SATUAN_KERJA_ID_ASAL, 
			TANGGAL_ENTRI, USER_ID, NAMA_USER, TANGGAL_UPDATE, A.NO_URUT, TERBACA, 
			TERPARAF, TERTANDA_TANGANI, TGL_FISIK, POSISI_SURAT_FISIK, WAITING_LIST, 
			KLASIFIKASI_JENIS, POSISI_SURAT_MASUK, INFO_SMS_POSISI, INFO_SMS, 
			INFO_SMS_NAMA, INFO_SMS_TELEPON, PENERIMA_SURAT, PENERIMA_SURAT_TANGGAL, 
			PENERIMA_SURAT_TTD, STATUS_PENERIMA, STATUS_PENERIMA_TANGGAL, 
			TANGGAL_KEGIATAN, LOKASI_SIMPAN, TANGGAL_KEGIATAN_AKHIR, LOKASI_ORDNER, 
			LOKASI_ORDNER_LEMBAR, LOKASI_ORDNER_TAHUN, SURAT_MASUK_STATUS, 
			SURAT_MASUK_DELETE, SURAT_MASUK_FINAL, SIFAT_NASKAH, JENIS_NASKAH_ID, STATUS_SURAT, PENYAMPAIAN_SURAT, USER_ATASAN_ID, REVISI,
			SURAT_PDF, AMBIL_SURAT_MASUK_TUJUAN(A.SURAT_MASUK_ID, 'TEMBUSAN') TEMBUSAN, B.LOKASI LOKASI_SURAT, TTD_KODE, USER_ATASAN, USER_ATASAN_JABATAN,
			D.ALAMAT ALAMAT_UNIT, D.TELEPON TELEPON_UNIT, D.FAX FAX_UNIT, D.NAMA NAMA_UNIT, D.LOKASI LOKASI_UNIT, KD_SURAT, D.KODE KODE_UNIT, A.JENIS_TTD, (SELECT X.KELOMPOK_JABATAN FROM SATUAN_KERJA X WHERE X.SATUAN_KERJA_ID=SATUAN_KERJA_ID_ASAL) 
			, A.AN_STATUS, A.AN_NAMA
			, INFO_GENERATE_NOMOR_SURAT
			(
				A.CABANG_ID
				, A.SATUAN_KERJA_ID_ASAL
				, A.JENIS_NASKAH_ID
				, A.TANGGAL_ENTRI::DATE
				, 0::INTEGER
				, KL.KL_KODE::CHARACTER VARYING
				, A.TARGET
				, A.SURAT_MASUK_ID::INTEGER
				, 0::INTEGER
			) INFO_NOMOR_SURAT
			, A.KOTA_TUJUAN
			, A.DASAR
			, A.ISI_PERINTAH
			, A.LAIN_LAIN
			, EKSTERNAL_KEPADA_ID, EKSTERNAL_KEPADA, EKSTERNAL_TEMBUSAN_ID, EKSTERNAL_TEMBUSAN

		FROM SURAT_MASUK A
		INNER JOIN SATUAN_KERJA B ON A.CABANG_ID = B.SATUAN_KERJA_ID
		LEFT JOIN SURAT_MASUK_JUMLAH_LAMP C ON A.SURAT_MASUK_ID = C.SURAT_MASUK_ID
		LEFT JOIN KODE_UNIT_KERJA D ON D.KODE = B.SATUAN_KERJA_ID
		LEFT JOIN (SELECT KLASIFIKASI_ID KL_ID, KODE KL_KODE FROM KLASIFIKASI) KL ON A.KLASIFIKASI_ID = KL.KL_ID
		WHERE 1 = 1
		";
		// , ''::CHARACTER VARYING

		while (list($key, $val) = each($paramsArray)) {
			$str .= " AND $key = '$val' ";
		}

		$str .= " " . $stat . "  " . $order;
		$this->query = $str;
		// echo $str;
		// exit;
		return $this->selectLimit($str, $limit, $from);
	}


	function selectByParamsSuratAsc($paramsArray = array(), $limit = -1, $from = -1, $stat = '', $order = "")
	{
		$str = "
		SELECT 
			A.SURAT_MASUK_ID, SURAT_MASUK_REF_ID, TAHUN, NOMOR, NO_AGENDA, TANGGAL, TANGGAL_DITERUSKAN, 
			TANGGAL_BATAS, JENIS, JENIS_TUJUAN
			, AMBIL_SURAT_MASUK_TUJUAN_NEW_ASC(A.SURAT_MASUK_ID, 'TUJUAN') KEPADA_INFO_NEW
			, AMBIL_SURAT_MASUK_TUJUAN_ASC(A.SURAT_MASUK_ID, 'TUJUAN') KEPADA
			, PERIHAL, KLASIFIKASI_ID, 
			INSTANSI_ASAL, ALAMAT_ASAL, KOTA_ASAL, KETERANGAN_ASAL, SATUAN_KERJA_ID_TUJUAN, 
			ISI, C.JUMLAH_LAMPIRAN, CATATAN, TERBALAS, TERDISPOSISI, SATUAN_KERJA_ID_ASAL, 
			TANGGAL_ENTRI, USER_ID, NAMA_USER, TANGGAL_UPDATE, A.NO_URUT, TERBACA, 
			TERPARAF, TERTANDA_TANGANI, TGL_FISIK, POSISI_SURAT_FISIK, WAITING_LIST, 
			KLASIFIKASI_JENIS, POSISI_SURAT_MASUK, INFO_SMS_POSISI, INFO_SMS, 
			INFO_SMS_NAMA, INFO_SMS_TELEPON, PENERIMA_SURAT, PENERIMA_SURAT_TANGGAL, 
			PENERIMA_SURAT_TTD, STATUS_PENERIMA, STATUS_PENERIMA_TANGGAL, 
			TANGGAL_KEGIATAN, LOKASI_SIMPAN, TANGGAL_KEGIATAN_AKHIR, LOKASI_ORDNER, 
			LOKASI_ORDNER_LEMBAR, LOKASI_ORDNER_TAHUN, SURAT_MASUK_STATUS, 
			SURAT_MASUK_DELETE, SURAT_MASUK_FINAL, SIFAT_NASKAH, JENIS_NASKAH_ID, STATUS_SURAT, PENYAMPAIAN_SURAT, USER_ATASAN_ID, REVISI,
			SURAT_PDF, AMBIL_SURAT_MASUK_TUJUAN_ASC(A.SURAT_MASUK_ID, 'TEMBUSAN') TEMBUSAN, B.LOKASI LOKASI_SURAT, TTD_KODE, USER_ATASAN, USER_ATASAN_PETIKAN, USER_ATASAN_JABATAN,
			D.ALAMAT ALAMAT_UNIT, D.TELEPON TELEPON_UNIT, D.FAX FAX_UNIT, D.NAMA NAMA_UNIT, D.LOKASI LOKASI_UNIT, KD_SURAT, D.KODE KODE_UNIT, A.JENIS_TTD, (SELECT X.KELOMPOK_JABATAN FROM SATUAN_KERJA X WHERE X.SATUAN_KERJA_ID=SATUAN_KERJA_ID_ASAL) 
			, A.AN_STATUS, A.AN_NAMA
			, INFO_GENERATE_NOMOR_SURAT
			(
				A.CABANG_ID
				, A.SATUAN_KERJA_ID_ASAL
				, A.JENIS_NASKAH_ID
				, A.TANGGAL_ENTRI::DATE
				, 0::INTEGER
				, KL.KL_KODE::CHARACTER VARYING
				, A.TARGET
				, A.SURAT_MASUK_ID::INTEGER
				, 0::INTEGER
			) INFO_NOMOR_SURAT
			, A.KOTA_TUJUAN
			, A.DASAR
			, A.ISI_PERINTAH
			, A.LAIN_LAIN
			, EKSTERNAL_KEPADA_ID, EKSTERNAL_KEPADA, EKSTERNAL_TEMBUSAN_ID, EKSTERNAL_TEMBUSAN
			, TO_CHAR(APPROVAL_QR_DATE, 'YYYY-MM-DD HH24:MI') APPROVAL_QR_DATE
		FROM SURAT_MASUK A
		INNER JOIN SATUAN_KERJA B ON A.CABANG_ID = B.SATUAN_KERJA_ID
		LEFT JOIN SURAT_MASUK_JUMLAH_LAMP C ON A.SURAT_MASUK_ID = C.SURAT_MASUK_ID
		LEFT JOIN KODE_UNIT_KERJA D ON D.KODE = B.SATUAN_KERJA_ID
		LEFT JOIN (SELECT KLASIFIKASI_ID KL_ID, KODE KL_KODE FROM KLASIFIKASI) KL ON A.KLASIFIKASI_ID = KL.KL_ID
		WHERE 1 = 1
		";
		// , ''::CHARACTER VARYING

		while (list($key, $val) = each($paramsArray)) {
			$str .= " AND $key = '$val' ";
		}

		$str .= " " . $stat . "  " . $order;
		$this->query = $str;
		// echo $str;
		// exit;
		return $this->selectLimit($str, $limit, $from);
	}


	function selectByParamsMonitoring($paramsArray = array(), $limit = -1, $from = -1, $stat = '', $sOrder = " ORDER BY TANGGAL_ENTRI DESC ")
	{
		$str = "SELECT SURAT_MASUK_ID, STATUS_SURAT, NOMOR, NO_AGENDA, TO_CHAR(TANGGAL_ENTRI, 'DD-MM-YYYY HH24:MI') TANGGAL_ENTRI, TANGGAL, 
				JENIS JENIS_NASKAH, PERIHAL, SIFAT_NASKAH, INSTANSI_ASAL || ', ' || ALAMAT_ASAL DARI,  
				AMBIL_SURAT_MASUK_TUJUAN(A.SURAT_MASUK_ID, 'TUJUAN') KEPADA, 
				AMBIL_SURAT_MASUK_TUJUAN(A.SURAT_MASUK_ID, 'TEMBUSAN') TEMBUSAN, 
				INSTANSI_ASAL, TERBALAS, TERDISPOSISI, TERBACA, USER_ID, USER_ATASAN_ID, ISI, TERBACA_VALIDASI
				FROM SURAT_MASUK A
				WHERE 1 = 1
		";

		while (list($key, $val) = each($paramsArray)) {
			$str .= " AND $key = '$val' ";
		}

		$str .= " " . $stat . " " . $sOrder;
		$this->query = $str;
		return $this->selectLimit($str, $limit, $from);
	}

	function selectByParamsPltJabatan($paramsArray = array(), $limit = -1, $from = -1, $stat = '', $sOrder = "")
	{
		$str = "SELECT USER_ATASAN_JABATAN,JABATAN,AN_TAMBAHAN FROM SURAT_MASUK A
		INNER JOIN SATUAN_KERJA B ON B.NIP = A.USER_ATASAN_ID
		INNER JOIN PEJABAT_PENGGANTI C ON C.PEGAWAI_ID_PENGGANTI = A.USER_ATASAN_ID
		WHERE 1 = 1
		";

		while (list($key, $val) = each($paramsArray)) {
			$str .= " AND $key = '$val' ";
		}

		$str .= " " . $stat . " " . $sOrder;
		$this->query = $str;
		return $this->selectLimit($str, $limit, $from);
	}

	function selectByParamsLogRegistrasiKeluar($paramsArray = array(), $limit = -1, $from = -1, $stat = '', $sOrder = " ORDER BY TANGGAL DESC ")
	{
		$str = "
			SELECT SURAT_MASUK_ID, NOMOR, TANGGAL, PERIHAL, JENIS_NASKAH_ID, SATUAN_KERJA_ID_ASAL, INSTANSI_ASAL,  
				AMBIL_SURAT_MASUK_TUJUAN(A.SURAT_MASUK_ID, 'TUJUAN') INSTANSI_TUJUAN
			FROM SURAT_MASUK A
			WHERE 1 = 1
		";

		while (list($key, $val) = each($paramsArray)) {
			$str .= " AND $key = '$val' ";
		}

		$str .= " " . $stat . " " . $sOrder;
		$this->query = $str;
		return $this->selectLimit($str, $limit, $from);
	}

	function selectByParamsMonitoringDraft($paramsArray = array(), $limit = -1, $from = -1, $stat = '', $sOrder = " ORDER BY TANGGAL_ENTRI::TIMESTAMP DESC ")
	{
		$str = "
		SELECT 
			JENIS_SURAT, SURAT_MASUK_ID, STATUS_SURAT, NOMOR, NO_AGENDA, 
			TO_CHAR(TANGGAL_ENTRI, 'DD-MM-YYYY HH24:MI') TANGGAL_ENTRI, TANGGAL, JENIS_NASKAH, PERIHAL, SIFAT_NASKAH, 
			KEPADA, TEMBUSAN, INSTANSI_ASAL, TERBALAS, TERDISPOSISI, TERBACA, 
			USER_ID, USER_ATASAN_ID, ISI, TERBACA_VALIDASI, KLASIFIKASI, KLASIFIKASI_KODE, JENIS, JENIS_NASKAH_ID
			, USER_ATASAN, USER_ATASAN_JABATAN
		FROM DRAFT A
		WHERE 1 = 1
		";

		while (list($key, $val) = each($paramsArray)) {
			$str .= " AND $key = '$val' ";
		}

		$str .= " " . $stat . " " . $sOrder;
		$this->query = $str;
		// echo $str; exit;
		return $this->selectLimit($str, $limit, $from);
	}

	function selectByParamsMonitoringDraftInfo($paramsArray = array(), $limit = -1, $from = -1, $stat = '', $sOrder = " ORDER BY TANGGAL_ENTRI::TIMESTAMP DESC ")
	{
		$str = "
		SELECT 
			JENIS_SURAT, SURAT_MASUK_ID, STATUS_SURAT, NOMOR, NO_AGENDA, 
			TO_CHAR(TANGGAL_ENTRI, 'DD-MM-YYYY HH24:MI') TANGGAL_ENTRI, TANGGAL, JENIS_NASKAH, PERIHAL, SIFAT_NASKAH, 
			KEPADA, TEMBUSAN, INSTANSI_ASAL, TERBALAS, TERDISPOSISI, TERBACA, 
			USER_ID, USER_ATASAN_ID, ISI, TERBACA_VALIDASI, KLASIFIKASI, KLASIFIKASI_KODE, JENIS, JENIS_NASKAH_ID
			, USER_ATASAN, USER_ATASAN_JABATAN, INFO_NOMOR_SURAT, NOMOR_SURAT_INFO, DARI_INFO
		FROM DRAFT A
		INNER JOIN
		(
			SELECT
			A.SURAT_MASUK_ID SM_ID, NOMOR_SURAT_INFO, DARI_INFO
			, INFO_GENERATE_NOMOR_SURAT
			(
				CABANG_ID
				, SATUAN_KERJA_ID_ASAL
				, JENIS_NASKAH_ID
				, TANGGAL_ENTRI::DATE
				, 0::INTEGER
				, KL.KL_KODE::CHARACTER VARYING
				, TARGET
				, SURAT_MASUK_ID::INTEGER
				, 0::INTEGER
			) INFO_NOMOR_SURAT
			FROM SURAT_MASUK A
			LEFT JOIN (SELECT KLASIFIKASI_ID KL_ID, KODE KL_KODE FROM KLASIFIKASI) KL ON A.KLASIFIKASI_ID = KL.KL_ID
		) SM ON SURAT_MASUK_ID = SM_ID
		WHERE 1 = 1
		";

		while (list($key, $val) = each($paramsArray)) {
			$str .= " AND $key = '$val' ";
		}

		$str .= " " . $stat . " " . $sOrder;
		$this->query = $str;
		// echo $str; exit;
		return $this->selectLimit($str, $limit, $from);
	}

	function selectByParamsMonitoringSent($paramsArray = array(), $limit = -1, $from = -1, $stat = '', $sOrder = " ORDER BY TANGGAL_ENTRI::TIMESTAMP DESC ")
	{
		$str = "SELECT DISTINCT JENIS_SURAT, SURAT_MASUK_ID, STATUS_SURAT, NOMOR, NO_AGENDA, 
					   TO_CHAR(TANGGAL_ENTRI, 'DD-MM-YYYY HH24:MI') TANGGAL_ENTRI, TANGGAL_ENTRI::TIMESTAMP TANGGAL_ENTRY, TANGGAL, JENIS_NASKAH, PERIHAL, SIFAT_NASKAH, 
					   KEPADA, TEMBUSAN, INSTANSI_ASAL, TERBALAS, TERDISPOSISI, TERBACA, TERUSKAN,
					   ISI, TERBACA_VALIDASI, KLASIFIKASI, KLASIFIKASI_KODE, JENIS, MEDIA_PENGIRIMAN_ID, MEDIA_PENGIRIMAN, MEDIA_PENGIRIMAN_RESI
				  FROM DRAFT A
				  WHERE 1 = 1
			   ";

		while (list($key, $val) = each($paramsArray)) {
			$str .= " AND $key = '$val' ";
		}

		$str .= " " . $stat . " " . $sOrder;
		$this->query = $str;
		//echo $str; exit;
		return $this->selectLimit($str, $limit, $from);
	}

	function selectByParamsMonitoringApproval($paramsArray = array(), $limit = -1, $from = -1, $stat = '', $sOrder = " ORDER BY TANGGAL_ENTRI::TIMESTAMP DESC ")
	{
		$str = "SELECT JENIS_SURAT, SURAT_MASUK_ID, STATUS_SURAT, NOMOR, NO_AGENDA, 
					   TO_CHAR(TANGGAL_ENTRI, 'DD-MM-YYYY HH24:MI') TANGGAL_ENTRI, TANGGAL, JENIS_NASKAH, PERIHAL, SIFAT_NASKAH, 
					   KEPADA, TEMBUSAN, INSTANSI_ASAL, TERBALAS, TERDISPOSISI, TERBACA, 
					   USER_ID, USER_ATASAN_ID, ISI, TERBACA_VALIDASI, KLASIFIKASI, KLASIFIKASI_KODE, JENIS, ARSIP_ID, STATUS_BANTU
				  FROM APPROVAL A
				  WHERE 1 = 1
			   ";

		while (list($key, $val) = each($paramsArray)) {
			$str .= " AND $key = '$val' ";
		}

		$str .= " " . $stat . " " . $sOrder;
		$this->query = $str;
		// echo $str; exit;
		return $this->selectLimit($str, $limit, $from);
	}

	function selectByParamsDetil($paramsArray = array(), $limit = -1, $from = -1, $stat = '', $sOrder = " ORDER BY TANGGAL_ENTRI DESC ")
	{
		$str = "SELECT SURAT_MASUK_ID, STATUS_SURAT, NOMOR, NO_AGENDA, TO_CHAR(TANGGAL_ENTRI, 'DD-MM-YYYY HH24:MI') TANGGAL_ENTRI, TANGGAL, 
				JENIS_NASKAH_ID, JENIS JENIS_NASKAH, PERIHAL, SIFAT_NASKAH, 
				AMBIL_SURAT_MASUK_TUJUAN(A.SURAT_MASUK_ID, 'TUJUAN') KEPADA, 
				AMBIL_SURAT_MASUK_TUJUAN(A.SURAT_MASUK_ID, 'TEMBUSAN') TEMBUSAN, 
				JENIS_TUJUAN JENIS_TUJUAN_ID,
				CASE 
					WHEN JENIS_TUJUAN = 'NI' THEN 'Surat Internal'
					WHEN JENIS_TUJUAN = 'AGD' THEN 'Surat Masuk'
					WHEN JENIS_TUJUAN = 'PB' THEN 'Pemberitahuan' END JENIS_TUJUAN,
				CASE 
					WHEN JENIS_TUJUAN = 'NI' THEN 'surat_internal_validasi'
					WHEN JENIS_TUJUAN = 'PB' THEN 'pemberitahuan_validasi' END LINK_VALIDASI,	
				INSTANSI_ASAL, KOTA_ASAL, ALAMAT_ASAL, TERBALAS, TERDISPOSISI, TERTANDA_TANGANI, 
				TERBACA, USER_ID, NAMA_USER, PENYAMPAIAN_SURAT, ISI, SATUAN_KERJA_ID_ASAL, KLASIFIKASI_JENIS, A.JENIS_TTD
				FROM SURAT_MASUK A
				WHERE 1 = 1
		";

		while (list($key, $val) = each($paramsArray)) {
			$str .= " AND $key = '$val' ";
		}

		$str .= " " . $stat . " " . $sOrder;
		$this->query = $str;
		return $this->selectLimit($str, $limit, $from);
	}

	function selectByParamsAkses($paramsArray = array(), $limit = -1, $from = -1, $stat = '', $sOrder = " ORDER BY A.SURAT_MASUK_ID ASC, A.AKSES DESC ")
	{


		$str = " SELECT A.SURAT_MASUK_ID, A.USER_ID, A.AKSES, B.SURAT_PDF, C.ATTACHMENT TEMPLATE_SURAT_WORD, C.LINK_URL TEMPLATE_SURAT,
						A.TERBACA, A.TERBALAS, A.TERDISPOSISI, A.STATUS_PARAF, A.TERBACA_VALIDASI, A.STATUS_SURAT
					FROM SURAT_MASUK_AKSES A 
					INNER JOIN SURAT_MASUK B ON A.SURAT_MASUK_ID = B.SURAT_MASUK_ID
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

	function selectByParamsInbox($paramsArray = array(), $limit = -1, $from = -1, $stat = '', $sOrder = " ORDER BY B.TANGGAL_DISPOSISI DESC ")
	{
		$str = "
				SELECT A.SURAT_MASUK_ID, A.INSTANSI_ASAL, A.USER_ATASAN, USER_ATASAN_JABATAN, B.NAMA_USER_ASAL, B.NAMA_SATKER_ASAL, A.JENIS, A.ALAMAT_ASAL, B.DISPOSISI_ID, B.USER_ID, NOMOR, 
				TO_CHAR(TANGGAL_ENTRI, 'DD-MM-YYYY HH24:MI') TANGGAL_ENTRI, 
				CASE WHEN B.STATUS_DISPOSISI = 'TUJUAN' THEN TO_CHAR(TANGGAL_ENTRI, 'DD-MM-YYYY HH24:MI') ELSE TO_CHAR(TANGGAL_DISPOSISI, 'DD-MM-YYYY HH24:MI') END TANGGAL_DISPOSISI, 
				TANGGAL, B.SATUAN_KERJA_ID_TUJUAN,
				JENIS_TUJUAN JENIS_TUJUAN_ID,
				CASE 
					WHEN JENIS_TUJUAN = 'NI' THEN 'Surat Internal'
					WHEN JENIS_TUJUAN = 'AGD' THEN 'Surat Masuk'
					WHEN JENIS_TUJUAN = 'PB' THEN 'Pemberitahuan' END JENIS_TUJUAN, A.ISI,
				CASE 
					WHEN B.STATUS_DISPOSISI = 'DISPOSISI' THEN '[DISPOSISI] ' 
					WHEN B.STATUS_DISPOSISI = 'TEMBUSAN' THEN '[TEMBUSAN] ' 
					WHEN B.STATUS_DISPOSISI = 'DISPOSISI_TEMBUSAN' THEN '[TEMBUSAN DISPOSISI] ' 
					WHEN B.STATUS_DISPOSISI = 'TERUSAN' THEN '[FWD] ' 
					WHEN B.STATUS_DISPOSISI = 'BALASAN' THEN '[RE] ' 
					ELSE '' END || PERIHAL PERIHAL, JENIS JENIS_NASKAH, SIFAT_NASKAH, 
				INSTANSI_ASAL, B.TERBALAS, B.TERDISPOSISI, B.TERUSKAN, B.TERBACA, B.STATUS_DISPOSISI, B.ISI DISPOSISI, B.DISPOSISI_PARENT_ID, A.PENYAMPAIAN_SURAT,
				C.KODE KLASIFIKASI_KODE, AMBIL_KLASIFIKASI(C.KODE) KLASIFIKASI,
				B.SATUAN_KERJA_ID_ASAL, B.USER_ID_OBSERVER, B.KETERANGAN 
				FROM SURAT_MASUK A
				INNER JOIN DISPOSISI B ON A.SURAT_MASUK_ID = B.SURAT_MASUK_ID
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
		$str = "SELECT SURAT_MASUK_ID, STATUS_SURAT, NOMOR, NO_AGENDA, TO_CHAR(TANGGAL_ENTRI, 'DD-MM-YYYY HH24:MI') TANGGAL_ENTRI, TANGGAL, 
				JENIS JENIS_NASKAH, 
				CASE 
					WHEN A.STATUS_SURAT = 'PARAF' THEN '[PARAF] ' 
					WHEN A.STATUS_SURAT = 'VALIDASI' THEN '[VALIDASI] ' 
					ELSE '' END || PERIHAL PERIHAL,  
				SIFAT_NASKAH, 
				AMBIL_SURAT_MASUK_TUJUAN(A.SURAT_MASUK_ID, 'TUJUAN') KEPADA, 
				AMBIL_SURAT_MASUK_TUJUAN(A.SURAT_MASUK_ID, 'TEMBUSAN') TEMBUSAN, 
				JENIS_TUJUAN JENIS_TUJUAN_ID,
				CASE 
					WHEN JENIS_TUJUAN = 'NI' THEN 'Surat Internal'
					WHEN JENIS_TUJUAN = 'AGD' THEN 'Surat Masuk'
					WHEN JENIS_TUJUAN = 'PB' THEN 'Pemberitahuan' END JENIS_TUJUAN,
				CASE 
					WHEN JENIS_TUJUAN = 'NI' THEN 'surat_internal_' || LOWER(A.STATUS_SURAT)
					WHEN JENIS_TUJUAN = 'PB' THEN 'pemberitahuan_' || LOWER(A.STATUS_SURAT) END LINK_VALIDASI,	
				INSTANSI_ASAL, TERBALAS, TERDISPOSISI, TERTANDA_TANGANI, TERBACA, USER_ID, NAMA_USER
							  FROM SURAT_MASUK A
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
		$str = "SELECT SURAT_MASUK_ID, STATUS_SURAT, NOMOR, NO_AGENDA, TO_CHAR(TANGGAL_ENTRI, 'DD-MM-YYYY HH24:MI') TANGGAL_ENTRI, TANGGAL, 
				JENIS JENIS_NASKAH, 
				CASE 
					WHEN A.STATUS_SURAT = 'PARAF' THEN '[MENUNGGU PARAF] ' 
					WHEN A.STATUS_SURAT = 'POSTING' THEN '[POSTING] ' 
					ELSE '' END || PERIHAL PERIHAL, 
				SIFAT_NASKAH, 
				AMBIL_SURAT_MASUK_TUJUAN(A.SURAT_MASUK_ID, 'TUJUAN') KEPADA, 
				AMBIL_SURAT_MASUK_TUJUAN(A.SURAT_MASUK_ID, 'TEMBUSAN') TEMBUSAN, 
				JENIS_TUJUAN JENIS_TUJUAN_ID,
				CASE 
					WHEN JENIS_TUJUAN = 'NI' THEN 'Surat Internal'
					WHEN JENIS_TUJUAN = 'AGD' THEN 'Surat Masuk'
					WHEN JENIS_TUJUAN = 'PB' THEN 'Pemberitahuan' END JENIS_TUJUAN,
				CASE 
					WHEN JENIS_TUJUAN = 'NI' THEN 'surat_internal_sent'
					WHEN JENIS_TUJUAN = 'PB' THEN 'pemberitahuan_sent' END LINK_VALIDASI,	
				INSTANSI_ASAL, TERBALAS, TERDISPOSISI, TERTANDA_TANGANI, TERBACA, USER_ID, NAMA_USER
							  FROM SURAT_MASUK A
				  WHERE 1 = 1
			   ";

		while (list($key, $val) = each($paramsArray)) {
			$str .= " AND $key = '$val' ";
		}

		$str .= " " . $stat . " " . $sOrder;
		$this->query = $str;
		return $this->selectLimit($str, $limit, $from);
	}

	function selectByParamsAttachment($paramsArray = array(), $limit = -1, $from = -1, $stat = '', $sOrder = " ORDER BY SURAT_MASUK_ATTACHMENT_ID ASC ")
	{
		$str = "SELECT SURAT_MASUK_ATTACHMENT_ID, SURAT_MASUK_ID, ATTACHMENT, CATATAN, 
					   UKURAN, TIPE, NAMA, NO_URUT, LAST_CREATE_USER, LAST_CREATE_DATE, 
					   LAST_UPDATE_USER, LAST_UPDATE_DATE
				  FROM SURAT_MASUK_ATTACHMENT A
				  WHERE 1 = 1
			   ";

		while (list($key, $val) = each($paramsArray)) {
			$str .= " AND $key = '$val' ";
		}

		$str .= " " . $stat . " " . $sOrder;
		$this->query = $str;
		return $this->selectLimit($str, $limit, $from);
	}

	function selectByParamsJumlahSurat($userId, $userGroup, $cabangId)
	{
		//  AND COALESCE(TERBACA_VALIDASI, 0) = 0 HILANGKAN TERBACA SUPAYA ATASAN TAU ADA SURAT
		$str = "
		SELECT 
		(
			SELECT COUNT(A.SURAT_MASUK_ID) JUMLAH 
			FROM DISPOSISI A 
			INNER JOIN SURAT_MASUK B ON A.SURAT_MASUK_ID = B.SURAT_MASUK_ID 
			AND 
			(
				B.STATUS_SURAT = 'POSTING' 
				OR 
				(
					B.STATUS_SURAT = 'TU-IN' 
					AND
					EXISTS
					(
						SELECT 1 
						FROM SURAT_MASUK_ARSIP X WHERE X.SURAT_MASUK_ID = B.SURAT_MASUK_ID AND X.CABANG_ID = '" . $cabangId . "'
					)
				)
			) WHERE A.USER_ID = '" . $userId . "' AND COALESCE(A.TERBACA, 0) = 0
		) JUMLAH_INBOX
		,
		(
			SELECT COUNT(SURAT_MASUK_ID) JUMLAH 
			FROM APPROVAL 
			WHERE 
			(
				(USER_ATASAN_ID = '" . $userId . "' AND  APPROVAL_DATE IS NULL )
				OR 
				(USER_ATASAN_ID = '" . $userGroup . $userId . "' AND APPROVAL_DATE IS NOT NULL)
			) AND STATUS_SURAT IN ('VALIDASI', 'PARAF')
		) JUMLAH_VALIDASI
		,
		(
			SELECT COUNT(SURAT_MASUK_ID) JUMLAH 
			FROM APPROVAL 
			WHERE 
			(
				(USER_ATASAN_ID = '" . $userId . "' AND  APPROVAL_DATE IS NULL )
				OR 
				(USER_ATASAN_ID = '" . $userGroup . $userId . "' AND APPROVAL_DATE IS NOT NULL)
			) AND STATUS_SURAT IN ('VALIDASI', 'PARAF')
			AND NO_URUT = NEXT_URUT
		) JUMLAH_PERSETUJUAN
		,
		(
			SELECT COUNT(SURAT_MASUK_ID) JUMLAH 
			FROM DRAFT 
			WHERE USER_ID = '" . $userId . "' 
			AND STATUS_SURAT IN ('DRAFT', 'REVISI')
			AND JENIS_NASKAH_ID NOT IN (1)
		) JUMLAH_DRAFT
		,
		(
			SELECT COUNT(SURAT_MASUK_ID) JUMLAH 
			FROM DRAFT 
			WHERE USER_ID = '" . $userId . "' 
			AND STATUS_SURAT IN ('DRAFT', 'REVISI')
			AND JENIS_NASKAH_ID IN (1)
		) JUMLAH_DRAFT_MANUAL
		";
		// echo $str;exit;
		return $this->selectLimit($str, -1, -1);
	}

	function selectByParamsModifJumlahSurat($userId, $userGroup, $cabangId, $useridatasan, $userkelompok)
	{
		$str = "
		SELECT 
		(
			SELECT COUNT(A.SURAT_MASUK_ID) JUMLAH 
			FROM DISPOSISI A 
			INNER JOIN SURAT_MASUK B ON A.SURAT_MASUK_ID = B.SURAT_MASUK_ID 
			AND 
			(
				B.STATUS_SURAT = 'POSTING' 
				OR 
				(
					B.STATUS_SURAT = 'TU-IN' 
					AND
					EXISTS
					(
						SELECT 1 
						FROM SURAT_MASUK_ARSIP X WHERE X.SURAT_MASUK_ID = B.SURAT_MASUK_ID AND X.CABANG_ID = '" . $cabangId . "'
					)
				)
			) WHERE A.USER_ID = '" . $userId . "' AND COALESCE(A.TERBACA, 0) = 0
		) JUMLAH_INBOX
		,
		(
			SELECT COUNT(SURAT_MASUK_ID) JUMLAH 
			FROM APPROVAL 
			WHERE 
			(
				(USER_ATASAN_ID = '" . $userId . "' AND  APPROVAL_DATE IS NULL )
				OR 
				(USER_ATASAN_ID = '" . $userGroup . $userId . "' AND APPROVAL_DATE IS NOT NULL)
			) AND STATUS_SURAT IN ('VALIDASI', 'PARAF')
		) JUMLAH_VALIDASI
		,
		(
			SELECT COUNT(SURAT_MASUK_ID) JUMLAH 
			FROM APPROVAL 
			WHERE 
			(
				(USER_ATASAN_ID = '" . $userId . "' AND  APPROVAL_DATE IS NULL )
				OR 
				(USER_ATASAN_ID = '" . $userGroup . $userId . "' AND APPROVAL_DATE IS NOT NULL)
			) AND STATUS_SURAT IN ('VALIDASI', 'PARAF')
			AND NO_URUT = NEXT_URUT
		) JUMLAH_PERSETUJUAN
		,
		(
			SELECT COUNT(SURAT_MASUK_ID) JUMLAH 
			FROM DRAFT 
			WHERE 
			(
              (
                USER_ID = '".$userId."' AND COALESCE(NULLIF(NIP_MUTASI, ''), NULL) IS NULL
              )
              OR 
              (
                NIP_MUTASI = '".$userId."' AND COALESCE(NULLIF(USER_ID, ''), NULL) IS NOT NULL
              )
            )
			AND STATUS_SURAT IN ('DRAFT', 'REVISI')
			AND JENIS_NASKAH_ID NOT IN (1)
		) JUMLAH_DRAFT
		,
		(
			SELECT COUNT(SURAT_MASUK_ID) JUMLAH 
			FROM DRAFT 
			WHERE 
			(
              (
                USER_ID = '".$userId."' AND COALESCE(NULLIF(NIP_MUTASI, ''), NULL) IS NULL
              )
              OR 
              (
                NIP_MUTASI = '".$userId."' AND COALESCE(NULLIF(USER_ID, ''), NULL) IS NOT NULL
              )
            )
			AND STATUS_SURAT IN ('DRAFT', 'REVISI')
			AND JENIS_NASKAH_ID IN (1)
		) JUMLAH_DRAFT_MANUAL
		, 
		(
			SELECT COUNT(1) JUMLAH
			FROM p_surat_masuk(-1, '".$userId."', '".$userGroup."', '".$cabangId."', '".$useridatasan."', '".$userkelompok."')
		) JUMLAH_KOTAK_MASUK_SEMUA
		, 
		(
			SELECT COUNT(1) JUMLAH
			FROM p_surat_masuk(1, '".$userId."', '".$userGroup."', '".$cabangId."', '".$useridatasan."', '".$userkelompok."')
		) JUMLAH_KOTAK_MASUK_MANUAL
		, 
		(
			SELECT COUNT(1) JUMLAH
			FROM p_surat_masuk(2, '".$userId."', '".$userGroup."', '".$cabangId."', '".$useridatasan."', '".$userkelompok."')
		) JUMLAH_KOTAK_MASUK_NOTA_DINAS
		, 
		(
			SELECT COUNT(1) JUMLAH
			FROM p_surat_masuk(13, '".$userId."', '".$userGroup."', '".$cabangId."', '".$useridatasan."', '".$userkelompok."')
		) JUMLAH_KOTAK_MASUK_SURAT_EDARAN
		, 
		(
			SELECT COUNT(1) JUMLAH
			FROM p_surat_masuk(15, '".$userId."', '".$userGroup."', '".$cabangId."', '".$useridatasan."', '".$userkelompok."')
		) JUMLAH_KOTAK_MASUK_SURAT_KELUAR
		, 
		(
			SELECT COUNT(1) JUMLAH
			FROM p_surat_masuk(17, '".$userId."', '".$userGroup."', '".$cabangId."', '".$useridatasan."', '".$userkelompok."')
		) JUMLAH_KOTAK_MASUK_SURAT_KEPUTUSAN_DIREKSI
		, 
		(
			SELECT COUNT(1) JUMLAH
			FROM p_surat_masuk(8, '".$userId."', '".$userGroup."', '".$cabangId."', '".$useridatasan."', '".$userkelompok."')
		) JUMLAH_KOTAK_MASUK_KEPUTUSAN_DIREKSI
		, 
		(
			SELECT COUNT(1) JUMLAH
			FROM p_surat_masuk(19, '".$userId."', '".$userGroup."', '".$cabangId."', '".$useridatasan."', '".$userkelompok."')
		) JUMLAH_KOTAK_MASUK_INSTRUKSI_DIREKSI
		, 
		(
			SELECT COUNT(1) JUMLAH
			FROM p_surat_masuk(18, '".$userId."', '".$userGroup."', '".$cabangId."', '".$useridatasan."', '".$userkelompok."')
		) JUMLAH_KOTAK_MASUK_SURAT_PERINTAH
		";
		// echo $str;exit;
		return $this->selectLimit($str, -1, -1);
	}

	function selectByParamsGoogleCalendar($paramsArray = array(), $limit = -1, $from = -1, $stat = '', $sOrder = " ORDER BY SURAT_MASUK_ID DESC ")
	{
		$str = "SELECT A.SURAT_MASUK_ID, EXTRACT(EPOCH FROM TANGGAL_KEGIATAN AT TIME ZONE '-07') TANGGAL_KEGIATAN, 
					EXTRACT(EPOCH FROM TANGGAL_KEGIATAN_AKHIR AT TIME ZONE '-07') TANGGAL_KEGIATAN_AKHIR,
					PERIHAL, A.ISI
				FROM SURAT_MASUK A
				WHERE TANGGAL_KEGIATAN IS NOT NULL AND TANGGAL_KEGIATAN_AKHIR IS NOT NULL
			   ";

		while (list($key, $val) = each($paramsArray)) {
			$str .= " AND $key = '$val' ";
		}

		$str .= " " . $stat . " " . $sOrder;
		$this->query = $str;
		return $this->selectLimit($str, $limit, $from);
	}

	function selectByParamsEmailDisposisi($paramsArray = array(), $limit = -1, $from = -1, $stat = '', $sOrder = " ORDER BY SURAT_MASUK_ID DESC ")
	{
		$str = "SELECT B.EMAIL
				FROM DISPOSISI A
				LEFT JOIN PEGAWAI B ON A.USER_ID = B.PEGAWAI_ID
			   ";

		while (list($key, $val) = each($paramsArray)) {
			$str .= " AND $key = '$val' ";
		}

		$str .= " " . $stat . " " . $sOrder;
		$this->query = $str;
		return $this->selectLimit($str, $limit, $from);
	}

	function selectByParamsArsip($paramsArray = array(), $limit = -1, $from = -1, $stat = '', $sOrder = " ORDER BY SURAT_MASUK_ID DESC ")
	{
		$str = "
			SELECT SURAT_MASUK_ID, A.ARSIP_ID, ARSIP, RETENSI_AKTIF, RETENSI_INAKTIF,
				C.KODE || ' - ' || C.NAMA LOKASI_ARSIP, D.KODE || ' - ' || D.NAMA PENYUSUTAN_AKHIR, 
				E.NAMA SATUAN_KERJA
			FROM SURAT_MASUK A
			LEFT JOIN ARSIP B ON A.ARSIP_ID=B.ARSIP_ID
			LEFT JOIN LOKASI_ARSIP C ON B.LOKASI_ARSIP_ID=C.LOKASI_ARSIP_ID
			LEFT JOIN PENYUSUTAN_AKHIR D ON B.PENYUSUTAN_AKHIR_ID=D.PENYUSUTAN_AKHIR_ID
			LEFT JOIN SATUAN_KERJA E ON A.SATUAN_KERJA_ID_ASAL=E.SATUAN_KERJA_ID
			WHERE 1 = 1
		";

		while (list($key, $val) = each($paramsArray)) {
			$str .= " AND $key = '$val' ";
		}

		$str .= " " . $stat . " " . $sOrder;
		$this->query = $str;
		return $this->selectLimit($str, $limit, $from);
	}

	function getCountByParams($paramsArray = array(), $statement = "")
	{
		$str = "SELECT COUNT(SURAT_MASUK_ID) AS ROWCOUNT FROM surat_masuk A WHERE 1=1 ";
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

	function getCountByParamsLogRegistrasiKeluar($paramsArray = array(), $statement = "")
	{
		$str = "SELECT COUNT(SURAT_MASUK_ID) AS ROWCOUNT FROM SURAT_MASUK A WHERE 1=1 ";

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

	function getCountByParamsAttachment($paramsArray = array(), $statement = "")
	{
		$str = "SELECT COUNT(SURAT_MASUK_ID) AS ROWCOUNT FROM SURAT_MASUK_ATTACHMENT A WHERE 1=1 ";
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
		$str = "SELECT COUNT(1) AS ROWCOUNT FROM SURAT_MASUK_AKSES A WHERE 1=1 ";
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

	function getAksesSurat($paramsArray = array(), $statement = "")
	{
		$str = "SELECT AKSES FROM SURAT_MASUK_AKSES A WHERE 1=1 ";
		while (list($key, $val) = each($paramsArray)) {
			$str .= " AND $key = '$val' ";
		}

		$str .= $statement;
		$this->query = $str;
		// echo $str;exit;
		$this->select($str);
		if ($this->firstRow())
			return $this->getField("AKSES");
		else
			return "";
	}

	function getTtdSurat($paramsArray = array(), $statement = "")
	{
		$str = "SELECT TTD_KODE FROM SURAT_MASUK A WHERE 1=1 ";
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

	function selectByParamsGetInfoTtdSurat($paramsArray = array(), $limit = -1, $from = -1, $stat = '', $sOrder = "  ")
	{
		$str = "SELECT 
					A.TTD_KODE, A.USER_ATASAN_JABATAN ||' ('||A.USER_ATASAN||')' APPROVED_BY,
					A.NOMOR, A.APPROVAL_DATE, A.SURAT_MASUK_ID , TO_CHAR(A.APPROVAL_QR_DATE, 'YYYY-MM-DD HH:MM:SS') APPROVAL_QR_DATE
				FROM SURAT_MASUK A 
				WHERE 1=1 
		";

		while (list($key, $val) = each($paramsArray)) {
			$str .= " AND $key = '$val' ";
		}

		$str .= " " . $stat . " " . $sOrder;
		$this->query = $str;
		return $this->selectLimit($str, $limit, $from);
	}

	function getStatusSurat($paramsArray = array(), $statement = "")
	{
		$str = "SELECT STATUS_SURAT FROM surat_masuk A WHERE 1=1 ";
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

	function getTarget($paramsArray = array(), $statement = "")
	{
		$str = "SELECT TARGET FROM surat_masuk A WHERE 1=1 ";
		while (list($key, $val) = each($paramsArray)) {
			$str .= " AND $key = '$val' ";
		}

		$str .= $statement;
		$this->query = $str;
		$this->select($str);
		if ($this->firstRow())
			return $this->getField("TARGET");
		else
			return "";
	}

	function getPemilikSurat($paramsArray = array(), $statement = "")
	{
		$str = "SELECT USER_ATASAN_ID FROM surat_masuk A WHERE 1=1 ";
		while (list($key, $val) = each($paramsArray)) {
			$str .= " AND $key = '$val' ";
		}

		$str .= $statement;
		$this->query = $str;
		$this->select($str);
		if ($this->firstRow())
			return $this->getField("USER_ATASAN_ID");
		else
			return "";
	}

	function getCountByParamsInbox($paramsArray = array(), $statement = "")
	{
		$str = "SELECT COUNT(A.SURAT_MASUK_ID) AS ROWCOUNT 
				FROM SURAT_MASUK A
						INNER JOIN DISPOSISI B ON A.SURAT_MASUK_ID = B.SURAT_MASUK_ID
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
		$str = "SELECT COUNT(SURAT_MASUK_ID) AS ROWCOUNT FROM surat_masuk A WHERE 1=1 ";
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

	function getCountByParamsMonitoringDraft($paramsArray = array(), $statement = "")
	{
		$str = "SELECT COUNT(SURAT_MASUK_ID) AS ROWCOUNT FROM DRAFT A WHERE 1=1 ";
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

	function getCountByParamsMonitoringApproval($paramsArray = array(), $statement = "")
	{
		$str = "SELECT COUNT(SURAT_MASUK_ID) AS ROWCOUNT FROM APPROVAL A WHERE 1=1 ";
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
		$str = "SELECT MAX(NO_AGENDA) AS ROWCOUNT FROM surat_masuk WHERE SURAT_MASUK_ID IS NOT NULL ";
		while (list($key, $val) = each($paramsArray)) {
			$str .= " AND $key = '$val' ";
		}

		$this->select($str);

		if ($this->firstRow())
			return $this->getField("ROWCOUNT");
		else
			return 0;
	}

	function selectByParamsStatus($paramsArray = array(), $limit = -1, $from = -1, $userid, $stat = '', $order = "")
	{
		$str = "
		SELECT 
		A.SURAT_MASUK_ID, A.STATUS_SURAT
		, INFO_NOMOR_SURAT, INFO_STATUS_TANGGAL, A.PERIHAL
		, INFO_STATUS, PERSETUJUAN_INFO, JUMLAH_STEP
		, TO_CHAR(TANGGAL_ENTRI, 'YYY-MM-DD HH24:MI') TANGGAL_ENTRI, A.SIFAT_NASKAH, A.SATUAN_KERJA_ID_ASAL
		, A.PEMESAN_SATUAN_KERJA_ID, A.PEMESAN_SATUAN_KERJA_ISI, A.NOMOR_SURAT_INFO, A.DARI_INFO, A.JENIS_NASKAH_ID
		, C.KODE KLASIFIKASI_KODE, C.KODE_INFO, A.ISI KETERANGAN_ISI
		FROM surat_masuk A
		INNER JOIN (SELECT * FROM p_surat_info()) S ON A.SURAT_MASUK_ID = S.SURAT_MASUK_ID
		INNER JOIN (SELECT * FROM p_surat_status('".$userid."')) SM ON A.SURAT_MASUK_ID = SM.SM_ID
		LEFT JOIN (SELECT KLASIFIKASI_ID, KODE, KODE || ' - ' || NAMA KODE_INFO FROM KLASIFIKASI) C ON A.KLASIFIKASI_ID = C.KLASIFIKASI_ID
		WHERE 1=1
		";

		while (list($key, $val) = each($paramsArray)) {
			$str .= " AND $key = '$val' ";
		}

		$str .= " " . $stat . "  " . $order;
		$this->query = $str;
		// echo $str; exit;
		return $this->selectLimit($str, $limit, $from);
	}

	function getCountByParamsStatus($paramsArray = array(), $userid, $statement = "")
	{
		$str = "
		SELECT COUNT(1) AS ROWCOUNT 
		FROM surat_masuk A 
		INNER JOIN (SELECT * FROM p_surat_status('".$userid."')) SM ON A.SURAT_MASUK_ID = SM.SM_ID
		WHERE 1=1 ";

		while (list($key, $val) = each($paramsArray)) {
			$str .= " AND $key = '$val' ";
		}

		$str .= $statement;
		$this->query = $str;
		// echo $str;exit;
		$this->select($str);
		if ($this->firstRow())
			return $this->getField("ROWCOUNT");
		else
			return 0;
	}

	function selectByParamsPersetujuan($paramsArray = array(), $limit = -1, $from = -1, $userid, $stat = '', $order = "")
	{
		$str = "
		SELECT
			S.*
			, JENIS_SURAT, NOMOR, NO_AGENDA, 
			TO_CHAR(TANGGAL_ENTRI, 'DD-MM-YYYY HH24:MI') TANGGAL_ENTRI, TANGGAL, JENIS_NASKAH, PERIHAL, SIFAT_NASKAH, 
			KEPADA, TEMBUSAN, INSTANSI_ASAL, TERBALAS, TERDISPOSISI, TERBACA, 
			USER_ID, USER_ATASAN_ID, ISI, TERBACA_VALIDASI, KLASIFIKASI, KLASIFIKASI_KODE, JENIS, ARSIP_ID
			, A.NO_URUT, A.NEXT_URUT, A.STATUS_BANTU
		FROM (SELECT * FROM p_surat_info()) S
		INNER JOIN (SELECT SURAT_MASUK_ID, TERPARAF FROM SURAT_MASUK) A1 ON S.SURAT_MASUK_ID = A1.SURAT_MASUK_ID
		INNER JOIN approval A ON A.SURAT_MASUK_ID = S.SURAT_MASUK_ID
		INNER JOIN (SELECT * FROM p_surat_status('".$userid."')) SM ON A.SURAT_MASUK_ID = SM.SM_ID
		WHERE 1 = 1
		AND A.NO_URUT = A.NEXT_URUT
		";

		while (list($key, $val) = each($paramsArray)) {
			$str .= " AND $key = '$val' ";
		}

		$str .= " " . $stat . "  " . $order;
		$this->query = $str;
		// echo $str; exit;
		return $this->selectLimit($str, $limit, $from);
	}

	function getCountByParamsPersetujuan($paramsArray = array(), $userid, $statement = "")
	{
		$str = "
		SELECT COUNT(1) AS ROWCOUNT 
		FROM (SELECT * FROM p_surat_info()) S
		INNER JOIN (SELECT SURAT_MASUK_ID, TERPARAF FROM SURAT_MASUK) A1 ON S.SURAT_MASUK_ID = A1.SURAT_MASUK_ID
		INNER JOIN approval A ON A.SURAT_MASUK_ID = S.SURAT_MASUK_ID
		INNER JOIN (SELECT * FROM p_surat_status('".$userid."')) SM ON A.SURAT_MASUK_ID = SM.SM_ID
		WHERE 1=1
		AND A.NO_URUT = A.NEXT_URUT
		";

		while (list($key, $val) = each($paramsArray)) {
			$str .= " AND $key = '$val' ";
		}

		$str .= $statement;
		$this->query = $str;
		// echo $str;exit;
		$this->select($str);
		if ($this->firstRow())
			return $this->getField("ROWCOUNT");
		else
			return 0;
	}

	function selectByParamsSuratMasuk($paramsArray = array(), $limit = -1, $from = -1, $stat = '', $order = "ORDER BY TANGGAL_ENTRI DESC")
	{
		$str = "
		SELECT
			A.SURAT_MASUK_ID, A.NOMOR, A.USER_ATASAN, USER_ATASAN_JABATAN, A.INSTANSI_ASAL
			, CASE 
			WHEN B.STATUS_DISPOSISI = 'DISPOSISI' THEN '[DISPOSISI] ' 
			WHEN B.STATUS_DISPOSISI = 'TEMBUSAN' THEN '[TEMBUSAN] ' 
			WHEN B.STATUS_DISPOSISI = 'DISPOSISI_TEMBUSAN' THEN '[TEMBUSAN DISPOSISI] ' 
			WHEN B.STATUS_DISPOSISI = 'TERUSAN' THEN '[FWD] ' 
			WHEN B.STATUS_DISPOSISI = 'BALASAN' THEN '[RE] ' 
			ELSE '' END || PERIHAL PERIHAL
			, TO_CHAR(TANGGAL_ENTRI, 'YYYY-MM-DD HH24:MI') TANGGAL_ENTRI
			, TO_CHAR(B.TANGGAL_DISPOSISI, 'YYYY-MM-DD HH24:MI') TANGGAL_DISPOSISI
			, A.JENIS_NASKAH_ID, A.JENIS JENIS_NASKAH, A.SIFAT_NASKAH
			, B.DISPOSISI_ID, B.DISPOSISI_PARENT_ID, B.USER_ID, B.TERBALAS, B.TERDISPOSISI
			, B.TERUSKAN, B.TERBACA, B.STATUS_DISPOSISI, B.ISI DISPOSISI
			, A.SATUAN_KERJA_ID_ASAL, A.PEMESAN_SATUAN_KERJA_ID, A.PEMESAN_SATUAN_KERJA_ISI, B.TERBACA_INFO
			, A.NOMOR_SURAT_INFO, A.DARI_INFO, B.STATUS_BANTU, B.NAMA_SATKER, B.SATUAN_KERJA_ID_TUJUAN
			, SF.PEJABAT_REHAT_SEKARANG_NIP, SF.PEJABAT_REHAT_CHECK
			, C.KODE_INFO, A.ISI KETERANGAN_ISI
		FROM SURAT_MASUK A
		INNER JOIN DISPOSISI B ON A.SURAT_MASUK_ID = B.SURAT_MASUK_ID
		LEFT JOIN (SELECT *, KODE || ' - ' || NAMA KODE_INFO FROM KLASIFIKASI) C ON A.KLASIFIKASI_ID = C.KLASIFIKASI_ID
		LEFT JOIN
		(
			SELECT 
			CASE WHEN COALESCE(NULLIF(NIP, ''), 'X') = 'X' THEN NIP_OBSERVER ELSE NIP END PEJABAT_REHAT_SEKARANG_NIP
			, CHECK_ADA_PEJABAT PEJABAT_REHAT_CHECK
			, SATUAN_KERJA_ID PEJABAT_REHAT_SATUAN_KERJA
			FROM SATUAN_KERJA_FIX
		) SF ON SF.PEJABAT_REHAT_SATUAN_KERJA = B.SATUAN_KERJA_ID_TUJUAN
		WHERE 1 = 1
		";

		while (list($key, $val) = each($paramsArray)) {
			$str .= " AND $key = '$val' ";
		}

		$str .= " " . $stat . "  " . $order;
		$this->query = $str;
		// echo $str; exit;
		return $this->selectLimit($str, $limit, $from);
	}

	function getCountByParamsSuratMasuk($paramsArray = array(), $statement = "")
	{
		$str = "
		SELECT COUNT(1) AS ROWCOUNT 
		FROM SURAT_MASUK A
		INNER JOIN DISPOSISI B ON A.SURAT_MASUK_ID = B.SURAT_MASUK_ID
		LEFT JOIN KLASIFIKASI C ON A.KLASIFIKASI_ID = C.KLASIFIKASI_ID
		LEFT JOIN
		(
			SELECT 
			CASE WHEN COALESCE(NULLIF(NIP, ''), 'X') = 'X' THEN NIP_OBSERVER ELSE NIP END PEJABAT_REHAT_SEKARANG_NIP
			, CHECK_ADA_PEJABAT PEJABAT_REHAT_CHECK
			, SATUAN_KERJA_ID PEJABAT_REHAT_SATUAN_KERJA
			FROM SATUAN_KERJA_FIX
		) SF ON SF.PEJABAT_REHAT_SATUAN_KERJA = B.SATUAN_KERJA_ID_TUJUAN
		WHERE 1 = 1
		";

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

	function selectByParamsDisposisi($paramsArray = array(), $limit = -1, $from = -1, $userid, $stat = '', $order = "")
	{
		$str = "
		SELECT
			A.SURAT_MASUK_ID, A.NOMOR, A.USER_ATASAN, USER_ATASAN_JABATAN, A.INSTANSI_ASAL
			, CASE 
			WHEN B.STATUS_DISPOSISI = 'DISPOSISI' THEN '[DISPOSISI] ' 
			WHEN B.STATUS_DISPOSISI = 'TEMBUSAN' THEN '[TEMBUSAN] ' 
			WHEN B.STATUS_DISPOSISI = 'DISPOSISI_TEMBUSAN' THEN '[TEMBUSAN DISPOSISI] ' 
			WHEN B.STATUS_DISPOSISI = 'TERUSAN' THEN '[FWD] ' 
			WHEN B.STATUS_DISPOSISI = 'BALASAN' THEN '[RE] ' 
			ELSE '' END || PERIHAL PERIHAL
			, TO_CHAR(TANGGAL_ENTRI, 'YYYY-MM-DD HH24:MI') TANGGAL_ENTRI
			, TO_CHAR(B.TANGGAL_DISPOSISI, 'YYYY-MM-DD HH24:MI') TANGGAL_DISPOSISI
			, A.JENIS_NASKAH_ID, A.JENIS JENIS_NASKAH, A.SIFAT_NASKAH
			, B.DISPOSISI_ID, B.DISPOSISI_PARENT_ID, B.USER_ID, B.TERBALAS, B.TERDISPOSISI
			, B.TERUSKAN, B.TERBACA, B.STATUS_DISPOSISI, B.ISI DISPOSISI
			, B.SATUAN_KERJA_ID_ASAL, B.NAMA_SATKER_ASAL, B.NAMA_USER_ASAL, B.NAMA_USER, B.NAMA_SATKER
			, A.NOMOR_SURAT_INFO, B.TERBACA_INFO, A.DARI_INFO
		FROM SURAT_MASUK A
		INNER JOIN 
		(
			SELECT * 
			FROM DISPOSISI A
			WHERE 
			EXISTS
			(
				SELECT 1
				FROM
				(
					SELECT SURAT_MASUK_ID, MAX(DISPOSISI_ID) DISPOSISI_ID
					FROM DISPOSISI 
					WHERE STATUS_DISPOSISI IN ('DISPOSISI')
					AND 
					(
						(
							USER_ID = '".$userid."' AND COALESCE(NULLIF(NIP_MUTASI, ''), NULL) IS NULL
						)
						OR
						NIP_MUTASI = '".$userid."'
					)
					-- AND TERDISPOSISI IS NULL
					GROUP BY SURAT_MASUK_ID
				) X WHERE A.SURAT_MASUK_ID = X.SURAT_MASUK_ID AND A.DISPOSISI_ID = X.DISPOSISI_ID
			)
		) B ON A.SURAT_MASUK_ID = B.SURAT_MASUK_ID
		LEFT JOIN KLASIFIKASI C ON A.KLASIFIKASI_ID = C.KLASIFIKASI_ID
		WHERE 1 = 1
		";

		while (list($key, $val) = each($paramsArray)) {
			$str .= " AND $key = '$val' ";
		}

		$str .= " " . $stat . "  " . $order;
		$this->query = $str;
		// echo $str; exit;
		return $this->selectLimit($str, $limit, $from);
	}

	function getCountByParamsDisposisi($paramsArray = array(), $userid, $statement = "")
	{
		$str = "
		SELECT COUNT(1) AS ROWCOUNT 
		FROM SURAT_MASUK A
		INNER JOIN 
		(
			SELECT * 
			FROM DISPOSISI A
			WHERE 
			EXISTS
			(
				SELECT 1
				FROM
				(
					SELECT SURAT_MASUK_ID, MAX(DISPOSISI_ID) DISPOSISI_ID
					FROM DISPOSISI 
					WHERE STATUS_DISPOSISI IN ('DISPOSISI')
					AND 
					(
						(
							USER_ID = '".$userid."' AND COALESCE(NULLIF(NIP_MUTASI, ''), NULL) IS NULL
						)
						OR
						NIP_MUTASI = '".$userid."'
					)
					-- AND TERDISPOSISI IS NULL
					GROUP BY SURAT_MASUK_ID
				) X WHERE A.SURAT_MASUK_ID = X.SURAT_MASUK_ID AND A.DISPOSISI_ID = X.DISPOSISI_ID
			)
		) B ON A.SURAT_MASUK_ID = B.SURAT_MASUK_ID
		LEFT JOIN KLASIFIKASI C ON A.KLASIFIKASI_ID = C.KLASIFIKASI_ID
		WHERE 1 = 1
		";
		// UNION ALL
		// 			SELECT SURAT_MASUK_ID, MAX(DISPOSISI_ID) DISPOSISI_ID
		// 			FROM DISPOSISI 
		// 			WHERE STATUS_DISPOSISI IN ('TUJUAN')
		// 			AND USER_ID = '".$userid."'
		// 			GROUP BY SURAT_MASUK_ID

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

	function selectByParamsKeluarDisposisi($paramsArray = array(), $limit = -1, $from = -1, $userid, $stat = '', $order = "")
	{
		$str = "
		SELECT
			A.SURAT_MASUK_ID, A.NOMOR, A.USER_ATASAN, USER_ATASAN_JABATAN, A.INSTANSI_ASAL
			, CASE 
			WHEN B.STATUS_DISPOSISI = 'DISPOSISI' THEN '[DISPOSISI] ' 
			WHEN B.STATUS_DISPOSISI = 'TEMBUSAN' THEN '[TEMBUSAN] ' 
			WHEN B.STATUS_DISPOSISI = 'DISPOSISI_TEMBUSAN' THEN '[TEMBUSAN DISPOSISI] ' 
			WHEN B.STATUS_DISPOSISI = 'TERUSAN' THEN '[FWD] ' 
			WHEN B.STATUS_DISPOSISI = 'BALASAN' THEN '[RE] ' 
			ELSE '' END || PERIHAL PERIHAL
			, TO_CHAR(TANGGAL_ENTRI, 'YYYY-MM-DD HH24:MI') TANGGAL_ENTRI
			, TO_CHAR(B.TANGGAL_DISPOSISI, 'YYYY-MM-DD HH24:MI') TANGGAL_DISPOSISI
			, A.JENIS_NASKAH_ID, A.JENIS JENIS_NASKAH, A.SIFAT_NASKAH
			, B.DISPOSISI_ID, B.DISPOSISI_PARENT_ID, B.USER_ID, B.TERBALAS, B.TERDISPOSISI
			, B.TERUSKAN, B.TERBACA, B.STATUS_DISPOSISI, B.ISI DISPOSISI
			, B.SATUAN_KERJA_ID_ASAL, B.NAMA_SATKER_ASAL, B.NAMA_USER_ASAL, B.NAMA_USER, B.NAMA_SATKER
			, INFO_KEPADA_DIPOSISI(B.DISPOSISI_PARENT_ID, TO_CHAR(B.TANGGAL_DISPOSISI, 'YYYY-MM-DD HH24:MI')) DETIL_INFO_KEPADA_DIPOSISI
			, A.NOMOR_SURAT_INFO
		FROM SURAT_MASUK A
		INNER JOIN 
		(
			SELECT *
			FROM DISPOSISI A
			WHERE 
			EXISTS
			(
				SELECT 1
				FROM
				(
					SELECT SURAT_MASUK_ID, MAX(DISPOSISI_ID) DISPOSISI_ID
					, TO_CHAR(TANGGAL_DISPOSISI, 'YYYY-MM-DD HH24:MI') TANGGAL_DISPOSISI
					FROM DISPOSISI 
					WHERE STATUS_DISPOSISI IN ('DISPOSISI')
					AND 
					(
						(
							LAST_CREATE_USER = '".$userid."' AND COALESCE(NULLIF(LAST_CREATE_NIP_MUTASI, ''), NULL) IS NULL
						)
						OR
						(
							LAST_CREATE_NIP_MUTASI = '".$userid."'
							AND SATUAN_KERJA_ID_ASAL IN(SELECT SATUAN_KERJA_ID FROM SATUAN_KERJA WHERE NIP = '".$userid."')
						)
					)
					GROUP BY SURAT_MASUK_ID, TO_CHAR(TANGGAL_DISPOSISI, 'YYYY-MM-DD HH24:MI')
				) X WHERE A.SURAT_MASUK_ID = X.SURAT_MASUK_ID AND A.DISPOSISI_ID = X.DISPOSISI_ID
			)
		) B ON A.SURAT_MASUK_ID = B.SURAT_MASUK_ID
		LEFT JOIN KLASIFIKASI C ON A.KLASIFIKASI_ID = C.KLASIFIKASI_ID
		WHERE 1 = 1
		";

		while (list($key, $val) = each($paramsArray)) {
			$str .= " AND $key = '$val' ";
		}

		$str .= " " . $stat . "  " . $order;
		$this->query = $str;
		// echo $str; exit;
		return $this->selectLimit($str, $limit, $from);
	}

	function getCountByParamsKeluarDisposisi($paramsArray = array(), $userid, $statement = "")
	{
		$str = "
		SELECT COUNT(1) AS ROWCOUNT 
		FROM SURAT_MASUK A
		INNER JOIN 
		(
			SELECT * 
			FROM DISPOSISI A
			WHERE 
			EXISTS
			(
				SELECT 1
				FROM
				(
					SELECT SURAT_MASUK_ID, MAX(DISPOSISI_ID) DISPOSISI_ID
					, TO_CHAR(TANGGAL_DISPOSISI, 'YYYY-MM-DD HH24:MI') TANGGAL_DISPOSISI
					FROM DISPOSISI 
					WHERE STATUS_DISPOSISI IN ('DISPOSISI')
					AND 
					(
						(
							LAST_CREATE_USER = '".$userid."' AND COALESCE(NULLIF(LAST_CREATE_NIP_MUTASI, ''), NULL) IS NULL
						)
						OR
						(
							LAST_CREATE_NIP_MUTASI = '".$userid."'
							AND SATUAN_KERJA_ID_ASAL IN(SELECT SATUAN_KERJA_ID FROM SATUAN_KERJA WHERE NIP = '".$userid."')
						)
					)
					GROUP BY SURAT_MASUK_ID, TO_CHAR(TANGGAL_DISPOSISI, 'YYYY-MM-DD HH24:MI')
				) X WHERE A.SURAT_MASUK_ID = X.SURAT_MASUK_ID AND A.DISPOSISI_ID = X.DISPOSISI_ID
			)
		) B ON A.SURAT_MASUK_ID = B.SURAT_MASUK_ID
		LEFT JOIN KLASIFIKASI C ON A.KLASIFIKASI_ID = C.KLASIFIKASI_ID
		WHERE 1 = 1
		";
		// UNION ALL
		// 			SELECT SURAT_MASUK_ID, MAX(DISPOSISI_ID) DISPOSISI_ID
		// 			FROM DISPOSISI 
		// 			WHERE STATUS_DISPOSISI IN ('TUJUAN')
		// 			AND USER_ID = '".$userid."'
		// 			GROUP BY SURAT_MASUK_ID

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

	function selectByParamsTanggapanDisposisi($paramsArray = array(), $limit = -1, $from = -1, $userid, $stat = '', $order = "")
	{
		$str = "
		SELECT
			A.SURAT_MASUK_ID, A.NOMOR, A.USER_ATASAN, USER_ATASAN_JABATAN, A.INSTANSI_ASAL
			, CASE 
			WHEN B.STATUS_DISPOSISI = 'DISPOSISI' THEN '[DISPOSISI] ' 
			WHEN B.STATUS_DISPOSISI = 'TEMBUSAN' THEN '[TEMBUSAN] ' 
			WHEN B.STATUS_DISPOSISI = 'DISPOSISI_TEMBUSAN' THEN '[TEMBUSAN DISPOSISI] ' 
			WHEN B.STATUS_DISPOSISI = 'TERUSAN' THEN '[FWD] ' 
			WHEN B.STATUS_DISPOSISI = 'BALASAN' THEN '[RE] ' 
			ELSE '' END || PERIHAL PERIHAL
			, TO_CHAR(TANGGAL_ENTRI, 'YYYY-MM-DD HH24:MI') TANGGAL_ENTRI
			, TO_CHAR(B.TANGGAL_DISPOSISI, 'YYYY-MM-DD HH24:MI') TANGGAL_DISPOSISI
			, A.JENIS_NASKAH_ID, A.JENIS JENIS_NASKAH, A.SIFAT_NASKAH
			, B.DISPOSISI_ID, B.DISPOSISI_PARENT_ID, B.USER_ID, B.TERBALAS, B.TERDISPOSISI
			, B.TERUSKAN, B.TERBACA, B.STATUS_DISPOSISI, B.ISI DISPOSISI
			, B.SATUAN_KERJA_ID_ASAL, B.NAMA_SATKER_ASAL, B.NAMA_USER_ASAL, B.NAMA_USER, B.NAMA_SATKER
			, A.NOMOR_SURAT_INFO, B.POSISI_TANGGAPAN, B.TERBACA_INFO
		FROM SURAT_MASUK A
		INNER JOIN 
		(
			SELECT * 
			FROM DISPOSISI A
			WHERE 
			EXISTS
			(
				SELECT 1
				FROM
				(
					SELECT SURAT_MASUK_ID, DISPOSISI_ID
					FROM
					(
						SELECT SURAT_MASUK_ID, -- MAX(DISPOSISI_ID)
						DISPOSISI_ID
						FROM DISPOSISI 
						--WHERE STATUS_DISPOSISI IN ('DISPOSISI', 'BALASAN', 'TUJUAN')
						WHERE STATUS_DISPOSISI IN ('BALASAN')
						-- AND REPLACE(SATUAN_KERJA_ID_TUJUAN, 'PEGAWAI', '') NOT IN ('".$userid."')
						AND
						(
							(
								USER_ID = '".$userid."' AND COALESCE(NULLIF(NIP_MUTASI, ''), NULL) IS NULL
							)
							OR
							NIP_MUTASI = '".$userid."'
						)
						-- AND POSISI_TANGGAPAN = 1
						-- GROUP BY SURAT_MASUK_ID
					) A
				) X WHERE A.SURAT_MASUK_ID = X.SURAT_MASUK_ID AND A.DISPOSISI_ID = X.DISPOSISI_ID
			)
		) B ON A.SURAT_MASUK_ID = B.SURAT_MASUK_ID
		LEFT JOIN KLASIFIKASI C ON A.KLASIFIKASI_ID = C.KLASIFIKASI_ID
		WHERE 1 = 1
		";

		while (list($key, $val) = each($paramsArray)) {
			$str .= " AND $key = '$val' ";
		}

		$str .= " " . $stat . "  " . $order;
		$this->query = $str;
		// echo $str; exit;
		return $this->selectLimit($str, $limit, $from);
	}

	function getCountByParamsTanggapanDisposisi($paramsArray = array(), $userid, $statement = "")
	{
		$str = "
		SELECT COUNT(1) AS ROWCOUNT 
		FROM SURAT_MASUK A
		INNER JOIN 
		(
			SELECT * 
			FROM DISPOSISI A
			WHERE 
			EXISTS
			(
				SELECT 1
				FROM
				(
					SELECT SURAT_MASUK_ID, DISPOSISI_ID
					FROM
					(
						SELECT SURAT_MASUK_ID, --MAX(DISPOSISI_ID) 
						DISPOSISI_ID
						FROM DISPOSISI 
						--WHERE STATUS_DISPOSISI IN ('DISPOSISI', 'BALASAN', 'TUJUAN')
						WHERE STATUS_DISPOSISI IN ('BALASAN')
						-- AND REPLACE(SATUAN_KERJA_ID_TUJUAN, 'PEGAWAI', '') NOT IN ('".$userid."')
						AND
						(
							(
								USER_ID = '".$userid."' AND COALESCE(NULLIF(NIP_MUTASI, ''), NULL) IS NULL
							)
							OR
							NIP_MUTASI = '".$userid."'
						)
						-- AND POSISI_TANGGAPAN = 1
						-- GROUP BY SURAT_MASUK_ID
					) A
				) X WHERE A.SURAT_MASUK_ID = X.SURAT_MASUK_ID AND A.DISPOSISI_ID = X.DISPOSISI_ID
			)
		) B ON A.SURAT_MASUK_ID = B.SURAT_MASUK_ID
		LEFT JOIN KLASIFIKASI C ON A.KLASIFIKASI_ID = C.KLASIFIKASI_ID
		WHERE 1 = 1
		";

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

	function selectByParamsTanggapanKeluarDisposisi($paramsArray = array(), $limit = -1, $from = -1, $userid, $stat = '', $order = "")
	{
		$str = "
		SELECT
			A.SURAT_MASUK_ID, A.NOMOR, A.USER_ATASAN, USER_ATASAN_JABATAN, A.INSTANSI_ASAL
			, CASE 
			WHEN B.STATUS_DISPOSISI = 'DISPOSISI' THEN '[DISPOSISI] ' 
			WHEN B.STATUS_DISPOSISI = 'TEMBUSAN' THEN '[TEMBUSAN] ' 
			WHEN B.STATUS_DISPOSISI = 'DISPOSISI_TEMBUSAN' THEN '[TEMBUSAN DISPOSISI] ' 
			WHEN B.STATUS_DISPOSISI = 'TERUSAN' THEN '[FWD] ' 
			WHEN B.STATUS_DISPOSISI = 'BALASAN' THEN '[RE] ' 
			ELSE '' END || PERIHAL PERIHAL
			, TO_CHAR(TANGGAL_ENTRI, 'YYYY-MM-DD HH24:MI') TANGGAL_ENTRI
			, TO_CHAR(B.TANGGAL_DISPOSISI, 'YYYY-MM-DD HH24:MI') TANGGAL_DISPOSISI
			, A.JENIS_NASKAH_ID, A.JENIS JENIS_NASKAH, A.SIFAT_NASKAH
			, B.DISPOSISI_ID, B.DISPOSISI_PARENT_ID, B.USER_ID, B.TERBALAS, B.TERDISPOSISI
			, B.TERUSKAN, B.TERBACA, B.STATUS_DISPOSISI, B.ISI DISPOSISI
			, B.SATUAN_KERJA_ID_ASAL, B.NAMA_SATKER_ASAL, B.NAMA_USER_ASAL, B.NAMA_USER, B.NAMA_SATKER
			, B.POSISI_TANGGAPAN
		FROM SURAT_MASUK A
		INNER JOIN 
		(
			SELECT * 
			FROM DISPOSISI A
			WHERE 
			EXISTS
			(
				SELECT 1
				FROM
				(
					SELECT SURAT_MASUK_ID, DISPOSISI_ID
					FROM
					(
						SELECT SURAT_MASUK_ID, -- MAX(DISPOSISI_ID) 
						DISPOSISI_ID
						FROM DISPOSISI 
						WHERE STATUS_DISPOSISI IN ('BALASAN')
						AND
						(
							LAST_CREATE_USER = '".$userid."' AND COALESCE(NULLIF(LAST_CREATE_NIP_MUTASI, ''), NULL) IS NULL
						)
						OR
						LAST_CREATE_NIP_MUTASI = '".$userid."'
						-- AND POSISI_TANGGAPAN = 1
						-- GROUP BY SURAT_MASUK_ID
					) A
				) X WHERE A.SURAT_MASUK_ID = X.SURAT_MASUK_ID AND A.DISPOSISI_ID = X.DISPOSISI_ID
			)
		) B ON A.SURAT_MASUK_ID = B.SURAT_MASUK_ID
		LEFT JOIN KLASIFIKASI C ON A.KLASIFIKASI_ID = C.KLASIFIKASI_ID
		WHERE 1 = 1
		";

		while (list($key, $val) = each($paramsArray)) {
			$str .= " AND $key = '$val' ";
		}

		$str .= " " . $stat . "  " . $order;
		$this->query = $str;
		// echo $str; exit;
		return $this->selectLimit($str, $limit, $from);
	}

	function getCountByParamsTanggapanKeluarDisposisi($paramsArray = array(), $userid, $statement = "")
	{
		$str = "
		SELECT COUNT(1) AS ROWCOUNT 
		FROM SURAT_MASUK A
		INNER JOIN 
		(
			SELECT * 
			FROM DISPOSISI A
			WHERE 
			EXISTS
			(
				SELECT 1
				FROM
				(
					SELECT SURAT_MASUK_ID, DISPOSISI_ID
					FROM
					(
						SELECT SURAT_MASUK_ID, -- MAX(DISPOSISI_ID) 
						DISPOSISI_ID
						FROM DISPOSISI 
						WHERE STATUS_DISPOSISI IN ('BALASAN')
						AND
						(
							LAST_CREATE_USER = '".$userid."' AND COALESCE(NULLIF(LAST_CREATE_NIP_MUTASI, ''), NULL) IS NULL
						)
						OR
						LAST_CREATE_NIP_MUTASI = '".$userid."'
						-- AND POSISI_TANGGAPAN = 1
						-- GROUP BY SURAT_MASUK_ID
					) A
				) X WHERE A.SURAT_MASUK_ID = X.SURAT_MASUK_ID AND A.DISPOSISI_ID = X.DISPOSISI_ID
			)
		) B ON A.SURAT_MASUK_ID = B.SURAT_MASUK_ID
		LEFT JOIN KLASIFIKASI C ON A.KLASIFIKASI_ID = C.KLASIFIKASI_ID
		WHERE 1 = 1
		";

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

	function selectByParamsSuratKeluar($paramsArray = array(), $limit = -1, $from = -1, $userid, $stat = '', $order = "")
	{
		$str = "
		SELECT
			DISTINCT A.SURAT_MASUK_ID, A.NOMOR, A.NOMOR INFO_NOMOR_SURAT, A.USER_ATASAN, USER_ATASAN_JABATAN, A.INSTANSI_ASAL
			, CASE 
			WHEN B.STATUS_DISPOSISI = 'DISPOSISI' THEN '[DISPOSISI] ' 
			WHEN B.STATUS_DISPOSISI = 'TEMBUSAN' THEN '[TEMBUSAN] ' 
			WHEN B.STATUS_DISPOSISI = 'DISPOSISI_TEMBUSAN' THEN '[TEMBUSAN DISPOSISI] ' 
			WHEN B.STATUS_DISPOSISI = 'TERUSAN' THEN '[FWD] ' 
			WHEN B.STATUS_DISPOSISI = 'BALASAN' THEN '[RE] ' 
			ELSE '' END || PERIHAL PERIHAL
			, TO_CHAR(TANGGAL_ENTRI, 'YYYY-MM-DD HH24:MI') TANGGAL_ENTRI
			, TO_CHAR(B.TANGGAL_DISPOSISI, 'YYYY-MM-DD HH24:MI') TANGGAL_DISPOSISI
			, A.JENIS_NASKAH_ID, A.JENIS JENIS_NASKAH, A.SIFAT_NASKAH
			, B.DISPOSISI_ID, B.DISPOSISI_PARENT_ID, B.USER_ID, B.TERBALAS, B.TERDISPOSISI
			, B.TERUSKAN, B.TERBACA, B.STATUS_DISPOSISI, B.ISI DISPOSISI, A.SATUAN_KERJA_ID_ASAL
			, A.PEMESAN_SATUAN_KERJA_ID, A.PEMESAN_SATUAN_KERJA_ISI, A.NOMOR_SURAT_INFO, A.DARI_INFO, B.TANGGAL_DISPOSISI tgl_disposisi_x
			, C.KODE KLASIFIKASI_KODE, C.KODE_INFO, A.ISI KETERANGAN_ISI
		FROM SURAT_MASUK A
		INNER JOIN (SELECT * FROM p_surat_terkirim('".$userid."')) SM ON A.SURAT_MASUK_ID = SM.SM_ID
		INNER JOIN 
		(
			SELECT
				DISPOSISI_ID, SURAT_MASUK_ID, TAHUN, SATUAN_KERJA_ID_ASAL, SATUAN_KERJA_ID_TUJUAN, 
				TANGGAL_DISPOSISI, USER_ID, NAMA_USER, TERBACA, TERBALAS, TERDISPOSISI, 
				TERPARAF, ISI, TANGGAL_BATAS, TERTANDA_TANGANI, STATUS_KEMBALI, 
				LAST_CREATE_USER, LAST_CREATE_DATE, LAST_UPDATE_USER, LAST_UPDATE_DATE, 
				TREE_ID, TREE_PARENT_ID, SURAT_BPPNFI_ID, STATUS_DISPOSISI, NAMA_SATKER, 
				NAMA_SATKER_ASAL, DISPOSISI_PARENT_ID, DISPOSISI_KELOMPOK_ID, 
				TERUSKAN, NAMA_USER_ASAL, USER_ID_OBSERVER, CABANG_ID_TUJUAN, 
				KETERANGAN, SIFAT_NAMA, POSISI_TANGGAPAN, TERBACA_INFO, STATUS_PEJABAT_GANTI, 
				STATUS_BANTU, STATUS_BANTU_TRIGER, NIP_MUTASI, LAST_CREATE_NIP_MUTASI
			FROM DISPOSISI A
			WHERE 
			EXISTS
			(
				SELECT 1
				FROM
				(
					SELECT SURAT_MASUK_ID, MAX(DISPOSISI_ID) DISPOSISI_ID
					FROM DISPOSISI 
					WHERE STATUS_DISPOSISI IN ('DISPOSISI')
					AND USER_ID = '".$userid."'
					GROUP BY SURAT_MASUK_ID
					UNION ALL
					SELECT SURAT_MASUK_ID, MAX(DISPOSISI_ID) DISPOSISI_ID
					FROM DISPOSISI 
					WHERE STATUS_DISPOSISI IN ('TUJUAN')
					GROUP BY SURAT_MASUK_ID
				) X WHERE A.SURAT_MASUK_ID = X.SURAT_MASUK_ID AND A.DISPOSISI_ID = X.DISPOSISI_ID
			)
			UNION ALL
			SELECT 
				NULL DISPOSISI_ID, A.SURAT_MASUK_ID, NULL TAHUN, NULL SATUAN_KERJA_ID_ASAL, NULL SATUAN_KERJA_ID_TUJUAN, 
				A.LAST_UPDATE_DATE TANGGAL_DISPOSISI, NULL USER_ID, NULL NAMA_USER, NULL TERBACA, NULL TERBALAS, NULL TERDISPOSISI, 
				NULL TERPARAF, NULL ISI, NULL TANGGAL_BATAS, NULL TERTANDA_TANGANI, NULL STATUS_KEMBALI, 
				NULL LAST_CREATE_USER, NULL LAST_CREATE_DATE, NULL LAST_UPDATE_USER, NULL LAST_UPDATE_DATE, 
				NULL TREE_ID, NULL TREE_PARENT_ID, NULL SURAT_BPPNFI_ID, NULL STATUS_DISPOSISI, NULL NAMA_SATKER, 
				NULL NAMA_SATKER_ASAL, NULL DISPOSISI_PARENT_ID, NULL DISPOSISI_KELOMPOK_ID, 
				NULL TERUSKAN, NULL NAMA_USER_ASAL, NULL USER_ID_OBSERVER, NULL CABANG_ID_TUJUAN, 
				NULL KETERANGAN, NULL SIFAT_NAMA, NULL POSISI_TANGGAPAN, NULL TERBACA_INFO, NULL STATUS_PEJABAT_GANTI, 
				NULL STATUS_BANTU, NULL STATUS_BANTU_TRIGER, NULL NIP_MUTASI, NULL LAST_CREATE_NIP_MUTASI
			FROM SURAT_MASUK A 
			LEFT JOIN (SELECT COUNT(1) JUMLAH, SURAT_MASUK_ID FROM DISPOSISI GROUP BY SURAT_MASUK_ID) B ON A.SURAT_MASUK_ID = B.SURAT_MASUK_ID
			WHERE COALESCE(B.JUMLAH,0) = 0
		) B ON A.SURAT_MASUK_ID = B.SURAT_MASUK_ID
		LEFT JOIN (SELECT *, KODE || ' - ' || NAMA KODE_INFO FROM KLASIFIKASI) C ON A.KLASIFIKASI_ID = C.KLASIFIKASI_ID
		WHERE 1 = 1
		";

		while (list($key, $val) = each($paramsArray)) {
			$str .= " AND $key = '$val' ";
		}

		$str .= " " . $stat . "  " . $order;
		$this->query = $str;
		// echo $str; exit;
		return $this->selectLimit($str, $limit, $from);
	}

	function getCountByParamsSuratKeluar($paramsArray = array(), $userid, $statement = "")
	{
		$str = "
		SELECT COUNT(1) AS ROWCOUNT 
		FROM SURAT_MASUK A
		INNER JOIN (SELECT * FROM p_surat_terkirim('".$userid."')) SM ON A.SURAT_MASUK_ID = SM.SM_ID
		INNER JOIN 
		(
			SELECT
				DISPOSISI_ID, SURAT_MASUK_ID, TAHUN, SATUAN_KERJA_ID_ASAL, SATUAN_KERJA_ID_TUJUAN, 
				TANGGAL_DISPOSISI, USER_ID, NAMA_USER, TERBACA, TERBALAS, TERDISPOSISI, 
				TERPARAF, ISI, TANGGAL_BATAS, TERTANDA_TANGANI, STATUS_KEMBALI, 
				LAST_CREATE_USER, LAST_CREATE_DATE, LAST_UPDATE_USER, LAST_UPDATE_DATE, 
				TREE_ID, TREE_PARENT_ID, SURAT_BPPNFI_ID, STATUS_DISPOSISI, NAMA_SATKER, 
				NAMA_SATKER_ASAL, DISPOSISI_PARENT_ID, DISPOSISI_KELOMPOK_ID, 
				TERUSKAN, NAMA_USER_ASAL, USER_ID_OBSERVER, CABANG_ID_TUJUAN, 
				KETERANGAN, SIFAT_NAMA, POSISI_TANGGAPAN, TERBACA_INFO, STATUS_PEJABAT_GANTI, 
				STATUS_BANTU, STATUS_BANTU_TRIGER, NIP_MUTASI, LAST_CREATE_NIP_MUTASI
			FROM DISPOSISI A
			WHERE 
			EXISTS
			(
				SELECT 1
				FROM
				(
					SELECT SURAT_MASUK_ID, MAX(DISPOSISI_ID) DISPOSISI_ID
					FROM DISPOSISI 
					WHERE STATUS_DISPOSISI IN ('DISPOSISI')
					AND USER_ID = '".$userid."'
					GROUP BY SURAT_MASUK_ID
					UNION ALL
					SELECT SURAT_MASUK_ID, MAX(DISPOSISI_ID) DISPOSISI_ID
					FROM DISPOSISI 
					WHERE STATUS_DISPOSISI IN ('TUJUAN')
					GROUP BY SURAT_MASUK_ID
				) X WHERE A.SURAT_MASUK_ID = X.SURAT_MASUK_ID AND A.DISPOSISI_ID = X.DISPOSISI_ID
			)
			UNION ALL
			SELECT 
				NULL DISPOSISI_ID, A.SURAT_MASUK_ID, NULL TAHUN, NULL SATUAN_KERJA_ID_ASAL, NULL SATUAN_KERJA_ID_TUJUAN, 
				NULL TANGGAL_DISPOSISI, NULL USER_ID, NULL NAMA_USER, NULL TERBACA, NULL TERBALAS, NULL TERDISPOSISI, 
				NULL TERPARAF, NULL ISI, NULL TANGGAL_BATAS, NULL TERTANDA_TANGANI, NULL STATUS_KEMBALI, 
				NULL LAST_CREATE_USER, NULL LAST_CREATE_DATE, NULL LAST_UPDATE_USER, NULL LAST_UPDATE_DATE, 
				NULL TREE_ID, NULL TREE_PARENT_ID, NULL SURAT_BPPNFI_ID, NULL STATUS_DISPOSISI, NULL NAMA_SATKER, 
				NULL NAMA_SATKER_ASAL, NULL DISPOSISI_PARENT_ID, NULL DISPOSISI_KELOMPOK_ID, 
				NULL TERUSKAN, NULL NAMA_USER_ASAL, NULL USER_ID_OBSERVER, NULL CABANG_ID_TUJUAN, 
				NULL KETERANGAN, NULL SIFAT_NAMA, NULL POSISI_TANGGAPAN, NULL TERBACA_INFO, NULL STATUS_PEJABAT_GANTI, 
				NULL STATUS_BANTU, NULL STATUS_BANTU_TRIGER, NULL NIP_MUTASI, NULL LAST_CREATE_NIP_MUTASI
			FROM SURAT_MASUK A 
			LEFT JOIN (SELECT COUNT(1) JUMLAH, SURAT_MASUK_ID FROM DISPOSISI GROUP BY SURAT_MASUK_ID) B ON A.SURAT_MASUK_ID = B.SURAT_MASUK_ID
			WHERE COALESCE(B.JUMLAH,0) = 0
		) B ON A.SURAT_MASUK_ID = B.SURAT_MASUK_ID
		LEFT JOIN KLASIFIKASI C ON A.KLASIFIKASI_ID = C.KLASIFIKASI_ID
		WHERE 1 = 1
		";

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

	function selectByParamsDataLog($paramsArray = array(), $limit = -1, $from = -1, $stat = '', $order = "ORDER BY A.SURAT_MASUK_ID, A.TANGGAL DESC")
	{
		$str = "
		SELECT 
			SURAT_MASUK_LOG_ID, SURAT_MASUK_ID, TO_CHAR(TANGGAL, 'YYYY-MM-DD HH24:MI:SS') TANGGAL, STATUS_SURAT, INFORMASI, CATATAN
			, LAST_CREATE_USER, LAST_CREATE_DATE
		FROM surat_masuk_log A
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

	function selectByParamsInfoDisposisi($paramsArray = array(), $limit = -1, $from = -1, $stat = '', $order = "")
	{
		$str = "
		SELECT 
		STATUS_DISPOSISI, DISPOSISI_ID, DISPOSISI_PARENT_ID
		, NAMA_SATKER_ASAL, NAMA_USER_ASAL
		, NAMA_SATKER, NAMA_USER
		, TO_CHAR(TANGGAL_DISPOSISI, 'YYYY-MM-DD HH24:MI') TANGGAL_DISPOSISI
		, INFO_KEPADA_DIPOSISI(DISPOSISI_PARENT_ID, TO_CHAR(TANGGAL_DISPOSISI, 'YYYY-MM-DD HH24:MI')) INFO_KEPADA
		, ISI TINDAKAN, KETERANGAN CATATAN, SIFAT_NAMA
		, USER_ID, NIP_MUTASI, TERBALAS, TERBACA_INFO
		FROM disposisi A
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

	function selectByParamsRiwayatSurat($paramsArray = array(), $limit = -1, $from = -1, $stat = '', $order = "")
	{
		$str = "
		SELECT * 
		FROM P_SURAT_RIWAYAT()
		WHERE 1=1
		";

		while (list($key, $val) = each($paramsArray)) {
			$str .= " AND $key = '$val' ";
		}

		$str .= " " . $stat . "  " . $order;
		$this->query = $str;
			// echo $str; exit;
		return $this->selectLimit($str, $limit, $from);
	}

	function selectByParamsBantu($userid)
	{
		$str = "
		SELECT * FROM SATUAN_KERJA_FIX 
		WHERE NIP = '".$userid."'
		";

		$this->query = $str;
		//	echo $str; exit;
		return $this->selectLimit($str, -1, -1);
	}

	function selectByParamsRiwayatSuratStatusBantu($paramsArray = array(), $limit = -1, $from = -1, $stat = '', $order = "")
	{
		$str = "
		SELECT
		R_SM_ID, R_USER_ID_TUJUAN, R_NAMA_TUJUAN, MAX(R_TANGGAL) R_TANGGAL, R_STATUS_PEJABAT_GANTI, MAX(COALESCE(R_STATUS_BANTU,0)) R_STATUS_BANTU
		, COUNT(1) JUMLAH
		FROM P_SURAT_RIWAYAT()
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

	function selectByParamsKepada($paramsArray = array(), $limit = -1, $from = -1, $stat = '', $order = "")
	{
		$str = "
		
		SELECT SURAT_MASUK_ID, SATUAN_KERJA_ID_PERINTAH,B.NAMA SATUAN_KERJA
		FROM SURAT_MASUK_PERINTAH_KEPADA A
		INNER JOIN SATUAN_KERJA B ON A.SATUAN_KERJA_ID_PERINTAH=B.SATUAN_KERJA_ID
		WHERE 1=1
			
		";

		while (list($key, $val) = each($paramsArray)) {
			$str .= " AND $key = '$val' ";
		}

		$str .= " " . $stat . "  " . $order;
		$this->query = $str;
			echo $str; exit;
		return $this->selectLimit($str, $limit, $from);
	}

	function selectByParamsTahun()
	{
		$str = "
		SELECT TAHUN
		FROM
		(
			SELECT TAHUN FROM SURAT_MASUK
			UNION ALL
			SELECT TAHUN FROM DISPOSISI
		) A
		GROUP BY TAHUN
		ORDER BY TAHUN DESC
		";
		$this->query = $str;
		//	echo $str; exit;
		return $this->selectLimit($str, -1, -1);
	}

	function getJson($paramsArray=array(),$statement="")
	{
		$str = "
			SELECT ROW_TO_JSON(A) JSON FROM 
			(SELECT SURAT_MASUK_ID, SATUAN_KERJA_ID_PERINTAH,B.NAMA SATUAN_KERJA
			FROM SURAT_MASUK_PERINTAH_KEPADA A
			INNER JOIN SATUAN_KERJA B ON A.SATUAN_KERJA_ID_PERINTAH=B.SATUAN_KERJA_ID
			) A
			WHERE 1 = 1
		"; 
		while(list($key,$val)=each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement;
		$this->query = $str;
		// echo $str;exit;
		$this->selectLimit($str,-1,-1); 
		$hasil = "[";
		$i = 0;
		while($this->nextRow())
		{
			if($i == 0)
				$hasil .= $this->getField("JSON");
			else
				$hasil .= ",".$this->getField("JSON");
			$i++;		
		}
		$hasil .= "]";		
		$hasil = str_replace("null", '""', $hasil);
		return strtoupper($hasil);
		
    }

    function insertPerintah()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("SURAT_MASUK_PERINTAH_KEPADA_ID", $this->getNextId("SURAT_MASUK_PERINTAH_KEPADA_ID", "SURAT_MASUK_PERINTAH_KEPADA"));

		$str = "INSERT INTO SURAT_MASUK_PERINTAH_KEPADA(SURAT_MASUK_PERINTAH_KEPADA_ID
				 , SURAT_MASUK_ID
				 , SATUAN_KERJA_ID_PERINTAH
            )
            VALUES ('" . $this->getField("SURAT_MASUK_PERINTAH_KEPADA_ID") . "',
					'" . (int)$this->getField("SURAT_MASUK_ID") . "',
					'" . $this->getField("SATUAN_KERJA_ID_PERINTAH") . "'
				)";

		$this->query = $str;
		// echo $str; exit;
		$this->id = $this->getField("SURAT_MASUK_PERINTAH_KEPADA_ID");
		return $this->execQuery($str);
	}
}