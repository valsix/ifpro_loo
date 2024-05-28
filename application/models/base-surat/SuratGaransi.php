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

  class SuratGaransi extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function SuratGaransi()
	{
      $this->Entity(); 
    }
	
	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("SURAT_GARANSI_ID", $this->getNextId("SURAT_GARANSI_ID","SURAT_GARANSI")); 
		$str = "
				INSERT INTO SURAT_GARANSI (
				   SURAT_GARANSI_ID, SURAT_BPPNFI_ID, GUEST_NAME, CHECK_IN, CHECK_OUT, 
       			   ROOM_TYPE, NO_OF_ROOM, ROOM_RATE, REMARKS, LAST_CREATE_USER, LAST_CREATE_DATE) 
				VALUES(
				  ".$this->getField("SURAT_GARANSI_ID").",
				  ".$this->getField("SURAT_BPPNFI_ID").",
				  '".$this->getField("GUEST_NAME")."',
				  ".$this->getField("CHECK_IN").",
				  ".$this->getField("CHECK_OUT").",
				  '".$this->getField("ROOM_TYPE")."',
				  '".$this->getField("NO_OF_ROOM")."',
				  '".$this->getField("ROOM_RATE")."',
				  '".$this->getField("REMARKS")."',
				  '".$this->getField("LAST_CREATE_USER")."',
				  CURRENT_DATE
				)"; 
		$this->id = $this->getField("SURAT_GARANSI_ID");
		$this->query = $str;
		return $this->execQuery($str);
    }
	
    function update()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "UPDATE SURAT_GARANSI SET
				  GUEST_NAME		= '".$this->getField("GUEST_NAME")."',
				  CHECK_IN			= ".$this->getField("CHECK_IN").",
				  CHECK_OUT			= ".$this->getField("CHECK_OUT").",
				  ROOM_TYPE			= '".$this->getField("ROOM_TYPE")."',
				  NO_OF_ROOM		= '".$this->getField("NO_OF_ROOM")."',
				  ROOM_RATE			= '".$this->getField("ROOM_RATE")."',
				  REMARKS			= '".$this->getField("REMARKS")."',
				  LAST_UPDATE_USER	= '".$this->getField("LAST_UPDATE_USER")."',
				  LAST_UPDATE_DATE	= CURRENT_DATE
				WHERE SURAT_GARANSI_ID 	= '".$this->getField("SURAT_GARANSI_ID")."'
				"; 
				$this->query = $str;
		return $this->execQuery($str);
    }
	
	function delete()
	{
        $str = "DELETE FROM SURAT_GARANSI
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
				SURAT_GARANSI_ID, SURAT_BPPNFI_ID, GUEST_NAME, CHECK_IN, CHECK_OUT, 
       			   ROOM_TYPE, NO_OF_ROOM, ROOM_RATE, REMARKS
				FROM SURAT_GARANSI A
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
				SURAT_GARANSI_ID, SURAT_BPPNFI_ID, GUEST_NAME, CHECK_IN, CHECK_OUT, 
       			   ROOM_TYPE, NO_OF_ROOM, ROOM_RATE, REMARKS
				FROM SURAT_GARANSI
				WHERE 1 = 1
				"; 
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key LIKE '%$val%' ";
		}
		
		$str .= $statement." ORDER BY SURAT_GARANSI_ID DESC";
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
		$str = "SELECT COUNT(1) AS ROWCOUNT FROM SURAT_GARANSI WHERE 1 = 1 ".$statement; 
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
		$str = "SELECT COUNT(1) AS ROWCOUNT FROM SURAT_GARANSI WHERE 1 = 1 "; 
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