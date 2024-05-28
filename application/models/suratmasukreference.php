<? 

  /***
  * Entity-base class untuk mengimplementasikan tabel kategori.
  * 
  ***/
  include_once(APPPATH.'/models/Entity.php');

  class SuratMasukReference extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function SuratMasukReference()
	{
      $this->Entity(); 
    }
	
	function insert()
	{
		$this->setField("SURAT_MASUK_REFERENCE_ID", $this->getNextId("SURAT_MASUK_REFERENCE_ID","SURAT_MASUK_REFERENCE"));
		$str = "
		INSERT INTO SURAT_MASUK_REFERENCE(SURAT_MASUK_REFERENCE_ID, SURAT_MASUK_ID, SM_REF_ID)
        VALUES 
        (
	        '".$this->getField("SURAT_MASUK_REFERENCE_ID")."',
	        '".$this->getField("SURAT_MASUK_ID")."',
	        '".$this->getField("SM_REF_ID")."'
        )
		";
				
		// echo $str; exit;
		$this->query = $str;
		return $this->execQuery($str);
    }

	function delete()
	{		
		$str1 = "
		 		DELETE FROM SURAT_MASUK_REFERENCE
                WHERE 
                  SURAT_MASUK_REFERENCE_ID = '".$this->getField("SURAT_MASUK_REFERENCE_ID")."'";
				  
		$this->query = $str1;
        return $this->execQuery($str1);
    }
	
	function deleteParent()
	{		
		$str1 = "
		 		DELETE FROM SURAT_MASUK_REFERENCE
                WHERE 
                  SURAT_MASUK_ID = '".$this->getField("SURAT_MASUK_ID")."' ";
				  
		$this->query = $str1;
        return $this->execQuery($str1);
    }
	
	function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order="ORDER BY A.SURAT_MASUK_REFERENCE_ID")
	{
		$str = "
		SELECT 
		A.SURAT_MASUK_REFERENCE_ID, A.SURAT_MASUK_ID, A.SM_REF_ID, B.NOMOR
		FROM SURAT_MASUK_REFERENCE A
		INNER JOIN SURAT_MASUK B ON B.SURAT_MASUK_ID = A.SM_REF_ID
		WHERE 1=1
		"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= " ".$statement.$order;
		$this->query = $str;
		//echo $str; 
	
		return $this->selectLimit($str,$limit,$from); 
    }
	
    function getCountByParams($paramsArray=array(),$statement="")
	{
		$str = "SELECT COUNT(1) AS ROWCOUNT
		FROM SURAT_MASUK_REFERENCE A
		INNER JOIN SURAT_MASUK B ON B.SURAT_MASUK_ID = A.SM_REF_ID
		WHERE 1=1 "; 
		while(list($key,$val)=each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement;

		// echo $str;exit;
		$this->query = $str;
		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0; 
    }
	
  } 
?>