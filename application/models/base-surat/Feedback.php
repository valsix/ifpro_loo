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

  class Feedback extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function Feedback()
	{
      $this->Entity(); 
    }
	
	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("FEEDBACK_ID", $this->getNextId("FEEDBACK_ID","FEEDBACK")); 

		$str = "INSERT INTO FEEDBACK(FEEDBACK_ID, USER_ID, URAIAN, SURAT_MASUK_ID, ATTACHMENT, CATATAN, UKURAN, TIPE)
				VALUES(
				  ".$this->getField("FEEDBACK_ID").",
				  '".$this->getField("USER_ID")."',
				  ".$this->getField("URAIAN").",
				  '".$this->getField("SURAT_MASUK_ID")."', 
				  ".$this->getField("ATTACHMENT").", 
				  ".$this->getField("CATATAN").",
				  ".$this->getField("UKURAN").", 
				  ".$this->getField("TIPE")."
				)"; 
				
		$this->query = $str;
		return $this->execQuery($str);
    }

    function update()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "UPDATE artikel SET
				  AKID = '".$this->getField("AKID")."',
				  judul = '".$this->getField("judul")."',
				  isi = '".$this->getField("isi")."',
				  status_approve = '".$this->getField("status_approve")."'
				WHERE ARID = '".$this->getField("ARID")."'
				"; 
				$this->query = $str;
		return $this->execQuery($str);
    }
	
	function delete()
	{
        $str = "DELETE FROM artikel
                WHERE 
                  ARID = '".$this->getField("ARID")."'"; 
				  
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
		$str = "SELECT FEEDBACK_ID, B.NAMA USERNAMA, C.NAMA SATKER, URAIAN, SURAT_MASUK_ID, ATTACHMENT, CATATAN, UKURAN, TIPE
				FROM FEEDBACK A, USERS B, SATUAN_KERJA C WHERE FEEDBACK_ID IS NOT NULL AND A.USER_ID = B.USER_ID AND B.SATUAN_KERJA_ID = C.SATUAN_KERJA_ID "; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = $val ";
		}
		
		$str .= $varStatement." ORDER BY FEEDBACK_ID DESC";
		$this->query = $str;
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