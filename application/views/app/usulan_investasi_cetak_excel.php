<?
header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=Usulan_Investasi_Cetak_Excel.xls");
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
	</style>
<table style="width:100%">
        <tr>
            <th>
                <td></td><td></td><td></td><td></td><td></td><td></td><td></td>&nbsp;<img src="<?=base_url()?>images/logo-pelindo.png" />
            </th>
        </tr>
        <tr></tr><tr></tr><tr></tr><tr></tr>
        <tr>
            <th colspan="18">BERITA ACARA</th>
        </tr>
        <tr>
            <th colspan="18">USULAN INVESTASI TAHUN 2016 PT PELABUHAN INDONESIA III (PERSERO)</th>	
        </tr>
        <tr>
            <th colspan="18">CABANG KOTABARU</th>	
        </tr>
        <tr>
            <td>Nomor : </td>
            <td></td>
        </tr>
        <tr>
            <td>I</td>
            <td colspan="18">Pada hari ini Jumat tanggal Sebelas bulan September  tahun dua ribu lima belas (11-09-2015), bertempat di ruang Kalimutu lantai IV Kantor Pusat PT Pelabuhan Indonesia III </td>
        </tr>
        <tr>
            <td>II</td>
            <td colspan="18">Rapat Penyusunan Usulan Investasi untuk tahun anggaran 2016 dipimpin oleh SM. Manajemen dan Resiko Keuangan dan dihadiri oleh peserta sebagaimana daftar</td>
        </tr>
        <tr>
            <td rowspan="3" style="vertical-align:top">III</td>
            <td colspan="18">Dasar Pelaksanaan Kegiatan</td>
        </tr>
        <tr>
            <td colspan="18">1. Surat direktur Teknik dan Teknologi Informasi kepada tiap-tiap General Manager, No: OS.03/156/PIII-2015 tanggal 13 Agustus 2015 Perihal laporan taksasi investasi  tahun </td>
        </tr>
        <tr>
            <td colspan="18">2.Surat direktur Teknik dan Teknologi Informasi kepada tiap-tiap Direktur Anak Perusahaan, No: OS.03/155/PIII-2015 tanggal 13 Agustus 2015 Perihal laporan taksasi investasi  </td>
        </tr>
        <tr>
            <td rowspan="2" style="vertical-align:top">IV</td>
            <td colspan="18">Materi Pembahasan</td>
        </tr>
        <tr>
            <td colspan="18">Usulan Investasi tahun 2016</td>
        </tr>
</table>
<br/>
<table style="width:100%" border="1" cellspacing="0" cellpadding="0">
        <tr>
            <th rowspan="2" width="30px">NO</th>
            <th rowspan="2" width="450px">NAMA AKTIVA</th>
            <th colspan="5">USULAN INVESTASI TH 2016</th>
            <th colspan="4">KELOMPOK INVESTASI</th>
            <th colspan="4">ANGGARAN PER-TRIWULAN (Rp)</th>
            <th colspan="1">KETERANGAN</th>
            <th rowspan="2" width="50px">OPEX</th>
            <th rowspan="2" width="50px">CAPEX</th>
        </tr>
        <tr>
            <th width="50px">VOL</th>
            <th width="50px">SAT</th>
            <th width="100px">NILAI PROYEK (Rp. 1000)</th>
            <th width="80px">NILAI USULAN (Rp. 1000)</th>
            <th width="50px">PROG</th>
            <th width="80px">LEVEL OF REVENUE</th>
            <th width="80px">LEVEL OF SERVICE</th>
            <th width="80px">LEVEL OF SAFETY</th>
            <th width="100px">MINIMAL REQUIREMENT</th>
            <th width="100px">TRW I</th>
            <th width="100px">TRW II</th>
            <th width="100px">TRW III</th>
            <th width="100px">TRW IV</th>
            <th width="120px">LATAR BELAKANG (MAKSUD/TUJUAN)</th>
        </tr>
        <tr>
            <?
                for($i=1;$i<=11;$i++)
                {
                    ?>
                    <th> <?=$i?> </th>
                    <?
                }
            ?>
            <?
                for($i=14;$i<=17;$i++)
                {
                    ?>
                    <th> <?=$i?> </th>
                    <?
                }
            ?>
            <?
                for($i=12;$i<=14;$i++)
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