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
  include_once("Entity.php");

  class SuratKeluarParaf extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function SuratKeluarParaf()
	{
      $this->Entity(); 
    }
	
	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("SURAT_KELUAR_PARAF_ID", $this->getNextId("SURAT_KELUAR_PARAF_ID","SURAT_KELUAR_PARAF")); 
		$str = "
				INSERT INTO SURAT_KELUAR_PARAF (
				   SURAT_KELUAR_PARAF_ID, SURAT_KELUAR_ID, SATUAN_KERJA_ID_TUJUAN, LAST_CREATE_USER, LAST_CREATE_DATE) 
				VALUES ( '".$this->getField("SURAT_KELUAR_PARAF_ID")."', '".$this->getField("SURAT_KELUAR_ID")."', 
						 '".$this->getField("SATUAN_KERJA_ID_TUJUAN")."', '".$this->getField("LAST_CREATE_USER")."', CURRENT_DATE)"; 
						 
		$this->id = $this->getField("SURAT_KELUAR_PARAF_ID");
		$this->query = $str;

		return $this->execQuery($str);
    }

    function update()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "
			   UPDATE SURAT_KELUAR_PARAF
				SET    SATUAN_KERJA_ID_TUJUAN      	= '".$this->getField("SATUAN_KERJA_ID_TUJUAN")."',
					   LAST_UPDATE_USER   	= '".$this->getField("LAST_UPDATE_USER")."',
					   LAST_UPDATE_DATE   	= CURRENT_DATE
				WHERE  SURAT_KELUAR_PARAF_ID    	= '".$this->getField("SURAT_KELUAR_PARAF_ID")."'
				"; 
				$this->query = $str;
		return $this->execQuery($str);
    }

    function updateByField()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "UPDATE SURAT_KELUAR_PARAF A SET
				  ".$this->getField("FIELD")." 		= '".$this->getField("FIELD_VALUE")."',
				  LAST_UPDATE_USER 	= '".$this->getField("LAST_UPDATE_USER")."'
				WHERE SURAT_KELUAR_PARAF_ID = ".$this->getField("SURAT_KELUAR_PARAF_ID")."
				"; 
				$this->query = $str;
		return $this->execQuery($str);
    }	
	
	function deleteParent()
	{
        $str = "DELETE FROM SURAT_KELUAR_PARAF
                WHERE 
                  SURAT_KELUAR_ID = ".$this->getField("SURAT_KELUAR_ID").""; 
				  
		$this->query = $str;
        return $this->execQuery($str);
    }
	
	function delete()
	{
        $str = "DELETE FROM SURAT_KELUAR_PARAF
                WHERE 
                  SURAT_KELUAR_PARAF_ID = ".$this->getField("SURAT_KELUAR_PARAF_ID").""; 
				  
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
    function selectByParams($paramsArray=array(),$limit=-1,$from=-1,$statement="", $order=" ORDER BY SURAT_KELUAR_PARAF_ID ASC")
	{
		$str = "
				SELECT SURAT_KELUAR_ID, SATUAN_KERJA_ID_TUJUAN, USER_ID, NAMA_USER, NAMA_SATKER, 
					   STATUS_PARAF, KODE_PARAF, LAST_CREATE_USER, LAST_CREATE_DATE, 
					   LAST_UPDATE_USER, LAST_UPDATE_DATE
				  FROM SURAT_KELUAR_PARAF A
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
    /** 
    * Hitung jumlah record berdasarkan parameter (array). 
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","nama"=>"yyy") 
    * @return long Jumlah record yang sesuai kriteria 
    **/ 
	
    function getJson($paramsArray=array(),$statement="")
	{
		$str = "
			SELECT ROW_TO_JSON(A) JSON FROM 
			(SELECT SURAT_KELUAR_ID, SATUAN_KERJA_ID_TUJUAN SATUAN_KERJA_ID, NAMA_SATKER SATUAN_KERJA, NAMA_USER NAMA_PEGAWAI, STATUS_PARAF, KODE_PARAF FROM SURAT_KELUAR_PARAF) A
			WHERE 1 = 1
		"; 
		while(list($key,$val)=each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement;
		$this->selectLimit($str,-1,-1); 
		$hasil = "[";
		$i = 0;
		while($this->nextRow())
		{
			if($i == 0)
				$hasil .= $this->getField("JSON");
			else
				$hasil .= ",".$this->getField("JSON");
			$i++;		
		}
		$hasil .= "]";	
		$hasil = str_replace("null", '""', $hasil);	
		return strtoupper($hasil);
		
    }
	
    function getCountByParams($paramsArray=array(), $statement="")
	{
		$str = "SELECT COUNT(SURAT_KELUAR_PARAF_ID) AS ROWCOUNT FROM SURAT_KELUAR_PARAF A WHERE 1 = 1 ".$statement; 
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
  } 
?>