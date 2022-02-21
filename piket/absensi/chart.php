 <?php  
    include '../../config/koneksi.php';
    include '../../library/fungsi.php';
    session_start();
    date_default_timezone_set("Asia/Jakarta");
    $aksi = new oop();
    @$dari = $_POST['dari'];
    @$sampai = $_POST['sampai'];
    $today = date("Y-m-d");
?>
<!DOCTYPE HTML>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="../../assets/css/bootstrap.css">
    <link rel="icon" href="../../assets/images/defaultimage.png">
    <title>Absensi Chart</title>
    <style type="text/css">

    </style>
</head>
<body>
    <br><br><br>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <form method="post">
                    <div class="col-md-6">
                        <div class="input-group">
                            <div class="input-group-addon" id="pri">Dari Tanggal</div>
                            <input type="date" name="dari" class="form-control" required value="<?php if(isset($_POST['dari'])){echo $dari;}else{echo $today;}?>">

                            <div class="input-group-addon" id="pri">Sampai Tanggal</div>
                            <input type="date" name="sampai" class="form-control" required value="<?php if(isset($_POST['sampai'])){echo $sampai;}else{echo $today;}?>"> 

                            <div class="input-group-btn">
                                <input type="submit" name="tampil" class="btn btn-primary" value="Tampil">
                            </div>
                        </div>
                    </div>
                </form>
                <br><br><br> 
            </div>
        </div>
    </div>   
    <?php  
        if (isset($_POST['tampil'])) { 
                // $link_print = "piket/absensi/export/format_print_grafik.php?dari=".$dari."&sampai=".$sampai;
                // $link_pdf = "piket/absensi/export/format_pdf.php?alasan&dari=".$dari."&sampai=".$sampai;
                // $link_excel = "piket/absensi/export/format_excel.php?alasan&dari=".$dari."&sampai=".$sampai;
                $link_print = "export/format_print_grafik.php?dari=".$dari."&sampai=".$sampai;
                $link_pdf = "export/format_pdf.php?alasan&dari=".$dari."&sampai=".$sampai;
                $link_excel = "export/format_excel.php?alasan&dari=".$dari."&sampai=".$sampai;
            ?>
            <div class="col-md-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">Grafik Alasan Siswa Tidak Hadir Periode <?php $aksi->format_tanggal($dari); echo " - ";$aksi->format_tanggal($sampai); ?></div>
                        <div class="panel-body">
                            <div class="col-md-12" style="margin-bottom:15px;">
                                <div class="col-md-4 pull-right" >
                                    <div class="input-group">
                                        <div class="input-group-btn">
                                            <a href="<?php echo $link_print; ?>" id="print" target="_blank" class="btn btn-success"><div class="glyphicon glyphicon-print"></div>&nbsp;Cetak</a>
                                            <a href="<?php echo $link_pdf; ?>" target="_blank" class="btn btn-success"><div class="glyphicon glyphicon-floppy-save"></div>&nbsp;Simpan PDF</a>
                                            <a href="<?php echo $link_excel; ?>" target="_blank" class="btn btn-success"><div class="glyphicon glyphicon-floppy-save"></div>&nbsp;Simpan Excel</a>
                                        </div>
                                    </div>
                                </div>          
                            </div>
                            <div id="container" style="width: 70%; min-height: 500px; margin: 0 auto"></div>
                        </div>
                    </div>
                </div>
       <?php } ?>

