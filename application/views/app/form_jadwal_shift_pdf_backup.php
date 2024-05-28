<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

/* CHECK USER LOGIN 
$CI =& get_instance();
$CI->checkUserLogin();*/

$this->load->model('KelompokShift');
$this->load->model('KelompokShiftJadwal');
$this->load->model('PermohonanShift');

$kelompok_shift = new KelompokShift();
$kelompok_shift_jadwal = new KelompokShiftJadwal();
$permohonan_shift = new PermohonanShift();

$reqId = $this->input->get("reqId");

$permohonan_shift->selectByParams(array("A.PERMOHONAN_SHIFT_ID" => $reqId));
$permohonan_shift->firstRow();
$tahun = $permohonan_shift->getField("TAHUN");

$kelompok_shift_jadwal->selectByParams(array("A.PERMOHONAN_SHIFT_ID" => $reqId));
while($kelompok_shift_jadwal->nextRow())
{
	$arrData[$kelompok_shift_jadwal->getField("JAM_KERJA_SHIFT_ID").(int)$kelompok_shift_jadwal->getField("PERIODE").$kelompok_shift_jadwal->getField("HARI")] = $kelompok_shift_jadwal->getField("KELOMPOK");
}
?>
<?php
//start report
$html = "
<div class='konten-cetak'>
	
    <div class='kop-jadwal-shift'>
    	<table>
        	<tr>
            	<td width='3%' rowspan='4'><img src='".base_url()."images/logo-pjb3.jpg'></td>
                <td width='70%' align='center'><strong>PT PEMBANGKITAN JAWA BALI UNIT BISNIS JASA O&M PLTU REMBANG</strong></td>
                <td width='12%'>No. Dokumen</td>
                <td width='15%' class='noborder-left'>: FME-04.1.1.26</td>
            </tr>
            <tr>
            	<td align='center'><strong>PJB INTEGRATED MANAGEMENT SYSTEM</strong></td>
                <td>Tanggal Terbit</td>
                <td>: 3 Oktober 2013</td>
            </tr>
            <tr>
            	<td align='center'><strong>FORMULIR</strong></td>
                <td>Revisi</td>
                <td>: 00</td>
            </tr>
            <tr>
            	<td align='center'><strong>JADWAL DINAS OPERATOR PRODUKSI TAHUN 2015</strong></td>
                <td>Halaman</td>
                <td>: 1 dari 1</td>
            </tr>
        </table>
    </div>
    
	<table class='table overflow-y'>
	<thead>
		<tr>
			<th class='atas' style='text-align:center; vertical-align:middle; background:black !important; color:#FFF !important;' rowspan='2'>Bulan</th>
			<th class='atas' style='text-align:center; vertical-align:middle' rowspan='2'>Jam Dinas</th>
			<th style='text-align:center' colspan='31'>Tanggal</th>
		</tr> 
		<tr>"
?>
<?
for($i=1;$i<=31;$i++)
{
?>
<?
$html .= "<th class='atas' style='text-align:center'>".$i."</th>"
?>
<?
}
?>
<?
$html .= "
		</tr>
    </thead>
	<tbody id='tbData'>
