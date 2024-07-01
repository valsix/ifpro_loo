<?php
include_once("functions/date.func.php");
include_once("functions/default.func.php");
include_once("functions/string.func.php");
include_once("libraries/vendor/autoload.php");

$this->load->model("TrPsm");
$this->load->model("TrPsmDetil");
$this->load->model("Combo");
$this->load->model("LokasiLoo");

$reqId= $this->input->get("reqId");

if(empty($reqId)) $reqId= -1;

$arrlokasi= [];
$statement= " AND A.TR_PSM_ID = ".$reqId." AND VMODE ILIKE '%luas_sewa%'";
$set= new TrPsmDetil();
$set->selectlokasi(array(), -1,-1, $statement);
// echo $set->query;exit;
while($set->nextRow())
{
    $arrdata= [];
    $arrdata["trloidetilid"]= $set->getField("TR_PSM_DETIL_ID");
    $arrdata["trloiid"]= $set->getField("TR_PSM_ID");
    $arrdata["vmode"]= $set->getField("VMODE");
    $arrdata["vid"]= $set->getField("VID");
    $arrdata["vnilai"]= $set->getField("NILAI");
    $arrdata["kode"]= $set->getField("KODE");
    $arrdata["nama"]= $set->getField("NAMA");
    $arrdata["lantai"]= $set->getField("LANTAI");
    $arrdata["area"]= $set->getField("AREA");
    $arrdata["areanama"]= $set->getField("AREA_NAMA");
    array_push($arrlokasi, $arrdata);
}
// print_r($arrlokasi);exit;

$statement= " AND A.TR_PSM_ID = ".$reqId;
$set= new TrPsm();
$set->selectpsm(array(), -1,-1, $statement);
$set->firstRow();
$reqPenandaTanganNama= $set->getField("USER_PENGIRIM_NAMA");
$reqNamaArea= $set->getField("NAMA_AREA");
$reqTotalLuas= $set->getField("TOTAL_LUAS");
$reqTerletakArea= $set->getField("TERLETAK_AREA");
$reqLokasiGedung= $set->getField("LOKASI_GEDUNG");
$reqPerusahaanPenyewa= $set->getField("PERUSAHAAN_PENYEWA");
$reqKedudukanPenyewa= $set->getField("KEDUDUKAN_PENYEWA");
$reqVNomorSurat= $set->getField("V_NOMOR_SURAT");
$reqDasarHukum= $set->getField("DASAR_HUKUM");
$reqLoiNomor= $set->getField("LOI_NOMOR");
$reqLoiTanggal= getFormattedDateTimeCheck($set->getField("LOI_TANGGAL"), false);
$reqPicPenandatangan= $set->getField("PIC_PENANDATANGAN");
$reqJabatanPenandatangan= $set->getField("JABATAN_PENANDATANGAN");
$reqPenandaTanganNama= $set->getField("USER_PENGIRIM_NAMA");
$reqPenandaTanganJabatan= $set->getField("USER_PENGIRIM_JABATAN");

$reqSaksiNama= $set->getField("SAKSI_NAMA");
$reqSaksiJabatan= $set->getField("SAKSI_JABATAN");
$reqSaksiPenyewaNama= $set->getField("SAKSI_PENYEWA_NAMA");
$reqSaksiPenyewaJabatan= $set->getField("SAKSI_PENYEWA_JABATAN");

$arrpasal= array(
  array("kode"=>"I", "isi"=>'PT. INDONESIA FERRY PROPERTI suatu perseroan terbatas berkedudukan di Jakarta Pusat, beralamat di Gedung ASDP Indonesia Ferry (Persero) Jalan Jenderal Ahmad Yani Kav 52A yang didirikan berdasarkan hukum Negara Republik Indonesia berdasarkan '.$reqDasarHukum.', dalam hal ini diwakili oleh <b>'.strtoupper($reqPenandaTanganNama).'</b> bertindak dalam jabatannya selaku Direktur Perseroan dari dan oleh karena itu sah bertindak untuk dan atas nama <b>PT INDONESIA FERRY PROPERTI</b>, Selanjutnya disebut <b>"Yang Menyewakan";</b>')
  , array("kode"=>"II", "isi"=>$reqKedudukanPenyewa)
);

