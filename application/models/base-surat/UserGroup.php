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
  * Entity-base class untuk mengimplementasikan tabel USER_GROUP.
  * 
  ***/
  include_once(APPPATH.'/models/Entity.php');

  class UserGroup extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function UserGroup()
	{
      $this->Entity(); 
    }
	
	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("USER_GROUP_ID", $this->getNextId("USER_GROUP_ID","USER_GROUP"));

		$str = "
				INSERT INTO USER_GROUP (
					   USER_GROUP_ID, AKSES_ADM_SURAT_ID, AKSES_ADM_ARSIP_ID, 
					   NAMA, KETERANGAN, LAST_CREATE_USER, LAST_CREATE_DATE
				)
 			  	VALUES (
				  ".$this->getField("USER_GROUP_ID").",
				  ".$this->getField("AKSES_ADM_SURAT_ID").",
				  ".$this->getField("AKSES_ADM_ARSIP_ID").",
				  '".$this->getField("NAMA")."',
				  '".$this->getField("KETERANGAN")."',
				  '".$this->getField("LAST_CREATE_USER")."',
				  CURRENT_DATE
				)"; 
		$this->query = $str;

		return $this->execQuery($str);
    }

    function update()
	{
		$str = "
				UPDATE USER_GROUP
				SET    
					   AKSES_ADM_SURAT_ID	= ".$this->getField("AKSES_ADM_SURAT_ID").",
					   AKSES_ADM_ARSIP_ID	= ".$this->getField("AKSES_ADM_ARSIP_ID").",
				  	   NAMA					= '".$this->getField("NAMA")."',
				  	   KETERANGAN			= '".$this->getField("KETERANGAN")."',
				       LAST_UPDATE_USER		= '".$this->getField("LAST_UPDATE_USER")."',
				  	   LAST_UPDATE_DATE		= CURRENT_DATE
				WHERE  USER_GROUP_ID     	= '".$this->getField("USER_GROUP_ID")."'

			 "; 
		$this->query = $str;
		//echo $this->query;exit;
		return $this->execQuery($str);
    }

	function delete()
	{
        $str = "DELETE FROM USER_GROUP
                WHERE 
                  USER_GROUP_ID = ".$this->getField("USER_GROUP_ID").""; 
				  
		$this->query = $str;
        return $this->execQuery($str);
    }

	function deleteAll()
	{
        $str1 = "DELETE FROM USERS
                WHERE 
                  USER_GROUP_ID = ".$this->getField("USER_GROUP_ID").""; 
		$this->execQuery($str1);
		
        $str = "DELETE FROM USER_GROUP
                WHERE 
                  USER_GROUP_ID = ".$this->getField("USER_GROUP_ID").""; 
				  
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
    function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order=" ORDER BY A.NAMA ASC")
	{
		$str = "
				  SELECT USER_GROUP_ID, A.NAMA, KETERANGAN, A.AKSES_ADM_SURAT_ID, B.NAMA AKSES_ADM_SURAT, C.NAMA AKSES_ADM_ARSIP, A.AKSES_ADM_ARSIP_ID
				  FROM USER_GROUP A 
				  LEFT JOIN AKSES_ADM_SURAT B ON A.AKSES_ADM_SURAT_ID=B.AKSES_ADM_SURAT_ID
				  LEFT JOIN AKSES_ADM_ARSIP C ON A.AKSES_ADM_ARSIP_ID=C.AKSES_ADM_ARSIP_ID
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
				SELECT USER_GROUP_ID, AKSES_ADM_SURAT_ID, NAMA, KETERANGAN, LAST_CREATE_USER, LAST_CREATE_DATE, 
				   LAST_UPDATE_USER, LAST_UPDATE_DATE
			   FROM USER_GROUP WHERE 1=1

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
		$str = "SELECT COUNT(USER_GROUP_ID) AS ROWCOUNT 
				FROM USER_GROUP A 
				LEFT JOIN AKSES_ADM_SURAT B ON A.AKSES_ADM_SURAT_ID=B.AKSES_ADM_SURAT_ID
				LEFT JOIN AKSES_ADM_ARSIP C ON A.AKSES_ADM_ARSIP_ID=C.AKSES_ADM_ARSIP_ID
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
		$str = "SELECT COUNT(USER_GROUP_ID) AS ROWCOUNT 
				FROM USER_GROUP
		        WHERE USER_GROUP_ID IS NOT NULL ".$statement; 
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