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

  class SuratBankKeluarLampiran extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function SuratBankKeluarLampiran()
	{
      $this->Entity(); 
    }
	
	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("SURAT_BANK_KELUAR_LAMPIRAN_ID", $this->getNextId("SURAT_BANK_KELUAR_LAMPIRAN_ID","SURAT_BANK_KELUAR_LAMPIRAN")); 
		$str = "
				INSERT INTO SURAT_BANK_KELUAR_LAMPIRAN (
				   SURAT_BANK_KELUAR_LAMPIRAN_ID, SURAT_BPPNFI_ID, SURAT_BPPNFI_LAMPIRAN_ID, LAST_CREATE_USER, LAST_CREATE_DATE) 
				VALUES(
				  ".$this->getField("SURAT_BANK_KELUAR_LAMPIRAN_ID").",
				  ".$this->getField("SURAT_BPPNFI_ID").",
				  ".$this->getField("SURAT_BPPNFI_LAMPIRAN_ID").",
				  '".$this->getField("LAST_CREATE_USER")."',
				  CURRENT_DATE
				)"; 
		$this->id = $this->getField("SURAT_BANK_KELUAR_LAMPIRAN_ID");
		$this->query = $str;
		return $this->execQuery($str);
    }
	
    function update()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "UPDATE SURAT_BANK_KELUAR_LAMPIRAN SET
				  SURAT_BPPNFI_LAMPIRAN_ID 	= ".$this->getField("SURAT_BPPNFI_LAMPIRAN_ID").",
				  LAST_UPDATE_USER	= '".$this->getField("LAST_UPDATE_USER")."',
				  LAST_UPDATE_DATE	= CURRENT_DATE
				WHERE SURAT_BANK_KELUAR_LAMPIRAN_ID 	= '".$this->getField("SURAT_BANK_KELUAR_LAMPIRAN_ID")."'
				"; 
				$this->query = $str;
		return $this->execQuery($str);
    }
	
	function delete()
	{
        $str = "DELETE FROM SURAT_BANK_KELUAR_LAMPIRAN
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
				SURAT_BANK_KELUAR_LAMPIRAN_ID, A.SURAT_BPPNFI_ID, SURAT_BPPNFI_LAMPIRAN_ID, B.NOMOR
				FROM SURAT_BANK_KELUAR_LAMPIRAN A
				LEFT JOIN SURAT_BPPNFI B ON A.SURAT_BPPNFI_LAMPIRAN_ID = B.SURAT_BPPNFI_ID
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
				SURAT_BANK_KELUAR_LAMPIRAN_ID, SURAT_BPPNFI_ID, SURAT_BPPNFI_LAMPIRAN_ID
				FROM SURAT_BANK_KELUAR_LAMPIRAN
				WHERE 1 = 1
				"; 
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key LIKE '%$val%' ";
		}
		
		$str .= $statement." ORDER BY SURAT_BANK_KELUAR_LAMPIRAN_ID DESC";
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
		$str = "SELECT COUNT(1) AS ROWCOUNT FROM SURAT_BANK_KELUAR_LAMPIRAN WHERE 1 = 1 ".$statement; 
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
		$str = "SELECT COUNT(1) AS ROWCOUNT FROM SURAT_BANK_KELUAR_LAMPIRAN WHERE 1 = 1 "; 
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