$arrdetil2= array(
  array("kode"=>"1", "isi"=>'Bahwa <b>Yang Menyewakan</b> adalah suatu perusahaan yang bergerak dibidang Properti, yang telah mengembangkan dan/atau mengelola suatu bangunan pusat perbelanjaan setempat dikenal dengan nama <b>Area Komersial</b> <b>'.$reqPerusahaanPenyewa.'</b>, yang terletak di '.$reqTerletakArea.', untuk selanjutnya disebut <b>"Gedung".</b>')
  , array("kode"=>"2", "isi"=>"Bahwa <b>Yang Menyewakan</b> bermaksud untuk menyewakan sebagian tempat/ruang di dalam Gedung kepada <b>Penyewa</b> dan <b>Penyewa</b> bermaksud untuk menyewa sebagian tempat/ruang tersebut dari <b>Yang Menyewakan.</b>")
  , array("kode"=>"3", "isi"=>"Bahwa berdasarkan Butir 1 dan 2 tersebut di atas, dengan ini <b>Para Pihak</b> bermaksud untuk mengadakan Perjanjian Inti sehubungan dengan sewa-menyewa tempat/ruang dengan syarat-syarat dan kondisi-kondisi yang ditentukan dalam pasal-pasal yang diuraikan lebih lanjut di bawah ini dan dalam lampiran-lampiran yang menjadi bagian dari Perjanjian Inti ini.")
  , array("kode"=>"4", "isi"=>"Bahwa <b>Penyewa</b> setuju akan mengikatkan diri untuk mengikuti dan menaati seluruh Perjanjian Inti berikut lampiran-lampirannya yang merupakan bagian yang tidak terpisahkan dari Perjanjian ini serta mengikat kepada siapa pun yang menggantikan kedudukan <b>Penyewa.</b>")
);

