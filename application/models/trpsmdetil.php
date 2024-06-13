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

  class TrPsmDetil extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function TrPsmDetil()
	{
      $this->Entity(); 
    }
	
	function insert()
	{
		$this->setField("TR_PSM_DETIL_ID", $this->getNextId("TR_PSM_DETIL_ID","tr_psm_detil")); 
		$str = "
		INSERT INTO tr_psm_detil 
		(
			TR_PSM_DETIL_ID, TR_PSM_ID, VMODE, VID, NILAI, KETERANGAN
		)
		VALUES 
		(
			".$this->getField("TR_PSM_DETIL_ID")."
			, ".$this->getField("TR_PSM_ID")."
			, '".$this->getField("VMODE")."'
			, ".$this->getField("VID")."
			, ".$this->getField("NILAI")."
			, '".$this->getField("KETERANGAN")."'
		)";
		$this->id = $this->getField("TR_PSM_DETIL_ID");
		$this->query = $str;
		// echo $str;exit;

		return $this->execQuery($str);
    }

  	function update()
	{
		$str = "
		UPDATE tr_psm_detil
		SET   
		TR_PSM_ID= ".$this->getField("TR_PSM_ID")."
		, VMODE= '".$this->getField("VMODE")."'
		, VID= ".$this->getField("VID")."
		, NILAI= ".$this->getField("NILAI")."
		WHERE TR_PSM_DETIL_ID= ".$this->getField("TR_PSM_DETIL_ID")."
		"; 
		// echo $str;exit;
		$this->query = $str;
		return $this->execQuery($str);
    }

	function delete()
	{
        $str = "
        DELETE FROM tr_psm_detil
        WHERE TR_PSM_ID = ".$this->getField("TR_PSM_ID")."
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
    function selectByParams($paramsArray=array(),$limit=-1,$from=-1,$statement="", $order=" ORDER BY A.TR_PSM_DETIL_ID ASC")
	{
		$str = "
		SELECT 
		A.*
		FROM tr_psm_detil A
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

    function selectlokasi($paramsArray=array(),$limit=-1,$from=-1,$statement="", $order=" ORDER BY A.VID ASC")
	{
		$str = "
		SELECT
		A1.KODE, A1.NAMA, A1.LANTAI
		, A.*
		FROM tr_psm_detil A
		INNER JOIN
		(
			SELECT A.*, A1.NAMA LANTAI
			FROM lokasi_loo_detil A
			INNER JOIN lantai_loo A1 ON A.LANTAI_LOO_ID = A1.LANTAI_LOO_ID
		) A1 ON A.VID = A1.LOKASI_LOO_DETIL_ID
		WHERE 1=1
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
		$str = "SELECT COUNT(1) AS ROWCOUNT FROM tr_psm_detil A WHERE 1 = 1 ".$statement; 
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