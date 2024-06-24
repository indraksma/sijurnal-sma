<?php

namespace App\Http\Livewire\Setting;

use App\Models\SiteConfig as ModelsSiteConfig;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithFileUploads;

class SiteConfig extends Component
{
    use WithFileUploads, LivewireAlert;
    public $iteration = 0;
    public $logo, $school_name, $photo, $kop_surat, $file_kop;
    protected $listeners = ['refresh' => '$refresh'];

    public function mount()
    {
        $this->kop_surat = ModelsSiteConfig::where('option_name', 'kop_surat')->first()->option_value;
        $this->logo = ModelsSiteConfig::where('option_name', 'logo')->first()->option_value;
        $this->school_name = ModelsSiteConfig::where('option_name', 'school_name')->first()->option_value;
    }

    public function render()
    {
        return view('livewire.setting.site-config', [
            'iteration' => $this->iteration,
        ]);
    }

    // public function updatedFileLogo()
    // {
    //     $this->validate([
    //         'file_logo' => 'image|png|max:1024',
    //     ]);
    // }

    public function save()
    {
        // dd($this->file_kop);
        $logo = ModelsSiteConfig::where('option_name', 'logo')->first();
        $kop = ModelsSiteConfig::where('option_name', 'kop_surat')->first();
        if ($this->photo) {
            $this->validate(['photo' => 'required|image|max:2048']);
            $name = 'logo.' . $this->photo->extension();
            $this->photo->storeAs('public/img', $name);
        } else {
            $name = $logo->option_value;
        }
        if ($this->file_kop) {
            $this->validate(['file_kop' => 'required|image|max:2048']);
            $kopname = 'kop_surat.' . $this->file_kop->extension();
            $this->file_kop->storeAs('public/img', $kopname);
        } else {
            $kopname = $kop->option_value;
        }

        $logo->option_value = $name;
        $logo->update();
        $kop->option_value = $kopname;
        $kop->update();
        $school_name = ModelsSiteConfig::where('option_name', 'school_name')->first();
        $school_name->option_value = $this->school_name;
        $school_name->update();

        $this->alert('success', 'Site Configuration Saved Successfully', [
            'position' => 'center',
            'timer' => 3000,
            'toast' => true,
        ]);

        $this->reset(['file_kop', 'photo']);
        $this->iteration = $this->iteration + 1;
        $this->emit('refresh');
    }
}
