<?
header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=Trw3_cetak_excel.xls");
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

<table style="width:100%" border="1" cellspacing="0" cellpadding="0">
        <tr>
            <th rowspan="4" width="30px" height="70px" bgcolor="#CCFFFF">NO</th>
            <th rowspan="4" width="450px" height="70px" bgcolor="#CCFFFF">NAMA CABANG</th>
            <th colspan="20" width="450px" height="70px" bgcolor="#CCFFFF"><br>TAHUN BERJALAN<br>&nbsp;</th>
            <th rowspan="4" width="100px" height="70px" bgcolor="#CCFFFF">TOTAL</th>
            <th rowspan="4" width="100px" height="70px" bgcolor="#CCFFFF">PRG TOTAL</th>
            <th colspan="20" width="450px" height="70px" bgcolor="#FFCC00"><br>MULTIYEARS<br>&nbsp;</th>
            <th rowspan="4" width="100px" height="70px" bgcolor="#FFCC00">TOTAL</th>
            <th rowspan="4" width="100px" height="70px" bgcolor="#FFCC00">PRG TOTAL</th>
            <th colspan="20" width="450px" height="70px" bgcolor="#CC99FF"><br>CARRYOVER<br>&nbsp;</th>
            <th rowspan="4" width="100px" height="70px" bgcolor="#CC99FF">TOTAL</th>
            <th rowspan="4" width="100px" height="70px" bgcolor="#CC99FF">PRG TOTAL</th>
            <th rowspan="4" width="100px" height="70px" bgcolor="#CCFFFF">PRG RKAP</th>
            <th rowspan="4" width="100px" height="70px" bgcolor="#CCFFFF"></th>
            <th rowspan="4" width="100px" height="70px" bgcolor="#CCFFFF"></th>
            <th rowspan="4" width="100px" height="70px" bgcolor="#FFFF00">TOTAL (URAIN)</th>
            <th rowspan="4" width="100px" height="70px" bgcolor="#CCFFFF">RKAP Th. 2014</th>
            <th rowspan="4" width="200px" height="70px" bgcolor="#CCFFFF">&nbsp;&nbsp;CEK&nbsp;&nbsp;</th>
         </tr>
         <tr>
         	<?
			for ($i=1; $i<=3; $i++)
			{
				if ($i == 1)
				{
					$warna = "#CCFFFF";
				}
				else if ($i == 2)
				{
					$warna = "#FFCC00";
				}
				else if ($i == 3)
				{
					$warna = "#CC99FF";
				}
            ?>
         	<th colspan="8" width="450px" height="70px" bgcolor="<?=$warna?>"><br>AKTIVA TETAP POKOK<br>&nbsp;</th>
         	<th colspan="6" width="450px" height="70px" bgcolor="<?=$warna?>"><br>AKTIVA TETAP PENUNJANG<br>&nbsp;</th>
         	<th colspan="4" width="450px" height="70px" bgcolor="<?=$warna?>"><br>AKTIVA TETAP PELENGKAP<br>&nbsp;</th>
         	<th colspan="2" width="450px" height="70px" bgcolor="<?=$warna?>"><br>AKTIVA TETAP TIDAK<br>BERWUJUD</th>
            <?
			}
            ?>
         </tr>
         <tr>
            <?
			for ($i=1; $i<=3; $i++)
			{
				if ($i == 1)
				{
					$warna = "#CCFFFF";
				}
				else if ($i == 2)
				{
					$warna = "#FFCC00";
				}
				else if ($i == 3)
				{
					$warna = "#CC99FF";
				}
            ?>
                <th width="110px" bgcolor="<?=$warna?>"><br>201 Bangunan<br>Fasilitas Pelabuhan<br>&nbsp;</th>
                <th width="50" bgcolor="<?=$warna?>">&nbsp;<? $i ?></th>
                <th width="110px" bgcolor="<?=$warna?>">202 Kapal</th>
                <th width="50" bgcolor="<?=$warna?>">&nbsp;</th>
                <th width="110px" bgcolor="<?=$warna?>"><br>203 Alat Fasilitas<br>Pelabuhan<br>&nbsp;</th>
                <th width="50" bgcolor="<?=$warna?>">&nbsp;</th>
                <th width="110px" bgcolor="<?=$warna?>"><br>204 Instalasi<br>Fasilitas Pelabuhan</th>
                <th width="50" bgcolor="<?=$warna?>">&nbsp;</th>
                <th width="110px" bgcolor="<?=$warna?>">211 Tanah</th>
                <th width="50" bgcolor="<?=$warna?>">&nbsp;</th>
                <th width="110px" bgcolor="<?=$warna?>"><br>212 Jalan dan<br>Bangunan</th>
                <th width="50" bgcolor="<?=$warna?>">&nbsp;</th>
                <th width="110px" bgcolor="<?=$warna?>">213 Peralatan</th>
                <th width="50" bgcolor="<?=$warna?>">&nbsp;</th>
                <th width="110px" bgcolor="<?=$warna?>">221 Kendaraan</th>
                <th width="50" bgcolor="<?=$warna?>">&nbsp;</th>
                <th width="110px" bgcolor="<?=$warna?>"><br>222<br>Emplasemen</th>
                <th width="50" bgcolor="<?=$warna?>">&nbsp;</th>
                <th width="110px" bgcolor="<?=$warna?>">231 Lain-Lain</th>
                <th width="50" bgcolor="<?=$warna?>">&nbsp;</th>
            <?
			}
            ?>
            	
        </tr>
        <tr>
        	<?
			for ($i=1; $i<=30; $i++)
			{
				if ($i <= 10)
				{
					$warna = "#CCFFFF";
				}
				else if ($i <= 20)
				{
					$warna = "#FFCC00";
				}
				else if ($i <= 30)
				{
					$warna = "#CC99FF";
				}
            ?>
                <th bgcolor="<?=$warna?>"><br>(Rp. 1000)<br>&nbsp;</th>
                <th bgcolor="<?=$warna?>"><? $i ?></th>
            <?
			}
            ?>
        </tr>
        <tr>
        	<? $color = "#CCFFFF" ?>
        		<th bgcolor="<?=$color?>">1</th>
                <th bgcolor="<?=$color?>">2</th>
                <?
                for($i=3;$i<=35;$i++)
                {
                    ?>
                    <th bgcolor="<?=$color?>"> <?=$i?> </th>
                    <th bgcolor="<?=$color?>">&nbsp;</th>
                    <?
                }
            	?>
                <?
                for($i=1;$i<=4;$i++)
                {
                    ?>
                    <th bgcolor="<?=$color?>">&nbsp;<? $i ?></th>
                    <?
                }
            	?>
                <th bgcolor="<?=$color?>">36</th>
                <th bgcolor="<?=$color?>">37</th>
        </tr>
</table>
</body>
</html>