<?php

namespace App\Http\Livewire;

use App\Models\Jurusan;
use App\Models\MataPelajaran;
use App\Models\Materi as ModelsMateri;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithPagination;

class Materi extends Component
{
    use WithPagination, LivewireAlert;

    public $materi_id, $mapel_id, $mapel_list, $guru, $guru_list, $nama_guru, $materi, $ki_kd, $link_materi, $delete_id, $user, $jurusan_id, $jurusan_list;
    protected $listeners = ['refresh' => '$refresh'];
    protected $paginationTheme = 'bootstrap';

    public function mount()
    {
        $this->guru_list =
            User::whereHas(
                'roles',
                function ($q) {
                    $q->where('name', 'user')->orWhere('name', 'admin');
                }
            )->get();
        $this->jurusan_list = Jurusan::all();
        /** @var \App\Models\User */
        $user = Auth::user();
        if ($user->hasRole(['user'])) {
            $this->nama_guru = $user->name;
        }
        $this->user = $user;
    }
    public function updatedJurusanId($id)
    {
        $this->mapel_id = '';
        $this->mapel_list = MataPelajaran::where('jurusan_id', $id)->orWhere('jurusan_id', 0)->get();
    }
    public function render()
    {
        /** @var \App\Models\User */
        $user = Auth::user();
        if ($user->hasRole(['admin', 'superadmin'])) {
            $materi = ModelsMateri::latest()->paginate(15);
        } else {
            $materi = ModelsMateri::where('user_id', Auth::user()->id)->paginate(15);
        }
        return view('livewire.materi', [
            'data_materi' => $materi,
        ]);
    }
    public function store()
    {
        if ($this->user->hasRole(['admin', 'superadmin'])) {
            $userid = $this->guru;
        } else {
            $userid = $this->user->id;
        }
        $materi = ModelsMateri::updateOrCreate(['id' => $this->materi_id], [
            'materi'   => $this->materi,
            'mata_pelajaran_id'   => $this->mapel_id,
            'link'   => $this->link_materi,
            'ki_kd'   => $this->ki_kd,
            'user_id'   => $userid,
        ]);

        $this->alert('success', $this->materi_id ? 'Materi updated successfully.' : 'Materi created successfully.', [
            'position' => 'center',
            'timer' => 3000,
            'toast' => true,
        ]);
        $this->resetInputFields();
        $this->dispatchBrowserEvent('storedData');
        $this->emit('refresh');
    }

    public function edit($id)
    {
        $materi = ModelsMateri::where('id', $id)->first();
        $this->materi_id = $materi->id;
        $this->mapel_id = $materi->mata_pelajaran_id;
        $this->guru = $materi->user_id;
        $this->materi = $materi->materi;
        $this->ki_kd = $materi->ki_kd;
        $this->link_materi = $materi->link;
    }

    public function resetInputFields()
    {
        $this->reset(['materi_id', 'mapel_id', 'guru', 'materi', 'ki_kd', 'link_materi', 'delete_id', 'jurusan_id']);
    }

    public function deleteId($id)
    {
        $this->delete_id = $id;
    }

    public function destroy()
    {
        ModelsMateri::destroy($this->delete_id);

        $this->alert('success', 'Materi deleted successfully.', [
            'position' => 'center',
            'timer' => 3000,
            'toast' => true,
        ]);
        $this->resetInputFields();
        $this->emit('refresh');
    }
}
