<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once 'kloader.php';
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Kauth
 *
 * @author user
 */
class ApprovalInfo{
	var $approvalId1;
	var $approvalNama1;
	var $approvalId2;
	var $approvalNama2;
	
    /******************** CONSTRUCTOR **************************************/
    function ApprovalInfo(){
		 $this->emptyProps();
    }

    /******************** METHODS ************************************/
    /** Empty the properties **/
    function emptyProps(){
		$this->approvalId1 = "";
		$this->approvalNama1 = "";
		$this->approvalId2 = "";
		$this->approvalNama2 = "";
    }
		
    
    /** Verify user login. True when login is valid**/
    function getApproval($pegawaiId){			
		$CI =& get_instance();
		$CI->load->model("Pegawai");
		$CI->load->model("AbsensiRekap");
		
		$pegawai = new Pegawai();
		$pegawai->selectByParamsInformasiLogin(array("A.PEGAWAI_ID" => $pegawaiId));
		if($pegawai->firstRow())
		{
			$ID = $pegawai->getField("PEGAWAI_ID");
			$NAMA = $pegawai->getField("NAMA");
			$USERNAME = $pegawai->getField("PEGAWAI_ID");
			$KODE_CABANG = $pegawai->getField("CABANG_ID");
			$CABANG = $pegawai->getField("NAMA_CABANG");
			$CABANG_LOKASI = $pegawai->getField("LOKASI");
			$KODE_DEPARTEMEN = $pegawai->getField("DEPARTEMEN_ID");
			$DEPARTEMEN = $pegawai->getField("NAMA_DEPARTEMEN");
			$KODE_SUB_DEPARTEMEN = $pegawai->getField("SUB_DEPARTEMEN_ID");
			$SUB_DEPARTEMEN = $pegawai->getField("NAMA_SUB_DEPARTEMEN");
			$KODE_STAFF = $pegawai->getField("STAFF_ID");
			$STAFF = $pegawai->getField("NAMA_STAFF");
			$KODE_FUNGSI = $pegawai->getField("FUNGSI_ID");
			$FUNGSI = $pegawai->getField("NAMA_FUNGSI");
			$KODE_JABATAN = $pegawai->getField("JABATAN_ID");
			$JABATAN = $pegawai->getField("JABATAN");
			$STATUS_PEGAWAI = trim($pegawai->getField("STATUS_PEGAWAI"));
			$CUTI_TAHUNAN_AKTIF = $pegawai->getField("CUTI_TAHUNAN_AKTIF");
			$CUTI_BESAR_AKTIF = $pegawai->getField("CUTI_BESAR_AKTIF");
			$PM_PROYEK_AKTIF = $pegawai->getField("PM_PROYEK_AKTIF");
			$TANGGAL_MASUK = $pegawai->getField("TANGGAL_MASUK");
			$KELOMPOK = $pegawai->getField("KELOMPOK");
			$JENIS_KELAMIN = $pegawai->getField("JENIS_KELAMIN");

			if($KELOMPOK == "N")
				$kelompok_keterangan = "Non-Shift";
			else
				$kelompok_keterangan = "Shift";

			$KELOMPOK_KETERANGAN = $kelompok_keterangan;
			$ISLOGIN = 1;
			
			/* APPROVAL NEW - NOVEMBER 2017*/
			// KANTOR PUSAT
			if($CABANG_LOKASI == "KP"  )
			{

				// DIREKTUR UTAMA - EDIT KODE 'D' ANGGIT 21 NOV 2017
				if(
				$KODE_DEPARTEMEN == 'A' && //  EDIT KODE 'D' ANGGIT 21 NOV 2017
				$KODE_STAFF == "01" &&// DIRUT
				$KODE_JABATAN == "KP00000D"//DIRUT
				)
				{
					$APPROVAL[0] = "";
					$APPROVAL[1] = "Dewan Komisaris Utama";
					$APPROVAL_ID[0] = "''";
					$APPROVAL_ID[1] = "'00'";
					$APPROVAL_STATEMENT[0] = array();
					$APPROVAL_STATEMENT[1] = array("A.CABANG_ID" => $KODE_CABANG, 
																	 "A.JABATAN_ID" => "KPDK0000");	
				}

// DIREKSI SELAIN DIRUT - EDIT KODE 'D' ANGGIT 21 NOV 2017				
				elseif(
				$KODE_DEPARTEMEN != 'A' && 
				(
				$KODE_STAFF == "01" || // DIREKSI
				$KODE_STAFF == "02" || // KASAT
				$KODE_STAFF == "03"    // SEKPER
				)
				)
				{
					$APPROVAL[0] = "";
					$APPROVAL[1] = "Direktur Utama";
					$APPROVAL_ID[0] = "''";
					$APPROVAL_ID[1] = "'01'";
					$APPROVAL_STATEMENT[0] = array();
					$APPROVAL_STATEMENT[1] = array("A.CABANG_ID" => $KODE_CABANG,
																	 "A.JABATAN_ID" => "KP00000D"); 
				} // EDIT

// DIBAWAH DIRUT LANGSUNG - EDIT KODE 'D' ANGGIT 21 NOV 2017
				elseif( 
				$KODE_JABATAN == "KP00001D" || // SNR ENJ 1
				$KODE_JABATAN == "KP00002D" || // SNR OPR 1
				$KODE_JABATAN == "KP00003D" || // SNR SPC 1
				$KODE_JABATAN == "KP00004D" || // SNR OFC 1
				$KODE_JABATAN == "KP00005D" || // DIRENSAR
				$KODE_JABATAN == "KP00048D" || // DIROM
				$KODE_JABATAN == "KP00115D" || // DIRPRO
				$KODE_JABATAN == "KP00179D" || // DIRKEU
				$KODE_JABATAN == "KP00218D" || // DIRHC
				$KODE_JABATAN == "KP00314D" || // SEKPER
				$KODE_JABATAN == "KP00349D" || // KSPI
				$KODE_JABATAN == "KP00360D" || // KASAT MMK
				$KODE_JABATAN == "KP00385D" || // KASAT RISK
				$KODE_JABATAN == "KP00397D"  // KASAT SCM
				)
				{
						$APPROVAL[0] = "";
						$APPROVAL[1] = "Direktur Utama";
						$APPROVAL_ID[0] = "''";
						$APPROVAL_ID[1] = "'01'";
						$APPROVAL_STATEMENT[0] = array();
						$APPROVAL_STATEMENT[1] = array("A.CABANG_ID" => $KODE_CABANG,
																 "A.JABATAN_ID" => "KP00000D");
				} 

				else
				{

// ASMAN - EDIT KODE 'D' ANGGIT 21 NOV 2017							
					if($KODE_STAFF == "06") // ASMAN
					{
						$APPROVAL[0] = "";
						$APPROVAL[1] = "Manager";
						$APPROVAL_ID[0] = "''";
						$APPROVAL_ID[1] = "'04'";
						$APPROVAL_STATEMENT[0] = array();
						$APPROVAL_STATEMENT[1] = array("A.CABANG_ID" => $KODE_CABANG,
																 "A.DEPARTEMEN_ID" => $KODE_DEPARTEMEN,
																 "A.SUB_DEPARTEMEN_ID" => $KODE_SUB_DEPARTEMEN);
					} 

// DIBAWAH DIR PROYEK				
					elseif(
					$KODE_JABATAN == "KP00116D" || 
					$KODE_JABATAN == "KP00117D" || 
					$KODE_JABATAN == "KP00118D" ||
					$KODE_JABATAN == "KP00119D" ||
					$KODE_JABATAN == "KP00120D" ||
					$KODE_JABATAN == "KP00121D" ||
					$KODE_JABATAN == "KP00122D" ||
					$KODE_JABATAN == "KP00123D" ||
					$KODE_JABATAN == "KP00136D" ||
					$KODE_JABATAN == "KP00174D" 
					)
					{
						$APPROVAL[0] = "";
						$APPROVAL[1] = "Direktur Proyek";
						$APPROVAL_ID[0] = "''";
						$APPROVAL_ID[1] = "'01'";
						$APPROVAL_STATEMENT[0] = array();
						$APPROVAL_STATEMENT[1] = array("A.CABANG_ID" => $KODE_CABANG,
																 "A.DEPARTEMEN_ID" => $KODE_DEPARTEMEN);

					}
// DIBAWAH DIR O&M
				elseif(
					$KODE_JABATAN == "KP00049D" || 
					$KODE_JABATAN == "KP00050D" || 
					$KODE_JABATAN == "KP00051D" ||
					$KODE_JABATAN == "KP00052D" 
					)
					{
					$APPROVAL[0] = "";
					$APPROVAL[1] = "Direktur O&M";
					$APPROVAL_ID[0] = "''";
					$APPROVAL_ID[1] = "'01'";
					$APPROVAL_STATEMENT[0] = array();
					$APPROVAL_STATEMENT[1] = array("A.CABANG_ID" => "KP",
															 "A.DEPARTEMEN_ID" => "Z");
					}

// JIKA JABATAN SEKRETARIS - DINON AKTIFKAN SEJAK KODE C DI EDIT PER 09/06/2017
// KESEKERTARIATAN - EDIT KODE 'D' ANGGIT 21 NOV 2017	
					elseif(
					$KODE_JABATAN == "KP00342D" || 
					$KODE_JABATAN == "KP00343D" || 
					$KODE_JABATAN == "KP00344D" 
					)
					{
						$APPROVAL[0] = "";
						$APPROVAL[1] = "Manager";
						$APPROVAL_ID[0] = "''";
						$APPROVAL_ID[1] = "'04'";
						$APPROVAL_STATEMENT[0] = array();
						$APPROVAL_STATEMENT[1] = array("A.CABANG_ID" => $KODE_CABANG,
																 "A.DEPARTEMEN_ID" => $KODE_DEPARTEMEN,
																 "A.SUB_DEPARTEMEN_ID" => $KODE_SUB_DEPARTEMEN);

					}


// STAFF KHUSUS DIBAWAH MANAJER LANGSUNG - EDIT KODE 'D' ANGGIT 21 NOV 2017				
// BINAAN & TKARYA - PENGEMBANGAN
					elseif( 
					$KODE_JABATAN == "KP00235D" || //BINA
					$KODE_JABATAN == "KP00236D" || //BINA
					$KODE_JABATAN == "KP00237D" || //BINA
					$KODE_JABATAN == "KP00238D" || //BINA
					$KODE_JABATAN == "KP00239D" || //BINA
					$KODE_JABATAN == "KP00240D" || //BINA
					$KODE_JABATAN == "KP00241D" || //TK
					$KODE_JABATAN == "KP00242D" || //TK
					$KODE_JABATAN == "KP00243D" || //TK
					$KODE_JABATAN == "KP00244D" || //TK
					$KODE_JABATAN == "KP00245D" || //TK
					$KODE_JABATAN == "KP00246D" || //TK
					$KODE_JABATAN == "KP00247D" || //TK
					$KODE_JABATAN == "KP00248D" || //TK
					$KODE_JABATAN == "KP00264D" ||//PENGEMBANGAN
					$KODE_JABATAN == "KP00265D" ||//PENGEMBANGAN
					$KODE_JABATAN == "KP00266D" ||//PENGEMBANGAN
					$KODE_JABATAN == "KP00267D" ||//PENGEMBANGAN
					$KODE_JABATAN == "KP00268D" ||//PENGEMBANGAN
					$KODE_JABATAN == "KP00269D" ||//PENGEMBANGAN
					$KODE_JABATAN == "KP00270D" ||//PENGEMBANGAN
					$KODE_JABATAN == "KP00271D" 	//PENGEMBANGAN
					)
					{
							$APPROVAL[0] = "";
							$APPROVAL[1] = "Manager";
							$APPROVAL_ID[0] = "''";
							$APPROVAL_ID[1] = "'04'";
							$APPROVAL_STATEMENT[0] = array();
							$APPROVAL_STATEMENT[1] = array("A.CABANG_ID" => $KODE_CABANG,
																	 "A.DEPARTEMEN_ID" => $KODE_DEPARTEMEN,
																	 "A.SUB_DEPARTEMEN_ID" => $KODE_SUB_DEPARTEMEN);
					} // EDIT

// STAFF KHUSUS DIBAWAH DIREKTUR, SEKPER DAN KASAT - EDIT KODE 'D' ANGGIT 21 NOV 2017
					else 
					{

// SENIOR SPESIALIS 2, OFFICER 2, AST OFFICER, JNR OFFICER 
						if(
						(
						$KODE_STAFF == "37" || // ASSISTEN ANALIS
						$KODE_STAFF == "38" || // ASSISTEN ANALIS
						$KODE_STAFF == "35" || // SENIOR SPESIALIS 2
						$KODE_STAFF == "40" || // OFFICER 2
						$KODE_STAFF == "42" || // AST OFFICER
						$KODE_STAFF == "43" || // JNR OFFICER 
						$KODE_STAFF == "04" || // MANAGER KP
						$KODE_STAFF == "05" || // MANAGER UNIT
						$KODE_STAFF == "25"	 // KOORDINATOR UNIT
						)
						&& ($KODE_FUNGSI == "")
						)
						{
							//APPR SEKPER
							if(
							$KODE_DEPARTEMEN == 'F' ||
							$KODE_JABATAN == "KP00315D" ||
							$KODE_JABATAN == "KP00316D" ||
							$KODE_JABATAN == "KP00317D" ||
							$KODE_JABATAN == "KP00318D" ||
							$KODE_JABATAN == "KP00319D" ||
							$KODE_JABATAN == "KP00331D" 
							) {
								$APPROVAL[0] = "";
								$APPROVAL[1] = "Sekper";
								$APPROVAL_ID[0] = "''";
								$APPROVAL_ID[1] = "'03'";
								$APPROVAL_STATEMENT[0] = array();
								$APPROVAL_STATEMENT[1] = array("A.CABANG_ID" => $KODE_CABANG,
																	 "A.DEPARTEMEN_ID" => $KODE_DEPARTEMEN);
							}
							elseif( //RISK K3
							$KODE_JABATAN == "KP00390D" || // RISK K3
							$KODE_JABATAN == "KP00391D" || // RISK K3
							$KODE_JABATAN == "KP00392D" || // RISK K3
							$KODE_JABATAN == "KP00394D" || // RISK K3
							$KODE_JABATAN == "KP00395D" || // RISK K3
							$KODE_JABATAN == "KP00396D"  // RISK K3
							
							) {
								$APPROVAL[0] = "";
								$APPROVAL[1] = "Manajer";
								$APPROVAL_ID[0] = "''";
								$APPROVAL_ID[1] = "'04'";
								$APPROVAL_STATEMENT[0] = array();
								$APPROVAL_STATEMENT[1] = array("A.CABANG_ID" => $KODE_CABANG,
																	"A.DEPARTEMEN_ID" => $KODE_DEPARTEMEN,
																	"A.SUB_DEPARTEMEN_ID" => $KODE_SUB_DEPARTEMEN
																	);
								
							}
							//APPR KASAT
							elseif( 
							$KODE_DEPARTEMEN == 'AG' || // AUDIT INTERNAL
							$KODE_DEPARTEMEN == 'AE' || // PERENCANAAN KORPORAT
							$KODE_DEPARTEMEN == 'AF' || // RESIKO LK3
							$KODE_DEPARTEMEN == 'I'  || // SCM
							$KODE_JABATAN == "KP00361D" || // PERENCANAAN KORPORAT
							$KODE_JABATAN == "KP00362D" || // PERENCANAAN KORPORAT
							$KODE_JABATAN == "KP00363D" || // PERENCANAAN KORPORAT
							$KODE_JABATAN == "KP00364D" || // PERENCANAAN KORPORAT
							$KODE_JABATAN == "KP00373D" || // PERENCANAAN KORPORAT
							
							$KODE_JABATAN == "KP00350D" || // SPI
							$KODE_JABATAN == "KP00351D" || // SPI
							$KODE_JABATAN == "KP00352D" || // SPI
							$KODE_JABATAN == "KP00353D" || // SPI
							$KODE_JABATAN == "KP00354D" || // SPI
							$KODE_JABATAN == "KP00355D" || // SPI
							$KODE_JABATAN == "KP00356D" || // SPI
							$KODE_JABATAN == "KP00357D" || // SPI
							$KODE_JABATAN == "KP00358D" || // SPI
							$KODE_JABATAN == "KP00359D" || // SPI
							
							$KODE_JABATAN == "KP00389D" || // RISK K3
							$KODE_JABATAN == "KP00393D" || // RISK K3
							$KODE_JABATAN == "KP00401D" || // SCM
							$KODE_JABATAN == "KP00410D" // SCM
							) {
								$APPROVAL[0] = "";
								$APPROVAL[1] = "Kepala Satuan";
								$APPROVAL_ID[0] = "''";
								$APPROVAL_ID[1] = "'02'";
								$APPROVAL_STATEMENT[0] = array();
								$APPROVAL_STATEMENT[1] = array("A.CABANG_ID" => $KODE_CABANG,
																	 "A.DEPARTEMEN_ID" => $KODE_DEPARTEMEN);
							}
							//APPR DIREKTUR [MANAGER KP, MANAGER UNIT, KOORDINATOR]
							else
							{
								$APPROVAL[0] = "";
								$APPROVAL[1] = "Direktur";
								$APPROVAL_ID[0] = "''";
								$APPROVAL_ID[1] = "'01'";
								$APPROVAL_STATEMENT[0] = array();
								$APPROVAL_STATEMENT[1] = array("A.CABANG_ID" => $KODE_CABANG,
																	 "A.DEPARTEMEN_ID" => $KODE_DEPARTEMEN);
							}
							
						}	
						
// KONDISI NORMAL - EDIT KODE 'D' ANGGIT 21 NOV 2017
						else // STAFF - EDIT KODE 'D' ANGGIT 21 NOV 2017
						{
							if( 
							$KODE_DEPARTEMEN == 'AG' || // AUDIT INTERNAL
							$KODE_DEPARTEMEN == 'AF' || // RESIKO LK3
							$KODE_JABATAN == 'KP00175D' || // OME
							$KODE_JABATAN == 'KP00176D' || // OME
							$KODE_JABATAN == 'KP00177D' || // OME
							$KODE_JABATAN == 'KP00178D'  // OME
							)
							{
							$APPROVAL[0] = "";
							$APPROVAL[1] = "Manager";
							$APPROVAL_ID[0] = "''";
							$APPROVAL_ID[1] = "'04'";
							$APPROVAL_STATEMENT[0] = array();
							$APPROVAL_STATEMENT[1] = array("A.CABANG_ID" => $KODE_CABANG,
																	 "A.DEPARTEMEN_ID" => $KODE_DEPARTEMEN,
																	 "A.SUB_DEPARTEMEN_ID" => $KODE_SUB_DEPARTEMEN
																	 );
							}
							else{
							$APPROVAL[0] = "Asisten Manager";
							$APPROVAL[1] = "Manager";
							$APPROVAL_ID[0] = "'06'";
							$APPROVAL_ID[1] = "'04'";
							$APPROVAL_STATEMENT[0] = array("A.CABANG_ID" => $KODE_CABANG,
																	 "A.DEPARTEMEN_ID" => $KODE_DEPARTEMEN,
																	 "A.FUNGSI_ID" => $KODE_FUNGSI,
																	 "A.SUB_DEPARTEMEN_ID" => $KODE_SUB_DEPARTEMEN
																	 );
							$APPROVAL_STATEMENT[1] = array("A.CABANG_ID" => $KODE_CABANG,
																	 "A.DEPARTEMEN_ID" => $KODE_DEPARTEMEN,
																	 "A.SUB_DEPARTEMEN_ID" => $KODE_SUB_DEPARTEMEN
																	 );
							}
						}//EDIT
					}
				}
			}
			
			////------------ KODE DIBAWAH MASIH KODE LAMA BELUM SEMUA
				
			// proyek
			elseif($CABANG_LOKASI == "PR")
			{
				if ($KODE_CABANG == "BE") //BELAWAN SUDAH DI EDIT
				{
					if($KODE_JABATAN == "KP00039D")
					{
						$APPROVAL[0] = "Asman Niaga Proyek";
						$APPROVAL[1] = "Manager Niaga Proyek";
						$APPROVAL_ID[0] = "'06'";
						$APPROVAL_ID[1] = "'04'";
						$APPROVAL_STATEMENT[0] = array("A.JABATAN_ID" => "KP00033D");
						$APPROVAL_STATEMENT[1] = array("A.JABATAN_ID" => "KP00032D");
					}
				}
				elseif ($KODE_CABANG == "MK") // belum UJP DIHILANGKAN
				{
					if($KODE_STAFF == "08")
					{
						$APPROVAL[0] = "";
						$APPROVAL[1] = "Manager";
						$APPROVAL_ID[0] = "''";
						$APPROVAL_ID[1] = "'04'";
						$APPROVAL_STATEMENT[0] = array();
						$APPROVAL_STATEMENT[1] = array("A.JABATAN_ID" => "UJ00080B");
					}
					else
					{
						$APPROVAL[0] = "Supervisior";
						$APPROVAL[1] = "Manager";
						$APPROVAL_ID[0] = "'08'";
						$APPROVAL_ID[1] = "'04'";
						$APPROVAL_STATEMENT[0] = array("A.CABANG_ID" => $KODE_CABANG,
																 "A.DEPARTEMEN_ID" => $KODE_DEPARTEMEN);
						//$APPROVAL_STATEMENT[1] = array("A.JABATAN_ID" => "UJ00080B");
						$APPROVAL_STATEMENT[1] = array("A.CABANG_ID" => $KODE_CABANG,
															 "A.DEPARTEMEN_ID" => $KODE_DEPARTEMEN);
					}
				}
					
				else if($KODE_STAFF == "24") // GM SUDAH
				{
					$APPROVAL[0] = "";
					$APPROVAL[1] = "Direktur Proyek";
					$APPROVAL_ID[0] = "''";
					$APPROVAL_ID[1] = "'01'";
					$APPROVAL_STATEMENT[0] = array();
					//$APPROVAL_STATEMENT[1] = array("A.CABANG_ID" => "KP", "A.DEPARTEMEN_ID" => "C");
					$APPROVAL_STATEMENT[1] = array("A.JABATAN_ID" => "KP00115D");
				}
				else if // BELUM  UJP DIHILANGKAN
				(
					$KODE_STAFF == "04" ||
					($KODE_STAFF == "16" && $KODE_JABATAN == "UJ00001C") ||
					($KODE_STAFF == "17" && $KODE_JABATAN == "UJ00002C") ||
					($KODE_STAFF == "30" && $KODE_JABATAN == "UJ00003C") ||
					($KODE_STAFF == "31" && $KODE_JABATAN == "UJ00004C") ||
					//$KODE_STAFF == "21" || NVN
					($KODE_STAFF == "43" && $KODE_JABATAN == "UJ00005C") ||
					($KODE_STAFF == "37" && $KODE_JABATAN == "UJ00007C") ||
					($KODE_STAFF == "11" && $KODE_JABATAN == "UJ00006C") 
				)
				{
					$APPROVAL[0] = "";
					$APPROVAL[1] = "General Manager";
					$APPROVAL_ID[0] = "''";
					$APPROVAL_ID[1] = "'24'";
					$APPROVAL_STATEMENT[0] = array();
					$APPROVAL_STATEMENT[1] = array("A.CABANG_ID" => $KODE_CABANG,
															 "A.DEPARTEMEN_ID" => $KODE_DEPARTEMEN);
				}
				else if
				(
					$KODE_STAFF == "07" || // BELUM  UJP DIHILANGKAN
					$KODE_STAFF == "16" ||
					($KODE_STAFF == "17" && $KODE_JABATAN == "UJ00010C") ||
					($KODE_STAFF == "27" && $KODE_JABATAN == "UJ00011C")
				)
				{
					$APPROVAL[0] = "Manager";
					$APPROVAL[1] = "General Manager";
					$APPROVAL_ID[0] = "'04'";
					$APPROVAL_ID[1] = "'24'";
					$APPROVAL_STATEMENT[0] = array("A.CABANG_ID" => $KODE_CABANG,
															 "A.DEPARTEMEN_ID" => $KODE_DEPARTEMEN,
															 "A.SUB_DEPARTEMEN_ID" => $KODE_SUB_DEPARTEMEN);
					$APPROVAL_STATEMENT[1] = array("A.CABANG_ID" => $KODE_CABANG,
															 "A.DEPARTEMEN_ID" => $KODE_DEPARTEMEN);
				}
				else if
				($KODE_STAFF == "28" && $KODE_JABATAN == "UJ00049C") // BELUM  UJP DIHILANGKAN
				{
					$APPROVAL[0] = "";
					$APPROVAL[1] = "Manager";
					$APPROVAL_ID[0] = "''";
					$APPROVAL_ID[1] = "'04'";
					$APPROVAL_STATEMENT[0] = array();
					$APPROVAL_STATEMENT[1] = array("A.JABATAN_ID" => "UJ00008C");
				}
				else
				{
					$APPROVAL[0] = "DM";
					$APPROVAL[1] = "Manager";
					//$APPROVAL_ID[0] = "'08', '06'";
					$APPROVAL_ID[0] = "'07'";
					$APPROVAL_ID[1] = "'04'";
					$APPROVAL_STATEMENT[0] = array("A.CABANG_ID" => $KODE_CABANG,
															 "A.DEPARTEMEN_ID" => $KODE_DEPARTEMEN,
															 "A.SUB_DEPARTEMEN_ID" => $KODE_SUB_DEPARTEMEN,
															 "A.FUNGSI_ID" => $KODE_FUNGSI);
					$APPROVAL_STATEMENT[1] = array("A.CABANG_ID" => $KODE_CABANG,
															 "A.DEPARTEMEN_ID" => $KODE_DEPARTEMEN,
															 "A.SUB_DEPARTEMEN_ID" => $KODE_SUB_DEPARTEMEN);
				}
			}
			
			
			// unit kecil
			elseif($CABANG_LOKASI == "UK")
			{
				if($KODE_STAFF == "05") // manager unit bawean
				{
					//$APPROVAL[0] = "";
					$APPROVAL[1] = "Direktur O&M";
					//$APPROVAL_ID[0] = "''";
					$APPROVAL_ID[1] = "'01'";
					//$APPROVAL_STATEMENT[0] = array();
					$APPROVAL_STATEMENT[1] = array("A.CABANG_ID" => "KP",
															 "A.DEPARTEMEN_ID" => "Z");
				}
				else
				{
					$APPROVAL[0] = "";
					$APPROVAL[1] = "Manager Unit";
					$APPROVAL_ID[0] = "''";
					$APPROVAL_ID[1] = "'05'";
					$APPROVAL_STATEMENT[0] = array();
					$APPROVAL_STATEMENT[1] = array("A.CABANG_ID" => $KODE_CABANG,
															 "A.DEPARTEMEN_ID" => $KODE_DEPARTEMEN);
				}
			}
			// om & ls
			else
			{
				
				// unit luar jawa labor supplay
				if($CABANG_LOKASI == "LS")
				{
					if ($KODE_CABANG == "CNG") //CNG
					{
						if($KODE_STAFF == "33" ||
						$KODE_STAFF == "28")
						{
							$APPROVAL[0] = "Supervisior";
							$APPROVAL[1] = "Manager Unit";
							$APPROVAL_ID[0] = "'08'";
							$APPROVAL_ID[1] = "'04'";
							$APPROVAL_STATEMENT[0] = array("A.CABANG_ID" => $KODE_CABANG,
																 "A.DEPARTEMEN_ID" => $KODE_DEPARTEMEN,
																 "A.SUB_DEPARTEMEN_ID" => $KODE_SUB_DEPARTEMEN,
																	 "A.FUNGSI_ID" => $KODE_FUNGSI);
						$APPROVAL_STATEMENT[1] = array("A.CABANG_ID" => $KODE_CABANG,
																 "A.DEPARTEMEN_ID" => $KODE_DEPARTEMEN,
																 "A.SUB_DEPARTEMEN_ID" => $KODE_SUB_DEPARTEMEN);
						}

						elseif
						(
							$KODE_STAFF == "42" ||
						$KODE_STAFF == "43" ||
						$KODE_STAFF == "37" ||
						$KODE_STAFF == "38"
						)
						{
							$APPROVAL[0] = "Supervisor";
							$APPROVAL[1] = "Manager";
							$APPROVAL_ID[0] = "'08'";
							$APPROVAL_ID[1] = "'04'";
							$APPROVAL_STATEMENT[0] = array("A.CABANG_ID" => $KODE_CABANG,
																	 "A.DEPARTEMEN_ID" => $KODE_DEPARTEMEN,
																	 "A.SUB_DEPARTEMEN_ID" => $KODE_SUB_DEPARTEMEN,
																	 "A.FUNGSI_ID" => $KODE_FUNGSI);
							$APPROVAL_STATEMENT[1] = array("A.CABANG_ID" => $KODE_CABANG,
																	 "A.DEPARTEMEN_ID" => $KODE_DEPARTEMEN,
																	 "A.SUB_DEPARTEMEN_ID" => $KODE_SUB_DEPARTEMEN);
						}

					}
					
					/* JIKA JABATAN */
					elseif( //admin unit
					substr($KODE_JABATAN, -6) == "00001D" ||
					substr($KODE_JABATAN, -6) == "00002D" 
					//substr($KODE_JABATAN, -6) == "00003B"
					)
					{
						$APPROVAL[0] = "";
						$APPROVAL[1] = "Koordinator";
						$APPROVAL_ID[0] = "''";
						$APPROVAL_ID[1] = "'25'";	 //koordinator UNIT
						$APPROVAL_STATEMENT[0] = array();
						$APPROVAL_STATEMENT[1] = array("A.CABANG_ID" => $KODE_CABANG,
																 "A.DEPARTEMEN_ID" => $KODE_DEPARTEMEN);
					} //EDIT

					/* JIKA JABATAN FOREMAN */
					else if(
					$KODE_STAFF == "07"	// DEPUTY MANAGER
					)
					{
						$APPROVAL[0] = "";
						$APPROVAL[1] = "Manager"; //unit
						$APPROVAL_ID[0] = "''";
						$APPROVAL_ID[1] = "'05', '04'";	 //MANAGER UNIT
						$APPROVAL_STATEMENT[0] = array();
						$APPROVAL_STATEMENT[1] = array("A.CABANG_ID" => $KODE_CABANG,
																 "A.DEPARTEMEN_ID" => $KODE_DEPARTEMEN);
					} //EDIT

					 /* JIKA MANAGER UNIT */
					elseif($KODE_STAFF == "05")
					{
						$APPROVAL[0] = "";
						$APPROVAL[1] = "Direktur O&M";
						$APPROVAL_ID[0] = "''";
						$APPROVAL_ID[1] = "'01'";
						$APPROVAL_STATEMENT[0] = array();
						$APPROVAL_STATEMENT[1] = array("A.CABANG_ID" => "KP",
																 "A.DEPARTEMEN_ID" => "Z");
					} //EDIT


					/* JIKA TEKNIK  */
					elseif( // belum
					$KODE_STAFF == "18" || // TEKNIK
					$KODE_STAFF == "19" ||
					$KODE_STAFF == "20" ||
					$KODE_STAFF == "21" ||
					$KODE_STAFF == "22" ||
					$KODE_STAFF == "23" ||
					
					$KODE_STAFF == "12" || // OFFICER
					$KODE_STAFF == "13" ||
					
					$KODE_STAFF == "14"
					)
					{
						$APPROVAL[0] = "Supervisor";
						$APPROVAL[1] = "Manager";
						$APPROVAL_ID[0] = "'08'";
						$APPROVAL_ID[1] = "'04'";
						$APPROVAL_STATEMENT[0] = array("A.CABANG_ID" => $KODE_CABANG,
																 "A.DEPARTEMEN_ID" => $KODE_DEPARTEMEN,
																 "A.SUB_DEPARTEMEN_ID" => $KODE_SUB_DEPARTEMEN,
																 "A.FUNGSI_ID" => $KODE_FUNGSI);
						$APPROVAL_STATEMENT[1] = array("A.CABANG_ID" => $KODE_CABANG,
																 "A.DEPARTEMEN_ID" => $KODE_DEPARTEMEN,
																 "A.SUB_DEPARTEMEN_ID" => $KODE_SUB_DEPARTEMEN);
					} //EDIT


					/* JIKA NON TEKNIK  */
					elseif(
					$KODE_STAFF == "42" ||
					$KODE_STAFF == "43" ||
					$KODE_STAFF == "37" ||
					$KODE_STAFF == "38"
					)
					{
						$APPROVAL[0] = "Supervisor";
						$APPROVAL[1] = "Manager";
						$APPROVAL_ID[0] = "'08'";
						$APPROVAL_ID[1] = "'04'";
						$APPROVAL_STATEMENT[0] = array("A.CABANG_ID" => $KODE_CABANG,
																 "A.DEPARTEMEN_ID" => $KODE_DEPARTEMEN,
																 "A.SUB_DEPARTEMEN_ID" => $KODE_SUB_DEPARTEMEN,
																 "A.FUNGSI_ID" => $KODE_FUNGSI);
						$APPROVAL_STATEMENT[1] = array("A.CABANG_ID" => $KODE_CABANG,
																 "A.DEPARTEMEN_ID" => $KODE_DEPARTEMEN,
																 "A.SUB_DEPARTEMEN_ID" => $KODE_SUB_DEPARTEMEN);
					}//EDIT


					/* JIKA NON TEKNIK  */

					elseif(
					$KODE_STAFF == "08" //SUPERVISOR
					)
					{
						$APPROVAL[0] = "";
						$APPROVAL[1] = "Manager";
						$APPROVAL_ID[0] = "''";
						$APPROVAL_ID[1] = "'05','07','04'"; //MANAGER UNIT ATAU DEPUTY MANAGER
						$APPROVAL_STATEMENT[0] = array();
						$APPROVAL_STATEMENT[1] = array("A.CABANG_ID" => $KODE_CABANG,
																 "A.DEPARTEMEN_ID" => $KODE_DEPARTEMEN,
																 "A.SUB_DEPARTEMEN_ID" => $KODE_SUB_DEPARTEMEN);
					}//EDIT

					else
					{
						$APPROVAL[0] = "Supervisor";
						$APPROVAL[1] = "Manager";
						$APPROVAL_ID[0] = "'08'";
						$APPROVAL_ID[1] = "'05', '04'";
						$APPROVAL_STATEMENT[0] = array("A.CABANG_ID" => $KODE_CABANG,
																 "A.DEPARTEMEN_ID" => $KODE_DEPARTEMEN,
																 "A.SUB_DEPARTEMEN_ID" => $KODE_SUB_DEPARTEMEN,
																 "A.FUNGSI_ID" => $KODE_FUNGSI);
						$APPROVAL_STATEMENT[1] = array("A.CABANG_ID" => $KODE_CABANG,
																 "A.DEPARTEMEN_ID" => $KODE_DEPARTEMEN,
																 "A.SUB_DEPARTEMEN_ID" => $KODE_SUB_DEPARTEMEN);
					}//EDIT


				}
				
//unit luar jawa
				else if($CABANG_LOKASI == "OM") // LUAR JAWA
				{
					if ($KODE_CABANG == "DR") //duri
					{
						if($KODE_STAFF == "05")//manager unit
						{
							$APPROVAL[0] = "";
							$APPROVAL[1] = "Direktur O&M";
							$APPROVAL_ID[0] = "''";
							$APPROVAL_ID[1] = "'01'";
							$APPROVAL_STATEMENT[0] = array();
							$APPROVAL_STATEMENT[1] = array("A.CABANG_ID" => "KP",
																	 "A.DEPARTEMEN_ID" => "Z");
						}
						else if(
						$KODE_JABATAN == "DR00001D" ||
						$KODE_JABATAN == "DR00002D" ||
						$KODE_STAFF == "08")
						{
							$APPROVAL[0] = "";
							$APPROVAL[1] = "Manager Unit";
							$APPROVAL_ID[0] = "''";
							$APPROVAL_ID[1] = "'05'";
							$APPROVAL_STATEMENT[0] = array();
							$APPROVAL_STATEMENT[1] = array("A.CABANG_ID" => $KODE_CABANG,
																	 "A.DEPARTEMEN_ID" => $KODE_DEPARTEMEN);
						}
						else// staff biasa
						{
							$APPROVAL[0] = "Supervisor";
							$APPROVAL[1] = "Manager Unit";
							$APPROVAL_ID[0] = "'08'";
							$APPROVAL_ID[1] = "'05'";
							$APPROVAL_STATEMENT[0] = array("A.CABANG_ID" => $KODE_CABANG,
																	 "A.DEPARTEMEN_ID" => $KODE_DEPARTEMEN,
																	 "A.SUB_DEPARTEMEN_ID" => $KODE_SUB_DEPARTEMEN);
							$APPROVAL_STATEMENT[1] = array("A.CABANG_ID" => $KODE_CABANG,
																	 "A.DEPARTEMEN_ID" => $KODE_DEPARTEMEN);
						}
						/*
						else if(
						$KODE_STAFF == "18" ||
						$KODE_STAFF == "20" ||
						$KODE_STAFF == "22" ||
						$KODE_STAFF == "12" ||
						$KODE_STAFF == "13" ||
						$KODE_STAFF == "14" ||
						$KODE_STAFF == "19" ||
						$KODE_STAFF == "21" ||
						$KODE_STAFF == "23"
						)
						{
							$APPROVAL[0] = "Supervisor";
							$APPROVAL[1] = "Manager Unit";
							$APPROVAL_ID[0] = "'08'";
							$APPROVAL_ID[1] = "'05'";
							$APPROVAL_STATEMENT[0] = array("A.CABANG_ID" => $KODE_CABANG,
																	 "A.DEPARTEMEN_ID" => $KODE_DEPARTEMEN,
																	 "A.SUB_DEPARTEMEN_ID" => $KODE_SUB_DEPARTEMEN);
							$APPROVAL_STATEMENT[1] = array("A.CABANG_ID" => $KODE_CABANG,
																	 "A.DEPARTEMEN_ID" => $KODE_DEPARTEMEN);
						}
						*/
					}
					// bukan DURI
					/* JIKA JABATAN DEPUTY MANAGER */
					else if(
					$KODE_STAFF == "07" 
					//||
					//$KODE_STAFF == "08" // tambahan
					)
					{
						$APPROVAL[0] = "";
						$APPROVAL[1] = "Manager";
						$APPROVAL_ID[0] = "''";
						$APPROVAL_ID[1] = "'05'";	//manager unit
						$APPROVAL_STATEMENT[0] = array();
						$APPROVAL_STATEMENT[1] = array("A.CABANG_ID" => $KODE_CABANG,
																 "A.DEPARTEMEN_ID" => $KODE_DEPARTEMEN);
					} //EDIT


/* JIKA MANAGER UNIT */
					elseif($KODE_STAFF == "05")
					{
						$APPROVAL[0] = "";
						$APPROVAL[1] = "Direktur O&M";
						$APPROVAL_ID[0] = "''";
						$APPROVAL_ID[1] = "'01'";
						$APPROVAL_STATEMENT[0] = array();
						$APPROVAL_STATEMENT[1] = array("A.CABANG_ID" => "KP",
																 "A.DEPARTEMEN_ID" => "Z");
					} //EDIT


					/* JIKA NON TEKNIK DAN ADMINISTRASI */
					elseif(
					(
					$KODE_STAFF == "42" ||
					$KODE_STAFF == "43" ||
					$KODE_STAFF == "37" || 
					$KODE_STAFF == "38"
					)
					&&
					(
					$KODE_SUB_DEPARTEMEN == "J3" || //
					$KODE_SUB_DEPARTEMEN == "K3" || //
					$KODE_SUB_DEPARTEMEN == "K4" || //
					$KODE_SUB_DEPARTEMEN == "Q4" ||
					$KODE_SUB_DEPARTEMEN == "T3" ||
					$KODE_SUB_DEPARTEMEN == "X3" 
					) // SUPERVISOR SDM ADM & KEU
					)
					{
						$APPROVAL[0] = "Supervisor";
						$APPROVAL[1] = "Manager Unit";
						$APPROVAL_ID[0] = "'08'";
						$APPROVAL_ID[1] = "'05'";
						$APPROVAL_STATEMENT[0] = array("A.CABANG_ID" => $KODE_CABANG,
																 "A.DEPARTEMEN_ID" => $KODE_DEPARTEMEN,
																 "A.SUB_DEPARTEMEN_ID" => $KODE_SUB_DEPARTEMEN);
						$APPROVAL_STATEMENT[1] = array("A.CABANG_ID" => $KODE_CABANG,
																 "A.DEPARTEMEN_ID" => $KODE_DEPARTEMEN);
					} //EDIT
					
					 /* JIKA STAFF SELAIN SDM DAN ADM*/
					elseif(
					(
					$KODE_STAFF == "27" || //ass enginer
					$KODE_STAFF == "28" || //jun enginer
					$KODE_STAFF == "37" || //ass analis
					$KODE_STAFF == "38" || //jun analis
					$KODE_STAFF == "32" || //ass opr 
					$KODE_STAFF == "33"  //jun opr
				
					) && $KODE_FUNGSI != ""
					)
					{
						$APPROVAL[0] = "Supervisor";
						$APPROVAL[1] = "Deputy Manager";
						$APPROVAL_ID[0] = "'08'";
						$APPROVAL_ID[1] = "'07'";
						$APPROVAL_STATEMENT[0] = array("A.CABANG_ID" => $KODE_CABANG,
																 "A.DEPARTEMEN_ID" => $KODE_DEPARTEMEN,
																 "A.SUB_DEPARTEMEN_ID" => $KODE_SUB_DEPARTEMEN,
																 "A.FUNGSI_ID" => $KODE_FUNGSI);
						$APPROVAL_STATEMENT[1] = array("A.CABANG_ID" => $KODE_CABANG,
																 "A.DEPARTEMEN_ID" => $KODE_DEPARTEMEN,
																 "A.SUB_DEPARTEMEN_ID" => $KODE_SUB_DEPARTEMEN);
					}//EDIT
					
					

					//asahan
					elseif(//ANGGIT ASAHAN
					(
					//$KODE_STAFF == "37" ||
					$KODE_STAFF == "38" ||
					//$KODE_STAFF == "42" ||
					$KODE_STAFF == "43" 
					)
					 &&
					//(
					//$KODE_SUB_DEPARTEMEN == "J3" ||
					//$KODE_SUB_DEPARTEMEN == "K3" ||
					//$KODE_SUB_DEPARTEMEN == "K4" ||
					//$KODE_SUB_DEPARTEMEN == "X3"
					//) && // SUPERVISOR SDM ADM & KEU)
					$KODE_CABANG == "AS"
					)
					{
						$APPROVAL[0] = "";
						$APPROVAL[1] = "Manager Unit";
						$APPROVAL_ID[0] = "''";
						$APPROVAL_ID[1] = "'05'";
						$APPROVAL_STATEMENT[0] = array();
						$APPROVAL_STATEMENT[1] = array("A.CABANG_ID" => $KODE_CABANG,
																 "A.DEPARTEMEN_ID" => $KODE_DEPARTEMEN);
					} //ANGGIT MODIF ASAHAN


					elseif(
					$KODE_STAFF == "08"  &&// SUPERVISOR
					(
					$KODE_SUB_DEPARTEMEN == "J3" || //
					$KODE_SUB_DEPARTEMEN == "K3" || //
					$KODE_SUB_DEPARTEMEN == "K4" || //
					$KODE_SUB_DEPARTEMEN == "Q4" ||
					$KODE_SUB_DEPARTEMEN == "T3" ||
					$KODE_SUB_DEPARTEMEN == "X3" 
					)
					)
					{
						$APPROVAL[0] = "";
						$APPROVAL[1] = "Manager Unit";
						$APPROVAL_ID[0] = "''";
						$APPROVAL_ID[1] = "'05'";
						$APPROVAL_STATEMENT[0] = array();
						$APPROVAL_STATEMENT[1] = array("A.CABANG_ID" => $KODE_CABANG,
																 "A.DEPARTEMEN_ID" => $KODE_DEPARTEMEN);
					}	//EDIT


//SELAIN SUPERVISOR ADMINISTRASI & KEUANGAN UNIT
					elseif($KODE_STAFF == "08")
					{
						$APPROVAL[0] = "Deputy Manager";
						$APPROVAL[1] = "Manager Unit";
						$APPROVAL_ID[0] = "'07'";
						$APPROVAL_ID[1] = "'05'";
						$APPROVAL_STATEMENT[0] = array("A.CABANG_ID" => $KODE_CABANG,
																 "A.DEPARTEMEN_ID" => $KODE_DEPARTEMEN,
																 "A.SUB_DEPARTEMEN_ID" => $KODE_SUB_DEPARTEMEN);
						$APPROVAL_STATEMENT[1] = array("A.CABANG_ID" => $KODE_CABANG,
																 "A.DEPARTEMEN_ID" => $KODE_DEPARTEMEN);
					} //EDIT

					else
					{
						$APPROVAL[0] = "Deputy Manager";
						$APPROVAL[1] = "Manager";
						$APPROVAL_ID[0] = "'07'";
						$APPROVAL_ID[1] = "'05'";
						$APPROVAL_STATEMENT[0] = array("A.CABANG_ID" => $KODE_CABANG,
																 "A.DEPARTEMEN_ID" => $KODE_DEPARTEMEN,
																 "A.SUB_DEPARTEMEN_ID" => $KODE_SUB_DEPARTEMEN,
																 "A.FUNGSI_ID" => $KODE_FUNGSI);
						$APPROVAL_STATEMENT[1] = array("A.CABANG_ID" => $KODE_CABANG,
																 "A.DEPARTEMEN_ID" => $KODE_DEPARTEMEN,
																 "A.SUB_DEPARTEMEN_ID" => $KODE_SUB_DEPARTEMEN);
					}
				}
			}

			if($APPROVAL[0] == "")
			{
				$APPROVAL1_DISPLAY = "  style=\"display:none\" ";
				$APPROVAL1_REQUIRED = "";
				$APPROVAL_MONITORING = array("NAMA_APPROVAL2", "STATUS_APPROVAL2");
				$APPROVAL_KOLOM_NULL = ",null,null";
				$APPROVAL_KOLOM1 = "<th colspan=\"2\" style=\"text-align:center\">Approval I</th>";
				$APPROVAL_KOLOM2 = "<th>Nama</th><th>Status</th>";

				if($APPROVAL_ID[1] == "")
					$APPROVAL_ID[1] = "'X'";
				else
					$APPROVAL_ID[1] = $APPROVAL_ID[1];

				if($APPROVAL_STATEMENT[1] == "")
					$APPROVAL_STATEMENT[1] = array();
				else
					$APPROVAL_STATEMENT[1] = $APPROVAL_STATEMENT[1];

				$pegawai_approval = new Pegawai();
				$statement = " AND A.STAFF_ID IN (".$APPROVAL_ID[1].") ";
				$pegawai_approval->selectByParamsVerifikasi($APPROVAL_STATEMENT[1], -1, -1, $statement);
				// $pegawai_approval->query;
				$pegawai_approval->firstRow();
				$this->approvalId1 = "";
				$this->approvalNama1 = "TIDAK PERLU";
				$this->approvalId2 = $pegawai_approval->getField("PEGAWAI_ID");
				$this->approvalNama2 = $pegawai_approval->getField("NAMA");								


			}
			else
			{
				$APPROVAL1_DISPLAY = "";
				$APPROVAL1_REQUIRED = " required ";
				$APPROVAL_MONITORING = array("NAMA_APPROVAL1", "STATUS_APPROVAL1", "NAMA_APPROVAL2", "STATUS_APPROVAL2");
				$APPROVAL_KOLOM_NULL = ",null,null,null,null";
				$APPROVAL_KOLOM1 = "<th colspan=\"2\" style=\"text-align:center\">Approval I</th><th colspan=\"2\" style=\"text-align:center\">Approval II</th>";
				$APPROVAL_KOLOM2 = "<th>Nama</th><th>Status</th><th>Nama</th><th>Status</th>";


				if($APPROVAL_ID[0] == "")
					$APPROVAL_ID[0] = "'X'";
				else
					$APPROVAL_ID[0] = $APPROVAL_ID[0];


				if($APPROVAL_STATEMENT[0] == "")
					$APPROVAL_STATEMENT[0] = array();
				else
					$APPROVAL_STATEMENT[0] = $APPROVAL_STATEMENT[0];


				if($APPROVAL_ID[1] == "")
					$APPROVAL_ID[1] = "'X'";
				else
					$APPROVAL_ID[1] = $APPROVAL_ID[1];

				
				if($APPROVAL_STATEMENT[1] == "")
					$APPROVAL_STATEMENT[1] = array();
				else
					$APPROVAL_STATEMENT[1] = $APPROVAL_STATEMENT[1];

				$pegawai_approval0 = new Pegawai();
				$statement0 = " AND A.STAFF_ID IN (".$APPROVAL_ID[0].") ";
				$pegawai_approval0->selectByParamsVerifikasi($APPROVAL_STATEMENT[0], -1, -1, $statement0);
				$pegawai_approval0->firstRow();
				$pegawai_approval1 = new Pegawai();
				$statement1 = " AND A.STAFF_ID IN (".$APPROVAL_ID[1].") ";
				$pegawai_approval1->selectByParamsVerifikasi($APPROVAL_STATEMENT[1], -1, -1, $statement1);
				$pegawai_approval1->firstRow();


				$this->approvalId1 = $pegawai_approval0->getField("PEGAWAI_ID");		
				$this->approvalNama1 = $pegawai_approval0->getField("NAMA");		
				$this->approvalId2 = $pegawai_approval1->getField("PEGAWAI_ID");
				$this->approvalNama2 = $pegawai_approval1->getField("NAMA");								


			}

		}
		
    }
			   
}
	
  /***** INSTANTIATE THE GLOBAL OBJECT */
  $approvalInfo = new ApprovalInfo();

?>
