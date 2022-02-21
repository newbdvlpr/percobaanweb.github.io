<?php  
	include '../../../config/koneksi.php';
	include '../../../library/fungsi.php';
	date_default_timezone_set("Asia/Jakarta");
	session_start();
	$aksi = new oop();
	if (empty($_SESSION['nip'])) {
		$aksi->redirect("../../../index.php");
	}
	$kesiswaan = $aksi->caridata("tbl_pegawai WHERE hak_akses = 'kesiswaan'");
	$rombel = $_GET['rombel'];
	$judul = "LAPORAN IZIN SISWA SMK WIKRAMA KOTA BOGOR ROMBEL ".$rombel;
	$table = "tbl_izin_siswa";

	if (isset($_GET['rombelM'])) {
		$bulan = $_GET['bulan'];
		$tahun = $_GET['tahun'];

		$where = "WHERE rombel = '$rombel' AND MONTH(tgl_izin)='$bulan' AND YEAR(tgl_izin)='$tahun'";
		$sum = "nis,nama,rombel,kode_rayon,COUNT(jenis_izin) as jumlah_izin";
		$data = $aksi->tampil_sum($sum,$table,$where,"GROUP BY nis ORDER BY nis ASC");

	}elseif (isset($_GET['rombelP'])) {
		$dari = $_GET['dari'];
		$sampai = $_GET['sampai'];

		$where = "WHERE rombel = '$rombel' AND tgl_izin BETWEEN '$dari' AND '$sampai'";
		$sum = "nis,nama,rombel,kode_rayon,COUNT(jenis_izin) as jumlah_izin";
		$data = $aksi->tampil_sum($sum,$table,$where,"GROUP BY nis ORDER BY nis ASC");
	}elseif (isset($_GET['rombelS'])) {
		$semester = $_GET['semester'];
		$tpel = $_GET['tp'];

		$where = "WHERE rombel = '$rombel' AND semester='$semester' AND tahun_pelajaran = '$tpel'";
		$sum = "nis,nama,rombel,kode_rayon,COUNT(jenis_izin) as jumlah_izin";
		$data = $aksi->tampil_sum($sum,$table,$where,"GROUP BY nis ORDER BY nis ASC");
	}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Print Laporan</title>
	<link rel="icon" href="../../../assets/images/defaultimage.png">
	<style type="text/css">
		html,body{
			margin:0;
			padding:0;
			height:100%;
			font-family: Arial;
		}
		#wrapper{
			/*background-color: gray;*/
			min-height:100%;
			position:relative;
		}
		#header{
			/*background-color: red;*/
			padding-top: 20px;
			padding-left:20px;
			/*padding:5px;*/
			height:100px;
		}
		#judul{
			font:12px Arial;
			font-weight: bolder;
		}
		#isi{
			/*background-color: green;*/
			padding-bottom:100px;
			padding-left:30px;
			margin-right:10px;
			font:12px Arial;
		}
		#footer{
			/*background-color: yellow;*/
			position:absolute;
			bottom:1px;
			padding-right: 100px;
			padding-left: 20px;
			width:100%;
			font-weight: bold;
		  	color:black;
		  	font:13px Arial;
		  }
	</style>
