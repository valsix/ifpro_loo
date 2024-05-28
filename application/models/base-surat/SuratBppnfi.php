<? 

  /***
  * Entity-base class untuk mengimplementasikan tabel kategori.
  * 
  ***/
  include_once(APPPATH.'/models/Entity.php');

  class SuratBppnfi extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function SuratBppnfi()
	{
      $this->Entity(); 
    }
	
	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("SURAT_BPPNFI_ID", $this->getNextId("SURAT_BPPNFI_ID","SURAT_BPPNFI")); 
		$this->tanggal = date("Y-m-d");
		$this->NowYear = date("Y");
	
		$str = "
		   INSERT INTO SURAT_BPPNFI (
				   SURAT_BPPNFI_ID, TIPE, NO_AGENDA, NOMOR, TANGGAL, PERIHAL, ISI, KEPADA, 
				   TANDA_TANGAN_ID, TANGGAL_UNDANGAN, SURAT_NAMA, SURAT_NIP, SURAT_PANGKAT, SURAT_JABATAN, DARI, 
				   SURAT_KETERANGAN_NAMA1, SURAT_KETERANGAN_NIP1, SURAT_KETERANGAN_PANGKAT1, SURAT_KETERANGAN_JABATAN1, SURAT_KETERANGAN_ALAMAT1, 
				   SURAT_KETERANGAN_NAMA2, SURAT_KETERANGAN_NIP2, SURAT_KETERANGAN_PANGKAT2, SURAT_KETERANGAN_JABATAN2, SURAT_KETERANGAN_ALAMAT2,
				   JUMLAH_LAMPIRAN, SATUAN_KERJA_ID_ASAL, SATUAN_KERJA_ID_PEMBUAT, 
				   SATUAN_KERJA_ID_POSISI, SATUAN_KERJA_ID_AKHIR, TANGGAL_AKSI, 
				   USER_ID, NAMA_USER, TANGGAL_ENTRI, 
				   TANGGAL_UPDATE, KODE_HAL_ID, NOMOR_GENERATE,
				   UNDANGAN_TANGGAL, UNDANGAN_JAM, UNDANGAN_TEMPAT, UNDANGAN_ACARA, ALAMAT, TEMBUSAN,
				   SURAT_REKOMENDASI_UNIT_KERJA1, SURAT_REKOMENDASI_UNIT_KERJA2, KUALIFIKASI_AKADEMIK, 
				   TEMPAT, TANGGAL_LAHIR, NO_INVOICE, JENIS_KELAMIN, NAMA_PERUSAHAAN, ALAMAT_PERUSAHAAN, NO_TELP,
				   URUT, TANGGAL_AWAL, TANGGAL_AKHIR, PELANGGARAN, KOMITMEN, LOKASI, MENIMBANG, MENGINGAT, MENETAPKAN, PERTIMBANGAN, DASAR,
				   TANGGAL_TRANSFER, NOMOR_CEK_BG, NOMOR_REKENING, BANK, JENIS, SURAT_MASUK_ID, SURAT_BPPNFI_BACKDATE, NOMOR_BACKDATE, SATUAN_KERJA_ID_TUJUAN,
				   LAST_CREATE_USER, LAST_CREATE_DATE) 
		   VALUES (
					'".$this->getField("SURAT_BPPNFI_ID")."', 
					'".$this->getField("TIPE")."',
					'".$this->getField("NO_AGENDA")."',
					'".$this->getField("NOMOR")."',
					".$this->getField("TANGGAL").",
					'".$this->getField("PERIHAL")."',
					'".$this->getField("ISI")."',
					'".$this->getField("KEPADA")."',
					".$this->getField("TANDA_TANGAN_ID").",
					".$this->getField("TANGGAL_UNDANGAN").",
					'".$this->getField("SURAT_NAMA")."',
					'".$this->getField("SURAT_NIP")."',
					'".$this->getField("SURAT_PANGKAT")."',
					'".$this->getField("SURAT_JABATAN")."',
					'".$this->getField("DARI")."',
					'".$this->getField("SURAT_KETERANGAN_NAMA1")."',
					'".$this->getField("SURAT_KETERANGAN_NIP1")."',
		   			'".$this->getField("SURAT_KETERANGAN_PANGKAT1")."',
					'".$this->getField("SURAT_KETERANGAN_JABATAN1")."',
					'".$this->getField("SURAT_KETERANGAN_ALAMAT1")."',
					'".$this->getField("SURAT_KETERANGAN_NAMA2")."',
					'".$this->getField("SURAT_KETERANGAN_NIP2")."',
					'".$this->getField("SURAT_KETERANGAN_PANGKAT2")."',
					'".$this->getField("SURAT_KETERANGAN_JABATAN2")."',
					'".$this->getField("SURAT_KETERANGAN_ALAMAT2")."',
					".$this->getField("JUMLAH_LAMPIRAN").",
					'".$this->getField("SATUAN_KERJA_ID_ASAL")."',
					'".$this->getField("SATUAN_KERJA_ID_PEMBUAT")."',
					'".$this->getField("SATUAN_KERJA_ID_POSISI")."',
					'".$this->getField("SATUAN_KERJA_ID_AKHIR")."',
					".$this->getField("TANGGAL_AKSI").",
					".$this->getField("USER_ID").",
					'".$this->getField("NAMA_USER")."',
					".$this->getField("TANGGAL_ENTRI").",
					".$this->getField("TANGGAL_UPDATE").",
					'".$this->getField("KODE_HAL_ID")."',
					'".$this->getField("NOMOR_GENERATE")."',
					".$this->getField("UNDANGAN_TANGGAL").",
					'".$this->getField("UNDANGAN_JAM")."',
					'".$this->getField("UNDANGAN_TEMPAT")."',
					'".$this->getField("UNDANGAN_ACARA")."',
					'".$this->getField("ALAMAT")."',
					'".$this->getField("TEMBUSAN")."',
					'".$this->getField("SURAT_REKOMENDASI_UNIT_KERJA1")."',
					'".$this->getField("SURAT_REKOMENDASI_UNIT_KERJA2")."',
					'".$this->getField("KUALIFIKASI_AKADEMIK")."',
					'".$this->getField("TEMPAT")."',
					".$this->getField("TANGGAL_LAHIR").",
					'".$this->getField("NO_INVOICE")."',
					'".$this->getField("JENIS_KELAMIN")."',
					'".$this->getField("NAMA_PERUSAHAAN")."',
					'".$this->getField("ALAMAT_PERUSAHAAN")."',
					'".$this->getField("NO_TELP")."',
					'".$this->getField("URUT")."',
					".$this->getField("TANGGAL_AWAL").",
					".$this->getField("TANGGAL_AKHIR").",
					'".$this->getField("PELANGGARAN")."',
					'".$this->getField("KOMITMEN")."',
					'".$this->getField("LOKASI")."',
					'".$this->getField("MENIMBANG")."',
					'".$this->getField("MENGINGAT")."',
					'".$this->getField("MENETAPKAN")."',
					'".$this->getField("PERTIMBANGAN")."',
					'".$this->getField("DASAR")."',
					".$this->getField("TANGGAL_TRANSFER").",
					'".$this->getField("NOMOR_CEK_BG")."',
					'".$this->getField("NOMOR_REKENING")."',
					'".$this->getField("BANK")."',
					'".$this->getField("JENIS")."',
				  	".$this->getField("SURAT_MASUK_ID").",
				  	".$this->getField("SURAT_BPPNFI_BACKDATE").",
				  	'".$this->getField("NOMOR_BACKDATE")."',
				  	'".$this->getField("SATUAN_KERJA_ID_TUJUAN").";',
				  	'".$this->getField("LAST_CREATE_USER")."',
				  	CURRENT_DATE
				)";
				
		$this->query = $str;
		$this->reqSuratBppnfiId = $this->getField("SURAT_BPPNFI_ID");
		
		return $this->execQuery($str);
    }
	
	function insertArsip()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("ARSIP_ID", $this->getNextId("ARSIP_ID","arsip.ARSIP")); 
		$this->tanggal = date("Y-m-d");
		$this->NowYear = date("Y");
		
		$str = "
			INSERT INTO arsip.ARSIP(
			   SURAT_BPPNFI_ID, ARSIP_TAHUN, ARSIP_ID, ARSIP_KODE, ARSIP_JENIS, ARSIP_SIFAT,
			   ARSIP_ORGANISASI, ARSIP_NOMOR, ARSIP_TANGGAL, ARSIP_JUDUL, SURAT_BPPNFI_TIPE, ARSIP_STATUS, ARSIP_AUTHOR, ARSIP_CREATE)
			SELECT SURAT_BPPNFI_ID, CAST(TO_CHAR(TANGGAL, 'YYYY') AS INT), '".$this->getField("ARSIP_ID")."', NOMOR, '01', 2, 
				SATUAN_KERJA_ID_PEMBUAT, NOMOR, TANGGAL, NOMOR, TIPE, 'CREATE', '".$this->getField("ARSIP_AUTHOR")."', CURRENT_DATE 
			FROM surat_bppnfi WHERE SURAT_BPPNFI_ID =  ".$this->getField("SURAT_BPPNFI_ID")."
            ";
				
		$this->query = $str;
		$this->id = $this->getField("ARSIP_ID");
		return $this->execQuery($str);
    }
	
	function insertCopy()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("SURAT_BPPNFI_ID", $this->getNextId("SURAT_BPPNFI_ID","SURAT_BPPNFI")); 
		$this->tanggal = date("Y-m-d");
		$this->NowYear = date("Y");
	
		$str = "
		   INSERT INTO SURAT_BPPNFI (
				   SURAT_BPPNFI_ID, TIPE, NO_AGENDA, NOMOR, TANGGAL, PERIHAL, ISI, KEPADA, TANGGAL_TANDA_TANGAN, 
				   TANDA_TANGAN_ID, TANGGAL_UNDANGAN, SURAT_NAMA, SURAT_NIP, SURAT_PANGKAT, SURAT_JABATAN, DARI, 
				   SURAT_KETERANGAN_NAMA1, SURAT_KETERANGAN_NIP1, SURAT_KETERANGAN_PANGKAT1, SURAT_KETERANGAN_JABATAN1, SURAT_KETERANGAN_ALAMAT1, 
				   SURAT_KETERANGAN_NAMA2, SURAT_KETERANGAN_NIP2, SURAT_KETERANGAN_PANGKAT2, SURAT_KETERANGAN_JABATAN2, SURAT_KETERANGAN_ALAMAT2,
				   JUMLAH_LAMPIRAN, SATUAN_KERJA_ID_ASAL, SATUAN_KERJA_ID_PEMBUAT, 
				   SATUAN_KERJA_ID_POSISI, SATUAN_KERJA_ID_AKHIR, TANGGAL_AKSI, 
				   USER_ID, NAMA_USER, TANGGAL_ENTRI, 
				   TANGGAL_UPDATE, KODE_HAL_ID, NOMOR_GENERATE,
				   UNDANGAN_TANGGAL, UNDANGAN_JAM, UNDANGAN_TEMPAT, UNDANGAN_ACARA, ALAMAT, TEMBUSAN,
				   SURAT_REKOMENDASI_UNIT_KERJA1, SURAT_REKOMENDASI_UNIT_KERJA2, KUALIFIKASI_AKADEMIK, 
				   TEMPAT, TANGGAL_LAHIR, NO_INVOICE, JENIS_KELAMIN, NAMA_PERUSAHAAN, ALAMAT_PERUSAHAAN, NO_TELP,
				   URUT, TANGGAL_AWAL, TANGGAL_AKHIR, PELANGGARAN, KOMITMEN, LOKASI, MENIMBANG, MENGINGAT, MENETAPKAN, PERTIMBANGAN, DASAR,
				   TANGGAL_TRANSFER, NOMOR_CEK_BG, NOMOR_REKENING, BANK, JENIS, USER_PEMOHON_ID, SURAT_MASUK_ID, SURAT_BPPNFI_BACKDATE, NOMOR_BACKDATE,
				   LAST_CREATE_USER, LAST_CREATE_DATE) 
		   SELECT ".$this->getField("SURAT_BPPNFI_ID").", TIPE, '".$this->getField("NO_AGENDA")."', '".$this->getField("NOMOR")."', CURRENT_DATE, PERIHAL, ISI, KEPADA, TANGGAL_TANDA_TANGAN, 
				   TANDA_TANGAN_ID, TANGGAL_UNDANGAN, SURAT_NAMA, SURAT_NIP, SURAT_PANGKAT, SURAT_JABATAN, DARI, 
				   SURAT_KETERANGAN_NAMA1, SURAT_KETERANGAN_NIP1, SURAT_KETERANGAN_PANGKAT1, SURAT_KETERANGAN_JABATAN1, SURAT_KETERANGAN_ALAMAT1, 
				   SURAT_KETERANGAN_NAMA2, SURAT_KETERANGAN_NIP2, SURAT_KETERANGAN_PANGKAT2, SURAT_KETERANGAN_JABATAN2, SURAT_KETERANGAN_ALAMAT2,
				   JUMLAH_LAMPIRAN, SATUAN_KERJA_ID_ASAL, SATUAN_KERJA_ID_PEMBUAT, 
				   SATUAN_KERJA_ID_POSISI, SATUAN_KERJA_ID_AKHIR, TANGGAL_AKSI, 
				   USER_ID, NAMA_USER, TANGGAL_ENTRI, 
				   TANGGAL_UPDATE, KODE_HAL_ID, '".$this->getField("NOMOR_GENERATE")."',
				   UNDANGAN_TANGGAL, UNDANGAN_JAM, UNDANGAN_TEMPAT, UNDANGAN_ACARA, ALAMAT, TEMBUSAN,
				   SURAT_REKOMENDASI_UNIT_KERJA1, SURAT_REKOMENDASI_UNIT_KERJA2, KUALIFIKASI_AKADEMIK, 
				   TEMPAT, TANGGAL_LAHIR, NO_INVOICE, JENIS_KELAMIN, NAMA_PERUSAHAAN, ALAMAT_PERUSAHAAN, NO_TELP,
				   URUT, TANGGAL_AWAL, TANGGAL_AKHIR, PELANGGARAN, KOMITMEN, LOKASI, MENIMBANG, MENGINGAT, MENETAPKAN, PERTIMBANGAN, DASAR,
				   TANGGAL_TRANSFER, NOMOR_CEK_BG, NOMOR_REKENING, BANK, JENIS, USER_PEMOHON_ID, SURAT_MASUK_ID, SURAT_BPPNFI_BACKDATE, NOMOR_BACKDATE,
				   '".$this->getField("LAST_CREATE_USER")."', CURRENT_DATE
			FROM SURAT_BPPNFI 
			WHERE SURAT_BPPNFI_ID = ".$this->getField("SURAT_BPPNFI_ID_DATA")."";
				
		$this->query = $str;
		$this->id = $this->getField("SURAT_BPPNFI_ID");
		//echo $str;
		return $this->execQuery($str);
    }
	
	function updateDyna()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "UPDATE SURAT_BPPNFI A SET
				  ".$this->getField("FIELD")." = '".$this->getField("FIELD_VALUE")."'
				WHERE SURAT_BPPNFI_ID = ".$this->getField("SURAT_BPPNFI_ID")."
				"; 
				$this->query = $str;
		//echo $str;
		return $this->execQuery($str);
    }
	
	function updateIsi()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "
				UPDATE SURAT_BPPNFI
				SET    
					ISI					= '".$this->getField("ISI")."',
				  	LAST_UPDATE_USER	= '".$this->getField("LAST_UPDATE_USER")."',
				  	LAST_UPDATE_DATE	= CURRENT_DATE
				WHERE  SURAT_BPPNFI_ID 	= ".$this->getField("SURAT_BPPNFI_ID")."
				"; 
				$this->query = $str;
		return $this->execQuery($str);
    }
	
	function updateIsiTemp()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "
				UPDATE SURAT_BPPNFI
				SET    
					   ISI_TEMP= '".$this->getField("ISI_TEMP")."'
				WHERE  SURAT_BPPNFI_ID = ".$this->getField("SURAT_BPPNFI_ID")."
				"; 
				$this->query = $str;
		return $this->execQuery($str);
    }
	
	function updatePerevisiStatusParaf()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "
				UPDATE SURAT_BPPNFI
				SET    
					   USER_REVISI_STATUS= '1',
					   PEREVISI_STATUS= '".$this->getField("PEREVISI_STATUS")."'
				WHERE  SURAT_BPPNFI_ID = ".$this->getField("SURAT_BPPNFI_ID")."
				"; 
				$this->query = $str;
		return $this->execQuery($str);
    }
	
	function updatePerevisiStatus()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "
				UPDATE SURAT_BPPNFI
				SET    
					PEREVISI_STATUS		= '".$this->getField("PEREVISI_STATUS")."',
				  	LAST_UPDATE_USER	= '".$this->getField("LAST_UPDATE_USER")."',
				  	LAST_UPDATE_DATE	= CURRENT_DATE
				WHERE  SURAT_BPPNFI_ID 	= ".$this->getField("SURAT_BPPNFI_ID")."
				"; 
				$this->query = $str;
		return $this->execQuery($str);
    }
	
	function updateFinal()
	{
        $str = "UPDATE SURAT_BPPNFI 
				SET 
					SURAT_BPPNFI_FINAL = CURRENT_DATE, 
					SURAT_BPPNFI_STATUS = 'FINAL' 
				WHERE SURAT_BPPNFI_ID = '".$this->getField("SURAT_BPPNFI_ID")."'"; 
		$this->query = $str;
        return $this->execQuery($str);
    }
	
	function updateSatuanKerja()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "
				UPDATE SURAT_BPPNFI
				SET    
					   SATUAN_KERJA_ID_ASAL		= '".$this->getField("SATUAN_KERJA_ID_ASAL")."',
					   SATUAN_KERJA_ID_POSISI	= '".$this->getField("SATUAN_KERJA_ID_POSISI")."',
				  	   LAST_UPDATE_USER			= '".$this->getField("LAST_UPDATE_USER")."',
				  	   LAST_UPDATE_DATE			= CURRENT_DATE
				WHERE  SURAT_BPPNFI_ID 			= ".$this->getField("SURAT_BPPNFI_ID")."
				"; 
				$this->query = $str;
		return $this->execQuery($str);
    }
	
	function updatePerevisiStatusFinal()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "
				UPDATE SURAT_BPPNFI
				SET
				       TANGGAL_TANDA_TANGAN     = ".$this->getField("TANGGAL_TANDA_TANGAN").",   
					   SATUAN_KERJA_ID_AKHIR	= '".$this->getField("SATUAN_KERJA_ID_AKHIR")."',
					   PEREVISI_STATUS			= '".$this->getField("PEREVISI_STATUS")."',
				  	   LAST_UPDATE_USER			= '".$this->getField("LAST_UPDATE_USER")."',
				  	   LAST_UPDATE_DATE			= CURRENT_DATE
				WHERE  SURAT_BPPNFI_ID 			= ".$this->getField("SURAT_BPPNFI_ID")."
				"; 
				$this->query = $str;
		return $this->execQuery($str);
    }
	
	function updateDinamis()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "
				UPDATE ".$this->getField("TABLE")."
				SET    
					   ".$this->getField("FIELD")." = ".$this->getField("FIELD_VALUE")."
				WHERE  ".$this->getField("FIELD_ID")." = ".$this->getField("FIELD_ID_VALUE")."
				"; 
				$this->query = $str;
		return $this->execQuery($str);
    }
	
	function insertNomorSurat()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("SURAT_BPPNFI_ID", $this->getNextId("SURAT_BPPNFI_ID","SURAT_BPPNFI")); 
		$str = "
			INSERT INTO SURAT_BPPNFI (
			   SURAT_BPPNFI_ID, NOMOR, TANGGAL, PERIHAL, USER_ID, PEREVISI_STATUS) 
		   VALUES (
		   			'".$this->getField("SURAT_BPPNFI_ID")."', 
		   			'".$this->getField("NOMOR")."',
					".$this->getField("TANGGAL").",
					'".$this->getField("PERIHAL")."',
					".$this->getField("USER_ID").",
					'1'
				)";
				
		$this->query = $str;
		$this->reqSuratBppnfiId = $this->getField("SURAT_BPPNFI_ID");
		//echo $str;
		return $this->execQuery($str);
    }
	
	function updateNomorSurat()
	{
		//Auto-generate primary key(s) by next max value (integer)
		$str = "
		UPDATE SURAT_BPPNFI SET
					NOMOR= '".$this->getField("NOMOR")."',
					TANGGAL= ".$this->getField("TANGGAL").",
					PERIHAL= '".$this->getField("PERIHAL")."',
					USER_ID= ".$this->getField("USER_ID")."
		   WHERE SURAT_BPPNFI_ID = '".$this->getField("SURAT_BPPNFI_ID")."'
				"; 
				//USER_ID= ".$this->getField("USER_ID").", NAMA_USER= '".$this->getField("NAMA_USER")."',
				$this->query = $str;
		return $this->execQuery($str);
    }
	
	function updateNomorSuratKode()
	{
		//Auto-generate primary key(s) by next max value (integer)
		$str = "
		UPDATE SURAT_BPPNFI SET
					NOMOR= '".$this->getField("NOMOR")."',
					KODE_HAL_ID= '".$this->getField("KODE_HAL_ID")."',
					USER_ID= ".$this->getField("USER_ID")."
		   WHERE SURAT_BPPNFI_ID = '".$this->getField("SURAT_BPPNFI_ID")."'
				"; 
				//USER_ID= ".$this->getField("USER_ID").", NAMA_USER= '".$this->getField("NAMA_USER")."',
				$this->query = $str;
		return $this->execQuery($str);
    }
	
	function updatePemohon()
	{
		//Auto-generate primary key(s) by next max value (integer)
		$str = "
			UPDATE SURAT_BPPNFI SET
					USER_PEMOHON_ID		= ".$this->getField("USER_PEMOHON_ID").",
				  	LAST_UPDATE_USER	= '".$this->getField("LAST_UPDATE_USER")."',
				  	LAST_UPDATE_DATE	= CURRENT_DATE
		   WHERE SURAT_BPPNFI_ID 		= '".$this->getField("SURAT_BPPNFI_ID")."'
				"; 
				//USER_ID= ".$this->getField("USER_ID").", NAMA_USER= '".$this->getField("NAMA_USER")."',
				$this->query = $str;
		return $this->execQuery($str);
    }
	
	function update()
	{
		//Auto-generate primary key(s) by next max value (integer)
		$str = "
		UPDATE SURAT_BPPNFI 
		SET
					SATUAN_KERJA_ID_POSISI		= '".$this->getField("SATUAN_KERJA_ID_POSISI")."',
					TIPE						= '".$this->getField("TIPE")."',
					NO_AGENDA					= '".$this->getField("NO_AGENDA")."',
					NOMOR						= '".$this->getField("NOMOR")."',
					TANGGAL						= ".$this->getField("TANGGAL").",
					PERIHAL						= '".$this->getField("PERIHAL")."',
					ISI							= '".$this->getField("ISI")."',
					PEREVISI_INFO				= '".$this->getField("PEREVISI_INFO")."',
					KEPADA						= '".$this->getField("KEPADA")."',
					TANDA_TANGAN_ID				= ".$this->getField("TANDA_TANGAN_ID").",
					TANGGAL_UNDANGAN			= ".$this->getField("TANGGAL_UNDANGAN").",
					SURAT_NAMA					= '".$this->getField("SURAT_NAMA")."',
					SURAT_NIP					= '".$this->getField("SURAT_NIP")."',
					SURAT_PANGKAT				= '".$this->getField("SURAT_PANGKAT")."',
					SURAT_JABATAN				= '".$this->getField("SURAT_JABATAN")."',
					DARI						= '".$this->getField("DARI")."',
					JENIS						= '".$this->getField("JENIS")."',
					SURAT_KETERANGAN_NAMA1		= '".$this->getField("SURAT_KETERANGAN_NAMA1")."',
					SURAT_KETERANGAN_NIP1		= '".$this->getField("SURAT_KETERANGAN_NIP1")."',
		   			SURAT_KETERANGAN_PANGKAT1	= '".$this->getField("SURAT_KETERANGAN_PANGKAT1")."',
					SURAT_KETERANGAN_JABATAN1	= '".$this->getField("SURAT_KETERANGAN_JABATAN1")."',
					SURAT_KETERANGAN_ALAMAT1	= '".$this->getField("SURAT_KETERANGAN_ALAMAT1")."',
					SURAT_KETERANGAN_NAMA2		= '".$this->getField("SURAT_KETERANGAN_NAMA2")."',
					SURAT_KETERANGAN_NIP2		= '".$this->getField("SURAT_KETERANGAN_NIP2")."',
					SURAT_KETERANGAN_PANGKAT2	= '".$this->getField("SURAT_KETERANGAN_PANGKAT2")."',
					SURAT_KETERANGAN_JABATAN2	= '".$this->getField("SURAT_KETERANGAN_JABATAN2")."',
					SURAT_KETERANGAN_ALAMAT2	= '".$this->getField("SURAT_KETERANGAN_ALAMAT2")."',
					TANGGAL_UPDATE				= ".$this->getField("TANGGAL_UPDATE").",
					KODE_HAL_ID					= '".$this->getField("KODE_HAL_ID")."',
					UNDANGAN_TANGGAL			= ".$this->getField("UNDANGAN_TANGGAL").",
					UNDANGAN_JAM				= '".$this->getField("UNDANGAN_JAM")."',
					UNDANGAN_TEMPAT				= '".$this->getField("UNDANGAN_TEMPAT")."',
					UNDANGAN_ACARA				= '".$this->getField("UNDANGAN_ACARA")."',
					ALAMAT						= '".$this->getField("ALAMAT")."',
					TEMBUSAN					= '".$this->getField("TEMBUSAN")."',
					SURAT_REKOMENDASI_UNIT_KERJA1= '".$this->getField("SURAT_REKOMENDASI_UNIT_KERJA1")."',
					SURAT_REKOMENDASI_UNIT_KERJA2= '".$this->getField("SURAT_REKOMENDASI_UNIT_KERJA2")."',
					KUALIFIKASI_AKADEMIK		= '".$this->getField("KUALIFIKASI_AKADEMIK")."',
					TEMPAT						= '".$this->getField("TEMPAT")."',
					TANGGAL_LAHIR				= ".$this->getField("TANGGAL_LAHIR").",
					NO_INVOICE					= '".$this->getField("NO_INVOICE")."',
					JENIS_KELAMIN				= '".$this->getField("JENIS_KELAMIN")."',
					NAMA_PERUSAHAAN				= '".$this->getField("NAMA_PERUSAHAAN")."',
					ALAMAT_PERUSAHAAN			= '".$this->getField("ALAMAT_PERUSAHAAN")."',
					NO_TELP						= '".$this->getField("NO_TELP")."',
					URUT  						= '".$this->getField("URUT")."',
					TANGGAL_AWAL				= ".$this->getField("TANGGAL_AWAL").",
					TANGGAL_AKHIR				= ".$this->getField("TANGGAL_AKHIR").",
					PELANGGARAN					= '".$this->getField("PELANGGARAN")."',
					KOMITMEN					= '".$this->getField("KOMITMEN")."',
					LOKASI						= '".$this->getField("LOKASI")."',
					MENIMBANG					= '".$this->getField("MENIMBANG")."',
					MENGINGAT					= '".$this->getField("MENGINGAT")."',
					MENETAPKAN					= '".$this->getField("MENETAPKAN")."',
					PERTIMBANGAN				= '".$this->getField("PERTIMBANGAN")."',
					DASAR						= '".$this->getField("DASAR")."',
					TANGGAL_TRANSFER 			= ".$this->getField("TANGGAL_TRANSFER").",
					NOMOR_CEK_BG				= '".$this->getField("NOMOR_CEK_BG")."',
					NOMOR_REKENING				= '".$this->getField("NOMOR_REKENING")."',
					BANK						= '".$this->getField("BANK")."',
				  	SURAT_MASUK_ID				= ".$this->getField("SURAT_MASUK_ID").",
				  	SATUAN_KERJA_ID_TUJUAN		= '".$this->getField("SATUAN_KERJA_ID_TUJUAN").";',
				  	LAST_UPDATE_USER			= '".$this->getField("LAST_UPDATE_USER")."',
				  	LAST_UPDATE_DATE			= CURRENT_DATE
		   WHERE SURAT_BPPNFI_ID 				= '".$this->getField("SURAT_BPPNFI_ID")."'
				"; 
				//USER_ID= ".$this->getField("USER_ID").", NAMA_USER= '".$this->getField("SATUAN_KERJA_ID_TUJUAN")."',
				$this->query = $str;
				// echo $str; 
		return $this->execQuery($str);
    }
	
	function updatePerevisi()
	{
		//Auto-generate primary key(s) by next max value (integer)
		$str = "
		UPDATE SURAT_BPPNFI SET
					USER_REVISI_ID= ".$this->getField("USER_REVISI_ID")."
		   WHERE SURAT_BPPNFI_ID = '".$this->getField("SURAT_BPPNFI_ID")."'
				"; 
				//USER_ID= ".$this->getField("USER_ID").", NAMA_USER= '".$this->getField("NAMA_USER")."',
				$this->query = $str;
		return $this->execQuery($str);
    }
	
	function updateRevisi()
	{
		//Auto-generate primary key(s) by next max value (integer)
		$str = "
		UPDATE SURAT_BPPNFI SET
					PEREVISI_STATUS	= '".$this->getField("PEREVISI_STATUS")."',
					PEREVISI_INFO	= '".$this->getField("PEREVISI_INFO")."',
				  	LAST_UPDATE_USER	= '".$this->getField("LAST_UPDATE_USER")."',
				  	LAST_UPDATE_DATE	= CURRENT_DATE
		   WHERE SURAT_BPPNFI_ID 	= '".$this->getField("SURAT_BPPNFI_ID")."'
				"; 
				//USER_ID= ".$this->getField("USER_ID").", NAMA_USER= '".$this->getField("NAMA_USER")."',
				$this->query = $str;
		return $this->execQuery($str);
    }
	
	function updateInfo()
	{
		//Auto-generate primary key(s) by next max value (integer)
		$str = "
		   UPDATE SURAT_BPPNFI 
		   SET
					PEREVISI_INFO	= NULL,
				  	LAST_UPDATE_USER	= '".$this->getField("LAST_UPDATE_USER")."',
				  	LAST_UPDATE_DATE	= CURRENT_DATE
		   WHERE SURAT_BPPNFI_ID 	= '".$this->getField("SURAT_BPPNFI_ID")."'
				"; 
				//USER_ID= ".$this->getField("USER_ID").", NAMA_USER= '".$this->getField("NAMA_USER")."',
				$this->query = $str;
		return $this->execQuery($str);
    }
	
	function updateDiambil()
	{
		//Auto-generate primary key(s) by next max value (integer)
		$str = "
		UPDATE SURAT_BPPNFI SET
					TANGGAL_DIAMBIL= ".$this->getField("TANGGAL_DIAMBIL").",
					DIAMBIL= '".$this->getField("DIAMBIL")."'
		   WHERE SURAT_BPPNFI_ID = '".$this->getField("SURAT_BPPNFI_ID")."'
				"; 
				//USER_ID= ".$this->getField("USER_ID").", NAMA_USER= '".$this->getField("NAMA_USER")."',
				$this->query = $str;
		return $this->execQuery($str);
    }
	
	function deleteBak()
	{	
		$str1 = "
		 		DELETE FROM SURAT_BPPNFI
                WHERE 
                  SURAT_BPPNFI_ID = '".$this->getField("SURAT_BPPNFI_ID")."'";
				  
		$this->query = $str1;
        return $this->execQuery($str1);
    }
	
	function delete()
	{
        $str = "UPDATE SURAT_BPPNFI 
			    SET 
					SURAT_BPPNFI_DELETE = CURRENT_DATE, 
					SURAT_BPPNFI_STATUS = 'DELETE' 
				WHERE
                	SURAT_BPPNFI_ID = '".$this->getField("SURAT_BPPNFI_ID")."'"; 
		$this->query = $str;
        return $this->execQuery($str);
    }
	
	function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement="", $sOrder="")
	{
		$str = "
				SELECT 
				   A.SURAT_BPPNFI_ID, A.SURAT_MASUK_ID, TIPE, A.KODE_HAL_ID, E.KODE KODE_HAL, E.NAMA KODE_HAL_NAMA,
				   UNDANGAN_TANGGAL, UNDANGAN_JAM, UNDANGAN_TEMPAT, UNDANGAN_ACARA, A.ALAMAT, NOMOR_GENERATE, SURAT_BPPNFI_BACKDATE, NOMOR_BACKDATE,
				   CASE 
					WHEN TIPE = 1 THEN 'Surat Keluar' 
					WHEN TIPE = 2 THEN 'Pengumuman' 
					WHEN TIPE = 3 THEN 'Surat Kuasa' 
					WHEN TIPE = 4 THEN 'Surat Perintah Kerja' 
					WHEN TIPE = 5 THEN 'Guarantee Letter' 
					WHEN TIPE = 6 THEN 'Surat Keterangan'
					WHEN TIPE = 7 THEN 'Surat Pernyataan'
					WHEN TIPE = 8 THEN 'Berita Acara'
					WHEN TIPE = 9 THEN 'Nota Kesepahaman (MOU)'
					WHEN TIPE = 10 THEN 'Perjanjian Pengadaan'
					WHEN TIPE = 51 THEN 'NDA'
					WHEN TIPE = 52 THEN 'Perjanjian Kerjasama'
					WHEN TIPE = 11 THEN 'Memo'
					WHEN TIPE = 12 THEN 'Pengumuman'
					WHEN TIPE = 13 THEN 'Surat Tugas'
					WHEN TIPE = 14 THEN 'Surat Peringatan'
					WHEN TIPE = 15 THEN 'Surat Pengantar'
					WHEN TIPE = 16 THEN 'Surat Perintah'
					WHEN TIPE = 17 THEN 'Surat Instruksi'
					WHEN TIPE = 19 THEN 'Surat Keputusan'
					WHEN TIPE = 20 THEN 'Surat Kuasa'
					WHEN TIPE = 21 THEN 'Voucher'
					WHEN TIPE = 22 THEN 'Memo'
					ELSE ''
					END TIPE_NAMA,
					CASE WHEN TIPE IS NOT NULL THEN A.PERIHAL ELSE '' END PERIHAL_INFO, 
					EXTRACT(MONTH FROM TANGGAL_AKHIR) - EXTRACT(MONTH FROM TANGGAL_AWAL) MASA_BERLAKU,
				   A.NO_AGENDA, A.NOMOR, A.TANGGAL, A.PERIHAL, A.ISI, A.KEPADA, TANGGAL_TANDA_TANGAN, 
				   TANGGAL_TRANSFER, NOMOR_CEK_BG, NOMOR_REKENING, BANK, A.JENIS,
				   A.TANDA_TANGAN_ID, CASE C.STATUS WHEN 1 THEN '' ELSE 'a.n. ' END STATUS_TANDA_TANGAN_INFO, C.STATUS STATUS_TANDA_TANGAN, 
                   C.USER_TANDA_TANGAN_ID, C.JABATAN JABATAN_TANDA_TANGAN, C.JABATAN_ENG JABATAN_TANDA_TANGAN_ENG, C.NIP NIP_JABATAN, C.NAMA TANDA_TANGAN, TANGGAL_UNDANGAN, SURAT_NAMA, SURAT_NIP, SURAT_PANGKAT, SURAT_JABATAN,
				   DARI, SURAT_KETERANGAN_NAMA1, SURAT_KETERANGAN_NIP1, SURAT_KETERANGAN_PANGKAT1, SURAT_KETERANGAN_JABATAN1, SURAT_KETERANGAN_NAMA2, 
				   SURAT_KETERANGAN_NIP2, SURAT_KETERANGAN_PANGKAT2, SURAT_KETERANGAN_JABATAN2, SURAT_KETERANGAN_ALAMAT1, SURAT_KETERANGAN_ALAMAT2,
				   A.JUMLAH_LAMPIRAN, A.SATUAN_KERJA_ID_ASAL, AMBIL_SATUAN_KERJA(A.SATUAN_KERJA_ID_ASAL) SATUAN_KERJA_ASAL_INFO, SATUAN_KERJA_ID_PEMBUAT, CASE WHEN JENIS_KELAMIN = 'L' THEN 'Laki - Laki' WHEN JENIS_KELAMIN = 'P' THEN 'Perempuan' END JENIS_KELAMIN_INFO,
				   SATUAN_KERJA_ID_POSISI, SATUAN_KERJA_ID_AKHIR, TANGGAL_AKSI, URUT, CASE URUT WHEN '1' THEN 'PERTAMA' WHEN '2' THEN 'KEDUA' WHEN '3' THEN 'KETIGA' END URUT_INFO, TANGGAL_AWAL, TANGGAL_AKHIR, PELANGGARAN, KOMITMEN,
				   A.USER_ID, D.NAMA NAMA_USER, A.TANGGAL_ENTRI, NAMA_PERUSAHAAN, ALAMAT_PERUSAHAAN, NO_TELP, LOKASI,
				   A.TANGGAL_UPDATE, A.TEMBUSAN, SURAT_REKOMENDASI_UNIT_KERJA1, SURAT_REKOMENDASI_UNIT_KERJA2, KUALIFIKASI_AKADEMIK, TANGGAL_LAHIR, TEMPAT
				   , TO_CHAR(A.TANGGAL, 'YYYY') TAHUN_SURAT, NO_INVOICE, JENIS_KELAMIN, NO_TELP, NAMA_PERUSAHAAN, ALAMAT_PERUSAHAAN
				   , A.USER_REVISI_ID, AMBIL_USER_NAMA(A.USER_REVISI_ID) USER_REVISI_INFO, MENGINGAT, MENIMBANG, MENETAPKAN, SURAT_BPPNFI_STATUS
				   , A.USER_PEMOHON_ID, AMBIL_USER_NAMA(A.USER_PEMOHON_ID) USER_PEMOHON_INFO, A.PEREVISI_STATUS, A.PEREVISI_INFO, PERTIMBANGAN, DASAR
				   , CASE A.PEREVISI_STATUS WHEN '1' THEN 'Kirim' WHEN '3' THEN 'Revisi Atasan' WHEN '5' THEN 'Kirim Direktur' WHEN '7' THEN 'Revisi Direktur' WHEN '9' THEN 'Finalisasi' ELSE 'Draft' END PEREVISI_STATUS_INFO
				   , A.USER_REVISI_STATUS, CASE A.USER_REVISI_STATUS WHEN '1' THEN 'Terparaf oleh ' || AMBIL_USER_NAMA(A.USER_REVISI_ID) ELSE 'belum terparaf' END USER_REVISI_STATUS_INFO, F.NOMOR NO_MASUK,
				   CASE WHEN SURAT_BPPNFI_FINAL IS NOT NULL THEN 'Sudah' ELSE 'Belum' END STATUS_ARSIP, AMBIL_SATUAN_KERJA(A.SATUAN_KERJA_ID_POSISI) SATUAN_KERJA_POSISI_INFO, AMBIL_SATUAN_KERJA(A.SATUAN_KERJA_ID_TUJUAN) SATUAN_KERJA_TUJUAN_INFO, A.SATUAN_KERJA_ID_TUJUAN
				FROM SURAT_BPPNFI A
				LEFT JOIN SURAT_PENGANTAR B ON A.SURAT_BPPNFI_ID = B.SURAT_BPPNFI_ID
				LEFT JOIN TANDA_TANGAN C ON A.TANDA_TANGAN_ID = C.TANDA_TANGAN_ID
				LEFT JOIN users D ON A.USER_ID = D.USER_ID
				LEFT JOIN KODE_HAL E ON A.KODE_HAL_ID = E.KODE_HAL_ID
				LEFT JOIN SURAT_MASUK F ON A.SURAT_MASUK_ID = F.SURAT_MASUK_ID
				LEFT JOIN DISPOSISI G ON A.SURAT_BPPNFI_ID = G.SURAT_BPPNFI_ID
				WHERE 1=1 AND A.SURAT_BPPNFI_DELETE IS NULL
			   "; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= " ".$statement." ".$sOrder;
		$this->query = $str;
	
		return $this->selectLimit($str,$limit,$from); 
    }

	function selectByParamsMonitoring($paramsArray=array(),$limit=-1,$from=-1,$stat='',$order="")
	{
		$str = "SELECT
				UPPER (D.NAMA) PEMBUAT_SURAT,
				A .SURAT_BPPNFI_ID,
				A .NO_AGENDA,
				A .TERBACA TERBACA_TUJUAN,
				A .TAHUN,
				A .NOMOR,
				A .TANGGAL,
				A .TANGGAL_BATAS,
				A .JENIS,
				CASE 
				WHEN TIPE = 1 THEN 'Surat Keluar' 
				WHEN TIPE = 2 THEN 'Pengumuman' 
				WHEN TIPE = 3 THEN 'Surat Kuasa' 
				WHEN TIPE = 4 THEN 'Surat Perintah Kerja' 
				WHEN TIPE = 5 THEN 'Guarantee Letter' 
				WHEN TIPE = 6 THEN 'Surat Keterangan'
				WHEN TIPE = 7 THEN 'Surat Pernyataan'
				WHEN TIPE = 8 THEN 'Berita Acara'
				WHEN TIPE = 9 THEN 'Nota Kesepahaman (MOU)'
				WHEN TIPE = 10 THEN 'Perjanjian Pengadaan'
				WHEN TIPE = 51 THEN 'NDA'
				WHEN TIPE = 52 THEN 'Perjanjian Kerjasama'
				WHEN TIPE = 11 THEN 'Nota Dinas'
				WHEN TIPE = 12 THEN 'Surat Keterangan'
				WHEN TIPE = 13 THEN 'Surat Pernyataan'
				WHEN TIPE = 14 THEN 'Surat Edaran'
				WHEN TIPE = 15 THEN 'Surat Peringatan'
				WHEN TIPE = 16 THEN 'Surat Perintah'
				WHEN TIPE = 17 THEN 'Surat Instruksi'
				WHEN TIPE = 19 THEN 'Surat Keputusan'
				WHEN TIPE = 20 THEN 'Surat Kuasa'
				WHEN TIPE = 21 THEN 'Voucher'
				WHEN TIPE = 22 THEN 'Memo'
				ELSE ''
				END TIPE_NAMA,
				A .KEPADA,
				A .PERIHAL,
				G.NAMA INSTANSI_ASAL,
				A .SATUAN_KERJA_ID_TUJUAN,
				C .NAMA SATUAN_NAMA1,
				ambil_satuan_tujuan_bppnfi(a.surat_bppnfi_id) SATUAN_NAMA,
				A .ISI,
				TO_CHAR(
					A .TANGGAL_ENTRI,
					'YYYY-MM-DD HH24:MI'
				) TANGGAL_ENTRI,
				A .USER_ID,
				A .NAMA_USER,
				COALESCE (G .NAMA, A .INSTANSI_ASAL) ASAL,
				COALESCE (H.NAMA, C .NAMA) TUJUAN
			FROM
				SURAT_BPPNFI A
			LEFT JOIN surat_bppnfi_tujuan AA ON AA.surat_bppnfi_id = A.surat_bppnfi_id
			LEFT JOIN SATUAN_KERJA C ON AA.SATUAN_KERJA_ID_TUJUAN = C .SATUAN_KERJA_ID
			LEFT JOIN users D ON D.USER_ID = A .USER_ID
			LEFT JOIN (
				SELECT
					A .NAMA || '-' || B.NAMA NAMA,
					A .SATUAN_KERJA_ID,
					B.USER_ID
				FROM
					satuan_kerja A
				INNER JOIN users B ON A .SATUAN_KERJA_ID = B.SATUAN_KERJA_ID
				WHERE
					1 = 1
			) G ON G .SATUAN_KERJA_ID = A .SATUAN_KERJA_ID_ASAL
			LEFT JOIN (
				SELECT
					A .NAMA || '-' || B.NAMA NAMA1,
					A .NAMA,
					A .SATUAN_KERJA_ID,
					B.USER_ID
				FROM
					satuan_kerja A
				INNER JOIN users B ON A .SATUAN_KERJA_ID = B.SATUAN_KERJA_ID
				WHERE
					1 = 1
				LIMIT 1
			) H ON H.SATUAN_KERJA_ID = AA .SATUAN_KERJA_ID_TUJUAN
			WHERE
				1 = 1
			AND A .SURAT_MASUK_DELETE IS NULL "; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= " ".$stat."  ".$order;
		$this->query = $str;
	
		return $this->selectLimit($str,$limit,$from); 
    }
	
	function selectByParamsCetak($paramsArray=array(),$limit=-1,$from=-1, $statement="", $sOrder="")
	{
		$str = "
				SELECT 
				   A.SURAT_BPPNFI_ID, A.SURAT_MASUK_ID, TIPE, A.KODE_HAL_ID, E.KODE KODE_HAL, E.NAMA KODE_HAL_NAMA,
				   UNDANGAN_TANGGAL, UNDANGAN_JAM, UNDANGAN_TEMPAT, UNDANGAN_ACARA, A.ALAMAT, NOMOR_GENERATE, SURAT_BPPNFI_BACKDATE, NOMOR_BACKDATE,
				   CASE 
					WHEN TIPE = 1 THEN 'Surat Keluar' 
					WHEN TIPE = 2 THEN 'Pengumuman' 
					WHEN TIPE = 3 THEN 'Surat Kuasa' 
					WHEN TIPE = 4 THEN 'Surat Perintah Kerja' 
					WHEN TIPE = 5 THEN 'Guarantee Letter' 
					WHEN TIPE = 6 THEN 'Surat Keterangan'
					WHEN TIPE = 7 THEN 'Surat Pernyataan'
					WHEN TIPE = 8 THEN 'Berita Acara'
					WHEN TIPE = 11 THEN 'Nota Dinas'
					WHEN TIPE = 12 THEN 'Surat Keterangan'
					WHEN TIPE = 13 THEN 'Surat Pernyataan'
					WHEN TIPE = 14 THEN 'Surat Edaran'
					WHEN TIPE = 15 THEN 'Surat Peringatan'
					WHEN TIPE = 16 THEN 'Surat Perintah'
					WHEN TIPE = 17 THEN 'Surat Instruksi'
					WHEN TIPE = 19 THEN 'Surat Keputusan'
					WHEN TIPE = 20 THEN 'Surat Kuasa'
					WHEN TIPE = 21 THEN 'Voucher'
					WHEN TIPE = 22 THEN 'Memo'
					ELSE ''
					END TIPE_NAMA,
					CASE WHEN TIPE IS NOT NULL THEN A.PERIHAL ELSE '' END PERIHAL_INFO, 
					EXTRACT(MONTH FROM TANGGAL_AKHIR) - EXTRACT(MONTH FROM TANGGAL_AWAL) MASA_BERLAKU,
				   A.NO_AGENDA, A.NOMOR, A.TANGGAL, A.PERIHAL, A.ISI, A.KEPADA, TANGGAL_TANDA_TANGAN, 
				   TANGGAL_TRANSFER, NOMOR_CEK_BG, NOMOR_REKENING, BANK, A.JENIS,
				   A.TANDA_TANGAN_ID, CASE C.STATUS WHEN 1 THEN '' ELSE 'a.n. ' END STATUS_TANDA_TANGAN_INFO, C.STATUS STATUS_TANDA_TANGAN, C.LINK_GAMBAR LINK_TANDA_TANGAN,
                   C.USER_TANDA_TANGAN_ID, C.JABATAN JABATAN_TANDA_TANGAN, C.JABATAN_ENG JABATAN_TANDA_TANGAN_ENG, C.NIP NIP_JABATAN, C.NAMA TANDA_TANGAN, TANGGAL_UNDANGAN, SURAT_NAMA, SURAT_NIP, SURAT_PANGKAT, SURAT_JABATAN,
				   DARI, SURAT_KETERANGAN_NAMA1, SURAT_KETERANGAN_NIP1, SURAT_KETERANGAN_PANGKAT1, SURAT_KETERANGAN_JABATAN1, SURAT_KETERANGAN_NAMA2, 
				   SURAT_KETERANGAN_NIP2, SURAT_KETERANGAN_PANGKAT2, SURAT_KETERANGAN_JABATAN2, SURAT_KETERANGAN_ALAMAT1, SURAT_KETERANGAN_ALAMAT2,
				   A.JUMLAH_LAMPIRAN, A.SATUAN_KERJA_ID_ASAL, SATUAN_KERJA_ID_PEMBUAT, CASE WHEN JENIS_KELAMIN = 'L' THEN 'Laki - Laki' WHEN JENIS_KELAMIN = 'P' THEN 'Perempuan' END JENIS_KELAMIN_INFO,
				   SATUAN_KERJA_ID_POSISI, SATUAN_KERJA_ID_AKHIR, TANGGAL_AKSI, URUT, CASE URUT WHEN '1' THEN 'PERTAMA' WHEN '2' THEN 'KEDUA' WHEN '3' THEN 'KETIGA' END URUT_INFO, TANGGAL_AWAL, TANGGAL_AKHIR, PELANGGARAN, KOMITMEN,
				   A.USER_ID, D.NAMA NAMA_USER, A.TANGGAL_ENTRI, NAMA_PERUSAHAAN, ALAMAT_PERUSAHAAN, NO_TELP, LOKASI,
				   A.TANGGAL_UPDATE, A.TEMBUSAN, SURAT_REKOMENDASI_UNIT_KERJA1, SURAT_REKOMENDASI_UNIT_KERJA2, KUALIFIKASI_AKADEMIK, TANGGAL_LAHIR, TEMPAT
				   , TO_CHAR(A.TANGGAL, 'YYYY') TAHUN_SURAT, NO_INVOICE, JENIS_KELAMIN, NO_TELP, NAMA_PERUSAHAAN, ALAMAT_PERUSAHAAN
				   , A.USER_REVISI_ID, AMBIL_USER_NAMA(A.USER_REVISI_ID) USER_REVISI_INFO, MENGINGAT, MENIMBANG, MENETAPKAN, SURAT_BPPNFI_STATUS
				   , A.USER_PEMOHON_ID, AMBIL_USER_NAMA(A.USER_PEMOHON_ID) USER_PEMOHON_INFO, A.PEREVISI_STATUS, A.PEREVISI_INFO, PERTIMBANGAN, DASAR
				   , CASE A.PEREVISI_STATUS WHEN '1' THEN 'Kirim' WHEN '3' THEN 'Revisi Atasan' WHEN '5' THEN 'Kirim Direktur' WHEN '7' THEN 'Revisi Direktur' WHEN '9' THEN 'Finalisasi' ELSE 'Draft' END PEREVISI_STATUS_INFO
				   , A.USER_REVISI_STATUS, CASE A.USER_REVISI_STATUS WHEN '1' THEN 'Terparaf oleh ' || AMBIL_USER_NAMA(A.USER_REVISI_ID) ELSE 'belum terparaf' END USER_REVISI_STATUS_INFO, F.NOMOR NO_MASUK,
				   CASE WHEN SURAT_BPPNFI_FINAL IS NOT NULL THEN 'Sudah' ELSE 'Belum' END STATUS_ARSIP
				FROM SURAT_BPPNFI A
				LEFT JOIN SURAT_PENGANTAR B ON A.SURAT_BPPNFI_ID = B.SURAT_BPPNFI_ID
				LEFT JOIN TANDA_TANGAN C ON A.TANDA_TANGAN_ID = C.TANDA_TANGAN_ID
				LEFT JOIN users D ON A.USER_ID = D.USER_ID
				LEFT JOIN KODE_HAL E ON A.KODE_HAL_ID = E.KODE_HAL_ID
				LEFT JOIN SURAT_MASUK F ON A.SURAT_MASUK_ID = F.SURAT_MASUK_ID
				WHERE 1=1 AND A.SURAT_BPPNFI_DELETE IS NULL
			   "; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= " ".$statement." ".$sOrder;
		$this->query = $str;
	
		return $this->selectLimit($str,$limit,$from); 
    }
	
	function selectByParamsNomorSurat($paramsArray=array(),$limit=-1,$from=-1, $statement="", $sOrder="")
	{
		$str = "
				SELECT 
				SURAT_BPPNFI_ID, TANGGAL, PERIHAL, NOMOR_SURAT, NOMOR_SURAT_LAIN, 
                CASE WHEN NOMOR_SURAT_LAIN IS NULL THEN NOMOR_SURAT ELSE NOMOR_SURAT || NOMOR_SURAT_LAIN END NOMOR_SURAT_MODIF, 
                TANGGAL_DIAMBIL, DIAMBIL, PEMROSES_DOKUMEN, KODE_HAL_ID, KODE, KODE_NAMA, TIPE, USER_PEMOHON_ID
                FROM
                (
					SELECT
					A.SURAT_BPPNFI_ID, TANGGAL, PERIHAL, TANGGAL_DIAMBIL, DIAMBIL, D.NAMA PEMROSES_DOKUMEN, E.KODE_HAL_ID, E.KODE, E.NAMA KODE_NAMA,
					CASE WHEN POSITION('/' IN NOMOR) > 0 THEN SUBSTR(NOMOR, 1, POSITION('/' in NOMOR)-1) ELSE NOMOR END NOMOR_SURAT,
					CASE WHEN POSITION('/' IN NOMOR) > 0 THEN SUBSTR(NOMOR, 1, POSITION('/' in NOMOR)-1) ELSE '' END NOMOR_SURAT_LAIN
					, A.TIPE, A.USER_PEMOHON_ID
					FROM SURAT_BPPNFI A
					LEFT JOIN SURAT_PENGANTAR B ON A.SURAT_BPPNFI_ID = B.SURAT_BPPNFI_ID
					LEFT JOIN TANDA_TANGAN C ON A.TANDA_TANGAN_ID = C.TANDA_TANGAN_ID
					LEFT JOIN users D ON A.USER_ID = D.USER_ID
					LEFT JOIN KODE_HAL E ON A.KODE_HAL_ID = E.KODE_HAL_ID
					WHERE 1=1 AND A.SURAT_BPPNFI_DELETE IS NULL
				)A
				WHERE 1=1
			   "; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$sOrder;
		$this->query = $str;
	
		return $this->selectLimit($str,$limit,$from); 
    }

    function selectByParamsTree($paramsArray=array(),$limit=-1,$from=-1,$statement="", $order="")
	{
		$str = "SELECT disposisi_id_generate('0', SURAT_BPPNFI_ID) TREE_ID, '0' TREE_PARENT_ID
					FROM SURAT_BPPNFI_ID a
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

     function selectByParamsUser($paramsArray=array(),$limit=-1,$from=-1, $statement="", $id="", $order="")
	{
		$str = "
				  SELECT DISTINCT
									CASE
									WHEN C.STATUS_KEMBALI = 1 AND C.SATUAN_KERJA_ID_TUJUAN = '".$id."' AND COALESCE (C.TERDISPOSISI, 0) = 0 THEN CASE COALESCE(C.TERBACA,0) WHEN 0 THEN 'belum di baca' ELSE 'telah di baca' END
									WHEN A.SATUAN_KERJA_ID_TUJUAN = '".$id."' AND COALESCE (A.TERDISPOSISI, 0) = 0 THEN CASE COALESCE(A.TERBACA,0) WHEN 0 THEN 'belum di baca' ELSE 'telah di baca' END
									WHEN A.SATUAN_KERJA_ID_TUJUAN = '".$id."' AND COALESCE (A.TERDISPOSISI, 0) = 1 THEN 'terdisposisi'
									WHEN C.SATUAN_KERJA_ID_TUJUAN = '".$id."' AND COALESCE (C.TERDISPOSISI, 0) = 1 THEN 'terdisposisi'
									WHEN A.SATUAN_KERJA_ID_TUJUAN = '".$id."' THEN 'Surat Masuk'
									ELSE 'Disposisi'
								 END STATUS_SURAT_INFO,
									C.STATUS_KEMBALI, C.SATUAN_KERJA_ID_ASAL, C.SATUAN_KERJA_ID_TUJUAN, B.SATUAN_KERJA_ID, G.NAMA ASAL, H.NAMA TUJUAN,
									A.SURAT_BPPNFI_ID, A.TAHUN, A.NO_AGENDA, A.NOMOR, 
									CASE WHEN C.STATUS_KEMBALI = 1 THEN 'Disposisi' WHEN A.SATUAN_KERJA_ID_TUJUAN = '".$id."' THEN 'Surat Masuk' ELSE 'Disposisi' END JENIS_SURAT,
									A.TANGGAL, TO_CHAR(C.TANGGAL_DISPOSISI, 'YYYY-MM-DD HH24:MI') TANGGAL_DISPOSISI,
									CASE WHEN C.STATUS_KEMBALI = 1 THEN 1 WHEN C.SATUAN_KERJA_ID_ASAL = '".$id."' THEN 1 ELSE 0 END STATUS_DISPOSISI,
									CASE 
									WHEN C.STATUS_KEMBALI = 1 THEN COALESCE (C.TERDISPOSISI, 0)
									WHEN A.SATUAN_KERJA_ID_TUJUAN = '".$id."' THEN
										(SELECT COALESCE (X.TERDISPOSISI, 0) FROM surat_bppnfi X WHERE X.SURAT_BPPNFI_ID=A.SURAT_BPPNFI_ID) 
									ELSE 
										(SELECT COALESCE (X.TERDISPOSISI, 0) FROM DISPOSISI X WHERE X.SATUAN_KERJA_ID_TUJUAN = '".$id."' AND X.SURAT_BPPNFI_ID=A.SURAT_BPPNFI_ID  LIMIT 1) 
									END
									N_DISPOSISI, 
									CASE 
									WHEN C.SATUAN_KERJA_ID_TUJUAN = '".$id."' AND COALESCE (C.TERDISPOSISI, 0) = 0
									THEN 
									CASE COALESCE (C.TERBACA, 0) WHEN 0 THEN 1 ELSE 0 END
									WHEN A.SATUAN_KERJA_ID_TUJUAN = '".$id."' AND COALESCE (A.TERDISPOSISI, 0) = 0
									THEN CASE COALESCE (A.TERBACA, 0) WHEN 0 THEN 0 ELSE 1 END
									END BACA_DISPOSISI,
									CASE 
									WHEN C.STATUS_KEMBALI = 1 THEN 1
									WHEN A.SATUAN_KERJA_ID_TUJUAN = '".$id."' THEN 0 ELSE 1 END BACA_DISPOSISIBAK, 
									CASE 
									WHEN C.STATUS_KEMBALI = 1 THEN COALESCE (C.TERBACA, 0) 
									WHEN A.SATUAN_KERJA_ID_TUJUAN = '".$id."' THEN COALESCE (A.TERBACA, 0)
									ELSE  COALESCE (C.TERBACA, 0)
									END N_BACA,
									CASE 
									WHEN C.STATUS_KEMBALI = 1 THEN COALESCE (C.TERBACA, 0) 
									WHEN A.SATUAN_KERJA_ID_TUJUAN = '".$id."' THEN COALESCE (A.TERBACA, 0)
									ELSE  COALESCE (C.TERBACA, 0)
									END STATUS_BACA,
									null STATUS_BALAS, A.PERIHAL,
									BB.NAMA INSTANSI_ASAL, ambil_satuan_tujuan_bppnfi(a.surat_bppnfi_id) SATUAN_KERJA_TUJUAN, 
									(SELECT COUNT(FEEDBACK_ID) FROM FEEDBACK X WHERE X.SURAT_BPPNFI_ID = A.SURAT_BPPNFI_ID) FEEDBACK,
								CASE WHEN C.TANGGAL_BATAS IS NULL THEN 
										CASE WHEN TO_CHAR(A.TANGGAL_BATAS,'YYYY-MM-DD') < TO_CHAR(CURRENT_DATE,'YYYY-MM-DD') AND COALESCE(A.TERBACA, 0) = 0 THEN 0 
										WHEN TO_CHAR(A.TANGGAL_BATAS,'YYYY-MM-DD') >= TO_CHAR(CURRENT_DATE,'YYYY-MM-DD') AND COALESCE(A.TERBACA, 0) = 0 THEN 1 
										ELSE 2 END  
									ELSE
										CASE WHEN TO_CHAR(C.TANGGAL_BATAS,'YYYY-MM-DD') < TO_CHAR(CURRENT_DATE,'YYYY-MM-DD') AND COALESCE(C.TERBACA, 0) = 0 AND C.SATUAN_KERJA_ID_TUJUAN = '".$id."' THEN 0 
										WHEN TO_CHAR(C.TANGGAL_BATAS,'YYYY-MM-DD') >=  TO_CHAR(CURRENT_DATE,'YYYY-MM-DD') AND COALESCE(C.TERBACA, 0) = 0  AND C.SATUAN_KERJA_ID_TUJUAN = '".$id."' THEN 1             
										ELSE 2 END          
									END REMINTEN, A.TANGGAL_BATAS,
									 null SURAT_KELUAR_ID
							  FROM surat_bppnfi A 
								   LEFT JOIN SATUAN_KERJA B ON A.SATUAN_KERJA_ID_TUJUAN = B.SATUAN_KERJA_ID 
								   LEFT JOIN SATUAN_KERJA BB ON A.SATUAN_KERJA_ID_ASAL = BB.SATUAN_KERJA_ID 
								   LEFT JOIN disposisi C ON A.SURAT_BPPNFI_ID = C.SURAT_BPPNFI_ID 
								   AND ((C.SATUAN_KERJA_ID_ASAL = '".$id."' AND C.STATUS_KEMBALI = '1') OR (C.SATUAN_KERJA_ID_TUJUAN = '".$id."')) AND C.SATUAN_KERJA_ID_TUJUAN = '".$id."'
								   
								   LEFT JOIN
								   (
								   SELECT A.NAMA || '-' || B.NAMA NAMA, A.SATUAN_KERJA_ID, B.USER_ID
								   FROM satuan_kerja A
								   INNER JOIN users B ON A.SATUAN_KERJA_ID = B.SATUAN_KERJA_ID
								   WHERE 1=1
								   ) G ON G.SATUAN_KERJA_ID = C.SATUAN_KERJA_ID_ASAL AND C.USER_ID = G.USER_ID
								   LEFT JOIN
								   (
								   SELECT A.NAMA || '-' || B.NAMA NAMA1, A.NAMA, A.SATUAN_KERJA_ID, B.USER_ID
								   FROM satuan_kerja A
								   INNER JOIN users B ON A.SATUAN_KERJA_ID = B.SATUAN_KERJA_ID
								   WHERE 1=1 LIMIT 1
								   ) H ON H.SATUAN_KERJA_ID = C.SATUAN_KERJA_ID_TUJUAN  
							  WHERE 1 = 1 AND A.SURAT_MASUK_DELETE IS NULL 
									AND (A.SATUAN_KERJA_ID_TUJUAN LIKE '%".$id.";%' OR C.SATUAN_KERJA_ID_TUJUAN = '".$id."')
									   ".$statement;
			   //AND A.SURAT_MASUK_ID IN (2011, 2012)
			 	//AND (C.SATUAN_KERJA_ID_ASAL = ".$id." OR C.SATUAN_KERJA_ID_TUJUAN = ".$id.") OR (STATUS_KEMBALI = 1 AND C.SATUAN_KERJA_ID_ASAL = ".$id.")
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= "
					GROUP BY G.NAMA, H.NAMA, C.TERDISPOSISI, C.STATUS_KEMBALI, A.SURAT_BPPNFI_ID, A.TAHUN, A.NO_AGENDA, A.NOMOR, A.TANGGAL,BB.NAMA, 
							   A.INSTANSI_ASAL, B.NAMA, A.PERIHAL, C.TANGGAL_DISPOSISI, B.SATUAN_KERJA_ID,
							   A.SATUAN_KERJA_ID_TUJUAN, C.SATUAN_KERJA_ID_ASAL, 
							   A.TERBACA, A.TANGGAL_BATAS, C.TANGGAL_BATAS, C.TERBACA, C.SATUAN_KERJA_ID_TUJUAN, A.TERDISPOSISI, C.TERDISPOSISI  ".$order;
		$this->query = $str;
		// echo $str; exit;
	
		return $this->selectLimit($str,$limit,$from); 
    }
	
	function getCountByParamsSimple($paramsArray=array(), $varStatement="")
	{
		$str = "SELECT MAX(NO_AGENDA) AS ROWCOUNT FROM SURAT_BPPNFI WHERE 1=1 "; 
		while(list($key,$val)=each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$this->select($str); 
		$this->query = $str;
		
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0; 
    }
	
	function getCountByParamsMaxNomor($paramsArray=array(), $varStatement="")
	{
		$str = "
		SELECT CAST(MAX(NOMOR_GENERATE) AS INT)+1 AS ROWCOUNT 
		FROM 
		(
			SELECT 
				SURAT_BPPNFI_ID, TANGGAL, PERIHAL, NOMOR_SURAT, NOMOR_SURAT_LAIN, TANGGAL_DIAMBIL, DIAMBIL, NOMOR_GENERATE
			FROM
			(
				SELECT
					A.SURAT_BPPNFI_ID, TANGGAL, PERIHAL, TANGGAL_DIAMBIL, DIAMBIL,
					CASE WHEN POSITION('/' IN NOMOR) > 0 THEN SUBSTR(NOMOR, 0, POSITION('/' IN NOMOR)-1) ELSE NOMOR END NOMOR_SURAT,
					CASE WHEN POSITION('/' IN NOMOR) > 0 THEN SUBSTR(NOMOR, POSITION('/' IN NOMOR)) ELSE '' END NOMOR_SURAT_LAIN, 
					NOMOR_GENERATE
				FROM SURAT_BPPNFI A
				LEFT JOIN SURAT_PENGANTAR B ON A.SURAT_BPPNFI_ID = B.SURAT_BPPNFI_ID
				LEFT JOIN TANDA_TANGAN C ON A.TANDA_TANGAN_ID = C.TANDA_TANGAN_ID
				LEFT JOIN users D ON A.USER_ID = D.USER_ID
				LEFT JOIN KODE_HAL E ON A.KODE_HAL_ID = E.KODE_HAL_ID
				WHERE 1=1 AND A.SURAT_BPPNFI_DELETE IS NULL
			) A
			WHERE 1=1 ".$varStatement."
		) A
		WHERE 1=1
		"; 
		while(list($key,$val)=each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$this->select($str); 
		$this->query = $str;
		
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0; 
    }
	
	function getCountByParamsNomorSurat($paramsArray=array(), $statement='')
	{
		$str = "SELECT COUNT(1) ROWCOUNT
				FROM 
				(
					SELECT
					A.SURAT_BPPNFI_ID, TANGGAL, PERIHAL,
					CASE WHEN POSITION('/' IN NOMOR) > 0 THEN SUBSTR(NOMOR,0, POSITION('/' in NOMOR)-1) ELSE NOMOR END NOMOR_SURAT
					FROM SURAT_BPPNFI A
					LEFT JOIN SURAT_PENGANTAR B ON A.SURAT_BPPNFI_ID = B.SURAT_BPPNFI_ID
					LEFT JOIN TANDA_TANGAN C ON A.TANDA_TANGAN_ID = C.TANDA_TANGAN_ID
					LEFT JOIN users D ON A.USER_ID = D.USER_ID
					LEFT JOIN KODE_HAL E ON A.KODE_HAL_ID = E.KODE_HAL_ID
					WHERE 1=1 AND A.SURAT_BPPNFI_DELETE IS NULL
				)A
				WHERE 1=1
		"; 
		while(list($key,$val)=each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		$str .= $statement." ";
		
		$this->query = $str;
		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0; 
    }
	
	function getCountByParamsGenerateNomorBackdate($tahun='', $statement="")
	{
		$str = "
		SELECT CAST((COALESCE(CAST(MAX(NOMOR_BACKDATE) AS INTEGER), 0) + 1) AS TEXT) AS ROWCOUNT 
		FROM SURAT_BPPNFI A WHERE 1=1 AND A.SURAT_BPPNFI_DELETE IS NULL AND TO_CHAR(TANGGAL, 'YYYY') = '".$tahun."' 
		AND NOMOR_GENERATE IN (SELECT NOMOR_GENERATE FROM LIST_NO_SURAT X WHERE A.TIPE = X.TIPE AND TO_CHAR(A.TANGGAL, 'MMYYYY') = TO_CHAR(X.TANGGAL, 'MMYYYY')) ".$statement;
		$this->select($str);
		$this->query = $str;
		//echo $str;exit;
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0; 
    }
	
	function getCountByParamsGenerateNomorManual($tahun='', $statement="")
	{
		$str = "
		SELECT NOMOR_BACKDATE
		FROM SURAT_BPPNFI A WHERE 1=1 AND A.SURAT_BPPNFI_DELETE IS NULL ".$statement;
		$this->select($str);
		$this->query = $str;
		//echo $str;exit;
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 1; 
    }
	
	function getCountByParamsGenerateNomor($tahun='', $statement="")
	{
		$str = "
		SELECT GENERATEZERO(CAST((COALESCE(CAST(MAX(NOMOR_GENERATE) AS INTEGER), 0) + 1) AS TEXT), 3) AS ROWCOUNT 
		FROM SURAT_BPPNFI A WHERE 1=1 AND A.SURAT_BPPNFI_DELETE IS NULL AND TO_CHAR(TANGGAL, 'YYYY') = '".$tahun."' ".$statement;
		$this->select($str);
		$this->query = $str;
		//echo $str;exit;
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0; 
    }
	
	function getCountByParamsGenerateNomorDual($tahun='', $statement="")
	{
		$str = "
		SELECT GENERATEZERO(CAST((COALESCE(CAST(MAX(NOMOR_GENERATE) AS INTEGER), 0) + 2) AS TEXT), 3) AS ROWCOUNT 
		FROM SURAT_BPPNFI A WHERE 1=1 AND A.SURAT_BPPNFI_DELETE IS NULL AND TO_CHAR(TANGGAL, 'YYYY') = '".$tahun."' ".$statement;
		$this->select($str);
		$this->query = $str;
		//echo $str;exit;
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0; 
    }
	
	function getCountByParams($paramsArray=array(), $statement='')
	{
		$str = " SELECT COUNT(1) ROWCOUNT
				 FROM SURAT_BPPNFI A
				 LEFT JOIN SURAT_PENGANTAR B ON A.SURAT_BPPNFI_ID = B.SURAT_BPPNFI_ID
                 LEFT JOIN TANDA_TANGAN C ON A.TANDA_TANGAN_ID = C.TANDA_TANGAN_ID
                 LEFT JOIN users D ON A.USER_ID = D.USER_ID
                 WHERE 1=1 AND A.SURAT_BPPNFI_DELETE IS NULL
		"; 
		while(list($key,$val)=each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		$str .= " ".$statement;
		
		$this->query = $str;
		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0; 
    }


	
    function getCountByParamsUser($paramsArray=array(), $statement='', $id='')
	{
		$str = " SELECT COUNT(*) ROWCOUNT
				 FROM
				 (
				 SELECT A.SURAT_BPPNFI_ID
				  FROM SURAT_BPPNFI A 
					   LEFT JOIN SATUAN_KERJA B ON A.SATUAN_KERJA_ID_TUJUAN = B.SATUAN_KERJA_ID 
					   LEFT JOIN disposisi C ON A.SURAT_BPPNFI_ID = C.SURAT_BPPNFI_ID 
					   AND ((C.SATUAN_KERJA_ID_ASAL = '".$id."' AND C.STATUS_KEMBALI = '1') OR (C.SATUAN_KERJA_ID_TUJUAN = '".$id."')) AND C.SATUAN_KERJA_ID_TUJUAN = '".$id."'
					   LEFT JOIN SURAT_KELUAR D    ON A.SURAT_MASUK_ID = D.SURAT_MASUK_ID 
					   LEFT JOIN
					   (
					   SELECT A.NAMA || '-' || B.NAMA NAMA, A.SATUAN_KERJA_ID, B.USER_ID
					   FROM satuan_kerja A
					   INNER JOIN users B ON A.SATUAN_KERJA_ID = B.SATUAN_KERJA_ID
					   WHERE 1=1
					   ) G ON G.SATUAN_KERJA_ID = C.SATUAN_KERJA_ID_ASAL AND C.USER_ID = G.USER_ID
					   LEFT JOIN
					   (
					   SELECT A.NAMA || '-' || B.NAMA NAMA1, A.NAMA, A.SATUAN_KERJA_ID, B.USER_ID
					   FROM satuan_kerja A
					   INNER JOIN users B ON A.SATUAN_KERJA_ID = B.SATUAN_KERJA_ID
					   WHERE 1=1 LIMIT 1
					   ) H ON H.SATUAN_KERJA_ID = C.SATUAN_KERJA_ID_TUJUAN
				  WHERE 1 = 1 AND A.SURAT_MASUK_DELETE IS NULL 
						AND (A.SATUAN_KERJA_ID_TUJUAN LIKE '%".$id.";%' OR C.SATUAN_KERJA_ID_TUJUAN = '".$id."') 
						".$statement."
				  GROUP BY A.SURAT_BPPNFI_ID) A
		"; 
		while(list($key,$val)=each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		$str .= ' ';
		
		$this->query = $str;
		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0; 
    }
	


  }
?>