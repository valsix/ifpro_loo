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

  class ListNoSurat extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function ListNoSurat()
	{
      $this->Entity(); 
    }
	
	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("LIST_NO_SURAT_ID", $this->getNextId("LIST_NO_SURAT_ID","LIST_NO_SURAT")); 
		$str = "
				INSERT INTO LIST_NO_SURAT (LIST_NO_SURAT_ID, TIPE, TANGGAL, NOMOR_GENERATE, SATUAN_KERJA_ID, LAST_CREATE_USER, LAST_CREATE_DATE) 
				VALUES(
				  ".$this->getField("LIST_NO_SURAT_ID").",
				  ".$this->getField("TIPE").",
				  ".$this->getField("TANGGAL").",
				  '".$this->getField("NOMOR_GENERATE")."',
				  '".$this->getField("SATUAN_KERJA_ID")."',
				  '".$this->getField("LAST_CREATE_USER")."',
				  CURRENT_DATE
				)"; 
		$this->id = $this->getField("LIST_NO_SURAT_ID");
		$this->query = $str;
		return $this->execQuery($str);
    }

    function update()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "UPDATE LIST_NO_SURAT SET
				  	TIPE				= ".$this->getField("TIPE").",
				  	TANGGAL				= ".$this->getField("TANGGAL").",
				  	NOMOR_GENERATE 		= '".$this->getField("NOMOR_GENERATE")."',
				  	SATUAN_KERJA_ID		= '".$this->getField("SATUAN_KERJA_ID")."',
				    LAST_UPDATE_USER	= '".$this->getField("LAST_UPDATE_USER")."',
				    LAST_UPDATE_DATE	= CURRENT_DATE
				WHERE LIST_NO_SURAT_ID 	= '".$this->getField("LIST_NO_SURAT_ID")."'
				"; 
				$this->query = $str;
		return $this->execQuery($str);
    }
	
	function delete()
	{
        $str = "DELETE FROM LIST_NO_SURAT
                WHERE 
                  LIST_NO_SURAT_ID = '".$this->getField("LIST_NO_SURAT_ID")."'"; 
				  
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
    function selectByParams($paramsArray=array(),$limit=-1,$from=-1,$statement="", $order="")
	{
		$str = "
				SELECT LIST_NO_SURAT_ID, TIPE, TANGGAL, NOMOR_GENERATE, TO_CHAR(TANGGAL, 'MMYYYY') PERIODE, SATUAN_KERJA_ID
				FROM LIST_NO_SURAT		
				WHERE 1 = 1
			"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = $val ";
		}
		
		$str .= $statement." ".$order;
		$this->query = $str;
		return $this->selectLimit($str,$limit,$from); 
    }
    
	function selectByParamsLike($paramsArray=array(),$limit=-1,$from=-1, $statement="")
	{
		$str = "
				SELECT LIST_NO_SURAT_ID, TIPE, TANGGAL, NOMOR_GENERATE, TO_CHAR(TANGGAL, 'MMYYYY') PERIODE
				FROM LIST_NO_SURAT		
				WHERE 1 = 1
				"; 
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key LIKE '%$val%' ";
		}
		
		$str .= $statement." ORDER BY LIST_NO_SURAT_ID DESC";
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
		$str = "SELECT COUNT(LIST_NO_SURAT_ID) AS ROWCOUNT FROM LIST_NO_SURAT WHERE 1 = 1 ".$statement; 
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

    function getCountByParamsLike($paramsArray=array())
	{
		$str = "SELECT COUNT(LIST_NO_SURAT_ID) AS ROWCOUNT FROM LIST_NO_SURAT WHERE 1 = 1 "; 
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