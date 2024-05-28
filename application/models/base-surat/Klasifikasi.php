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

  class Klasifikasi extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function Klasifikasi()
	{
      $this->Entity(); 
    }
	
	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		//$this->setField("KLASIFIKASI_ID", $this->getNextId("KLASIFIKASI_ID","KLASIFIKASI")); 
		$str = "
				INSERT INTO KLASIFIKASI (
				   KLASIFIKASI_ID, KLASIFIKASI_ID_PARENT, NAMA, 
				   KETERANGAN, NO_URUT, KODE, LAST_CREATE_USER, LAST_CREATE_DATE) 
				VALUES(
				  KLASIFIKASI_ID_GENERATE('".$this->getField("KLASIFIKASI_ID")."'),
				  '".$this->getField("KLASIFIKASI_ID")."',
				  '".$this->getField("NAMA")."',
				  '".$this->getField("KETERANGAN")."',
				  ".$this->getField("NO_URUT").",
				  '".$this->getField("KODE")."',
				  '".$this->getField("LAST_CREATE_USER")."',
				  CURRENT_DATE
				)"; 
		$this->id = $this->getField("KLASIFIKASI_ID");
		$this->query = $str;
		return $this->execQuery($str);
    }

    function update()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "UPDATE KLASIFIKASI SET
				  NAMA 				= '".$this->getField("NAMA")."',
				  KETERANGAN 		= '".$this->getField("KETERANGAN")."',
				  KODE 				= '".$this->getField("KODE")."',
				  LAST_UPDATE_USER	= '".$this->getField("LAST_UPDATE_USER")."',
				  LAST_UPDATE_DATE	= CURRENT_DATE
				WHERE KLASIFIKASI_ID = '".$this->getField("KLASIFIKASI_ID")."'
				"; 
				$this->query = $str;
		return $this->execQuery($str);
    }
	
	function delete()
	{
        $str = "DELETE FROM KLASIFIKASI
                WHERE 
                  KLASIFIKASI_ID LIKE '".$this->getField("KLASIFIKASI_ID")."%'"; 
				  
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
				KLASIFIKASI_ID, KLASIFIKASI_ID_PARENT, NAMA, KETERANGAN, NO_URUT, KODE
				FROM KLASIFIKASI
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
				KLASIFIKASI_ID, KLASIFIKASI_ID_PARENT, NAMA, KETERANGAN, NO_URUT
				FROM KLASIFIKASI
				WHERE 1 = 1
				"; 
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key LIKE '%$val%' ";
		}
		
		$str .= $statement." ORDER BY KLASIFIKASI_ID DESC";
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
		$str = "SELECT COUNT(KLASIFIKASI_ID) AS ROWCOUNT FROM KLASIFIKASI WHERE 1 = 1 ".$statement; 
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
		$str = "SELECT COUNT(KLASIFIKASI_ID) AS ROWCOUNT FROM KLASIFIKASI WHERE 1 = 1 "; 
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