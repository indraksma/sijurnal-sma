<?php

namespace App\Http\Livewire;

use App\Models\Jurnal;
use App\Models\Materi;
use App\Models\Siswa;
use App\Models\Agenda;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Home extends Component
{
    public $jurnal, $siswa, $agenda, $materi;
    public function render()
    {
        /** @var \App\Models\User */
        $user = Auth::user();
        $this->siswa = Siswa::all()->count();
        if ($user->hasRole(['admin', 'superadmin'])) {
            $this->jurnal = Jurnal::all()->count();
            $this->materi = Materi::all()->count();
            $this->agenda = Agenda::all()->count();
        } else {
            $this->jurnal = Jurnal::where('user_id', $user->id)->count();
            $this->materi = Materi::where('user_id', $user->id)->count();
            $this->agenda = Agenda::where('user_id', $user->id)->count();
        }
        return view('livewire.home');
    }
}