$arrdetilpasal1= array(
  array("kode"=>"1", "isi"=>"<b>Perjanjian Inti</b> adalah suatu perjanjian sewa menyewa yang dilakukan oleh <b>Yang Menyewakan<b/> dan <b>Penyewa</b> mengenai suatu area di dalam Gedung dengan ketentuan-ketentuan yang diatur di dalamnya beserta lampiran-lampirannya.")
  , array("kode"=>"2", "isi"=>"<b>Gedung</b> adalah Suatu Bangunan yang berlokasi di ".$reqLokasiGedung)
  , array("kode"=>"3", "isi"=>"<b>Area Komersial ".$reqNamaArea."</b> adalah nama Gedung tersebut.")
  , array("kode"=>"4", "isi"=>"<b>Yang Menyewakan</b> adalah Pihak yang memiliki Gedung termasuk ruang sewa di dalamnya dan bergerak dibidang Properti.")
  , array("kode"=>"5", "isi"=>"<b>Penyewa</b> adalah badan hukum maupun perorangan yang menyewa area sewa/ruang sewa di dalam Gedung untuk menjalankan usahanya di dalam Gedung.")
  , array("kode"=>"6", "isi"=>"<b>Objek Sewa</b> adalah suatu ruang atau area di dalam Gedung yang disewakan oleh <b>Yang Menyewakan</b> kepada <b>Penyewa</b> dengan cara pengukuran dari as ke as termasuk kolom di dalamnya.")
  , array("kode"=>"7", "isi"=>"<b>Jangka Waktu Sewa</b> adalah lamanya masa/periode waktu pihak <b>Penyewa</b> yang menyewa dan menguasai Objek Sewa dari pihak <b>Yang Menyewakan.</b>")
  , array("kode"=>"8", "isi"=>"<b>Tanggal Mulai Sewa</b> adalah tanggal dimana <b>Penyewa</b> mulai membuka dan menjalankan usahanya di dalam Objek Sewa sekaligus tanggal dimulainya semua hak dan kewajiban <b>Penyewa</b> dan <b>Yang Menyewakan.</b>")
  , array("kode"=>"9", "isi"=>"<b>Harga Sewa</b> adalah sejumlah uang sebagai kompensasi pembayaran dari <b>Penyewa</b> kepada <b>Yang Menyewakan</b> atas penggunaan Objek Sewa.")
  , array("kode"=>"10", "isi"=>"<b>Biaya Pelayanan</b> adalah sejumlah uang yang wajib dibayarkan oleh <b>Penyewa</b> sebagai kompensasi pembayaran atas fasilitas-fasilitas gedung yang disediakan untuk <b>Penyewa</b> dan <b>Penyewa</b> lain atau pengunjung secara umum.")
  , array("kode"=>"11", "isi"=>"<b>Pajak</b> adalah sejumlah uang yang wajib disetorkan oleh Wajib Pajak kepada Pemerintah Daerah maupun nasional sebagaimana yang ditentukan oleh Undang-undang dan ketentuan-ketentuan pengikutnya berkaitan dengan sewa menyewa ruang.")
  , array("kode"=>"12", "isi"=>"<b>Asuransi</b> pihak <b>Penyewa</b> adalah pertanggungan yang wajib dilakukan oleh Penyewa terhadap risiko-risiko yang mungkin timbul di dalam Objek Sewa termasuk risiko yang mungkin timbul untuk pengunjung tempat usaha <b>Penyewa</b> selama Jangka Waktu Sewa.")
  , array("kode"=>"13", "isi"=>"<b>Asuransi</b> pihak <b>Yang Menyewakan</b> adalah pertanggungan yang wajib dilakukan oleh <b>Yang Menyewakan</b> terhadap risiko yang mungkin timbul untuk Gedung secara umum termasuk terhadap risiko yang mungkin timbul untuk pengunjung di area publik.")
  , array("kode"=>"14", "isi"=>"<b>Fasilitas</b> adalah prasarana yang disediakan <b>Yang Menyewakan</b> kepada seluruh <b>Penyewa</b> maupun pengunjung berupa penerangan di area publik, eskalator, AHU, ducting AC termasuk Chiller, toilet, air bersih dan saluran pembuangan air kotor, tangga darurat.")
  , array("kode"=>"15", "isi"=>"<b>Building Management</b> adalah pengelola operasional gedung sehari-hari yang dibentuk oleh <b>Yang Menyewakan</b> dan bertindak serta mewakili <b>Yang Menyewakan.</b>")
  , array("kode"=>"16", "isi"=>"<b>Pemeliharaan Gedung</b> adalah kegiatan Building Management untuk melakukan pemeliharaan gedung sehari-hari mencakup Perbaikan dan Pemeliharaan terhadap semua fasilitas umum yang disediakan untuk seluruh <b>Penyewa</b> maupun pengunjung.")
  , array("kode"=>"17", "isi"=>"<b>Pelayanan Kebersihan</b> adalah kegiatan menjaga kebersihan pihak yang ditunjuk oleh <b>Yang Menyewakan</b> yang berdiri sendiri untuk memelihara kebersihan di seluruh area publik.")
  , array("kode"=>"18", "isi"=>"<b>BAST</b> adalah Berita Acara Serah Terima Lokasi Sewa.")
  , array("kode"=>"19", "isi"=>"<b>Fitting Out</b> adalah kegiatan renovasi lokasi sewa yang diinginkan oleh <b>Penyewa</b> yang telah mendapat persetujuan atas rancangan (desain) oleh <b>Yang Menyewakan.</b>")
  , array("kode"=>"20", "isi"=>"<b>Keamanan</b> adalah kegiatan penjagaan terhadap pencurian, pengerusakan maupun hal-hal lainnya yang dilaksanakan oleh pihak yang ditunjuk oleh <b>Yang Menyewakan</b> untuk menjaga keamanan di seluruh area publik baik pada jam operasional maupun di luar jam operasional.")
  , array("kode"=>"21", "isi"=>"<b>Biaya Lain-lain</b> adalah segala biaya yang timbul berkenaan dengan fasilitas pendukung kegiatan Operasional <b>Penyewa</b> seperti namun tidak terbatas biaya listrik, air, internet, TIK, Gas dan lainnya.")
  , array("kode"=>"22", "isi"=>"<b>Letter Of Intent</b> adalah Perjanjian pendahuluan tertanggal <b>".$reqLoiTanggal."</b> Nomor <b>".$reqLoiNomor.".</b>")
);

$arrdetilpasal3= array(
  array("kode"=>"1", "isi"=>'Objek Sewa diperuntukkan sebagai kegiatan/bentuk usaha <b>Penyewa</b> yaitu sebagai tempat untuk jenis kegiatan usaha Retail untuk Nama Usaha / Merek Dagang <b>'.$reqPerusahaanPenyewa.'.</b>')
  , array("kode"=>"2", "isi"=>'<b>Penyewa</b> tidak diperbolehkan mengubah peruntukan Objek Sewa dan/atau nama usaha dan/atau merek dagang yang digunakan <b>Penyewa</b> tanpa memperoleh persetujuan tertulis terlebih dahulu dari pihak <b>Yang Menyewakan.</b>')
  , array("kode"=>"3", "isi"=>'<b>Penyewa</b> hanya berhak mempergunakan Lokasi Sewa untuk usaha sebagaimana dimaksud dalam ayat 1 pasal ini, serta tidak bertentangan dengan undang-undang negara Republik Indonesia, kesusilaan, ketertiban umum, kebersihan, dan kesehatan dan karenanya <b>Penyewa</b> menjamin dan membebaskan <b>Yang Menyewakan</b>, Building Management dan/atau dari segala tuntutan, tagihan atau gugatan dari pihak mana pun berkaitan dengan kegiatan usaha yang dilakukan <b>Penyewa</b> termasuk tetapi tidak terbatas pada barang yang diperjualbelikan maupun dipamerkan oleh <b>Penyewa.</b>')
  , array("kode"=>"4", "isi"=>'Penyewa wajib memperhatikan dan mematuhi hari dan jam buka tutup sesuai ketentuan yang ditetapkan oleh Building Management.')
);

