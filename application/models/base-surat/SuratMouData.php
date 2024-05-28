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

  class SuratMouData extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function SuratMouData()
	{
      $this->Entity(); 
    }
	
	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("SURAT_MOU_DATA_ID", $this->getNextId("SURAT_MOU_DATA_ID","SURAT_MOU_DATA")); 
		$str = "
				INSERT INTO SURAT_MOU_DATA (
				   SURAT_MOU_DATA_ID, SURAT_BPPNFI_ID, NAMA_PERUSAHAAN1, LOKASI_PERUSAHAAN1, 
				   PHONE_PERUSAHAAN1, FAX_PERUSAHAAN1, NO_AKTA1, TANGGAL_AKTA1, 
				   TANGGAL_PENGESAHAN1, PEMBUAT_AKTA1, NAMA_DIREKTUR1, NAMA_DIREKSI1, 
				   BIDANG_USAHA1, UP_NAMA1, UP_JABATAN1, TTD_NAMA1, TTD_JABATAN1, 
				   NAMA_PERUSAHAAN2, LOKASI_PERUSAHAAN2, PHONE_PERUSAHAAN2, FAX_PERUSAHAAN2, 
				   NO_AKTA2, TANGGAL_AKTA2, TANGGAL_PENGESAHAN2, PEMBUAT_AKTA2, 
				   NAMA_DIREKTUR2, NAMA_DIREKSI2, BIDANG_USAHA2, UP_NAMA2, UP_JABATAN2, 
				   TTD_NAMA2, TTD_JABATAN2, JANGKA_WAKTU, LAST_CREATE_USER, LAST_CREATE_DATE) 
				VALUES(
				  ".$this->getField("SURAT_MOU_DATA_ID").",
				  ".$this->getField("SURAT_BPPNFI_ID").",
				  '".$this->getField("NAMA_PERUSAHAAN1")."',
				  '".$this->getField("LOKASI_PERUSAHAAN1")."',
				  '".$this->getField("PHONE_PERUSAHAAN1")."',
				  '".$this->getField("FAX_PERUSAHAAN1")."',
				  '".$this->getField("NO_AKTA1")."',
				  ".$this->getField("TANGGAL_AKTA1").",
				  ".$this->getField("TANGGAL_PENGESAHAN1").",
				  '".$this->getField("PEMBUAT_AKTA1")."',
				  '".$this->getField("NAMA_DIREKTUR1")."',
				  '".$this->getField("NAMA_DIREKSI1")."',
				  '".$this->getField("BIDANG_USAHA1")."',
				  '".$this->getField("UP_NAMA1")."',
				  '".$this->getField("UP_JABATAN1")."',
				  '".$this->getField("TTD_NAMA1")."',
				  '".$this->getField("TTD_JABATAN1")."',
				  '".$this->getField("NAMA_PERUSAHAAN2")."',
				  '".$this->getField("LOKASI_PERUSAHAAN2")."',
				  '".$this->getField("PHONE_PERUSAHAAN2")."',
				  '".$this->getField("FAX_PERUSAHAAN2")."',
				  '".$this->getField("NO_AKTA2")."',
				  ".$this->getField("TANGGAL_AKTA2").",
				  ".$this->getField("TANGGAL_PENGESAHAN2").",
				  '".$this->getField("PEMBUAT_AKTA2")."',
				  '".$this->getField("NAMA_DIREKTUR2")."',
				  '".$this->getField("NAMA_DIREKSI2")."',
				  '".$this->getField("BIDANG_USAHA2")."',
				  '".$this->getField("UP_NAMA2")."',
				  '".$this->getField("UP_JABATAN2")."',
				  '".$this->getField("TTD_NAMA2")."',
				  '".$this->getField("TTD_JABATAN2")."',
				  ".$this->getField("JANGKA_WAKTU").",
				  '".$this->getField("LAST_CREATE_USER")."',
				  CURRENT_DATE
				)"; 
		$this->id = $this->getField("SURAT_MOU_DATA_ID");
		$this->query = $str;
		return $this->execQuery($str);
    }
	
    function update()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "UPDATE SURAT_MOU_DATA 
				SET
				  NAMA_PERUSAHAAN1		= '".$this->getField("NAMA_PERUSAHAAN1")."',
				  LOKASI_PERUSAHAAN1	= '".$this->getField("LOKASI_PERUSAHAAN1")."',
				  PHONE_PERUSAHAAN1		= '".$this->getField("PHONE_PERUSAHAAN1")."',
				  FAX_PERUSAHAAN1		= '".$this->getField("FAX_PERUSAHAAN1")."',
				  NO_AKTA1				= '".$this->getField("NO_AKTA1")."',
				  TANGGAL_AKTA1			= ".$this->getField("TANGGAL_AKTA1").",
				  TANGGAL_PENGESAHAN1	= ".$this->getField("TANGGAL_PENGESAHAN1").",
				  PEMBUAT_AKTA1			= '".$this->getField("PEMBUAT_AKTA1")."',
				  NAMA_DIREKTUR1		= '".$this->getField("NAMA_DIREKTUR1")."',
				  NAMA_DIREKSI1			= '".$this->getField("NAMA_DIREKSI1")."',
				  BIDANG_USAHA1			= '".$this->getField("BIDANG_USAHA1")."',
				  UP_NAMA1				= '".$this->getField("UP_NAMA1")."',
				  UP_JABATAN1			= '".$this->getField("UP_JABATAN1")."',
				  TTD_NAMA1				= '".$this->getField("TTD_NAMA1")."',
				  TTD_JABATAN1			= '".$this->getField("TTD_JABATAN1")."',
				  NAMA_PERUSAHAAN2		= '".$this->getField("NAMA_PERUSAHAAN2")."',
				  LOKASI_PERUSAHAAN2	= '".$this->getField("LOKASI_PERUSAHAAN2")."',
				  PHONE_PERUSAHAAN2		= '".$this->getField("PHONE_PERUSAHAAN2")."',
				  FAX_PERUSAHAAN2		= '".$this->getField("FAX_PERUSAHAAN2")."',
				  NO_AKTA2				= '".$this->getField("NO_AKTA2")."',
				  TANGGAL_AKTA2			= ".$this->getField("TANGGAL_AKTA2").",
				  TANGGAL_PENGESAHAN2	= ".$this->getField("TANGGAL_PENGESAHAN2").",
				  PEMBUAT_AKTA2			= '".$this->getField("PEMBUAT_AKTA2")."',
				  NAMA_DIREKTUR2		= '".$this->getField("NAMA_DIREKTUR2")."',
				  NAMA_DIREKSI2			= '".$this->getField("NAMA_DIREKSI2")."',
				  BIDANG_USAHA2			= '".$this->getField("BIDANG_USAHA2")."',
				  UP_NAMA2				= '".$this->getField("UP_NAMA2")."',
				  UP_JABATAN2			= '".$this->getField("UP_JABATAN2")."',
				  TTD_NAMA2				= '".$this->getField("TTD_NAMA2")."',
				  TTD_JABATAN2			= '".$this->getField("TTD_JABATAN2")."',
				  JANGKA_WAKTU			= ".$this->getField("JANGKA_WAKTU").",
				  LAST_UPDATE_USER		= '".$this->getField("LAST_UPDATE_USER")."',
				  LAST_UPDATE_DATE		= CURRENT_DATE
				WHERE SURAT_MOU_DATA_ID 	= '".$this->getField("SURAT_MOU_DATA_ID")."'
				"; 
				$this->query = $str;
		return $this->execQuery($str);
    }
	
	function delete()
	{
        $str = "DELETE FROM SURAT_MOU_DATA
                WHERE 
                  SURAT_BPPNFI_ID = '".$this->getField("SURAT_BPPNFI_ID")."'"; 
				  
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
				   SURAT_MOU_DATA_ID, SURAT_BPPNFI_ID, NAMA_PERUSAHAAN1, LOKASI_PERUSAHAAN1, 
				   PHONE_PERUSAHAAN1, FAX_PERUSAHAAN1, NO_AKTA1, TANGGAL_AKTA1, 
				   TANGGAL_PENGESAHAN1, PEMBUAT_AKTA1, NAMA_DIREKTUR1, NAMA_DIREKSI1, 
				   BIDANG_USAHA1, UP_NAMA1, UP_JABATAN1, TTD_NAMA1, TTD_JABATAN1, 
				   NAMA_PERUSAHAAN2, LOKASI_PERUSAHAAN2, PHONE_PERUSAHAAN2, FAX_PERUSAHAAN2, 
				   NO_AKTA2, TANGGAL_AKTA2, TANGGAL_PENGESAHAN2, PEMBUAT_AKTA2, 
				   NAMA_DIREKTUR2, NAMA_DIREKSI2, BIDANG_USAHA2, UP_NAMA2, UP_JABATAN2, 
				   TTD_NAMA2, TTD_JABATAN2, JANGKA_WAKTU
				FROM SURAT_MOU_DATA
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
				   SURAT_MOU_DATA_ID, SURAT_BPPNFI_ID, NAMA_PERUSAHAAN1, LOKASI_PERUSAHAAN1, 
				   PHONE_PERUSAHAAN1, FAX_PERUSAHAAN1, NO_AKTA1, TANGGAL_AKTA1, 
				   TANGGAL_PENGESAHAN1, PEMBUAT_AKTA1, NAMA_DIREKTUR1, NAMA_DIREKSI1, 
				   BIDANG_USAHA1, UP_NAMA1, UP_JABATAN1, TTD_NAMA1, TTD_JABATAN1, 
				   NAMA_PERUSAHAAN2, LOKASI_PERUSAHAAN2, PHONE_PERUSAHAAN2, FAX_PERUSAHAAN2, 
				   NO_AKTA2, TANGGAL_AKTA2, TANGGAL_PENGESAHAN2, PEMBUAT_AKTA2, 
				   NAMA_DIREKTUR2, NAMA_DIREKSI2, BIDANG_USAHA2, UP_NAMA2, UP_JABATAN2, 
				   TTD_NAMA2, TTD_JABATAN2, JANGKA_WAKTU
				FROM SURAT_MOU_DATA
				WHERE 1 = 1
				"; 
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key LIKE '%$val%' ";
		}
		
		$str .= $statement." ORDER BY SURAT_MOU_DATA_ID DESC";
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
		$str = "SELECT COUNT(1) AS ROWCOUNT FROM SURAT_MOU_DATA WHERE 1 = 1 ".$statement; 
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
		$str = "SELECT COUNT(1) AS ROWCOUNT FROM SURAT_MOU_DATA WHERE 1 = 1 "; 
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