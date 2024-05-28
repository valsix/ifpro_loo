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

  class UserLogin extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function UserLogin()
	{
      $this->Entity(); 
    }
	
	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("USER_LOGIN_ID", $this->getNextId("USER_LOGIN_ID","USER_LOGIN"));

		$str = "
				INSERT INTO USER_LOGIN (
				   USER_LOGIN_ID, USER_GROUP_ID, PEGAWAI_ID, 
				   SATUAN_KERJA_ID_ASAL,
				   NAMA, JABATAN, EMAIL, 
				   TELEPON, STATUS, USER_LOGIN, 
				   USER_PASS, FOTO, LAST_CREATE_USER, LAST_CREATE_DATE) 
				VALUES ( '".$this->getField("USER_LOGIN_ID")."', '".$this->getField("USER_GROUP_ID")."', '".$this->getField("PEGAWAI_ID")."',
					'".$this->getField("SATUAN_KERJA_ID_ASAL")."', 
					'".$this->getField("NAMA")."', '".$this->getField("JABATAN")."', '".$this->getField("EMAIL")."',
					'".$this->getField("TELEPON")."', '".$this->getField("STATUS")."', '".$this->getField("USER_LOGIN")."',
					'".$this->getField("USER_PASS")."', '".$this->getField("FOTO")."', '".$this->getField("LAST_CREATE_USER")."', CURRENT_DATE )
				"; 
		$this->id = $this->getField("USER_LOGIN_ID");
		$this->query = $str;
		return $this->execQuery($str);
    }

    function update()
	{
		$str = "
				UPDATE USER_LOGIN
				SET    USER_GROUP_ID    = '".$this->getField("USER_GROUP_ID")."',
					   SATUAN_KERJA_ID_ASAL  = '".$this->getField("SATUAN_KERJA_ID_ASAL")."',
					   LAST_UPDATE_USER = '".$this->getField("LAST_UPDATE_USER")."',
					   LAST_UPDATE_DATE = CURRENT_DATE
				WHERE  USER_LOGIN_ID    = '".$this->getField("USER_LOGIN_ID")."'

			 "; 
		$this->query = $str;
		//echo $str;
		return $this->execQuery($str);
    }

    function updateFoto()
	{
		$str = "
				UPDATE USER_LOGIN
				SET    FOTO    = '".$this->getField("FOTO")."'
				WHERE  PEGAWAI_ID    = '".$this->getField("PEGAWAI_ID")."'

			 "; 
		$this->query = $str;
		//echo $str;
		return $this->execQuery($str);
    }

	function delete()
	{
        $str = "DELETE FROM USER_LOGIN
                WHERE 
                  USER_LOGIN_ID = ".$this->getField("USER_LOGIN_ID").""; 
				  
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
    function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order="ORDER BY USER_LOGIN_ID ASC")
	{
		$str = "
				SELECT 
				USER_LOGIN_ID, A.USER_GROUP_ID, A.PEGAWAI_ID, 
				   A.NAMA, A.JABATAN, A.EMAIL, A.SATUAN_KERJA_ID_ASAL,
				   A.TELEPON, STATUS, USER_LOGIN, 
				   USER_PASS, B.JABATAN, C.NAMA SATUAN_KERJA, D.NAMA SATUAN_KERJA_ASAL, FOTO 
				FROM USER_LOGIN A 
				INNER JOIN PEGAWAI B ON A.PEGAWAI_ID = B.PEGAWAI_ID
				INNER JOIN SATUAN_KERJA C ON B.SATUAN_KERJA_ID = C.SATUAN_KERJA_ID
				LEFT JOIN SATUAN_KERJA D ON A.SATUAN_KERJA_ID_ASAL = D.SATUAN_KERJA_ID
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
				  SELECT USER_LOGIN_ID, NAMA
				  FROM LINK.USER_LOGIN                  
				  WHERE 0=0
			    "; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key LIKE '%$val%' ";
		}
		
		$this->query = $str;
		$str .= $statement." ORDER BY USER_LOGIN_ID ASC";
		return $this->selectLimit($str,$limit,$from); 
    }	
    /** 
    * Hitung jumlah record berdasarkan parameter (array). 
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","IJIN_USAHA_ID"=>"yyy") 
    * @return long Jumlah record yang sesuai kriteria 
    **/ 
    function getCountByParams($paramsArray=array(), $statement="")
	{
		$str = "SELECT COUNT(A.USER_LOGIN_ID) AS ROWCOUNT 
				FROM USER_LOGIN A
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
		$str = "SELECT COUNT(USER_LOGIN_ID) AS ROWCOUNT FROM LINK.USER_LOGIN 
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

    function getNamaPegawai($pegawaiId='')
    {
    	if($pegawaiId == ''){
    		return '';
    	}else{
    		$str = "SELECT NAMA FROM LINK.USER_LOGIN 
		        WHERE PEGAWAI_ID = '".$pegawaiId."'"; 
			
			$this->select($str); 
			if($this->firstRow()) 
				return $this->getField("NAMA"); 
			else 
				return 0; 
    	}
    }
  } 
?>