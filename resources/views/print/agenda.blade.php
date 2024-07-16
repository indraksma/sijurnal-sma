<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Sistem Informasi Jurnal Pembelajaran">
    <meta name="author" content="IndraKus @indrakus_">
    <link rel="icon" type="image" href="<?= asset('assets/img/favicon.png') ?>">
    <title>SIJURNAL - Agenda Guru Bulanan</title>
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
    <img src="{{ $base64 }}" width="100%" style="margin-bottom: 10px;" />
    <h3 class="ctr">AGENDA GURU<br>{{ strtoupper($school_name) }}</h3>
    <table style="margin-bottom: 20px;">
        <tr>
            <td width="150px">Nama Guru</td>
            <td width="10px">:</td>
            <td>{{ $user->gelar_depan != null ? $user->gelar_depan . ' ' : '' }}{{ $user->name }}{{ $user->gelar_belakang != null ? ', ' . $user->gelar_belakang : '' }}
            </td>
        </tr>
        <tr>
            <td width="150px">Bulan</td>
            <td width="10px">:</td>
            <td>{{ $date->isoFormat('MMMM') }}</td>
        </tr>
        <tr>
            <td width="150px">Tahun</td>
            <td width="10px">:</td>
            <td>{{ $date->isoFormat('Y') }}</td>
        </tr>
    </table>
    <table border="1" class="coba ctr" width="100%" cellspacing="0">
        <tr style="font-weight: bold;">
            <td width="20px">No.</td>
            <td width="100px">Hari/Tanggal</td>
            <td width="60px">Waktu</td>
            <td width="150px">Rincian</td>
            <td width="150px">Keterangan</td>
        </tr>
        <?php
        $no = 1;
        foreach ($data as $datas) { ?>
        <tr>
            <td class="ctr"><?= $no ?></td>
            <td>{{ \Carbon\Carbon::parse($datas->tanggal)->isoFormat('dddd, D MMMM Y') }}</td>
            <td>{{ \Carbon\Carbon::parse($datas->waktu)->format('H:i') }}</td>
            <td>{{ $datas->rincian }}</td>
            <td>{{ $datas->keterangan }}</td>
        </tr>
        <?php
            $no++;
        }
        ?>
    </table>
    <table width="100%" class="ctr" style="margin-top: 20px;">
        <tr>
            <td width="50%" style="padding-right: 20%">
                &nbsp;
            </td>
            <td width="50%" style="padding-left: 20%">
                Banjarnegara, {{ \Carbon\Carbon::now()->isoFormat('D MMMM Y') }}<br>
                <br>
                <br>
                <br>
                <br>
                <br>
                {{ $user->gelar_depan != null ? $user->gelar_depan . ' ' : '' }}{{ $user->name }}{{ $user->gelar_belakang != null ? ', ' . $user->gelar_belakang : '' }}<br>
                NIP. <?php if ($user->nip == null) {
                    echo '-';
                } else {
                    echo $user->nip;
                } ?>
            </td>
        </tr>
    </table>
</body>

</html>
