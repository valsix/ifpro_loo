<?php
defined('BASEPATH') or exit('No direct script access allowed');

include_once("functions/default.func.php");
include_once("functions/string.func.php");
include_once("functions/date.func.php");
// include_once("lib/excel/excel_reader2.php");

class jabatan_struktural_add_json extends CI_Controller
{

	function __construct()
	{
		parent::__construct();

		if (!$this->kauth->getInstance()->hasIdentity()) {
			//redirect('login');
		}

		$this->db->query("SET DATESTYLE TO PostgreSQL,European;");
		$this->ID				= $this->kauth->getInstance()->getIdentity()->ID;
		$this->NAMA				= $this->kauth->getInstance()->getIdentity()->NAMA;
		$this->JABATAN			= $this->kauth->getInstance()->getIdentity()->JABATAN;
		$this->HAK_AKSES		= $this->kauth->getInstance()->getIdentity()->HAK_AKSES;
		$this->LAST_LOGIN		= $this->kauth->getInstance()->getIdentity()->LAST_LOGIN;
		$this->USERNAME			= $this->kauth->getInstance()->getIdentity()->USERNAME;
		$this->USER_LOGIN_ID	= $this->kauth->getInstance()->getIdentity()->USER_LOGIN_ID;
		$this->USER_GROUP		= $this->kauth->getInstance()->getIdentity()->USER_GROUP;
		$this->CABANG_ID		= $this->kauth->getInstance()->getIdentity()->CABANG_ID;
		$this->CABANG		= $this->kauth->getInstance()->getIdentity()->CABANG;
		$this->HIRARKI		= $this->kauth->getInstance()->getIdentity()->HIRARKI;
		$this->KD_LEVEL		= $this->kauth->getInstance()->getIdentity()->KD_LEVEL;
		$this->KELOMPOK_JABATAN		= $this->kauth->getInstance()->getIdentity()->KELOMPOK_JABATAN;
	}
	
	function add()
	{
		// echo "Data berhasil disimpan.";exit;
		$this->load->model("SatuanKerja");
		$satuan_kerja = new SatuanKerja();

		$reqMode= $this->input->post("reqMode");
		$reqId= $this->input->post("reqId");
		$reqParentId= $this->input->post("reqParentId");

		$reqUnitKerja= $this->input->post("reqUnitKerja");
		$reqJabatan= $this->input->post("reqJabatan");
		$reqKelompokJabatan= $this->input->post("reqKelompokJabatan");
		$reqKodeSurat= $this->input->post("reqKodeSurat");
		$reqNamaPegawai= $this->input->post("reqNamaPegawai");
		$reqNipPegawai= $this->input->post("reqNipPegawai");
		$reqUserBantu= $this->input->post("reqUserBantu");
		$reqStatusAktif= $this->input->post("reqStatusAktif");
		$reqUnitKerjaId= $this->input->post("reqUnitKerjaId");
		$reqApprovalSttpd= $this->input->post("reqApprovalSttpd");

		$satuan_kerja->setField("SATUAN_KERJA_ID", $reqId);
		$satuan_kerja->setField("SATUAN_KERJA_ID_PARENT", $reqParentId);
		$satuan_kerja->setField("NAMA", setQuote($reqUnitKerja));
		$satuan_kerja->setField("JABATAN", setQuote($reqJabatan));
		$satuan_kerja->setField("KODE_SURAT", $reqKodeSurat);
		$satuan_kerja->setField("NIP", $reqNipPegawai);
		$satuan_kerja->setField("NAMA_PEGAWAI", setQuote($reqNamaPegawai));
		$satuan_kerja->setField("KELOMPOK_JABATAN", $reqKelompokJabatan);
		$satuan_kerja->setField("USER_BANTU", $reqUserBantu);
		$satuan_kerja->setField("STATUS_AKTIF", $reqStatusAktif);
		$satuan_kerja->setField("APPROVAL_STTPD", $reqApprovalSttpd);
		$satuan_kerja->setField("LOKASI", $reqUnitKerjaId);

		$reqSimpan ="";
		if ($reqMode=='insert') 
		{
			$satuan_kerja->setField("LAST_CREATE_USER", $this->USERNAME);
			if($satuan_kerja->insertJabatanStruktural())
			{
				$reqId= $satuan_kerja->id;
				$reqSimpan = 1;
			}
			// echo $jenis_naskah->query;exit;
		} 
		else 
		{
			$satuan_kerja->setField("LAST_UPDATE_USER", $this->USERNAME);
			if($satuan_kerja->updateJabatanStruktural())
			{
				$reqSimpan = 1;
			}
			// echo $jenis_naskah->query;exit;
		}

		if ($reqSimpan = 1)
		{
			echo $reqId."-Data berhasil disimpan.";
		}
		else
		{
			echo "xxx-Data gagal disimpan.";
		}
	}