</head>
<body onload="window.print()">
	<div id="wrapper">
		<!-- bagian header laporan -->
		<div id="header">
			<table>
				<tr>
					<td><img src="../../../assets/images/logowk.png" alt="Logo" width="90px" height="90px"></td>
					<td></td>
					<td>
						<h4 style="margin: 0;margin-left: 2px;"><strong>YAYASAN PRAWITAMA</strong></h4>
						<h1 style="margin: 0;margin-left: 2px;"><strong>SMK WIKRAMA BOGOR</strong> </h1>
						<h5 style="margin: 0;margin-left: 2px;">Jl. Raya Wangun Kel. Sindangsari Kec. Bogor Timur</h5>
						<h5 style="margin: 0;margin-left: 2px;">Telp/Fax.(0251) 8242411, email : prohumasi@smkwikrama.net, website : www.smkwikrama.net</h5>
					</td>
				</tr>
			</table>
		</div>
		<!-- end bagian header laporan -->
		<hr style="border: 2px solid black;">

		<!-- bagian judul laporan -->
		<div id="judul">
			<?php if (isset($_GET['rombelM'])) { ?>
				<center><h4 style="margin-bottom: 15px;margin-top: 15px;"><strong><?php echo  $judul." BULAN ";$aksi->bulan_kapital($bulan);echo " TAHUN ".$tahun; ?></strong></h4></center>
			<?php }elseif (isset($_GET['rombelP'])) {?>
				<center><h4 style="margin-bottom: 15px;margin-top: 15px;"><strong><?php echo  $judul." PERIODE ";$aksi->tanggal_kapital($dari);echo "  -  ";$aksi->tanggal_kapital($sampai) ?></strong></h4></center>
			<?php }else{?>
				<center><h4 style="margin-bottom: 15px;margin-top: 15px;"><strong><?php echo  $judul." SEMESTER ".$semester." TAHUN PELAJARAN ".$tpel; ?></strong></h4
></center>
			<?php } ?>
		</div>
		<!-- end bagian judul laporan -->
		
		<!-- bagian isi laporan -->
		<div id="isi">
			<table width="100%" border="1" cellspacing="0" cellpadding="3" >
				<thead>
			    	<tr>
			        	<th width="4%"><center>No.</center></th>
			    		<th width="15%"><center>NIS</center></th>
			    		<th><center>Nama</center></th>
			    		<th width="10%">JK</th>
			    		<th width="15%"><center>Rayon</center></th>
			    		<th width="15%"><center>Jumlah Izin</center></th>
			    	</tr>
			    </thead>
			    <tbody>
			    	<?php  
			    		$no = 0;
			    		if (empty($data)) {
			    			$aksi->no_record(6);
			    		}else{
			    			foreach ($data as $r) {
			    				$no++;
			    				$siswa = $aksi->caridata("tbl_siswa WHERE nis = '$r[nis]'");
	                			$rayon = $aksi->caridata("tbl_rayon WHERE kode_rayon = '$r[kode_rayon]'");
	                		 ?>
	                		 	<tr>
			             			<td align="center"><?php echo $no; ?>.</td>
			             			<td align="center"><?php echo $r['nis']; ?></td>
			             			<td><?php echo $r['nama']; ?></td>
			             			<td align="center">
			             				<?php 
			             					if ($siswa['jk']=="L") {
			             						echo "Laki-laki";
			             					}else{
			             						echo "Perempuan";
			             					}
			             				 ?>
			             			</td>
			             			<td align="center"><?php echo $rayon['rayon']; ?></td>
				    			<td align="center"><?php echo $r['jumlah_izin']; ?></td>
				    		</tr>
    			 <?php } } ?>
			    </tbody>
			</table>
		</div>
		<!-- end bagian isi laporan -->

		<!-- bagian tanda tangan -->
		<div id="footer">
			<!-- tanda tangan kiri -->
			<table align="left" style="margin-left: 10px;">
				<tr><td>&nbsp;</td></tr>
				<tr>
					<td align="left">Mengetahui</td>
				</tr>
				<tr>
					<td align="left">Wakasek Kesiswaan,</td>
				</tr>
				<tr><td>&nbsp;</td></tr>
				<tr><td>&nbsp;</td></tr>
				<tr><td>&nbsp;</td></tr>
				<tr>
					<td align="left"><?php echo $kesiswaan['nama']; ?></td>
				</tr>
				<tr><td>&nbsp;</td></tr>
			</table>
			<!-- end tanda tangan kiri -->

			<!-- tanda tangan kanan -->
			<table align="right" style="margin-right: 40px;">
				<tr><td>&nbsp;</td></tr>
				<tr>
					<td align="left">Bogor, <?php $aksi->hari(date("N"));echo " ";$aksi->format_tanggal(date("Y-m-d")) ?></td>
				</tr>
				<tr>
					<td align="left">Petugas Piket,</td>
				</tr>
				<tr><td>&nbsp;</td></tr>
				<tr><td>&nbsp;</td></tr>
				<tr><td>&nbsp;</td></tr>
				<tr>
					<td align="left"><?php echo $_SESSION['nama']; ?></td>
				</tr>
				<tr><td>&nbsp;</td></tr>
			</table>
			<!-- end tanda tangan kanan -->
		</div>
		<!-- end bagian tanda tangan -->
	</div>
</body>
</html>