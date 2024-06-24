<?php

namespace App\Http\Livewire\Setting;

use App\Models\KepalaSekolah;
use App\Models\Semester as ModelsSemester;
use App\Models\TahunAjaran;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class Semester extends Component
{
    use LivewireAlert;

    public $semester_id, $semester, $ta_id, $ta_list, $is_active, $delete_id, $kepsek_list, $kepsek_id;
    protected $listeners = ['refresh' => '$refresh'];

    public function mount()
    {
        $this->ta_list = TahunAjaran::all();
        $this->kepsek_list = KepalaSekolah::all();
    }

    public function render()
    {
        $semester = ModelsSemester::all();
        return view('livewire.setting.semester', ['smt' => $semester]);
    }

    public function store()
    {
        $user = ModelsSemester::updateOrCreate(['id' => $this->semester_id], [
            'semester'      => $this->semester,
            'tahun_ajaran_id'   => $this->ta_id,
            'kepala_sekolah_id'   => $this->kepsek_id,
            'is_active' => '0',
        ]);

        $this->alert('success', $this->semester_id ? 'Semester updated successfully.' : 'Semester created successfully.', [
            'position' => 'center',
            'timer' => 3000,
            'toast' => true,
        ]);
        $this->resetInputFields();
        $this->emit('refresh');
    }

    public function edit($id)
    {
        $semester = ModelsSemester::where('id', $id)->first();
        $this->semester_id = $semester->id;
        $this->semester = $semester->semester;
        $this->ta_id = $semester->tahun_ajaran_id;
        $this->is_active = $semester->is_active;
    }

    public function resetInputFields()
    {
        $this->reset(['semester', 'ta_id', 'semester_id', 'is_active', 'delete_id']);
    }

    public function deleteId($id)
    {
        $this->delete_id = $id;
    }

    public function destroy()
    {
        ModelsSemester::destroy($this->delete_id);

        $this->alert('success', 'Semester deleted successfully.', [
            'position' => 'center',
            'timer' => 3000,
            'toast' => true,
        ]);
    }

    public function activate($id)
    {
        ModelsSemester::where('is_active', '1')->update(['is_active' => '0']);
        ModelsSemester::where('id', $id)->update(['is_active' => '1']);

        $this->alert('success', 'Semester activated successfully.', [
            'position' => 'center',
            'timer' => 3000,
            'toast' => true,
        ]);
    }
}
