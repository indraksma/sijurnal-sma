<?php

namespace App\Http\Livewire;

use App\Models\Agenda as ModelsAgenda;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class Agenda extends Component
{
    use LivewireAlert, WithPagination;

    protected $paginationTheme = 'bootstrap';
    protected $listeners = ['refresh' => '$refresh'];
    public $rincian, $tanggal, $keterangan, $agenda_id, $delete_id, $guru, $nama_guru, $guru_list, $user_id, $user, $bulan, $tahun, $waktu;

    public function mount()
    {
        /** @var \App\Models\User */
        $user = Auth::user();
        $this->user = $user;
        if ($user->hasRole(['user'])) {
            $this->nama_guru = $user->name;
        }
        $this->guru_list = User::whereHas('roles', function ($q) {
            $q->where('name', 'user')->orWhere('name', 'admin');
        })->get();
        $this->guru = $user->id;
        $this->user_id = $user->id;
    }

    public function render()
    {
        if ($this->user->hasRole(['admin', 'superadmin'])) {
            $agenda = ModelsAgenda::orderBy('tanggal', 'DESC')->paginate(10);
        } else {
            $agenda = ModelsAgenda::where('user_id', $this->user->id)->orderBy('tanggal', 'DESC')->paginate(10);
        }
        return view('livewire.agenda', [
            'agenda' => $agenda,
        ]);
    }

    public function add()
    {
        $this->tanggal = date('Y-m-d');
        $this->guru = $this->user->id;
    }

    public function delete($id)
    {
        $this->delete_id = $id;
    }

    public function destroy()
    {
        ModelsAgenda::destroy($this->delete_id);

        $this->alert('success', 'Agenda deleted successfully.', [
            'position' => 'center',
            'timer' => 3000,
            'toast' => true,
        ]);
        $this->emit('refresh');
    }

    public function resetInputFields()
    {
        $this->reset(['delete_id', 'agenda_id', 'rincian', 'tanggal', 'keterangan', 'guru', 'bulan', 'tahun', 'waktu']);
    }

    public function store()
    {
        if ($this->user->hasRole(['admin', 'superadmin'])) {
            $userid = $this->guru;
        } else {
            $userid = $this->user_id;
        }
        $agenda = ModelsAgenda::updateOrCreate(['id' => $this->agenda_id], [
            'rincian'   => $this->rincian,
            'keterangan'   => $this->keterangan,
            'tanggal'   => $this->tanggal,
            'user_id'   => $userid,
            'waktu'   => $this->waktu,
        ]);

        $this->alert('success', $this->agenda_id ? 'Agenda updated successfully.' : 'Agenda created successfully.', [
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
        $agenda = ModelsAgenda::find($id);
        $this->agenda_id = $agenda->id;
        $this->tanggal = $agenda->tanggal;
        $this->rincian = $agenda->rincian;
        $this->keterangan = $agenda->keterangan;
        $this->user_id = $agenda->user_id;
        $this->waktu = $agenda->waktu;
        if ($this->user->hasRole(['admin', 'superadmin'])) {
            $this->guru = $agenda->user_id;
        }
    }
}
