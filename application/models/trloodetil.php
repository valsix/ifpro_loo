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

  class TrLooDetil extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function TrLooDetil()
	{
      $this->Entity(); 
    }
	
	function insert()
	{
		$this->setField("TR_LOO_DETIL_ID", $this->getNextId("TR_LOO_DETIL_ID","tr_loo_detil")); 
		$str = "
		INSERT INTO tr_loo_detil 
		(
			TR_LOO_DETIL_ID, TR_LOO_ID, VMODE, VID, NILAI
		)
		VALUES 
		(
			".$this->getField("TR_LOO_DETIL_ID")."
			, ".$this->getField("TR_LOO_ID")."
			, '".$this->getField("VMODE")."'
			, ".$this->getField("VID")."
			, ".$this->getField("NILAI")."
		)";
		$this->id = $this->getField("TR_LOO_DETIL_ID");
		$this->query = $str;
		// echo $str;exit;

		return $this->execQuery($str);
    }

  	function update()
	{
		$str = "
		UPDATE tr_loo_detil
		SET   
		TR_LOO_ID= ".$this->getField("TR_LOO_ID")."
		, VMODE= '".$this->getField("VMODE")."'
		, VID= ".$this->getField("VID")."
		, NILAI= ".$this->getField("NILAI")."
		WHERE TR_LOO_DETIL_ID= ".$this->getField("TR_LOO_DETIL_ID")."
		"; 
		// echo $str;exit;
		$this->query = $str;
		return $this->execQuery($str);
    }

	function delete()
	{
        $str = "
        DELETE FROM tr_loo_detil
        WHERE TR_LOO_ID = ".$this->getField("TR_LOO_ID")."
        ";
				  
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
    function selectByParams($paramsArray=array(),$limit=-1,$from=-1,$statement="", $order=" ORDER BY A.TR_LOO_DETIL_ID ASC")
	{
		$str = "
		SELECT 
		A.*
		FROM tr_loo_detil A
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
	
    /** 
    * Hitung jumlah record berdasarkan parameter (array). 
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","nama"=>"yyy") 
    * @return long Jumlah record yang sesuai kriteria 
    **/ 
    function getCountByParams($paramsArray=array(), $statement="")
	{
		$str = "SELECT COUNT(1) AS ROWCOUNT FROM tr_loo_detil A WHERE 1 = 1 ".$statement; 
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
	
  } 
?>