	function delete()
	{
		$reqId	= $this->input->get('reqId');
		$this->load->model("SatuanKerja");
		$jenis_naskah = new SatuanKerja();


		$jenis_naskah->setField("JENIS_NASKAH_ID", $reqId);
		if ($jenis_naskah->delete())
			$arrJson["PESAN"] = "Data berhasil dihapus.";
		else
			$arrJson["PESAN"] = "Data gagal dihapus.";

		echo json_encode($arrJson);
	}

	function combo()
	{
		$this->load->model("SatuanKerja");
		$jenis_naskah = new SatuanKerja();

		$jenis_naskah->selectByParams(array("NOT JENIS_NASKAH_ID" => "0"));
		$i = 0;
		while ($jenis_naskah->nextRow()) {
			$arr_json[$i]['id']		= $jenis_naskah->getField("JENIS_NASKAH_ID");
			$arr_json[$i]['text']	= $jenis_naskah->getField("NAMA");
			$arr_json[$i]['JENIS_TTD']	= $jenis_naskah->getField("JENIS_TTD");
			$arr_json[$i]['PENERBIT_NOMOR']	= $jenis_naskah->getField("PENERBIT_NOMOR");
			$i++;
		}

		echo json_encode($arr_json);
	}


	function combo_statement()
	{
		$this->load->model("SatuanKerja");
		$jenis_naskah = new SatuanKerja();

		$reqId = $this->input->get("reqId");
		$reqKelompokJabatan = $this->input->get("reqKelompokJabatan");


		$statement .= " AND TIPE_NASKAH LIKE '%" . $reqId . "%' ";

		$arr_json = array();
		$jenis_naskah->selectByParams(array("NOT JENIS_NASKAH_ID" => "0"), -1, -1, $statement);
		// echo $jenis_naskah->query;exit;
		$i = 0;
		while ($jenis_naskah->nextRow()) {
			$arr_json[$i]['id']		= $jenis_naskah->getField("JENIS_NASKAH_ID");
			$arr_json[$i]['text']	= $jenis_naskah->getField("NAMA");
			$arr_json[$i]['JENIS_TTD']	= $jenis_naskah->getField("JENIS_TTD");
			$arr_json[$i]['PENERBIT_NOMOR']	= $jenis_naskah->getField("PENERBIT_NOMOR");

			if ($this->CABANG_ID == "01") {
				$arr_json[$i]['KD_LEVEL']	= $jenis_naskah->getField("KD_LEVEL");
			} else {
				$arr_json[$i]['KD_LEVEL']	= $jenis_naskah->getField("KD_LEVEL_CABANG");
			}

			$i++;
		}

		echo json_encode($arr_json);
	}




	function combo_request()
	{
		$this->load->model("SatuanKerja");
		$jenis_naskah = new SatuanKerja();

		$reqId = $this->input->get("reqId");


		$statement = " AND TIPE_NASKAH LIKE '%" . $reqId . "%' ";
		$statement .= " AND NOT COALESCE(NULLIF(KODE_SURAT, ''), 'X') = 'X' ";

		$arr_json = array();
		$jenis_naskah->selectByParams(array("NOT JENIS_NASKAH_ID" => "0"), -1, -1, $statement);
		$i = 0;
		while ($jenis_naskah->nextRow()) {
			$arr_json[$i]['id']		= $jenis_naskah->getField("JENIS_NASKAH_ID");
			$arr_json[$i]['text']	= $jenis_naskah->getField("NAMA");
			$arr_json[$i]['PENERBIT_NOMOR']	= $jenis_naskah->getField("PENERBIT_NOMOR");
			if ($this->CABANG_ID == "01")
				$arr_json[$i]['KD_LEVEL']	= $jenis_naskah->getField("KD_LEVEL");
			else
				$arr_json[$i]['KD_LEVEL']	= $jenis_naskah->getField("KD_LEVEL_CABANG");
			$i++;
		}

		echo json_encode($arr_json);
	}


	function combo_level()
	{
		$this->load->model("SatuanKerja");
		$jenis_naskah = new SatuanKerja();

		$reqId = $this->input->get("reqId");


		$statement = " AND TIPE_NASKAH LIKE '%" . $reqId . "%' ";


		$jenis_naskah->selectByParams(array("NOT JENIS_NASKAH_ID" => "0"), -1, -1, $statement);
		$i = 0;
		$arr_json = array();
		while ($jenis_naskah->nextRow()) {
			$arr_json[$i]['id']		= $jenis_naskah->getField("JENIS_NASKAH_ID");
			$arr_json[$i]['text']	= $jenis_naskah->getField("NAMA");
			if ($this->CABANG_ID == "01")
				$arr_json[$i]['KD_LEVEL']	= $jenis_naskah->getField("KD_LEVEL");
			else
				$arr_json[$i]['KD_LEVEL']	= $jenis_naskah->getField("KD_LEVEL_CABANG");

			$i++;
		}

		echo json_encode($arr_json);
	}
}
