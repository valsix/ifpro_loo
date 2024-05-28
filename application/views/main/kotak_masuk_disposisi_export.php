<?php
include_once("functions/string.func.php");
include_once("functions/default.func.php");
include_once("functions/date.func.php");

ini_set("memory_limit","500M");
ini_set('max_execution_time', 520);

$this->load->model("SuratMasuk");

$reqJenisNaskahId= $this->input->get("reqJenisNaskahId");
$reqTahun= $this->input->get("reqTahun");

$reqJenisNaskahNamaFile= "kotak_masuk_disposisi_";
$reqJenisNaskahInfo= ucwords(str_replace("_", " ", $reqJenisNaskahNamaFile));
$reqJenisNaskahNamaFile= $reqJenisNaskahNamaFile;

$tempPeriode= $reqJenisNaskahNamaFile.str_replace("-","_",date("d-m-Y"));
header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=\"".$tempPeriode.".xls\"");

$reqPencarian= $this->input->get("reqPencarian");
$searchJson= " 
AND 
(
	UPPER(A.NO_AGENDA) LIKE '%".strtoupper($reqPencarian)."%' OR 
	UPPER(A.NOMOR) LIKE '%".strtoupper($reqPencarian)."%' OR
	UPPER(A.PERIHAL) LIKE '%".strtoupper($reqPencarian)."%' OR 
	UPPER(A.INSTANSI_ASAL) LIKE '%".strtoupper($reqPencarian)."%' OR 
	UPPER(A.DARI_INFO) LIKE '%".strtoupper($reqPencarian)."%' OR 
	UPPER(A.USER_ATASAN) LIKE '%".strtoupper($reqPencarian)."%' OR 
	UPPER(USER_ATASAN_JABATAN) LIKE '%".strtoupper($reqPencarian)."%' OR 
	UPPER(A.NOMOR_SURAT_INFO) LIKE '%".strtoupper($reqPencarian)."%' OR 
	UPPER(B.NAMA_USER_ASAL) LIKE '%".strtoupper($reqPencarian)."%' OR 
	UPPER(B.NAMA_SATKER_ASAL) LIKE '%".strtoupper($reqPencarian)."%'
)";

$statement= "
AND 
(
	A.STATUS_SURAT = 'POSTING' OR
	A.STATUS_SURAT = 'TU-NOMOR' OR
	(
		A.STATUS_SURAT = 'TU-IN' AND
		EXISTS(SELECT 1 FROM SURAT_MASUK_ARSIP X WHERE X.SURAT_MASUK_ID = A.SURAT_MASUK_ID AND X.CABANG_ID = '".$this->CABANG_ID."')
	)
)";

if(!empty($reqJenisNaskahId))
{
	$statement.= " AND A.JENIS_NASKAH_ID IN (".$reqJenisNaskahId.")";
}

if(!empty($reqTahun))
{
	$statement.= " AND A.TAHUN = ".$reqTahun;
}

$arrdata= array(
	array("label"=>"NO. SURAT", "field"=>"NOMOR", "width"=>"100")
	, array("label"=>"DARI", "field"=>"INFO_DARI", "width"=>"")
	, array("label"=>"PERIHAL", "field"=>"PERIHAL", "width"=>"")
	, array("label"=>"TANGGAL", "field"=>"TANGGAL_DISPOSISI", "width"=>"")
	, array("label"=>"DISPOSISI DARI", "field"=>"DETIL_INFO_DARI_DIPOSISI", "width"=>"")
	, array("label"=>"DISPOSISI", "field"=>"DISPOSISI", "width"=>"")
);

$sOrder = " ORDER BY B.TANGGAL_DISPOSISI DESC";
$set= new SuratMasuk();
$set->selectByParamsDisposisi(array(), -1, -1, $this->ID, $statement.$searchJson, $sOrder);
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
            <td colspan="<?=count($arrdata)?>" style="font-size:13px;font-weight:bold; text-align: center;">Data <?=$reqJenisNaskahInfo?></td>	
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
						elseif($field == "DETIL_INFO_DARI_DIPOSISI")
						{
							$tempValue= $set->getField("NAMA_USER_ASAL")."<br/>".$set->getField("NAMA_SATKER_ASAL");
						}
						elseif ($field == "TANGGAL_DISPOSISI")
						{
							$infoicondisposisi= "";
							// if($set->getField("TERDISPOSISI") == "1")
							// {
							// 	$infoicondisposisi= "<i class='fa fa-share-alt' aria-hidden='true'></i>";
							// }
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