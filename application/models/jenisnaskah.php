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

  class JenisNaskah extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function JenisNaskah()
	{
      $this->Entity(); 
    }
	
	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("JENIS_NASKAH_ID", $this->getNextId("JENIS_NASKAH_ID","JENIS_NASKAH")); 
		$str = "
				INSERT INTO JENIS_NASKAH (
				   JENIS_NASKAH_ID, NAMA, KETERANGAN, PREFIX, KODE_SURAT, KD_LEVEL, NAMA_LEVEL, KD_LEVEL_CABANG, NAMA_LEVEL_CABANG,
				   KODE_SURAT_KELUAR, DIGIT_NOMOR, ATTACHMENT, LINK_URL, TIPE_NASKAH, PENERBIT_NOMOR, JENIS_TTD, 
				   LAST_CREATE_USER, LAST_CREATE_DATE) 
				VALUES (
					'".$this->getField("JENIS_NASKAH_ID")."', 
					'".$this->getField("NAMA")."', 
					'".$this->getField("KETERANGAN")."', 
					'".$this->getField("PREFIX")."', 
					'".$this->getField("KODE_SURAT")."', 
					'".$this->getField("KD_LEVEL")."', 
					'".$this->getField("NAMA_LEVEL")."', 
					'".$this->getField("KD_LEVEL_CABANG")."', 
					'".$this->getField("NAMA_LEVEL_CABANG")."', 
					'".$this->getField("KODE_SURAT_KELUAR")."', 
					'".$this->getField("DIGIT_NOMOR")."', 
					'".$this->getField("ATTACHMENT")."', 
					'".$this->getField("LINK_URL")."', 
					'".$this->getField("TIPE_NASKAH")."', 
					'".$this->getField("PENERBIT_NOMOR")."', 
					'".$this->getField("JENIS_TTD")."', 
					'".$this->getField("LAST_CREATE_USER")."', 
					CURRENT_DATE)"; 
		$this->id = $this->getField("JENIS_NASKAH_ID");
		$this->query = $str;

		return $this->execQuery($str);
    }
	
	
	function insertTemplate()
	{
		$str = "
				INSERT INTO SATUAN_KERJA_TEMPLATE (
				   JENIS_NASKAH_ID, SATUAN_KERJA_ID, ATTACHMENT, LAST_CREATE_USER, LAST_CREATE_DATE) 
				VALUES ( '".$this->getField("JENIS_NASKAH_ID")."', '".$this->getField("SATUAN_KERJA_ID")."', '".$this->getField("ATTACHMENT")."', 
				'".$this->getField("LAST_CREATE_USER")."', CURRENT_DATE)"; 
		$this->query = $str;
		return $this->execQuery($str);
    }

    function update()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "
			   UPDATE JENIS_NASKAH
				SET    NAMA         	= '".$this->getField("NAMA")."',
					   PENERBIT_NOMOR      	= '".$this->getField("PENERBIT_NOMOR")."',
					   KETERANGAN      	= '".$this->getField("KETERANGAN")."',
					   TIPE_NASKAH		= '".$this->getField("TIPE_NASKAH")."',
					   KD_LEVEL			= '".$this->getField("KD_LEVEL")."',
					   NAMA_LEVEL		= '".$this->getField("NAMA_LEVEL")."',
					   KD_LEVEL_CABANG		= '".$this->getField("KD_LEVEL_CABANG")."',
					   NAMA_LEVEL_CABANG	= '".$this->getField("NAMA_LEVEL_CABANG")."',
					   PREFIX      		= '".$this->getField("PREFIX")."',
					   KODE_SURAT      	= '".$this->getField("KODE_SURAT")."',
					   KODE_SURAT_KELUAR    = '".$this->getField("KODE_SURAT_KELUAR")."',
					   DIGIT_NOMOR      	= '".$this->getField("DIGIT_NOMOR")."',
					   ATTACHMENT      		= '".$this->getField("ATTACHMENT")."',
					   LINK_URL      		= '".$this->getField("LINK_URL")."',
					   JENIS_TTD      		= '".$this->getField("JENIS_TTD")."',
					   LAST_UPDATE_USER   	= '".$this->getField("LAST_UPDATE_USER")."',
					   LAST_UPDATE_DATE   	= CURRENT_DATE
				WHERE  JENIS_NASKAH_ID    	= '".$this->getField("JENIS_NASKAH_ID")."'
				"; 
		// echo $str;exit;
		$this->query = $str;
		return $this->execQuery($str);
    }

    function updateByField()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "UPDATE JENIS_NASKAH A SET
				  ".$this->getField("FIELD")." 		= '".$this->getField("FIELD_VALUE")."'
				WHERE JENIS_NASKAH_ID = ".$this->getField("JENIS_NASKAH_ID")."
				"; 
				$this->query = $str;
		return $this->execQuery($str);
    }	
	
	function delete()
	{
        $str = "DELETE FROM JENIS_NASKAH
                WHERE 
                  JENIS_NASKAH_ID = ".$this->getField("JENIS_NASKAH_ID").""; 
				  
		$this->query = $str;
        return $this->execQuery($str);
    }
	
	function deleteTemplate()
	{
        $str = "DELETE FROM SATUAN_KERJA_TEMPLATE
                WHERE 
                  SATUAN_KERJA_ID = '".$this->getField("SATUAN_KERJA_ID")."'"; 
				  
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
    function selectByParams($paramsArray=array(),$limit=-1,$from=-1,$statement="", $order=" ORDER BY JENIS_NASKAH_ID ASC")
	{
		$str = "
				SELECT 
				JENIS_NASKAH_ID, NAMA, KETERANGAN, KD_LEVEL, NAMA_LEVEL, TIPE_NASKAH, KD_LEVEL_CABANG, NAMA_LEVEL_CABANG, 
				PREFIX, KODE_SURAT, DIGIT_NOMOR, KODE_SURAT_KELUAR, ATTACHMENT, LINK_URL, PENERBIT_NOMOR, JENIS_TTD 
				FROM JENIS_NASKAH A
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
	
    function selectByParamsEntri($satuanKerjaId, $paramsArray=array(),$limit=-1,$from=-1,$statement="", $order=" ORDER BY A.JENIS_NASKAH_ID ASC")
	{
		$str = "
				SELECT 
				A.JENIS_NASKAH_ID, NAMA, B.ATTACHMENT
				FROM JENIS_NASKAH A
				LEFT JOIN SATUAN_KERJA_TEMPLATE B ON A.JENIS_NASKAH_ID = B.JENIS_NASKAH_ID AND B.SATUAN_KERJA_ID = '".$satuanKerjaId."'
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
	
	function selectByParamsLike($paramsArray=array(),$limit=-1,$from=-1, $statement="")
	{
		$str = "    
				SELECT 
				JENIS_NASKAH_ID, NAMA, KETERANGAN
				FROM JENIS_NASKAH A
				WHERE 1 = 1
			"; 
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key LIKE '%$val%' ";
		}
		
		$str .= $statement." ORDER BY JENIS_NASKAH_ID DESC";
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
		$str = "SELECT COUNT(JENIS_NASKAH_ID) AS ROWCOUNT FROM JENIS_NASKAH A WHERE 1 = 1 ".$statement; 
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
	
	
    function getJson($paramsArray=array(), $statement="")
	{

		$str = " SELECT ARRAY_TO_JSON(ARRAY_AGG(ROW_TO_JSON(T))) NILAI FROM (SELECT REPLACE(NAMA, ' ', '') \"value\", NAMA \"label\" FROM JENIS_NASKAH WHERE 1 = 1 ".$statement; 
		while(list($key,$val)=each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= " ) T ";
		
		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("NILAI"); 
		else 
			return "[]"; 
    }
			
    function getCountByParamsLike($paramsArray=array())
	{
		$str = "SELECT COUNT(JENIS_NASKAH_ID) AS ROWCOUNT FROM JENIS_NASKAH WHERE 1 = 1 "; 
		while(list($key,$val)=each($paramsArray))
		{
			$str .= " AND $key LIKE '%$val%' ";
		}
		
		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0; 
    }	
  } 
?>