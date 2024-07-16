<?php

namespace App\Http\Livewire\Setting;

use App\Models\KepalaSekolah as ModelsKepalaSekolah;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithFileUploads;

class KepalaSekolah extends Component
{
    use LivewireAlert, WithFileUploads;

    public $kepsek_id, $nama, $nip, $delete_id, $ttd, $file_ttd;
    public $edit = false;
    public $iteration = 0;
    protected $listeners = ['refresh' => '$refresh'];

    public function render()
    {
        $kepsek = ModelsKepalaSekolah::all();
        return view('livewire.setting.kepala-sekolah', ['kepsek' => $kepsek, 'iteration' => $this->iteration]);
    }

    public function store()
    {
        if ($this->edit) {
            if ($this->ttd) {
                $this->validate(['ttd' => 'required|image|max:2048']);
                $filename = 'ttd_' . date('YmdHis');
                $uploadedfilename = $filename . '.' . $this->ttd->getClientOriginalExtension();
                $this->ttd->storeAs('public/img/ttd', $uploadedfilename);
            } else {
                $uploadedfilename = $this->file_ttd;
            }
        } else {
            $this->validate(['ttd' => 'required|image|max:2048']);
            $filename = 'ttd_' . date('YmdHis');
            $uploadedfilename = $filename . '.' . $this->ttd->getClientOriginalExtension();
            $this->ttd->storeAs('public/img/ttd', $uploadedfilename);
        }

        $user = ModelsKepalaSekolah::updateOrCreate(['id' => $this->kepsek_id], [
            'nama'      => $this->nama,
            'nip'   => $this->nip,
            'ttd'   => $uploadedfilename
        ]);
        $this->alert('success', $this->kepsek_id ? 'Kepala Sekolah updated successfully.' : 'Kepala Sekolah created successfully.', [
            'position' => 'center',
            'timer' => 3000,
            'toast' => true,
        ]);
        $this->resetInputFields();
        $this->emit('refresh');
        $this->iteration = $this->iteration + 1;
    }

    public function edit($id)
    {
        $kepsek = ModelsKepalaSekolah::where('id', $id)->first();
        $this->kepsek_id = $kepsek->id;
        $this->nama = $kepsek->nama;
        $this->nip = $kepsek->nip;
        $this->file_ttd = $kepsek->ttd;
        $this->edit = true;
    }

    public function resetInputFields()
    {
        $this->reset(['nama', 'nip', 'kepsek_id', 'delete_id', 'ttd', 'file_ttd']);
        $this->edit = false;
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
