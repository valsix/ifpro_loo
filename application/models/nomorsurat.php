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

  class NomorSurat extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function NomorSurat()
	{
      $this->Entity(); 
    }
	
	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		// $this->setField("TIPE_NASKAH", $this->getNextId("TIPE_NASKAH","NOMOR_SURAT")); 		

		$str = "  
            INSERT INTO NOMOR_SURAT(
                CABANG_ID, SATUAN_KERJA_ID, TIPE_NASKAH, JENIS_NASKAH_ID,
                PENERBIT_NOMOR, KODE_JENIS_NASKAH, KODE_JABATAN, BLN_SURAT, 
                THN_SURAT, TEMP_SURAT, URUT_SURAT, SURAT_MASUK_ID, 
                SURAT_KELUAR_ID, PERMOHONAN_NOMOR_ID, LAST_CREATE_USER, LAST_CREATE_DATE)
            VALUES ( '".$this->getField("CABANG_ID")."', '".$this->getField("SATUAN_KERJA_ID")."', '".$this->getField("TIPE_NASKAH")."', '".$this->getField("JENIS_NASKAH_ID")."', '".$this->getField("PENERBIT_NOMOR")."', '".$this->getField("KODE_JENIS_NASKAH")."', '".$this->getField("KODE_JABATAN")."', '".$this->getField("BLN_SURAT")."', '".$this->getField("THN_SURAT")."', '".$this->getField("TEMP_SURAT")."', '".$this->getField("URUT_SURAT")."', '".$this->getField("SURAT_MASUK_ID")."', '".$this->getField("SURAT_KELUAR_ID")."', '".$this->getField("PERMOHONAN_NOMOR_ID")."', '".$this->getField("LAST_CREATE_USER")."', CURRENT_DATE
            )";

		$this->query = $str;
		//echo $str;
		return $this->execQuery($str);
    }

    function update()
	{
		$str = "
            UPDATE NOMOR_SURAT
                SET 
                CABANG_ID             	='".$this->getField("CABANG_ID")."', 
                SATUAN_KERJA_ID       	='".$this->getField("SATUAN_KERJA_ID")."', 
                TIPE_NASKAH            	='".$this->getField("TIPE_NASKAH")."', 
                JENIS_NASKAH_ID         ='".$this->getField("JENIS_NASKAH_ID")."', 
                PENERBIT_NOMOR         	=".$this->getField("PENERBIT_NOMOR").", 
                KODE_JENIS_NASKAH       ='".$this->getField("KODE_JENIS_NASKAH")."', 
                KODE_JABATAN        	='".$this->getField("KODE_JABATAN")."', 
                BLN_SURAT       		='".$this->getField("BLN_SURAT")."', 
                THN_SURAT      			='".$this->getField("THN_SURAT")."',
                TEMP_SURAT      		='".$this->getField("TEMP_SURAT")."',
                URUT_SURAT      		='".$this->getField("URUT_SURAT")."',
                SURAT_MASUK_ID      	='".$this->getField("SURAT_MASUK_ID")."',
                SURAT_KELUAR_ID      	='".$this->getField("SURAT_KELUAR_ID")."',
                PERMOHONAN_NOMOR_ID     ='".$this->getField("PERMOHONAN_NOMOR_ID")."'
            WHERE TIPE_NASKAH ='".$this->getField("TIPE_NASKAH")."'

		"; 

		$this->query = $str;
		//echo $str;
		return $this->execQuery($str);
    }
	
	
    function updateByField()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "UPDATE NOMOR_SURAT  SET
				  ".$this->getField("FIELD")." = '".$this->getField("FIELD_VALUE")."'
				WHERE TIPE_NASKAH = ".$this->getField("TIPE_NASKAH")."
				"; 
				$this->query = $str;
	
		return $this->execQuery($str);
    }	

	function delete()
	{
        $str = "DELETE FROM NOMOR_SURAT
                WHERE 
                TIPE_NASKAH = ".$this->getField("TIPE_NASKAH").""; 
				  
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


    
    function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order=" ORDER BY SATUAN_KERJA_ID")
	{
		$str = "    
			SELECT CABANG_ID, SATUAN_KERJA_ID, TIPE_NASKAH, JENIS_NASKAH_ID,
                PENERBIT_NOMOR, KODE_JENIS_NASKAH, KODE_JABATAN, BLN_SURAT, 
                THN_SURAT, TEMP_SURAT, URUT_SURAT, SURAT_MASUK_ID, 
                SURAT_KELUAR_ID, PERMOHONAN_NOMOR_ID, LAST_CREATE_USER, LAST_CREATE_DATE
			FROM NOMOR_SURAT A
            WHERE 1 = 1
		"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement.$order;
		
		$this->query = $str;
		//echo $str;exit;
		return $this->selectLimit($str,$limit,$from); 
    }
    
    function selectByParamsMonitoring($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order=" ORDER BY SATUAN_KERJA_ID")
	{
		$str = "    
			SELECT A.CABANG_ID, A.SATUAN_KERJA_ID, A.TIPE_NASKAH, A.JENIS_NASKAH_ID,
				PENERBIT_NOMOR, KODE_JENIS_NASKAH, KODE_JABATAN, A.BLN_SURAT, 
				A.THN_SURAT, A.TEMP_SURAT, A.URUT_SURAT, A.SURAT_MASUK_ID, 
				A.SURAT_KELUAR_ID, A.PERMOHONAN_NOMOR_ID, A.LAST_CREATE_USER, A.LAST_CREATE_DATE,
				C.PERUNTUKAN, C.TANGGAL_SURAT, CASE WHEN C.TIPE_NASKAH = 'INTERNAL' THEN 'Surat Internal' 
				ELSE 'Surat Keluar' END TIPE_NASKAH_KET, C.SATUAN_KERJA, C.JENIS_NASKAH, B.NOMOR
			FROM NOMOR_SURAT A
			LEFT JOIN SURAT_MASUK B ON A.SURAT_MASUK_ID=B.SURAT_MASUK_ID
			LEFT JOIN PERMOHONAN_NOMOR C ON A.PERMOHONAN_NOMOR_ID=C.PERMOHONAN_NOMOR_ID
			WHERE 1=1
		"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement.$order;
		
		$this->query = $str;
		//echo $str;exit;
		return $this->selectLimit($str,$limit,$from); 
    }
	
    /** 
    * Hitung jumlah record berdasarkan parameter (array). 
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","IJIN_USAHA_ID"=>"yyy") 
    * @return long Jumlah record yang sesuai kriteria 
    **/ 
    function getCountByParams($paramsArray=array(), $statement="")
	{
		$str = "
			SELECT COUNT(TIPE_NASKAH) AS ROWCOUNT FROM NOMOR_SURAT A
		    WHERE TIPE_NASKAH IS NOT NULL ".$statement; 
		
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
		$str = "
			SELECT COUNT(A.TIPE_NASKAH) AS ROWCOUNT  
			FROM NOMOR_SURAT A
			LEFT JOIN SURAT_MASUK B ON A.SURAT_MASUK_ID=B.SURAT_MASUK_ID
			LEFT JOIN PERMOHONAN_NOMOR C ON A.PERMOHONAN_NOMOR_ID=C.PERMOHONAN_NOMOR_ID
		    WHERE A.TIPE_NASKAH IS NOT NULL ".$statement; 
		
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