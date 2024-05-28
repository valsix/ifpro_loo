<?php
/* INCLUDE FILE */
include_once("functions/date.func.php");
include_once("functions/default.func.php");
include_once("functions/string.func.php");
include_once("libraries/vendor/autoload.php");
?>
<link href="<?= base_url() ?>css/gaya-surat.css" rel="stylesheet" type="text/css">
<link href="<?= base_url() ?>lib/froala_editor_2.9.8/css/froala_style.css" rel="stylesheet" type="text/css">
<style>
  body{
/*      background-image:url('<?= base_url() ?>images/bg_cetak.jpg')  ;
      background-image-resize:6;
      background-size: cover;*/
  }
  td{
    padding-right: 5px;
    padding-left: 5px;
  }
</style>
<body>
  <!-- head -->
  <table style="width: 100%;">
    <tr>
      <td colspan="7">
        <img src="<?=base_url().'images/logo.png'?>" height="100px">
      </td>
    </tr>
    <tr>
      <td style="text-align:center;" colspan="7">
        <h1><b><u>LETTER OF INTENT</u></b></h1>
      </td>
    </tr>
    <tr>
      <td style="width:10%">NO</td>
      <td style="width:1%">:</td>
      <td style="width:19%"></td>
      <td style="width:45%"></td>
      <td style="width:10%">Lampiran  </td>
      <td style="width:1%">:</td>
      <td style="width:14%;text-align: right;">2 (dua) Halaman</td>
    </tr>
    <tr>
      <td>TANGGAL</td>
      <td>:</td>
      <td></td>
      <td></td>
      <td></td>
    </tr>
    <tr>
      <td><br></td>
    </tr>
    <tr>
      <td colspan=7>
        SOSORO MALL - MERAK / ANJUNGAN AGUNG MALL - BAKAUHENI / PLAZA MARINA - LABUAN BAJO
      </td>
    </tr>
    <tr>
      <td><br></td>
    </tr>
    <tr>
      <td colspan="3">Kepada Yth.</td>
    </tr>
    <tr>
      <td colspan="3">.....................</td>
    </tr>
    <tr>
      <td colspan="3">JABATAN</td>
    </tr>
    <tr>
      <td colspan="3">CV / PT / BRAND</td>
    </tr>
    <tr>
      <td colspan="3">di Tempat</td>
    </tr>
  </table>
  <br>
  <!-- body -->
  <table style="width: 100%;">
    <tr>
      <td colspan="5">Salam hangat dari PT Indonesia Ferry Properti.</td>
    </tr>
    <tr>
      <td colspan="5">Bersama ini kami sampaikan Letter of Intent untuk lokasi sewa di Area Komersial Sosoro Mall Merak / Anjungan Agung Mall Bakauheni / Plaza Marina Labuan Bajo dengan syarat dan kondisi sebagai berikut:                                 </td>
    </tr>
    <tr>
      <td><br></td>
    </tr>
    <tr>
      <td style="width:3%"><b>1</b></td>
      <td colspan="5" ><b>PENYEWA</b></td>
    </tr>
    <tr>
      <td></td>
      <td style="width:5%">1.1</td>
      <td style="width:15%">Nama</td>
      <td style="width:3%">:</td>
      <td>......................</td>
    </tr>
    <tr>
      <td></td>
      <td style="width:3%">1.2</td>
      <td style="width:15%">Nomor NPWP</td>
      <td style="width:3%">:</td>
      <td>......................</td>
    </tr>
    <tr>
      <td></td>
      <td style="width:3%">1.3</td>
      <td style="width:15%">Alamat NPWP</td>
      <td style="width:3%">:</td>
      <td>......................</td>
    </tr>
    <tr>
      <td></td>
      <td style="width:3%">1.4</td>
      <td style="width:15%">Nomor NIORA</td>
      <td style="width:3%">:</td>
      <td>......................</td>
    </tr>
    <tr>
      <td></td>
      <td style="width:3%">1.5</td>
      <td style="width:15%">Alamat Domisili</td>
      <td style="width:3%">:</td>
      <td>......................</td>
    </tr>
    <tr>
      <td></td>
      <td style="width:3%">1.6</td>
      <td style="width:15%">Telepon</td>
      <td style="width:3%">:</td>
      <td>......................</td>
    </tr>
    <tr>
      <td><br></td>
    </tr>
    <tr>
      <td style="width:3%"><b>2</b></td>
      <td colspan="5" ><b>PERINCIAN UNIT</b></td>
    </tr>
    <tr>
      <td></td>
      <td style="width:3%">2.1</td>
      <td style="width:15%">Nama</td>
      <td style="width:3%">:</td>
      <td>Ground Floor</td>
    </tr>
    <tr>
      <td></td>
      <td style="width:3%"></td>
      <td style="width:15%"></td>
      <td style="width:3%"></td>
      <td>1st Floor</td>
    </tr>
    <tr>
      <td></td>
      <td style="width:3%">2.2</td>
      <td style="width:15%">Unit</td>
      <td style="width:3%">:</td>
      <td>01</td>
    </tr>
    <tr>
      <td></td>
      <td style="width:3%">2.3</td>
      <td style="width:15%">Luas</td>
      <td style="width:3%">:</td>
      <td>......................</td>
    </tr>
    <tr>
      <td><br></td>
    </tr>
    <tr>
      <td style="width:3%"><b>3</b></td>
      <td colspan="5" ><b>JANGKA WAKTU</b></td>
    </tr>
    <tr>
      <td></td>
      <td style="width:3%">3.1</td>
      <td style="width:15%">Jangka Waktu</td>
      <td style="width:3%">:</td>
      <td>24</td>
    </tr>
    <tr>
      <td></td>
      <td style="width:3%">3.2</td>
      <td style="width:15%">Tanggal Awal</td>
      <td style="width:3%">:</td>
      <td>......................</td>
    </tr>
    <tr>
      <td></td>
      <td style="width:3%">3.3</td>
      <td style="width:15%">Tanggal Akhir</td>
      <td style="width:3%">:</td>
      <td>......................</td>
    </tr>
    <tr>
      <td><br></td>
    </tr>
    <tr>
      <td style="width:3%"><b>4</b></td>
      <td colspan="5" ><b>HARGA SEWA</b></td>
    </tr>
    <tr>
      <td></td>
      <td style="width:3%">4.1</td>
      <td style="width:15%">Harga Sewa</td>
      <td style="width:3%">:</td>
      <td>
        <table>
          <tr>
            <td style="width:3%">Rp</td>
            <td style="width:20%">13381200</td>
            <td style="width:15%">/ m2 / bulan</td>
            <td style="width:15%" >(excl. PPN)</td>
            <td style="width:57%" ></td>
          </tr>
          <tr>
            <td colspan="2">Total harga sewa</td>
            <td >24 bulan</td>
          </tr>
          <tr>
            <td colspan="2">Sebesar</td>
            <td >Rp 356475168</td>
            <td >(inc. PPN)</td>
          </tr>
        </table>
      </td>
    </tr>
    <tr>
      <td></td>
      <td style="width:3%">4.2</td>
      <td style="width:15%">Service Charge</td>
      <td style="width:3%">:</td>
      <td>
        <table>
          <tr>
            <td style="width:3%">Rp</td>
            <td style="width:20%">23684724</td>
            <td style="width:15%">/ m2 / bulan</td>
            <td style="width:15%" >(excl. PPN)</td>
            <td style="width:57%" ></td>
          </tr>
          <tr>
            <td colspan="5">Total service charge 1 (satu) tahun pertama yaitu sebesar</td>
          </tr>
          <tr>
            <td colspan="2">Rp 356475168</td>
            <td ></td>
            <td >(inc. PPN)</td>
          </tr>
        </table>
      </td>
    </tr>
    <tr>
      <td><br></td>
    </tr>
    <tr>
      <td style="width:3%"><b>5</b></td>
      <td colspan="5" ><b>PROMOTION LEVY</b></td>
    </tr>
    <tr>
      <td></td>
      <td style="width:3%">5.1</td>
      <td colspan="3">
        <table style="width:100%">
          <tr>
            <td style="width:45%">Biaya yang dikenakan kepada pihak Penyewa sebesar </td>      
            <td style="width:3%">Rp</td>      
            <td style="width:20%"></td>      
            <td style="width:10%">/ m2 / bulan</td>
            <td></td>      
          </tr>
        </table>
      </td>
    </tr>
    <tr>
      <td style="width:3%"><b>6</b></td>
      <td colspan="5" ><b>FASILITAS PENERANGAN, GAS DAN AIR</b></td>
    </tr>
    <tr>
      <td></td>
      <td style="width:3%">6.1</td>
      <td colspan="3">
        Penerangan untuk unit sewa menggunakan fasilitas listrik yang disediakan oleh Pemilik dengan daya listrik sebesar 180 VA/m2 yang wajib dibayarkan setiap bulannya sesuai pemakaian*) dan biaya rekening minimum sebesar (belum termasuk PPN). Apabila Penyewa memerlukan daya listrik melebihi dari yang ditentukan, maka Penyewa wajib membayar kelebihan daya listrik tersebut sesuai dengan ketentuan dan standar biaya yang telah ditetapkan oleh Pemilik.<br>
        *) Biaya listrik sewaktu waktu dapat berubah sesuai dengan ketentuan yang ditetapkan oleh Pemilik.      
      </td>
    </tr>
    <tr>
      <td></td>
      <td style="width:3%">6.2</td>
      <td colspan="3">
        Pemilik menyediakan jaringan bahan bakar gas dan menjamin ketersediaan BBG (Bahan Bakar Gas) setiap hari. Penyewa wajib membayarkan tagihan setiap bulannya sesuai pemakaian*) dan biaya rekening minimum (belum termasuk PPN 11%).<br>
        *) Biaya BBG sewaktu waktu dapat berubah sesuai dengan ketentuan yang ditetapkan oleh Pemilik.
      </td>
    </tr>
    <tr>
      <td></td>
      <td style="width:3%">6.3</td>
      <td colspan="3">
        Pemilik menyediakan jaringan saluran air bersih dan menjamin ketersediaan air bersih setiap hari. Penyewa wajib membayarkan tagihan setiap bulannya sesuai pemakaian*) dan biaya rekening minimum (belum termasuk PPN 11%).<br>
        *) Biaya air bersih sewaktu waktu dapat berubah sesuai dengan ketentuan yang ditetapkan oleh Pemilik
      </td>
    </tr>
    <tr>
      <td><br></td>
    </tr>
    <tr>
      <td style="width:3%"><b>7</b></td>
      <td colspan="5" ><b>FASILITAS LINE TELEPON DAN INTERNET</b></td>
    </tr>
    <tr>
      <td></td>
      <td colspan="5">
        Pemilik tidak menyediakan line telepon namun dikenakan biaya pemasangan Rp 1,500,000.00/line telepon, dimana setiap line telepon akan dikenakan biaya deposit sebesar Rp 3,000,000.00/line telepon, yang harus dibayar oleh Penyewa.Biaya aktivasi untuk internet tanpa IP dedicated dikenakan biaya sebesar Rp 3,500,000.00 dan untuk biaya aktivasi yang membutuhkan IP dedicated dikenakan biaya sebesar Rp 4,200,000.00. Sedangkan untuk biaya berlangganan 10Mbps: Rp 1,600,000.00 per bulan ditambah dengan biaya pemeliharaan sesuai dengan tariff manajemen yang berlaku.
      </td>
    </tr>
    <tr>
      <td><br></td>
    </tr>
    <tr>
      <td style="width:3%"><b>8</b></td>
      <td colspan="5" ><b>DOWN PAYMENT</b></td>
    </tr>
    <tr>
      <td></td>
      <td colspan="5">
        <b>Rp 71295033,6      </b>
      </td>
    </tr>
    <tr>
      <td><br></td>
    </tr>
    <tr>
      <td style="width:3%"><b>9</b></td>
      <td colspan="5" ><b>SECURITY DEPOSIT</b></td>
    </tr>
    <tr>
      <td></td>
      <td colspan="5">
        <b>Rp 42675178,38           </b>
      </td>
    </tr>
    <tr>
      <td><br></td>
    </tr>
    <tr>
      <td></td>
      <td colspan="5" >
        Security Deposit adalah nilai dari tiga bulan harga sewa ditambah tiga bulan service charge. Uang Jaminan dibayarkan setelah penandatanganan Letter of Intent, Uang Jaminan akan Hangus/ tidak dikembalikan apabila:                                
      </td>
    </tr>
    <tr>
      <td></td>
      <td style="width:3%">9.1</td>
      <td colspan="3">
        Dalam beroperasional tidak buka lebih dari lima hari dalam satu bulan dan sudah dikirimkan Surat Peringatan 1, dan surat peringatan 2 sampai batas waktu yang ditentukan.                             
      </td>
    </tr>
    <tr>
      <td></td>
      <td style="width:3%">9.2</td>
      <td colspan="3">
        Pihak Penyewa membatalkan sewa ruang sebelum periode sewa berakhir secara sepihak.                              
      </td>
    </tr>
    <tr>
      <td><br></td>
    </tr>
    <tr>
      <td style="width:3%"><b>10</b></td>
      <td colspan="5" ><b>CARA PEMBAYARAN</b></td>
    </tr>
    <tr>
      <td></td>
      <td style="width:3%">10.1</td>
      <td colspan="3">
        Downpayment dan Security Deposit dibayarkan paling lambat 7 (tujuh) hari setelah penandatanganan Letter of Intent (LOI)                             
      </td>
    </tr>
    <tr>
      <td></td>
      <td style="width:3%">10.2</td>
      <td colspan="3">
        Pembayaran Down Payment dan Security Deposit wajib dibayarkan apabila pembayaran belum kami terima sampai dengan tanggal tersebut diatas maka surat LOI ini kami anggap batal dan pemilik dapat menawarkan lokasi dan tempat tersebut kepada pihak lain.                              
      </td>
    </tr>
    <tr>
      <td></td>
      <td style="width:3%">10.3</td>
      <td colspan="3">
        Uang sewa dan Service Charge dibayarkan pada saat awal sewa adapun biaya-biaya lain dibayar setiap tanggal 10 setiap bulan, apabila lewat tanggal tersebut maka dikenakan denda sebesar 0.1% per hari dan dibulatkan menjadi 5% per bulan apabila denda sudah melewati lebih dari 30 hari. Dari jumlah tertunggak untuk setiap hari keterlambatan terhitung sejak pembayaran tersebut harus dibayarkan sampai seluruh pembayaran yang tertunggak dilunasi.                              
      </td>
    </tr>
    <tr>
      <td></td>
      <td style="width:3%">10.4</td>
      <td colspan="3">
        Semua pembayaran dilakukan dengan transfer dalam mata uang Rupiah kerekening  berikut:
        <table width="100%">
          <tr>
            <td width="20%">BANK</td>
            <td width="3%">:</td>
            <td>.......................</td>
          </tr>
          <tr>
            <td>NOMOR REKENING</td>
            <td>:</td>
            <td>.......................</td>
          </tr>
          <tr>
            <td>ATAS NAMA</td>
            <td>:</td>
            <td>.......................</td>
          </tr>
        </table>                     
      </td>
    </tr>
    <tr>
      <td></td>
      <td style="width:3%">10.5</td>
      <td colspan="3">
        Biaya Sewa dan Biaya Service Charge dikenakan PPN sesuai dengan tarif yang berlaku untuk masing-masing beban biaya. Beban PPh dari pembayaran biaya sewa, biaya jasa pelayanan serta biaya penggunaan listrik dibebankan kepada pihak Penyewa.                              
      </td>
    </tr>
    <tr>
      <td></td>
      <td colspan="5">
        Apabila telah melakukan pembayaran mohon mengirimkan bukti pembayaran ke email ......@ifpro.co.id.
      </td>
    </tr>
    <tr>
      <td><br></td>
    </tr>
    <tr>
      <td style="width:3%"><b>11</b></td>
      <td colspan="5" ><b>JENIS KEGIATAN USAHA</b></td>
    </tr>
    <tr>
      <td></td>
      <td style="width:15%" colspan="2"><b>NAMA USAHA</b></td>
      <td style="width:3%"><b>:</b></td>
    </tr>
    <tr>
      <td></td>
      <td colspan="5">
        Apabila ada perubahan terhadap jenis kegiatan usaha sebagaimana tersebut diatas wajib mendapat persetujuan tertulis terlebuh dahulu dari pemilik. Seluruh biaya perijinan dan administrasi terhadap jenis kegiatan usaha tersebut merupakan tanggung jawab penyewa, dan penyewa dengan ini menjamin bahwa Mitra telah memiliki segala perijinan dan persetujuan yang diperlukan dari instansi yang berwenang untuk menjalankan kegiatan usahanya dan tidak melakukan kegiatan usaha yang dilarang dan selain dari kegiatan usaha yang disebutkan diatas.
      </td>
    </tr>
    <tr>
      <td><br></td>
    </tr>
    <tr>
      <td style="width:3%"><b>12</b></td>
      <td colspan="5" ><b>BIAYA FITTING OUT</b></td>
    </tr>
    <tr>
      <td></td>
      <td style="width:15%" colspan="2"><b>Rp 3.500.000   </b></td>
    </tr>
    <tr>
      <td></td>
      <td colspan="5">
        Biaya fitting out berdasarkan luas ruang sewa harus dibayarkan selambat-lambatnya 7 hari sejak gambar desain disetujui oleh Pihak Penyewa dan uang jaminan fit out akan dikembalikan saat renovasi berakhir. Selama periode pekerjaan fitting out, Penyewa wajib membayar pemakaian listrik dan air berdasarkan alat ukur meter yang telah dipasang oleh Pemilik dalam obye ksewa. Penyewa juga bertanggung jawab untuk menjaga kebersihan dalam dan luar ruang dari debu dan sampah, dengan membuang sampah/puing secara terus menerus yang timbul selama pekerjaan berlangsung hingga selesai.                                
      </td>
    </tr>
    <tr>
      <td><br></td>
    </tr>
    <tr>
      <td style="width:3%"><b>13</b></td>
      <td colspan="5" ><b>PERIODE FITTING OUT</b></td>
    </tr>
    <tr>
      <td></td>
      <td colspan="5">
        Pihak Penyewa mendapatkan periode Fitting out untuk merenovasi area Ruang Sewa maksimum selama 30 hari sejak tanggal Berita Acara Serah Terima Ruangan. Setiap perubahan wajib mendapat persetujuan dari pihak Pemilik/Pengelola.
        Apabila setelah 30 hari atau maksimum tanggal mulai sewa masih belum dilakukan Fitting out oleh pihak Penyewa, maka pemilik/Pengelola Gedung akan langsung menetapkan tanggal efektif buka toko.
        Penyewa wajib memberikan gambar atau dokumen lainnya yang dibutuhkan dan diminta oleh yang menyewakan sebelum tanggal mulai Fitting out. 
      </td>
    </tr>
    <tr>
      <td><br></td>
    </tr>
    <tr>
      <td style="width:3%"><b>14</b></td>
      <td colspan="5" ><b>KETENTUAN LAIN</b></td>
    </tr>
    <tr>
      <td></td>
      <td style="width:3%">14.1</td>
      <td colspan="3">
        Menandatangani dan/atau menyetujui ketentuan-ketentuan sewa dan syarat-syarat sewa yang akan diatur dalam perjanjian sewa ruang usaha area PT Indonesia Ferry Properti.                             
      </td>
    </tr>
    <tr>
      <td></td>
      <td style="width:3%">14.2</td>
      <td colspan="3">
        Penyewa tidak diperbolehkan memindahtangankan atau menyewakan kembali ruangan kepada pihak lain tanpa ada permohonan tertulis kepada Pemilik/Pengelola serta kesepakatan dengan pihak Pemilik/Pengelola Gedung.                             
      </td>
    </tr>
    <tr>
      <td></td>
      <td style="width:3%">14.3</td>
      <td colspan="3">
        Pemilik/Pengelola Gedung menetapkan jam operasional normal usaha sebagai berikut :<br>
        Senin s/d Minggu        : 10.00 - 22.00                               
      </td>
    </tr>
    <tr>
      <td></td>
      <td style="width:3%">14.4</td>
      <td colspan="3">
        Pemilik/Pengelola Gedung berhaksewaktu-waktu meninjau atau melakukan  perubahan atas jam layanan operasional Gedung berdasarkan kondisi-kondisi yang terkait keamanan, kesehatan dan Force Majeur.                              
      </td>
    </tr>
    <tr>
      <td><br></td>
    </tr>
    <tr>
      <td></td>
      <td colspan="5">
        Apabila hal-hal tersebut diatas telah di setujui, mohon dapat menandatangani Surat Konfirmasi/ LOI ini dan dikembalikan kepada kami.                                
      </td>
    </tr>
    <tr>
      <td><br></td>
    </tr>
    <tr>
      <td></td>
      <td colspan="5">
        Surat Konfirmasi/ LOI ini selambat-lambatnya sudah dapat kami terima pada hari Selasa tanggal 27 Februari 2024. Selanjutnya apabila Surat Konfirmasi Unit sewa ini belum kami terima sampai batas waktu yang telah ditetapkan, maka Surat Konfirmasi Unit sewa ini kami anggap batal dan lokasi tersebutakan kami tawarkan kepihak lain.                                                                
      </td>
    </tr>
    <tr>
      <td><br></td>
    </tr>
    <tr>
      <td></td>
      <td colspan="5">
        Demikian Surat Konfirmasi Unit Sewa Ruang ini kami sampaikan, atas kerjasamanya kami ucapkan terimakasih.                                                                
      </td>
    </tr>
  </table>
  <br>
  <!-- footer -->
  <table style="width:100%">
    <tr>
      <td style="width:30%;text-align: center;">
        Hormat kami,          
      </td>
      <td style="width:40%"></td>
      <td style="width:30%;text-align: center;">
        Menyetujui,           
      </td>
    </tr>
    <tr>
      <td style="width:30%;text-align: center;">
        <b>PT Indonesia Ferry Properti</b>                   
      </td>
      <td style="width:40%"></td>
      <td style="width:30%;text-align: center;">
        <b>JABATAN</b>           
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
        <u><b>Fajar Saiful Bahri</b></u>                    
      </td>
      <td style="width:40%"></td>
      <td style="width:30%;text-align: center;">
        <u><b>S. Hannie Krisdianta</b></u>                       
      </td>
    </tr>
    <tr>
      <td style="width:30%;text-align: center;">
        Direktur          
      </td>
      <td style="width:40%"></td>
      <td style="width:30%;text-align: center;">
        Corporate Senior Manager                
      </td>
    </tr>
  </table>
  <br>
  <br>

  <!-- header rincian 1 -->
  <table style="width: 100%;">
    <tr>
      <td colspan="7">
        <img src="<?=base_url().'images/logo.png'?>" height="100px">
      </td>
    </tr>
    <tr>
      <td style="text-align:center;" colspan="7">
        <h1><b>KALKULASI HARGA SEWA</b></h1>
      </td>
    </tr>
  </table>

  <!-- rincian 1 -->
  <table style="width:100%">
    <tr>
      <td colspan="5" style="border:solid black 0.5px;text-align: center;"><b>PROFIL PENYEWA</b></td>
    </tr>
    <tr>
      <td colspan=2 style="border:solid black 0.5px;"> Unit</td>
      <td style="width:3%;border:solid black 0.5px;text-align: center;">:</td>
      <td style="border:solid black 0.5px;" colspan="2">
         GF.01
        <br> GF.03                    
      </td>
    </tr>
    <tr>
      <td colspan=2 style="border:solid black 0.5px;"> Nama Penyewa</td>
      <td style="width:3%;border:solid black 0.5px;text-align: center;">:</td>
      <td style="border:solid black 0.5px;"colspan="2">
      </td>
    </tr>
    <tr>
      <td colspan=2 style="border:solid black 0.5px;">Nama Toko</td>
      <td style="width:3%;border:solid black 0.5px;text-align: center;">:</td>
      <td style="border:solid black 0.5px;"colspan="2">
      </td>
    </tr>
    <tr>
      <td colspan=2 style="border:solid black 0.5px;">Lini Bisnis</td>
      <td style="width:3%;border:solid black 0.5px;text-align: center;">:</td>
      <td style="border:solid black 0.5px;"colspan="2">
      </td>
    </tr>
    <tr>
      <td colspan="5" style="border:solid black 0.5px;text-align: center;"><b>STATUS PENYEWA</b></td>
    </tr>
    <tr>
      <td colspan=2 style="border:solid black 0.5px;">Status Dokumen</td>
      <td style="width:3%;border:solid black 0.5px;text-align: center;">:</td>
      <td style="border:solid black 0.5px;"colspan="2">
      </td>
    </tr>
    <tr>
      <td colspan="5" style="border:solid black 0.5px;text-align: center;"><b>TARIF KOMERSIAL</b></td>
    </tr>
    <tr>
      <td colspan=2 style="border:solid black 0.5px;">Pola Bisnis         </td>
      <td style="width:3%;border:solid black 0.5px;text-align: center;">:</td>
      <td style="border:solid black 0.5px;" colspan="2">Sewa             </td>
    </tr>
    <tr>
      <td colspan=2 style="border:solid black 0.5px;">Masa Kerja Sama                 </td>
      <td style="width:3%;border:solid black 0.5px;text-align: center;">:</td>
      <td style="width:20%;border:solid black 0.5px;">24             </td>
      <td style="width:40%;border:solid black 0.5px;">Bulan             </td>
    </tr>
    <tr>
      <td colspan=2 style="border:solid black 0.5px;">Luas Area Unit</td>
      <td style="width:3%;border:solid black 0.5px;text-align: center;">:</td>
      <td style="border:solid black 0.5px;" colspan="2"></td>
    </tr>
    <tr>
      <td style="width:2%;border:solid black 0.5px;">-</td>
      <td style="width:35%;border:solid black 0.5px;">Indoor        </td>
      <td style="width:3%;border:solid black 0.5px;text-align: center;">:</td>
      <td style="border:solid black 0.5px;" colspan="2"></td>
    </tr>
    <tr>
      <td style="width:2%;border:solid black 0.5px;">-</td>
      <td style="width:35%;border:solid black 0.5px;">Outdoor       </td>
      <td style="width:3%;border:solid black 0.5px;text-align: center;">:</td>
      <td style="border:solid black 0.5px;" colspan="2"></td>
    </tr>
    <tr>
      <td colspan=2 style="border:solid black 0.5px;">Harga Sewa          </td>
      <td style="width:3%;border:solid black 0.5px;text-align: center;">:</td>
      <td style="border:solid black 0.5px;" colspan="2"></td>
    </tr>
    <tr>
      <td style="width:2%;border:solid black 0.5px;">-</td>
      <td style="width:35%;border:solid black 0.5px;">Indoor        </td>
      <td style="width:3%;border:solid black 0.5px;text-align: center;">:</td>
      <td style="border:solid black 0.5px;">Rp -</td>
      <td style="border:solid black 0.5px;">/ m2 (excl. PPN)      </td>
    </tr>
    <tr>
      <td style="width:2%;border:solid black 0.5px;">-</td>
      <td style="width:35%;border:solid black 0.5px;">Outdoor       </td>
      <td style="width:3%;border:solid black 0.5px;text-align: center;">:</td>
      <td style="border:solid black 0.5px;">Rp -</td>
      <td style="border:solid black 0.5px;">/ m2 (excl. PPN)      </td>
    </tr>
    <tr>
      <td colspan=2 style="border:solid black 0.5px;">Harga Sewa Unit Per Bulan          </td>
      <td style="width:3%;border:solid black 0.5px;text-align: center;">:</td>
      <td style="border:solid black 0.5px;" colspan="2"></td>
    </tr>
    <tr>
      <td style="width:2%;border:solid black 0.5px;">-</td>
      <td style="width:35%;border:solid black 0.5px;">Indoor        </td>
      <td style="width:3%;border:solid black 0.5px;text-align: center;">:</td>
      <td style="border:solid black 0.5px;">Rp -</td>
      <td style="border:solid black 0.5px;">excl. PPN     </td>
    </tr>
    <tr>
      <td style="width:2%;border:solid black 0.5px;">-</td>
      <td style="width:35%;border:solid black 0.5px;">Outdoor       </td>
      <td style="width:3%;border:solid black 0.5px;text-align: center;">:</td>
      <td style="border:solid black 0.5px;">Rp -</td>
      <td style="border:solid black 0.5px;">excl. PPN     </td>
    </tr>
    <tr>
      <td colspan=2 style="border:solid black 0.5px;">Harga Sewa Unit Per Bulan          </td>
      <td style="width:3%;border:solid black 0.5px;text-align: center;">:</td>
      <td style="border:solid black 0.5px;" colspan="2"></td>
    </tr>
    <tr>
      <td style="width:2%;border:solid black 0.5px;">-</td>
      <td style="width:35%;border:solid black 0.5px;">Indoor        </td>
      <td style="width:3%;border:solid black 0.5px;text-align: center;">:</td>
      <td style="border:solid black 0.5px;">Rp -</td>
      <td style="border:solid black 0.5px;">incl. PPN     </td>
    </tr>
    <tr>
      <td style="width:2%;border:solid black 0.5px;">-</td>
      <td style="width:35%;border:solid black 0.5px;">Outdoor       </td>
      <td style="width:3%;border:solid black 0.5px;text-align: center;">:</td>
      <td style="border:solid black 0.5px;">Rp -</td>
      <td style="border:solid black 0.5px;">incl. PPN     </td>
    </tr>
    <tr>
      <td colspan=2 style="border:solid black 0.5px;"><b>Total Sewa         </b></td>
      <td style="width:3%;border:solid black 0.5px;text-align: center;">:</td>
      <td style="border:solid black 0.5px;" colspan="2"></td>
    </tr>
    <tr>
      <td style="width:2%;border:solid black 0.5px;">-</td>
      <td style="width:35%;border:solid black 0.5px;">Indoor        </td>
      <td style="width:3%;border:solid black 0.5px;text-align: center;">:</td>
      <td style="border:solid black 0.5px;">Rp -</td>
      <td style="border:solid black 0.5px;">incl. PPN     </td>
    </tr>
    <tr>
      <td style="width:2%;border:solid black 0.5px;">-</td>
      <td style="width:35%;border:solid black 0.5px;">Outdoor       </td>
      <td style="width:3%;border:solid black 0.5px;text-align: center;">:</td>
      <td style="border:solid black 0.5px;">Rp -</td>
      <td style="border:solid black 0.5px;">incl. PPN     </td>
    </tr>
    <tr>
      <td colspan=2 style="border:solid black 0.5px;">Fitting out Charge</td>
      <td style="width:3%;border:solid black 0.5px;text-align: center;">:</td>
      <td style="border:solid black 0.5px;" colspan="2">Rp -</td>
    </tr>
    <tr>
      <td colspan=2 style="border:solid black 0.5px;">Harga Service Charge</td>
    </tr>
    <tr>
      <td style="width:2%;border:solid black 0.5px;">-</td>
      <td style="width:35%;border:solid black 0.5px;">Indoor        </td>
      <td style="width:3%;border:solid black 0.5px;text-align: center;">:</td>
      <td style="border:solid black 0.5px;">Rp -</td>
      <td style="border:solid black 0.5px;">/ m2 (excl. PPN)</td>
    </tr>
    <tr>
      <td style="width:2%;border:solid black 0.5px;">-</td>
      <td style="width:35%;border:solid black 0.5px;">Outdoor       </td>
      <td style="width:3%;border:solid black 0.5px;text-align: center;">:</td>
      <td style="border:solid black 0.5px;">Rp -</td>
      <td style="border:solid black 0.5px;">/ m2 (excl. PPN)</td>
    </tr>
    <tr>
      <td colspan=2 style="border:solid black 0.5px;">Harga Service Charge Per Bulan</td>
    </tr>
    <tr>
      <td style="width:2%;border:solid black 0.5px;">-</td>
      <td style="width:35%;border:solid black 0.5px;">Indoor        </td>
      <td style="width:3%;border:solid black 0.5px;text-align: center;">:</td>
      <td style="border:solid black 0.5px;">Rp -</td>
      <td style="border:solid black 0.5px;">incl. PPN     </td>
    </tr>
    <tr>
      <td style="width:2%;border:solid black 0.5px;">-</td>
      <td style="width:35%;border:solid black 0.5px;">Outdoor       </td>
      <td style="width:3%;border:solid black 0.5px;text-align: center;">:</td>
      <td style="border:solid black 0.5px;">Rp -</td>
      <td style="border:solid black 0.5px;">incl. PPN     </td>
    </tr>
    <tr>
      <td colspan=2 style="border:solid black 0.5px;">Total Service Charge (1 Tahun)</td>
    </tr>
    <tr>
      <td style="width:2%;border:solid black 0.5px;">-</td>
      <td style="width:35%;border:solid black 0.5px;">Indoor        </td>
      <td style="width:3%;border:solid black 0.5px;text-align: center;">:</td>
      <td style="border:solid black 0.5px;">Rp -</td>
      <td style="border:solid black 0.5px;">incl. PPN     </td>
    </tr>
    <tr>
      <td style="width:2%;border:solid black 0.5px;">-</td>
      <td style="width:35%;border:solid black 0.5px;">Outdoor       </td>
      <td style="width:3%;border:solid black 0.5px;text-align: center;">:</td>
      <td style="border:solid black 0.5px;">Rp -</td>
      <td style="border:solid black 0.5px;">incl. PPN     </td>
    </tr>
    <tr>
      <td colspan=2 style="border:solid black 0.5px;">Marketing Levy</td>
      <td style="width:3%;border:solid black 0.5px;text-align: center;">:</td>
      <td style="border:solid black 0.5px;" colspan="2">Rp -</td>
    </tr>
    <tr>
      <td colspan=2 style="border:solid black 0.5px;">DP</td>
      <td style="width:3%;border:solid black 0.5px;text-align: center;">:</td>
      <td style="border:solid black 0.5px;" colspan="2">20</td>
    </tr>
    <tr>
      <td colspan=2 style="border:solid black 0.5px;">TOP         </td>
      <td style="width:3%;border:solid black 0.5px;text-align: center;">:</td>
      <td style="border:solid black 0.5px;" colspan="2">18</td>
    </tr>
    <tr>
      <td colspan="5" style="border:solid black 0.5px;text-align: center;"><b>Cara Pembayaran</b></td>
    </tr>
    <tr>
      <td style="border:solid black 0.5px;" colspan="2">Down Payment</td>
      <td style="width:3%;border:solid black 0.5px;text-align: center;">:</td>
      <td style="border:solid black 0.5px;">Rp 71295033,6         </td>
      <td style="border:solid black 0.5px;">(incl. PPN)     </td>
    </tr>
    <tr>
      <td style="border:solid black 0.5px;" colspan="2">Angsuran (sisa dikurang DP)</td>
      <td style="width:3%;border:solid black 0.5px;text-align: center;">:</td>
      <td style="border:solid black 0.5px;">Rp -</td>
      <td style="border:solid black 0.5px;">(incl. PPN)          </td>
    </tr>
    <tr>
      <td style="border:solid black 0.5px;" colspan="2">Down Payment</td>
      <td style="width:3%;border:solid black 0.5px;text-align: center;">:</td>
      <td style="border:solid black 0.5px;">Rp -</td>
      <td style="border:solid black 0.5px;">(incl. PPN)          </td>
    </tr>
    <tr>
      <td style="border:solid black 0.5px;" colspan="2">Amotrisasi DP         </td>
      <td style="width:3%;border:solid black 0.5px;text-align: center;">:</td>
      <td style="border:solid black 0.5px;">Rp -</td>
      <td style="border:solid black 0.5px;">/ bulan     </td>
    </tr>
    <tr>
      <td style="border:solid black 0.5px;" colspan="2">Biaya Service Charge          t</td>
      <td style="width:3%;border:solid black 0.5px;text-align: center;">:</td>
      <td style="border:solid black 0.5px;">Rp -</td>
      <td style="border:solid black 0.5px;">/ bulan      </td>
    </tr>
    <tr>
      <td colspan="5" style="border:solid black 0.5px;text-align: center;"><b>SIMULASI</b></td>
    </tr>
    <tr>
      <td style="border:solid black 0.5px;" colspan="2">Uraian</td>
      <td style="width:3%;border:solid black 0.5px;text-align: center;">:</td>
      <td style="border:solid black 0.5px;" colspan="2">Bulan                   </td>
    </tr>
  </table>

