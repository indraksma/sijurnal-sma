<?php

namespace App\Http\Livewire;

use App\Models\Jurnal;
use App\Models\Jurusan;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\Materi;
use App\Models\Presensi;
use App\Models\Semester;
use App\Models\Siswa;
use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Illuminate\Support\Facades\DB;

class AddJurnal extends Component
{
    use LivewireAlert;
    public $showPresensi = false;
    public $kehadiran = [];
    public $semester_id, $tanggal, $kelas_id, $jurusan_id, $nama_kelas_list, $kelas, $jurusan_list, $kelas_list, $materi_list, $user_id, $guru_list, $mapel_list, $mapel_id, $materi_id, $materi, $ki_kd, $link_materi, $siswa_list, $jam_mulai, $jam_selesai;
    protected $listeners = ['refresh' => '$refresh'];
    private $cekform = true;

    public function mount()
    {
        /** @var \App\Models\User */
        $user = Auth::user();
        $this->user_id = $user->id;
        $this->guru_list = User::whereHas('roles', function ($q) {
            $q->where('name', 'user')->orWhere('name', 'admin');
        })->get();
        $this->jurusan_list = Jurusan::all();
        $this->tanggal = date('Y-m-d');
        $this->semester_id = Semester::where('is_active', '1')->first()->id;
    }

    public function render()
    {
        $this->kelas_list = Kelas::select('kelas')->distinct()->get();
        return view('livewire.add-jurnal');
    }

    public function updatedKelas()
    {
        $this->kelas_id = '';
        $this->nama_kelas_list = Kelas::where('kelas', $this->kelas)->get();
        $this->cekForm();
    }

    public function updatedKelasId($id)
    {
        $this->mapel_id = '';
        $this->siswa_list = Siswa::where('kelas_id', $id)->get();
        $kelas = Kelas::where('id', $id)->first();
        $jurusan_id = $kelas->jurusan_id;
        $this->mapel_list = MataPelajaran::where('jurusan_id', $jurusan_id)->orWhere('jurusan_id', 0)->get();
        $siswa = $this->siswa_list;
        if ($siswa->isNotEmpty()) {
            foreach ($siswa as $key => $data) {
                $this->kehadiran[$key] = 0;
            }
        }
        $this->cekForm();
    }

    public function updatedUserId()
    {
        $this->mapel_id = '';
        $this->cekForm();
    }

    public function updatedMapelId($id)
    {
        $this->materi_list = Materi::where('user_id', $this->user_id)->where('mata_pelajaran_id', $id)->get();
        $this->cekForm();
    }

    public function updatedMateriId()
    {
        $this->cekForm();
    }

    public function resetAddMateri()
    {
        $this->reset(['materi', 'ki_kd', 'link_materi']);
    }

    public function cekForm()
    {
        if ($this->kelas == "") {
            $this->cekform = false;
        }
        if ($this->jurusan_id == "") {
            $this->cekform = false;
        }
        if ($this->kelas_id == "") {
            $this->cekform = false;
        }
        if ($this->user_id == "") {
            $this->cekform = false;
        }
        if ($this->mapel_id == "") {
            $this->cekform = false;
        }
        if ($this->materi_id == "") {
            $this->cekform = false;
        }
        if ($this->jam_mulai == "") {
            $this->cekform = false;
        }
        if ($this->jam_selesai == "") {
            $this->cekform = false;
        }
        if ($this->cekform) {
            $this->showPresensi = true;
        } else {
            $this->showPresensi = false;
        }
    }

    public function storeMateri()
    {
        $materi = Materi::create([
            'materi'   => $this->materi,
            'mata_pelajaran_id'   => $this->mapel_id,
            'link'   => $this->link_materi,
            'ki_kd'   => $this->ki_kd,
            'user_id'   => $this->user_id,
        ]);

        $this->alert('success', $this->materi_id ? 'Materi updated successfully.' : 'Materi created successfully.', [
            'position' => 'center',
            'timer' => 3000,
            'toast' => true,
        ]);
        $this->resetAddMateri();
        $this->dispatchBrowserEvent('storedData');
        $this->materi_list = Materi::where('user_id', $this->user_id)->where('mata_pelajaran_id', $this->mapel_id)->get();
    }

    public function store()
    {
        $tanggal = date('Y-m-d');
        DB::beginTransaction();
        try {
            $jurnal = new Jurnal();
            $jurnal->user_id = $this->user_id;
            $jurnal->tanggal = $this->tanggal;
            $jurnal->tanggal_input = $tanggal;
            $jurnal->kelas_id = $this->kelas_id;
            $jurnal->materi_id = $this->materi_id;
            $jurnal->jam_selesai = $this->jam_selesai;
            $jurnal->jam_mulai = $this->jam_mulai;
            $jurnal->mata_pelajaran_id = $this->mapel_id;
            $jurnal->semester_id = $this->semester_id;
            $jurnal->save();
            $siswa = $this->siswa_list;

            foreach ($siswa as $key => $data) {
                $presensi = new Presensi();
                $presensi->jurnal_id = $jurnal->id;
                $presensi->siswa_id = $data->id;
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

            $this->alert('success', 'Jurnal Created Successfully', [
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
