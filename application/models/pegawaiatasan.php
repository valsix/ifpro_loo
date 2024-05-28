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

  class PegawaiAtasan extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function PegawaiAtasan()
	{
      $this->Entity(); 
    }

    /** 
    * Cari record berdasarkan array parameter dan limit tampilan 
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","IJIN_USAHA_ID"=>"yyy") 
    * @param int limit Jumlah maksimal record yang akan diambil 
    * @param int from Awal record yang diambil 
    * @return boolean True jika sukses, false jika tidak 
    **/ 
    function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order="ORDER BY B.NAMA ASC")
	{
		$str = "
				 SELECT A.PEGAWAI_ID, PEGAWAI_ID_ATASAN, B.NAMA
				  FROM LINK.PEGAWAI_ATASAN A
					LEFT JOIN LINK.PEGAWAI B ON A.PEGAWAI_ID = B.PEGAWAI_ID
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
    
    function selectByParamsRekapBawahan($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order="ORDER BY B.NAMA ASC")
	{
		$str = "
				 SELECT A.PEGAWAI_ID, PEGAWAI_ID_ATASAN, B.NAMA NAMA_PEGAWAI,
					(SELECT COUNT(1) JUMLAH_SELESAI FROM AKTIFITAS.DAILY_REPORT X WHERE X.PEGAWAI_ID = A.PEGAWAI_ID) TOTAL
				  FROM LINK.PEGAWAI_ATASAN A
					LEFT JOIN LINK.PEGAWAI B ON A.PEGAWAI_ID = B.PEGAWAI_ID
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
				SELECT PEGAWAI_ID, PEGAWAI_ID_ATASAN, NAMA_ATASAN, JABATAN_ATASAN
				  FROM LINK.PEGAWAI_ATASAN A
				  WHERE 1=1
			    "; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key LIKE '%$val%' ";
		}
		
		$this->query = $str;
		$str .= $statement." ORDER BY NAMA ASC";
		return $this->selectLimit($str,$limit,$from); 
    }	
    /** 
    * Hitung jumlah record berdasarkan parameter (array). 
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","IJIN_USAHA_ID"=>"yyy") 
    * @return long Jumlah record yang sesuai kriteria 
    **/ 
    function getCountByParams($paramsArray=array(), $statement="")
	{
		$str = "SELECT COUNT(PEGAWAI_ID) AS ROWCOUNT FROM LINK.PEGAWAI_ATASAN 
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
		$str = "SELECT COUNT(PEGAWAI_ID) AS ROWCOUNT FROM LINK.PEGAWAI_ATASAN 
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
  } 
?>