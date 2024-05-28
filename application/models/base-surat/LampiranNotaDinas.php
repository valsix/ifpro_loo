<? 

  /***
  * Entity-base class untuk mengimplementasikan tabel kategori.
  * 
  ***/
  include_once(APPPATH.'/models/Entity.php');

  class LampiranNotaDinas extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function LampiranNotaDinas()
	{
      $this->Entity(); 
    }
	
	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("nota_dinas_attachment_id", $this->getNextId("nota_dinas_attachment_id","nota_dinas_attachment")); 
		
		$str = "		
				INSERT INTO nota_dinas_attachment
				(nota_dinas_attachment_id, nota_dinas_id, attachment, catatan, ukuran, tipe) 
				VALUES ('".$this->getField("nota_dinas_attachment_id")."',
					'".$this->getField("nota_dinas_id")."', '".$this->getField("attachment")."', '".$this->getField("catatan")."',
					'".$this->getField("ukuran")."', '".$this->getField("tipe")."')
				"; 
				
		$this->query = $str;
		//echo $str;
		return $this->execQuery($str);
    }

    function update()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "UPDATE nota_dinas_attachment SET
				  catatan = '".$this->getField("catatan")."'
				WHERE nota_dinas_attachment_id = '".$this->getField("nota_dinas_attachment_id")."'
				"; 
				
		$this->query = $str;
		return $this->execQuery($str);
    }
	
	function pindahUrut()
	{				
        $str = "UPDATE nota_dinas_attachment SET
				  LAMP_NOURUT = '".$this->getField("tmpLampNo")."'
				WHERE nota_dinas_attachment_id = '".$this->getField("IdLamp")."'				
		"; 				  
		$this->query = $str;
        $this->execQuery($str);
		//echo $str;
		
		$str1 = "UPDATE nota_dinas_attachment SET
		  LAMP_NOURUT = '".$this->getField("LampNo")."'
		WHERE nota_dinas_attachment_id = '".$this->getField("tmpIdLamp")."'				
		"; 
		$this->query = $str1;
		//echo $str1;
		return $this->execQuery($str1);
    }
	
	function update_urut()
	{
        $str = "UPDATE nota_dinas_attachment SET
				  LAMP_NOURUT = '".$this->getField("LAMP_NOURUT")."'
				WHERE nota_dinas_attachment_id = '".$this->getField("nota_dinas_attachment_id")."'				
		";
				  
		$this->query = $str;
        $this->execQuery($str);
    }
	
	function delete()
	{
        $str = "DELETE FROM nota_dinas_attachment
                WHERE 
                  nota_dinas_attachment_id = '".$this->getField("nota_dinas_attachment_id")."'"; 
				  
		$this->query = $str;
        return $this->execQuery($str);
    }

    function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $varStatement="")
	{
		$str = "SELECT nota_dinas_attachment_id \"nota_dinas_attachment_id\", nota_dinas_id \"nota_dinas_id\", attachment \"attachment\", catatan \"catatan\", ukuran \"ukuran\", tipe \"tipe\"
				FROM nota_dinas_attachment WHERE 1 = 1
				".$varStatement;			    
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$this->query = $str;
		$str .= " ORDER BY nota_dinas_attachment_id ASC";
		//echo $str;		
		return $this->selectLimit($str,$limit,$from); 
    }
	
    function selectImage($paramsArray=array(),$limit=-1,$from=-1)
	{
		$str = "
					SELECT 
					LAMP_ISI
					FROM nota_dinas_attachment
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
		$str = "SELECT COUNT(nota_dinas_attachment_id) AS ROWCOUNT FROM nota_dinas_attachment WHERE nota_dinas_attachment_id IS NOT NULL "; 
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
		$str = "SELECT COUNT(nota_dinas_attachment_id) AS ROWCOUNT FROM nota_dinas_attachment WHERE nota_dinas_attachment_id IS NOT NULL "; 
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
		$str = "SELECT COUNT(nota_dinas_attachment_id) NOURUT FROM nota_dinas_attachment WHERE nota_dinas_attachment_id IS NOT NULL ";
		//$str = "SELECT MAX(LAMP_NOURUT) NOURUT FROM nota_dinas_attachment WHERE LAMP_NOURUT IS NOT NULL "; 
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