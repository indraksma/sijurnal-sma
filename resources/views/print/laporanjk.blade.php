<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Sistem Informasi Jurnal Pembelajaran">
    <meta name="author" content="IndraKus @indrakus_">
    <link rel="icon" type="image" href="{{ asset('assets/img/favicon.png') }}">
    <title>SIJURNAL - Laporan Kegiatan Pembelajaran</title>
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
    <h4 class="ctr" style="margin-bottom: 0; font-weight:normal;">DAFTAR HADIR DAN JURNAL
        KELAS<br><strong>{{ strtoupper($school_name) }}</strong><br>SEMESTER {{ strtoupper($semester->semester) }} TAHUN
        AJARAN <?= $tahunajaran ?></h4>
    <table style="margin-top: 10px;font-size:10pt;font-weight:bold;" width="100%">
        <tr>
            <td width="50%" rowspan="2" style="vertical-align: bottom;">A. Kehadiran Siswa</td>
            <td width="20%">Kelas</td>
            <td width="3%">:</td>
            <td>{{ $kelas->nama_kelas }}</td>
        </tr>
        <tr>
            <td>Hari, Tanggal</td>
            <td>:</td>
            <td>{{ \Carbon\Carbon::parse($tanggal)->isoFormat('dddd, D MMMM Y') }}</td>
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
            <td rowspan="2" style="width: 200px;" colspan="2">Nama</td>
            <td colspan="<?= $row ?>">Jam Ke</td>
            <td rowspan="2" style="width: 90px;">Keterangan</td>
        </tr>
        <tr style="font-weight: bold;background-color:lightgray;">
            <?php
            $jam = $jurnal;
            foreach ($jam as $jam) {
            ?>
            <td>{{ $jam->jam_mulai . ' - ' . $jam->jam_selesai }}</td>
            <?php
            }
            ?>
        </tr>
        <?php
        $no = 1;
        foreach ($siswa as $key => $siswa) {
        ?>
        <tr>
            <td><?= $no ?></td>
            <td style="width: 50px;"><?= $siswa->nis ?></td>
            <td class="le" colspan="2"><?= strtoupper($siswa->nama) ?></td>
            <?php
            foreach ($presensi[$key] as $datahadir) {
                if ($datahadir->presensi == 0) {
                    echo '<td><div style="font-family: ZapfDingbats, sans-serif;">4</div></td>';
                } elseif ($datahadir->presensi == 1) {
                    echo "<td style='font-weight: bold'>S</td>";
                } elseif ($datahadir->presensi == 2) {
                    echo "<td style='font-weight: bold'>I</td>";
                } elseif ($datahadir->presensi == 3) {
                    echo "<td style='font-weight: bold'>A</td>";
                } elseif ($datahadir->presensi == 4) {
                    echo "<td style='font-weight: bold'>D</td>";
                }
            }
            ?>
            <td>
                <?php
                $kehadiran = '';
                foreach ($presensi[$key] as $datahadir) {
                    if ($datahadir->presensi == 1) {
                        $kehadiran = 'Sakit';
                    } elseif ($datahadir->presensi == 2) {
                        $kehadiran = 'Izin';
                    } elseif ($datahadir->presensi == 3) {
                        $kehadiran = 'Alpha';
                    } elseif ($datahadir->presensi == 4) {
                        $kehadiran = 'Dispensasi';
                    }
                }
                echo $kehadiran;
                ?>
            </td>
        </tr>
        <?php
            $no++;
        }
        ?>
    </table>
    <table border="1" class="coba ctr" width="100%" cellspacing="0">
        <colgroup>
            <col style="width: 30px;">
            <col style="width: 50px;">
            <col style="width: 200px;">
            <col style="width: auto;">
            <col style="width: 90px;">
        </colgroup>
        <tr>
            <td colspan="9" class="le bold">B. Kehadiran Guru</td>
        </tr>
        <tr class="bold" style="background-color:lightgray;">
            <td rowspan="3">No</td>
            <td rowspan="3" colspan="3">Mata Pelajaran</td>
            <td rowspan="3">Jam Ke</td>
            <td colspan="4">Kehadiran Guru</td>
        </tr>
        <tr class="bold" style="background-color:lightgray;">
            <td colspan="2">Hadir</td>
            <td colspan="2">Tidak Hadir</td>
        </tr>
        <tr class="bold" style="background-color:lightgray;">
            <td>Mengajar</td>
            <td>Tugas</td>
            <td>Ada Tugas</td>
            <td>Tdk Ada Tugas</td>
        </tr>
        <?php
        $no = 1;
        $jurnal2 = $jurnal;
        foreach ($jurnal as $data) {
        ?>
        <tr>
            <td><?= $no ?></td>
            <td colspan="3"><?= $data->mata_pelajaran->nama_mapel ?></td>
            <td><?= $data->jam_mulai . ' - ' . $data->jam_selesai ?></td>
            @if ($data->verifikasi && $data->status == 1)
                <td>
                    @if ($data->verifikasi->tugas == 1)
                        <div style="font-family: ZapfDingbats, sans-serif;">4</div>
                    @endif
                </td>
                <td>
                    @if ($data->verifikasi->tugas == 2)
                        <div style="font-family: ZapfDingbats, sans-serif;">4</div>
                    @endif
                </td>
                <td>
                    @if ($data->verifikasi->tugas == 3)
                        <div style="font-family: ZapfDingbats, sans-serif;">4</div>
                    @endif
                </td>
                <td>
                    @if ($data->verifikasi->tugas == 4)
                        <div style="font-family: ZapfDingbats, sans-serif;">4</div>
                    @endif
                </td>
            @else
                <td>
                    <div style="font-family: ZapfDingbats, sans-serif;">4</div>
                </td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            @endif
        </tr>
        <?php
            $no++;
        }
        ?>
    </table>
    <table border="1" class="coba ctr" width="100%" cellspacing="0">
        <colgroup>
            <col style="width: 30px;">
            <col style="width: 50px;">
            <col style="width: 200px;">
            <col style="width: auto;">
            <col style="width: 90px;">
        </colgroup>
        <tr>
            <td colspan="9" class="le bold">C. Jurnal Kelas</td>
        </tr>
        <tr class="bold" style="background-color:lightgray;page-break-inside: avoid;page-break-after: auto;">
            <td rowspan="2">Jam ke</td>
            <td colspan="2" rowspan="2">Materi</td>
            <td colspan="2" rowspan="2">Nama Guru</td>
            <td colspan="5">Kehadiran Siswa</td>
        </tr>
        <tr class="bold" style="background-color:lightgray;page-break-inside: avoid;page-break-after: auto;">
            <td style="width:50px;">Hadir</td>
            <td style="width:50px;">S</td>
            <td style="width:50px;">I</td>
            <td style="width:50px;">A</td>
            <td style="width:50px;">D</td>
        </tr>
        <?php
        $no = 1;
        foreach ($jurnal2 as $jurnal2) {
        ?>
        <tr>
            <td>{{ $no }}</td>
            <td colspan="2">{{ $jurnal2->materi->materi }}</td>
            <td colspan="2">
                {{ $jurnal2->user->gelar_depan != null ? $jurnal2->user->gelar_depan . ' ' : '' }}{{ $jurnal2->user->name }}{{ $jurnal2->user->gelar_belakang != null ? ', ' . $jurnal2->user->gelar_belakang : '' }}
            </td>
            <td>{{ $jurnal2->hadir }}</td>
            <td>{{ $jurnal2->sakit }}</td>
            <td>{{ $jurnal2->izin }}</td>
            <td>{{ $jurnal2->alpha }}</td>
            <td>{{ $jurnal2->dispensasi }}</td>
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
