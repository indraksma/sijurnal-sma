<?php

namespace App\Http\Livewire\Setting;

use App\Models\TahunAjaran as ModelsTahunAjaran;
use Livewire\Component;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class TahunAjaran extends Component
{
    use LivewireAlert;

    public $ta_id, $tahun_ajaran, $delete_id;
    protected $listeners = ['refresh' => '$refresh'];

    public function render()
    {
        $ta = ModelsTahunAjaran::all();
        return view('livewire.setting.tahun-ajaran', ['ta' => $ta]);
    }

    public function store()
    {
        $tahun_ajaran = ModelsTahunAjaran::updateOrCreate(['id' => $this->ta_id], [
            'tahun_ajaran'      => $this->tahun_ajaran,
        ]);

        $this->alert('success', $this->ta_id ? 'Tahun Ajaran updated successfully.' : 'Tahun Ajaran created successfully.', [
            'position' => 'center',
            'timer' => 3000,
            'toast' => true,
        ]);
        $this->resetInputFields();
        $this->emit('refresh');
    }

    public function edit($id)
    {
        $ta = ModelsTahunAjaran::where('id', $id)->first();
        $this->ta_id = $ta->id;
        $this->tahun_ajaran = $ta->tahun_ajaran;
    }

    public function resetInputFields()
    {
        $this->reset(['ta_id', 'tahun_ajaran', 'delete_id']);
    }

    public function deleteId($id)
    {
        $this->delete_id = $id;
    }

    public function destroy()
    {
        ModelsTahunAjaran::destroy($this->delete_id);

        $this->alert('success', 'Tahun Ajaran deleted successfully.', [
            'position' => 'center',
            'timer' => 3000,
            'toast' => true,
        ]);
    }
}
