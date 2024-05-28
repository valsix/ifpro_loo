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

  class PermohonanNomor extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function PermohonanNomor()
	{
      $this->Entity(); 
    }
	
	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("PERMOHONAN_NOMOR_ID", $this->getNextId("PERMOHONAN_NOMOR_ID","PERMOHONAN_NOMOR")); 		

		$str = "  

                  INSERT INTO PERMOHONAN_NOMOR(
                    PERMOHONAN_NOMOR_ID, CABANG_ID, SATUAN_KERJA_ID, SATUAN_KERJA, 
					JENIS_NASKAH_ID, JENIS_NASKAH, 
					KD_LEVEL, PERUNTUKAN, 
                    KETERANGAN, TANGGAL_SURAT, TIPE_NASKAH, 
                    LAST_CREATE_USER, LAST_CREATE_DATE)
            VALUES ( '".$this->getField("PERMOHONAN_NOMOR_ID")."', '".$this->getField("CABANG_ID")."', '".$this->getField("SATUAN_KERJA_ID")."', '".$this->getField("SATUAN_KERJA")."', 
					'".$this->getField("JENIS_NASKAH_ID")."', '".$this->getField("JENIS_NASKAH")."', 
					'".$this->getField("KD_LEVEL")."', '".$this->getField("PERUNTUKAN")."', 
                    '".$this->getField("KETERANGAN")."', CURRENT_DATE, '".$this->getField("TIPE_NASKAH")."', 
                    '".$this->getField("LAST_CREATE_USER")."', CURRENT_DATE
                  )";

		$this->query = $str;
		//echo $str;
		return $this->execQuery($str);
    }

    function update()
	{
		$str = "

                UPDATE PERMOHONAN_NOMOR
                    SET 
                    CABANG_ID             ='".$this->getField("CABANG_ID")."', 
                    SATUAN_KERJA_ID       ='".$this->getField("SATUAN_KERJA_ID")."', 
                    PERUNTUKAN            ='".$this->getField("PERUNTUKAN")."', 
                    KETERANGAN            ='".$this->getField("KETERANGAN")."', 
                    TANGGAL_SURAT         =".$this->getField("TANGGAL_SURAT").", 
                    TIPE_NASKAH           ='".$this->getField("TIPE_NASKAH")."', 
                    SURAT_MASUK_ID        ='".$this->getField("SURAT_MASUK_ID")."', 
                    SURAT_KELUAR_ID       ='".$this->getField("SURAT_KELUAR_ID")."', 
                    LAST_UPDATE_USER      ='".$this->getField("LAST_UPDATE_USER")."', 
                    LAST_UPDATE_DATE      = CURRENT_DATE, 
                    LAST_APPROVE_USER     ='".$this->getField("LAST_APPROVE_USER")."', 
                    LAST_APPROVE_DATE     = CURRENT_DATE 
                WHERE PERMOHONAN_NOMOR_ID ='".$this->getField("PERMOHONAN_NOMOR_ID")."'

			 "; 
		$this->query = $str;
		// echo $str;
		return $this->execQuery($str);
    }
	
	

    function approval($statement)
	{
		$str = "

                UPDATE PERMOHONAN_NOMOR
                    SET 
					SURAT_NOMOR 		  = '".$this->getField("SURAT_NOMOR")."', 
                    LAST_APPROVE_USER     = '".$this->getField("LAST_APPROVE_USER")."', 
                    LAST_APPROVE_DATE     = CURRENT_DATE 
                WHERE PERMOHONAN_NOMOR_ID = '".$this->getField("PERMOHONAN_NOMOR_ID")."'
			 ".$statement; 
		$this->query = $str;
		//echo $str;
		return $this->execQuery($str);
    }
	
	
    function updateByField()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "UPDATE PERMOHONAN_NOMOR  SET
				  ".$this->getField("FIELD")." = '".$this->getField("FIELD_VALUE")."'
				WHERE PERMOHONAN_NOMOR_ID = ".$this->getField("PERMOHONAN_NOMOR_ID")."
				"; 
				$this->query = $str;
	
		return $this->execQuery($str);
    }	

	function delete()
	{
        $str = "DELETE FROM PERMOHONAN_NOMOR
                WHERE 
                PERMOHONAN_NOMOR_ID = ".$this->getField("PERMOHONAN_NOMOR_ID").""; 
				  
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
		$str = "    SELECT PERMOHONAN_NOMOR_ID, CABANG_ID, PERUNTUKAN, KETERANGAN, TANGGAL_SURAT, 
					   TIPE_NASKAH, JENIS_NASKAH_ID, JENIS_NASKAH, KD_LEVEL, SATUAN_KERJA_ID, 
					   SATUAN_KERJA, SURAT_NOMOR, SURAT_MASUK_ID, SURAT_KELUAR_ID, LAST_CREATE_USER, 
					   COALESCE(SURAT_NOMOR::VARCHAR, 'Sedang diverifikasi') SURAT_NOMOR_FIX,
					   LAST_CREATE_DATE, LAST_UPDATE_USER, LAST_UPDATE_DATE, LAST_APPROVE_USER, 
					   LAST_APPROVE_DATE, CASE WHEN TIPE_NASKAH = 'INTERNAL' THEN 'Surat Internal' ELSE 'Surat Keluar' END TIPE_NASKAH_KET
				  FROM PERMOHONAN_NOMOR A
                WHERE 1 = 1

				"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ORDER BY A.PERMOHONAN_NOMOR_ID DESC";
		$this->query = $str;
		return $this->selectLimit($str,$limit,$from); 
    }
    
    function selectByParamsVerifikasi($paramsArray=array(),$limit=-1,$from=-1, $statement="")
	{
		$str = "    SELECT PERMOHONAN_NOMOR_ID, CABANG_ID, PERUNTUKAN, A.KETERANGAN, TANGGAL_SURAT, 
					   A.TIPE_NASKAH, A.JENIS_NASKAH_ID, JENIS_NASKAH, A.KD_LEVEL, SATUAN_KERJA_ID, 
					   SATUAN_KERJA, SURAT_NOMOR, SURAT_MASUK_ID, SURAT_KELUAR_ID, A.LAST_CREATE_USER, 
					   COALESCE(SURAT_NOMOR::VARCHAR, 'Sedang diverifikasi') SURAT_NOMOR_FIX,
					   A.LAST_CREATE_DATE, A.LAST_UPDATE_USER, A.LAST_UPDATE_DATE, LAST_APPROVE_USER, 
					   LAST_APPROVE_DATE, B.PENERBIT_NOMOR, CASE WHEN A.TIPE_NASKAH = 'INTERNAL' THEN 'Surat Internal' ELSE 'Surat Keluar' END TIPE_NASKAH_KET
				  FROM PERMOHONAN_NOMOR A
				INNER JOIN JENIS_NASKAH B ON A.JENIS_NASKAH_ID = B.JENIS_NASKAH_ID
                WHERE 1 = 1
				"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		$str .= $statement." ORDER BY A.PERMOHONAN_NOMOR_ID DESC";
		$this->query = $str;
		return $this->selectLimit($str,$limit,$from); 
    }
	
	
    
    function selectByParamsApproval($paramsArray=array(),$limit=-1,$from=-1, $statement="")
	{
		$str = "    SELECT PERMOHONAN_NOMOR_ID, CABANG_ID, PERUNTUKAN, A.KETERANGAN, TANGGAL_SURAT, 
					   A.TIPE_NASKAH, A.JENIS_NASKAH_ID, JENIS_NASKAH, A.KD_LEVEL, SATUAN_KERJA_ID, 
					   SATUAN_KERJA, SURAT_NOMOR, SURAT_MASUK_ID, SURAT_KELUAR_ID, A.LAST_CREATE_USER, 
					   COALESCE(SURAT_NOMOR::VARCHAR, 'Sedang diverifikasi') SURAT_NOMOR_FIX,
					   A.LAST_CREATE_DATE, A.LAST_UPDATE_USER, A.LAST_UPDATE_DATE, LAST_APPROVE_USER, 
					   LAST_APPROVE_DATE, B.PENERBIT_NOMOR, CASE WHEN A.TIPE_NASKAH = 'INTERNAL' THEN 'Surat Internal' ELSE 'Surat Keluar' END TIPE_NASKAH_KET,
					    ambil_nomor_surat(CABANG_ID, SATUAN_KERJA_ID, A.JENIS_NASKAH_ID, TANGGAL_SURAT) NOMOR_BARU
				  FROM PERMOHONAN_NOMOR A
				INNER JOIN JENIS_NASKAH B ON A.JENIS_NASKAH_ID = B.JENIS_NASKAH_ID
                WHERE 1 = 1
				"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		$str .= $statement." ORDER BY A.PERMOHONAN_NOMOR_ID DESC";
		$this->query = $str;
		return $this->selectLimit($str,$limit,$from); 
    }
	
	function selectByParamsLike($paramsArray=array(),$limit=-1,$from=-1, $statement="")
	{
		$str = "     SELECT PERMOHONAN_NOMOR_ID, CABANG_ID, SATUAN_KERJA_ID, PERUNTUKAN, 
                        KETERANGAN, TANGGAL_SURAT, TIPE_NASKAH, SURAT_MASUK_ID, SURAT_KELUAR_ID, 
                        LAST_CREATE_USER, LAST_CREATE_DATE, LAST_UPDATE_USER, LAST_UPDATE_DATE, 
                        LAST_APPROVE_USER, LAST_APPROVE_DATE
                FROM  PERMOHONAN_NOMOR A
                WHERE PERMOHONAN_NOMOR_ID IS NOT NULL
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
		$str = "SELECT COUNT(PERMOHONAN_NOMOR_ID) AS ROWCOUNT FROM PERMOHONAN_NOMOR A
		        WHERE PERMOHONAN_NOMOR_ID IS NOT NULL ".$statement; 
		
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

    function getCountByParamsVerifikasi($paramsArray=array(), $statement="")
	{
		$str = "SELECT COUNT(PERMOHONAN_NOMOR_ID) AS ROWCOUNT FROM PERMOHONAN_NOMOR A
					INNER JOIN JENIS_NASKAH B ON A.JENIS_NASKAH_ID = B.JENIS_NASKAH_ID
		        WHERE PERMOHONAN_NOMOR_ID IS NOT NULL ".$statement; 
		
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
		$str = "SELECT COUNT(PERMOHONAN_NOMOR_ID) AS ROWCOUNT FROM PERMOHONAN_NOMOR A
		        WHERE PERMOHONAN_NOMOR_ID IS NOT NULL ".$statement; 
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

    function getGenerateNomor($cabangId, $satuanKerjaId, $jenisNaskahId, $tanggal, $permohonanId, $klasifikasiKode, $jenisSurat, $suratMasukId)
	{

		$str = "SELECT GENERATE_NOMOR_SURAT('".$cabangId."','".$satuanKerjaId."',".$jenisNaskahId.",".$tanggal.",".$permohonanId.",'".$klasifikasiKode."','".$jenisSurat."',".$suratMasukId.") AS NOMOR_BARU"; 
		
		$this->select($str); 
		if($this->firstRow()) {
			return $this->getField("NOMOR_BARU"); 
		}
		else {
			return 0; 
		}
    }

  } 
?>