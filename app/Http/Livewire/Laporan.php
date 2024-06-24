<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Jurusan;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\Semester;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class Laporan extends Component
{
    use LivewireAlert;
    public $tanggal, $jk_type, $modal, $jurusan_id, $jurusan_list, $kelas, $kelas_id, $kelas_list, $nama_kelas_list, $user_id, $guru_list, $nama_guru, $mapel_id, $semester_id, $mapel_list, $semester_list;
    private $cekform = true;
    public $showPrintBtn = false;
    public $showTanggal = false;

    public function mount()
    {

        /** @var \App\Models\User */
        $user = Auth::user();
        if ($user->hasRole(['admin', 'user'])) {
            $this->guru_list = User::whereHas('roles', function ($q) {
                $q->where('name', 'user')->orWhere('name', 'admin');
            })->get();
            $this->nama_guru = $user->name;
        } else {
            $this->guru_list = User::all();
        }
        $this->user_id = $user->id;
        $this->jurusan_list = Jurusan::all();
        $this->mapel_list = MataPelajaran::all();
        $this->semester_list = Semester::all();
        // $this->semester_id = Semester::where('is_active', '1')->first()->id;
    }

    public function render()
    {
        $this->kelas_list = Kelas::select('kelas')->distinct()->get();
        return view('livewire.laporan');
    }


    public function updatedKelas($id)
    {
        $this->kelas_id = '';
        if ($this->jurusan_id != NULL) {
            $this->nama_kelas_list = Kelas::where('kelas', $id)->where('jurusan_id', $this->jurusan_id)->get();
        }
        $this->cekForm();
    }

    public function updatedJurusanId()
    {
        $this->kelas_id = '';
        if ($this->kelas != NULL) {
            $this->nama_kelas_list = Kelas::where('kelas', $this->kelas)->where('jurusan_id', $this->jurusan_id)->get();
        }
        $this->cekForm();
    }

    public function updatedKelasId()
    {
        $this->cekForm();
    }

    public function updatedSemesterId()
    {
        $this->cekForm();
    }

    public function updatedMapelId()
    {
        $this->cekForm();
    }

    public function updatedJkType($type)
    {
        if ($type == 1) {
            $this->showTanggal = true;
            $this->tanggal = date('Y-m-d');
        } else {
            $this->showTanggal = false;
            $this->semester_id = Semester::where('is_active', '1')->first()->id;
        }
        $this->cekForm();
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
        if ($this->modal == 1) {
            if ($this->mapel_id == "") {
                $this->cekform = false;
            }
            if ($this->semester_id == "") {
                $this->cekform = false;
            }
        } else {
            if ($this->jk_type == "") {
                $this->cekform = false;
            }
            if ($this->jk_type && $this->jk_type == 2) {
                if ($this->semester_id == "") {
                    $this->cekform = false;
                }
            }
            if ($this->jk_type && $this->jk_type == 1) {
                if ($this->tanggal == "") {
                    $this->cekform = false;
                }
            }
        }
        if ($this->cekform) {
            $this->showPrintBtn = true;
        } else {
            $this->showPrintBtn = false;
        }
    }

    public function modalForm($type)
    {
        if ($type == 1) {
            $this->modal = 1;
            $this->semester_id = Semester::where('is_active', '1')->first()->id;
        } else {
            $this->modal = 2;
        }
    }

    public function resetInputFields($type)
    {
        if ($type == 1) {
            $this->reset(['kelas', 'jurusan_id', 'kelas_id', 'semester_id', 'mapel_id', 'nama_kelas_list']);
        } else {
            $this->reset(['kelas', 'jurusan_id', 'kelas_id', 'semester_id', 'jk_type', 'nama_kelas_list']);
        }
    }
}
