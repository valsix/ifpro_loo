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
  * Entity-base class untuk mengimplementasikan tabel KAPAL_JENIS.
  * 
  ***/
  include_once("Entity.php");

  class PermohonanCutiTahunan extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function PermohonanCutiTahunan()
	{
      $this->Entity(); 
    }
	
	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("PERMOHONAN_CUTI_TAHUNAN_ID", $this->getNextId("PERMOHONAN_CUTI_TAHUNAN_ID","PERMOHONAN_CUTI_TAHUNAN"));

		$str = "
				INSERT INTO PERMOHONAN_CUTI_TAHUNAN (
				   PERMOHONAN_CUTI_TAHUNAN_ID, PEGAWAI_ID, TAHUN, NOMOR, 
				   TANGGAL, JABATAN, CABANG, 
				   DEPARTEMEN, SUB_DEPARTEMEN, TANGGAL_AWAL, 
				   TANGGAL_AKHIR, LAMA_CUTI, KETERANGAN, 
				   ALAMAT, TELEPON, PEGAWAI_ID_APPROVAL1, PEGAWAI_ID_APPROVAL2, 
				   LAST_CREATE_USER, LAST_CREATE_DATE, 
				   STATUS_TUNDA, CABANG_ID, APPROVAL1) 
				VALUES ( '".$this->getField("PERMOHONAN_CUTI_TAHUNAN_ID")."', '".$this->getField("PEGAWAI_ID")."', '".$this->getField("TAHUN")."', '".$this->getField("NOMOR")."',
					".$this->getField("TANGGAL").", '".$this->getField("JABATAN")."', '".$this->getField("CABANG")."',
					'".$this->getField("DEPARTEMEN")."', '".$this->getField("SUB_DEPARTEMEN")."', ".$this->getField("TANGGAL_AWAL").",
					".$this->getField("TANGGAL_AKHIR").", '".$this->getField("LAMA_CUTI")."', '".$this->getField("KETERANGAN")."',
					'".$this->getField("ALAMAT")."', '".$this->getField("TELEPON")."', '".$this->getField("PEGAWAI_ID_APPROVAL1")."', '".$this->getField("PEGAWAI_ID_APPROVAL2")."',
					'".$this->getField("LAST_CREATE_USER")."', ".$this->getField("LAST_CREATE_DATE").",
					'".$this->getField("STATUS_TUNDA")."', '".$this->getField("CABANG_ID")."', '".$this->getField("APPROVAL1")."')
				"; 
		$this->id = $this->getField("PERMOHONAN_CUTI_TAHUNAN_ID");
		$this->query = $str;
		return $this->execQuery($str);
    }

    function update()
	{
		$str = "
				UPDATE PERMOHONAN_CUTI_TAHUNAN
				SET    PEGAWAI_ID            = '".$this->getField("PEGAWAI_ID")."',
					   TAHUN                 = '".$this->getField("TAHUN")."',
					   NOMOR                 = '".$this->getField("PERMOHONAN_CUTI_TAHUNAN_ID")."',
					   TANGGAL               = ".$this->getField("PERMOHONAN_CUTI_TAHUNAN_ID").",
					   JABATAN               = '".$this->getField("PERMOHONAN_CUTI_TAHUNAN_ID")."',
					   CABANG                = '".$this->getField("PERMOHONAN_CUTI_TAHUNAN_ID")."',
					   DEPARTEMEN            = '".$this->getField("PERMOHONAN_CUTI_TAHUNAN_ID")."',
					   SUB_DEPARTEMEN        = '".$this->getField("PERMOHONAN_CUTI_TAHUNAN_ID")."',
					   TANGGAL_AWAL          = ".$this->getField("PERMOHONAN_CUTI_TAHUNAN_ID").",
					   TANGGAL_AKHIR         = ".$this->getField("PERMOHONAN_CUTI_TAHUNAN_ID").",
					   LAMA_CUTI             = '".$this->getField("PERMOHONAN_CUTI_TAHUNAN_ID")."',
					   KETERANGAN            = '".$this->getField("PERMOHONAN_CUTI_TAHUNAN_ID")."',
					   ALAMAT                = '".$this->getField("PERMOHONAN_CUTI_TAHUNAN_ID")."',
					   TELEPON               = '".$this->getField("PERMOHONAN_CUTI_TAHUNAN_ID")."',
					   PEGAWAI_ID_APPROVAL1   = '".$this->getField("PERMOHONAN_CUTI_TAHUNAN_ID")."',
					   APPROVAL1              = '".$this->getField("PERMOHONAN_CUTI_TAHUNAN_ID")."',
					   LAST_UPDATE_USER      = '".$this->getField("PERMOHONAN_CUTI_TAHUNAN_ID")."',
					   LAST_UPDATE_DATE      = ".$this->getField("PERMOHONAN_CUTI_TAHUNAN_ID").",
					   STATUS_TUNDA          = '".$this->getField("PERMOHONAN_CUTI_TAHUNAN_ID")."'
				WHERE  PERMOHONAN_CUTI_TAHUNAN_ID = '".$this->getField("PERMOHONAN_CUTI_TAHUNAN_ID")."'

			 "; 
		$this->query = $str;
		return $this->execQuery($str);
    }
	
	function approval1()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "
				UPDATE PERMOHONAN_CUTI_TAHUNAN
				SET    APPROVAL1          			= '".$this->getField("APPROVAL")."',
					   ALASAN_TOLAK1				= '".$this->getField("ALASAN_TOLAK")."',
					   APPROVAL_TANGGAL1			= ".$this->getField("APPROVAL_TANGGAL1").",
					   LAST_UPDATE_DATE     		= ".$this->getField("LAST_UPDATE_DATE").",
					   LAST_UPDATE_USER       		= '".$this->getField("LAST_UPDATE_USER")."'
				WHERE  PERMOHONAN_CUTI_TAHUNAN_ID   = '".$this->getField("PERMOHONAN_CUTI_TAHUNAN_ID")."'

				"; 
				$this->query = $str;
		return $this->execQuery($str);
    }
	
	function approval2()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "
				UPDATE PERMOHONAN_CUTI_TAHUNAN
				SET    APPROVAL2          			= '".$this->getField("APPROVAL")."',
					   ALASAN_TOLAK2				= '".$this->getField("ALASAN_TOLAK")."',
					   APPROVAL_TANGGAL2			= ".$this->getField("APPROVAL_TANGGAL2").",
					   LAST_UPDATE_DATE     		= ".$this->getField("LAST_UPDATE_DATE").",
					   LAST_UPDATE_USER       		= '".$this->getField("LAST_UPDATE_USER")."'
				WHERE  PERMOHONAN_CUTI_TAHUNAN_ID   = '".$this->getField("PERMOHONAN_CUTI_TAHUNAN_ID")."'

				"; 
				$this->query = $str;
		return $this->execQuery($str);
    }
	
	function delete()
	{
        $str = "DELETE FROM PERMOHONAN_CUTI_TAHUNAN
                WHERE 
                  PERMOHONAN_CUTI_TAHUNAN_ID = ".$this->getField("PERMOHONAN_CUTI_TAHUNAN_ID").""; 
				  
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
    function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order="ORDER BY PERMOHONAN_CUTI_TAHUNAN_ID DESC")
	{
		$str = "
				SELECT 
				PERMOHONAN_CUTI_TAHUNAN_ID, A.PEGAWAI_ID, A.TAHUN, 
				   A.NOMOR, A.TANGGAL, A.JABATAN, 
				   A.CABANG, A.DEPARTEMEN, A.SUB_DEPARTEMEN, 
				   A.TANGGAL_AWAL, A.TANGGAL_AKHIR, A.LAMA_CUTI, 
				   A.KETERANGAN, A.ALAMAT, A.TELEPON, 
				   A.LAST_CREATE_USER, 
				   A.LAST_CREATE_DATE, A.LAST_UPDATE_USER, A.LAST_UPDATE_DATE, 
				   STATUS_TUNDA, A.CABANG_ID, B.NAMA NAMA_PEGAWAI, C.NAMA NAMA_CABANG,
				   (SELECT NAMA FROM PEGAWAI X WHERE X.PEGAWAI_ID = PEGAWAI_ID_APPROVAL1) NAMA_APPROVAL1,
				   (SELECT NAMA FROM PEGAWAI X WHERE X.PEGAWAI_ID = PEGAWAI_ID_APPROVAL2) NAMA_APPROVAL2,
				   DECODE(APPROVAL1, 'Y', 'Disetujui', 'T', 'Ditolak', 'Verifikasi') STATUS_APPROVAL1, 
				   DECODE(APPROVAL2, 'Y', 'Disetujui', 'T', 'Ditolak', 'Verifikasi') STATUS_APPROVAL2,
				   APPROVAL_TANGGAL1, APPROVAL_TANGGAL2, CASE WHEN H.PEGAWAI_ID IS NULL THEN F.NAMA ELSE 'PH ' || F.NAMA END NAMA_STAFF, E.JABATAN JABATAN_APPROVAL
				FROM PERMOHONAN_CUTI_TAHUNAN A
                LEFT JOIN PEGAWAI B ON B.PEGAWAI_ID = A.PEGAWAI_ID
				LEFT JOIN CABANG C ON C.CABANG_ID = A.CABANG_ID
				LEFT JOIN PEGAWAI D ON D.PEGAWAI_ID = A.PEGAWAI_ID_APPROVAL1
				LEFT JOIN PEGAWAI E ON E.PEGAWAI_ID = A.PEGAWAI_ID_APPROVAL2
                LEFT JOIN STAFF F ON F.STAFF_ID = E.STAFF_ID
                LEFT JOIN PELAKSANA_HARIAN G ON A.PEGAWAI_ID = G.PEGAWAI_ID AND STATUS_AKTIF = 1
                LEFT JOIN PEGAWAI H ON G.PEGAWAI_PH_ID = H.PEGAWAI_ID
                LEFT JOIN STAFF I ON H.STAFF_ID = I.STAFF_ID
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

    function selectByParamsApproval($pegawaiId, $paramsArray=array(),$limit=-1,$from=-1, $statement="", $order="ORDER BY PERMOHONAN_CUTI_TAHUNAN_ID ASC")
	{
		$str = "
				SELECT 
				PERMOHONAN_CUTI_TAHUNAN_ID, A.PEGAWAI_ID, TAHUN, 
				   NOMOR, TANGGAL, A.JABATAN, 
				   CABANG, DEPARTEMEN, SUB_DEPARTEMEN, 
				   TANGGAL_AWAL, TANGGAL_AKHIR, LAMA_CUTI, 
				   A.KETERANGAN, A.ALAMAT, A.TELEPON, 
				   A.LAST_CREATE_USER, 
				   A.LAST_CREATE_DATE, A.LAST_UPDATE_USER, A.LAST_UPDATE_DATE, 
				   STATUS_TUNDA, A.CABANG_ID, B.NAMA NAMA_PEGAWAI, C.NAMA NAMA_CABANG,
				   (SELECT NAMA FROM PEGAWAI X WHERE X.PEGAWAI_ID = A.PEGAWAI_ID_APPROVAL1) NAMA_APPROVAL1,
				   (SELECT NAMA FROM PEGAWAI X WHERE X.PEGAWAI_ID = A.PEGAWAI_ID_APPROVAL2) NAMA_APPROVAL2,
				   CASE 
				   	WHEN PEGAWAI_ID_APPROVAL1 = '".$pegawaiId."' THEN
				   		DECODE(APPROVAL1, 'Y', 'Disetujui', 'T', 'Ditolak', 'Verifikasi') 
					WHEN PEGAWAI_ID_APPROVAL2 = '".$pegawaiId."' THEN
				   		DECODE(APPROVAL2, 'Y', 'Disetujui', 'T', 'Ditolak', 'Verifikasi') 
				   END STATUS_APPROVAL, 
				   CASE 
				   	WHEN PEGAWAI_ID_APPROVAL1 = '".$pegawaiId."' THEN
				   		DECODE(APPROVAL2, 'Y', 'Disetujui', 'T', 'Ditolak', 'Verifikasi') || ' Oleh ' || (SELECT NAMA FROM PEGAWAI X WHERE X.PEGAWAI_ID = A.PEGAWAI_ID_APPROVAL2)
					WHEN PEGAWAI_ID_APPROVAL2 = '".$pegawaiId."' AND PEGAWAI_ID_APPROVAL1 IS NULL THEN
				   		'Tidak ada'
					WHEN PEGAWAI_ID_APPROVAL2 = '".$pegawaiId."' THEN
				   		DECODE(APPROVAL1, 'Y', 'Disetujui', 'T', 'Ditolak', 'Verifikasi') || ' Oleh ' || (SELECT NAMA FROM PEGAWAI X WHERE X.PEGAWAI_ID = A.PEGAWAI_ID_APPROVAL1) 
				   END STATUS_APPROVAL_LAIN, 
				   DECODE(APPROVAL1, 'Y', 'Disetujui', 'T', 'Ditolak', 'Verifikasi') APPROVAL1,
				   DECODE(APPROVAL2, 'Y', 'Disetujui', 'T', 'Ditolak', 'Verifikasi') APPROVAL2,
				   CASE 
				   	WHEN PEGAWAI_ID_APPROVAL1 = '".$pegawaiId."' THEN
				   		APPROVAL1 
					WHEN PEGAWAI_ID_APPROVAL2 = '".$pegawaiId."' THEN
				   		APPROVAL2
				   END APPROVAL, 
				   CASE 
				   	WHEN PEGAWAI_ID_APPROVAL1 = '".$pegawaiId."' THEN
				   		'1' 
					WHEN PEGAWAI_ID_APPROVAL2 = '".$pegawaiId."' THEN
				   		'2'
				   END APPROVAL_KE, 
				   CASE 
				   	WHEN PEGAWAI_ID_APPROVAL1 = '".$pegawaiId."' THEN
				   		ALASAN_TOLAK1 
					WHEN PEGAWAI_ID_APPROVAL2 = '".$pegawaiId."' THEN
				   		ALASAN_TOLAK2
				   END ALASAN_TOLAK, 
				   ALASAN_TOLAK1,
				   ALASAN_TOLAK2
				FROM PERMOHONAN_CUTI_TAHUNAN A
                LEFT JOIN PEGAWAI B ON B.PEGAWAI_ID = A.PEGAWAI_ID
				LEFT JOIN CABANG C ON C.CABANG_ID = A.CABANG_ID
				  WHERE 1=1
				"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$order;
		$this->query = $str;
		
		return $this->selectLimit($str,$limit,$from); 
    }
	
    function selectByParamsJatahCutiTahunan($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order="ORDER BY PEGAWAI_ID ASC")
	{
		$str = "
				SELECT 
					PEGAWAI_ID, STATUS_PEGAWAI, TANGGAL_MASUK, 
					   TOTAL_CUTI, TOTAL_DIAMBIL, TOTAL_VERIFIKASI, 
					   TOTAL_SETUJU, TOTAL_TOLAK, (TOTAL_CUTI - TOTAL_DIAMBIL) TOTAL_SISA
				FROM PEGAWAI_JATAH_CUTI_TAHUNAN A
				WHERE 1=1
				"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$order;
		$this->query = $str;
		
		return $this->selectLimit($str,$limit,$from); 
    }

    function selectByParamsJatahCutiTangguhan($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order="ORDER BY PEGAWAI_ID ASC")
	{
		$str = "
				SELECT 
					PEGAWAI_ID, TOTAL_PENANGGUHAN, TOTAL_DIAMBIL, 
				    TOTAL_VERIFIKASI, TOTAL_SETUJU, TOTAL_TOLAK, (TOTAL_PENANGGUHAN - TOTAL_DIAMBIL) TOTAL_SISA
				FROM PEGAWAI_JATAH_CUTI_TANGGUHAN A
				WHERE 1=1
				"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$order;
		$this->query = $str;
		
		return $this->selectLimit($str,$limit,$from); 
    }
	
	function selectByParamsRekapJatahCutiTahunan($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order="")
	{
		$str = "
				SELECT 
                    A.PEGAWAI_ID, STATUS_PEGAWAI, A.TANGGAL_MASUK, 
					   TOTAL_CUTI, TOTAL_DIAMBIL, TOTAL_VERIFIKASI, 
					   TOTAL_SETUJU, TOTAL_TOLAK, (TOTAL_CUTI - TOTAL_DIAMBIL) TOTAL_SISA, B.NAMA
				FROM PEGAWAI_JATAH_CUTI_TAHUNAN A
                LEFT JOIN PEGAWAI B ON B.PEGAWAI_ID = A.PEGAWAI_ID
				WHERE 1=1
				"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$order;
		$this->query = $str;
		
		return $this->selectLimit($str,$limit,$from); 
    }
	
	function selectByParamsRekapJatahCutiTahunanReport($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order="")
	{
		$str = "
				SELECT 
                    A.PEGAWAI_ID, STATUS_PEGAWAI, A.TANGGAL_MASUK,
					   TANGGAL_BERLAKU, TOTAL_CUTI, TOTAL_DIAMBIL, TOTAL_VERIFIKASI, 
					   TOTAL_SETUJU, TOTAL_TOLAK, (TOTAL_CUTI - TOTAL_SETUJU) TOTAL_SISA, B.NAMA
				FROM PEGAWAI_JATAH_CUTI_TAHUNAN A
                LEFT JOIN PEGAWAI B ON B.PEGAWAI_ID = A.PEGAWAI_ID
				WHERE 1=1
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
				  SELECT PERMOHONAN_CUTI_TAHUNAN_ID
				  FROM PERMOHONAN_CUTI_TAHUNAN A                 
				  WHERE 0=0
			    "; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key LIKE '%$val%' ";
		}
		
		$this->query = $str;
		$str .= $statement." ORDER BY PERMOHONAN_CUTI_TAHUNAN_ID ASC";
		return $this->selectLimit($str,$limit,$from); 
    }	
    /** 
    * Hitung jumlah record berdasarkan parameter (array). 
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","IJIN_USAHA_ID"=>"yyy") 
    * @return long Jumlah record yang sesuai kriteria 
    **/ 
    function getCountByParams($paramsArray=array(), $statement="")
	{
		$str = "SELECT COUNT(PERMOHONAN_CUTI_TAHUNAN_ID) AS ROWCOUNT FROM PERMOHONAN_CUTI_TAHUNAN A
				LEFT JOIN PEGAWAI B ON B.PEGAWAI_ID = A.PEGAWAI_ID
				LEFT JOIN CABANG C ON C.CABANG_ID = A.CABANG_ID
				LEFT JOIN PEGAWAI D ON D.PEGAWAI_ID = A.PEGAWAI_ID_APPROVAL1
				LEFT JOIN PEGAWAI E ON E.PEGAWAI_ID = A.PEGAWAI_ID_APPROVAL2
		        WHERE 0=0 ".$statement; 
		
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
	
    function getSumByParams($paramsArray=array(), $statement="")
	{
		$str = "SELECT SUM(LAMA_CUTI) AS ROWCOUNT FROM PERMOHONAN_CUTI_TAHUNAN A
		        WHERE 0=0 ".$statement; 
		
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
		$str = "SELECT COUNT(PERMOHONAN_CUTI_TAHUNAN_ID) AS ROWCOUNT FROM PERMOHONAN_CUTI_TAHUNAN  A
		        WHERE 0=0 ".$statement; 
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
	
	function getCountByParamsRekapCutiTahunan($paramsArray=array(), $statement="")
	{
		$str = "SELECT COUNT(A.PEGAWAI_ID) AS ROWCOUNT FROM PEGAWAI_JATAH_CUTI_TAHUNAN A
				LEFT JOIN PEGAWAI B ON B.PEGAWAI_ID = A.PEGAWAI_ID
				LEFT JOIN CABANG C ON C.CABANG_ID = B.CABANG_ID
		        WHERE 0=0 ".$statement; 
		
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