<?php

namespace App\Http\Livewire\Setting;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class ChangePassword extends Component
{
    use LivewireAlert;

    public $old_password, $new_password;

    public function render()
    {
        return view('livewire.setting.change-password');
    }

    public function store()
    {
        $user = User::where('id', Auth::user()->id)->first();
        if (Hash::check($this->old_password, $user->password)) {
            $user->password = Hash::make($this->new_password);
            $user->update();
            $this->alert('success', 'Password changed successfully!', [
                'position' => 'center',
                'timer' => 3000,
                'toast' => true,
            ]);
            $this->reset(['old_password', 'new_password']);
            $this->resetErrorBag();
        } else {
            $this->addError('old_password', 'Password doesnt match.');
        }
    }
}
