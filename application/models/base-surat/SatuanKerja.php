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

  class SatuanKerja extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function SatuanKerja()
	{
      $this->Entity(); 
    }
	
	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		//$this->setField("SATUAN_KERJA_ID", $this->getNextId("SATUAN_KERJA_ID","SATUAN_KERJA")); 
		$str = "
				INSERT INTO SATUAN_KERJA (
				   SATUAN_KERJA_ID, SATUAN_KERJA_ID_PARENT, NAMA, 
				   NIP, KODE_SURAT, KODE_SO, 
				   NAMA_PEGAWAI, EMAIL, HP, JABATAN) 
				VALUES(
				  SATUAN_KERJA_ID_GENERATE('".$this->getField("SATUAN_KERJA_ID")."'),
				  '".$this->getField("SATUAN_KERJA_ID")."',
				  '".$this->getField("NAMA")."',
				  '".$this->getField("NIP")."',
				  '".$this->getField("KODE_SURAT")."',
				  '".$this->getField("KODE_SO")."',
				  '".$this->getField("NAMA_PEGAWAI")."',
				  '".$this->getField("EMAIL")."',
				  '".$this->getField("HP")."',
				  '".$this->getField("JABATAN")."'
				)"; 
		$this->id = $this->getField("SATUAN_KERJA_ID");
		$this->query = $str;
		return $this->execQuery($str);
    }

    function update()
	{
		//SATUAN_KERJA_ID_PARENT = '".$this->getField("SATUAN_KERJA_ID_PARENT")."',
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "UPDATE SATUAN_KERJA SET
				  NAMA = '".$this->getField("NAMA")."',
				  NIP = '".$this->getField("NIP")."',
				  KODE_SURAT = '".$this->getField("KODE_SURAT")."',
				  KODE_SO = '".$this->getField("KODE_SO")."',
				  NAMA_PEGAWAI = '".$this->getField("NAMA_PEGAWAI")."',
				  EMAIL = '".$this->getField("EMAIL")."',
				  HP= '".$this->getField("HP")."',
				  JABATAN = '".$this->getField("JABATAN")."'
				WHERE SATUAN_KERJA_ID = '".$this->getField("SATUAN_KERJA_ID")."'
				"; 
				$this->query = $str;
		return $this->execQuery($str);
    }
	
	function delete()
	{
        $str = "DELETE FROM SATUAN_KERJA
                WHERE 
                  SATUAN_KERJA_ID = '".$this->getField("SATUAN_KERJA_ID")."'"; 
				  
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
					SATUAN_KERJA_ID, SATUAN_KERJA_ID_PARENT, NAMA, NIP, KODE_SURAT, KODE_SO, NAMA_PEGAWAI, EMAIL, HP, JABATAN,
					CASE WHEN COALESCE(NULLIF(JABATAN,'') , NULL ) IS NULL THEN NAMA ELSE CONCAT(JABATAN, ' ', NAMA) END NAMA_INFO
				FROM SATUAN_KERJA
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
				SATUAN_KERJA_ID, SATUAN_KERJA_ID_PARENT, NAMA, NIP, KODE_SURAT, KODE_SO, NAMA_PEGAWAI, EMAIL
				FROM SATUAN_KERJA
				WHERE 1 = 1
				"; 
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key LIKE '%$val%' ";
		}
		
		$str .= $statement." ORDER BY SATUAN_KERJA_ID DESC";
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
		$str = "SELECT COUNT(SATUAN_KERJA_ID) AS ROWCOUNT FROM SATUAN_KERJA WHERE 1 = 1 ".$statement; 
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
		$str = "SELECT COUNT(SATUAN_KERJA_ID) AS ROWCOUNT FROM SATUAN_KERJA WHERE 1 = 1 "; 
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