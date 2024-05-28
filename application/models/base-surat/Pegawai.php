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

  class Pegawai extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function Pegawai()
	{
      $this->Entity(); 
    }
	
	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("PEGAWAI_ID", $this->getNextId("PEGAWAI_ID","PEGAWAI")); 
		$str = "
				INSERT INTO PEGAWAI (PEGAWAI_ID, NIP, NAMA, JENIS_KELAMIN, ALAMAT, TEMPAT_LAHIR, TANGGAL_LAHIR, 
      				 EMAIL, PHONE, SATUAN_KERJA_ID, LAST_CREATE_USER, LAST_CREATE_DATE) 
				VALUES(
				  ".$this->getField("PEGAWAI_ID").",
				  '".$this->getField("NIP")."',
				  '".$this->getField("NAMA")."',
				  '".$this->getField("JENIS_KELAMIN")."',
				  '".$this->getField("ALAMAT")."',
				  '".$this->getField("TEMPAT_LAHIR")."',
				  ".$this->getField("TANGGAL_LAHIR").",
				  '".$this->getField("EMAIL")."',
				  '".$this->getField("PHONE")."',
				  '".$this->getField("SATUAN_KERJA_ID")."',
				  '".$this->getField("LAST_CREATE_USER")."',
				  CURRENT_DATE
				)"; 
		$this->id = $this->getField("PEGAWAI_ID");
		$this->query = $str;
		return $this->execQuery($str);
    }

    function update()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "UPDATE PEGAWAI SET
				  NIP				= '".$this->getField("NIP")."',
				  NAMA				= '".$this->getField("NAMA")."',
				  JENIS_KELAMIN		= '".$this->getField("JENIS_KELAMIN")."',
				  ALAMAT			= '".$this->getField("ALAMAT")."',
				  TEMPAT_LAHIR		= '".$this->getField("TEMPAT_LAHIR")."',
				  TANGGAL_LAHIR		= ".$this->getField("TANGGAL_LAHIR").",
				  EMAIL				= '".$this->getField("EMAIL")."',
				  PHONE				= '".$this->getField("PHONE")."',
				  SATUAN_KERJA_ID	= '".$this->getField("SATUAN_KERJA_ID")."',
				  LAST_UPDATE_USER	= '".$this->getField("LAST_UPDATE_USER")."',
				  LAST_UPDATE_DATE	= CURRENT_DATE
				WHERE PEGAWAI_ID 	= '".$this->getField("PEGAWAI_ID")."'
				"; 
				$this->query = $str;
		return $this->execQuery($str);
    }
	
	function delete()
	{
        $str = "DELETE FROM PEGAWAI
                WHERE 
                  PEGAWAI_ID = '".$this->getField("PEGAWAI_ID")."'"; 
				  
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
				SELECT PEGAWAI_ID, NAMA, NIP, NAMA, JENIS_KELAMIN, ALAMAT, TEMPAT_LAHIR, TANGGAL_LAHIR, 
      				 EMAIL, PHONE, SATUAN_KERJA_ID, CASE WHEN JENIS_KELAMIN = 'L' THEN 'Laki - Laki' WHEN JENIS_KELAMIN = 'P' THEN 'Perempuan' END JENIS_KELAMIN_INFO
				FROM PEGAWAI A		
				WHERE 1 = 1
			"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = $val ";
		}
		
		$str .= $statement." ".$order;
		$this->query = $str;
		return $this->selectLimit($str,$limit,$from); 
    }
    
	function selectByParamsLike($paramsArray=array(),$limit=-1,$from=-1, $statement="")
	{
		$str = "
				SELECT PEGAWAI_ID, NIP, NAMA, JENIS_KELAMIN, ALAMAT, TEMPAT_LAHIR, TANGGAL_LAHIR, 
      				 EMAIL, PHONE, SATUAN_KERJA_ID, CASE WHEN JENIS_KELAMIN = 'L' THEN 'Laki - Laki' WHEN JENIS_KELAMIN = 'P' THEN 'Perempuan' END JENIS_KELAMIN_INFO
				FROM PEGAWAI A		
				WHERE 1 = 1
				"; 
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key LIKE '%$val%' ";
		}
		
		$str .= $statement." ORDER BY PEGAWAI_ID DESC";
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
		$str = "SELECT COUNT(PEGAWAI_ID) AS ROWCOUNT FROM PEGAWAI A WHERE 1 = 1 ".$statement; 
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
		$str = "SELECT COUNT(PEGAWAI_ID) AS ROWCOUNT FROM PEGAWAI A WHERE 1 = 1 "; 
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