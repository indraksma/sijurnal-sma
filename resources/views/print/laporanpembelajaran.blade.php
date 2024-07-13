<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Sistem Informasi Jurnal Pembelajaran">
    <meta name="author" content="IndraKus @indrakus_">
    <link rel="icon" type="image" href="<?= asset('assets/img/favicon.png') ?>">
    <title>SIJURNAL - Laporan Kegiatan Pembelajaran</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 12px;
        }

        .coba td {
            padding-top: 7px;
            padding-bottom: 7px;
            padding-left: 5px;
            padding-right: 5px;
        }

        .topik td {
            padding-top: 0px;
            padding-bottom: 0px;
            padding-left: 0px;
            padding-right: 0px;
            font-size: 10px;
            text-align: left;
            vertical-align: top;
        }

        .ctr {
            text-align: center;
        }

        .absensi {
            padding-top: 0px;
            padding-bottom: 0px;
            padding-left: 25px;
            padding-right: 10px;
            font-size: 10px;
            text-align: left;
            vertical-align: top;
        }
    </style>
</head>

<body>
    <?php
    $path = public_path('/storage/img/' . $kop);
    $type = pathinfo($path, PATHINFO_EXTENSION);
    $data64 = file_get_contents($path);
    $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data64);
    ?>
    <img src="{{ $base64 }}" width="100%" style="margin-bottom: 20px;" />
    <h3 class="ctr">JURNAL KEGIATAN PEMBELAJARAN<br>{{ strtoupper($school_name) }}</h3>
    <table style="margin-bottom: 20px;">
        <tr>
            <td width="150px">Kelas</td>
            <td width="10px">:</td>
            <td>{{ $jurnal[0]->kelas->nama_kelas }}</td>
        </tr>
        <tr>
            <td width="150px">Mata Pelajaran</td>
            <td width="10px">:</td>
            <td>{{ $jurnal[0]->mata_pelajaran->nama_mapel }}</td>
        </tr>
        <tr>
            <td width="150px">Semester</td>
            <td width="10px">:</td>
            <td>{{ $jurnal[0]->semester->semester }}</td>
        </tr>
        <tr>
            <td width="150px">Tahun Pelajaran</td>
            <td width="10px">:</td>
            <td>{{ $jurnal[0]->semester->tahun_ajaran->tahun_ajaran }}</td>
        </tr>
        <tr>
            <td width="150px">Guru Pengampu</td>
            <td width="10px">:</td>
            <td>{{ $jurnal[0]->user->name }}</td>
        </tr>
    </table>
    <table border="1" class="coba ctr" width="100%" cellspacing="0">
        <tr style="font-weight: bold;">
            <td width="20px">No.</td>
            <td width="100px">Hari/Tanggal</td>
            <td width="60px">Jam Ke</td>
            <td width="150px">Uraian Materi / Kegiatan</td>
            <td width="150px">Absensi Siswa</td>
        </tr>
        <?php
        $no = 1;
        foreach ($jurnal as $data) { ?>
        <tr>
            <td class="ctr"><?= $no ?></td>
            <td>{{ \Carbon\Carbon::parse($data->tanggal)->isoFormat('dddd, D MMMM Y') }}</td>
            <td><?= $data->jam_mulai . ' - ' . $data->jam_selesai ?></td>
            <td>
                <div style="width: inherit; word-wrap:break-word;">
                    <table border="0" class="topik" width="100%" cellspacing="0" cellpadding="0">
                        <tr>
                            <td style="width:30px">Topik</td>
                            <td style="width:1px">:</td>
                            <td><?= $data->materi->materi ?></td>
                        </tr>
                        <tr>
                            <td>Materi</td>
                            <td style="width:1px">:</td>
                            <td><?= $data->materi->ki_kd ?></td>
                        </tr>
                        <?php
                        if ($data->materi->link_materi != null) {
                            echo '<tr><td>Materi</td><td >:</td><td >' . $data->materi->link_materi . '</td></tr>';
                        }
                        ?>
                    </table>
                </div>
            </td>
            <td>
                <?php
                $presensi = $data->presensis()->where('presensi', '>', '0')->get();
                if ($presensi->isNotEmpty()) {
                    echo "<ol class='absensi'>";
                    foreach ($presensi as $datapr) {
                        if ($datapr->presensi == '1') {
                            $ket = 'Sakit';
                        } elseif ($datapr->presensi == '2') {
                            $ket = 'Izin';
                        } elseif ($datapr->presensi == '3') {
                            $ket = 'Alpha';
                        } elseif ($datapr->presensi == '4') {
                            $ket = 'Dispensasi';
                        }
                        echo '<li>' . $datapr->siswa->nama . ' (' . $ket . ')</li>';
                    }
                    echo '</ol>';
                } else {
                    echo '-';
                }
                ?>
            </td>
        </tr>
        <?php
            $no++;
        }
        ?>
    </table>
    <table width="100%" class="ctr" style="margin-top: 20px;">
        <tr>
            <td width="50%" style="padding-right: 20%">
                <br>
                Mengetahui,<br>
                Kepala Sekolah<br>
                <br>
                <br>
                <br>
                <br>
                <br>
                {{-- <img src="./assets/temp/qrlogo.png" width="90px"><br> --}}
                {{ $jurnal[0]->semester->kepala_sekolah->nama }}<br>
                NIP. {{ $jurnal[0]->semester->kepala_sekolah->nip }}
            </td>
            <td width="50%" style="padding-left: 20%">
                Banjarnegara, {{ \Carbon\Carbon::now()->isoFormat('D MMMM Y') }}<br>
                <br>
                Guru Mata Pelajaran
                <br>
                <br>
                <br>
                <br>
                <br>
                <br>
                {{ $jurnal[0]->user->gelar_depan != null ? $jurnal[0]->user->gelar_depan . ' ' : '' }}<?= $jurnal[0]->user->name ?>{{ $jurnal[0]->user->gelar_belakang != null ? ', ' . $jurnal[0]->user->gelar_belakang : '' }}<br>
                NIP. <?php if ($jurnal[0]->user->nip == null) {
                    echo '-';
                } else {
                    echo $jurnal[0]->user->nip;
                } ?>
            </td>
        </tr>
    </table>
</body>

</html>
