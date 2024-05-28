<? 

  /***
  * Entity-base class untuk mengimplementasikan tabel kategori.
  * 
  ***/
  include_once(APPPATH.'/models/Entity.php');

  class LampiranNotaDinasDisposisi extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function LampiranNotaDinasDisposisi()
	{
      $this->Entity(); 
    }
	
	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("NOTA_DINAS_DISPOSISI_ATTACH_ID", $this->getNextId("NOTA_DINAS_DISPOSISI_ATTACH_ID","NOTA_DINAS_DISPOSISI_ATTACH")); 
		
		$str = "		
				INSERT INTO NOTA_DINAS_DISPOSISI_ATTACH
				(NOTA_DINAS_DISPOSISI_ATTACH_ID, NOTA_DINAS_DISPOSISI_ID, CATATAN, UKURAN, TIPE) 
				VALUES ('".$this->getField("NOTA_DINAS_DISPOSISI_ATTACH_ID")."',
					'".$this->getField("NOTA_DINAS_DISPOSISI_ID")."', '".$this->getField("CATATAN")."',
					'".$this->getField("UKURAN")."', '".$this->getField("TIPE")."')
				"; 
				
		$this->query = $str;
		$this->id= $this->getField("NOTA_DINAS_DISPOSISI_ATTACH_ID");
		//echo $str;
		return $this->execQuery($str);
    }

    function update()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "UPDATE NOTA_DINAS_DISPOSISI_ATTACH SET
				  CATATAN = '".$this->getField("catatan")."'
				WHERE NOTA_DINAS_DISPOSISI_ATTACH_ID = '".$this->getField("NOTA_DINAS_DISPOSISI_ATTACH_ID")."'
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
        $str = "UPDATE NOTA_DINAS_DISPOSISI_ATTACH SET
				  LAMP_NOURUT = '".$this->getField("tmpLampNo")."'
				WHERE NOTA_DINAS_DISPOSISI_ATTACH_ID = '".$this->getField("IdLamp")."'				
		"; 				  
		$this->query = $str;
        $this->execQuery($str);
		//echo $str;
		
		$str1 = "UPDATE NOTA_DINAS_DISPOSISI_ATTACH SET
		  LAMP_NOURUT = '".$this->getField("LampNo")."'
		WHERE NOTA_DINAS_DISPOSISI_ATTACH_ID = '".$this->getField("tmpIdLamp")."'				
		"; 
		$this->query = $str1;
		//echo $str1;
		return $this->execQuery($str1);
    }
	
	function update_urut()
	{
        $str = "UPDATE NOTA_DINAS_DISPOSISI_ATTACH SET
				  LAMP_NOURUT = '".$this->getField("LAMP_NOURUT")."'
				WHERE NOTA_DINAS_DISPOSISI_ATTACH_ID = '".$this->getField("NOTA_DINAS_DISPOSISI_ATTACH_ID")."'				
		";
				  
		$this->query = $str;
        $this->execQuery($str);
    }
	
	function delete()
	{
        $str = "DELETE FROM NOTA_DINAS_DISPOSISI_ATTACH
                WHERE 
                  NOTA_DINAS_DISPOSISI_ATTACH_ID = '".$this->getField("NOTA_DINAS_DISPOSISI_ATTACH_ID")."'"; 
				  
		$this->query = $str;
        return $this->execQuery($str);
    }

    function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $varStatement="")
	{
		$str = "SELECT NOTA_DINAS_DISPOSISI_ATTACH_ID , NOTA_DINAS_DISPOSISI_ID , ATTACHMENT , CATATAN , UKURAN , TIPE
				FROM NOTA_DINAS_DISPOSISI_ATTACH WHERE 1 = 1
				".$varStatement;			    
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$this->query = $str;
		$str .= " ORDER BY NOTA_DINAS_DISPOSISI_ATTACH_ID ASC";
		//echo $str;		
		return $this->selectLimit($str,$limit,$from); 
    }
	
    function selectImage($paramsArray=array(),$limit=-1,$from=-1)
	{
		$str = "
					SELECT 
					LAMP_ISI
					FROM NOTA_DINAS_DISPOSISI_ATTACH
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
		$str = "SELECT COUNT(NOTA_DINAS_DISPOSISI_ATTACH_ID) AS ROWCOUNT FROM NOTA_DINAS_DISPOSISI_ATTACH WHERE NOTA_DINAS_DISPOSISI_ATTACH_ID IS NOT NULL "; 
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
		$str = "SELECT COUNT(NOTA_DINAS_DISPOSISI_ATTACH_ID) AS ROWCOUNT FROM NOTA_DINAS_DISPOSISI_ATTACH WHERE NOTA_DINAS_DISPOSISI_ATTACH_ID IS NOT NULL "; 
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
		$str = "SELECT COUNT(NOTA_DINAS_DISPOSISI_ATTACH_ID) NOURUT FROM NOTA_DINAS_DISPOSISI_ATTACH WHERE NOTA_DINAS_DISPOSISI_ATTACH_ID IS NOT NULL ";
		//$str = "SELECT MAX(LAMP_NOURUT) NOURUT FROM NOTA_DINAS_DISPOSISI_ATTACH WHERE LAMP_NOURUT IS NOT NULL "; 
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