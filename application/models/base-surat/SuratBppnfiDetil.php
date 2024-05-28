<? 

  /***
  * Entity-base class untuk mengimplementasikan tabel kategori.
  * 
  ***/
  include_once(APPPATH.'/models/Entity.php');

  class SuratBppnfiDetil extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function SuratBppnfiDetil()
	{
      $this->Entity(); 
    }
	
	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("SURAT_BPPNFI_DETIL_ID", $this->getNextId("SURAT_BPPNFI_DETIL_ID","SURAT_BPPNFI_DETIL")); 
		$this->tanggal = date("Y-m-d");
		$this->NowYear = date("Y");
	
		$str = "
		   INSERT INTO SURAT_BPPNFI_DETIL (
		   			SURAT_BPPNFI_DETIL_ID, SURAT_BPPNFI_ID, ISI, JUMLAH, KETERANGAN, LAST_CREATE_USER, LAST_CREATE_DATE
		   ) 
		   VALUES (
					'".$this->getField("SURAT_BPPNFI_DETIL_ID")."', 
					'".$this->getField("SURAT_BPPNFI_ID")."',
					'".$this->getField("ISI")."',
					'".$this->getField("JUMLAH")."',
					'".$this->getField("KETERANGAN")."',
				  	'".$this->getField("LAST_CREATE_USER")."',
				  	CURRENT_DATE
			)";
				
		$this->query = $str;
		$this->reqSuratBppnfiDetilId = $this->getField("SURAT_BPPNFI_DETIL_ID");
		//echo $str;
		return $this->execQuery($str);
    }
	
	function updateDyna()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "UPDATE SURAT_BPPNFI_DETIL A SET
				  ".$this->getField("FIELD")." = '".$this->getField("FIELD_VALUE")."'
				WHERE SURAT_BPPNFI_DETIL_ID = ".$this->getField("SURAT_BPPNFI_DETIL_ID")."
				"; 
				$this->query = $str;
		//echo $str;
		return $this->execQuery($str);
    }
	
	function update()
	{
		//Auto-generate primary key(s) by next max value (integer)
		$str = "
		UPDATE SURAT_BPPNFI_DETIL SET
					ISI					= '".$this->getField("ISI")."',
					JUMLAH				= '".$this->getField("JUMLAH")."',
					KETERANGAN			= '".$this->getField("KETERANGAN")."',
				  	LAST_UPDATE_USER	= '".$this->getField("LAST_UPDATE_USER")."',
				  	LAST_UPDATE_DATE	= CURRENT_DATE
		   WHERE SURAT_BPPNFI_DETIL_ID 	= '".$this->getField("SURAT_BPPNFI_DETIL_ID")."'
				"; 
				$this->query = $str;
		return $this->execQuery($str);
    }
		
	function delete()
	{	
		$str1 = "
		 		DELETE FROM SURAT_BPPNFI_DETIL
                WHERE 
                  SURAT_BPPNFI_ID = '".$this->getField("SURAT_BPPNFI_ID")."'";
				  
		$this->query = $str1;
        return $this->execQuery($str1);
    }
	
	function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement="", $sOrder="")
	{
		$str = "
				SELECT 
				SURAT_BPPNFI_DETIL_ID, SURAT_BPPNFI_ID, ISI, 
				   JUMLAH, KETERANGAN
				FROM SURAT_BPPNFI_DETIL
				WHERE 1=1
			   "; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= " ".$statement." ".$sOrder;
		$this->query = $str;
	
		return $this->selectLimit($str,$limit,$from); 
    }
	
	function getCountByParams($paramsArray=array(), $statement='')
	{
		$str = " SELECT COUNT(1) ROWCOUNT
				 FROM SURAT_BPPNFI_DETIL
                 WHERE 1=1
		"; 
		while(list($key,$val)=each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		$str .= " ".$statement;
		
		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0; 
    }
	
  }
?>