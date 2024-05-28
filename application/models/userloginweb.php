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

  class UserLoginWeb extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function UserLoginWeb()
	{
      $this->Entity(); 
    }
	
	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("USER_LOGIN_WEB_ID", $this->getNextId("USER_LOGIN_WEB_ID","USER_LOGIN_WEB"));
		$this->setField("TOKEN", $this->getToken($this->getField("PEGAWAI_ID")));

		$str = "
				INSERT INTO USER_LOGIN_WEB (
				   USER_LOGIN_WEB_ID, PEGAWAI_ID, WAKTU_LOGIN, 
				   TOKEN, STATUS, DEVICE_ID, TOKEN_FIREBASE) 
				VALUES ( '".$this->getField("USER_LOGIN_WEB_ID")."',
				 '".$this->getField("PEGAWAI_ID")."',
				 ".$this->getField("WAKTU_LOGIN").",
				 '".$this->getField("TOKEN")."',
				 '".$this->getField("STATUS")."',
				 '".$this->getField("DEVICE_ID")."',
				 '".$this->getField("TOKEN_FIREBASE")."')

				"; 
		$this->id = $this->getField("USER_LOGIN_WEB_ID");
		$this->idToken = $this->getField("TOKEN");
		// echo $str;
		$this->query = $str;
		return $this->execQuery($str);
    }

    function insertbak()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("USER_LOGIN_WEB_ID", $this->getNextId("USER_LOGIN_WEB_ID","USER_LOGIN_WEB"));
		$this->setField("TOKEN", $this->getToken($this->getField("PEGAWAI_ID")));

		$str = "
				INSERT INTO USER_LOGIN_WEB (
				   USER_LOGIN_WEB_ID, PEGAWAI_ID, WAKTU_LOGIN, 
				   TOKEN, STATUS, DEVICE_ID, IMEI, TOKEN_FIREBASE) 
				VALUES ( '".$this->getField("USER_LOGIN_WEB_ID")."',
				 '".$this->getField("PEGAWAI_ID")."',
				 ".$this->getField("WAKTU_LOGIN").",
				 '".$this->getField("TOKEN")."',
				 '".$this->getField("STATUS")."',
				 '".$this->getField("DEVICE_ID")."',
				 '".$this->getField("IMEI")."',
				 '".$this->getField("TOKEN_FIREBASE")."' )
				"; 
		$this->id = $this->getField("USER_LOGIN_WEB_ID");
		$this->idToken = $this->getField("TOKEN");
		// echo $str; exit;
		$this->query = $str;
		return $this->execQuery($str);
    }

    function update()
	{
		$str = "
				UPDATE USER_LOGIN_WEB
				SET    	STATUS   		= '".$this->getField("STATUS")."'
				WHERE  	TOKEN 			= '".$this->getField("TOKEN")."'
			 "; 
		$this->query = $str;
		//echo $str;
		return $this->execQuery($str);
    }

    function updateTokenFirebase()
	{
		$str = "
				UPDATE USER_LOGIN_WEB
				SET    	TOKEN_FIREBASE  = '".$this->getField("TOKEN_FIREBASE")."'
				WHERE  	TOKEN 			= '".$this->getField("TOKEN")."'
			 "; 
		$this->query = $str;
		//echo $str;
		return $this->execQuery($str);
    }

    function updateStatusPegawai()
	{
		$str = "
				UPDATE USER_LOGIN_WEB
				SET    	STATUS   		= '".$this->getField("STATUS")."'
				WHERE  	PEGAWAI_ID 		= '".$this->getField("PEGAWAI_ID")."'
			 "; 
		$this->query = $str;
		// echo $str;exit();
		return $this->execQuery($str);
    }
    
	function delete()
	{
        $str = "DELETE FROM USER_LOGIN_WEB
                WHERE 
                  PEGAWAI_ID = '".$this->getField("PEGAWAI_ID")."' AND 
                  TOKEN = '".$this->getField("TOKEN")."' "; 
				  
		$this->query = $str;
        return $this->execQuery($str);
    }
    
	function deletePegawai()
	{
        $str = "DELETE FROM USER_LOGIN_WEB
                WHERE 
                  PEGAWAI_ID = '".$this->getField("PEGAWAI_ID")."' "; 
				  
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
    function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order="ORDER BY USER_LOGIN_WEB_ID ASC")
	{
		$str = "
				SELECT 
				USER_LOGIN_WEB_ID, A.PEGAWAI_ID, WAKTU_LOGIN, 
				   TOKEN, STATUS, DEVICE_ID, 
				   TOKEN_FIREBASE, B.NAMA NAMA_PEGAWAI
				FROM USER_LOGIN_WEB A
				LEFT JOIN PEGAWAI B ON A.PEGAWAI_ID = B.PEGAWAI_ID
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

    function selectByParamsMonitoring($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order="ORDER BY USER_LOGIN_WEB_ID ASC")
	{
		$str = "
				SELECT USER_LOGIN_WEB_ID, PEGAWAI_ID, WAKTU_LOGIN, STATUS, DEVICE_ID, 
				       IMEI, TOKEN_FIREBASE, TOKEN, STATUS_SINKRONISASI
				  FROM USER_LOGIN_WEB A
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
				  SELECT USER_LOGIN_WEB_ID
				  FROM USER_LOGIN_WEB                  
				  WHERE 0=0
			    "; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key LIKE '%$val%' ";
		}
		
		$this->query = $str;
		$str .= $statement." ORDER BY USER_LOGIN_WEB_ID ASC";
		return $this->selectLimit($str,$limit,$from); 
    }	
    /** 
    * Hitung jumlah record berdasarkan parameter (array). 
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","IJIN_USAHA_ID"=>"yyy") 
    * @return long Jumlah record yang sesuai kriteria 
    **/ 
    function getCountByParams($paramsArray=array(), $statement="")
	{
		$str = "SELECT COUNT(A.PEGAWAI_ID) AS ROWCOUNT 
				FROM USER_LOGIN_WEB A
                LEFT JOIN LINK.PEGAWAI B ON B.PEGAWAI_ID = A.PEGAWAI_ID
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

    function getCountByParamsMonitoring($paramsArray=array(), $statement="")
	{
		$str = "SELECT COUNT(A.PEGAWAI_ID) AS ROWCOUNT 
				FROM USER_LOGIN_WEB A
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

    function getCountByParamsUserOnline($paramsArray=array(), $statement="")
	{
		$str = "SELECT COUNT(A.PEGAWAI_ID) AS ROWCOUNT FROM USER_LOGIN_WEB A
			WHERE 1=1 ".$statement; 
		
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
		$str = "SELECT COUNT(USER_LOGIN_WEB_ID) AS ROWCOUNT FROM USER_LOGIN_WEB A
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
	
	function getTokenPegawaiId($paramsArray=array(), $statement="")
	{
		$str = "SELECT PEGAWAI_ID AS ROWCOUNT FROM USER_LOGIN_WEB A
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
	
	function getTokenFirebase($paramsArray=array(), $statement="")
	{
		$str = "SELECT TOKEN_FIREBASE AS ROWCOUNT FROM USER_LOGIN_WEB A
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