$arrdetilpasal7= array(
  array("kode"=>"1", "isi"=>'<b>Penyewa</b> wajib membayar Biaya Pelayanan kepada <b>Yang Menyewakan</b> sebesar <b>[mohon diisi]</b> dan sudah termasuk PPN <b>[mohon diisi]</b> tanpa mengurangi ketentuan dan syarat sebagaimana diatur dalam pasal 4 Ayat 1 Lampiran V, sehingga total Biaya Pelayanan <b>[mohon diisi]</b> sebesar Rp. <b>[mohon diisi]</b>  dan sudah termasuk PPN <b>[mohon diisi].</b>')
  , array("kode"=>"2", "isi"=>'Terhadap Biaya Pelayanan diatas untuk sisa masa sewa selanjutnya, sewaktu-waktu dapat berubah mengikuti kebijakan dan ketentuan yang berlaku.')
);

$arrdetilpasal9= array(
  array("kode"=>"1", "isi"=>'Terhadap biaya lain-lain yang timbul, <b>Penyewa</b> membayarkan atas biaya tersebut setiap tanggal 10 setiap bulan;')
  , array("kode"=>"2", "isi"=>'Apabila terhadap biaya lain-lain tersebut terdapat keterlambatan pembayaran oleh <b>Penyewa</b>, <b>Yang Menyewakan</b> berhak atas denda keterlambatan sebesar 0.1% (nol koma satu persen) per hari dan dibulatkan menjadi 5% per bulan apabila denda sudah melewati lebih dari 30 (tiga puluh).')
);

$arrdetilpasal10= array(
  array("kode"=>"1", "isi"=>'<b>Yang Menyewakan</b> dan <b>Penyewa</b> sepakat untuk menggunakan Hukum Negara Republik Indonesia  sebagai dasar hukum di dalam Perjanjian ini dengan segala akibatnya.')
  , array("kode"=>"2", "isi"=>'Apabila timbul perselisihan di dalam  masa sewa, maka Para Pihak sepakat untuk menyelesaikannya secara musyawarah untuk mencapai mufakat.')
  , array("kode"=>"3", "isi"=>'Apabila ternyata tidak tercapai kesepakatan, maka Para Pihak sepakat untuk menyerahkan penyelesaiannya kepada Pengadilan Negeri Jakarta Pusat.')
);


$arrdetilpasal11= array(
  array("kode"=>"1", "isi"=>'Pihak <b>Yang Menyewakan</b> merupakan pemilik/memiliki kewenangan untuk menyewakan Objek Sewa dan melepaskan <b>Penyewa</b> dari seluruh tuntutan, klaim, keberatan dan gugatan dari pihak mana pun juga terkait dengan penggunaan Objek Sewa oleh <b>Penyewa;</b>')
  , array("kode"=>"2", "isi"=>'<b>Para Pihak</b> menjamin bahwa penandatanganan Perjanjian Sewa telah memenuhi ketentuan internal maupun eksternal dari masing-masing Pihak.')
  , array("kode"=>"3", "isi"=>'Pihak <b>Yang Menyewakan</b> menjamin bahwa <b>Penyewa</b> berhak untuk melanjutkan sewanya dengan persyaratan dan kondisi telah disepakati dalam Perjanjian Sewa apabila di masa yang akan datang terjadi perpindahan baik secara sukarela atau tidak.')
);

