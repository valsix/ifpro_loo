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
  include_once("Entity.php");

  class NaskahTemplate extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function NaskahTemplate()
	{
      $this->Entity(); 
    }
	
	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("NASKAH_TEMPLATE_ID", $this->getNextId("NASKAH_TEMPLATE_ID","NASKAH_TEMPLATE")); 
		$str = "
				INSERT INTO NASKAH_TEMPLATE (
				   NASKAH_TEMPLATE_ID, NAMA, KODE, LINK_URL, 
				   LAST_CREATE_USER, LAST_CREATE_DATE) 
				VALUES (
					'".$this->getField("NASKAH_TEMPLATE_ID")."', 
					'".$this->getField("NAMA")."', 
					'".$this->getField("KODE")."', 
					'".$this->getField("LINK_URL")."', 
					'".$this->getField("LAST_CREATE_USER")."', 
					CURRENT_DATE)"; 
		$this->id = $this->getField("NASKAH_TEMPLATE_ID");
		$this->query = $str;

		return $this->execQuery($str);
    }
	
    function update()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "
			   UPDATE NASKAH_TEMPLATE
				SET    NAMA         		= '".$this->getField("NAMA")."',
					   KODE      			= '".$this->getField("KODE")."',
					   LINK_URL      		= '".$this->getField("LINK_URL")."',
					   LAST_UPDATE_USER   	= '".$this->getField("LAST_UPDATE_USER")."',
					   LAST_UPDATE_DATE   	= CURRENT_DATE
				WHERE  NASKAH_TEMPLATE_ID   = '".$this->getField("NASKAH_TEMPLATE_ID")."'
				"; 
				$this->query = $str;
		return $this->execQuery($str);
    }

    function updateByField()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "UPDATE NASKAH_TEMPLATE A SET
				  ".$this->getField("FIELD")." 		= '".$this->getField("FIELD_VALUE")."'
				WHERE NASKAH_TEMPLATE_ID = ".$this->getField("NASKAH_TEMPLATE_ID")."
				"; 
				$this->query = $str;
		return $this->execQuery($str);
    }	
	
	function delete()
	{
        $str = "DELETE FROM NASKAH_TEMPLATE
                WHERE 
                  NASKAH_TEMPLATE_ID = ".$this->getField("NASKAH_TEMPLATE_ID").""; 
				  
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
    function selectByParams($paramsArray=array(),$limit=-1,$from=-1,$statement="", $order=" ORDER BY NASKAH_TEMPLATE_ID ASC")
	{
		$str = "
				SELECT 
				NASKAH_TEMPLATE_ID, NAMA, KODE, LINK_URL 
				FROM NASKAH_TEMPLATE A
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
	
	
    function selectByParamsTemplate($paramsArray=array(),$limit=-1,$from=-1,$statement="", $order=" ORDER BY LINK_URL ASC")
	{
		$str = "
				SELECT 
				LINK_URL
				FROM TEMPLATE_URL A
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
				NASKAH_TEMPLATE_ID, NAMA, KODE
				FROM NASKAH_TEMPLATE A
				WHERE 1 = 1
			"; 
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key LIKE '%$val%' ";
		}
		
		$str .= $statement." ORDER BY NASKAH_TEMPLATE_ID DESC";
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
		$str = "SELECT COUNT(NASKAH_TEMPLATE_ID) AS ROWCOUNT FROM NASKAH_TEMPLATE A WHERE 1 = 1 ".$statement; 
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
		$str = "SELECT COUNT(NASKAH_TEMPLATE_ID) AS ROWCOUNT FROM NASKAH_TEMPLATE WHERE 1 = 1 "; 
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