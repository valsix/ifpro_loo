<? 
/* *******************************************************************************************************
MODUL NAME 			: IMASYS
FILE NAME 			: 
AUTHOR				: 
VERSION				: 1.0
MODIFICATION DOC	:
DESCRIPTION			: 
***************************************************************************************************** */

  /***
  * Entity-base class untuk mengimplementasikan tabel AGENDA.
  * 
  ***/
  include_once("Entity.php");

  class Arsip extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function Arsip()
	{
      $this->Entity(); 
    }
	
	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("ARSIP_ID", $this->getNextId("ARSIP_ID","ARSIP")); 		

		$str = "  
                  INSERT INTO ARSIP(
                    ARSIP_ID, CABANG_ID, SATUAN_KERJA_ID, KLASIFIKASI_ID, KLASIFIKASI_KODE, 
                    PENYUSUTAN_AKHIR_ID, PENYUSUTAN_AKHIR_KODE, LOKASI_ARSIP_ID, 
                    LOKASI_ARSIP_KODE, KODE, NAMA, KETERANGAN, RETENSI_AKTIF, 
                    RETENSI_INAKTIF, LAST_CREATE_USER, LAST_CREATE_DATE)
                  VALUES ( '".$this->getField("ARSIP_ID")."', '".$this->getField("CABANG_ID")."',  '".$this->getField("SATUAN_KERJA_ID")."',  '".$this->getField("KLASIFIKASI_ID")."', '".$this->getField("KLASIFIKASI_KODE")."', 
                  '".$this->getField("PENYUSUTAN_AKHIR_ID")."', '".$this->getField("PENYUSUTAN_AKHIR_KODE")."', '".$this->getField("LOKASI_ARSIP_ID")."', 
                  '".$this->getField("LOKASI_ARSIP_KODE")."', '".$this->getField("KODE")."',  '".$this->getField("NAMA")."', 
                   '".$this->getField("KETERANGAN")."',  '".$this->getField("RETENSI_AKTIF")."', 
                  '".$this->getField("RETENSI_INAKTIF")."', '".$this->getField("LAST_CREATE_USER")."', CURRENT_DATE
				)"; 
		$this->query = $str;
		//echo $str;
		return $this->execQuery($str);
    }

    function update()
	{
		$str = "
                
                UPDATE arsip
                SET  
                    CABANG_ID               ='".$this->getField("CABANG_ID")."', 
                    SATUAN_KERJA_ID         ='".$this->getField("SATUAN_KERJA_ID")."', 
                    KLASIFIKASI_ID          ='".$this->getField("KLASIFIKASI_ID")."', 
                    KLASIFIKASI_KODE        ='".$this->getField("KLASIFIKASI_KODE")."', 
                    PENYUSUTAN_AKHIR_ID     ='".$this->getField("PENYUSUTAN_AKHIR_ID")."', 
                    PENYUSUTAN_AKHIR_KODE   ='".$this->getField("PENYUSUTAN_AKHIR_KODE")."', 
                    LOKASI_ARSIP_ID         ='".$this->getField("LOKASI_ARSIP_ID")."', 
                    LOKASI_ARSIP_KODE       ='".$this->getField("LOKASI_ARSIP_KODE")."', 
                    KODE                    ='".$this->getField("KODE")."', 
                    NAMA                    ='".$this->getField("NAMA")."', 
                    KETERANGAN              ='".$this->getField("KETERANGAN")."', 
                    RETENSI_AKTIF           ='".$this->getField("RETENSI_AKTIF")."', 
                    RETENSI_INAKTIF         ='".$this->getField("RETENSI_INAKTIF")."', 
                    LAST_UPDATE_USER        ='".$this->getField("LAST_UPDATE_USER")."', 
                    LAST_UPDATE_DATE        = CURRENT_DATE
                WHERE ARSIP_ID              ='".$this->getField("ARSIP_ID")."';

			 "; 
		$this->query = $str;
		//echo $str;
		return $this->execQuery($str);
    }
	
    function updateByField()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "UPDATE ARSIP A SET
				  ".$this->getField("FIELD")." = '".$this->getField("FIELD_VALUE")."'
				WHERE ARSIP_ID = ".$this->getField("ARSIP_ID")."
				"; 
				$this->query = $str;
	
		return $this->execQuery($str);
    }	

	function delete()
	{
        $str = "DELETE FROM ARSIP
                WHERE 
                ARSIP_ID = ".$this->getField("ARSIP_ID").""; 
				  
		$this->query = $str;
        return $this->execQuery($str);
    }

    /** 
    * Cari record berdasarkan array parameter dan limit tampilan 
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","IJIN_USAHA_ID"=>"yyy") 
    * @param int limit Jumlah maksimal record yang akan diambil 
    * @param int from Awal record yang diambil 
    * @return boolean True jika sukses, false jika tidak 
    **/ 


    
    function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement="")
	{
		$str = "    SELECT 
                        ARSIP_ID, CABANG_ID, SATUAN_KERJA_ID, KLASIFIKASI_ID, KLASIFIKASI_KODE, 
                        PENYUSUTAN_AKHIR_ID, PENYUSUTAN_AKHIR_KODE, LOKASI_ARSIP_ID, 
                        LOKASI_ARSIP_KODE, URUT, KODE, NAMA, KETERANGAN, RETENSI_AKTIF, 
                        RETENSI_INAKTIF, LAST_CREATE_USER, LAST_CREATE_DATE, LAST_UPDATE_USER, 
                        LAST_UPDATE_DATE
                FROM ARSIP A
				        WHERE ARSIP_ID IS NOT NULL
				"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ORDER BY A.ARSIP_ID DESC";
		$this->query = $str;
		return $this->selectLimit($str,$limit,$from); 
    }

    function selectByParamsMonitoring($paramsArray=array(),$limit=-1,$from=-1, $statement="")
	{
		$str = "   
			SELECT 
                ARSIP_ID, A.CABANG_ID, A.SATUAN_KERJA_ID, KLASIFIKASI_ID, KLASIFIKASI_KODE, 
                PENYUSUTAN_AKHIR_ID, PENYUSUTAN_AKHIR_KODE, A.LOKASI_ARSIP_ID, B.KODE||' - '||B.NAMA LOKASI_ARSIP, 
                LOKASI_ARSIP_KODE, URUT, A.KODE, A.NAMA, A.KETERANGAN, RETENSI_AKTIF, 
                RETENSI_INAKTIF, A.LAST_CREATE_USER, A.LAST_CREATE_DATE, A.LAST_UPDATE_USER, 
                A.LAST_UPDATE_DATE
            FROM ARSIP A
			LEFT JOIN LOKASI_ARSIP B ON B.LOKASI_ARSIP_ID=A.LOKASI_ARSIP_ID::varchar
			WHERE ARSIP_ID IS NOT NULL
		"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ORDER BY A.ARSIP_ID DESC";
		$this->query = $str;
		return $this->selectLimit($str,$limit,$from); 
    }
    
	function selectByParamsLike($paramsArray=array(),$limit=-1,$from=-1, $statement="")
	{
		$str = "    SELECT 
                        ARSIP_ID, CABANG_ID, SATUAN_KERJA_ID, KLASIFIKASI_ID, KLASIFIKASI_KODE, 
                        PENYUSUTAN_AKHIR_ID, PENYUSUTAN_AKHIR_KODE, LOKASI_ARSIP_ID, 
                        LOKASI_ARSIP_KODE, URUT, KODE, NAMA, KETERANGAN, RETENSI_AKTIF, 
                        RETENSI_INAKTIF, LAST_CREATE_USER, LAST_CREATE_DATE, LAST_UPDATE_USER, 
                        LAST_UPDATE_DATE
                    FROM ARSIP A
                    WHERE ARSIP_ID IS NOT NULL
			    "; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key LIKE '%$val%' ";
		}
		
		$this->query = $str;
		$str .= $statement." ORDER BY A.NAMA ASC";
		return $this->selectLimit($str,$limit,$from); 
    }	
    /** 
    * Hitung jumlah record berdasarkan parameter (array). 
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","IJIN_USAHA_ID"=>"yyy") 
    * @return long Jumlah record yang sesuai kriteria 
    **/ 
    function getCountByParams($paramsArray=array(), $statement="")
	{
		$str = "SELECT COUNT(ARSIP_ID) AS ROWCOUNT FROM ARSIP A
		        WHERE ARSIP_ID IS NOT NULL ".$statement; 
		
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

    function getCountByParamsMonitoring($paramsArray=array(), $statement="")
	{
		$str = "SELECT COUNT(ARSIP_ID) AS ROWCOUNT FROM ARSIP A
			LEFT JOIN LOKASI_ARSIP B ON B.LOKASI_ARSIP_ID=A.LOKASI_ARSIP_ID::varchar
		    WHERE ARSIP_ID IS NOT NULL ".$statement; 
		
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

    function getCountByParamsLike($paramsArray=array(), $statement="")
	{
		$str = "SELECT COUNT(ARSIP_ID) AS ROWCOUNT FROM ARSIP A
		        WHERE ARSIP_ID IS NOT NULL ".$statement; 
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