<!-- header rincian 2 -->
  <table style="width: 100%;">
    <tr>
      <td colspan="7">
        <img src="<?=base_url().'images/logo.png'?>" height="100px">
      </td>
    </tr>
    <tr>
      <td style="text-align:center;" colspan="7">
        <h1><b>TABEL PERHITUNGAN SEWA</b></h1>
      </td>
    </tr>
  </table>

<!-- rincian 2 -->
  <table style="width:100%">
    <tr>
      <td style="border:solid black 0.5px; text-align: center;"> ANGSURAN  </td>
      <td style="border:solid black 0.5px; text-align: center;"> SEWA UNIT <br>(Incl. PPN)    </td>
      <td style="border:solid black 0.5px; text-align: center;"> TOTAL SEWA <br> (Incl. PPN)</td>
      <td style="border:solid black 0.5px; text-align: center;"> SERVICE CHARGE <br> (Incl. PPN)</td>
    </tr>
    <tr>
      <td style="border:solid black 0.5px;" colspan="2"> SALDO AWAL          </td>
      <td style="border:solid black 0.5px;"></td>
      <td style="border:solid black 0.5px;"></td>
    </tr>
    <?for($i=1; $i<=48; $i++){?>
      <tr>
        <td style="border:solid black 0.5px;"> Angsuran #<?=$i?>  </td>
        <td style="border:solid black 0.5px;"> </td>
        <td style="border:solid black 0.5px;"> </td>
        <td style="border:solid black 0.5px;"> </td>
      </tr>
    <?}?>
    <tr>
      <td style="border:solid black 0.5px; text-align: center;" colspan="2"><b> TOTAL</b> </td>
      <td style="border:solid black 0.5px;"></td>
      <td style="border:solid black 0.5px;"></td>
    </tr>

</body>
<!-- End Maker Surat -->