<script src="../../assets/plugins/highchart/code/highcharts.js"></script>
<script src="../../assets/plugins/highchart/code/modules/exporting.js"></script>


        <script type="text/javascript">
            Highcharts.theme = {
                colors: ['#dd4b39', '#337ab7', '#00c0ef', '#00a65a', '#f39c12', '#86E2D5', 
                         '#22313F', '#95A5A6', '#947CB0','#E9D460'],
                chart: {
                   backgroundColor: {
                        linearGradient: [0, 0, 500, 500],
                        stops: [
                            [0, 'rgba(20, 5, 255,0.07)'],
                            [1, 'rgba(255, 25, 100,0.03)']
                        ]
                    },
                },
                title: {
                    style: {
                        color: '#000',
                        font: 'bold 16px "Trebuchet MS", Verdana, sans-serif'
                    }
                },
                subtitle: {
                    style: {
                        color: '#666666',
                        font: 'bold 12px "Trebuchet MS", Verdana, sans-serif'
                    }
                },

                legend: {
                    itemStyle: {
                        font: '9pt Trebuchet MS, Verdana, sans-serif',
                        color: 'black'
                    },
                    itemHoverStyle:{
                        color: 'gray'
                    }   
                }
            };

            Highcharts.setOptions(Highcharts.theme);

            Highcharts.chart('container', {
                chart: {
                    type: 'column',
                    spacing : [20,15,20,15],
                },
                title: {
                    text: 'Grafik Alasan Siswa Tidak Hadir'
                },
                subtitle: {
                    text: 'Periode <?php $aksi->format_tanggal($dari);echo " - ";$aksi->format_tanggal($sampai); ?>'
                },
                xAxis: {
                    type: 'category',
                    
                },
                yAxis: {
                    min: 0,
                    title: {
                        text: 'Jumlah'
                    }
                },
                legend: {
                    enabled: true
                },
                tooltip: {
                    pointFormat: 'Jumlah Tidak Hadir Periode <?php $aksi->format_tanggal($dari);echo " -  ";$aksi->format_tanggal($sampai); ?>: <b>{point.y:1f} Kali</b>'
                },
                series: [
                    <?php  
                        $no = 0;
                        $table = "tbl_absensi_siswa";
                        $where = "WHERE tgl_absen BETWEEN '$dari' AND '$sampai'";
                        $sum = "semester,MONTH(tgl_absen) as bulan,YEAR(tgl_absen) as tahun,SUM(hadir) as jumlah_hadir,SUM(sakit) as jumlah_sakit,SUM(izin) as jumlah_izin,SUM(alpa) as jumlah_alpa,SUM(tugas) as jumlah_tugas";
                        $data = $aksi->tampil_sum($sum,$table,$where,"GROUP BY bulan,semester");
                        $total = $aksi->cekdata("tbl_izin_siswa WHERE tgl_izin BETWEEN '$dari' AND '$sampai'");
                        if (empty($data)) {
                            echo "<script>alert('Data Tidak Ada')</script>";
                        }else{
                            foreach ($data as $r) {
                                $bulan = $r['bulan'];
                                $tahun = $r['tahun'];

                                $whr = "WHERE hadir != '1' AND MONTH(tgl_absen) = '$bulan' AND YEAR(tgl_absen) = '$tahun' AND semester = '$r[semester]'";
                                $dat = "SUM(hadir) as jumlah_hadir,SUM(sakit) as jumlah_sakit,SUM(izin) as jumlah_izin,SUM(alpa) as jumlah_alpa,SUM(tugas) as jumlah_tugas";
                                $detail = $aksi->tampil_sum($dat,$table,$whr,"");
                                foreach ($detail as $c) {
                                   $sakit = $c['jumlah_sakit'];
                                   $izin = $c['jumlah_izin'];
                                   $tugas = $c['jumlah_tugas'];
                                   $alpa = $c['jumlah_alpa'];
                                }
                    ?>
                {
                    name: '<?php $aksi->bulan($bulan);echo " ".$tahun; ?>',
                    data: [ 
                            ['Sakit', <?php echo $sakit; ?>],
                            ['Izin', <?php echo $izin; ?>],
                            ['Tugas', <?php echo $tugas; ?>],
                            ['Alpa', <?php echo $alpa; ?>]
                          ],
                    pointWidth: 20,
                    dataLabels: {
                        enabled: true,
                        color: '#22313F',
                        align: 'center',
                        format: '{point.y:1f}', // one decimal
                        y: 1, // 10 pixels down from the top
                        style: {
                            fontSize: '20px',
                            fontFamily: 'Verdana, sans-serif'
                        }
                    }
                },
            <?php } } ?>
            ] });
		</script>
	</body>
</html>
