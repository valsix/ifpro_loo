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

  class DaftarAlamat extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function DaftarAlamat()
	{
      $this->Entity(); 
    }
	
	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("DAFTAR_ALAMAT_ID", $this->getNextId("DAFTAR_ALAMAT_ID","DAFTAR_ALAMAT")); 
		$str = "
				INSERT INTO DAFTAR_ALAMAT (DAFTAR_ALAMAT_ID, DAFTAR_ALAMAT_GROUP_ID, INSTANSI, ALAMAT, KOTA, NO_TELP, EMAIL, STATUS, 
						KODE_POS, FAX, NAMA_KEPALA, JABATAN_KEPALA ,HP, CABANG_ID, LAST_CREATE_USER, LAST_CREATE_DATE) 
				VALUES(
				  '".$this->getField("DAFTAR_ALAMAT_ID")."',
				  ".(int)$this->getField("DAFTAR_ALAMAT_GROUP_ID").",
				  '".$this->getField("INSTANSI")."',
				  '".$this->getField("ALAMAT")."',
				  '".$this->getField("KOTA")."',
				  '".$this->getField("NO_TELP")."',
				  '".$this->getField("EMAIL")."',
				  '".$this->getField("STATUS")."',
				  '".$this->getField("KODE_POS")."',
				  '".$this->getField("FAX")."',
				  '".$this->getField("NAMA_KEPALA")."',
				  '".$this->getField("JABATAN_KEPALA")."',
				  '".$this->getField("HP")."',
				  '".$this->getField("CABANG_ID")."',
				  '".$this->getField("LAST_CREATE_USER")."',
				  CURRENT_DATE
				)"; 
		$this->id = $this->getField("DAFTAR_ALAMAT_ID");
		$this->query = $str;
		return $this->execQuery($str);
    }
	
    function update()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "UPDATE DAFTAR_ALAMAT SET
				  DAFTAR_ALAMAT_GROUP_ID	= ".(int)$this->getField("DAFTAR_ALAMAT_GROUP_ID").",
				  INSTANSI 			= '".$this->getField("INSTANSI")."',
				  ALAMAT 			= '".$this->getField("ALAMAT")."',
				  KOTA 				= '".$this->getField("KOTA")."',
				  NO_TELP 			= '".$this->getField("NO_TELP")."',
				  EMAIL 			= '".$this->getField("EMAIL")."',
				  STATUS			= '".$this->getField("STATUS")."',
				  KODE_POS			= '".$this->getField("KODE_POS")."',
				  FAX				= '".$this->getField("FAX")."',
				  NAMA_KEPALA		= '".$this->getField("NAMA_KEPALA")."',
				  JABATAN_KEPALA	= '".$this->getField("JABATAN_KEPALA")."',
				  HP				= '".$this->getField("HP")."',
				  CABANG_ID			= '".$this->getField("CABANG_ID")."',
				  LAST_UPDATE_USER	= '".$this->getField("LAST_UPDATE_USER")."',
				  LAST_UPDATE_DATE	= CURRENT_DATE
				WHERE DAFTAR_ALAMAT_ID = '".$this->getField("DAFTAR_ALAMAT_ID")."'
				"; 
				$this->query = $str;
		return $this->execQuery($str);
    }
	
	function updateTanpaTelepon()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "UPDATE DAFTAR_ALAMAT SET
				  INSTANSI = '".$this->getField("INSTANSI")."',
				  ALAMAT = '".$this->getField("ALAMAT")."',
				  KOTA = '".$this->getField("KOTA")."'
				WHERE DAFTAR_ALAMAT_ID = '".$this->getField("DAFTAR_ALAMAT_ID")."'
				"; 
				$this->query = $str;
		return $this->execQuery($str);
    }
	
	function delete()
	{
        $str = "DELETE FROM DAFTAR_ALAMAT
                WHERE 
                  DAFTAR_ALAMAT_ID = '".$this->getField("DAFTAR_ALAMAT_ID")."'"; 
				  
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
				DAFTAR_ALAMAT_ID, DAFTAR_ALAMAT_GROUP_ID, INSTANSI, ALAMAT, KOTA, 
				NO_TELP, EMAIL, STATUS, KODE_POS, FAX, NAMA_KEPALA, HP, CABANG_ID, JABATAN_KEPALA
				FROM DAFTAR_ALAMAT		
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
    
	function selectByParamsMonitoring($paramsArray=array(),$limit=-1,$from=-1,$statement="", $order="")
	{
		$str = "
				SELECT 
				DAFTAR_ALAMAT_ID, A.DAFTAR_ALAMAT_GROUP_ID, COALESCE(B.NAMA, 'Belum ada Group') DAFTAR_ALAMAT_GROUP, INSTANSI, ALAMAT, KOTA, NO_TELP, EMAIL, STATUS, KODE_POS, FAX, NAMA_KEPALA, HP, CABANG_ID, JABATAN_KEPALA 
				FROM DAFTAR_ALAMAT A
				LEFT JOIN DAFTAR_ALAMAT_GROUP B ON A.DAFTAR_ALAMAT_GROUP_ID=B.DAFTAR_ALAMAT_GROUP_ID        
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
				DAFTAR_ALAMAT_ID, INSTANSI, ALAMAT, KOTA, NO_TELP, EMAIL, STATUS, KODE_POS, FAX, NAMA_KEPALA, HP, CABANG_ID, JABATAN_KEPALA 
				FROM DAFTAR_ALAMAT		
				WHERE 1 = 1
				"; 
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key LIKE '%$val%' ";
		}
		
		$str .= $statement." ORDER BY DAFTAR_ALAMAT_ID DESC";
		$this->query = $str;		
		return $this->selectLimit($str,$limit,$from); 
    }	
    /** 
    * Hitung jumlah record berdasarkan parameter (array). 
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","nama"=>"yyy") 
    * @return long Jumlah record yang sesuai kriteria 
    **/ 
	function getCountByParamsMonitoring($paramsArray=array(), $statement="")
	{
		$str = "SELECT COUNT(1) AS ROWCOUNT 
				FROM DAFTAR_ALAMAT A
				LEFT JOIN DAFTAR_ALAMAT_GROUP B ON A.DAFTAR_ALAMAT_GROUP_ID=B.DAFTAR_ALAMAT_GROUP_ID        
				WHERE 1 = 1 ".$statement; 
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
	
    function getCountByParams($paramsArray=array(), $statement="")
	{
		$str = "SELECT COUNT(DAFTAR_ALAMAT_ID) AS ROWCOUNT FROM DAFTAR_ALAMAT WHERE 1 = 1 ".$statement; 
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
		$str = "SELECT COUNT(DAFTAR_ALAMAT_ID) AS ROWCOUNT FROM DAFTAR_ALAMAT WHERE 1 = 1 "; 
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