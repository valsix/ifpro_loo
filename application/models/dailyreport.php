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

  class DailyReport extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function DailyReport()
	{
      $this->Entity(); 
    }
	
	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("DAILY_REPORT_ID", $this->getNextId("DAILY_REPORT_ID","AKTIFITAS.DAILY_REPORT")); 
		$str = "
				INSERT INTO AKTIFITAS.DAILY_REPORT (
				   DAILY_REPORT_ID, PEGAWAI_ID, NAMA_PEGAWAI, 
				   TANGGAL_AWAL, TANGGAL_AKHIR, JAM_AWAL, 
				   JAM_AKHIR, KETERANGAN, PEGAWAI_ID_PENUGAS, 
				   NAMA_PEGAWAI_PENUGAS, STATUS, LAST_CREATE_USER, 
				   LAST_CREATE_DATE, PROYEK_ID) 
				VALUES ( '".$this->getField("DAILY_REPORT_ID")."', '".$this->getField("PEGAWAI_ID")."', '".$this->getField("NAMA_PEGAWAI")."',
					".$this->getField("TANGGAL_AWAL").", ".$this->getField("TANGGAL_AKHIR").", '".$this->getField("JAM_AWAL")."',
					'".$this->getField("JAM_AKHIR")."', '".$this->getField("KETERANGAN")."', '".$this->getField("PEGAWAI_ID_PENUGAS")."',
					'".$this->getField("NAMA_PEGAWAI_PENUGAS")."', '".$this->getField("STATUS")."', '".$this->getField("LAST_CREATE_USER")."', 
					CURRENT_DATE, '".$this->getField("PROYEK_ID")."')"; 
		$this->id = $this->getField("DAILY_REPORT_ID");
		$this->query = $str;

		return $this->execQuery($str);
    }

    function update()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "
			   UPDATE AKTIFITAS.DAILY_REPORT
				SET    PEGAWAI_ID         	= '".$this->getField("PEGAWAI_ID")."',
					   NAMA_PEGAWAI       	= '".$this->getField("NAMA_PEGAWAI")."',
					   TANGGAL_AWAL       	= ".$this->getField("TANGGAL_AWAL").",
					   TANGGAL_AKHIR      	= ".$this->getField("TANGGAL_AKHIR").",
					   JAM_AWAL           	= '".$this->getField("JAM_AWAL")."',
					   JAM_AKHIR          	= '".$this->getField("JAM_AKHIR")."',
					   KETERANGAN         	= '".$this->getField("KETERANGAN")."',
					   PEGAWAI_ID_PENUGAS 	= '".$this->getField("PEGAWAI_ID_PENUGAS")."',
					   NAMA_PEGAWAI_PENUGAS = '".$this->getField("NAMA_PEGAWAI_PENUGAS")."',
					   STATUS             	= '".$this->getField("STATUS")."',
					   LAST_UPDATE_USER   	= '".$this->getField("LAST_UPDATE_USER")."',
					   LAST_UPDATE_DATE   	= CURRENT_DATE,
					   PROYEK_ID		   	= '".$this->getField("PROYEK_ID")."'
				WHERE  DAILY_REPORT_ID    	= '".$this->getField("DAILY_REPORT_ID")."'
				"; 
				$this->query = $str;
		return $this->execQuery($str);
    }
	
	function updateStatus()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "
			   UPDATE AKTIFITAS.DAILY_REPORT
				SET    JAM_SELESAI	      = '".$this->getField("JAM_SELESAI")."',
					   TANGGAL_SELESAI	  = ".$this->getField("TANGGAL_SELESAI").",
					   LAST_UPDATE_USER   = '".$this->getField("LAST_UPDATE_USER")."',
					   LAST_UPDATE_DATE   = CURRENT_DATE,
					   STATUS   		  = '".$this->getField("STATUS")."'
				WHERE  DAILY_REPORT_ID    = '".$this->getField("DAILY_REPORT_ID")."'
				"; 
				$this->query = $str;
		return $this->execQuery($str);
    }
	
    function updateByField()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "UPDATE AKTIFITAS.DAILY_REPORT A SET
				  ".$this->getField("FIELD")." 		= '".$this->getField("FIELD_VALUE")."',
				  ".$this->getField("FIELD_VALIDATOR")." 	= '".$this->getField("FIELD_VALUE_VALIDATOR")."'
				WHERE DAILY_REPORT_ID = ".$this->getField("DAILY_REPORT_ID")."
				"; 
				$this->query = $str;
		return $this->execQuery($str);
    }	
	
	function delete()
	{
        $str = "DELETE FROM AKTIFITAS.DAILY_REPORT
                WHERE 
                  DAILY_REPORT_ID = ".$this->getField("DAILY_REPORT_ID").""; 
				  
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
    function selectByParams($paramsArray=array(),$limit=-1,$from=-1,$statement="", $order=" ORDER BY TANGGAL_AWAL, JAM ASC")
	{
		$str = "
				SELECT 
				DAILY_REPORT_ID, PEGAWAI_ID, NAMA_PEGAWAI, 
				   TANGGAL_AWAL, TANGGAL_AKHIR, JAM_AWAL, 
				   JAM_AKHIR, KETERANGAN, PEGAWAI_ID_PENUGAS, 
				   STATUS, LAST_CREATE_USER, LAST_CREATE_DATE, 
				   LAST_UPDATE_USER, LAST_UPDATE_DATE, PROYEK_ID
				FROM AKTIFITAS.DAILY_REPORT A
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
	
	function selectByParamsMonitoring($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order="ORDER BY DAILY_REPORT_ID DESC")
	{
		$str = "
				SELECT 
				DAILY_REPORT_ID, A.PEGAWAI_ID, NAMA_PEGAWAI, 
				   TANGGAL_AWAL, TANGGAL_AKHIR, JAM_AWAL, 
				   JAM_AKHIR, KETERANGAN, PEGAWAI_ID_PENUGAS, 
				   STATUS, A.LAST_CREATE_USER, A.LAST_CREATE_DATE, 
				   A.LAST_UPDATE_USER, A.LAST_UPDATE_DATE
				FROM AKTIFITAS.DAILY_REPORT A
				LEFT JOIN PEGAWAI B ON A.PEGAWAI_ID = B.PEGAWAI_ID
				LEFT JOIN PEGAWAI C ON A.PEGAWAI_ID_PENUGAS = C.PEGAWAI_ID
				LEFT JOIN PROYEK D ON A.PROYEK_ID = D.PROYEK_ID
                  WHERE 1=1  
				"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$order;
		$this->query = $str;
		//echo $str;
		return $this->selectLimit($str,$limit,$from); 
    }
	
	function selectByParamsMonitoringLog($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order="ORDER BY TANGGAL_AWAL DESC")
	{
		$str = "
				SELECT 
				PEGAWAI_ID, NAMA_PEGAWAI, TANGGAL_AWAL TANGGAL, TANGGAL_AWAL TANGGAL_DESC, 
				(SELECT COUNT(1) FROM AKTIFITAS.DAILY_REPORT X WHERE X.PEGAWAI_ID = A.PEGAWAI_ID AND X.STATUS = '0' AND X.TANGGAL_AWAL = A.TANGGAL_AWAL) JUMLAH_PROGRESS,
				(SELECT COUNT(1) FROM AKTIFITAS.DAILY_REPORT X WHERE X.PEGAWAI_ID = A.PEGAWAI_ID AND X.STATUS IN ('1', '2') AND X.TANGGAL_AWAL = A.TANGGAL_AWAL) JUMLAH_SELESAI
				FROM AKTIFITAS.DAILY_REPORT A
				WHERE 1=1
				"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." GROUP BY PEGAWAI_ID, NAMA_PEGAWAI, TANGGAL_AWAL ".$order;
		$this->query = $str;
		//echo $str;
		return $this->selectLimit($str,$limit,$from); 
    }
	
	function selectByParamsMonitoringLogDetil($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order="ORDER BY PEGAWAI_ID ASC")
	{
		
		$str = "
				SELECT 
				PEGAWAI_ID, DAILY_REPORT_ID, NAMA_PEGAWAI, TANGGAL_AWAL TANGGAL, TANGGAL_AWAL TANGGAL_DESC, 
				(SELECT COUNT(1) FROM AKTIFITAS.DAILY_REPORT X WHERE X.PEGAWAI_ID = A.PEGAWAI_ID AND X.STATUS = '0' AND X.TANGGAL_AWAL = A.TANGGAL_AWAL) JUMLAH_PROGRESS,
				(SELECT COUNT(1) FROM AKTIFITAS.DAILY_REPORT X WHERE X.PEGAWAI_ID = A.PEGAWAI_ID AND X.STATUS IN ('1', '2') AND X.TANGGAL_AWAL = A.TANGGAL_AWAL) JUMLAH_SELESAI, JAM_AWAL, JAM_AKHIR, 
				KETERANGAN, STATUS
				FROM AKTIFITAS.DAILY_REPORT A
				WHERE 1=1
				"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement."  ".$order;
		$this->query = $str;
		//echo $str;
		return $this->selectLimit($str,$limit,$from); 
    }
	
	function selectByParamsLike($paramsArray=array(),$limit=-1,$from=-1, $statement="")
	{
		$str = "    
				SELECT 
				DAILY_REPORT_ID, A.PEGAWAI_ID, NAMA_PEGAWAI, 
				   TANGGAL_AWAL, TANGGAL_AKHIR, JAM_AWAL, 
				   JAM_AKHIR, KETERANGAN, PEGAWAI_ID_PENUGAS, 
				   STATUS, A.LAST_CREATE_USER, A.LAST_CREATE_DATE, 
				   A.LAST_UPDATE_USER, A.LAST_UPDATE_DATE
				FROM AKTIFITAS.DAILY_REPORT
				WHERE 1 = 1
			"; 
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key LIKE '%$val%' ";
		}
		
		$str .= $statement." ORDER BY DAILY_REPORT_ID DESC";
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
		$str = "SELECT COUNT(DAILY_REPORT_ID) AS ROWCOUNT FROM AKTIFITAS.DAILY_REPORT WHERE 1 = 1 ".$statement; 
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
		$str = "SELECT COUNT(DAILY_REPORT_ID) AS ROWCOUNT FROM AKTIFITAS.DAILY_REPORT WHERE 1 = 1 ".$statement; 
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
		$str = "SELECT COUNT(DAILY_REPORT_ID) AS ROWCOUNT FROM AKTIFITAS.DAILY_REPORT WHERE 1 = 1 "; 
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

    function getStatusSelesai($reqTanggalSelesai, $reqJamSelesai, $paramsArray=array(), $statement="")
	{
		$str = "SELECT
				    CASE WHEN TO_TIMESTAMP('".$reqTanggalSelesai." ".$reqJamSelesai."', 'DD-MM-YYYY HH24:MI') 
				    		BETWEEN TO_TIMESTAMP(TO_CHAR(TANGGAL_AWAL, 'DD-MM-YYYY') || ' ' || JAM_AWAL, 'DD-MM-YYYY HH24:MI') AND TO_TIMESTAMP(TO_CHAR(TANGGAL_AKHIR, 'DD-MM-YYYY') || ' ' || JAM_AKHIR, 'DD-MM-YYYY HH24:MI') THEN '1'
				    ELSE '2'
				    END ROWCOUNT 
				FROM AKTIFITAS.DAILY_REPORT WHERE 1 = 1 ".$statement; 
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