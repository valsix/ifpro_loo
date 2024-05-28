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
  include_once(APPPATH.'/models/Entity.php');

  class NoGenerate extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function NoGenerate()
	{
      $this->Entity(); 
    }
	
	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("NO_GENERATE_ID", $this->getNextId("NO_GENERATE_ID","NO_GENERATE")); 

		$str = "INSERT INTO NO_GENERATE(
				   NO_GENERATE_ID, TAHUN, NOMOR, TIPE, STATUS_AKTIF, SATUAN_KERJA_ID) 
				VALUES (
				  ".$this->getField("NO_GENERATE_ID").",
				  '".$this->getField("TAHUN")."',
				  '".$this->getField("NOMOR")."',
				  ".$this->getField("TIPE").",
				  '".$this->getField("STATUS_AKTIF")."',
				  '".$this->getField("SATUAN_KERJA_ID")."'
				)"; 
				
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
	
    function update()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "UPDATE NO_GENERATE SET
				  	  TAHUN 		= '".$this->getField("TAHUN")."',
				  	  NOMOR 		= '".$this->getField("NOMOR")."',
					  STATUS_AKTIF 	= '".$this->getField("STATUS_AKTIF")."',
				  	  TIPE			= ".$this->getField("TIPE").",
				  	  SATUAN_KERJA_ID = '".$this->getField("SATUAN_KERJA_ID")."'
				WHERE NO_GENERATE_ID = '".$this->getField("NO_GENERATE_ID")."'
				"; 
				$this->query = $str;
		return $this->execQuery($str);
    }
	
    function updateStatus()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "UPDATE NO_GENERATE 
				SET
				  	  STATUS_AKTIF 		= '".$this->getField("STATUS_AKTIF")."'
				WHERE TAHUN 			= '".$this->getField("TAHUN")."' 
				AND SATUAN_KERJA_ID 	= '".$this->getField("SATUAN_KERJA_ID")."'
				AND TIPE 				= '".$this->getField("TIPE")."'
				"; 
				$this->query = $str;
		return $this->execQuery($str);
    }
	
	function delete()
	{
        $str = "DELETE FROM NO_GENERATE
                WHERE NO_GENERATE_ID = '".$this->getField("NO_GENERATE_ID")."'"; 
				  
		$this->query = $str;
        return $this->execQuery($str);
    }

    /** 
    * Cari record berdasarkan array parameter dan limit tampilan 
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","STATUS"=>"yyy") 
    * @param int limit Jumlah maksimal record yang akan diambil 
    * @param int from Awal record yang diambil 
    * @return boolean True jika sukses, false jika tidak 
    **/ 
    function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement="", $sOrder="ORDER BY NO_GENERATE_ID ASC")
	{
		$str = "SELECT NO_GENERATE_ID, TAHUN, NOMOR, TIPE, STATUS_AKTIF, SATUAN_KERJA_ID, CASE WHEN STATUS_AKTIF =  '1' THEN 'Aktif' ELSE 'Non Aktif' END STATUS_INFO
				FROM NO_GENERATE WHERE NO_GENERATE_ID IS NOT NULL"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$sOrder;
		$this->query = $str;
				
		return $this->selectLimit($str,$limit,$from); 
    }
	
    function selectByParamsMonitoring($paramsArray=array(),$limit=-1,$from=-1, $statement="", $sOrder="ORDER BY NO_GENERATE_ID ASC")
	{
		$str = "SELECT NO_GENERATE_ID, TAHUN, NOMOR, TIPE, STATUS_AKTIF, A.SATUAN_KERJA_ID, 
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
					CASE WHEN STATUS_AKTIF =  '1' THEN 'Aktif' ELSE 'Non Aktif' END STATUS_INFO, COALESCE(B.NAMA, '-') SATUAN_KERJA_NAMA
				FROM NO_GENERATE A
				LEFT JOIN SATUAN_KERJA B ON A.SATUAN_KERJA_ID = B.SATUAN_KERJA_ID
				WHERE NO_GENERATE_ID IS NOT NULL"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$sOrder;
		$this->query = $str;
				
		return $this->selectLimit($str,$limit,$from); 
    }
    
    /** 
    * Hitung jumlah record berdasarkan parameter (array). 
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","STATUS"=>"yyy") 
    * @return long Jumlah record yang sesuai kriteria 
    **/ 
    function getCountByParams($paramsArray=array(), $statement="")
	{
		$str = "SELECT COUNT(1) AS ROWCOUNT FROM NO_GENERATE WHERE NO_GENERATE_ID IS NOT NULL "; 
		while(list($key,$val)=each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		$str.=$statement;
		$this->select($str);
		$this->query = $str; 
		if($this->firstRow()) 
			return $this->getField("rowcount"); 
		else 
			return 0; 
    }
	
	function getCountByParamsGenerateNomor($statement="")
	{
		$str = "
		SELECT GENERATEZERO(CAST((COALESCE(CAST(MAX(NOMOR) AS INTEGER),'0') + 1) AS TEXT), 3) AS ROWCOUNT FROM NO_GENERATE WHERE STATUS_AKTIF = '1'".$statement;
		$this->select($str);
		$this->query = $str;
		//echo $str;exit;
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0; 
    }

  } 
?>