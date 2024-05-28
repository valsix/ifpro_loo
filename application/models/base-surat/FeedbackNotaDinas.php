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

  class FeedbackNotaDinas extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function FeedbackNotaDinas()
	{
      $this->Entity(); 
    }
	
	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("FEEDBACK_ID", $this->getNextId("FEEDBACK_ID","FEEDBACK_NOTA_DINAS")); 
		$this->tanggal = date("Y-m-d H:i:s");


		$str = "INSERT INTO FEEDBACK_NOTA_DINAS(FEEDBACK_ID, USER_ID, URAIAN, NOTA_DINAS_ID, CATATAN, UKURAN, TIPE)
				VALUES(
				  ".$this->getField("FEEDBACK_ID").",
				  '".$this->getField("USER_ID")."',
				  ".$this->getField("URAIAN").",
				  '".$this->getField("NOTA_DINAS_ID")."', 
				  ".$this->getField("CATATAN").",
				  ".$this->getField("UKURAN").", 
				  ".$this->getField("TIPE")."
				)"; 
				
		$this->query = $str;
		$this->id= $this->getField("FEEDBACK_ID");
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
	
	function upload($table, $column, $blob, $id)
	{
		return $this->uploadBlob($table, $column, $blob, $id);
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
		$str = "SELECT FEEDBACK_ID, B.NAMA USERNAMA, C.NAMA SATKER, URAIAN, NOTA_DINAS_ID, ATTACHMENT, CATATAN, UKURAN, TIPE
				FROM FEEDBACK_NOTA_DINAS A, USERS B, SATUAN_KERJA C WHERE FEEDBACK_ID IS NOT NULL AND A.USER_ID = B.USER_ID AND B.SATUAN_KERJA_ID = C.SATUAN_KERJA_ID "; 
		
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