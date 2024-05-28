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

  class SuratNotaDinasData extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function SuratNotaDinasData()
	{
      $this->Entity(); 
    }
	
	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("SURAT_NOTA_DINAS_DATA_ID", $this->getNextId("SURAT_NOTA_DINAS_DATA_ID","SURAT_NOTA_DINAS_DATA")); 
		$str = "
				INSERT INTO SURAT_NOTA_DINAS_DATA (
				   SURAT_NOTA_DINAS_DATA_ID, SURAT_BPPNFI_ID, KETERANGAN, LAST_CREATE_USER, LAST_CREATE_DATE) 
				VALUES(
				  ".$this->getField("SURAT_NOTA_DINAS_DATA_ID").",
				  ".$this->getField("SURAT_BPPNFI_ID").",
				  '".$this->getField("KETERANGAN")."',
				  '".$this->getField("LAST_CREATE_USER")."',
				  CURRENT_DATE
				)"; 
		$this->id = $this->getField("SURAT_NOTA_DINAS_DATA_ID");
		$this->query = $str;
		return $this->execQuery($str);
    }
	
	function insertCopy()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("SURAT_NOTA_DINAS_DATA_ID", $this->getNextId("SURAT_NOTA_DINAS_DATA_ID","SURAT_NOTA_DINAS_DATA")); 
		$str = "
				INSERT INTO SURAT_NOTA_DINAS_DATA (
				   SURAT_NOTA_DINAS_DATA_ID, SURAT_BPPNFI_ID, KETERANGAN, LAST_CREATE_USER, LAST_CREATE_DATE) 
				SELECT (SELECT MAX(SURAT_NOTA_DINAS_DATA_ID) FROM SURAT_NOTA_DINAS_DATA)+ROW_NUMBER() OVER() AS SURAT_NOTA_DINAS_DATA_ID, 
					".$this->getField("SURAT_BPPNFI_ID").", KETERANGAN, 
					'".$this->getField("LAST_CREATE_USER")."', CURRENT_DATE
				FROM SURAT_NOTA_DINAS_DATA
				WHERE SURAT_BPPNFI_ID = ".$this->getField("SURAT_BPPNFI_ID_DATA")."";
				
		$this->id = $this->getField("SURAT_NOTA_DINAS_DATA_ID");
		$this->query = $str;
		return $this->execQuery($str);
    }

    function update()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "UPDATE SURAT_NOTA_DINAS_DATA SET
				  KETERANGAN 		= '".$this->getField("KETERANGAN")."',
				  LAST_UPDATE_USER	= '".$this->getField("LAST_UPDATE_USER")."',
				  LAST_UPDATE_DATE	= CURRENT_DATE
				WHERE SURAT_NOTA_DINAS_DATA_ID 	= '".$this->getField("SURAT_NOTA_DINAS_DATA_ID")."'
				"; 
				$this->query = $str;
		return $this->execQuery($str);
    }
	
	function delete()
	{
        $str = "DELETE FROM SURAT_NOTA_DINAS_DATA
                WHERE 
                  SURAT_BPPNFI_ID = '".$this->getField("SURAT_BPPNFI_ID")."'"; 
				  
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
				SELECT 
				SURAT_NOTA_DINAS_DATA_ID, SURAT_BPPNFI_ID, KETERANGAN
				FROM SURAT_NOTA_DINAS_DATA
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
				SURAT_NOTA_DINAS_DATA_ID, SURAT_BPPNFI_ID, KETERANGAN
				FROM SURAT_NOTA_DINAS_DATA
				WHERE 1 = 1
				"; 
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key LIKE '%$val%' ";
		}
		
		$str .= $statement." ORDER BY SURAT_NOTA_DINAS_DATA_ID DESC";
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
		$str = "SELECT COUNT(1) AS ROWCOUNT FROM SURAT_NOTA_DINAS_DATA WHERE 1 = 1 ".$statement; 
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
		$str = "SELECT COUNT(1) AS ROWCOUNT FROM SURAT_NOTA_DINAS_DATA WHERE 1 = 1 "; 
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