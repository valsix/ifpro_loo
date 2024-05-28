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

  class LokasiLooDetil extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function LokasiLooDetil()
	{
      $this->Entity(); 
    }
	
	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		// $this->setField("LOKASI_LOO_DETIL_ID", $this->getNextId("LOKASI_LOO_DETIL_ID","LOKASI_LOO_DETIL")); 
		$str = "
				INSERT INTO LOKASI_LOO_DETIL (
				   LOKASI_LOO_ID, KODE, NAMA, LANTAI_LOO_ID, PRIME, LUAS, KD_TARIF, DESKRIPSI
				   ) 
				VALUES (
					'".$this->getField("LOKASI_LOO_ID")."', 
					'".$this->getField("KODE")."',
					'".$this->getField("NAMA")."',
					'".$this->getField("LANTAI_LOO_ID")."',
					'".$this->getField("PRIME")."',
					'".$this->getField("LUAS")."',
					'".$this->getField("KD_TARIF")."',
					'".$this->getField("DESKRIPSI")."'
				)"; 
		$this->id = $this->getField("LOKASI_LOO_DETIL_ID");
		$this->query = $str;

		return $this->execQuery($str);
    }
	
	
	function insertTemplate()
	{
		$str = "
				INSERT INTO SATUAN_KERJA_TEMPLATE (
				   LOKASI_LOO_DETIL_ID, SATUAN_KERJA_ID, ATTACHMENT, LAST_CREATE_USER, LAST_CREATE_DATE) 
				VALUES ( '".$this->getField("LOKASI_LOO_DETIL_ID")."', '".$this->getField("SATUAN_KERJA_ID")."', '".$this->getField("ATTACHMENT")."', 
				'".$this->getField("LAST_CREATE_USER")."', CURRENT_DATE)"; 
		$this->query = $str;
		return $this->execQuery($str);
    }

  function update()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "
			   UPDATE LOKASI_LOO_DETIL
				SET   
					   LOKASI_LOO_ID      	= '".$this->getField("LOKASI_LOO_ID")."',
					   KODE		= '".$this->getField("KODE")."',
					   NAMA		= '".$this->getField("NAMA")."',
					   LANTAI_LOO_ID		= '".$this->getField("LANTAI_LOO_ID")."',
					   PRIME		= '".$this->getField("PRIME")."',
					   LUAS		= '".$this->getField("LUAS")."',
					   KD_TARIF		= '".$this->getField("KD_TARIF")."',
					   DESKRIPSI		= '".$this->getField("DESKRIPSI")."'
				WHERE  LOKASI_LOO_DETIL_ID    	= '".$this->getField("LOKASI_LOO_DETIL_ID")."'
				"; 
		// echo $str;exit;
		$this->query = $str;
		return $this->execQuery($str);
    }

    function updateByField()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "UPDATE LOKASI_LOO_DETIL A SET
				  ".$this->getField("FIELD")." 		= '".$this->getField("FIELD_VALUE")."'
				WHERE LOKASI_LOO_DETIL_ID = ".$this->getField("LOKASI_LOO_DETIL_ID")."
				"; 
				$this->query = $str;
		return $this->execQuery($str);
    }	
	
	function delete()
	{
        $str = "DELETE FROM LOKASI_LOO_DETIL
                WHERE 
                  LOKASI_LOO_DETIL_ID = ".$this->getField("LOKASI_LOO_DETIL_ID").""; 
				  
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
    function selectByParams($paramsArray=array(),$limit=-1,$from=-1,$statement="", $order=" ORDER BY A.LOKASI_LOO_DETIL_ID ASC")
	{
		// LOKASI_LOO_DETIL_ID, A.LOKASI_LOO_ID, A.KODE, A.NAMA, A.LANTAI_LOO_ID, A.PRIME, A.LUAS, A.KD_TARIF, A.DESKRIPSI
		$str = "
		SELECT 
		B.NAMA NAMA_LOKASI_LOO, C.NAMA NAMA_LANTAI, CASE WHEN C.TIPE = 'I' THEN 'Indoor' ELSE 'Outdoor' END TIPE_LANTAI_INFO 
		, A.*
		FROM LOKASI_LOO_DETIL A
		LEFT JOIN LOKASI_LOO B ON B.LOKASI_LOO_ID = A.LOKASI_LOO_ID
		LEFT JOIN LANTAI_LOO C ON C.LANTAI_LOO_ID = A.LANTAI_LOO_ID
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
	
    function selectByParamsEntri($satuanKerjaId, $paramsArray=array(),$limit=-1,$from=-1,$statement="", $order=" ORDER BY A.LOKASI_LOO_DETIL_ID ASC")
	{
		$str = "
				SELECT 
				A.LOKASI_LOO_DETIL_ID, NAMA, B.ATTACHMENT
				FROM LOKASI_LOO_DETIL A
				LEFT JOIN SATUAN_KERJA_TEMPLATE B ON A.LOKASI_LOO_DETIL_ID = B.LOKASI_LOO_DETIL_ID AND B.SATUAN_KERJA_ID = '".$satuanKerjaId."'
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
				LOKASI_LOO_DETIL_ID, NAMA, KETERANGAN
				FROM LOKASI_LOO_DETIL A
				WHERE 1 = 1
			"; 
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key LIKE '%$val%' ";
		}
		
		$str .= $statement." ORDER BY LOKASI_LOO_DETIL_ID DESC";
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
		$str = "SELECT COUNT(LOKASI_LOO_DETIL_ID) AS ROWCOUNT FROM LOKASI_LOO_DETIL A WHERE 1 = 1 ".$statement; 
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

		$str = " SELECT ARRAY_TO_JSON(ARRAY_AGG(ROW_TO_JSON(T))) NILAI FROM (SELECT REPLACE(NAMA, ' ', '') \"value\", NAMA \"label\" FROM LOKASI_LOO_DETIL WHERE 1 = 1 ".$statement; 
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
		$str = "SELECT COUNT(LOKASI_LOO_DETIL_ID) AS ROWCOUNT FROM LOKASI_LOO_DETIL WHERE 1 = 1 "; 
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