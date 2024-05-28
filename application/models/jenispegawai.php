<? 
/* *******************************************************************************************************
MODUL NAME 			: IMASYS
FILE NAME 			: 
AUTHOR				: 
VERSION				: 1.0
MODIFICATION DOC	:
DESCRIPTION			: 
***************************************************************************************************** */

  /***
  * Entity-base class untuk mengimplementasikan tabel JENIS_PEGAWAI.
  * 
  ***/
  include_once("Entity.php");

  class JenisPegawai extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function JenisPegawai()
	{
      $this->Entity(); 
    }
	
	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		// $this->setField("JENIS_PEGAWAI_ID", $this->getNextId("JENIS_PEGAWAI_ID","JENIS_PEGAWAI"));

		$str = "
				INSERT INTO JENIS_PEGAWAI (
				   JENIS_PEGAWAI_ID, NAMA, KETERANGAN, JENIS_URUT) 
 			  	VALUES (
				  ".$this->getField("JENIS_PEGAWAI_ID").",
				  '".$this->getField("NAMA")."',
				  '".$this->getField("KETERANGAN")."',
				  ".$this->getField("JENIS_URUT")."
				)"; 
		$this->query = $str;
		return $this->execQuery($str);
    }

    function update()
	{
		$str = "
				UPDATE JENIS_PEGAWAI
				SET    
					   NAMA           = '".$this->getField("NAMA")."',
					   KETERANGAN      = '".$this->getField("KETERANGAN")."'
				WHERE  JENIS_PEGAWAI_ID     = '".$this->getField("JENIS_PEGAWAI_ID")."'

			 "; 
		$this->query = $str;
		return $this->execQuery($str);
    }

	function delete()
	{
        $str = "DELETE FROM JENIS_PEGAWAI
                WHERE 
                  JENIS_PEGAWAI_ID = ".$this->getField("JENIS_PEGAWAI_ID").""; 
				  
		$this->query = $str;
        return $this->execQuery($str);
    }

    /** 
    * Cari record berdasarkan array parameter dan limit tampilan 
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","IJIN_USAHA_ID"=>"yyy") 
    * @param int limit Jumlah maksimal record yang akan diambil 
    * @param int from Awal record yang diambil 
    * @return boolean True jika sukses, false jika tidak 
    **/ 
    function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement="")
	{
		$str = "
				SELECT JENIS_PEGAWAI_ID, NAMA, KETERANGAN
				FROM JENIS_PEGAWAI
				WHERE 1 = 1
				"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ORDER BY JENIS_PEGAWAI_ID ASC";

		$this->query = $str;
		return $this->selectLimit($str,$limit,$from); 
    }
	    
	function selectByParamsLike($paramsArray=array(),$limit=-1,$from=-1, $statement="")
	{
		$str = "
				SELECT JENIS_PEGAWAI_ID, NAMA, KETERANGAN
				FROM JENIS_PEGAWAI
				WHERE 1 = 1
			    "; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key LIKE '%$val%' ";
		}
		
		$this->query = $str;
		$str .= $statement." ORDER BY NAMA ASC";
		return $this->selectLimit($str,$limit,$from); 
    }	
    /** 
    * Hitung jumlah record berdasarkan parameter (array). 
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","IJIN_USAHA_ID"=>"yyy") 
    * @return long Jumlah record yang sesuai kriteria 
    **/ 
    function getCountByParams($paramsArray=array(), $statement="")
	{
		$str = "SELECT COUNT(JENIS_PEGAWAI_ID) AS ROWCOUNT FROM JENIS_PEGAWAI
		        WHERE JENIS_PEGAWAI_ID IS NOT NULL ".$statement; 
		
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

    function getJenisPegawaiPeriode($periode, $pegawai_id)
	{
		$str = "SELECT AMBIL_JENIS_PEGAWAI_PERIODE('".$periode."', '".$pegawai_id."') ROWCOUNT FROM DUAL ";
		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0; 
    }	

    function getCountByParamsLike($paramsArray=array(), $statement="")
	{
		$str = "SELECT COUNT(JENIS_PEGAWAI_ID) AS ROWCOUNT FROM JENIS_PEGAWAI
		        WHERE JENIS_PEGAWAI_ID IS NOT NULL ".$statement; 
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