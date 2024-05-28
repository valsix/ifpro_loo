<? 

  /***
  * Entity-base class untuk mengimplementasikan tabel kategori.
  * 
  ***/
  include_once(APPPATH.'/models/Entity.php');

  class LampiranKeluar extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function LampiranKeluar()
	{
      $this->Entity(); 
    }
	
	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("surat_keluar_attachment_id", $this->getNextId("SURAT_KELUAR_ATTACHMENT_ID","SURAT_KELUAR_ATTACHMENT")); 
		
		$str = "		
				INSERT INTO SURAT_KELUAR_ATTACHMENT
				(SURAT_KELUAR_ATTACHMENT_ID, SURAT_KELUAR_ID, ATTACHMENT, CATATAN, UKURAN, TIPE) 
				VALUES ('".$this->getField("surat_keluar_attachment_id")."',
					'".$this->getField("surat_keluar_id")."', '".$this->getField("attachment")."', '".$this->getField("catatan")."',
					'".$this->getField("ukuran")."', '".$this->getField("tipe")."')
				"; 
				
		$this->query = $str;
		//echo $str;
		return $this->execQuery($str);
    }

    function update()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "UPDATE SURAT_KELUAR_ATTACHMENT SET
				  CATATAN = '".$this->getField("catatan")."'
				WHERE SURAT_KELUAR_ATTACHMENT_ID = '".$this->getField("surat_keluar_attachment_id")."'
				"; 
				
		$this->query = $str;
		return $this->execQuery($str);
    }
	
	function pindahUrut()
	{				
        $str = "UPDATE surat_keluar_attachment SET
				  LAMP_NOURUT = '".$this->getField("tmpLampNo")."'
				WHERE surat_keluar_attachment_id = '".$this->getField("IdLamp")."'				
		"; 				  
		$this->query = $str;
        $this->execQuery($str);
		//echo $str;
		
		$str1 = "UPDATE surat_keluar_attachment SET
		  LAMP_NOURUT = '".$this->getField("LampNo")."'
		WHERE surat_keluar_attachment_id = '".$this->getField("tmpIdLamp")."'				
		"; 
		$this->query = $str1;
		//echo $str1;
		return $this->execQuery($str1);
    }
	
	function update_urut()
	{
        $str = "UPDATE surat_keluar_attachment SET
				  LAMP_NOURUT = '".$this->getField("LAMP_NOURUT")."'
				WHERE surat_keluar_attachment_id = '".$this->getField("surat_keluar_attachment_id")."'				
		";
				  
		$this->query = $str;
        $this->execQuery($str);
    }
	
	function delete()
	{
        $str = "DELETE FROM SURAT_KELUAR_ATTACHMENT
                WHERE 
                  SURAT_KELUAR_ATTACHMENT_ID = '".$this->getField("surat_keluar_attachment_id")."'"; 
				  
		$this->query = $str;
        return $this->execQuery($str);
    }

    function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $varStatement="")
	{
		$str = "SELECT SURAT_KELUAR_ATTACHMENT_ID , SURAT_KELUAR_ID , ATTACHMENT , CATATAN , UKURAN , TIPE 
				FROM SURAT_KELUAR_ATTACHMENT WHERE 1 = 1
				".$varStatement;			    
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$this->query = $str;
		$str .= " ORDER BY SURAT_KELUAR_ATTACHMENT_ID ASC";
		//echo $str;		
		return $this->selectLimit($str,$limit,$from); 
    }
	
    function selectImage($paramsArray=array(),$limit=-1,$from=-1)
	{
		$str = "
					SELECT 
					LAMP_ISI
					FROM surat_keluar_attachment
					WHERE 1 = 1
			   "; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		$this->query = $str;
				
		return $this->selectLimit($str,$limit,$from); 
    }
   
    function getCountByParams($paramsArray=array())
	{
		$str = "SELECT COUNT(SURAT_KELUAR_ATTACHMENT_ID) AS ROWCOUNT FROM SURAT_KELUAR_ATTACHMENT WHERE SURAT_KELUAR_ATTACHMENT_ID IS NOT NULL "; 
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
		$str = "SELECT COUNT(surat_keluar_attachment_id) AS ROWCOUNT FROM surat_keluar_attachment WHERE surat_keluar_attachment_id IS NOT NULL "; 
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

    function getMaxNoUrut($paramsArray=array())
	{
		$str = "SELECT COUNT(SURAT_KELUAR_ATTACHMENT_ID) NOURUT FROM SURAT_KELUAR_ATTACHMENT WHERE SURAT_KELUAR_ATTACHMENT_ID IS NOT NULL ";
		//$str = "SELECT MAX(LAMP_NOURUT) NOURUT FROM surat_keluar_attachment WHERE LAMP_NOURUT IS NOT NULL "; 
		while(list($key,$val)=each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("NOURUT") + 1; 
		else 
			return 1; 
    }
  } 
?>