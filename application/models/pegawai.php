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

  class Pegawai extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function Pegawai()
	{
      $this->Entity(); 
    }
	
	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) 
		$this->setField("PEGAWAI_ID", $this->getNextId("PEGAWAI_ID","PEGAWAI")); */
		// $str = "
		// 		INSERT INTO PEGAWAI (PEGAWAI_ID, NIP, NAMA, JABATAN, 
		// 		DEPARTEMEN_ID, DEPARTEMEN, SATUAN_KERJA_ID,  JENIS_PEGAWAI, LAST_CREATE_USER, LAST_CREATE_DATE, SATUAN_KERJA
		// 		) 
		// 		VALUES(
		// 		  '".$this->getField("NIP")."',
		// 		  '".$this->getField("NIP")."',
		// 		  '".$this->getField("NAMA")."',
		// 		  '".$this->getField("JABATAN")."',
		// 		  '".$this->getField("DEPARTEMEN_ID")."',
		// 		  '".$this->getField("DEPARTEMEN")."',
		// 		  'PST', 
		// 		  '".$this->getField("JENIS_PEGAWAI")."',
		// 		  '".$this->getField("LAST_CREATE_USER")."',
		// 		  CURRENT_DATE,
		// 		  'Kantor Pusat'
		// 		)"; 
		$str = "
				INSERT INTO PEGAWAI (PEGAWAI_ID, NIP, NAMA, JABATAN,  SATUAN_KERJA_ID,  JENIS_PEGAWAI, LAST_CREATE_USER, LAST_CREATE_DATE, SATUAN_KERJA
				) 
				VALUES(
				  '".$this->getField("NIP")."',
				  '".$this->getField("NIP")."',
				  '".$this->getField("NAMA")."',
				  '".$this->getField("JABATAN")."',
				  'PST', 
				  '".$this->getField("JENIS_PEGAWAI")."',
				  '".$this->getField("LAST_CREATE_USER")."',
				  CURRENT_DATE,
				  'Kantor Pusat'
				)"; 
		$this->id = $this->getField("PEGAWAI_ID");
		$this->query = $str;
		// echo $str;exit();
		$this->execQuery($str);

		$strproses= "
		SELECT pjabatanpegawai('".$this->getField("NIP")."')
		"; 
		$this->query = $strproses;
		// echo $str;exit();
        return $this->execQuery($strproses);
    }


	function import()
	{
		/*Auto-generate primary key(s) by next max value (integer) 
		$this->setField("PEGAWAI_ID", $this->getNextId("PEGAWAI_ID","PEGAWAI")); */
		
	
		$str = "SELECT COUNT(1) AS ROWCOUNT FROM PEGAWAI WHERE 1 = 1 
				AND PEGAWAI_ID = '".$this->getField("PEGAWAI_ID")."' "; 
		
	 	$query = $this->db->query($str);
		$row = $query->first_row();
		$adaData = $row->rowcount;
		
		if($adaData == 0)
		{

			$str = "
					INSERT INTO PEGAWAI (PEGAWAI_ID, SOURCE_DATA, NIP, NIP_URUT, NAMA, EMAIL, 
					PHONE, JABATAN, SATUAN_KERJA_ID, DEPARTEMEN_ID, SATUAN_KERJA, DEPARTEMEN,
					TAHUN_LAHIR, TAHUN_MASUK, JENIS_PEGAWAI,
					LAST_CREATE_USER, LAST_CREATE_DATE) 
					VALUES(
					  '".$this->getField("PEGAWAI_ID")."',
					  '".$this->getField("SOURCE_DATA")."',
					  '".$this->getField("NIP")."',
					  '".$this->getField("NIP_URUT")."',
					  '".$this->getField("NAMA")."',
					  '".$this->getField("EMAIL")."',
					  '".$this->getField("PHONE")."',
					  '".$this->getField("JABATAN")."',
					  '".$this->getField("SATUAN_KERJA_ID")."',
					  '".$this->getField("DEPARTEMEN_ID")."',
					  (SELECT NAMA FROM SATUAN_KERJA WHERE SATUAN_KERJA_ID = '".$this->getField("SATUAN_KERJA_ID")."'),
					  (SELECT NAMA FROM SATUAN_KERJA WHERE SATUAN_KERJA_ID = '".$this->getField("DEPARTEMEN_ID")."'),
					  '".$this->getField("TAHUN_LAHIR")."', '".$this->getField("TAHUN_MASUK")."', '".$this->getField("JENIS_PEGAWAI")."',
					  '".$this->getField("LAST_CREATE_USER")."',
					  CURRENT_DATE
					)"; 
			$this->flag = "Y"; 
			$this->query = $str;
			
			return $this->execQuery($str);
		}
		
		$this->flag = "T"; 
		return true;
		
    }


	function insertHCIS()
	{

	
		$str = "SELECT COUNT(PEGAWAI_ID) AS ROWCOUNT FROM PEGAWAI WHERE 1 = 1 
				AND PEGAWAI_ID = '".$this->getField("PEGAWAI_ID")."' AND SOURCE_DATA = 'SYNC' "; 
				
	 	$query = $this->db->query($str);
		$row = $query->first_row();
		$adaData = $row->ROWCOUNT;
		
		if($adaData == 0)
		{

			$str = "
					INSERT INTO PEGAWAI (PEGAWAI_ID, NIP, NAMA, JABATAN, 
					JENIS_KELAMIN, ALAMAT, EMAIL, PHONE, DEPARTEMEN,
					SATUAN_KERJA_ID, SATUAN_KERJA, LAST_CREATE_USER, LAST_CREATE_DATE) 
					VALUES(
					  '".$this->getField("PEGAWAI_ID")."',
					  '".$this->getField("NIP")."',
					  '".$this->getField("NAMA")."',
					  '".$this->getField("JABATAN")."',
					  '".$this->getField("JENIS_KELAMIN")."','".$this->getField("ALAMAT")."','".$this->getField("EMAIL")."','".$this->getField("PHONE")."','".$this->getField("DEPARTEMEN")."',
					  (SELECT KODE FROM KODE_UNIT_KERJA WHERE KODE_UNIT_KERJA_ID = '".$this->getField("SATUAN_KERJA_ID")."'), '".$this->getField("SATUAN_KERJA")."',
					  '".$this->getField("LAST_CREATE_USER")."',
					  CURRENT_DATE
					)"; 

		}
		else
		{
			$str = "
				UPDATE PEGAWAI SET
				  JABATAN		= '".$this->getField("JABATAN")."',
				  ALAMAT			= '".$this->getField("ALAMAT")."',
				  EMAIL				= '".$this->getField("EMAIL")."',
				  PHONE				= '".$this->getField("PHONE")."',
				  DEPARTEMEN		= '".$this->getField("DEPARTEMEN")."',
				  SATUAN_KERJA_ID	= (SELECT KODE FROM KODE_UNIT_KERJA WHERE KODE_UNIT_KERJA_ID = '".$this->getField("SATUAN_KERJA_ID")."'),
				  SATUAN_KERJA		= '".$this->getField("SATUAN_KERJA")."',
				  LAST_UPDATE_DATE	= CURRENT_DATE
				WHERE PEGAWAI_ID 	= '".$this->getField("PEGAWAI_ID")."' AND SOURCE_DATA = 'SYNC'
			";
		}
		
		$this->id = $this->getField("PEGAWAI_ID");
		$this->query = $str;
		return $this->execQuery($str);
    }


    function update()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "UPDATE PEGAWAI SET
				  NAMA				= '".$this->getField("NAMA")."',
				  JENIS_PEGAWAI= '".$this->getField("JENIS_PEGAWAI")."'
				WHERE PEGAWAI_ID 	= '".$this->getField("PEGAWAI_ID")."'
				"; 
				$this->query = $str;
				// echo $str; exit;
		return $this->execQuery($str);
    }

    function updateSatker()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "UPDATE satuan_kerja SET
				  NAMA				= '".$this->getField("NAMA")."'
				WHERE NIP 	= '".$this->getField("PEGAWAI_ID")."'
				"; 
				$this->query = $str;
				// echo $str; exit;
		return $this->execQuery($str);
    }

    function updateUserLogin()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "UPDATE user_login SET
				  NAMA				= '".$this->getField("NAMA")."'
				WHERE PEGAWAI_ID 	= '".$this->getField("PEGAWAI_ID")."'
				"; 
				$this->query = $str;
				// echo $str; exit;
		return $this->execQuery($str);
    }

    function statususer()
	{
		$str = "
		UPDATE USER_LOGIN SET
		STATUS= '".$this->getField("STATUS")."'
		WHERE PEGAWAI_ID= '".$this->getField("PEGAWAI_ID")."'
		"; 
		$this->query = $str;
		return $this->execQuery($str);
    }
	
	function delete()
	{
        $str = "DELETE FROM PEGAWAI
                WHERE 
                  PEGAWAI_ID = '".$this->getField("PEGAWAI_ID")."'"; 
				  
		$this->query = $str;
        return $this->execQuery($str);
    }
	
	
	
	function nonaktif()
	{
		
		$str = "
			INSERT INTO PEGAWAI_HAPUS(
            PEGAWAI_ID, NIP, NAMA, JENIS_KELAMIN, ALAMAT, TEMPAT_LAHIR, TANGGAL_LAHIR, 
            EMAIL, PHONE, JABATAN, SATUAN_KERJA_ID, SATUAN_KERJA, LAST_CREATE_USER, 
            LAST_CREATE_DATE, LAST_UPDATE_USER, LAST_UPDATE_DATE, DEPARTEMEN_ID, 
            DEPARTEMEN, SOURCE_DATA, TAHUN_LAHIR, TAHUN_MASUK, JENIS_PEGAWAI, 
            NIP_URUT)
			SELECT PEGAWAI_ID, NIP, NAMA, JENIS_KELAMIN, ALAMAT, TEMPAT_LAHIR, TANGGAL_LAHIR, 
            EMAIL, PHONE, JABATAN, SATUAN_KERJA_ID, SATUAN_KERJA, LAST_CREATE_USER, 
            LAST_CREATE_DATE, LAST_UPDATE_USER, LAST_UPDATE_DATE, DEPARTEMEN_ID, 
            DEPARTEMEN, SOURCE_DATA, TAHUN_LAHIR, TAHUN_MASUK, JENIS_PEGAWAI, 
            NIP_URUT FROM PEGAWAI WHERE PEGAWAI_ID = '".$this->getField("PEGAWAI_ID")."'
		";
		$this->execQuery($str);
		
        $str = "DELETE FROM PEGAWAI
                WHERE 
                  PEGAWAI_ID = '".$this->getField("PEGAWAI_ID")."'"; 
				  
		$this->query = $str;
        return $this->execQuery($str);
    }
	
	
	
	function deleteParent()
	{
        $str = " TRUNCATE TABLE PEGAWAI "; 
				  
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
		PEGAWAI_ID, A.NAMA, A.NIP, A.JENIS_KELAMIN, ALAMAT, TEMPAT_LAHIR, TANGGAL_LAHIR, A.JABATAN, B.NAMA SATUAN_KERJA,
		A.EMAIL, PHONE, A.SATUAN_KERJA_ID
		, CASE WHEN JENIS_KELAMIN = 'L' THEN 'Laki - Laki' WHEN JENIS_KELAMIN = 'P' THEN 'Perempuan' END JENIS_KELAMIN_INFO
		, A.DEPARTEMEN, A.DEPARTEMEN_ID, INFO_DEPARTEMEN_NAMA, A.JENIS_PEGAWAI
		FROM PEGAWAI A 
		INNER JOIN SATUAN_KERJA B ON A.SATUAN_KERJA_ID = B.SATUAN_KERJA_ID
		LEFT JOIN 
		(
			SELECT NAMA INFO_DEPARTEMEN_NAMA, SATUAN_KERJA_ID INFO_DEPARTEMEN_ID FROM SATUAN_KERJA
		) DEP ON A.DEPARTEMEN_ID = DEP.INFO_DEPARTEMEN_ID
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

    function selectByParamsUserBantu($paramsArray=array(),$limit=-1,$from=-1,$statement="", $order="")
	{ 
		$str = "
		SELECT 
		PEGAWAI_ID, A.NAMA, A.NIP, A.JENIS_KELAMIN, ALAMAT, TEMPAT_LAHIR, TANGGAL_LAHIR, A.JABATAN, B.NAMA SATUAN_KERJA,
		A.EMAIL, PHONE, A.SATUAN_KERJA_ID
		, CASE WHEN JENIS_KELAMIN = 'L' THEN 'Laki - Laki' WHEN JENIS_KELAMIN = 'P' THEN 'Perempuan' END JENIS_KELAMIN_INFO
		, A.DEPARTEMEN, A.DEPARTEMEN_ID,  A.JENIS_PEGAWAI
		FROM PEGAWAI A 
		INNER JOIN SATUAN_KERJA B ON A.SATUAN_KERJA_ID = B.SATUAN_KERJA_ID
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
	
	
    function selectByParamsInformasi($paramsArray=array(),$limit=-1,$from=-1,$statement="", $order="")
	{ 
		$str = "
				SELECT PEGAWAI_ID, A.NAMA, A.NIP, A.JENIS_KELAMIN, ALAMAT, TEMPAT_LAHIR, TANGGAL_LAHIR, A.JABATAN, A.SATUAN_KERJA,
      				 A.EMAIL, PHONE, A.SATUAN_KERJA_ID, A.DEPARTEMEN,
      				 C.NAMA_PEGAWAI NAMA_ATASAN, C.JABATAN JABATAN_ATASAN
				FROM PEGAWAI A 
				INNER JOIN SATUAN_KERJA B ON A.DEPARTEMEN_ID = B.SATUAN_KERJA_ID
				INNER JOIN SATUAN_KERJA_FIX C ON B.KODE_SO = C.KODE_SO	
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
	
	
    function selectByParamsMonitoring($paramsArray=array(),$limit=-1,$from=-1,$statement="", $order="")
	{
		$str = "
				SELECT PEGAWAI_ID, A.NIP, A.NAMA, JENIS_KELAMIN, ALAMAT, TEMPAT_LAHIR, TANGGAL_LAHIR, TANGGAL_MASUK, BPJS, NOMOR_REKENING,
					   A.EMAIL, PHONE, A.JABATAN, A.SATUAN_KERJA_ID, B.NAMA SATUAN_KERJA, A.DEPARTEMEN_ID, A.DEPARTEMEN, A.JENIS_PEGAWAI, ktp, npwp, BANK_ID, agama_id, LAST_PENDIDIKAN_ID
				  FROM PEGAWAI A INNER JOIN 
				  SATUAN_KERJA B ON A.SATUAN_KERJA_ID = B.SATUAN_KERJA_ID
			"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$order;
		$this->query = $str;
		// echo $str;exit;
		return $this->selectLimit($str,$limit,$from); 
    }
    
	function selectByParamsLike($paramsArray=array(),$limit=-1,$from=-1, $statement="")
	{
		$str = "
				SELECT PEGAWAI_ID, NIP, NAMA, JENIS_KELAMIN, ALAMAT, TEMPAT_LAHIR, TANGGAL_LAHIR, 
      				 EMAIL, PHONE, SATUAN_KERJA_ID, CASE WHEN JENIS_KELAMIN = 'L' THEN 'Laki - Laki' WHEN JENIS_KELAMIN = 'P' THEN 'Perempuan' END JENIS_KELAMIN_INFO
				FROM PEGAWAI A		
				WHERE 1 = 1
				"; 
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key LIKE '%$val%' ";
		}
		
		$str .= $statement." ORDER BY PEGAWAI_ID DESC";
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
		$str = "SELECT COUNT(PEGAWAI_ID) AS ROWCOUNT FROM PEGAWAI A WHERE 1 = 1 ".$statement; 
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

    function getCountByParamsUserBantu($paramsArray=array(), $statement="")
	{
		$str = "SELECT COUNT(PEGAWAI_ID) AS ROWCOUNT FROM PEGAWAI A
		INNER JOIN SATUAN_KERJA B ON A.SATUAN_KERJA_ID = B.SATUAN_KERJA_ID 
		WHERE 1 = 1 ".$statement; 
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


    function getNipUrut($paramsArray=array(), $statement="")
	{
		$str = "SELECT MAX(NIP_URUT) AS ROWCOUNT FROM PEGAWAI A WHERE 1 = 1 ".$statement; 
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
		$str = "SELECT COUNT(PEGAWAI_ID) AS ROWCOUNT FROM PEGAWAI A WHERE 1 = 1 "; 
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

    function selectByParamsApprovalSttpd($paramsArray=array(),$limit=-1,$from=-1,$statement="", $order="ORDER BY B.SATUAN_KERJA_ID")
	{ 
		$str = "
		SELECT
			A.PEGAWAI_ID, A.NAMA, B.JABATAN, B.LOKASI_NAMA SATUAN_KERJA
			, A.DEPARTEMEN, A.DEPARTEMEN_ID
		FROM pegawai A 
		INNER JOIN satuan_kerja_fix B ON A.PEGAWAI_ID = B.NIP
		WHERE 1 = 1
		AND B.KELOMPOK_JABATAN IN ('DIRUT', 'DIREKSI', 'GM', 'SM', 'MAN')
		"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$order;
		$this->query = $str;
		return $this->selectLimit($str,$limit,$from); 
    }

    function getCountByParamsApprovalSttpd($paramsArray=array(), $statement="")
	{
		$str = "
		SELECT COUNT(1) AS ROWCOUNT
		FROM pegawai A 
		INNER JOIN satuan_kerja_fix B ON A.PEGAWAI_ID = B.NIP
		WHERE 1 = 1
		AND B.KELOMPOK_JABATAN IN ('DIRUT', 'DIREKSI', 'GM', 'SM', 'MAN')
		".$statement; 
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