$arrdetilpasal12= array(
  array("kode"=>"1", "isi"=>'<b>Penyewa</b> menyetujui bahwa <b>Yang Menyewakan</b> berhak untuk (tetapi tidak wajib) mengiklankan atau mempublikasikan atau dilain pihak <b>Penyewa</b> sebagai <b>Penyewa</b> dari <b>Yang Menyewakan</b> dan atau Properti yang terkait, untuk mencantumkan atau menggabungkan atau sehubungan dengan nama usaha, logo, merek jasa dan atau merek dagang pada setiap materi iklan dan promosi dalam kaitannya dengan properti.')
  , array("kode"=>"2", "isi"=>'Hal-hal yang belum diatur atau belum cukup diatur di dalam Perjanjian ini dan atas kesepakatan kedua belah pihak akan diatur di dalam suatu Addendum dan atau Amandemen dan merupakan satu kesatuan yang tidak terpisahkan dari Perjanjian ini.')
  , array("kode"=>"3", "isi"=>'Hal-hal yang lebih detail dan merupakan ketentuan-ketentuan lebih khusus berkaitan dengan pasal per pasal di dalam Perjanjian Inti ini dituangkan di dalam lampiran-lampiran yang mengikuti Perjanjian Inti, Lampiran-lampiran dimaksud terdiri dari :<br/><br/>a.  Lampiran I - Denah/Gambar Tata Letak<br/>b.  Lampiran II - Rincian Pembayaran Sewa dan Uang Muka')
  , array("kode"=>"4", "isi"=>'Bahwa dengan ditandatanganinya Perjanjian Inti beserta lampiran-lampirannya, maka segala persyaratan dan ketentuan yang tercantum dalam Perjanjian, telah dibaca dan dimengerti dengan benar oleh Para Pihak.')
);
// print_r($arrdetilpasal12);exit;

$vinfolampiran= $arrdetilpasal12[2]["isi"];
// $vinfolampiran.= "<br/>c.  Lampiran III - Spesifikasi Objek Sewa dan Fitting Out";;
// $vinfolampiran.= "<br/>d.  Lampiran IV - Biaya Pelayanan";
// $vinfolampiran.= "<br/>e.  Lampiran V - Ketentuan - ketentuan Sewa Menyewa";
// $vinfolampiran.= "<br/>f.  Lampiran VI - Peraturan Gedung dan Tata Tertib Usaha";
$vinfolampiran.= "<br/><br/>seluruh lampiran-lampiran sebagaimana tersebut di atas yang mengikuti Perjanjian Inti merupakan satu kesatuan yang tidak terpisahkan dari Perjanjian Inti dan merupakan ketentuan yang bersifat lebih terperinci, dan oleh karenanya Para Pihak wajib taat, tunduk dan patuh pada seluruh ketentuan-ketentuan yang tercantum di dalamnya.";

$arrdetilpasal12[2]["isi"]= $vinfolampiran;
?>
<base href="<?=base_url();?>">
<link href="css/gaya-surat.css" rel="stylesheet" type="text/css">
<link href="lib/froala_editor_2.9.8/css/froala_style.css" rel="stylesheet" type="text/css">
<style>
  body{
  }

  tr.group, td.group{
    background-color:#F8F7F7
  }

  tr.border, td.border{
    border: 1px solid black;
    padding: 5px;
  }

  td.cntr{
    text-align:center;
  }

  td.rgt{
    text-align:right;
    padding-right: 15px;
  }
