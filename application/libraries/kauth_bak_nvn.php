<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once 'kloader.php';

class kauth {
    //put your code here
    private $ldap_config = array('server1'=>array(   'host'=>'10.0.0.11',
                                    'useStartTls'=>false,
                                    'accountDomainName'=>'pp3.co.id',
                                    'accountDomainNameShort'=>'PP3',
                                    'accountCanonicalForm'=>3,
                                    'baseDn'=>"DC=pp3,DC=co,DC=id"));


        function __construct() {
//        load the auth class
        kloader::load('Zend_Auth');
        kloader::load('Zend_Auth_Storage_Session');
        
//        set the unique storege
        Zend_Auth::getInstance()->setStorage(new Zend_Auth_Storage_Session("shgfhgjhgjkhjrakkesan4kemasti"));
    }
    
    public function localAuthenticate($username,$credential) {
        $auth = Zend_Auth::getInstance();
        $auth->clearIdentity();
        
		$CI =& get_instance();
					
		/* USER AUTH */
		
		// Define $username and $password
		// using ldap bind
        $ldaprdn  = 'ho' . "\\" .$username;     // ldap rdn or dn
        $ldappass = $credential;  // associated password
		
		
		$ldapconn = ldap_connect("172.16.30.106")
        or die("Could not connect to LDAP server.");
		

		ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3);
		ldap_set_option($ldapconn, LDAP_OPT_REFERRALS, 0);

		// binding to ldap server
		$ldapbind = @ldap_bind($ldapconn, $ldaprdn, $ldappass);
		// verify binding
		if ($ldapbind) 
		{}
		else
			return false;		
			

	   $filter="(sAMAccountName=$username)";
	   $result = ldap_search($ldapconn,"dc=ho,dc=pjbservices,dc=com",$filter);
	   ldap_sort($ldapconn,$result,"sn");
	   $info = ldap_get_entries($ldapconn, $result);		   
	   
	   $username = $info[0]["samaccountname"][0];		   						
		
		$CI->load->model("Pegawai");
		$CI->load->model("AbsensiRekap");

