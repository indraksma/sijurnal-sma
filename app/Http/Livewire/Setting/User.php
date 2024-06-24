<?php

namespace App\Http\Livewire\Setting;

use App\Imports\UsersImport;
use App\Models\User as ModelsUser;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Livewire\Component;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;

class User extends Component
{
    use LivewireAlert, WithPagination, WithFileUploads;

    protected $paginationTheme = 'bootstrap';

    public $role, $name, $email, $password, $user_id, $role_list, $delete_id, $nip, $gelar_depan, $gelar_belakang, $template_excel, $searchTerm, $guru_piket;
    public $iteration = 0;
    public $reqpassword = true;
    protected $listeners = ['refresh' => '$refresh'];

    public function mount()
    {
        $this->role_list = Role::all();
    }

    public function render()
    {
        /** @var \App\Models\User */
        $user = Auth::user();
        if ($user->hasRole('superadmin')) {
            $users = ModelsUser::select('id', 'name', 'nip', 'email')->where(
                function ($sub_query) {
                    $sub_query->where('name', 'like', '%' . $this->searchTerm . '%')
                        ->orWhere('email', 'like', '%' . $this->searchTerm . '%')
                        ->orWhere('nip', 'like', '%' . $this->searchTerm . '%');
                }
            )->orderBy('name', 'ASC')->paginate(10);
        } else {
            $users = ModelsUser::select('id', 'name', 'nip', 'email')->where(
                function ($sub_query) {
                    $sub_query->where('name', 'like', '%' . $this->searchTerm . '%')
                        ->orWhere('email', 'like', '%' . $this->searchTerm . '%')
                        ->orWhere('nip', 'like', '%' . $this->searchTerm . '%');
                }
            )->whereHas('roles', function ($q) {
                $q->where('name', 'user')->orWhere('name', 'admin')->orWhere('name', 'guru_piket');
            })->orderBy('name', 'ASC')->paginate(10);
        }
        // $users = ModelsUser::orderBy('name', 'ASC')->paginate(10);
        return view('livewire.setting.user', [
            'iteration' => $this->iteration,
            'users' => $users,
        ]);
    }

    public function store()
    {
        $messages = [
            '*.required'                => 'This column is required',
            '*.numeric'                 => 'This column is required to be filled in with number',
            '*.string'                  => 'This column is required to be filled in with letters',
        ];

        if ($this->reqpassword) {
            $this->validate(['password' => 'required'], $messages);
        }
        if ($this->password == NULL) {
            $user = ModelsUser::updateOrCreate(['id' => $this->user_id], [
                'name'      => $this->name,
                'nip'      => $this->nip,
                'gelar_depan'      => $this->gelar_depan,
                'gelar_belakang'      => $this->gelar_belakang,
                'email'   => $this->email,
            ]);
        } else {
            $user = ModelsUser::updateOrCreate(['id' => $this->user_id], [
                'name'      => $this->name,
                'nip'      => $this->nip,
                'gelar_depan'      => $this->gelar_depan,
                'gelar_belakang'      => $this->gelar_belakang,
                'email'   => $this->email,
                'password' => Hash::make($this->password),
            ]);
        }
        $role_name = [];
        $role_user = Role::where('id', $this->role)->first()->name;
        array_push($role_name, $role_user);
        if ($this->guru_piket) {
            array_push($role_name, 'guru_piket');
        }
        $user->syncRoles([$role_name]);

        $this->alert('success', $this->user_id ? 'User updated successfully.' : 'User created successfully.', [
            'position' => 'center',
            'timer' => 3000,
            'toast' => true,
        ]);
        $this->resetInputFields();
        $this->emit('refresh');
    }

    public function edit($id)
    {
        $this->reqpassword = false;
        $user = ModelsUser::where('id', $id)->first();
        $this->user_id = $user->id;
        $this->name = $user->name;
        $this->nip = $user->nip;
        $this->gelar_depan = $user->gelar_depan;
        $this->gelar_belakang = $user->gelar_belakang;
        $this->email = $user->email;
        $role = $user->hasAnyRole(Role::all()->except('guru_piket'));
        if ($role) {
            $this->role = $user->roles()->first()->id;
        }
        if ($user->hasRole('guru_piket')) {
            $this->guru_piket = 1;
        }
    }

    public function resetInputFields()
    {
        $this->reqpassword = true;
        $this->reset(['name', 'email', 'password', 'nip', 'gelar_depan', 'gelar_belakang', 'role', 'user_id', 'guru_piket']);
    }

    public function deleteId($id)
    {
        $this->delete_id = $id;
    }

    public function destroy()
    {
        ModelsUser::destroy($this->delete_id);

        $this->alert('success', 'User deleted successfully.', [
            'position' => 'center',
            'timer' => 3000,
            'toast' => true,
        ]);
        $this->emit('refresh');
        $this->resetInputFields();
    }

    public function import()
    {
        $file_path = $this->template_excel->store('files', 'public');
        Excel::import(new UsersImport, storage_path('/app/public/' . $file_path));
        Storage::disk('public')->delete($file_path);

        $this->resetInputFields();
        $this->emit('refreshSiswaTable');
        $this->alert('success', 'Data berhasil diimport!', [
            'position' => 'center',
            'timer' => 3000,
            'toast' => true,
        ]);
        $this->iteration = $this->iteration + 1;
    }
}
