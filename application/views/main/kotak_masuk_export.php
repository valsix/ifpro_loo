<?php
include_once("functions/string.func.php");
include_once("functions/default.func.php");
include_once("functions/date.func.php");

ini_set("memory_limit","500M");
ini_set('max_execution_time', 520);

$this->load->model("SuratMasuk");

$set= new SuratMasuk();

$reqJenisNaskahId= $this->input->get("reqJenisNaskahId");
$reqTahun= $this->input->get("reqTahun");
$reqPilihan= $this->input->get("reqPilihan");

$reqJenisNaskahNamaFile= "semua";
$reqJenisNaskahInfo= ucwords($reqJenisNaskahNamaFile);
$reqJenisNaskahNamaFile= $reqJenisNaskahNamaFile."_";
if(!empty($reqJenisNaskahId))
{
	$reqJenisNaskahNamaFile= str_replace("add", "", getJenisNaskah($reqJenisNaskahId));
	$reqJenisNaskahInfo= ucwords(str_replace("_", " ", $reqJenisNaskahNamaFile));
}

$tempPeriode= "kotak_masuk_".$reqJenisNaskahNamaFile.str_replace("-","_",date("d-m-Y"));
header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=\"".$tempPeriode.".xls\"");

$reqPencarian= $this->input->get("reqPencarian");
$searchJson= " 
AND 
(
	UPPER(A.NO_AGENDA) LIKE '%".strtoupper($reqPencarian)."%' OR 
	UPPER(A.NOMOR) LIKE '%".strtoupper($reqPencarian)."%' OR
	UPPER(CASE 
	WHEN B.STATUS_DISPOSISI = 'DISPOSISI' THEN '[DISPOSISI] ' 
	WHEN B.STATUS_DISPOSISI = 'TEMBUSAN' THEN '[TEMBUSAN] ' 
	WHEN B.STATUS_DISPOSISI = 'DISPOSISI_TEMBUSAN' THEN '[TEMBUSAN DISPOSISI] ' 
	WHEN B.STATUS_DISPOSISI = 'TERUSAN' THEN '[FWD] ' 
	WHEN B.STATUS_DISPOSISI = 'BALASAN' THEN '[RE] ' 
	ELSE '' END || A.PERIHAL) LIKE '%".strtoupper($reqPencarian)."%' OR 
	UPPER(A.INSTANSI_ASAL) LIKE '%".strtoupper($reqPencarian)."%' OR 
	UPPER(A.DARI_INFO) LIKE '%".strtoupper($reqPencarian)."%' OR 
	UPPER(A.USER_ATASAN) LIKE '%".strtoupper($reqPencarian)."%' OR 
	UPPER(USER_ATASAN_JABATAN) LIKE '%".strtoupper($reqPencarian)."%' OR 
	UPPER(A.NOMOR_SURAT_INFO) LIKE '%".strtoupper($reqPencarian)."%'  
)";

if ($reqPilihan=='divisi') 
{
	$reqNIPPP= $this->NIP_BY_DIVISI;
	$reqNIPPP= "'".str_replace("'", "''", $reqNIPPP)."'";
	$reqKelompokJabatann= $this->KELOMPOK_JABATAN_BY_DIVISI;
	$reqKelompokJabatann= "'".str_replace("'", "''", $reqKelompokJabatann)."'";
	$reqsatuankerjadivisi= "";
} 
else 
{
	$reqNIPPP= "'".$this->ID."'";
	$reqKelompokJabatann= "'".$this->KELOMPOK_JABATAN."'";
	$reqsatuankerjadivisi= $this->SATUAN_KERJA_ID_ASAL_ASLI;
}

$infogantijabatan= "";
if(in_array("SURAT", explode(",", $this->USER_GROUP)))
{
	$statement= " 
	AND A.TTD_KODE IS NOT NULL
	";
	/*AND EXISTS
	(
		SELECT 1
		FROM
		(
			SELECT X.SURAT_MASUK_ID, MAX(DISPOSISI_ID) DISPOSISI_ID
			FROM DISPOSISI X
			WHERE X.SATUAN_KERJA_ID_TUJUAN LIKE '".$this->CABANG_ID."%'
			AND X.DISPOSISI_PARENT_ID = 0
			GROUP BY X.SURAT_MASUK_ID
		) X WHERE X.SURAT_MASUK_ID = B.SURAT_MASUK_ID AND X.DISPOSISI_ID = B.DISPOSISI_ID
	)*/
}
else
{
	$statement= " 
	AND B.DISPOSISI_PARENT_ID = 0
	AND 
	(
		A.STATUS_SURAT = 'POSTING' OR
		A.STATUS_SURAT = 'TU-NOMOR' OR
		(
			A.STATUS_SURAT = 'TU-IN' AND
			EXISTS(SELECT 1 FROM SURAT_MASUK_ARSIP X WHERE X.SURAT_MASUK_ID = A.SURAT_MASUK_ID AND X.CABANG_ID = '".$this->CABANG_ID."')
		)
	)";

	if($this->SATUAN_KERJA_ID_ASAL_ASLI == $this->SATUAN_KERJA_ID_ASAL)
	{
		if(!empty($this->USER_BANTU))
		{
			$statement.= " AND B.SATUAN_KERJA_ID_TUJUAN = '".$this->SATUAN_KERJA_ID_ASAL."'";
		}
	}
	else
	{
		$infogantijabatan= "1";
		if(!empty($this->USER_BANTU))
		{
			$statement.= " AND B.STATUS_BANTU = 1 AND A.STATUS_SURAT IN ('POSTING')";
		}

		$statement.= "
		AND
		(
			B.SATUAN_KERJA_ID_TUJUAN = '".$this->SATUAN_KERJA_ID_ASAL."'
			OR
			(
				B.SATUAN_KERJA_ID_TUJUAN = '".$this->CABANG_ID."'
				and 
				exists
				(
					select 1
					from
					(
						select a.disposisi_kelompok_id, a1.kelompok_jabatan
						from disposisi_kelompok a
						inner join
						(
							select satuan_kerja_kelompok_id, kelompok_jabatan
							from satuan_kerja_kelompok_group
						) a1 on a.satuan_kerja_kelompok_id = a1.satuan_kerja_kelompok_id
					) x where b.disposisi_kelompok_id = x.disposisi_kelompok_id and x.kelompok_jabatan in (".$reqKelompokJabatann.")
				)
			)
		)
		";
	}
}

