<?php

namespace App\Http\Livewire\Setting;

use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Spatie\Permission\Models\Role;

class Roles extends Component
{
    use LivewireAlert;

    public $role_name, $role_id;
    protected $listeners = ['refresh' => '$refresh'];

    public function render()
    {
        $roles = Role::all();
        return view('livewire.setting.role', [
            'roles' => $roles,
        ]);
    }

    public function resetInputFields()
    {
        $this->reset(['role_name', 'role_id']);
    }

    public function store()
    {
        $roles = Role::updateOrCreate(['id' => $this->role_id], [
            'name'      => $this->role_name,
            'guard'   => 'web',
        ]);
        $this->alert('success', $this->role_id ? 'Role updated successfully.' : 'Role created successfully.', [
            'position' => 'center',
            'timer' => 3000,
            'toast' => true,
        ]);
        $this->resetInputFields();
        $this->emit('refresh');
    }

    public function edit($id)
    {
        $roles = Role::where('id', $id)->first();
        $this->role_id = $roles->id;
        $this->role_name = $roles->name;
    }

    public function destroy($id)
    {
        Role::destroy($id);

        $this->alert('success', 'Role deleted successfully.');
        $this->emit('refresh');
        $this->resetInputFields();
    }
}
