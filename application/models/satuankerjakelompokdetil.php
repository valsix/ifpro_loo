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

  class SatuanKerjaKelompokDetil  extends Entity{ 

	var $query;
	var $id;
    /**
    * Class constructor.
    **/
    function SatuanKerjaKelompokDetil ()
	{
      $this->Entity(); 
    }

    function insert()
    {
    	$this->setField("SATUAN_KERJA_KELOMPOK_DETIL_ID", $this->getNextId("SATUAN_KERJA_KELOMPOK_DETIL_ID","SATUAN_KERJA_KELOMPOK_DETIL")); 

    	$str = "INSERT INTO SATUAN_KERJA_KELOMPOK_DETIL (SATUAN_KERJA_KELOMPOK_DETIL_ID, SATUAN_KERJA_KELOMPOK_ID, SATUAN_KERJA_ID,        NAMA, LAST_CREATE_USER, LAST_CREATE_DATE)
    	VALUES (
	    	'".$this->getField("SATUAN_KERJA_KELOMPOK_DETIL_ID")."',
	    	'".$this->getField("SATUAN_KERJA_KELOMPOK_ID")."',
	    	'".$this->getField("SATUAN_KERJA_ID")."',
	    	'".$this->getField("NAMA")."',
	    	'".$this->getField("LAST_CREATE_USER")."',
	    	 CURRENT_DATE
	    )";

	    $this->id = $this->getField("SATUAN_KERJA_KELOMPOK_DETIL_ID");
	    $this->query= $str;
			// echo $str;exit();
	    return $this->execQuery($str);
	}

	function insertkelompok()
    {
    	$this->setField("SATUAN_KERJA_KELOMPOK_GROUP_ID", $this->getNextId("SATUAN_KERJA_KELOMPOK_GROUP_ID","SATUAN_KERJA_KELOMPOK_GROUP"));

    	$str = "
    	INSERT INTO SATUAN_KERJA_KELOMPOK_GROUP 
    	(
	    	SATUAN_KERJA_KELOMPOK_GROUP_ID, SATUAN_KERJA_KELOMPOK_ID, KELOMPOK_JABATAN
	    	, LAST_CREATE_USER, LAST_CREATE_DATE
    	)
    	VALUES (
	    	'".$this->getField("SATUAN_KERJA_KELOMPOK_GROUP_ID")."',
	    	'".$this->getField("SATUAN_KERJA_KELOMPOK_ID")."',
	    	'".$this->getField("KELOMPOK_JABATAN")."',
	    	'".$this->getField("LAST_CREATE_USER")."',
	    	 CURRENT_DATE
	    )";

	    $this->id = $this->getField("SATUAN_KERJA_KELOMPOK_GROUP_ID");
	    $this->query= $str;
			// echo $str;exit();
	    return $this->execQuery($str);
	}

	function update()
	{
		$str = "
		UPDATE SATUAN_KERJA_KELOMPOK_DETIL
		SET    
		SATUAN_KERJA_KELOMPOK_DETIL_ID ='".$this->getField("SATUAN_KERJA_KELOMPOK_DETIL_ID")."',
		SATUAN_KERJA_KELOMPOK_ID ='".$this->getField("SATUAN_KERJA_KELOMPOK_ID")."',
		SATUAN_KERJA_ID ='".$this->getField("SATUAN_KERJA_ID")."',
		NAMA ='".$this->getField("NAMA")."',
		LAST_UPDATE_USER ='".$this->getField("LAST_UPDATE_USER")."',
		LAST_UPDATE_DATE =".$this->getField("LAST_UPDATE_DATE")." 
		WHERE SATUAN_KERJA_KELOMPOK_DETIL_ID= ".$this->getField("SATUAN_KERJA_KELOMPOK_DETIL_ID")."";
		$this->query = $str;
		  // echo $str;exit;
		return $this->execQuery($str);
	}

	function deletePerent(){
		$str1= "
		DELETE FROM SATUAN_KERJA_KELOMPOK_GROUP
		WHERE SATUAN_KERJA_KELOMPOK_ID= '".$this->getField("SATUAN_KERJA_KELOMPOK_ID")."'
		"; 
		$this->query = $str1;
		$this->execQuery($str1);

		$str = "
		DELETE FROM SATUAN_KERJA_KELOMPOK_DETIL
		WHERE SATUAN_KERJA_KELOMPOK_ID= '".$this->getField("SATUAN_KERJA_KELOMPOK_ID")."'
		"; 
		$this->query = $str;
		  // echo $str;exit();
		return $this->execQuery($str);
	}

	function delete($statement= "")
	{
		$str = "DELETE FROM SATUAN_KERJA_KELOMPOK_DETIL
		WHERE SATUAN_KERJA_KELOMPOK_DETIL_ID= ".$this->getField("SATUAN_KERJA_KELOMPOK_DETIL_ID").""; 
		$this->query = $str;
		  // echo $str;exit();
		return $this->execQuery($str);
	}

	function selectByParamsMonitoring($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order="ORDER BY A.SATUAN_KERJA_KELOMPOK_DETIL_ID ASC")
	{
		$str = "
		SELECT A.SATUAN_KERJA_KELOMPOK_DETIL_ID,A.SATUAN_KERJA_KELOMPOK_ID,A.SATUAN_KERJA_ID,A.NAMA,A.LAST_CREATE_USER,A.LAST_CREATE_DATE,A.LAST_UPDATE_USER,A.LAST_UPDATE_DATE
		FROM SATUAN_KERJA_KELOMPOK_DETIL A
		WHERE 1=1 ";
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val'";
		}

		$str .= $statement." ".$order;
		$this->query = $str;
		return $this->selectLimit($str,$limit,$from); 
	}

	function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order="ORDER BY A.SATUAN_KERJA_KELOMPOK_DETIL_ID ASC")
	{
		$str = "
		SELECT A.SATUAN_KERJA_KELOMPOK_DETIL_ID,A.SATUAN_KERJA_KELOMPOK_ID,A.SATUAN_KERJA_ID,A.NAMA,A.LAST_CREATE_USER,A.LAST_CREATE_DATE,A.LAST_UPDATE_USER,A.LAST_UPDATE_DATE
		FROM SATUAN_KERJA_KELOMPOK_DETIL A
		WHERE 1=1 ";
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val'";
		}

		$str .= $statement." ".$order;
		$this->query = $str;
		return $this->selectLimit($str,$limit,$from); 
	}

	function getCountByParamsMonitoring($paramsArray=array(), $statement="")
	{
		$str = "SELECT COUNT(1) AS ROWCOUNT FROM SATUAN_KERJA_KELOMPOK_DETIL A WHERE 1=1 ".$statement;
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