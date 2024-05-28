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

  class DaftarAlamatGroup extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function DaftarAlamatGroup()
	{
      $this->Entity(); 
    }
	
	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("DAFTAR_ALAMAT_GROUP_ID", $this->getNextId("DAFTAR_ALAMAT_GROUP_ID","DAFTAR_ALAMAT_GROUP")); 
		$str = "
				INSERT INTO DAFTAR_ALAMAT_GROUP (DAFTAR_ALAMAT_GROUP_ID, NAMA, LAST_CREATE_USER, LAST_CREATE_DATE) 
				VALUES(
				  ".$this->getField("DAFTAR_ALAMAT_GROUP_ID").",
				  '".$this->getField("NAMA")."',
				  '".$this->getField("LAST_CREATE_USER")."',
				  CURRENT_DATE
				)"; 
		$this->id = $this->getField("DAFTAR_ALAMAT_GROUP_ID");
		$this->query = $str;
		return $this->execQuery($str);
    }
	
    function update()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "UPDATE DAFTAR_ALAMAT_GROUP 
				SET
				  NAMA				= '".$this->getField("NAMA")."',
				  HP				= '".$this->getField("HP")."',
				  LAST_UPDATE_USER	= '".$this->getField("LAST_UPDATE_USER")."',
				  LAST_UPDATE_DATE	= CURRENT_DATE
				WHERE DAFTAR_ALAMAT_GROUP_ID = ".$this->getField("DAFTAR_ALAMAT_GROUP_ID")."
				"; 
				$this->query = $str;
		return $this->execQuery($str);
    }
	
	function delete()
	{
        $str = "DELETE FROM DAFTAR_ALAMAT_GROUP
                WHERE 
                  DAFTAR_ALAMAT_GROUP_ID = ".$this->getField("DAFTAR_ALAMAT_GROUP_ID").""; 
				  
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
				DAFTAR_ALAMAT_GROUP_ID, NAMA
				FROM DAFTAR_ALAMAT_GROUP		
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
				DAFTAR_ALAMAT_GROUP_ID, NAMA
				FROM DAFTAR_ALAMAT_GROUP		
				WHERE 1 = 1
				"; 
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key LIKE '%$val%' ";
		}
		
		$str .= $statement." ORDER BY DAFTAR_ALAMAT_GROUP_ID DESC";
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
		$str = "SELECT COUNT(DAFTAR_ALAMAT_GROUP_ID) AS ROWCOUNT FROM DAFTAR_ALAMAT_GROUP WHERE 1 = 1 ".$statement; 
		while(list($key,$val)=each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$this->select($str); 
		$this->query = $str;		
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0; 
    }

    function getCountByParamsLike($paramsArray=array())
	{
		$str = "SELECT COUNT(DAFTAR_ALAMAT_GROUP_ID) AS ROWCOUNT FROM DAFTAR_ALAMAT_GROUP WHERE 1 = 1 "; 
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