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

  class SuratBppnfiPegawai extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function SuratBppnfiPegawai()
	{
      $this->Entity(); 
    }
	
	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("SURAT_BPPNFI_PEGAWAI_ID", $this->getNextId("SURAT_BPPNFI_PEGAWAI_ID","SURAT_BPPNFI_PEGAWAI")); 
		$str = "
				INSERT INTO SURAT_BPPNFI_PEGAWAI (SURAT_BPPNFI_PEGAWAI_ID, SURAT_BPPNFI_ID, PEGAWAI_ID, LAST_CREATE_USER, LAST_CREATE_DATE) 
				VALUES(
				  ".$this->getField("SURAT_BPPNFI_PEGAWAI_ID").",
				  ".$this->getField("SURAT_BPPNFI_ID").",
				  ".$this->getField("PEGAWAI_ID").",
				  '".$this->getField("LAST_CREATE_USER")."',
				  CURRENT_DATE
				)"; 
		$this->id = $this->getField("SURAT_BPPNFI_PEGAWAI_ID");
		$this->query = $str;
		return $this->execQuery($str);
    }

    function update()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "UPDATE SURAT_BPPNFI_PEGAWAI SET
				  SURAT_BPPNFI_ID	= ".$this->getField("SURAT_BPPNFI_ID").",
				  PEGAWAI_ID		= ".$this->getField("PEGAWAI_ID").",
				  LAST_UPDATE_USER	= '".$this->getField("LAST_UPDATE_USER")."',
				  LAST_UPDATE_DATE	= CURRENT_DATE
				WHERE SURAT_BPPNFI_PEGAWAI_ID 	= '".$this->getField("SURAT_BPPNFI_PEGAWAI_ID")."'
				"; 
				$this->query = $str;
		return $this->execQuery($str);
    }
	
	function updateEmail()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "UPDATE SURAT_BPPNFI_PEGAWAI SET
				  TANGGAL_KIRIM		= ".$this->getField("TANGGAL_KIRIM")."
				WHERE PEGAWAI_ID 	= ".$this->getField("PEGAWAI_ID")."
				AND SURAT_BPPNFI_ID	= ".$this->getField("SURAT_BPPNFI_ID")."
				"; 
				$this->query = $str;
		return $this->execQuery($str);
    }
	
	function delete()
	{
        $str = "DELETE FROM SURAT_BPPNFI_PEGAWAI
                WHERE 
                  SURAT_BPPNFI_PEGAWAI_ID = '".$this->getField("SURAT_BPPNFI_PEGAWAI_ID")."'"; 
				  
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
				SELECT SURAT_BPPNFI_PEGAWAI_ID, A.PEGAWAI_ID, SURAT_BPPNFI_ID, NIP, NAMA, JENIS_KELAMIN, ALAMAT, TEMPAT_LAHIR, TANGGAL_LAHIR, 
      				 EMAIL, PHONE, SATUAN_KERJA_ID, CASE WHEN JENIS_KELAMIN = 'L' THEN 'Laki - Laki' WHEN JENIS_KELAMIN = 'P' THEN 'Perempuan' END JENIS_KELAMIN_INFO
				FROM SURAT_BPPNFI_PEGAWAI A
				LEFT JOIN PEGAWAI B ON A.PEGAWAI_ID = B.PEGAWAI_ID		
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
				SELECT SURAT_BPPNFI_PEGAWAI_ID, A.PEGAWAI_ID, SURAT_BPPNFI_ID, NIP, NAMA, JENIS_KELAMIN, ALAMAT, TEMPAT_LAHIR, TANGGAL_LAHIR, 
      				 EMAIL, PHONE, SATUAN_KERJA_ID, CASE WHEN JENIS_KELAMIN = 'L' THEN 'Laki - Laki' WHEN JENIS_KELAMIN = 'P' THEN 'Perempuan' END JENIS_KELAMIN_INFO
				FROM SURAT_BPPNFI_PEGAWAI A
				LEFT JOIN PEGAWAI B ON A.PEGAWAI_ID = B.PEGAWAI_ID		
				WHERE 1 = 1
				"; 
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key LIKE '%$val%' ";
		}
		
		$str .= $statement." ORDER BY SURAT_BPPNFI_PEGAWAI_ID DESC";
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
		$str = "SELECT COUNT(SURAT_BPPNFI_PEGAWAI_ID) AS ROWCOUNT FROM SURAT_BPPNFI_PEGAWAI A WHERE 1 = 1 ".$statement; 
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
		$str = "SELECT COUNT(SURAT_BPPNFI_PEGAWAI_ID) AS ROWCOUNT FROM SURAT_BPPNFI_PEGAWAI WHERE 1 = 1 "; 
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