		$pegawai = new Pegawai();
		$pegawai->selectByParamsInformasiLogin(array("A.PEGAWAI_ID" => $username));
		if($pegawai->firstRow())
		{
			$identity = new stdClass();
			$identity->ID = $pegawai->getField("PEGAWAI_ID");
			$identity->NAMA = $pegawai->getField("NAMA");
			$identity->USERNAME = $pegawai->getField("PEGAWAI_ID");
			$identity->KODE_CABANG = $pegawai->getField("CABANG_ID");
			$identity->CABANG = $pegawai->getField("NAMA_CABANG");
			$identity->CABANG_LOKASI = $pegawai->getField("LOKASI");
			$identity->KODE_DEPARTEMEN = $pegawai->getField("DEPARTEMEN_ID");
			$identity->DEPARTEMEN = $pegawai->getField("NAMA_DEPARTEMEN");
			$identity->KODE_SUB_DEPARTEMEN = $pegawai->getField("SUB_DEPARTEMEN_ID");
			$identity->SUB_DEPARTEMEN = $pegawai->getField("NAMA_SUB_DEPARTEMEN");
			$identity->KODE_STAFF = $pegawai->getField("STAFF_ID");
			$identity->STAFF = $pegawai->getField("NAMA_STAFF");
			$identity->KODE_FUNGSI = $pegawai->getField("FUNGSI_ID");
			$identity->FUNGSI = $pegawai->getField("NAMA_FUNGSI");
			$identity->JABATAN = $pegawai->getField("NAMA_JABATAN");
			$identity->STATUS_PEGAWAI = trim($pegawai->getField("STATUS_PEGAWAI"));
			$identity->CUTI_TAHUNAN_AKTIF = $pegawai->getField("CUTI_TAHUNAN_AKTIF");
			$identity->CUTI_BESAR_AKTIF = $pegawai->getField("CUTI_BESAR_AKTIF");
			$identity->TANGGAL_MASUK = $pegawai->getField("TANGGAL_MASUK");
			$identity->KELOMPOK = $pegawai->getField("KELOMPOK");	
			$identity->JENIS_KELAMIN = $pegawai->getField("JENIS_KELAMIN");	
			$identity->AKSES_MASTER = $pegawai->getField("AKSES_MASTER");	
			$identity->AKSES_LAPORAN = $pegawai->getField("AKSES_LAPORAN");	
			$identity->AKSES_UNIT = $pegawai->getField("AKSES_UNIT");	
			$identity->AKSES_PROSES_REKAP = $pegawai->getField("AKSES_PROSES_REKAP");
			
			
			if($identity->KELOMPOK == "N")		
				$kelompok_keterangan = "Non-Shift";
			else
				$kelompok_keterangan = "Shift";
				
			$identity->KELOMPOK_KETERANGAN = $kelompok_keterangan;			
			$identity->ISLOGIN = 1;
			
			/* APPROVAL */
			if($identity->KODE_CABANG == "KP" || $identity->KODE_CABANG == "KO")
			{
				/* JIKA JABATAN ASMAN */
				if($identity->KODE_STAFF == "03")
				{
					$identity->APPROVAL[0] = "";
					$identity->APPROVAL[1] = "Manager";
					$identity->APPROVAL_ID[0] = "''";
					$identity->APPROVAL_ID[1] = "'02'";
					$identity->APPROVAL_STATEMENT[0] = array();
					$identity->APPROVAL_STATEMENT[1] = array("A.CABANG_ID" => $identity->KODE_CABANG, 
															 "A.DEPARTEMEN_ID" => $identity->KODE_DEPARTEMEN, 
															 "A.SUB_DEPARTEMEN_ID" => $identity->KODE_SUB_DEPARTEMEN);	
									   			
				}
				elseif($identity->KODE_STAFF == "04" || $identity->KODE_STAFF == "05")
				{
					$identity->APPROVAL[0] = "";
					$identity->APPROVAL[1] = "Direktur";
					$identity->APPROVAL_ID[0] = "''";
					$identity->APPROVAL_ID[1] = "'08'";			
					$identity->APPROVAL_STATEMENT[0] = array();
					$identity->APPROVAL_STATEMENT[1] = array("A.CABANG_ID" => $identity->KODE_CABANG, 
												   			 "A.DEPARTEMEN_ID" => $identity->KODE_DEPARTEMEN);								
				}
				elseif($identity->KODE_STAFF == "02")
				{
					$identity->APPROVAL[0] = "";
					$identity->APPROVAL[1] = "Direktur";
					$identity->APPROVAL_ID[0] = "''";
					$identity->APPROVAL_ID[1] = "'01'";			
					$identity->APPROVAL_STATEMENT[0] = array();
					$identity->APPROVAL_STATEMENT[1] = array("A.CABANG_ID" => $identity->KODE_CABANG, 
												   			 "A.DEPARTEMEN_ID" => $identity->KODE_DEPARTEMEN);								
				}
				else
				{
					/* JIKA STAFF AHLI */
					if($identity->KODE_SUB_DEPARTEMEN == "" && $identity->KODE_FUNGSI == "")
					{	
						$identity->APPROVAL[0] = "";
						$identity->APPROVAL[1] = "Direktur";
						$identity->APPROVAL_ID[0] = "''";
						$identity->APPROVAL_ID[1] = "'01'";			
						$identity->APPROVAL_STATEMENT[0] = array();
						$identity->APPROVAL_STATEMENT[1] = array("A.CABANG_ID" => $identity->KODE_CABANG, 
																 "A.DEPARTEMEN_ID" => $identity->KODE_DEPARTEMEN);								
					}
					else
					{					
						$identity->APPROVAL[0] = "Asisten Manager";
						$identity->APPROVAL[1] = "Manager";
						$identity->APPROVAL_ID[0] = "'03'";
						$identity->APPROVAL_ID[1] = "'02'";			
						$identity->APPROVAL_STATEMENT[0] = array("A.CABANG_ID" => $identity->KODE_CABANG, 
																 "A.DEPARTEMEN_ID" => $identity->KODE_DEPARTEMEN, 
																 "A.SUB_DEPARTEMEN_ID" => $identity->KODE_SUB_DEPARTEMEN, 
																 "A.FUNGSI_ID" => $identity->KODE_FUNGSI);	
						$identity->APPROVAL_STATEMENT[1] = array("A.CABANG_ID" => $identity->KODE_CABANG, 
																 "A.DEPARTEMEN_ID" => $identity->KODE_DEPARTEMEN, 
																 "A.SUB_DEPARTEMEN_ID" => $identity->KODE_SUB_DEPARTEMEN);			
					}
				}
			}			
			else
			{
				if($identity->CABANG_LOKASI == "JAWA")	
				{
					/* JIKA JABATAN FOREMAN */
					if($identity->KODE_STAFF == "10" || $identity->KODE_STAFF == "09")
					{
						$identity->APPROVAL[0] = "";
						$identity->APPROVAL[1] = "Manager";
						$identity->APPROVAL_ID[0] = "''";
						$identity->APPROVAL_ID[1] = "'02', '15'";	
						$identity->APPROVAL_STATEMENT[0] = array();
						$identity->APPROVAL_STATEMENT[1] = array("A.CABANG_ID" => $identity->KODE_CABANG, 
																 "A.DEPARTEMEN_ID" => $identity->KODE_DEPARTEMEN);											
					}
					elseif($identity->KODE_STAFF == "02")
					{
						$identity->APPROVAL[0] = "";
						$identity->APPROVAL[1] = "Direktur Operasi";
						$identity->APPROVAL_ID[0] = "''";
						$identity->APPROVAL_ID[1] = "'01'";		
						$identity->APPROVAL_STATEMENT[0] = array();
						$identity->APPROVAL_STATEMENT[1] = array("A.CABANG_ID" => "KP", 
																 "A.DEPARTEMEN_ID" => "2");											
					} /* JIKA MANAGER UNIT */
					elseif($identity->KODE_STAFF == "15")
					{
						$identity->APPROVAL[0] = "";
						$identity->APPROVAL[1] = "Direktur Operasi";
						$identity->APPROVAL_ID[0] = "''";
						$identity->APPROVAL_ID[1] = "'01'";		
						$identity->APPROVAL_STATEMENT[0] = array();
						$identity->APPROVAL_STATEMENT[1] = array("A.CABANG_ID" => "KP", 
																 "A.DEPARTEMEN_ID" => "2");											
					} /* JIKA TEKNIK  */
					elseif($identity->KODE_STAFF == "11" || $identity->KODE_STAFF == "12" || $identity->KODE_STAFF == "13" || $identity->KODE_STAFF == "14")
					{
						$identity->APPROVAL[0] = "Supervisor";
						$identity->APPROVAL[1] = "Deputy Manager";
						$identity->APPROVAL_ID[0] = "'16'";
						$identity->APPROVAL_ID[1] = "'09'";	
						$identity->APPROVAL_STATEMENT[0] = array("A.CABANG_ID" => $identity->KODE_CABANG, 
																 "A.DEPARTEMEN_ID" => $identity->KODE_DEPARTEMEN, 
																 "A.SUB_DEPARTEMEN_ID" => $identity->KODE_SUB_DEPARTEMEN,
																 "A.FUNGSI_ID" => $identity->KODE_FUNGSI);		
						$identity->APPROVAL_STATEMENT[1] = array("A.CABANG_ID" => $identity->KODE_CABANG, 
																 "A.DEPARTEMEN_ID" => $identity->KODE_DEPARTEMEN,
																 "A.SUB_DEPARTEMEN_ID" => $identity->KODE_SUB_DEPARTEMEN);										
					} /* JIKA NON TEKNIK  */
					elseif($identity->KODE_STAFF == "04" || $identity->KODE_STAFF == "05" || $identity->KODE_STAFF == "17")
					{
						$identity->APPROVAL[0] = "Supervisor";
						$identity->APPROVAL[1] = "Manager Unit";
						$identity->APPROVAL_ID[0] = "'16'";
						$identity->APPROVAL_ID[1] = "'15', '02'";	
						$identity->APPROVAL_STATEMENT[0] = array("A.CABANG_ID" => $identity->KODE_CABANG, 
																 "A.DEPARTEMEN_ID" => $identity->KODE_DEPARTEMEN, 
																 "A.SUB_DEPARTEMEN_ID" => $identity->KODE_SUB_DEPARTEMEN,
																 "A.FUNGSI_ID" => $identity->KODE_FUNGSI);		
						$identity->APPROVAL_STATEMENT[1] = array("A.CABANG_ID" => $identity->KODE_CABANG, 
																 "A.DEPARTEMEN_ID" => $identity->KODE_DEPARTEMEN,
																 "A.SUB_DEPARTEMEN_ID" => $identity->KODE_SUB_DEPARTEMEN);										
					}
					elseif($identity->KODE_STAFF == "16")
					{
						$identity->APPROVAL[0] = "";
						$identity->APPROVAL[1] = "Manager Unit";
						$identity->APPROVAL_ID[0] = "''";
						$identity->APPROVAL_ID[1] = "'15', '02', '09'";	
						$identity->APPROVAL_STATEMENT[0] = array();		
						$identity->APPROVAL_STATEMENT[1] = array("A.CABANG_ID" => $identity->KODE_CABANG, 
																 "A.DEPARTEMEN_ID" => $identity->KODE_DEPARTEMEN);										
					}					
					else
					{					
						$identity->APPROVAL[0] = "Foreman";
						$identity->APPROVAL[1] = "Manager";
						$identity->APPROVAL_ID[0] = "'10'";
						$identity->APPROVAL_ID[1] = "'15', '02'";	
						$identity->APPROVAL_STATEMENT[0] = array("A.CABANG_ID" => $identity->KODE_CABANG, 
																 "A.DEPARTEMEN_ID" => $identity->KODE_DEPARTEMEN, 
																 "A.SUB_DEPARTEMEN_ID" => $identity->KODE_SUB_DEPARTEMEN,
																 "A.FUNGSI_ID" => $identity->KODE_FUNGSI);		
						$identity->APPROVAL_STATEMENT[1] = array("A.CABANG_ID" => $identity->KODE_CABANG, 
																 "A.DEPARTEMEN_ID" => $identity->KODE_DEPARTEMEN,
																 "A.SUB_DEPARTEMEN_ID" => $identity->KODE_SUB_DEPARTEMEN);								
					}
				}
				else
				{
					/* JIKA JABATAN DEPUTY MANAGER */
					if($identity->KODE_STAFF == "09")
					{
						$identity->APPROVAL[0] = "";
						$identity->APPROVAL[1] = "Manager";
						$identity->APPROVAL_ID[0] = "''";
						$identity->APPROVAL_ID[1] = "'02', '15'";			
						$identity->APPROVAL_STATEMENT[0] = array();
						$identity->APPROVAL_STATEMENT[1] = array("A.CABANG_ID" => $identity->KODE_CABANG, 
																 "A.DEPARTEMEN_ID" => $identity->KODE_DEPARTEMEN);										
					}
					elseif($identity->KODE_STAFF == "02")
					{
						$identity->APPROVAL[0] = "";
						$identity->APPROVAL[1] = "Direktur Operasi";
						$identity->APPROVAL_ID[0] = "''";
						$identity->APPROVAL_ID[1] = "'01'";		
						$identity->APPROVAL_STATEMENT[0] = array();
						$identity->APPROVAL_STATEMENT[1] = array("A.CABANG_ID" => "KP", 
																 "A.DEPARTEMEN_ID" => "2");										
					} /* JIKA MANAGER UNIT */
					elseif($identity->KODE_STAFF == "15")
					{
						$identity->APPROVAL[0] = "";
						$identity->APPROVAL[1] = "Direktur Operasi";
						$identity->APPROVAL_ID[0] = "''";
						$identity->APPROVAL_ID[1] = "'01'";		
						$identity->APPROVAL_STATEMENT[0] = array();
						$identity->APPROVAL_STATEMENT[1] = array("A.CABANG_ID" => "KP", 
																 "A.DEPARTEMEN_ID" => "2");											
					}  /* JIKA TEKNIK  */
					elseif($identity->KODE_STAFF == "11" || $identity->KODE_STAFF == "12" || $identity->KODE_STAFF == "13" || $identity->KODE_STAFF == "14")
					{
						$identity->APPROVAL[0] = "Supervisor";
						$identity->APPROVAL[1] = "Deputy Manager";
						$identity->APPROVAL_ID[0] = "'16'";
						$identity->APPROVAL_ID[1] = "'09'";	
						$identity->APPROVAL_STATEMENT[0] = array("A.CABANG_ID" => $identity->KODE_CABANG, 
																 "A.DEPARTEMEN_ID" => $identity->KODE_DEPARTEMEN, 
																 "A.SUB_DEPARTEMEN_ID" => $identity->KODE_SUB_DEPARTEMEN,
																 "A.FUNGSI_ID" => $identity->KODE_FUNGSI);		
						$identity->APPROVAL_STATEMENT[1] = array("A.CABANG_ID" => $identity->KODE_CABANG, 
																 "A.DEPARTEMEN_ID" => $identity->KODE_DEPARTEMEN,
																 "A.SUB_DEPARTEMEN_ID" => $identity->KODE_SUB_DEPARTEMEN);										
					} /* JIKA NON TEKNIK  */
					elseif($identity->KODE_STAFF == "04" || $identity->KODE_STAFF == "05" || $identity->KODE_STAFF == "17")
					{
						$identity->APPROVAL[0] = "Supervisor";
						$identity->APPROVAL[1] = "Manager Unit";
						$identity->APPROVAL_ID[0] = "'16'";
						$identity->APPROVAL_ID[1] = "'15', '02'";	
						$identity->APPROVAL_STATEMENT[0] = array("A.CABANG_ID" => $identity->KODE_CABANG, 
																 "A.DEPARTEMEN_ID" => $identity->KODE_DEPARTEMEN, 
																 "A.SUB_DEPARTEMEN_ID" => $identity->KODE_SUB_DEPARTEMEN,
																 "A.FUNGSI_ID" => $identity->KODE_FUNGSI);		
						$identity->APPROVAL_STATEMENT[1] = array("A.CABANG_ID" => $identity->KODE_CABANG, 
																 "A.DEPARTEMEN_ID" => $identity->KODE_DEPARTEMEN,
																 "A.SUB_DEPARTEMEN_ID" => $identity->KODE_SUB_DEPARTEMEN);										
					}
					elseif($identity->KODE_STAFF == "16")
					{
						$identity->APPROVAL[0] = "";
						$identity->APPROVAL[1] = "Manager Unit";
						$identity->APPROVAL_ID[0] = "''";
						$identity->APPROVAL_ID[1] = "'15', '02', '09'";	
						$identity->APPROVAL_STATEMENT[0] = array();		
						$identity->APPROVAL_STATEMENT[1] = array("A.CABANG_ID" => $identity->KODE_CABANG, 
																 "A.DEPARTEMEN_ID" => $identity->KODE_DEPARTEMEN);										
					}							
					else
					{					
						$identity->APPROVAL[0] = "Deputy Manager";
						$identity->APPROVAL[1] = "Manager";
						$identity->APPROVAL_ID[0] = "'09'";
						$identity->APPROVAL_ID[1] = "'02'";	
						$identity->APPROVAL_STATEMENT[0] = array("A.CABANG_ID" => $identity->KODE_CABANG, 
																 "A.DEPARTEMEN_ID" => $identity->KODE_DEPARTEMEN, 
																 "A.SUB_DEPARTEMEN_ID" => $identity->KODE_SUB_DEPARTEMEN,
																 "A.FUNGSI_ID" => $identity->KODE_FUNGSI);		
						$identity->APPROVAL_STATEMENT[1] = array("A.CABANG_ID" => $identity->KODE_CABANG, 
																 "A.DEPARTEMEN_ID" => $identity->KODE_DEPARTEMEN,
																 "A.SUB_DEPARTEMEN_ID" => $identity->KODE_SUB_DEPARTEMEN);								
					}											
				}				
			}
			
			if($identity->APPROVAL[0] == "")
			{
				$identity->APPROVAL1_DISPLAY = "  style=\"display:none\" ";
				$identity->APPROVAL1_REQUIRED = "";
				$identity->APPROVAL_MONITORING = array("NAMA_APPROVAL2", "STATUS_APPROVAL2");	
				$identity->APPROVAL_KOLOM_NULL = ",null,null";	
				$identity->APPROVAL_KOLOM1 = "<th colspan=\"2\" style=\"text-align:center\">Approval I</th>";	
				$identity->APPROVAL_KOLOM2 = "<th>Nama</th><th>Status</th>";	
			}
			else
			{
				$identity->APPROVAL1_DISPLAY = "";
				$identity->APPROVAL1_REQUIRED = " required ";		
				$identity->APPROVAL_MONITORING = array("NAMA_APPROVAL1", "STATUS_APPROVAL1", "NAMA_APPROVAL2", "STATUS_APPROVAL2");				
				$identity->APPROVAL_KOLOM_NULL = ",null,null,null,null";			
				$identity->APPROVAL_KOLOM1 = "<th colspan=\"2\" style=\"text-align:center\">Approval I</th><th colspan=\"2\" style=\"text-align:center\">Approval II</th>";	
				$identity->APPROVAL_KOLOM2 = "<th>Nama</th><th>Status</th><th>Nama</th><th>Status</th>";	
			}

			$CI->load->library("SettingApp"); $settingApp = new SettingApp();
			$identity->TAHUN_CUTI = $settingApp->getSetting("CUTI_TAHUNAN_TAHUN_AKTIF");
			
			$auth->getStorage()->write($identity);
			return true;
		}
        else
        {
            return false;
        }
    }

    public function getInstance(){
        return Zend_Auth::getInstance();
    }
}

?>
