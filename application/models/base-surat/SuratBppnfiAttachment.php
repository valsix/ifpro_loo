<? 

  /***
  * Entity-base class untuk mengimplementasikan tabel kategori.
  * 
  ***/
  include_once(APPPATH.'/models/Entity.php');

  class SuratBppnfiAttachment extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function SuratBppnfiAttachment()
	{
      $this->Entity(); 
    }
	
	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("SURAT_BPPNFI_ATTACHMENT_ID", $this->getNextId("SURAT_BPPNFI_ATTACHMENT_ID","surat_bppnfi_attachment")); 
		
		$str = "		
				INSERT INTO surat_bppnfi_attachment
				(SURAT_BPPNFI_ATTACHMENT_ID, SURAT_BPPNFI_ID, NAMA, UKURAN, TIPE, LAST_CREATE_USER, LAST_CREATE_DATE) 
				VALUES ('".$this->getField("SURAT_BPPNFI_ATTACHMENT_ID")."',
					'".$this->getField("SURAT_BPPNFI_ID")."', 
					'".$this->getField("NAMA")."',
					'".$this->getField("UKURAN")."', 
					'".$this->getField("TIPE")."',
				  	'".$this->getField("LAST_CREATE_USER")."',
				  	CURRENT_DATE
				)
				"; 
				
		$this->query = $str;
		$this->id= $this->getField("SURAT_BPPNFI_ATTACHMENT_ID");
		// echo $str;
		return $this->execQuery($str);
    }
	
	function insertArsip()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("LAMP_ID", $this->getNextId("LAMP_ID","arsip.ARSIP_LAMPIRAN")); 
		
		$str = "
			INSERT INTO arsip.ARSIP_LAMPIRAN
			(
			   ARSIP_TAHUN, ARSIP_ID, LAMP_ID, LAMP_NOURUT, LAMP_NAMA, LAMP_ISI, LAMP_KETERANGAN, LAMP_TIPE, LAMP_UKURAN, SURAT_BPPNFI_ATTACHMENT_ID, SURAT_BPPNFI_ID
			)
			SELECT ARSIP_TAHUN, ARSIP_ID, ".$this->getField("LAMP_ID").", ROW_NUMBER() OVER (), CATATAN, ATTACHMENT, CATATAN, TIPE, UKURAN, SURAT_BPPNFI_ATTACHMENT_ID, A.SURAT_BPPNFI_ID
				FROM SURAT_BPPNFI_ATTACHMENT A
				LEFT JOIN arsip.ARSIP B ON A.SURAT_BPPNFI_ID = B.SURAT_BPPNFI_ID
			WHERE A.SURAT_BPPNFI_ID =  ".$this->getField("SURAT_BPPNFI_ID")."
            ";
				
		$this->query = $str;
		$this->id = $this->getField("LAMP_ID");
		return $this->execQuery($str);
    }

    function update()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "UPDATE surat_bppnfi_attachment SET
				  	CATATAN 			= '".$this->getField("CATATAN")."',
				  	LAST_UPDATE_USER	= '".$this->getField("LAST_UPDATE_USER")."',
				  	LAST_UPDATE_DATE	= CURRENT_DATE
				WHERE SURAT_BPPNFI_ATTACHMENT_ID = '".$this->getField("SURAT_BPPNFI_ATTACHMENT_ID")."'
				"; 
				
		$this->query = $str;
		return $this->execQuery($str);
    }
	
	function updateFormatDynamis()
	{
		$str = "
				UPDATE surat_bppnfi_attachment
				SET
					   ".$this->getField("FIELD")." = '".$this->getField("FIELD_VALUE")."'
				WHERE  SURAT_BPPNFI_ATTACHMENT_ID = '".$this->getField("SURAT_BPPNFI_ATTACHMENT_ID")."'
			 "; 
		$this->query = $str;
		return $this->execQuery($str);
    }
	
	function updateFormat()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "UPDATE surat_bppnfi_attachment SET
				  UKURAN= ".$this->getField("UKURAN").", 
				  TIPE= '".$this->getField("TIPE")."'
				WHERE SURAT_BPPNFI_ATTACHMENT_ID = '".$this->getField("SURAT_BPPNFI_ATTACHMENT_ID")."'
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
        $str = "UPDATE surat_bppnfi_attachment 
				SET
				  	LAMP_NOURUT = '".$this->getField("tmpLampNo")."'
				WHERE SURAT_BPPNFI_ATTACHMENT_ID = '".$this->getField("IdLamp")."'				
		"; 				  
		$this->query = $str;
        $this->execQuery($str);
		//echo $str;
		
		$str1 = "UPDATE surat_bppnfi_attachment SET
		  LAMP_NOURUT = '".$this->getField("LampNo")."'
		WHERE SURAT_BPPNFI_ATTACHMENT_ID = '".$this->getField("tmpIdLamp")."'				
		"; 
		$this->query = $str1;
		//echo $str1;
		return $this->execQuery($str1);
    }
	
	function update_urut()
	{
        $str = "UPDATE surat_bppnfi_attachment SET
				  LAMP_NOURUT = '".$this->getField("LAMP_NOURUT")."'
				WHERE SURAT_BPPNFI_ATTACHMENT_ID = '".$this->getField("SURAT_BPPNFI_ATTACHMENT_ID")."'				
		";
				  
		$this->query = $str;
        $this->execQuery($str);
    }
	
	function delete()
	{
        $str = "DELETE FROM surat_bppnfi_attachment
                WHERE 
                  SURAT_BPPNFI_ATTACHMENT_ID = '".$this->getField("SURAT_BPPNFI_ATTACHMENT_ID")."'"; 
				  
		$this->query = $str;
        return $this->execQuery($str);
    }

    function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $varStatement="")
	{
		$str = "SELECT SURAT_BPPNFI_ATTACHMENT_ID, SURAT_BPPNFI_ID, ATTACHMENT, NAMA, UKURAN , TIPE
				FROM surat_bppnfi_attachment WHERE 1 = 1
				".$varStatement;			    
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$this->query = $str;
		$str .= " ORDER BY SURAT_BPPNFI_ATTACHMENT_ID ASC";
		//echo $str;		
		return $this->selectLimit($str,$limit,$from); 
    }
	
    function selectImage($paramsArray=array(),$limit=-1,$from=-1)
	{
		$str = "
					SELECT 
					LAMP_ISI
					FROM surat_bppnfi_attachment
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
		$str = "SELECT COUNT(SURAT_BPPNFI_ATTACHMENT_ID) AS ROWCOUNT FROM surat_bppnfi_attachment WHERE SURAT_BPPNFI_ATTACHMENT_ID IS NOT NULL "; 
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
		$str = "SELECT COUNT(SURAT_BPPNFI_ATTACHMENT_ID) AS ROWCOUNT FROM surat_bppnfi_attachment WHERE SURAT_BPPNFI_ATTACHMENT_ID IS NOT NULL "; 
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
		$str = "SELECT COUNT(SURAT_BPPNFI_ATTACHMENT_ID) NOURUT FROM surat_bppnfi_attachment WHERE SURAT_BPPNFI_ATTACHMENT_ID IS NOT NULL ";
		//$str = "SELECT MAX(LAMP_NOURUT) NOURUT FROM surat_bppnfi_attachment WHERE LAMP_NOURUT IS NOT NULL "; 
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