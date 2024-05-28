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

  class TrLoo extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function TrLoo()
	{
      $this->Entity(); 
    }
	
	function insert()
	{
		$this->setField("TR_LOO_ID", $this->getNextId("TR_LOO_ID","tr_loo")); 
		$str = "
		INSERT INTO tr_loo 
		(
			TR_LOO_ID, PRODUK_ID, CUSTOMER_ID, LOKASI_LOO_ID, TOTAL_LUAS_INDOOR, TOTAL_LUAS_OUTDOOR
			, TOTAL_LUAS, TOTAL_DISKON_INDOOR_SEWA, TOTAL_DISKON_OUTDOOR_SEWA, TOTAL_DISKON_INDOOR_SERVICE
			, TOTAL_DISKON_OUTDOOR_SERVICE, HARGA_INDOOR_SEWA, HARGA_OUTDOOR_SEWA, HARGA_INDOOR_SERVICE
			, HARGA_OUTDOOR_SERVICE, DP, PERIODE_SEWA
		)
		VALUES 
		(
			".$this->getField("TR_LOO_ID")."
			, ".$this->getField("PRODUK_ID")."
			, ".$this->getField("CUSTOMER_ID")."
			, ".$this->getField("LOKASI_LOO_ID")."
			, ".$this->getField("TOTAL_LUAS_INDOOR")."
			, ".$this->getField("TOTAL_LUAS_OUTDOOR")."
			, ".$this->getField("TOTAL_LUAS")."
			, ".$this->getField("TOTAL_DISKON_INDOOR_SEWA")."
			, ".$this->getField("TOTAL_DISKON_OUTDOOR_SEWA")."
			, ".$this->getField("TOTAL_DISKON_INDOOR_SERVICE")."
			, ".$this->getField("TOTAL_DISKON_OUTDOOR_SERVICE")."
			, ".$this->getField("HARGA_INDOOR_SEWA")."
			, ".$this->getField("HARGA_OUTDOOR_SEWA")."
			, ".$this->getField("HARGA_INDOOR_SERVICE")."
			, ".$this->getField("HARGA_OUTDOOR_SERVICE")."
			, ".$this->getField("DP")."
			, ".$this->getField("PERIODE_SEWA")."
		)";
		$this->id = $this->getField("TR_LOO_ID");
		$this->query = $str;

		return $this->execQuery($str);
    }

  	function update()
	{
		$str = "
		UPDATE tr_loo
		SET   
		PRODUK_ID= ".$this->getField("PRODUK_ID")."
		, CUSTOMER_ID= ".$this->getField("CUSTOMER_ID")."
		, LOKASI_LOO_ID= ".$this->getField("LOKASI_LOO_ID")."
		, TOTAL_LUAS_INDOOR= ".$this->getField("TOTAL_LUAS_INDOOR")."
		, TOTAL_LUAS_OUTDOOR= ".$this->getField("TOTAL_LUAS_OUTDOOR")."
		, TOTAL_LUAS= ".$this->getField("TOTAL_LUAS")."
		, TOTAL_DISKON_INDOOR_SEWA= ".$this->getField("TOTAL_DISKON_INDOOR_SEWA")."
		, TOTAL_DISKON_OUTDOOR_SEWA= ".$this->getField("TOTAL_DISKON_OUTDOOR_SEWA")."
		, TOTAL_DISKON_INDOOR_SERVICE= ".$this->getField("TOTAL_DISKON_INDOOR_SERVICE")."
		, TOTAL_DISKON_OUTDOOR_SERVICE= ".$this->getField("TOTAL_DISKON_OUTDOOR_SERVICE")."
		, HARGA_INDOOR_SEWA= ".$this->getField("HARGA_INDOOR_SEWA")."
		, HARGA_OUTDOOR_SEWA= ".$this->getField("HARGA_OUTDOOR_SEWA")."
		, HARGA_INDOOR_SERVICE= ".$this->getField("HARGA_INDOOR_SERVICE")."
		, HARGA_OUTDOOR_SERVICE= ".$this->getField("HARGA_OUTDOOR_SERVICE")."
		, DP= ".$this->getField("DP")."
		, PERIODE_SEWA= ".$this->getField("PERIODE_SEWA")."
		WHERE TR_LOO_ID= ".$this->getField("TR_LOO_ID")."
		"; 
		// echo $str;exit;
		$this->query = $str;
		return $this->execQuery($str);
    }

	function delete()
	{
        $str = "
        DELETE FROM tr_loo
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
    function selectByParams($paramsArray=array(),$limit=-1,$from=-1,$statement="", $order=" ORDER BY A.TR_LOO_ID ASC")
	{
		$str = "
		SELECT 
		A.*
		FROM tr_loo A
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
		$str = "SELECT COUNT(1) AS ROWCOUNT FROM tr_loo A WHERE 1 = 1 ".$statement; 
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