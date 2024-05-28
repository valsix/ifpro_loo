<? 

  /***
  * Entity-base class untuk mengimplementasikan tabel kategori.
  * 
  ***/
  include_once(APPPATH.'/models/Entity.php');

  class Lampiran extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function Lampiran()
	{
      $this->Entity(); 
    }
	
	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("SURAT_MASUK_ATTACHMENT_ID", $this->getNextId("SURAT_MASUK_ATTACHMENT_ID","surat_masuk_attachment")); 
		
		$str = "		
				INSERT INTO surat_masuk_attachment
					(SURAT_MASUK_ATTACHMENT_ID, SURAT_MASUK_ID, NAMA, NO_URUT, CATATAN, UKURAN, TIPE, LAST_CREATE_USER, LAST_CREATE_DATE) 
				VALUES ('".$this->getField("SURAT_MASUK_ATTACHMENT_ID")."',
					'".$this->getField("SURAT_MASUK_ID")."', 
					'".$this->getField("NAMA")."',
					".$this->getField("NO_URUT").",
					'".$this->getField("CATATAN")."',
					".$this->getField("UKURAN").", 
					'".$this->getField("TIPE")."',
				  	'".$this->getField("LAST_CREATE_USER")."',
				  	CURRENT_DATE
				)
				"; 
				
		$this->query = $str;
		$this->id= $this->getField("SURAT_MASUK_ATTACHMENT_ID");
		// echo $str; exit();
		return $this->execQuery($str);
    }
	
	function insertMobile()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("SURAT_MASUK_ATTACHMENT_ID", $this->getNextId("SURAT_MASUK_ATTACHMENT_ID","surat_masuk_attachment")); 
		
		$str = "		
				INSERT INTO surat_masuk_attachment
					(SURAT_MASUK_ATTACHMENT_ID, SURAT_MASUK_ID, ATTACHMENT, NAMA, NO_URUT, CATATAN, UKURAN, TIPE, LAST_CREATE_USER, LAST_CREATE_DATE) 
				VALUES ('".$this->getField("SURAT_MASUK_ATTACHMENT_ID")."',
					'".$this->getField("SURAT_MASUK_ID")."', 
					'".$this->getField("ATTACHMENT")."',
					'".$this->getField("NAMA")."',
					".$this->getField("NO_URUT").",
					'".$this->getField("CATATAN")."',
					".$this->getField("UKURAN").", 
					'".$this->getField("TIPE")."',
				  	'".$this->getField("LAST_CREATE_USER")."',
				  	CURRENT_DATE
				)
				"; 
				
		$this->query = $str;
		$this->id= $this->getField("SURAT_MASUK_ATTACHMENT_ID");
		
		return $this->execQuery($str);
    }
	
	function insertArsip()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("LAMP_ID", $this->getNextId("LAMP_ID","arsip.ARSIP_LAMPIRAN")); 
		
		$str = "
			INSERT INTO arsip.ARSIP_LAMPIRAN(
			   ARSIP_TAHUN, ARSIP_ID, LAMP_ID, LAMP_NOURUT, LAMP_NAMA, LAMP_ISI, LAMP_KETERANGAN, LAMP_TIPE, LAMP_UKURAN, SURAT_MASUK_ATTACHMENT_ID, SURAT_MASUK_ID)
			SELECT ARSIP_TAHUN, ARSIP_ID, ".$this->getField("LAMP_ID").", NO_URUT, NAMA, ATTACHMENT, CATATAN, TIPE, UKURAN, SURAT_MASUK_ATTACHMENT_ID, A.SURAT_MASUK_ID
				FROM SURAT_MASUK_ATTACHMENT A
				LEFT JOIN ARSIP.ARSIP B ON A.SURAT_MASUK_ID = B.SURAT_MASUK_ID
			WHERE A.SURAT_MASUK_ID =  ".$this->getField("SURAT_MASUK_ID")."
            ";
				
		$this->query = $str;
		$this->id = $this->getField("LAMP_ID");
		return $this->execQuery($str);
    }
	
	function insertArsipLampiran()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("LAMP_ID", $this->getNextId("LAMP_ID","arsip.ARSIP_LAMPIRAN")); 
		
		$str = "
			INSERT INTO arsip.ARSIP_LAMPIRAN(
			   ARSIP_TAHUN, ARSIP_ID, LAMP_ID, LAMP_NOURUT, LAMP_NAMA, LAMP_ISI, LAMP_KETERANGAN, LAMP_TIPE, LAMP_UKURAN, SURAT_MASUK_ATTACHMENT_ID, SURAT_MASUK_ID)
			SELECT ARSIP_TAHUN, ARSIP_ID, ".$this->getField("LAMP_ID").", NO_URUT, NAMA, ATTACHMENT, CATATAN, TIPE, UKURAN, SURAT_MASUK_ATTACHMENT_ID, A.SURAT_MASUK_ID
				FROM SURAT_MASUK_ATTACHMENT A
				LEFT JOIN ARSIP.ARSIP B ON A.SURAT_MASUK_ID = B.SURAT_MASUK_ID
			WHERE A.SURAT_MASUK_ID =  ".$this->getField("SURAT_MASUK_ID")." AND A.SURAT_MASUK_ATTACHMENT_ID = ".$this->getField("SURAT_MASUK_ATTACHMENT_ID")."
            ";
				
		$this->query = $str;
		$this->id = $this->getField("LAMP_ID");
		return $this->execQuery($str);
    }
	
	function updateFormatDynamis()
	{
		$str = "
				UPDATE surat_masuk_attachment
				SET
					   ".$this->getField("FIELD")." = '".$this->getField("FIELD_VALUE")."'
				WHERE  SURAT_MASUK_ATTACHMENT_ID = '".$this->getField("SURAT_MASUK_ATTACHMENT_ID")."'
			 "; 
		$this->query = $str;
		return $this->execQuery($str);
    }
	
	function upload($table, $column, $blob, $id)
	{
		return $this->uploadBlob($table, $column, $blob, $id);
    }
	
	function updateFormat()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "UPDATE surat_masuk_attachment SET
				  UKURAN= ".$this->getField("UKURAN").", 
				  TIPE= '".$this->getField("TIPE")."'
				WHERE SURAT_MASUK_ATTACHMENT_ID = '".$this->getField("SURAT_MASUK_ATTACHMENT_ID")."'
				"; 
				
		$this->query = $str;
		return $this->execQuery($str);
    }
	
    function update()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "UPDATE surat_masuk_attachment 
				SET
					NAMA 						= '".$this->getField("NAMA")."',
				  	CATATAN 					= '".$this->getField("CATATAN")."',
			  		LAST_UPDATE_USER			= '".$this->getField("LAST_UPDATE_USER")."',
			   		LAST_UPDATE_DATE			= CURRENT_DATE
				WHERE SURAT_MASUK_ATTACHMENT_ID = '".$this->getField("SURAT_MASUK_ATTACHMENT_ID")."'
				"; 
				
		$this->query = $str;
		return $this->execQuery($str);
    }
	
	function updateArsipLampiran()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "UPDATE arsip.ARSIP_LAMPIRAN A
				SET LAMP_NAMA 			= B.NAMA, 
					LAMP_KETERANGAN		= B.CATATAN
				FROM (SELECT A.SURAT_MASUK_ID, SURAT_MASUK_ATTACHMENT_ID, NO_URUT, TIPE, NAMA, CATATAN, UKURAN, ATTACHMENT
					FROM SURAT_MASUK_ATTACHMENT A) AS B
				WHERE A.SURAT_MASUK_ATTACHMENT_ID = B.SURAT_MASUK_ATTACHMENT_ID AND A.SURAT_MASUK_ID = B.SURAT_MASUK_ID
				AND A.SURAT_MASUK_ID = ".$this->getField("SURAT_MASUK_ID")."
				AND A.SURAT_MASUK_ATTACHMENT_ID = ".$this->getField("SURAT_MASUK_ATTACHMENT_ID")."
				"; 
				$this->query = $str;
		//echo $str;
		return $this->execQuery($str);
    }
	
	function updateContent()
	{
		$str = "UPDATE content SET
				  parent_CID = '".$this->getField("parent_CID")."',
				  urut = '".$this->getField("urut")."',
				  nama = '".$this->getField("nama")."',
				  keterangan = '".$this->getField("keterangan")."',
				  isi = '".$this->getField("isi")."',
				  link_url = '".$this->getField("link_url")."',
				  status_content_menu = '".$this->getField("status_content_menu")."',
				  status_locked = '".$this->getField("status_locked")."'
				WHERE CID = '".$this->getField("CID")."'
				"; 
				
		$this->query = $str;
		return $this->execQuery($str);
	}
	
	function updateStatusContentMenu($CID, $value)
	{
		$str = "UPDATE content SET
				  status_content_menu = '".$value."'
				WHERE CID = '".$CID."'
				"; 
				
		$this->query = $str;
		return $this->execQuery($str);
	}
	
	function updateUrut($CID, $value)
	{
		$str = "UPDATE content SET
				  urut = '".$value."'
				WHERE CID = '".$CID."'
				"; 
				
		$this->query = $str;
		return $this->execQuery($str);
	}
	
	function pindahUrut()
	{				
        $str = "UPDATE surat_masuk_attachment SET
				  LAMP_NOURUT = '".$this->getField("tmpLampNo")."'
				WHERE SURAT_MASUK_ATTACHMENT_ID = '".$this->getField("IdLamp")."'				
		"; 				  
		$this->query = $str;
        $this->execQuery($str);
		//echo $str;
		
		$str1 = "UPDATE surat_masuk_attachment SET
		  LAMP_NOURUT = '".$this->getField("LampNo")."'
		WHERE SURAT_MASUK_ATTACHMENT_ID = '".$this->getField("tmpIdLamp")."'				
		"; 
		$this->query = $str1;
		//echo $str1;
		return $this->execQuery($str1);
    }
	
	function update_urut()
	{
        $str = "UPDATE surat_masuk_attachment SET
				  LAMP_NOURUT = '".$this->getField("LAMP_NOURUT")."'
				WHERE SURAT_MASUK_ATTACHMENT_ID = '".$this->getField("SURAT_MASUK_ATTACHMENT_ID")."'				
		";
				  
		$this->query = $str;
        $this->execQuery($str);
    }
	
	function delete()
	{
        $str = "DELETE FROM arsip.arsip_lampiran
                WHERE 
                  SURAT_MASUK_ATTACHMENT_ID = '".$this->getField("SURAT_MASUK_ATTACHMENT_ID")."'"; 
		$this->execQuery($str);
		
        $str1 = "DELETE FROM surat_masuk_attachment
                WHERE 
                  SURAT_MASUK_ATTACHMENT_ID = '".$this->getField("SURAT_MASUK_ATTACHMENT_ID")."'"; 
				  
		$this->query = $str1;
        return $this->execQuery($str1);
    }

    function deleteByParent()
	{
        $str = "DELETE FROM arsip.arsip_lampiran
                WHERE 
                  SURAT_MASUK_ID = '".$this->getField("SURAT_MASUK_ID")."'"; 
		$this->execQuery($str);
		
        $str1 = "DELETE FROM surat_masuk_attachment
                WHERE 
                  SURAT_MASUK_ID = '".$this->getField("SURAT_MASUK_ID")."'"; 
				  
		$this->query = $str1;
        return $this->execQuery($str1);
    }

    function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $varStatement="")
	{
		$str = "SELECT SURAT_MASUK_ATTACHMENT_ID, SURAT_MASUK_ID, ATTACHMENT, CATATAN, UKURAN, TIPE, NAMA, NO_URUT
				FROM surat_masuk_attachment WHERE 1 = 1
				".$varStatement;			    
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= " ORDER BY SURAT_MASUK_ATTACHMENT_ID ASC";
		$this->query = $str;
		// echo $str;		exit();
		return $this->selectLimit($str,$limit,$from); 
    }
	
    function selectImage($paramsArray=array(),$limit=-1,$from=-1)
	{
		$str = "
					SELECT 
					LAMP_ISI
					FROM surat_masuk_attachment
					WHERE 1 = 1
			   "; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		$this->query = $str;
				
		return $this->selectLimit($str,$limit,$from); 
    }	
    
	function selectByParamsLike($paramsArray=array(),$limit=-1,$from=-1)
	{
		$str = "SELECT CID, parent_CID, urut, nama, keterangan, isi, link_url, status_content_menu, status_locked
				FROM content WHERE CID IS NOT NULL"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key LIKE '%$val%' ";
		}
		
		$this->query = $str;
		$str .= " ORDER BY urut ASC, nama ASC";
				
		return $this->selectLimit($str,$limit,$from);
    }	
   
    function getCountByParams($paramsArray=array())
	{
		$str = "SELECT COUNT(SURAT_MASUK_ATTACHMENT_ID) AS ROWCOUNT FROM surat_masuk_attachment WHERE SURAT_MASUK_ATTACHMENT_ID IS NOT NULL "; 
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
		$str = "SELECT COUNT(CID) AS ROWCOUNT FROM content WHERE CID IS NOT NULL "; 
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
		$str = "SELECT MAX(NO_URUT) NOURUT FROM surat_masuk_attachment WHERE NO_URUT IS NOT NULL "; 
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

	function getContentTitle($varCID)
	{
		$this->selectByParams(array('CID' => $varCID));
		$this->firstRow();
		
		return $this->getField('nama');
	}
	
	function getContentText($varCID)
	{
		$this->selectByParams(array('CID' => $varCID));
		$this->firstRow();
		
		return $this->getField('keterangan');
	}
	
	function getContent($varCID)
	{
		$this->selectByParams(array('CID' => $varCID));
		$this->firstRow();
		
		return $this->getField('isi');
	}
	
  } 
?>