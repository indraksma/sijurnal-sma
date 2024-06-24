<?php

namespace App\Http\Livewire;

use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use App\Models\Jurusan;
use App\Models\Kelas;
use App\Models\Siswa;
use Exception;
use Illuminate\Support\Facades\DB;

class Rombel extends Component
{
    use LivewireAlert;
    public $jurusan_list, $nama_kelas, $kelas_list, $nama_kelas_list, $pindah_kelas_list, $kelas_id, $kelas, $jurusan_id, $siswa_list, $kelas_pindah, $kelasid_pindah, $jurusan_pindah;
    public $selected = [];
    public $showSiswa = false;
    private $cekform = true;
    protected $listeners = ['refresh' => '$refresh'];

    public function mount()
    {
        $this->jurusan_list = Jurusan::all();
    }

    public function render()
    {

        $this->kelas_list = Kelas::select('kelas')->distinct()->get();
        return view('livewire.rombel');
    }

    public function updatedKelas()
    {
        $this->kelas_id = '';
        if ($this->jurusan_id != NULL) {
            $this->nama_kelas_list = Kelas::where('kelas', $this->kelas)->where('jurusan_id', $this->jurusan_id)->get();
        }
        $this->cekForm();
    }

    public function updatedKelasPindah()
    {
        $this->kelasid_pindah = '';
        if ($this->jurusan_pindah != NULL) {
            $this->pindah_kelas_list = Kelas::where('kelas', $this->kelas_pindah)->where('jurusan_id', $this->jurusan_pindah)->get();
        }
    }

    public function updatedJurusanId()
    {
        $this->kelas_id = '';
        if ($this->kelas != NULL) {
            $this->nama_kelas_list = Kelas::where('kelas', $this->kelas)->where('jurusan_id', $this->jurusan_id)->get();
        }
        $this->cekForm();
    }

    public function updatedJurusanPindah()
    {
        $this->kelasid_pindah = '';
        if ($this->kelas_pindah != NULL) {
            $this->pindah_kelas_list = Kelas::where('kelas', $this->kelas_pindah)->where('jurusan_id', $this->jurusan_pindah)->get();
        }
    }

    public function updatedKelasId($id)
    {
        if ($this->kelas_id != '') {
            $this->siswa_list = Siswa::where('kelas_id', $id)->orderBy('nama', 'ASC')->get();
            $kelas = Kelas::where('id', $id)->first();
            $this->nama_kelas = $kelas->nama_kelas;
            $this->kelas_pindah = $this->kelas;
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
        if ($this->cekform) {
            $this->showSiswa = true;
        } else {
            $this->showSiswa = false;
        }
    }

    public function pindah()
    {
        if ($this->selected) {
            $siswa = $this->selected;
            $kelas_id = $this->kelasid_pindah;
            DB::beginTransaction();

            try {
                $updateKelas = Siswa::whereIn('id', $siswa)->update(['kelas_id' => $kelas_id]);
                DB::commit();
                $this->resetPindah();
                $this->dispatchBrowserEvent('storedData');
                $this->alert('success', 'Student class changed successfully!', [
                    'position' => 'center',
                    'timer' => 3000,
                    'toast' => true,
                ]);
            } catch (Exception $e) {
                DB::rollBack();
                $this->alert('error', 'Student class changing failed!', [
                    'position' => 'center',
                    'timer' => 3000,
                    'toast' => true,
                ]);
            }
            $this->reset('selected');
            $this->siswa_list = Siswa::where('kelas_id', $this->kelas_id)->orderBy('nama', 'ASC')->get();
        } else {
            $this->alert('error', 'No student selected!', [
                'position' => 'center',
                'timer' => 3000,
                'toast' => true,
            ]);
        }
    }

    public function resetPindah()
    {
        $this->reset(['kelasid_pindah', 'jurusan_pindah', 'kelas_pindah']);
    }
}
