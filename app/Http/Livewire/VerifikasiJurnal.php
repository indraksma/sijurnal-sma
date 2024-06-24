<?php

namespace App\Http\Livewire;

use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use App\Models\Jurnal;
use App\Models\VerifikasiJurnal as ModelsVerifikasiJurnal;
use Illuminate\Support\Facades\DB;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class VerifikasiJurnal extends Component
{
    use LivewireAlert, WithPagination;

    protected $paginationTheme = 'bootstrap';
    protected $listeners = ['refresh' => '$refresh'];
    public $jurnal_id, $kehadiran, $tugas;

    public function render()
    {
        $jurnal = Jurnal::where('status', 0)->orderBy('tanggal', 'ASC')->paginate(10);
        return view('livewire.verifikasi-jurnal', [
            'jurnal' => $jurnal
        ]);
    }

    public function verify($id)
    {
        $this->jurnal_id = $id;
    }

    public function updatedKehadiran()
    {
        $this->tugas = '';
    }
    public function store()
    {
        $user = Auth::user();
        DB::beginTransaction();

        try {

            ModelsVerifikasiJurnal::create([
                'user_id' => $user->id,
                'jurnal_id' => $this->jurnal_id,
                'kehadiran' => $this->kehadiran,
                'tugas' => $this->tugas
            ]);

            $jurnal = Jurnal::find($this->jurnal_id);
            $jurnal->status = 1;
            $jurnal->verifikator_id = $user->id;
            $jurnal->update();

            DB::commit();

            $this->resetModal();
            $this->alert('success', 'Jurnal Verified Successfully', [
                'position' => 'center',
                'timer' => 3000,
                'toast' => true,
            ]);
            $this->emit('refresh');
            $this->dispatchBrowserEvent('closeModal');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->alert('error', 'Jurnal Verification Failed', [
                'position' => 'center',
                'timer' => 3000,
                'toast' => true,
            ]);
            $this->emit('refresh');
            $this->dispatchBrowserEvent('closeModal');
        }
    }

    public function resetModal()
    {
        $this->reset('jurnal_id', 'kehadiran', 'tugas');
    }
}
