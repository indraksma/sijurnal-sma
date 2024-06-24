<?php

namespace App\Http\Livewire;

use App\Models\Jurnal as ModelsJurnal;
use App\Models\Presensi;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\VerifikasiJurnal;

class Jurnal extends Component
{
    use LivewireAlert;
    public $delete_id, $presensi, $jurnal, $jurnal_id, $kehadiran, $tugas;
    protected $listeners = ['deleteId' => 'deleteId', 'presensi' => 'presensi', 'verify' => 'verify'];

    public function render()
    {
        return view('livewire.jurnal');
    }

    public function resetDeleteId()
    {
        $this->reset(['delete_id']);
    }

    public function deleteId($id)
    {
        $this->delete_id = $id;
    }

    public function destroy()
    {
        DB::beginTransaction();
        try {
            Presensi::where('jurnal_id', $this->delete_id)->delete();
            ModelsJurnal::destroy($this->delete_id);

            DB::commit();

            $this->alert('success', 'Jurnal Deleted Successfully.', [
                'position' => 'center',
                'timer' => 3000,
                'toast' => true,
            ]);
            $this->resetDeleteId();
            $this->emit('refreshJurnalTable');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->alert('error', 'Jurnal Creation Failed', [
                'position' => 'center',
                'timer' => 3000,
                'toast' => true,
            ]);
            $this->emit('refresh');
            $this->dispatchBrowserEvent('closeModal');
        }
    }

    public function presensi($id)
    {
        $this->presensi = Presensi::where('jurnal_id', $id)->get();
        $this->jurnal = ModelsJurnal::where('id', $id)->first();
    }

    public function verify($id)
    {
        $this->jurnal_id = $id;
    }

    public function updatedKehadiran()
    {
        $this->tugas = '';
    }
    public function storeVerify()
    {
        $user = Auth::user();
        DB::beginTransaction();

        try {

            VerifikasiJurnal::create([
                'user_id' => $user->id,
                'jurnal_id' => $this->jurnal_id,
                'kehadiran' => $this->kehadiran,
                'tugas' => $this->tugas
            ]);

            $jurnal = ModelsJurnal::where('id', $this->jurnal_id)->first();
            $jurnal->status = 1;
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
            $this->emit('refreshJurnalTable');
            $this->dispatchBrowserEvent('closeModal');
        }
    }

    public function resetModal()
    {
        $this->reset('jurnal_id', 'kehadiran', 'tugas');
    }
}
