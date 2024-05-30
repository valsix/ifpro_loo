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
        <h1><b><u>LETTER OF OFFERING</u></b></h1>
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
      </tr>
    <tr>
      <td><br></td>
    </tr>
    <tr>
      <td colspan=7>
        SOSORO MALL - MERAK <br> ANJUNGAN AGUNG MALL - BAKAUHENI <br> PLAZA MARINA - LABUAN BAJO
      </td>
    </tr>
    <tr>
      <td><br></td>
    </tr>
    <tr>
      <td colspan="3">Kepada Yth.</td>
    </tr>
    <tr>
      <td colspan="3">Bapak S. Hannie Krisdianta</td>
    </tr>
    <tr>
      <td colspan="3">PT Indomarco Prismatama</td>
    </tr>
    <tr>
      <td colspan="3">
        <table>
          <tr>
            <td>Telp./HP</td>
            <td>:</td>
            <td>021 - 508 97 400</td>
          </tr>
          <tr>
            <td>Email   </td>
            <td>:</td>
            <td>-</td>
          </tr>
        </table>
      </td>
    </tr>
    <tr>
      <td colspan="3">di Tempat</td>
    </tr>
  </table>
  <br>
  <!-- body -->
  <table style="width: 100%;">
    <tr>
      <td colspan="9">Salam hangat dari <b>PT Indonesia Ferry Properti.</b></td>
    </tr>
    <tr>
      <td colspan="9">Bersama ini kami ingin menawarkan ruang sewa kami, dibawah ini adalah rincian lokasinya:</td>
    </tr>
    <tr>
      <td><br></td>
    </tr>
    <tr>
      <td style="width:3%">1</td>
      <td colspan="4">NAMA PEMILIK</td>
      <td ><b>:</b></td>
      <td colspan="3"><b>S. Hannie Krisdianta</b></td>
    </tr>
    <tr>
      <td style="width:3%">2</td>
      <td colspan="4">NAMA BRAND</td>
      <td ><b>:</b></td>
      <td colspan="3"><b>INDOMARET</b></td>
    </tr>
    <tr>
      <td style="width:3%">3</td>
      <td colspan="4">PRODUK</td>
      <td ><b>:</b></td>
      <td colspan="3"><b>Retail</b></td>
    </tr>
    <tr>
      <td style="width:3%">4</td>
      <td colspan="4">LOKASI / ID / LANTAI</td>
      <td ><b>:</b></td>
      <td colspan="3"><b>L1. 11</b></td>
    </tr>
    
    <tr>
      <td style="width:3%">5</td>
      <td colspan="4" >LUAS SEWA</td>
    </tr>
    <tr>
      <td></td>
      <td style="width:5%">5.1</td>
      <td colspan="5">Indoor</td>
    </tr>
    <tr>
      <td></td>
      <td></td>
      <td style="width:5%">5.1.1</td>
      <td style="width:10%">GF</td>
      <td style="width:10%">01</td>
      <td style="width:5%">:</td>
      <td style="width:10%">95,58 </td>
      <td style="width:10%">m2</td>
    </tr>
    <tr>
      <td></td>
      <td></td>
      <td style="width:5%">5.1.2</td>
      <td style="width:5%">L1</td>
      <td style="width:5%">02</td>
      <td style="width:5%">:</td>
      <td style="width:10%"></td>
      <td style="width:5%">m2</td>
      </tr>
    <tr>
      <td></td>
      <td style="width:5%">5.2</td>
      <td colspan="5">outdor</td>
    </tr>
    <tr>
      <td></td>
      <td></td>
      <td style="width:5%">5.2.1</td>
      <td colspan="2"></td>
      <td style="width:5%">:</td>
      <td style="width:10%">95,58 </td>
      <td style="width:5%">m2</td>
      </tr>
    <tr>
      <td></td>
      <td></td>
      <td style="width:5%">5.2.2</td>
      <td colspan="2"></td>
      <td style="width:5%">:</td>
      <td style="width:10%"></td>
      <td style="width:5%">m2</td>
      </tr>
    <tr>
      <td></td>
      <td style="width:5%">5.3</td>
      <td colspan="3">Total Luas Sewa</td>
      <td style="width:5%">:</td>
      <td style="width:10%">95,58</td>
      <td style="width:5%">m2</td>
    </tr>
    <tr>
      <td style="width:3%">6</td>
      <td colspan="4" >TARIF SEWA</td>
    </tr>
    <tr>
      <td></td>
      <td style="width:5%">6.1</td>
      <td colspan="5">Unit</td>
    </tr>
    <tr>
      <td></td>
      <td></td>
      <td style="width:5%">6.1.1</td>
      <td colspan="2">Indor</td>
      <td style="width:5%">:</td>
      <td style="width:10%"> 200.000   </td>
      <td style="width:5%">Rp / m2</td>
      </tr>
    <tr>
      <td colspan="3"></td>
      <td colspan="2">Discount</td>
      <td style="width:5%">:</td>
      <td style="width:10%"> 30   </td>
      <td style="width:5%">%</td>
      </tr>
    <tr>
      <td colspan="3"></td>
      <td colspan="2">Tarif (after discount)</td>
      <td style="width:5%">:</td>
      <td style="width:10%">  140.000 </td>
      <td style="width:5%">Rp / m2</td>
      </tr>
    <tr>
      <td></td>
      <td></td>
      <td style="width:5%">6.1.2</td>
      <td colspan="2">Outdoor</td>
      <td style="width:5%">:</td>
      <td style="width:10%">- </td>
      <td style="width:5%">Rp / m2</td>
      </tr>
    <tr>
      <td colspan="3"></td>
      <td colspan="2">Discount</td>
      <td style="width:5%">:</td>
      <td style="width:10%"> -   </td>
      <td style="width:5%">%</td>
      </tr>
    <tr>
      <td colspan="3"></td>
      <td colspan="2">Tarif (after discount)</td>
      <td style="width:5%">:</td>
      <td style="width:10%">  - </td>
      <td style="width:5%">Rp / m2</td>
      </tr>
    <tr>
      <td></td>
      <td style="width:5%">6.2</td>
      <td colspan="5">Service Charge</td>
    </tr>
    <tr>
      <td></td>
      <td></td>
      <td style="width:5%">6.2.1</td>
      <td colspan="2">Indor</td>
      <td style="width:5%">:</td>
      <td style="width:10%">  83.243</td>
      <td style="width:5%">Rp / m2</td>
      </tr>
    <tr>
      <td colspan="3"></td>
      <td colspan="2">Discount</td>
      <td style="width:5%">:</td>
      <td style="width:10%">-</td>
      <td style="width:5%">%</td>
      </tr>
    <tr>
      <td colspan="3"></td>
      <td colspan="2">Tarif (after discount)</td>
      <td style="width:5%">:</td>
      <td style="width:10%">   83.243    </td>
      <td style="width:5%">Rp / m2</td>
      </tr>
    <tr>
      <td></td>
      <td></td>
      <td style="width:5%">6.1.2</td>
      <td colspan="2">Outdoor</td>
      <td style="width:5%">:</td>
      <td style="width:10%"> 41.622    </td>
      <td style="width:5%">Rp / m2</td>
      </tr>
    <tr>
      <td colspan="3"></td>
      <td colspan="2">Discount</td>
      <td style="width:5%">:</td>
      <td style="width:10%"> -</td>
      <td style="width:5%">%</td>
      </tr>
    <tr>
      <td colspan="3"></td>
      <td colspan="2">Tarif (after discount)</td>
      <td style="width:5%">:</td>
      <td style="width:10%">   41.622    </td>
      <td style="width:5%">Rp / m2</td>
      </tr>
    <tr>
      <td style="width:3%">7</td>
      <td colspan="4" >HARGA SEWA</td>
    </tr>
    <tr>
      <td></td>
      <td style="width:5%">7.1</td>
      <td colspan="3">Indoor</td>
      <td style="width:5%">:</td>
      <td style="width:10%"> Rp  13.381.200</td>
      <td style="width:5%">/ m2 / bulan</td>
      </tr>
    <tr>
      <td></td>
      <td style="width:5%">7.2</td>
      <td colspan="3">Outdoor</td>
      <td style="width:5%">:</td>
      <td style="width:10%">Rp  -</td>
      <td style="width:5%">/ m2 / bulan</td>
      </tr>
    <tr>
      <td style="width:3%">8</td>
      <td colspan="4" >HARGA SERVICE CHARGE</td>
    </tr>
    <tr>
      <td></td>
      <td style="width:5%">8.1</td>
      <td colspan="3">Indoor</td>
      <td style="width:5%">:</td>
      <td style="width:10%"> Rp   7.956.389</td>
      <td style="width:5%">/ m2 / bulan</td>
      </tr>
    <tr>
      <td></td>
      <td style="width:5%">8.2</td>
      <td colspan="3">Outdoor</td>
      <td style="width:5%">:</td>
      <td style="width:10%">Rp  -</td>
      <td style="width:5%">/ m2 / bulan</td>
      </tr>
    <tr>
      <td style="width:3%">9</td>
      <td colspan="4" >HARGA SERVICE CHARGE</td>
    </tr>
    <tr>
      <td></td>
      <td style="width:5%">9.1</td>
      <td colspan="3">Listrik</td>
      <td style="width:5%">:</td>
      <td style="width:10%"> Rp   1.647,20    </td>
      <td style="width:5%">/ kwh</td>
      </tr>
    <tr>
      <td></td>
      <td style="width:5%">9.2</td>
      <td colspan="3">Gas</td>
      <td style="width:5%">:</td>
      <td style="width:10%">Rp  19.200    </td>
      <td style="width:5%">/ kg</td>
      </tr>
    <tr>
      <td></td>
      <td style="width:5%">9.3</td>
      <td colspan="3">Air</td>
      <td style="width:5%">:</td>
      <td style="width:10%">Rp  25.000        </td>
      <td style="width:5%">/ m3</td>
      </tr>
    <tr>
      <td style="width:3%">10</td>
      <td colspan="4">DOWN PAYMENT</td>
      <td >:</td>
      <td>20</td>
      <td>%</td>
    </tr>
    <tr>
      <td style="width:3%">11</td>
      <td colspan="4">PERIODE SEWA</td>
      <td >:</td>
      <td>24  </td>
      <td>bulan</td>
    </tr>
    <tr>
      <td style="width:3%">12</td>
      <td colspan="4" >JAM OPERASIONAL</td>
    </tr>
    <tr>
      <td></td>
      <td style="width:5%">12.1</td>
      <td colspan="3">Gedung</td>
      <td style="width:5%">:</td>
      <td colspan="2">10:00 s/d 22:00 </td>
      </tr>
    <tr>
      <td></td>
      <td style="width:5%">12.2</td>
      <td colspan="3">Tenant</td>
      <td style="width:5%">:</td>
      <td colspan="2">10:00 s/d 22:00 </td>
      </tr>
  </table>
  <!-- rincian sewa -->
  <table style="width: 100%;">
    <tr>
      <td colspan="8"><u><b>RINCIAN NILAI SEWA</b></u></td>
    </tr>
    <tr>
      <td style="border:solid black 0.5;text-align: center;">NO</td>
      <td style="border:solid black 0.5;text-align: center;">KETERANGAN          </td>
      <td style="border:solid black 0.5;text-align: center;">BIAYA SATUAN      </td>
      <td style="border:solid black 0.5;text-align: center;">PER BULAN     </td>
      <td style="border:solid black 0.5;text-align: center;">TOTAL     </td>
    </tr>
    <tr>
      <td style="border:solid black 0.5;text-align: center;">1</td>
      <td style="border:solid black 0.5;padding-left: 5px;">Harga Sewa Unit</td>
      <td style="border:solid black 0.5;text-align:right;padding-right: 5px;"> 140.000      </td>
      <td style="border:solid black 0.5;text-align:right;padding-right: 5px;"> 13.381.200       </td>
      <td style="border:solid black 0.5;text-align:right;padding-right: 5px;"> 321.148.800      </td>
    </tr>
    <tr>
      <td style="border:solid black 0.5;text-align: center;">2</td>
      <td style="border:solid black 0.5;padding-left: 5px;">Service Charge Tahun Pertama          </td>
      <td style="border:solid black 0.5;text-align:right;padding-right: 5px;"> 124.865      </td>
      <td style="border:solid black 0.5;text-align:right;padding-right: 5px;"> 7.956.389      </td>
      <td style="border:solid black 0.5;text-align:right;padding-right: 5px;"> 95.476.670       </td>
    </tr>
    <tr>
      <td style="border:solid black 0.5;padding-left: 5px;" colspan="3">Total (Tanpa PPN)         </td>
      <td style="border:solid black 0.5;text-align:right;padding-right: 5px;"> 21.337.589       </td>
      <td style="border:solid black 0.5;text-align:right;padding-right: 5px;"> 416.625.470     </td>
    </tr>
    <tr>
      <td style="border:solid black 0.5;padding-left: 5px;" colspan="3">Total (Dengan PPN)          </td>
      <td style="border:solid black 0.5;text-align:right;padding-right: 5px;"> 23.684.724       </td>
      <td style="border:solid black 0.5;text-align:right;padding-right: 5px;"> 462.454.272      </td>
    </tr>
    <tr>
      <td><br></td>
    </tr>
    <tr>
      <td colspan="8"><u><b>KONDISI DAN PERSYARATAN</b></u></td>
    </tr>
    <tr>
      <td ><b>1</b></td>
      <td colspan="5"><b>BIAYA LAYANAN (SERVICE CHARGE)</b>        </td>
    </tr>
    <tr>
      <td ></td>
      <td colspan="5"> Harga biaya pelayanan sewaktu - waktu dapat berubah mengikuti ketentuan Gedung yang berlaku. Biaya pelayanan dibayarkan setiap bulan dimuka sejak toko mulai beroperasi. Biaya pelayanan ini dapat ditinjau kembali berdasarkan kenaikan biaya operasional Gedung.                               
      </td>
    </tr>
    <tr>
      <td ><b>2</b></td>
      <td colspan="5"><b>JAMINAN SEWA (SECURITY DEPOSIT)</b>        </td>
    </tr>
    <tr>
      <td ></td>
      <td colspan="5">Jaminan Sewa sebesar<b> Rp 42.675.178    </b>        </td>
    </tr>
    <tr>
      <td ></td>
      <td colspan="5"> Uang jaminan dibayarkan pada saat penandatangan surat persetujuan. Pada saat masa sewa berakhir, Uang jaminan dapat dikembalikan dan diserahkan kepada Penyewa sesuai dengan mata uang Rupiah dan Jumlah nominal yang senyatanya dibayarkan pada waktu dilakukannya pembayaran uang jaminan, bebas bunga setelah dikurangi dengan pembayaran yang masih tertunggak dan/atau kewajiban – kewajiban Penyewa lainnya terhadap Pengelola Gedung (jika ada) berdasarkan Perjanjian Sewa Menyewa.                                                               
      </td>
    </tr>
    <tr>
      <td ><b>3</b></td>
      <td colspan="5"><b>FITTING OUT</b>        </td>
    </tr>
    <tr>
      <td ></td>
      <td colspan="5"> Fitting out sewa sebesar     <b>Rp   3.500.000</b>                                 
      </td>
    </tr>
    <tr>
      <td ></td>
      <td colspan="5"> 
      Uang Jaminan Fitting out ini akan dibayarkan paling lambat pada saat serah terima ruangan dengan ketentuan pembayaran dilakukan selambatnya 7 (tujuh) hari kerja setelah tagihan diterima oleh Penyewa dan uang jaminan Fitting out akan dikembalikan saat renovasi berakhir. Selain itu selama periode fit out. Penyewa akan dikenakan biaya Fitting out (air kerja, listrik kerja, koordinasi security) yang besarkan akan ditentukan kemudian oleh Pengelola Gedung. Pengelola Gedung memberikan waktu untuk periode Fitting out ruang sewa selama 7 (tujuh) hari dari tanggal serah terima ruang sewa.                                                               
      </td>
    </tr>
    <tr>
      <td ><b>4</b></td>
      <td colspan="5"><b>AIR, LISTRIK DAN GAS</b>        </td>
    </tr>
    <tr>
      <td ></td>
      <td colspan="5">
      Seluruh biaya penggunaan air,gas, listrik yang diadakan di tempat yang disewakan akan dibayar terpisah sesuai yang tercantum pada meteran terpasang.                                                               
      </td>
    </tr>
    <tr>
      <td ><b>5</b></td>
      <td colspan="5"><b>PAJAK PERTAMBAHAN NILAI</b>        </td>
    </tr>
    <tr>
      <td ></td>
      <td colspan="5">Pajak Pertambahan Nilai (PPN) ditanggung oleh Penyewa karena tidak termasuk dalam harga sewa menyewa dan biaya lainnya dan akan disesuaikan dengan peraturan pemerintah.                               
      </td>
    </tr>
    <tr>
      <td ><b>6</b></td>
      <td colspan="5"><b>CARA PEMBAYARAN</b>        </td>
    </tr>
    <tr>
      <td></td>
      <td colspan="5">
        <table style="width:100%">
          <tr>
            <td style="width:5%">
              6.1
            </td>
            <td style="width:30%">
               Down Payment sebesar    
            </td>
            <td style="width:5%">:</td>
            <td >Rp   71.295.034     </td>
          </tr>
        </table>
      </td>      
      </tr>
    <tr>
      <td></td>
      <td colspan="5">
        <table style="width:100%">
          <tr>
            <td style="width:5%">
              6.2
            </td>
            <td style="width:30%">
               Security Deposit sebesar
            </td>
            <td style="width:5%">:</td>
            <td >Rp    42.675.178</td>
          </tr>
        </table>
      </td>
      </tr>
    <tr>
      <td></td>
      <td colspan="5">
        <table style="width:100%">
          <tr>
            <td style="width:5%">
              6.3
            </td>
            <td style="width:30%">
               Fitting Out sebesar
            </td>
            <td style="width:5%">:</td>
            <td >Rp    3.500.000     </td>
          </tr>
        </table>
      </td>
    </tr>
    <tr>
      <td></td>
      <td colspan="5"> 
        <table>
          <tr>
            <td style="width:5%">
              6.4 
            </td>
            <td>
              Pembayaran Downpayment 20%, Security Deposit & Fitting Out wajib dibayarkan paling lambat 7 (tujuh) hari setelah penandatanganan Letter of Intent (LOI). Apabila pembayaran belum kami terima sampai tanggal yang tercantum di surat LOI, maka kami anggap batal dan pemilik dapat menawarkan lokasi dan tempat tersebut kepada pihak lain.    
            </td>
          </tr>
        </table>                              
      </td>
    </tr>
    <tr>
    <tr>
      <td></td>
      <td colspan="5"> 
        <table>
          <tr>
            <td style="width:5%">
              6.5
            </td>
            <td>
              Uang sewa dibayarkan pada saat awal sewa adapun biaya-biaya lain dibayar setiap tanggal 10 setiap bulan, apabila lewat tanggal tersebut maka dikenakan denda sebesar 0.1% per hari dan dibulatkan menjadi 5% per bulan apabila denda sudah melewati lebih dari 30 hari. dari jumlah tertunggak untuk setiap hari keterlambatan terhitung sejak pembayaran tersebut harus dibayarkan sampai seluruh pembayaran yang tertunggak dilunasi.     
            </td>
          </tr>
        </table>                              
      </td>
    </tr>
    <tr>
      <td></td>
      <td colspan="5"> 
        <table style="width:100%">
          <tr>
            <td style="width:5%">
              6.6
            </td>
            <td colspan="3">
              Semua pembayaran dilakukan dengan transfer dalam mata uang Rupiah ke rekening berikut:
            </td>
          </tr>
          <tr>
            <td style="width:5%">
            </td>
            <td style="width:30%">
              No. Rekening
            </td>
            <td style="width:5%">
              :
            </td>
            <td>
              2050051300    
            </td>
          </tr>
          <tr>
            <td style="width:5%">
            </td>
            <td style="width:30%">
              Atas Nama
            </td>
            <td style="width:5%">
              :
            </td>
            <td>
              PT Indonesia Ferry Properti    
            </td>
          </tr>
          <tr>
            <td style="width:5%">
            </td>
            <td style="width:30%">
              Bank
            </td>
            <td style="width:5%">
              :
            </td>
            <td>
              BCA (Bank Central Asia)
            </td>
          </tr>
          <tr>
            <td style="width:5%">
            </td>
            <td style="width:30%">
              Cabang
            </td>
            <td style="width:5%">
              :
            </td>
            <td>
              Merak    
            </td>
          </tr>
        </table>                              
      </td>
    </tr>
    <tr>
      <td></td>
      <td colspan="5"> 
        <table>
          <tr>
            <td style="width:5%">
              6.7
            </td>
            <td>
              Biaya Sewa dan Biaya Jasa Pelayanan dikenakan PPN sesuai dengan tarif yang berlaku untuk masing-masing beban biaya. Beban PPh dari pembayaran biaya sewa, biaya jasa pelayanan serta biaya penggunaan listrik dibebankan kepada pihak Penyewa.
            </td>
          </tr>
        </table>                              
      </td>
    </tr>
    <tr>
      <td></td>
      <td colspan="5">Apabila telah melakukan pembayaran mohon mengirimkan bukti pembayaran ke email nolita@ifpro.co.id                        
      </td>
    </tr>
    <tr>
      <td ><b>7</b></td>
      <td ><b>TEMPAT YANG DISEWAKAN SELALU BEROPERASI</b>        </td>
    </tr>
    <tr>
      <td ></td>
      <td colspan="5">Selama masa sewa, penyewa setuju untuk membuka dan mengoperasikan tempat yang disewakan untuk umum sesuai dengan waktu operasional Sosoro Mall. Pelanggaran atas ketentuan ini Pengelola Gedung berhak mengenakan sanksi, termasuk namun tidak terbatas pada sanksi denda dan/ atau pemutusan fasilitas yang diadakan di tempat yang disewakan.                                                              
      </td>
    </tr>
    <tr>
      <td ><b>8</b></td>
      <td ><b>DENAH TEMPAT YANG DISEWAKAN</b>        </td>
    </tr>
    <tr>
      <td ></td>
      <td colspan="5">Ukuran serta luasan tempat yang disewakan sesuai dengan yang tercantum dalam Perjanjian Sewa Menyewa yang merupakan hasil akhir dari pengukuran bersama/ kenyataan dilapangan yang dilakukan pada saat serah terima tempat yang disewakan.                                                              
      </td>
    </tr>
    <tr>
      <td ><b>9</b></td>
      <td ><b>RELOKASI</b>        </td>
    </tr>
    <tr>
      <td ></td>
      <td colspan="5">Apabila sepanjang masa sewa Penyewa di Sosoro Mall terjadi perubahan yang diperlukan untuk kepentingan Gedung yang akan merubah denah secara keseluruhan, maka Penyewa bersedia untuk dipindahkan ke tempat lain dengan pemberitahuan 30 (tiga puluh) hari sebelumnya atau apabila Penyewa tidak setuju dengan tempat pemindahan tersebut, maka Penyewa dapat membatalkan sewa dengan menerima kembali, tanpa bunga maupun ganti rugi, jaminan sewa, uang yang telah dibayarkan kepada Pengelola Gedung setelah dikurangi dengan uang sewa untuk masa sewa yang telah dipergunakan sampai dengan tanggal efektif pengakhiran.                                                              
      </td>
    </tr>
    <tr>
      <td ><b>10</b></td>
      <td ><b>DOKUMEN PENDUKUNG</b>        </td>
    </tr>
    <tr>
      <td ></td>
      <td colspan="5">Jika Penyewa adalah perusahaan, mohon untuk menyiapkan copy dokumen kepada Pengelola Gedung pada saat Surat Persetujuan Sewa telah ditandatangani dikembalikan kepada Pengelola Gedung yaitu sebagai berikut:                               
      </td>
    </tr>
    <tr>
      <td ></td>
      <td colspan="5">       
        <table style="width:100%">
          <tr>
            <td style="width:5%">
            10.1</td>
            <td>
              Akta Pendirian Perusahaan
            </td>
          </tr>
          <tr>
            <td style="width:5%">
            10.2</td>
            <td >
              Akta Perubahan Terakhir Perusahaan
            </td>
          </tr>
          <tr>
            <td style="width:5%">
            10.3</td>
            <td >
              Nomor Induk Berusaha (OSS)
            </td>
          </tr>
          <tr>
            <td style="width:5%">
            10.4</td>
            <td >
              KTP Direksi    
            </td>
          </tr>
          <tr>
            <td style="width:5%">
            10.5</td>
            <td >
              NPWP & NNPKP    
            </td>
          </tr>
          <tr>
            <td style="width:5%">
            10.6</td>
            <td >
              Surat Kuasa (Jika penandatangan bukan Direksi)    
            </td>
          </tr>
          <tr>
            <td style="width:5%">
            10.7</td>
            <td >
              Jika penyewa adalah perorangan, maka Penyewa wajib memberikan copy KTP dan NPWP *)    
            </td>
          </tr>
        </table>                              
      </td>
    </tr>
  </table>
  <br>
  <!-- footer -->
  <table>
    <tr>
      <td>
        Apabila hal-hal tersebut diatas telah di setujui, mohon dapat menandatangani surat LOO ini dan dikembalikan kepada kami yang merupakan tanda konfirmasi sewa yang selanjutnya menjadi dasar penyusunan LOI. Kami menunggu konfirmasi dari Bapak/Ibu paling lambat hari Kamis tanggal 02 Mei 2024                                  
      </td>
    </tr>
    <tr><td><br></td></tr>
    <tr>
      <td>
        Demikian penawaran ini kami sampaikan, apabila ada hal yang perlu kami jelaskan lebih lanjut, mohon berkenan menghubungi kami di 0896-0100-1997 (BapakTriadi). Atas perhatiannya diucapkan terima kasih.                                  
      </td>
    </tr>
  </table>
  <br>
  <br>
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
        <b>PT Indomarco Prismatama </b>           
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


</body>
<!-- End Maker Surat -->