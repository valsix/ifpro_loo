<!doctype html>
<html>
<head>
  <base href="<?=base_url()?>">
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<meta charset="utf-8">
	<script>
		document.onkeydown = function(e) {
			if(e.keyCode == 123) {
				return false;
			}
			if(e.ctrlKey && e.shiftKey && e.keyCode == 'I'.charCodeAt(0)){
				return false;
			}
			if(e.ctrlKey && e.shiftKey && e.keyCode == 'J'.charCodeAt(0)){
				return false;
			}
			if(e.ctrlKey && e.keyCode == 'U'.charCodeAt(0)){
				return false;
			}
			if(e.ctrlKey && e.shiftKey && e.keyCode == 'C'.charCodeAt(0)){
				return false;
			}      
		}		
	</script>
	<link rel="stylesheet" type="text/css" href="css/gaya-surat.css">
</head>
<body oncontextmenu="return false;">

  <div class="konten-naskah">
    <!-- Start Kop Surat -->
    <div class="kop-surat">
      <div class="logo-kop"><img src="images/logo-surat.png" width="250px" height="*"></div>
      <div class="alamat-kop">
        <b>PT.ASDP Indonesia Ferry (Persero)</b><br>
        <i>Jl. Jend Ahmad Yani Kav. 52 A, Jakarta 10510,</i><br>
        <i> Indonesia</i><br>
        tel : +6221 4208911-13-15 <br>
        fax : +6221 4210544 <br>
        web : www.indonesiaferry.co.id
      </div>
    </div>
    <!-- End Kop Surat -->

    <!-- Start Jenis Naskah -->
    <div class="jenis-naskah">
      <div class="nama-jenis-naskah"><u><b>PERJANJIAN ........</u></b></div>
      <div class="nama-jenis-naskah">ANTARA</div>
      <div class="nama-jenis-naskah">................</div>
      <div class="nomor-naskah">DENGAN</div>
      <div class="nomor-naskah">.............</div>
      <div class="nomor-naskah">NOMOR : .............</div>
    </div>
    <!-- End Jenis Naskah -->

    <!-- Start Isi Naskah -->
    <div class="isi-naskah">
      <table width="100%">
        <tr>
          <td width="79%">
            <ol type="a">
             <p>Pada hari ini, [Tanggal], di [Tempat], telah dibuat Perjanjian..................................................</p> 
            ....................................................................................................
             oleh dan antara :
             <br>
             [PT ASDP INDONESIA FERRY (PERSERO)] …………………………………………....................................................
              <br>  
             [Nama,         ]  
             …………………………………………………………………………………………………
              <br>
             PIHAK PERTAMA DAN PIHAK KEDUA...... secara bersama-sama disebut PARA PIHAK menerangkan hal-hal sebagai berikut: 
             …………………………………………..…………………………………………..……….. 
             <br>
             Berdasarkan hal-hal tersebut diatas PARA PIHAK dengan itikad baik dan saling menguntungkan, menyatakan telah sepakat dan setuju untuk membuat Perjanjian ini dengan ketentuan dan syarat-syarat sebagai berikut :
             <br>
             <p style="text-align: center"> Pasal 1
             <br>
             ...............................</p>
             <p style="text-align: center"> Pasal 2
             <br>
             ...............................</p>
             
             Perjanjian ini dibuat pada hari dan tanggal tersebut di atas dalam rangkap 2 (dua) bermeterai cukup dan keduanya mempunyai kekuatan hukum yang sama
            </ol>
          </td>
        </tr>
        
       
      </table>
    </div>
    <!-- End Isi Naskah -->

    <!-- Start Tanda Tangan -->
    <div class="tanda-tangan-kanan">
      <table width="100%">
        <tr>
          <td width="1%">,</td>
        </tr>
        <tr tyle="text-align: center">
          <td>PIHAK KEDUA</td>      
        </tr>
        <tr tyle="text-align: center">
          <td>Nama</td>      
        </tr>
      </table>
    </div>
    <!-- End Isi Naskah -->


    <!-- Start Tanda Tangan -->
    <div class="tanda-tangan-kanan">
      <table width="100%">
        <tr>
          <td width="40%"></td>
          <td width="1%"></td>
        </tr>
         <tr>
          <td width="40%"></td>
          <td width="1%"></td>
        </tr>
         <tr>
          <td width="40%"></td>
          <td width="1%"></td>
        </tr>
         <tr>
          <td width="40%"></td>
          <td width="1%"></td>
        </tr>
        <tr tyle="text-align: center">
          <td>PIHAK PERTAMA</td>      
        </tr>
        <tr tyle="text-align: center">
          <td>Nama</td>      
        </tr>
      </table>
    </div>
    <!-- End Isi Naskah -->

   

    <!-- Start Maker Surat -->
    <div class="maker-surat">
      <i>Valentino Rossi/22052019</i>
    </div>
    <!-- End Maker Surat -->

  </div>

</body>
</html>