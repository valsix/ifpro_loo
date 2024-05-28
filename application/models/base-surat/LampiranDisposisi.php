<? 

  /***
  * Entity-base class untuk mengimplementasikan tabel kategori.
  * 
  ***/
  include_once(APPPATH.'/models/Entity.php');

  class LampiranDisposisi extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function LampiranDisposisi()
	{
      $this->Entity(); 
    }
	
	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("DISPOSISI_ATTACHMENT_ID", $this->getNextId("DISPOSISI_ATTACHMENT_ID","disposisi_attachment")); 
		
		$str = "		
				INSERT INTO disposisi_attachment
				(DISPOSISI_ATTACHMENT_ID, DISPOSISI_ID, CATATAN, UKURAN, TIPE, NAMA) 
				VALUES (
					'".$this->getField("DISPOSISI_ATTACHMENT_ID")."',
					'".$this->getField("DISPOSISI_ID")."', 
					'".$this->getField("CATATAN")."',
					'".$this->getField("UKURAN")."', 
					'".$this->getField("TIPE")."',
					'".$this->getField("NAMA")."'
				)
				"; 
				
		$this->query = $str;
		$this->id= $this->getField("DISPOSISI_ATTACHMENT_ID");
		//echo $str;
		return $this->execQuery($str);
    }

    function update()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "UPDATE disposisi_attachment SET
				  CATATAN = '".$this->getField("CATATAN")."'
				WHERE DISPOSISI_ATTACHMENT_ID = '".$this->getField("DISPOSISI_ATTACHMENT_ID")."'
				"; 
				
		$this->query = $str;
		return $this->execQuery($str);
    }
	
	function updateFormatDynamis()
	{
		$str = "
				UPDATE disposisi_attachment
				SET
					   ".$this->getField("FIELD")." = '".$this->getField("FIELD_VALUE")."'
				WHERE  DISPOSISI_ATTACHMENT_ID = '".$this->getField("DISPOSISI_ATTACHMENT_ID")."'
			 "; 
		$this->query = $str;
		return $this->execQuery($str);
    }
	
	function updateFormat()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "UPDATE disposisi_attachment SET
				  UKURAN= ".$this->getField("UKURAN").", 
				  TIPE= '".$this->getField("TIPE")."'
				WHERE DISPOSISI_ATTACHMENT_ID = '".$this->getField("DISPOSISI_ATTACHMENT_ID")."'
				"; 
				
		$this->query = $str;
		return $this->execQuery($str);
    }
	
	function upload($table, $column, $blob, $id)
	{
		return $this->uploadBlob($table, $column, $blob, $id);
    }
	
	function pindahUrut()
	{				
        $str = "UPDATE disposisi_attachment SET
				  LAMP_NOURUT = '".$this->getField("tmpLampNo")."'
				WHERE DISPOSISI_ATTACHMENT_ID = '".$this->getField("IdLamp")."'				
		"; 				  
		$this->query = $str;
        $this->execQuery($str);
		//echo $str;
		
		$str1 = "UPDATE disposisi_attachment SET
		  LAMP_NOURUT = '".$this->getField("LampNo")."'
		WHERE DISPOSISI_ATTACHMENT_ID = '".$this->getField("tmpIdLamp")."'				
		"; 
		$this->query = $str1;
		//echo $str1;
		return $this->execQuery($str1);
    }
	
	function update_urut()
	{
        $str = "UPDATE disposisi_attachment SET
				  LAMP_NOURUT = '".$this->getField("LAMP_NOURUT")."'
				WHERE DISPOSISI_ATTACHMENT_ID = '".$this->getField("DISPOSISI_ATTACHMENT_ID")."'				
		";
				  
		$this->query = $str;
        $this->execQuery($str);
    }
	
	function delete()
	{
        $str = "DELETE FROM disposisi_attachment
                WHERE 
                  DISPOSISI_ATTACHMENT_ID = '".$this->getField("DISPOSISI_ATTACHMENT_ID")."'"; 
				  
		$this->query = $str;
        return $this->execQuery($str);
    }

    function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $varStatement="")
	{
		$str = "SELECT DISPOSISI_ATTACHMENT_ID , DISPOSISI_ID , ATTACHMENT , CATATAN , UKURAN , TIPE, NAMA
				FROM disposisi_attachment WHERE 1 = 1
				".$varStatement;			    
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$this->query = $str;
		$str .= " ORDER BY DISPOSISI_ATTACHMENT_ID ASC";
		//echo $str;		
		return $this->selectLimit($str,$limit,$from); 
    }
	
    function selectImage($paramsArray=array(),$limit=-1,$from=-1)
	{
		$str = "
					SELECT 
					LAMP_ISI
					FROM disposisi_attachment
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
		$str = "SELECT COUNT(DISPOSISI_ATTACHMENT_ID) AS ROWCOUNT FROM disposisi_attachment WHERE DISPOSISI_ATTACHMENT_ID IS NOT NULL "; 
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
		$str = "SELECT COUNT(DISPOSISI_ATTACHMENT_ID) AS ROWCOUNT FROM disposisi_attachment WHERE DISPOSISI_ATTACHMENT_ID IS NOT NULL "; 
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
		$str = "SELECT COUNT(DISPOSISI_ATTACHMENT_ID) NOURUT FROM disposisi_attachment WHERE DISPOSISI_ATTACHMENT_ID IS NOT NULL ";
		//$str = "SELECT MAX(LAMP_NOURUT) NOURUT FROM disposisi_attachment WHERE LAMP_NOURUT IS NOT NULL "; 
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