</style>
<body>
  <table style="width: 100%;" border="0">
    <tr>
      <td colspan="3">
        <img src="<?='images/logo.png'?>" height="100px">
      </td>
    </tr>
    <tr>
      <td colspan="3" class="cntr">
        <b>PERJANJIAN SEWA MENYEWA
        <br/><?=strtoupper($reqPerusahaanPenyewa)?>
        <br/>AREA KOMERSIAL <?=strtoupper($reqNamaArea)?>
        <br/>Nomor : <?=$reqVNomorSurat?></b>
      </td>
    </tr>
    <tr>
      <td colspan="3"><hr width="100%" height="2px"></td>
    </tr>
    <tr>
      <td colspan="3">
        Perjanjian Sewa Menyewa, ditandatangani, dan diberlakukan di Jakarta, pada hari ini, [mohon diisi], tanggal <b>[mohon diisi]</b>, bulan <b>[mohon diisi]</b>, tahun <b>[mohon diisi]</b> (<b>[mohon diisi]</b>), oleh dan antara :
      </td>
    </tr>
    <tr>
      <td style="width: 2%"><?=$arrpasal[0]["kode"]?></td>
      <td colspan="2" style="text-align: justify;"><?=$arrpasal[0]["isi"]?></td>
    </tr>
    <tr>
      <td></td>
      <td colspan="2" style="text-align: center;"><b>DAN</b></td>
    </tr>
    <tr>
      <td><?=$arrpasal[1]["kode"]?></td>
      <td colspan="2" style="text-align: justify;"><?=$arrpasal[1]["isi"]?></td>
    </tr>
    <tr>
      <td></td>
      <td colspan="2" style="text-align: justify;">
        <b>Yang Menyewakan</b> dan <b>Penyewa</b> untuk selanjutnya secara bersama-sama disebut "Para Pihak" dan secara sendiri-sendiri disebut <b>"Pihak".</b>
        Para Pihak setuju dan sepakat untuk mengikatkan diri dalam suatu perjanjian sewa menyewa untuk kemudian disebut <b>"Perjanjian Inti"</b> dan Para Pihak menerangkan terlebih dahulu hal-hal sebagai berikut :
      </td>
    </tr>
    <?
    foreach ($arrdetil2 as $k => $v)
    {
    ?>
    <tr>
      <td></td>
      <td style="width: 2%"><?=$v["kode"]?></td>
      <td style="text-align: justify;"><?=$v["isi"]?></td>
    </tr>
    <?
    }
    ?>
    <tr>
      <td></td>
      <td colspan="2" style="text-align: justify;">
        Berdasarkan hal-hal tersebut di atas, Para Pihak sepakat untuk mengadakan Perjanjian Inti dengan ketentuan-ketentuan sebagai berikut:
      </td>
    </tr>
    <tr>
      <td></td>
      <td colspan="2" style="text-align: center;">
        <br/><b>Pasal 1<br/>Definisi - definisi<b>
      </td>
    </tr>
    <tr>
      <td></td>
      <td colspan="2" style="text-align: justify;">
        Definisi istilah-istilah yang digunakan di dalam Perjanjian Inti maupun lampiran-lampirannya adalah sebagai berikut :
      </td>
    </tr>
    <?
    foreach ($arrdetilpasal1 as $k => $v)
    {
    ?>
    <tr>
      <td></td>
      <td style="width: 2%"><?=$v["kode"]?></td>
      <td style="text-align: justify;"><?=$v["isi"]?></td>
    </tr>
    <?
    }
    ?>
    <tr>
      <td></td>
      <td colspan="2" style="text-align: center;">
        <br/><b>Pasal 2<br/>Objek Sewa<b>
      </td>
    </tr>
    <tr>
      <td></td>
      <td colspan="2" style="text-align: justify;">
        <?
        if(count($arrlokasi) == 1)
        {
          $arrlokasidetil= $arrlokasi[0];
        ?>
        <b>Penyewa</b> setuju untuk menyewa kepada <b>Yang Menyewakan</b> dan <b>Yang Menyewakan</b> juga setuju untuk menyewakan kepada <b>Penyewa</b> sebagian tempat/ruang yang terletak dalam Gedung, di <b><?=$arrlokasidetil["lantai"]?></b>, dengan luas tempat/ruang <b><?=$reqTotalLuas?> m<sup>2</sup></b> <b>(<?=kekata($reqTotalLuas)?> meter persegi)</b> sebagaimana tercantum di dalam Lampiran I, untuk selanjutnya disebut juga sebagai <b>"Objek Sewa".</b>
        <?
        }
        else
        {
        ?>
        <b>Penyewa</b> setuju untuk menyewa kepada <b>Yang Menyewakan</b> dan <b>Yang Menyewakan</b> juga setuju untuk menyewakan kepada <b>Penyewa</b> sebagian tempat/ruang yang terletak dalam Gedung
        <?
        $vconcat= "";
        $vconcatdetil= '<table style="width: 80%; margin-left:100px;" border="0">';

        foreach ($arrlokasi as $kd => $vd) {
          $vinfodata= $vd["lantai"];
          if(isStrContain($vconcat, $vinfodata)){}
          else
          {
            if(empty($vconcat))
              $vconcat= $vinfodata;
            else
              $vconcat= $vconcat.", ".$vinfodata;
          }

          $vconcatdetil.= '<tr><td style="width:20%"> - '.$vd["kode"].' </td><td style="width:30px">:</td><td>'.$vd["vnilai"].' m<sup>2</sup></td></tr>';
        }
        $vconcatdetil.= '</table>';
        ?>
        <?=$vconcat?>
        dengan rincian sebagai berikut
        <?=$vconcatdetil?>
        Sehingga total luas tempat/ruang Sewa menjadi <b><?=$reqTotalLuas?> m<sup>2</sup></b> <b>(<?=kekata($reqTotalLuas)?> meter persegi)</b> sebagaimana tercantum di dalam Lampiran I, untuk selanjutnya disebut juga sebagai <b>"Obyek Sewa".</b>
        <?
        }
        ?>
      </td>
    </tr>
    <tr>
      <td></td>
      <td colspan="2" style="text-align: center;">
        <br/><b>Pasal 3<br/>Peruntukan Objek Sewa<b>
      </td>
    </tr>
    <?
    foreach ($arrdetilpasal3 as $k => $v)
    {
    ?>
    <tr>
      <td></td>
      <td style="width: 2%"><?=$v["kode"]?></td>
      <td style="text-align: justify;"><?=$v["isi"]?></td>
    </tr>
    <?
    }
    ?>
    <tr>
      <td></td>
      <td colspan="2" style="text-align: center;">
        <br/><b>Pasal 4<br/>Jangka Waktu Sewa<b>
      </td>
    </tr>
    <tr>
      <td></td>
      <td colspan="2" style="text-align: justify;">
        Periode sewa-menyewa yang disepakati oleh Para Pihak adalah selama <b>[mohon diisi] Tahun</b> terhitung sejak tanggal <b>[mohon diisi]</b> sampai dengan tanggal <b>[mohon diisi]</b>, selanjutnya disebut <b>Jangka Waktu Sewa.</b>
      </td>
    </tr>
    <tr>
      <td></td>
      <td colspan="2" style="text-align: center;">
        <br/><b>Pasal 5<br/>Uang Sewa<b>
      </td>
    </tr>
    <tr>
      <td></td>
      <td colspan="2" style="text-align: justify;">
        Uang sewa dari Objek Sewa yang disepakati bersama oleh Para Pihak adalah <b>[mohon diisi] dan sudah termasuk PPN [mohon diisi]</b>, selanjutnya disebut "Total Harga Sewa".
      </td>
    </tr>
    <tr>
      <td></td>
      <td colspan="2" style="text-align: center;">
        <br/><b>Pasal 6<br/>Jaminan Sewa<b>
      </td>
    </tr>
    <tr>
      <td></td>
      <td colspan="2" style="text-align: justify;">
        Penyewa wajib membayar Jaminan Sewa kepada <b>Yang Menyewakan</b> sebesar <b>Rp [mohon diisi]</b>. Pembayaran tersebut dilaksanakan sekaligus di muka bersamaan dengan pembayaran <b>[mohon diisi]</b> sehingga dibayarkan paling lama <b>[mohon diisi]</b>, pembayaran mana yang dibuatkan suatu bukti pembayaran yang sah (kuitansi) tersendiri.
      </td>
    </tr>
    <tr>
      <td></td>
      <td colspan="2" style="text-align: center;">
        <br/><b>Pasal 7<br/>Biaya Pelayanan (Service Charge)<b>
      </td>
    </tr>
    <?
    foreach ($arrdetilpasal7 as $k => $v)
    {
    ?>
    <tr>
      <td></td>
      <td style="width: 2%"><?=$v["kode"]?></td>
      <td style="text-align: justify;"><?=$v["isi"]?></td>
    </tr>
    <?
    }
    ?>
    <tr>
      <td></td>
      <td colspan="2" style="text-align: center;">
        <br/><b>Pasal 8<br/>Cara Pembayaran<b>
      </td>
    </tr>
    <tr>
      <td></td>
      <td colspan="2" style="text-align: justify;">
        <b>Penyewa</b> wajib melakukan pembayaran [mohon diisi] kepada <b>Yang Menyewakan</b> dan dibayarkan sekaligus setelah <i>Letter Of Intent</i> ditandatangani oleh Para Pihak atau paling lama dibayarkan saat awal sewa.
      </td>
    </tr>
    <tr>
      <td></td>
      <td colspan="2" style="text-align: center;">
        <br/><b>Pasal 9<br/>Pembayaran Biaya Lain-lain<b>
      </td>
    </tr>
    <?
    foreach ($arrdetilpasal9 as $k => $v)
    {
    ?>
    <tr>
      <td></td>
      <td style="width: 2%"><?=$v["kode"]?></td>
      <td style="text-align: justify;"><?=$v["isi"]?></td>
    </tr>
    <?
    }
    ?>
    <tr>
      <td></td>
      <td colspan="2" style="text-align: center;">
        <br/><b>Pasal 10<br/>Pilihan Hukum dan Penyelesaian Perselisihan<b>
      </td>
    </tr>
    <?
    foreach ($arrdetilpasal10 as $k => $v)
    {
    ?>
    <tr>
      <td></td>
      <td style="width: 2%"><?=$v["kode"]?></td>
      <td style="text-align: justify;"><?=$v["isi"]?></td>
    </tr>
    <?
    }
    ?>
    <tr>
      <td></td>
      <td colspan="2" style="text-align: center;">
        <br/><b>Pasal 11<br/>Pernyataan dan Jaminan<b>
      </td>
    </tr>
    <?
    foreach ($arrdetilpasal11 as $k => $v)
    {
    ?>
    <tr>
      <td></td>
      <td style="width: 2%"><?=$v["kode"]?></td>
      <td style="text-align: justify;"><?=$v["isi"]?></td>
    </tr>
    <?
    }
    ?>
    <tr>
      <td></td>
      <td colspan="2" style="text-align: center;">
        <br/><b>Pasal 12<br/>Ketentuan Lain-lain<b>
      </td>
    </tr>
    <?
    foreach ($arrdetilpasal12 as $k => $v)
    {
    ?>
    <tr>
      <td></td>
      <td style="width: 2%"><?=$v["kode"]?></td>
      <td style="text-align: justify;"><?=$v["isi"]?></td>
    </tr>
    <?
    }
    ?>
    <tr>
      <td></td>
      <td colspan="2" style="text-align: justify;">
        Demikianlah Perjanjian Inti dan lampiran-lampirannya dibuat oleh Para Pihak dalam rangkap dua serta bermeterai cukup yang masing-masing mempunyai kekuatan hukum yang sama, mulai berlaku sejak tanggal di awal Perjanjian ini.
      </td>
    </tr>
  </table>

  <br>
  <br>
  <table style="width:100%" border="0">
    <tr>
      <td style="width:30%;text-align: center;">
        <b>PENYEWA,</b>
      </td>
      <td style="width:40%"></td>
      <td style="width:30%;text-align: center;">
        <b>YANG MENYEWAKAN,</b>
      </td>
    </tr>
    <tr>
      <td style="width:30%;text-align: center;">
        <b><?=$reqPerusahaanPenyewa?></b>
      </td>
      <td style="width:40%"></td>
      <td style="width:30%;text-align: center;">
        <b>PT INDONESIA FERRY PROPERTI</b>
      </td>
    </tr>
    <tr>
      <td><br></td>
    </tr>
    <tr>
      <td><br></td>
    </tr>
    <tr>
      <td><br></td>
    </tr>
    <tr>
      <td><br></td>
    </tr>
    <tr>
      <td style="width:30%;text-align: center;">
        <b><?=$reqPicPenandatangan?></b>
      </td>
      <td style="width:40%"></td>
      <td style="width:30%;text-align: center;">
        <u><b><?=$reqPenandaTanganNama?></b></u>                       
      </td>
    </tr>
    <tr>
      <td style="width:30%;text-align: center;">
        <?=$reqJabatanPenandatangan?>
      </td>
      <td style="width:40%"></td>
      <td style="width:30%;text-align: center;">
        <?=$reqPenandaTanganJabatan?>
      </td>
    </tr>
    <tr>
      <td><br/></td>
    </tr>
    <tr>
      <td style="width:30%;text-align: center;">
        <?
        if(!empty($reqSaksiPenyewaNama) || !empty($reqSaksiPenyewaJabatan))
        {
        ?>
        <b>Saksi Yang Penyewa:</b>
        <?
        }
        ?>
      </td>
      <td style="width:40%"></td>
      <td style="width:30%;text-align: center;">
        <b>Saksi Yang Menyewakan:</b>
      </td>
    </tr>
    <tr>
      <td><br/><br/><br/><br/></td>
    </tr>
    <tr>
      <td style="width:30%;text-align: center;">
        <u><b><?=$reqSaksiPenyewaNama?></b></u>
      </td>
      <td style="width:40%"></td>
      <td style="width:30%;text-align: center;">
        <u><b><?=$reqSaksiNama?></b></u>
      </td>
    </tr>
    <tr>
      <td style="width:30%;text-align: center;">
        <?=$reqSaksiPenyewaJabatan?>
      </td>
      <td style="width:40%"></td>
      <td style="width:30%;text-align: center;">
        <?=$reqSaksiJabatan?>
      </td>
    </tr>

  </table>

</body>