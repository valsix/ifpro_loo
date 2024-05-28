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

  class Mailbox extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function Mailbox()
	{
      $this->Entity(); 
    }
	
	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("MAILBOX_ID", $this->getNextId("MAILBOX_ID","MAILBOX")); 
		$str = "
				INSERT INTO MAILBOX (MAILBOX_ID, JUDUL, TANGGAL, NAMA, ISI, LAST_CREATE_USER, LAST_CREATE_DATE) 
				VALUES(
				  ".$this->getField("MAILBOX_ID").",
				  '".$this->getField("JUDUL")."',
				  ".$this->getField("TANGGAL").",
				  '".$this->getField("NAMA")."',
				  '".$this->getField("ISI")."',
				  '".$this->getField("LAST_CREATE_USER")."',
				  CURRENT_DATE
				)"; 
		$this->id = $this->getField("MAILBOX_ID");
		$this->query = $str;
		return $this->execQuery($str);
    }

    function update()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "UPDATE MAILBOX SET
				  	JUDUL		= '".$this->getField("JUDUL")."',
				  	TANGGAL		= ".$this->getField("TANGGAL").",
				  	NAMA		= '".$this->getField("NAMA")."',
				  	ISI			= '".$this->getField("ISI")."',
				  	LAST_UPDATE_USER	= '".$this->getField("LAST_UPDATE_USER")."',
				  	LAST_UPDATE_DATE	= CURRENT_DATE
				WHERE MAILBOX_ID = '".$this->getField("MAILBOX_ID")."'
				"; 
				$this->query = $str;
		return $this->execQuery($str);
    }
	
	function delete()
	{
        $str = "DELETE FROM MAILBOX
                WHERE 
                  MAILBOX_ID = '".$this->getField("MAILBOX_ID")."'"; 
				  
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
				SELECT MAILBOX_ID, JUDUL, TANGGAL, NAMA, ISI
				FROM MAILBOX		
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
				SELECT MAILBOX_ID, JUDUL, TANGGAL, NAMA, ISI
				FROM MAILBOX		
				WHERE 1 = 1
				"; 
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key LIKE '%$val%' ";
		}
		
		$str .= $statement." ORDER BY MAILBOX_ID DESC";
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
		$str = "SELECT COUNT(MAILBOX_ID) AS ROWCOUNT FROM MAILBOX WHERE 1 = 1 ".$statement; 
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
		$str = "SELECT COUNT(MAILBOX_ID) AS ROWCOUNT FROM MAILBOX WHERE 1 = 1 "; 
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