if(!empty($reqJenisNaskahId))
{
	$statement.= " AND A.JENIS_NASKAH_ID IN (".$reqJenisNaskahId.")";
}

if(!empty($reqTahun))
{
	$statement.= " AND A.TAHUN = ".$reqTahun;
}

if($reqStatusSurat == 1)
{
	$statement.= " AND B.TERBACA_INFO LIKE '%".$this->ID."%'";
}
else if ($reqStatusSurat == 2)
{
	$statement.= " AND COALESCE(B.TERBACA_INFO, '')  NOT ILIKE '%".$this->ID."%'";
}

$arrdata= array(
	array("label"=>"NO. SURAT", "field"=>"NOMOR", "width"=>"100")
	, array("label"=>"DARI", "field"=>"INFO_DARI", "width"=>"")
	, array("label"=>"PERIHAL", "field"=>"PERIHAL", "width"=>"")
	, array("label"=>"TANGGAL", "field"=>"TANGGAL_DISPOSISI", "width"=>"")
);

// $sOrder = " ORDER BY B.TANGGAL_DISPOSISI DESC";
$sOrder = " ORDER BY  
CASE WHEN B.STATUS_DISPOSISI = 'DISPOSISI' THEN B.TANGGAL_DISPOSISI  
WHEN B.STATUS_DISPOSISI = 'DISPOSISI_TEMBUSAN' THEN B.TANGGAL_DISPOSISI 
ELSE TANGGAL_ENTRI END DESC";
if($infogantijabatan == "1")
{
	$set->selectByParamsSuratMasuk(array(), -1, -1, $statement.$searchJson, $sOrder);
}
else
{
	$set->selectByParamsNewSuratMasuk(array(), -1, -1, $reqNIPPP, $this->CABANG_ID, $reqKelompokJabatann, $statement.$searchJson, $sOrder, $reqsatuankerjadivisi);
}
// echo $set->query;exit;
?>
<!DOCTYPE html>
<html lang="en">
<head>
</head>
<body>
<style>
	body, table{
		font-size:12px;
		font-family:Arial, Helvetica, sans-serif
	}
	th {
		text-align:center;
		font-weight: bold;
	}
	td {
		vertical-align: top;
  		text-align: left;
	}
	.str{
	  mso-number-format:"\@";/*force text*/
	}
	</style>
<table style="width:100%">
        <tr>
            <td colspan="<?=count($arrdata)?>" style="font-size:13px;font-weight:bold; text-align: center;">Data Kotak Masuk <?=$reqJenisNaskahInfo?></td>	
        </tr>
</table>
<br/>
<br/>
    	<table style="width:100%" border="1" cellspacing="0" cellpadding="0">
            <thead>
                <tr>
                <?
                for($i=0; $i < count($arrdata); $i++)
                {
                	$infolabel= $arrdata[$i]["label"];
                ?>
                	<th style="text-align: center;"><?=$infolabel?></th>
                <?
            	}
                ?>
                </tr>
            </thead>
            <tbody>
                <?
				$nomor = 1;
                while($set->nextRow())
                {
                	$infojenisnaskahid= $set->getField("JENIS_NASKAH_ID");
				?>
                	<tr>
                	<?
                	for($i=0; $i<count($arrdata); $i++)
					{
						$field= $arrdata[$i]["field"];
						$tempValue= "";

						if($field == "NOMOR")
						{
							if($infojenisnaskahid == "1")
								$tempValue= $set->getField("NOMOR_SURAT_INFO");
							else
								$tempValue= $set->getField($field);
						}
						elseif($field == "INFO_DARI")
						{
							if($infojenisnaskahid == "1")
								$tempValue= $set->getField("DARI_INFO");
							else
								$tempValue= $set->getField("USER_ATASAN")."<br/>".$set->getField("USER_ATASAN_JABATAN");
						}
						elseif ($field == "TANGGAL_DISPOSISI")
						{
							$infoicondisposisi= "";
							if($set->getField("TERDISPOSISI") == "1")
							{
								$infoicondisposisi= "<i class='fa fa-share-alt' aria-hidden='true'></i>";
							}
							$tempValue= getFormattedExtDateTimeCheck($set->getField($field))." ".$infoicondisposisi;
						}
						else
							$tempValue= $set->getField($field);
                	?>
                		<td class="str"><?=$tempValue?></td>
                	<?
                	}
                	?>
                    </tr>
				<?
					$nomor++;
                }
                ?>    
            </tbody>
        </table>
</body>
</html>