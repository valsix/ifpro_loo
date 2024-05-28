<!DOCTYPE html>
<html>
<head>
	<title>Template Surat</title>
	<link rel="stylesheet" type="text/css" href="css/style.css">
</head>

<body>

<div class="content">
	<div class="menu">
		<ul>
			<li><a href="index.php?page=home">HOME</a></li>
			<li><a href="index.php?page=nota_dinas">Nota Dinas</a></li>
			<li><a href="index.php?page=memo_intern">Memo Intern</a></li>
			<li><a href="index.php?page=pengumuman">Pengumuman</a></li>
			<li><a href="index.php?page=undangan">Undangan</a></li>
			<li><a href="index.php?page=fax">Fax</a></li>
			<li><a href="index.php?page=surat_edaran">Surat Edaran</a></li>
			<li><a href="index.php?page=surat_keputusan">Surat Keputusan</a></li>
			<li><a href="index.php?page=surat_perintah">Surat Perintah</a></li>
		</ul>
	</div>

	<div class="badan">

	<?
	if(isset($_GET['page'])){
		$page = $_GET['page'];

		switch ($page) {
			case 'home':
				include "home.php";
				break;
			case 'nota_dinas':
				include "nota_dinas.php";
				break;
			case 'memo_intern':
				include "memo_intern.php";
				break;
			case 'pengumuman':
				include "pengumuman.php";
				break;
			case 'undangan':
				include "undangan.php";
				break;
			case 'fax':
				include "fax.php";
				break;
			case 'surat_edaran':
				include "surat_edaran.php";
				break;
			case 'surat_keputusan':
				include "surat_keputusan.php";
				break;
			case 'surat_perintah':
				include "surat_perintah.php";
				break;					
			default:
				echo "<center><h3>Maaf. Halaman tidak di temukan !</h3></center>";
				break;
		}
	}else{
		include "home.php";
	}

	?>

	</div>
</div>

</body>
</html>