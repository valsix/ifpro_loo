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
      <div class="nama-jenis-naskah"><u>PENGUMUMAN</u></div>
      <div class="nomor-naskah">NOMOR : PENG.4/HM.02/2019/DP</div>
    </div>
    <!-- End Jenis Naskah -->

    <!-- Start Tentang Naskah -->
    <div class="jenis-naskah">
      <div class="nomor-naskah"><b>TENTANG</b></div>
      <div class="nomor-naskah">PERINGATAN HARI KEBANGKITAN NASIONAL KE - 111 TAHUN 2019</div>
    </div>
    <!-- End Tentang Naskah -->

    <!-- Start Isi Naskah -->
    <div class="isi-naskah">
      <ol>
        <li>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas sed faucibus dui. Morbi tempor, risus at sodales fringilla, ligula lorem tincidunt elit, ac efficitur ligula dolor id lorem. Donec vel augue lectus. Proin facilisis metus quis mi rhoncus luctus. Pellentesque ultrices placerat eros id luctus. Pellentesque vestibulum bibendum eros id posuere. Pellentesque ornare tincidunt lacus quis iaculis. Ut at consectetur metus. Proin dignissim nunc at nulla auctor sagittis. Maecenas id tempor nisl. Phasellus a neque eget turpis bibendum auctor. Vestibulum sit amet ornare lorem. Aenean sollicitudin mauris libero, sit amet mattis ipsum vestibulum et. Maecenas suscipit, nulla finibus convallis mattis, lacus odio ultrices tortor, varius sodales mauris erat lobortis velit. Aliquam erat volutpat. Nulla porta finibus euismod.</li>
        <li>Suspendisse dui odio, ornare quis tristique quis, convallis vitae quam. Nullam pellentesque nisi a lacus sagittis pellentesque. Morbi vestibulum consectetur rutrum. Aliquam at justo vel felis commodo cursus et id velit. Aenean fermentum ullamcorper semper. Morbi eget odio urna. Aenean dapibus ligula ante, sed elementum turpis lacinia in. Donec eu congue lacus, ac malesuada mi. Maecenas sodales risus orci, tempor commodo lacus porttitor et. Maecenas eget dui ornare nibh tincidunt sodales eu sit amet dui. Aenean non fringilla velit. Sed interdum, lectus ultricies condimentum ultricies, sapien mi laoreet magna, vel pharetra nisi purus vel nisl. Vestibulum a felis porttitor, volutpat orci nec, dictum risus. Proin sit amet risus venenatis, pretium augue ac, lacinia turpis. Quisque interdum dignissim mauris, eu tincidunt neque tincidunt a. Curabitur tempor augue commodo pellentesque viverra.</li> 
        <li>Curabitur et nisl risus. Sed in mi vel lectus aliquet venenatis vitae non purus. Mauris scelerisque erat sit amet hendrerit vestibulum. Aliquam eget sagittis felis. Interdum et malesuada fames ac ante ipsum primis in faucibus. Quisque eget purus tristique, commodo eros id, vestibulum est. Quisque in facilisis nisl. Proin quam libero, cursus ut eleifend vitae, vehicula non ex.</li>
      </ol>
    </div>
    <!-- End Isi Naskah -->

    <!-- Start Tanda Tangan -->
    <div class="tanda-tangan-kanan">
      <table width="100%">
        <tr>
          <td width="40%">Dikeluarkan di</td>
          <td width="1%">:</td>
          <td width="59%">Jakarta</td>
        </tr>
        <tr class="border-bottom">
          <td>Pada tanggal</td>
          <td>:</td>
          <td>17 Mei 2019</td>
        </tr>
      </table>
      <br>&nbsp;
      <br>VICE PRESIDENT CORPORATE SECRETARY
      <br><img src="images/signature.png" width="250px" height="*">
      <br><u><b>LOREM IPSUM DOLOR SIT AMET, S.E, MM.</b></u>
    </div>
    <!-- End Isi Naskah -->

    <!-- Start Tembusan -->
    <div class="tembusan">
      <b><u>Tembusan Yth. :</u></b>
      <ol>
        <li>General Manager Kantor Cabang PT.ASDP Indonesia Ferry (Persero)</li>
        <li>Vice President Airport Facilities and Maintenance</li>
        <li>Vice President General Services</li>
        <li>Vice President Human Capital</li>
      </ol>
    </div>
    <!-- End Tembusan -->

    <!-- Start Maker Surat -->
    <div class="maker-surat">
      <i>Valentino Rossi/22052019</i>
    </div>
    <!-- End Maker Surat -->

  </div>

</body>
</html>