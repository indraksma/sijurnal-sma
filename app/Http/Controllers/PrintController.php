<?php

namespace App\Http\Controllers;

use App\Models\Agenda;
use App\Models\Jurnal;
use App\Models\Kelas;
use App\Models\Semester;
use App\Models\Presensi;
use App\Models\Siswa;
use App\Models\SiteConfig;
use App\Models\User;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PrintController extends Controller
{
    public function kbm(Request $request)
    {
        $semester_id = $request->semester_id;
        $semester = Semester::where('id', $semester_id)->first();
        $kop = SiteConfig::where('option_name', 'kop_surat')->first()->option_value;
        $school_name = SiteConfig::where('option_name', 'school_name')->first()->option_value;
        $jurnal = Jurnal::where('user_id', $request->user_id)->where('kelas_id', $request->kelas_id)->where('mata_pelajaran_id', $request->mapel_id)->where('semester_id', $request->semester_id)->get();
        if ($jurnal->isNotEmpty()) {
            $pdf = Pdf::loadView('print.laporanpembelajaran', ['jurnal' => $jurnal, 'school_name' => $school_name, 'kop' => $kop, 'semester' => $semester]);
            $filename = "Laporan-Pembelajaran-" . $jurnal[0]->mata_pelajaran->nama_mapel . "-" . $jurnal[0]->kelas->nama_kelas . ".pdf";
            return $pdf->stream($filename);
        } else {
            return back()->with('message', 'Data Tidak Ditemukan');
        }
    }

    public function jk(Request $request)
    {
        $kop = SiteConfig::where('option_name', 'kop_surat')->first()->option_value;
        $school_name = SiteConfig::where('option_name', 'school_name')->first()->option_value;
        $type = $request->jk_type;
        if ($type == 1) {
            $tanggal = $request->tanggal;
            $singlejurnal = Jurnal::where('kelas_id', $request->kelas_id)->where('tanggal', $tanggal)->first();
            if ($singlejurnal) {
                $jurnal = Jurnal::where('kelas_id', $request->kelas_id)->where('tanggal', $tanggal)->orderBy('jam_mulai', 'ASC')->get();
                $semester = Semester::where('id', $singlejurnal->semester_id)->first();
                $tahunajaran = $semester->tahun_ajaran->tahun_ajaran;
                $siswa = Siswa::where('kelas_id', $request->kelas_id)->orderBy('nama', 'ASC')->get();
                $presensi = [];
                $row = $jurnal->count();
                $kelas = Kelas::where('id', $request->kelas_id)->first();
                $jurnal_id = Jurnal::select('id')->where('kelas_id', $request->kelas_id)->where('tanggal', $tanggal)->orderBy('jam_mulai', 'ASC')->get();
                foreach ($siswa as $key => $value) {
                    $presensi[$key] = Presensi::where('siswa_id', $value->id)->whereIn('jurnal_id', $jurnal_id)->get();
                }
                $pdf = Pdf::loadView('print.laporanjk', compact('row', 'jurnal', 'siswa', 'semester', 'tahunajaran', 'presensi', 'kelas', 'tanggal', 'school_name', 'kop'));
                $tgl = date('d-m-Y', strtotime($tanggal));
                $filename = "Jurnal-Kelas-" . $tgl . "-" . $kelas->nama_kelas . ".pdf";
                return $pdf->stream($filename);
                // return view('print.laporanjk', compact('row', 'jurnal', 'siswa', 'semester', 'tahunajaran', 'presensi', 'kelas', 'tanggal'));
            } else {
                return back()->with('message', 'Data Tidak Ditemukan');
            }
        } elseif ($type == 2) {
            $semester_id = $request->semester_id;
            $countjurnal = Jurnal::where('kelas_id', $request->kelas_id)->where('semester_id', $semester_id)->count();
            if ($countjurnal > 0) {
                $semester = Semester::where('id', $semester_id)->first();
                $tahunajaran = $semester->tahun_ajaran->tahun_ajaran;
                $siswa = Siswa::where('kelas_id', $request->kelas_id)->orderBy('nama', 'ASC')->get();
                $kelas = Kelas::where('id', $request->kelas_id)->first();
                $query = "SELECT t.siswa_id, SUM(CASE WHEN presensi = 1 THEN 1 ELSE 0 END) AS sakitCount, SUM(CASE WHEN presensi = 2 THEN 1 ELSE 0 END) AS izinCount, SUM(CASE WHEN presensi = 3 THEN 1 ELSE 0 END) AS alphaCount, SUM(CASE WHEN presensi = 4 THEN 1 ELSE 0 END) AS dispenCount FROM (SELECT siswa_id, tanggal, semester_id, presensi FROM presensis JOIN jurnals ON presensis.jurnal_id = jurnals.id GROUP BY siswa_id, tanggal) t JOIN siswas ON t.siswa_id = siswas.id WHERE t.semester_id = " . $semester_id . " AND siswas.kelas_id = " . $request->kelas_id . " GROUP BY t.siswa_id, siswas.nama ORDER BY siswas.nis";
                $hadir = DB::select($query);
                $pdf = Pdf::loadView('print.laporanjksmt', compact('semester', 'tahunajaran', 'hadir', 'siswa', 'kelas', 'school_name', 'kop'));
                $filename = "Jurnal-Kelas-Semester-" . $semester->semester . "-" . $semester->tahun_ajaran->tahun_ajaran . "-" . $kelas->nama_kelas . ".pdf";
                return $pdf->download($filename);
                // return view('print.laporanjksmt', compact('semester', 'tahunajaran', 'hadir', 'siswa', 'kelas'));
            } else {
                return back()->with('message', 'Data Tidak Ditemukan');
            }
        }
    }

    public function agenda(Request $request)
    {
        $kop = SiteConfig::where('option_name', 'kop_surat')->first()->option_value;
        $tahun = $request->tahun;
        $bulan = $request->bulan;
        $user_id = $request->user_id;
        $school_name = SiteConfig::where('option_name', 'school_name')->first()->option_value;
        $date = Carbon::createFromDate($tahun, $bulan, 1);
        $agenda = Agenda::where('user_id', $user_id)->whereMonth('tanggal', '=', $bulan)->whereYear('tanggal', '=', $tahun)->count();
        if ($agenda > 0) {
            $user = User::where('id', $user_id)->first();
            $data = Agenda::where('user_id', $user_id)->whereMonth('tanggal', '=', $bulan)->whereYear('tanggal', '=', $tahun)->get();
            $pdf = Pdf::loadView('print.agenda', compact('data', 'user', 'school_name', 'bulan', 'tahun', 'date', 'kop'));
            $filename = "Agenda-Bulanan-" . $bulan . "-" . $tahun . "-" . $user->name . ".pdf";
            return $pdf->stream($filename);
        } else {
            return back()->with('message', 'Data Tidak Ditemukan');
        }
    }
}
