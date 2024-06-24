<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Models\Jurnal;
use App\Models\Jurusan;
use App\Models\MataPelajaran;
use App\Models\Kelas;
use App\Models\Materi;
use App\Models\Presensi;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Illuminate\Support\Facades\DB;

class EditJurnal extends Component
{
    use LivewireAlert;
    public $idjurnal;
    protected $queryString = ['idjurnal'];
    public $kehadiran = [];
    private $cekform = true;
    public $tanggal, $kelas_id, $jurusan_id, $nama_kelas_list, $kelas, $jurusan_list, $kelas_list, $materi_list, $user_id, $guru_list, $mapel_list, $mapel_id, $materi_id, $materi, $ki_kd, $link_materi, $siswa_list, $jam_mulai, $jam_selesai;

    public function mount()
    {
        $jurnal = Jurnal::where('id', $this->idjurnal)->first();
        $this->tanggal = $jurnal->tanggal;
        $this->jam_mulai = $jurnal->jam_mulai;
        $this->jam_selesai = $jurnal->jam_selesai;
        $this->user_id = $jurnal->user_id;
        $this->guru_list = User::whereHas('roles', function ($q) {
            $q->where('name', 'user')->orWhere('name', 'admin');
        })->get();
        $this->jurusan_list = Jurusan::all();
        $this->jurusan_id = $jurnal->kelas->jurusan_id;
        $this->mapel_list = MataPelajaran::all();
        $this->mapel_id = $jurnal->mata_pelajaran_id;
        $this->kelas = $jurnal->kelas->kelas;
        $this->kelas_id = $jurnal->kelas_id;
        $list_siswa = Presensi::where('jurnal_id', $jurnal->id)->get();
        $this->siswa_list = $list_siswa;
        foreach ($list_siswa as $key => $siswa) {
            $this->kehadiran[$key] = $siswa->presensi;
        }
        $this->nama_kelas_list = Kelas::where('kelas', $jurnal->kelas->kelas)->where('jurusan_id', $jurnal->kelas->jurusan_id)->get();
        $this->materi_list = Materi::where('user_id', $jurnal->user_id)->where('mata_pelajaran_id', $jurnal->mata_pelajaran_id)->get();
        $this->materi_id = $jurnal->materi_id;
    }

    public function render()
    {
        $this->kelas_list = Kelas::select('kelas')->distinct()->get();
        return view('livewire.edit-jurnal');
    }

    public function updatedMapelId($id)
    {
        $this->materi_id = '';
        $this->materi_list = Materi::where('user_id', $this->user_id)->where('mata_pelajaran_id', $id)->get();
    }


    public function store()
    {
        DB::beginTransaction();
        try {
            $jurnal = Jurnal::where('id', $this->idjurnal)->first();
            $jurnal->materi_id = $this->materi_id;
            $jurnal->jam_selesai = $this->jam_selesai;
            $jurnal->jam_mulai = $this->jam_mulai;
            $jurnal->mata_pelajaran_id = $this->mapel_id;
            $jurnal->save();
            $siswa = $this->siswa_list;

            foreach ($siswa as $key => $data) {
                $presensi = Presensi::where('id', $data->id)->first();
                $presensi->presensi = $this->kehadiran[$key];
                $presensi->save();
            }

            $count_hadir = Presensi::where('jurnal_id', $jurnal->id)->where('presensi', 0)->count();
            $count_sakit = Presensi::where('jurnal_id', $jurnal->id)->where('presensi', 1)->count();
            $count_izin = Presensi::where('jurnal_id', $jurnal->id)->where('presensi', 2)->count();
            $count_alpha = Presensi::where('jurnal_id', $jurnal->id)->where('presensi', 3)->count();
            $count_dispen = Presensi::where('jurnal_id', $jurnal->id)->where('presensi', 4)->count();

            $jurnal->hadir = $count_hadir;
            $jurnal->sakit = $count_sakit;
            $jurnal->izin = $count_izin;
            $jurnal->alpha = $count_alpha;
            $jurnal->dispensasi = $count_dispen;
            $jurnal->update();

            DB::commit();

            $this->alert('success', 'Jurnal Updated Successfully', [
                'position' => 'center',
                'timer' => 3000,
                'toast' => true,
            ]);
            return redirect()->route('jurnal');
        } catch (\Exception $e) {

            DB::rollback();

            $this->alert('warning', 'Something Went Wrong!', [
                'position' => 'center',
                'timer' => 3000,
                'toast' => true,
            ]);
        }
    }
}
