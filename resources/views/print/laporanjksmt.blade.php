<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-Type" content="charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Sistem Informasi Jurnal Pembelajaran SMKN 1 Bawang">
    <meta name="author" content="IndraKus @indrakus_">
    <link rel="icon" type="image" href="{{ asset('assets/img/favicon.png') }}">
    <title>SIJURNAL - Laporan Kegiatan Pembelajaran Semester</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
        }

        .coba {
            font-size: 9pt;
            page-break-inside: avoid;
        }

        .coba td {
            padding-top: 1px;
            padding-bottom: 1px;
            padding-left: 1px;
            padding-right: 1px;
        }

        .ctr {
            text-align: center;
        }

        .le {
            text-align: left;
        }

        .bold {
            font-weight: bold;
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
    <img src="{{ $base64 }}" width="100%" style="margin-bottom: 50px;" />
    <h4 class="ctr" style="margin-bottom: 0; font-weight:normal;">DAFTAR HADIR
        KELAS<br><strong>{{ strtoupper($school_name) }}</strong><br>SEMESTER {{ strtoupper($semester->semester) }} TAHUN
        AJARAN {{ $tahunajaran }}</h4>
    <table style="margin-top: 10px;font-size:10pt;font-weight:bold;" width="100%">
        <tr>
            <td width="50%" style="vertical-align: bottom;">Kehadiran Siswa</td>
            <td width="50%" style="text-align:right">Kelas : {{ $kelas->nama_kelas }}</td>
        </tr>
    </table>
    <table border="1" class="coba ctr" width="100%" cellspacing="0">
        <colgroup>
            <col style="width: 30px;">
            <col style="width: 50px;">
            <col style="width: 200px;">
            <col style="width: auto;">
            <col style="width: 90px;">
        </colgroup>
        <tr style="font-weight: bold;background-color:lightgray;">
            <td rowspan="2" style="width: 30px;">No.</td>
            <td rowspan="2" style="width: 50px;">NIS</td>
            <td rowspan="2" style="width: 200px;">Nama</td>
            <td colspan="4">Presensi</td>
            <td rowspan="2" style="width: 80px;">Jumlah</td>
        </tr>
        <tr style="font-weight: bold;background-color:lightgray;">
            <td>S</td>
            <td>I</td>
            <td>A</td>
            <td>D</td>
        </tr>
        <?php
        $no = 1;
        foreach ($siswa as $key => $siswa) {
        ?>
        <tr>
            <td><?= $no ?></td>
            <td style="width: 50px;"><?= $siswa->nis ?></td>
            <td class="le"><?= strtoupper($siswa->nama) ?></td>
            <?php
            $jmltotal = intval($hadir[$key]->sakitCount) + intval($hadir[$key]->izinCount) + intval($hadir[$key]->alphaCount) + intval($hadir[$key]->dispenCount);
            echo "<td style='font-weight: bold'>" . $hadir[$key]->sakitCount . '</td>';
            echo "<td style='font-weight: bold'>" . $hadir[$key]->izinCount . '</td>';
            echo "<td style='font-weight: bold'>" . $hadir[$key]->alphaCount . '</td>';
            echo "<td style='font-weight: bold'>" . $hadir[$key]->dispenCount . '</td>';
            echo "<td style='font-weight: bold'>" . $jmltotal . '</td>';
            ?>
        </tr>
        <?php
            $no++;
        }
        ?>
    </table>
    <table width="100%" class="coba ctr" style="margin-top: 20px;">
        <td>&nbsp;</td>
        <td width="50%" style="padding-left: 50px;">
            Banjarnegara, {{ \Carbon\Carbon::now()->isoFormat('D MMMM Y') }}<br><br>
            Mengetahui,<br>
            Kepala Sekolah<br>
            <br />
            <br />
            <br />
            <br />
            {{ $semester->kepala_sekolah->nama }}<br>
            NIP. {{ $semester->kepala_sekolah->nip }}
        </td>
    </table>
</body>

</html>
