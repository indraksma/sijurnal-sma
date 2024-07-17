<?php

namespace App\Http\Livewire;

use App\Models\Kelas;
use App\Models\Siswa as ModelsSiswa;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\SiswaImport;
use Illuminate\Support\Facades\Storage;
use Livewire\WithFileUploads;
use App\Models\Jurusan;

class Siswa extends Component
{
    use LivewireAlert, WithPagination, WithFileUploads;

    public $kelas_id, $siswa_id, $kelas_list, $nama, $nis, $jk, $delete_id, $template_excel, $kelas, $jurusan_list, $jurusan_id, $nama_kelas_list, $searchTerm;
    public $iteration = 0;
    protected $paginationTheme = 'bootstrap';
    protected $listeners = ['refresh' => '$refresh', 'edit' => 'edit', 'deleteId' => 'deleteId'];

    public function mount()
    {
        $this->jurusan_list = Jurusan::all();
    }

    public function render()
    {
        $this->kelas_list = Kelas::select('kelas')->distinct()->get();
        $siswa = ModelsSiswa::select('siswas.nama', 'siswas.id', 'siswas.kelas_id', 'kelas.nama_kelas', 'siswas.nis', 'siswas.jk')->join('kelas', 'siswas.kelas_id', '=', 'kelas.id')->where(
            function ($sub_query) {
                $sub_query->where('siswas.nama', 'like', '%' . $this->searchTerm . '%')
                    ->orWhere('siswas.nis', 'like', '%' . $this->searchTerm . '%')
                    ->orWhere('kelas.nama_kelas', 'like', '%' . $this->searchTerm . '%');
            }
        )->orderBy('siswas.nama', 'ASC')->paginate(10);
        // $siswa = ModelsSiswa::latest()->paginate(10);
        return view('livewire.siswa', [
            'data_siswa' => $siswa,
            'iteration' => $this->iteration,
        ]);
    }

    public function updatedKelas()
    {
        $this->kelas_id = '';
        if ($this->jurusan_id != NULL) {
            $this->nama_kelas_list = Kelas::where('kelas', $this->kelas)->where('jurusan_id', $this->jurusan_id)->get();
        }
    }

    public function updatedJurusanId()
    {
        $this->kelas_id = '';
        if ($this->kelas != NULL) {
            $this->nama_kelas_list = Kelas::where('kelas', $this->kelas)->where('jurusan_id', $this->jurusan_id)->get();
        }
    }

    public function store()
    {
        $kelas = ModelsSiswa::updateOrCreate(['id' => $this->siswa_id], [
            'nama'      => $this->nama,
            'kelas_id'   => $this->kelas_id,
            'nis'   => $this->nis,
            'jk'   => $this->jk,
        ]);

        $this->alert('success', $this->siswa_id ? 'Siswa updated successfully.' : 'Siswa created successfully.', [
            'position' => 'center',
            'timer' => 3000,
            'toast' => true,
        ]);
        $this->resetInputFields();
        $this->emit('refresh');
    }

    public function edit($id)
    {
        $data = ModelsSiswa::where('id', $id)->first();
        $this->siswa_id = $data->id;
        $this->kelas_id = $data->kelas_id;
        $this->nis = $data->nis;
        $this->nama = $data->nama;
        $this->jk = $data->jk;
        $kelas = Kelas::where('id', $this->kelas_id)->first();
        $this->nama_kelas_list = Kelas::where('kelas', $kelas->kelas)->where('jurusan_id', $kelas->jurusan_id)->get();
        $this->kelas = $kelas->kelas;
        $this->jurusan_id = $kelas->jurusan_id;
    }

    public function resetInputFields()
    {
        $this->reset(['kelas_id', 'nama', 'nis', 'jk', 'siswa_id', 'delete_id', 'template_excel', 'kelas', 'jurusan_id', 'nama_kelas_list']);
    }

    public function deleteId($id)
    {
        $this->delete_id = $id;
    }

    public function import()
    {
        $file_path = $this->template_excel->store('files', 'public');
        Excel::import(new SiswaImport, storage_path('/app/public/' . $file_path));
        Storage::disk('public')->delete($file_path);

        $this->resetInputFields();
        $this->emit('refreshSiswaTable');
        $this->alert('success', 'Data berhasil diimport!', [
            'position' => 'center',
            'timer' => 3000,
            'toast' => true,
        ]);
        $this->iteration = $this->iteration + 1;
    }

    public function destroy()
    {
        ModelsSiswa::destroy($this->delete_id);

        $this->alert('success', 'Siswa deleted successfully.', [
            'position' => 'center',
            'timer' => 3000,
            'toast' => true,
        ]);
        $this->emit('refresh');
    }
}
