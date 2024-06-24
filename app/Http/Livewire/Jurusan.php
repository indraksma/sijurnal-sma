<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Jurusan as ModelsJurusan;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\WithPagination;

class Jurusan extends Component
{
    use WithPagination, LivewireAlert;

    public $jurusan_id, $nama, $kode, $delete_id;
    protected $listeners = ['refresh' => '$refresh'];
    protected $paginationTheme = 'bootstrap';

    public function render()
    {
        $jurusan = ModelsJurusan::latest()->paginate(10);
        return view('livewire.jurusan', ['jurusan' => $jurusan]);
    }

    public function store()
    {
        $jurusan = ModelsJurusan::updateOrCreate(['id' => $this->jurusan_id], [
            'nama_jurusan'      => $this->nama,
            'kode_jurusan'   => $this->kode,
        ]);

        $this->alert('success', $this->jurusan_id ? 'Paket updated successfully.' : 'Paket created successfully.', [
            'position' => 'center',
            'timer' => 3000,
            'toast' => true,
        ]);
        $this->resetInputFields();
        $this->emit('refresh');
    }

    public function edit($id)
    {
        $jurusan = ModelsJurusan::where('id', $id)->first();
        $this->jurusan_id = $jurusan->id;
        $this->nama = $jurusan->nama_jurusan;
        $this->kode = $jurusan->kode_jurusan;
    }

    public function resetInputFields()
    {
        $this->reset(['jurusan_id', 'nama', 'kode']);
    }

    public function deleteId($id)
    {
        $this->delete_id = $id;
    }

    public function destroy()
    {
        ModelsJurusan::destroy($this->delete_id);

        $this->alert('success', 'Paket deleted successfully.', [
            'position' => 'center',
            'timer' => 3000,
            'toast' => true,
        ]);
        $this->resetInputFields();
        $this->emit('refresh');
    }
}
