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

  class Makanan extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function Makanan()
	{
      $this->Entity(); 
    }
	
	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("MAKANAN_ID", $this->getNextId("MAKANAN_ID","AKTIFITAS.MAKANAN")); 
		$str = "
				INSERT INTO AKTIFITAS.MAKANAN (
				   MAKANAN_ID, NAMA, KETERANGAN, LOKASI_ID, LAST_CREATE_USER, LAST_CREATE_DATE) 
				VALUES ( '".$this->getField("MAKANAN_ID")."', '".$this->getField("NAMA")."', '".$this->getField("KETERANGAN")."', '".$this->getField("LOKASI_ID")."', '".$this->getField("LAST_CREATE_USER")."', CURRENT_DATE)"; 
		$this->id = $this->getField("MAKANAN_ID");
		$this->query = $str;

		return $this->execQuery($str);
    }

    function update()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "
			   UPDATE AKTIFITAS.MAKANAN
				SET    NAMA         	= '".$this->getField("NAMA")."',
					   KETERANGAN      	= '".$this->getField("KETERANGAN")."',
					   LAST_UPDATE_USER   	= '".$this->getField("LAST_UPDATE_USER")."',
					   LAST_UPDATE_DATE   	= CURRENT_DATE
				WHERE  MAKANAN_ID    	= '".$this->getField("MAKANAN_ID")."'
				"; 
				$this->query = $str;
		return $this->execQuery($str);
    }

    function updateByField()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "UPDATE AKTIFITAS.MAKANAN A SET
				  ".$this->getField("FIELD")." 		= '".$this->getField("FIELD_VALUE")."',
				  ".$this->getField("FIELD_VALIDATOR")." 	= '".$this->getField("FIELD_VALUE_VALIDATOR")."'
				WHERE MAKANAN_ID = ".$this->getField("MAKANAN_ID")."
				"; 
				$this->query = $str;
		return $this->execQuery($str);
    }	
	
	function delete()
	{
        $str = "DELETE FROM AKTIFITAS.MAKANAN
                WHERE 
                  MAKANAN_ID = ".$this->getField("MAKANAN_ID").""; 
				  
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
    function selectByParams($paramsArray=array(),$limit=-1,$from=-1,$statement="", $order=" ORDER BY MAKANAN_ID ASC")
	{
		$str = "
				SELECT 
				MAKANAN_ID, NAMA, KETERANGAN
				FROM AKTIFITAS.MAKANAN A
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
				MAKANAN_ID, NAMA, KETERANGAN
				FROM AKTIFITAS.MAKANAN A
				WHERE 1 = 1
			"; 
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key LIKE '%$val%' ";
		}
		
		$str .= $statement." ORDER BY MAKANAN_ID DESC";
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
		$str = "SELECT COUNT(MAKANAN_ID) AS ROWCOUNT FROM AKTIFITAS.MAKANAN A WHERE 1 = 1 ".$statement; 
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
		$str = "SELECT COUNT(MAKANAN_ID) AS ROWCOUNT FROM AKTIFITAS.MAKANAN WHERE 1 = 1 "; 
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