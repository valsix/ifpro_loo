<?
include_once("functions/PageNumber.php");
include_once("functions/date.func.php");
include_once("functions/string.func.php");
include_once("functions/default.func.php");
include_once("functions/recordcoloring.func.php");

header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=Rekapitulasi_Kehadiran_Cetak.xls");

$this->load->model('AbsensiRekap');
$this->load->model('Cabang');

$absensi_rekap = new AbsensiRekap();
$cabang = new Cabang();

$reqDepartemen = $this->input->get("reqDepartemen");
$reqBulan = $this->input->get("reqBulan");
$reqTahun = $this->input->get("reqTahun");

$periode = $reqBulan.$reqTahun;

$reqTanggalAkhir = cal_days_in_month(CAL_GREGORIAN, (int)$reqBulan, $reqTahun);

//$statement = "AND A.CABANG_ID = '".$this->KODE_CABANG."'";

/* FILTER DEPARTEMEN */
$arrDepartemen = explode("-", $reqDepartemen);
$reqCabangId = $arrDepartemen[0];
$reqDepartemenId = $arrDepartemen[1];
$reqSubDepartemenId = $arrDepartemen[2];

if(trim($reqCabangId) !== '')
	$statement .= " AND A.CABANG_ID LIKE '".$reqCabangId."%'";
if (trim($reqDepartemenId) !== '')
	$statement .= " AND A.DEPARTEMEN_ID LIKE '".$reqDepartemenId."%'";
if (trim($reqSubDepartemenId) !== "")
	$statement .= " AND A.SUB_DEPARTEMEN_ID LIKE '".$reqSubDepartemenId."%' ";

$absensi_rekap->selectByParamsRekapKehadiranKoreksi($periode, array(), -1, -1, $statement, " ORDER BY A.NAMA ASC");

$cabang->selectByParams(array("CABANG_ID" => $reqCabangId), -1, -1);
$cabang->firstRow();
?>
<!DOCTYPE html>
<html lang="en">
<head>
</head>
<body>
<style>
	body, table{
		font-size:12px;
		font-family:Arial, Helvetica, sans-serif;
	}
	.center{
		text-align:center;
	}
</style>

<table style="width:100%" cellpadding="0" cellspacing="0">
	<tr>
    	<img src="<?=base_url()?>images/logo_cetak.bmp" >
        <th colspan="29"><br>REKAP KEHADIRAN STAFF BULAN <?=strtoupper(getNamePeriode($periode))?></th>
    </tr>
    <tr>
        <th colspan="29"><br>CABANG/UNIT <?=strtoupper($cabang->getField('NAMA'))?></th>
    </tr>
    <tr>
        <th>&nbsp;</th>
    </tr>
</table>

<table style="width:100%" border="1" cellspacing="0" cellpadding="0">
   <tr>
   		<th width="40px" rowspan="2">No.</th>
        <th width="70px" rowspan="2">NRP</th>
        <th width="100px" rowspan="2">Nama</th>
        <th rowspan="2">Hari Kerja</th>
        <th colspan="7" style="text-align:center">Hadir</th>
        <th colspan="7" style="text-align:center">Ijin</th>
        <th colspan="8" style="text-align:center">Cuti Lainnya</th>
        <th rowspan="2">Cuti Tahunan</th>
        <th rowspan="2">Istirahat</th>
        <th rowspan="2">Dinas</th>
        <th rowspan="2">Alpha</th>
    </tr>
    <tr>
        <th>Jumlah</th> 
        <th>H</th> 
        <th>HT</th> 
        <th>HPC</th> 
        <th>HTPC</th> 
        <th>HTAD</th> 
        <th>HTAP</th> 
        <th>Jumlah</th> 
        <th>IM</th> 
        <th>IMKK</th> 
        <th>IMMPAK</th> 
        <th>IKM</th> 
        <th>IKAK</th> 
        <th>SDK</th> 
        <th>Jumlah</th> 
        <th>CB</th> 
        <th>CBS</th> 
        <th>CD</th> 
        <th>CGK</th> 
        <th>CH</th> 
        <th>CIK</th> 
        <th>CKW</th> 
    </tr>
	<?
	$i=1;
    while($absensi_rekap->nextRow())
    {
    ?>
    <tr>
    	<td class="center"><?=$i?></td>
        <td><?=$absensi_rekap->getField('NRP')?></td>
        <td><?=$absensi_rekap->getField('NAMA')?></td>
        <td class="center"><?=$absensi_rekap->getField('KELOMPOK')?></td>
        <td class="center"><?=$absensi_rekap->getField('JUMLAH_H')?></td>
        <td class="center"><?=$absensi_rekap->getField('H')?></td>
        <td class="center"><?=$absensi_rekap->getField('HT')?></td>
        <td class="center"><?=$absensi_rekap->getField('HPC')?></td>
        <td class="center"><?=$absensi_rekap->getField('HTPC')?></td>
        <td class="center"><?=$absensi_rekap->getField('HTAD')?></td>
        <td class="center"><?=$absensi_rekap->getField('HTAP')?></td>
        <td class="center"><?=$absensi_rekap->getField('JUMLAH_IJIN')?></td>
        <td class="center"><?=$absensi_rekap->getField('IM')?></td>
        <td class="center"><?=$absensi_rekap->getField('IMKK')?></td>
        <td class="center"><?=$absensi_rekap->getField('IMMPAK')?></td>
        <td class="center"><?=$absensi_rekap->getField('IKM')?></td>
        <td class="center"><?=$absensi_rekap->getField('IKAK')?></td>
        <td class="center"><?=$absensi_rekap->getField('SDK')?></td>
        <td class="center"><?=$absensi_rekap->getField('JUMLAH_CUTI')?></td>
        <td class="center"><?=$absensi_rekap->getField('CB')?></td>
        <td class="center"><?=$absensi_rekap->getField('CBS')?></td>
        <td class="center"><?=$absensi_rekap->getField('CD')?></td>
        <td class="center"><?=$absensi_rekap->getField('CGK')?></td>
        <td class="center"><?=$absensi_rekap->getField('CH')?></td>
        <td class="center"><?=$absensi_rekap->getField('CIK')?></td>
        <td class="center"><?=$absensi_rekap->getField('CKW')?></td>
        <td class="center"><?=$absensi_rekap->getField('CT')?></td>
        <td class="center"><?=$absensi_rekap->getField('ITH')?></td>
        <td class="center"><?=$absensi_rekap->getField('DL')?></td>
        <td class="center"><?=$absensi_rekap->getField('A')?></td>
    </tr>
	<?
		$i++;
    }
    ?>
</table>
</body>
</html>