"
?>
<?
	for($j=1;$j<=12;$j++)
	{
		if ($j % 2 == 0){
			//$warna_row = "#FFF";
			$warna_bulan = "terang";
			
		}
		else{
			//$warna_row = "#f0f0f0";
			$warna_bulan = "gelap";
		}

?>
<?
$html .="		<tr>
                <th class='".$warna_bulan."' rowspan='4' style='vertical-align:middle'>".getNameMonth($j)."</th>
                <td class='jam-dinas'>Pagi</td>
"				
?>
<?
                for($k=1;$k<=31;$k++)
                {
					if($arrData["1".$j.$tahun.$k] == "A"){
						$bg_shift = "bg-shift-biru";
					}
					if($arrData["1".$j.$tahun.$k] == "B"){
						$bg_shift = "bg-shift-pink";
					}
					if($arrData["1".$j.$tahun.$k] == "C"){
						$bg_shift = "bg-shift-hijau";
					}
					if($arrData["1".$j.$tahun.$k] == "D"){
						$bg_shift = "bg-shift-putih";
					}
                    
?>
<?
$html .=	"			<td class='".$bg_shift."'>
                            <label id='pagi-".$j."-".$k."'>".$arrData['1'.$j.$tahun.$k]."</label>
                            <input type='hidden' name='reqPagi-".$j."-".$k."' id='reqPagi-".$j."-".$k."' value='".$arrData['1'.$j.$tahun.$k]."'>
                        </td>
             "
?>
<?		
                }
?>
<?
	$html .="
            </tr>
			<tr>
                <td class='jam-dinas'>Sore</td>
			"
?>
<?
                for($k=1;$k<=31;$k++)
                {
					if($arrData["2".$j.$tahun.$k] == "A"){
						$bg_shift = "bg-shift-biru";
					}
					if($arrData["2".$j.$tahun.$k] == "B"){
						$bg_shift = "bg-shift-pink";
					}
					if($arrData["2".$j.$tahun.$k] == "C"){
						$bg_shift = "bg-shift-hijau";
					}
					if($arrData["2".$j.$tahun.$k] == "D"){
						$bg_shift = "bg-shift-putih";
					}
?>
<?
$html .= "		<td class='".$bg_shift."'>
                            <label id='sore-".$j."-".$k."'>".$arrData['2'.$j.$tahun.$k]."</label>
                            <input type='hidden' name='reqSore-".$j."-".$k."' id='reqSore-".$j."-".$k."' value='".$arrData['2'.$j.$tahun.$k]."'>
                </td>
           "
?>
<?		
                }
?>
<?
$html .= "
			</tr>
            <tr>
                <td class='jam-dinas'>Malam</td>
			"
?>
<?
                for($k=1;$k<=31;$k++)
                {
					if($arrData["3".$j.$tahun.$k] == "A"){
						$bg_shift = "bg-shift-biru";
					}
					if($arrData["3".$j.$tahun.$k] == "B"){
						$bg_shift = "bg-shift-pink";
					}
					if($arrData["3".$j.$tahun.$k] == "C"){
						$bg_shift = "bg-shift-hijau";
					}
					if($arrData["3".$j.$tahun.$k] == "D"){
						$bg_shift = "bg-shift-putih";
					}
?>
<?
$html .= "		<td class='".$bg_shift."'>
                            <label id='malam-".$j."-".$k."'>".$arrData['3'.$j.$tahun.$k]."</label>
                            <input type='hidden' name='reqMalam-".$j."-".$k."' id='reqMalam-".$j."-".$k."' value='".$arrData['3'.$j.$tahun.$k]."'>
                </td>
           "
?>
<?		
                }
?>
<?
$html .= "
            </tr>
            <tr>
                <td class='jam-dinas'>Libur</td>
		"
?>
<?
                for($k=1;$k<=31;$k++)
                {
					if($arrData["4".$j.$tahun.$k] == "A"){
						$bg_shift = "bg-shift-biru";
					}
					if($arrData["4".$j.$tahun.$k] == "B"){
						$bg_shift = "bg-shift-pink";
					}
					if($arrData["4".$j.$tahun.$k] == "C"){
						$bg_shift = "bg-shift-hijau";
					}
					if($arrData["4".$j.$tahun.$k] == "D"){
						$bg_shift = "bg-shift-putih";
					}
?>  
<?
$html .= "		<td class='".$bg_shift."'>
                            <label id='libur-".$j."-".$k."'>".$arrData['4'.$j.$tahun.$k]."</label>
                            <input type='hidden' name='reqLibur-".$j."-".$k."' id='reqLibur-".$j."-".$k."' value='".$arrData['4'.$j.$tahun.$k]."'>
                </td>
           "
?>                             
<?								
                }
?>    
<?
$html .= "                          
            </tr>
		 "
?>
<?
        }
?>
<?
$html .="
    </tbody>
    </table>
</div> "
?>
<?
include_once("lib/MPDF60/mpdf.php");

$mpdf = new mPDF('c','LEGAL',0,'',15,15,16,16,9,9, 'L');
//$mpdf=new mPDF('c','A4'); 

$mpdf->SetDisplayMode('fullpage');

$mpdf->list_indent_first_level = 0;	// 1 or 0 - whether to indent the first level of a list

// LOAD a stylesheet
$stylesheet = file_get_contents(base_url().'css/laporan-jadwal-pdf.css');
$mpdf->WriteHTML($stylesheet,1);	// The parameter 1 tells that this is css/style only and no body/html/text

$mpdf->WriteHTML($html,2);

$mpdf->Output('form_jadwal_shift_pdf.pdf','I');
exit;
//==============================================================
//==============================================================
//==============================================================
?>