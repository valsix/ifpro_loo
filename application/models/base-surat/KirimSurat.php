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

  class KirimSurat extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function KirimSurat()
	{
      $this->Entity(); 
    }
	
	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("sms_id", $this->getNextId("sms_id","sms")); 
		$this->tanggal = date("Y-m-d H:i:s");

		$str = "INSERT INTO sms(sms_id, nomor, isi, status) 
				VALUES(
				  '".$this->getField("sms_id")."',				
				  '".$this->getField("nomor")."',
				  '".$this->getField("ISI")."',
				  '".$this->getField("status")."'				  				  				  
				)"; 
				
		$this->query = $str;
		return $this->execQuery($str);

    }

    function update()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "UPDATE sms SET
				  status = '1'
				WHERE sms_id = '".$this->getField("sms_id")."'
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
    function selectByParams($paramsArray=array(),$limit=-1,$from=-1,$varStatement="")
	{
		$str = "SELECT sms_id, nomor, isi  
				FROM sms WHERE 1 = 1 "; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = $val ";
		}
		
		$this->query = $str;
		$str .= $varStatement." ORDER BY sms_id ASC";
		return $this->selectLimit($str,$limit,$from); 
    }
    
	function selectByParamsLike($paramsArray=array(),$limit=-1,$from=-1, $varStatement="")
	{
		$str = "SELECT a.ARID, a.AKID, a.UID, a.tanggal, a.judul, a.isi, a.status_approve,
					   ak.AKID, ak.nama as ak_nama,
					   u.UID, u.nama as u_nama
				FROM artikel a, artikel_kategori ak, users u WHERE ARID IS NOT NULL AND ak.AKID = a.AKID AND u.UID = a.UID"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key LIKE '%$val%' ";
		}
		
		$this->query = $str;
		$str .= $varStatement." ORDER BY ARID DESC";
				
		return $this->selectLimit($str,$limit,$from); 
    }	
    /** 
    * Hitung jumlah record berdasarkan parameter (array). 
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","nama"=>"yyy") 
    * @return long Jumlah record yang sesuai kriteria 
    **/ 
    function getCountByParams($paramsArray=array(),$varStatement="")
	{
		$str = "SELECT COUNT(a.ARID) AS ROWCOUNT FROM artikel a, users u, artikel_kategori ak  WHERE a.ARID IS NOT NULL AND ak.AKID = a.AKID AND u.UID = a.UID ".$varStatement; 
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

    function getCountByParamsLike($paramsArray=array(),$varStatement="")
	{
		$str = "SELECT COUNT(a.ARID) AS ROWCOUNT FROM artikel a, users u, artikel_kategori ak WHERE a.ARID IS NOT NULL AND ak.AKID = a.AKID AND u.UID = a.UID ".$varStatement; 
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