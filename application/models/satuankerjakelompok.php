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

  class SatuanKerjaKelompok  extends Entity{ 

	var $query;
	var $id;	
    /**
    * Class constructor.
    **/
    function SatuanKerjaKelompok ()
	{
      $this->Entity(); 
    }
	
    function insert()
    {
    	$this->setField("SATUAN_KERJA_KELOMPOK_ID", $this->getNextId("SATUAN_KERJA_KELOMPOK_ID","SATUAN_KERJA_KELOMPOK")); 

    	$str = "INSERT INTO SATUAN_KERJA_KELOMPOK (SATUAN_KERJA_KELOMPOK_ID, KODE, NAMA, CABANG_ID, CABANG_OWNER, LAST_CREATE_USER,  LAST_CREATE_DATE)
    	VALUES (
    	'".$this->getField("SATUAN_KERJA_KELOMPOK_ID")."',
    	'".$this->getField("KODE")."',
    	'".$this->getField("NAMA")."',
    	'".$this->getField("CABANG_ID")."',
    	'".$this->getField("CABANG_OWNER")."',
    	'".$this->getField("LAST_CREATE_USER")."',
    	 CURRENT_DATE
    	
    )";

    $this->id = $this->getField("SATUAN_KERJA_KELOMPOK_ID");
    $this->query= $str;
		//echo $str;exit();
    return $this->execQuery($str);
	}

	function update()
	{
		$str = "
		UPDATE SATUAN_KERJA_KELOMPOK
		SET    
		SATUAN_KERJA_KELOMPOK_ID ='".$this->getField("SATUAN_KERJA_KELOMPOK_ID")."',
		KODE ='".$this->getField("KODE")."',
		NAMA ='".$this->getField("NAMA")."',
		CABANG_ID ='".$this->getField("CABANG_ID")."',
		CABANG_OWNER ='".$this->getField("CABANG_OWNER")."',
		LAST_UPDATE_USER ='".$this->getField("LAST_UPDATE_USER")."',
		LAST_UPDATE_DATE = CURRENT_DATE 
		WHERE SATUAN_KERJA_KELOMPOK_ID= '".$this->getField("SATUAN_KERJA_KELOMPOK_ID")."'";
		$this->query = $str;
		  // echo $str;exit;
		return $this->execQuery($str);
	}

	function delete($statement= "")
	{
		$str = "DELETE FROM SATUAN_KERJA_KELOMPOK
		WHERE SATUAN_KERJA_KELOMPOK_ID= ".$this->getField("SATUAN_KERJA_KELOMPOK_ID").""; 
		$this->query = $str;
		  // echo $str;exit();
		return $this->execQuery($str);
	}


	function selectByParamsMonitoring($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order="ORDER BY A.SATUAN_KERJA_KELOMPOK_ID ASC")
	{
		$str = "
		SELECT A.SATUAN_KERJA_KELOMPOK_ID,A.KODE,A.NAMA,A.CABANG_ID,A.LAST_CREATE_USER,A.LAST_CREATE_DATE,A.LAST_UPDATE_USER,A.LAST_UPDATE_DATE
		FROM SATUAN_KERJA_KELOMPOK A
		WHERE 1=1 ";
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val'";
		}

		$str .= $statement." ".$order;
		$this->query = $str;
		return $this->selectLimit($str,$limit,$from); 
	}

	function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order="ORDER BY A.SATUAN_KERJA_KELOMPOK_ID ASC")
	{
		$str = "
		SELECT A.SATUAN_KERJA_KELOMPOK_ID,A.KODE,A.NAMA,A.CABANG_ID,A.LAST_CREATE_USER,A.LAST_CREATE_DATE,A.LAST_UPDATE_USER,A.LAST_UPDATE_DATE,
			   AMBIL_KELOMPOK_JABATAN(A.SATUAN_KERJA_KELOMPOK_ID) JABATAN
		FROM SATUAN_KERJA_KELOMPOK A
		WHERE 1=1 ";
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val'";
		}

		$str .= $statement." ".$order;
		$this->query = $str;
		return $this->selectLimit($str,$limit,$from); 
	}
	
	function getJson($paramsArray=array(),$statement="")
	{
		$str = "
			SELECT ROW_TO_JSON(A) JSON FROM 
			(SELECT SATUAN_KERJA_KELOMPOK_ID, SATUAN_KERJA_ID, NAMA SATUAN_KERJA FROM SATUAN_KERJA_KELOMPOK_DETIL) A
			WHERE 1 = 1
		"; 
		while(list($key,$val)=each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement;
		$this->selectLimit($str,-1,-1); 
		$hasil = "[";
		$i = 0;
		while($this->nextRow())
		{
			if($i == 0)
				$hasil .= $this->getField("JSON");
			else
				$hasil .= ",".$this->getField("JSON");
			$i++;		
		}
		$hasil .= "]";		
		$hasil = str_replace("null", '""', $hasil);
		//echo $str;
		return strtoupper($hasil);
		
    }

	function getCountByParamsMonitoring($paramsArray=array(), $statement="")
	{
		$str = "SELECT COUNT(1) AS ROWCOUNT FROM SATUAN_KERJA_KELOMPOK A WHERE 1=1 ".$statement;
		while(list($key,$val)=each($paramsArray))
		{
			$str .= " AND $key = 	'$val' ";
		}
		$this->query = $str;
		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0; 
	}

	function getData($paramsArray=array(), $statement="")
	{
		$str = "
		SELECT 
			SATUAN_KERJA_KELOMPOK_ID, STRING_AGG(SATUAN_KERJA_ID, ',') AS HASIL
		FROM  SATUAN_KERJA_KELOMPOK_DETIL A
		WHERE 1 = 1
		".$statement;
		while(list($key,$val)=each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= " GROUP  BY SATUAN_KERJA_KELOMPOK_ID ";
		
		$this->query = $str;
		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("HASIL"); 
		else 
			return ""; 
	}

	function getDataKelompok($paramsArray=array(), $statement="")
	{
		$str = "
		SELECT 
			SATUAN_KERJA_KELOMPOK_ID, STRING_AGG(KELOMPOK_JABATAN, ',') AS HASIL
		FROM  SATUAN_KERJA_KELOMPOK_GROUP A
		WHERE 1 = 1
		".$statement;
		while(list($key,$val)=each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= " GROUP  BY SATUAN_KERJA_KELOMPOK_ID ";
		
		$this->query = $str;
		// echo $str;exit;
		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("HASIL"); 
		else 
			return ""; 
	}
	
	function getCountByParams($paramsArray=array(), $statement="")
	{
		$str = "SELECT COUNT(1) AS ROWCOUNT FROM SATUAN_KERJA_KELOMPOK A WHERE 1=1 ".$statement;
		while(list($key,$val)=each($paramsArray))
		{
			$str .= " AND $key = 	'$val' ";
		}
		$this->query = $str;
		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0; 
	}
} 
?>