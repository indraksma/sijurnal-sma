<?php

namespace App\Http\Livewire;

use App\Models\Jurusan;
use Livewire\Component;
use App\Models\Kelas as ModelsKelas;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\WithPagination;

class Kelas extends Component
{
    use WithPagination, LivewireAlert;

    public $kelas_id, $jurusan_id, $jurusan_list, $rombel, $kelas, $delete_id;
    protected $listeners = ['refresh' => '$refresh'];
    protected $paginationTheme = 'bootstrap';

    public function mount()
    {
        $this->jurusan_list = Jurusan::all();
    }

    public function render()
    {
        $kelas = ModelsKelas::latest()->paginate(10);
        return view('livewire.kelas', ['data_kelas' => $kelas]);
    }

    public function store()
    {
        $kode_jurusan = Jurusan::where('id', $this->jurusan_id)->first()->kode_jurusan;
        $nama_kelas = $this->kelas . ' ' . $this->rombel;
        $kelas = ModelsKelas::updateOrCreate(['id' => $this->kelas_id], [
            'kelas'      => $this->kelas,
            'jurusan_id'   => $this->jurusan_id,
            'rombel'   => $this->rombel,
            'nama_kelas'   => $nama_kelas,
        ]);

        $this->alert('success', $this->kelas_id ? 'Kelas updated successfully.' : 'Kelas created successfully.', [
            'position' => 'center',
            'timer' => 3000,
            'toast' => true,
        ]);
        $this->resetInputFields();
        $this->emit('refresh');
    }

    public function edit($id)
    {
        $kelas = ModelsKelas::where('id', $id)->first();
        $this->kelas_id = $kelas->id;
        $this->jurusan_id = $kelas->jurusan_id;
        $this->kelas = $kelas->kelas;
        $this->rombel = $kelas->rombel;
    }

    public function resetInputFields()
    {
        $this->reset(['kelas_id', 'jurusan_id', 'kelas', 'rombel', 'delete_id']);
    }

    public function deleteId($id)
    {
        $this->delete_id = $id;
    }

    public function destroy()
    {
        ModelsKelas::destroy($this->delete_id);

        $this->alert('success', 'Kelas deleted successfully.', [
            'position' => 'center',
            'timer' => 3000,
            'toast' => true,
        ]);
        $this->resetInputFields();
        $this->emit('refresh');
    }
}
