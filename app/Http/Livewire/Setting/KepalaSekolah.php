<?php

namespace App\Http\Livewire\Setting;

use App\Models\KepalaSekolah as ModelsKepalaSekolah;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class KepalaSekolah extends Component
{
    use LivewireAlert;

    public $kepsek_id, $nama, $nip, $delete_id;
    protected $listeners = ['refresh' => '$refresh'];

    public function render()
    {
        $kepsek = ModelsKepalaSekolah::all();
        return view('livewire.setting.kepala-sekolah', ['kepsek' => $kepsek]);
    }

    public function store()
    {
        $user = ModelsKepalaSekolah::updateOrCreate(['id' => $this->kepsek_id], [
            'nama'      => $this->nama,
            'nip'   => $this->nip
        ]);

        $this->alert('success', $this->kepsek_id ? 'Kepala Sekolah updated successfully.' : 'Kepala Sekolah created successfully.', [
            'position' => 'center',
            'timer' => 3000,
            'toast' => true,
        ]);
        $this->resetInputFields();
        $this->emit('refresh');
    }

    public function edit($id)
    {
        $kepsek = ModelsKepalaSekolah::where('id', $id)->first();
        $this->kepsek_id = $kepsek->id;
        $this->nama = $kepsek->nama;
        $this->nip = $kepsek->nip;
    }

    public function resetInputFields()
    {
        $this->reset(['nama', 'nip', 'kepsek_id', 'delete_id']);
    }

    public function deleteId($id)
    {
        $this->delete_id = $id;
    }

    public function destroy()
    {
        ModelsKepalaSekolah::destroy($this->delete_id);

        $this->alert('success', 'Kepala Sekolah deleted successfully.', [
            'position' => 'center',
            'timer' => 3000,
            'toast' => true,
        ]);
    }
}
