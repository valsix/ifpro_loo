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

  class FeedbackSuratKeluar extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function FeedbackSuratKeluar()
	{
      $this->Entity(); 
    }
	
	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("feedback_id", $this->getNextId("FEEDBACK_ID","FEEDBACK_SURAT_KELUAR")); 
		$this->tanggal = date("Y-m-d H:i:s");


		$str = "INSERT INTO FEEDBACK_SURAT_KELUAR(FEEDBACK_ID, USER_ID, URAIAN, SURAT_KELUAR_ID, ATTACHMENT, CATATAN, UKURAN, TIPE)
				VALUES(
				  ".$this->getField("feedback_id").",
				  '".$this->getField("user_id")."',
				  ".$this->getField("uraian").",
				  '".$this->getField("surat_keluar_id")."', 
				  ".$this->getField("attachment").", 
				  ".$this->getField("catatan").",
				  ".$this->getField("ukuran").", 
				  ".$this->getField("tipe")."
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
		$str = "SELECT FEEDBACK_ID, B.NAMA USERNAMA, C.NAMA SATKER, URAIAN, SURAT_KELUAR_ID, ATTACHMENT, CATATAN, UKURAN, TIPE
				FROM FEEDBACK_SURAT_KELUAR A, USERS B, SATUAN_KERJA C WHERE FEEDBACK_ID IS NOT NULL AND A.USER_ID = B.USER_ID AND B.SATUAN_KERJA_ID = C.SATUAN_KERJA_ID "; 
				
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = $val ";
		}
		
		$this->query = $str;
		$str .= $varStatement." ORDER BY FEEDBACK_ID DESC";
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