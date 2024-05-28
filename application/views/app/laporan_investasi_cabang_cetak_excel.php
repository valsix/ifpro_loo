<?
header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=Laporan_investasi_cabang_cetak_excel.xls");
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
    	<th rowspan="2" align="left">&nbsp;<img src="<?=base_url()?>images/logo-pelindo_new.png" /></th>
    	<th align="left">
        	PT. PELABUHAN INDONESIA III (PERSERO)<br>
    		<u>CABANG BANJARMASIN</u>
	    </th>
    </tr>
    <tr>
    	<th>&nbsp;</th>
    </tr>
    <tr>
        <th colspan="17"><br>LAPORAN BULANAN PELAKSANAAN PEKERJAAN INVESTASI TAHUN 2015</th>
    </tr>
    <tr>
        <th colspan="17">POSISI : S/D JUNI 2015</th>
    </tr>
    <tr>
        <th>&nbsp;</th>
    </tr>
</table>

<table style="width:100%" border="1" cellspacing="0" cellpadding="0">
    <tr>
        <th rowspan="2" width="5%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;NO&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
        <th rowspan="2" width="25%">JENIS KEGIATAN</th>
        <th rowspan="2" width="8%">VOL</th>
        <th width="8%">RKA<br>THN 2015</th>
        <th width="8%">ANGGARAN<br>PER<br>KEGIATAN</th>
        <th rowspan="2" width="8%">PROG</th>
        <th rowspan="2"width="8%">KONTRAK/SPK/DLL<br>NOMOR DAN<br>TANGGAL</th>
        <th width="8%">NILAI<br>KONTRAK<br>SPK</th>
        <th rowspan="2" width="8%">ALOKASI<br>KONTRAK<br>TAHUN 2015<br>(Rp. 1.000)</th>
        <th colspan="2" width="8%">TGL PELAKSANAAN<br>KONTRAK/SPK/DLL</th>
        <th colspan="5" width="8%">REALISASI<br>PELAKSANAAN</th>
        <th rowspan="2" width="5%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;KET.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
     </tr>
     <tr>
     	<th>(Rp. 1.000)</th>
        <th>(Rp. 1.000)</th>
        <th>(Rp. 1.000)</th>
        <th>MULAI</th>
        <th>SELESAI</th>
        <th>FISIK THP<br>KONTRAK<br>TOTAL<br><br>(%)</th>
        <th>FISIK THP<br>TARGET<br>2015<br><br>(%)</th>
        <th>ANGGARAN<br>THP<br>KONTRAK<br>TOTAL<br>(Rp.)</th>
        <th>ANGGARAN<br>THP LOKASI<br>KONTRAK TH<br>2015<br></th>
        <th>TOTAL<br>PEMBAYARAN<br><br><br>(Rp.)<br></th>
     </tr>
     <tr>
     	<?
		for($i=1;$i<=17;$i++)
		{
			?>
			<th> <?=$i?> </th>
			<?
		}
		?>
     </tr>
</table>
</body>
</html>