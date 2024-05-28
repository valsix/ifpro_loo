<? 
/* *******************************************************************************************************
MODUL NAME 			: E LEARNING
FILE NAME 			: 
AUTHOR				: 
VERSION				: 1.0
MODIFICATION DOC	:
DESCRIPTION			: 
***************************************************************************************************** */

  /***
  * Entity-base class untuk mengimplementasikan tabel KontakPegawai.
  * 
  ***/
  include_once(APPPATH.'/models/Entity.php');
  
  class Users extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function Users()
	{
      $this->Entity(); 
    }
	
	function selectByIdPassword($username,$password){
      /** YOU CAN INSERT/CHANGE CODES IN THIS SECTION **/
	  //$passwd = md5($passwd);
      
	  $str = "SELECT A.PEGAWAI_ID, A.NIP, A.NAMA, A.JABATAN, A.SATUAN_KERJA_ID, B.NAMA SATUAN_KERJA, TREE_ID, TREE_PARENT, B.KODE_LEVEL, 
	  			KODE_LEVEL, C.SATUAN_KERJA_ID_ASAL, C.USER_GROUP_ID, JENIS_KELAMIN
				FROM PEGAWAI A 
				INNER JOIN SATUAN_KERJA B ON A.SATUAN_KERJA_ID = B.SATUAN_KERJA_ID
				INNER JOIN USER_LOGIN C ON A.PEGAWAI_ID = C.PEGAWAI_ID
				WHERE C.USER_LOGIN = '".$username."' 
				AND 
				(
					C.STATUS = '1' 
					OR 
					COALESCE(NULLIF(C.STATUS, ''), NULL) IS NULL
				)
				";
      $this->query = $str;
	  //echo$str; exit();
	  return $this->select($str);     
    }
	
	
	function selectByIdBypass($username,$token){
      /** YOU CAN INSERT/CHANGE CODES IN THIS SECTION **/
	  //$passwd = md5($passwd);
      
	  $str = "SELECT A.PEGAWAI_ID, A.NIP, A.NAMA, A.JABATAN, A.SATUAN_KERJA_ID, B.NAMA SATUAN_KERJA, TREE_ID, TREE_PARENT, B.KODE_LEVEL, 
	  			 C.SATUAN_KERJA_ID_ASAL, JENIS_KELAMIN, D.USER_GROUP_ID, D.TOKEN_FIREBASE
				FROM PEGAWAI A 
				INNER JOIN SATUAN_KERJA B ON A.SATUAN_KERJA_ID = B.SATUAN_KERJA_ID
				INNER JOIN USER_LOGIN C ON A.PEGAWAI_ID = C.PEGAWAI_ID
				INNER JOIN USER_LOGIN_MOBILE D ON A.PEGAWAI_ID = D.PEGAWAI_ID
				WHERE C.USER_LOGIN = '".$username."' AND D.TOKEN = '".$token."' ";
      $this->query = $str;
	  //echo$str; exit();
	  return $this->select($str);     
    }
	

	function selectByIdPasswordMobile($username,$password){
      /** YOU CAN INSERT/CHANGE CODES IN THIS SECTION **/
	  //$passwd = md5($passwd);
      
		$str = "
		  	SELECT USER_LOGIN_ID, USER_GROUP_ID, A.PEGAWAI_ID, A.NAMA, B.EMAIL, B.JABATAN,
				B.SATUAN_KERJA_ID CABANG_ID, SATUAN_KERJA CABANG, DEPARTEMEN_ID SATUAN_KERJA_ID_ASAL,
				DEPARTEMEN SATUAN_KERJA_ASAL, C.KODE_LEVEL, C.TREE_ID, C.TREE_PARENT, D.KELOMPOK_JABATAN,
				C.SATUAN_KERJA_ID_PARENT, C.KODE_SO, C.KODE_PARENT, C.KODE_SURAT, C.KODE_SURAT_KELUAR, C.NIP NIP_ATASAN, 
				(SELECT X.KODE_LEVEL FROM SATUAN_KERJA X WHERE B.PEGAWAI_ID = X.NIP) KODE_LEVEL_PEJABAT
			FROM USER_LOGIN A
			LEFT JOIN PEGAWAI B ON B.PEGAWAI_ID=A.PEGAWAI_ID
			LEFT JOIN SATUAN_KERJA C ON A.SATUAN_KERJA_ID_ASAL = C.SATUAN_KERJA_ID
			LEFT JOIN SATUAN_KERJA D ON A.PEGAWAI_ID = D.NIP
			WHERE A.USER_LOGIN = '".$username."' 
			AND 
			(
				A.STATUS = '1' 
				OR 
				COALESCE(NULLIF(A.STATUS, ''), NULL) IS NULL
			)
			";
			// echo $str;exit;
	    $this->query = $str;
		return $this->select($str);     
    }
	
	function selectByPegawai($username)
	{
      /** YOU CAN INSERT/CHANGE CODES IN THIS SECTION **/
	  //$passwd = md5($passwd);
      
	  $str = "SELECT COALESCE(B.SATUAN_KERJA_ID, A.DEPARTEMEN_ID) SATUAN_KERJA_ID,  COALESCE(B.NAMA, C.NAMA) SATUAN_KERJA, 
	  			COALESCE(B.KODE_LEVEL, C.KODE_LEVEL) KODE_LEVEL, AMBIL_HIRARKI(COALESCE(B.SATUAN_KERJA_ID, A.DEPARTEMEN_ID)) HIRARKI,
				B.KODE_LEVEL KODE_LEVEL_PEJABAT, 
				A.JABATAN SATUAN_KERJA_JABATAN,
				B.KELOMPOK_JABATAN, C.KODE_PARENT
				FROM PEGAWAI A 
				LEFT JOIN SATUAN_KERJA B ON A.PEGAWAI_ID = B.NIP
				LEFT JOIN SATUAN_KERJA C ON A.DEPARTEMEN_ID = C.SATUAN_KERJA_ID
		        WHERE A.PEGAWAI_ID = '".$username."' ";
      $this->query = $str;
	  //echo $str; exit;
	  return $this->select($str);         
    }

    function selectByPegawaiId($pegawaiId){
      /** YOU CAN INSERT/CHANGE CODES IN THIS SECTION **/
	  //$passwd = md5($passwd);
      
	  $str = "SELECT A.PEGAWAI_ID, A.NIP, A.NAMA, A.JABATAN, A.SATUAN_KERJA_ID, B.NAMA SATUAN_KERJA, B.KELOMPOK_JABATAN, 
	  				 B.TREE_ID, B.TREE_PARENT, B.KODE_LEVEL KODE_LEVEL_PEJABAT, COALESCE(C.SATUAN_KERJA_ID, A.PEGAWAI_ID) SATUAN_KERJA_ID_ASAL, 
					 COALESCE(C.KODE_LEVEL, 'PEGAWAI') KODE_LEVEL, JENIS_KELAMIN, C.KODE_PARENT, AMBIL_HIRARKI(COALESCE(B.SATUAN_KERJA_ID, A.DEPARTEMEN_ID)) HIRARKI
				FROM PEGAWAI A 
				INNER JOIN SATUAN_KERJA B ON A.SATUAN_KERJA_ID = B.SATUAN_KERJA_ID
				LEFT  JOIN SATUAN_KERJA C ON A.NIP = C.NIP
				WHERE A.PEGAWAI_ID = '".$pegawaiId."' ";
		// echo $str;exit;
      $this->query = $str;
	  return $this->select($str);     
    }


	
	function selectByUserLogin($username)
	{
      /** YOU CAN INSERT/CHANGE CODES IN THIS SECTION **/
	  //$passwd = md5($passwd);
      
	  $str = "SELECT A.PEGAWAI_ID, A.NIP, A.NAMA, A.JABATAN, A.SATUAN_KERJA_ID, D.NAMA SATUAN_KERJA, D.TREE_ID, D.TREE_PARENT, D.KODE_LEVEL, 
	  			D.KODE_LEVEL, C.SATUAN_KERJA_ID_ASAL, C.USER_GROUP_ID, AMBIL_HIRARKI(C.SATUAN_KERJA_ID_ASAL) HIRARKI,
	  			D.JABATAN SATUAN_KERJA_JABATAN, D.KODE_PARENT, D.NIP NIP_ATASAN
				FROM PEGAWAI A 
				INNER JOIN SATUAN_KERJA B ON A.SATUAN_KERJA_ID = B.SATUAN_KERJA_ID
				INNER JOIN USER_LOGIN C ON A.PEGAWAI_ID = C.PEGAWAI_ID
				LEFT  JOIN SATUAN_KERJA D ON C.SATUAN_KERJA_ID_ASAL = D.SATUAN_KERJA_ID
		        WHERE A.PEGAWAI_ID = '".$username."' 
		        AND 
		        (
		        	C.STATUS = '1' 
		        	OR 
					COALESCE(NULLIF(C.STATUS, ''), NULL) IS NULL
		        )
		        ";
      $this->query = $str;

      //echo $str; exit;

	  return $this->select($str);         
    }


	function selectBypass($id_usr){
      /** YOU CAN INSERT/CHANGE CODES IN THIS SECTION **/
	  //$passwd = md5($passwd);
      
	  $str = "SELECT 
				A.PEGAWAI_ID, A.USER_LOGIN_ID, D.DEPARTEMEN_ID, A.USER_GROUP_ID, B.NAMA USER_GROUP, 
				   D.NRP, A.NAMA, F.NAMA JABATAN, A.EMAIL, 
				   A.TELEPON, STATUS, USER_LOGIN, 
				   USER_PASS, C.NAMA DEPARTEMEN, CABANG_ID,
				   B.AKSES_APP_HELPDESK_ID, E.KODE HAK_AKSES, E.NAMA HAK_AKSES_DESC
				FROM IMASYS.USER_LOGIN A 
				LEFT JOIN IMASYS.USER_GROUP B ON A.USER_GROUP_ID = B.USER_GROUP_ID 
                LEFT JOIN IMASYS_SIMPEG.PEGAWAI D ON A.PEGAWAI_ID = D.PEGAWAI_ID
                LEFT JOIN IMASYS_SIMPEG.DEPARTEMEN C ON D.DEPARTEMEN_ID = C.DEPARTEMEN_ID 
				LEFT JOIN IMASYS.AKSES_APP_HELPDESK E ON B.AKSES_APP_HELPDESK_ID = E.AKSES_APP_HELPDESK_ID
				LEFT JOIN IMASYS_SIMPEG.PEGAWAI_JABATAN_TERAKHIR F ON A.PEGAWAI_ID = F.PEGAWAI_ID
				WHERE 1 = 1 AND B.AKSES_APP_HELPDESK_ID IS NOT NULL AND IMASYS.MD5(A.PEGAWAI_ID || 'H3LPD35K') ='".$id_usr."' AND STATUS = 1 ";
      $this->query = $str;
	  
	  return $this->select($str);         
    }	
	
	
    function getSatuanKerjaPegawai($nip)
	{
		$str = "SELECT COUNT(A.SATUAN_KERJA_ID) AS SATUAN_KERJA_ID 
				FROM SATUAN_KERJA A
		        WHERE NIP = '".$nip."' "; 

		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("SATUAN_KERJA_ID"); 
		else 
			return $nip; 
    }
	
	
	function login_anggit($username)
	{
      /** YOU CAN INSERT/CHANGE CODES IN THIS SECTION **/
	  //$passwd = md5($passwd);
      
	  $str = "SELECT * FROM PEGAWAI WHERE PEGAWAI_ID = '".$username."'";
      $this->query = $str;
      // echo $str;exit();
	  
	  return $this->select($str);         
    }

    function getTokenGoogle($pegawaiId='')
    {
    	if($pegawaiId == ''){
    		return '';
    	}else{
    		$str = "SELECT TOKEN_GOOGLE FROM USER_LOGIN 
		        WHERE PEGAWAI_ID = '".$pegawaiId."'"; 
			
			$this->select($str); 
			if($this->firstRow()) 
				return $this->getField("TOKEN_GOOGLE"); 
			else 
				return 0; 
    	}
    }

    function setTokenGoogle($pegawaiId='', $tokenGoogle='')
    {
    	$str = "
				UPDATE USER_LOGIN
				SET    	TOKEN_GOOGLE   		= '".$tokenGoogle."'
				WHERE  	PEGAWAI_ID 			= '".$pegawaiId."'
			 "; 
		$this->query = $str;
		//echo $str;
		return $this->execQuery($str);
    }

  } 
?>