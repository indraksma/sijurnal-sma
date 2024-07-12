<?php

namespace App\Http\Livewire;

use App\Models\Jurusan;
use App\Models\MataPelajaran as ModelsMataPelajaran;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithPagination;

class MataPelajaran extends Component
{
    use LivewireAlert, WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $mapel_id, $jurusan_id, $jurusan_list, $nama_mapel, $delete_id;
    protected $listeners = ['refresh' => '$refresh', 'edit' => 'edit', 'deleteId' => 'deleteId'];

    public function mount()
    {
        $this->jurusan_list = Jurusan::all();
    }

    public function render()
    {
        $data = ModelsMataPelajaran::orderBy('nama_mapel', 'ASC')->orderBy('jurusan_id', 'ASC')->paginate(10);
        return view('livewire.mata-pelajaran', [
            'mapel' => $data,
        ]);
    }

    public function store()
    {
        $mapel = ModelsMataPelajaran::updateOrCreate(['id' => $this->mapel_id], [
            'nama_mapel'   => $this->nama_mapel,
            'jurusan_id'   => $this->jurusan_id,
        ]);

        $this->alert('success', $this->mapel_id ? 'Mata Pelajaran updated successfully.' : 'Mata Pelajaran created successfully.', [
            'position' => 'center',
            'timer' => 3000,
            'toast' => true,
        ]);
        $this->resetInputFields();
        $this->emit('refreshMapelTable');
    }

    public function edit($id)
    {
        $mapel = ModelsMataPelajaran::where('id', $id)->first();
        $this->mapel_id = $mapel->id;
        $this->jurusan_id = $mapel->jurusan_id;
        $this->nama_mapel = $mapel->nama_mapel;
    }

    public function resetInputFields()
    {
        $this->reset(['mapel_id', 'jurusan_id', 'nama_mapel', 'delete_id']);
    }

    public function deleteId($id)
    {
        $this->delete_id = $id;
    }

    public function destroy()
    {
        ModelsMataPelajaran::destroy($this->delete_id);

        $this->alert('success', 'Mata Pelajaran deleted successfully.', [
            'position' => 'center',
            'timer' => 3000,
            'toast' => true,
        ]);
        $this->resetInputFields();
        $this->emit('refreshMapelTable');
    }
}
