<?
header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=Total_per_fasilitas_usulan_cetak_excel.xls");
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
</style>

<table style="width:100%" cellpadding="0" cellspacing="0">
    <tr>
        <th colspan="11">REKAPITULASI REALISASI INVESTASI TAHUN 2015</th>
    </tr>
    <tr>
        <th colspan="11">PT  PELABUHAN  INDONESIA  III  (PERSERO)</th>
    </tr>
    <tr>
        <th colspan="11">(INDUK DAN ANAK PERUSAHAAN)</th>
    </tr>
    <tr>
        <th colspan="11">PER FASILITAS</th>
    </tr>
    <tr>
        <th>&nbsp;</th>
    </tr>
</table>

<table style="width:100%" border="1" cellspacing="0" cellpadding="0">
    <tr>
        <th rowspan="2" bgcolor="#DAEEF3" width="5%">NO</th>
        <th rowspan="2" bgcolor="#DAEEF3" width="25%">NAMA AKTIVA</th>
        <th colspan="2" bgcolor="#DAEEF3" width="20%">REVISI RKAP TH 2015</th>
        <th colspan="2" bgcolor="#DAEEF3" width="20%">RKAP S.D TRW III</th>
        <th colspan="2" bgcolor="#DAEEF3" width="20%">REALISASI S.D TRW III TAHUN 2015</th>
        <th rowspan="2" colspan="3" bgcolor="#DAEEF3" width="10%">KECEND. (%)</th>
     </tr>
     <tr>
        <th bgcolor="#DAEEF3">ANGGARAN<br>(Rp.1000)</th>
        <th bgcolor="#DAEEF3">PRG</th>
        <th bgcolor="#DAEEF3">ANGGARAN<br>(Rp.1000)</th>
        <th bgcolor="#DAEEF3">PRG</th>
        <th bgcolor="#DAEEF3">PENYERAPAN<br>(Rp.1000)</th>
        <th bgcolor="#DAEEF3">PRG</th>
     </tr>
     <tr>
     	<?
		for($i=1;$i<=8;$i++)
		{
			?>
			<th bgcolor="#DAEEF3"> <?=$i?> </th>
			<?
		}
		?>
        <th bgcolor="#DAEEF3">9 = 5:3</th>
        <th bgcolor="#DAEEF3">10 = 7:3</th>
        <th bgcolor="#DAEEF3">11 = 8:4</th>
     </tr>
</table>
</body>
</html>