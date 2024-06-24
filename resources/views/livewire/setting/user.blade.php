@section('title', 'User')
<div>
    <header class="page-header page-header-dark bg-gradient-primary-to-secondary pb-10">
        <div class="container-fluid px-4">
            <div class="page-header-content pt-4 pb-4">
                <div class="row align-items-center justify-content-between">
                    <div class="col-auto mt-4">
                        <h1 class="page-header-title">
                            <div class="page-header-icon"><span wire:ignore><i class="fas fa-user"></i></span>
                            </div>
                            User
                        </h1>
                        <div class="page-header-subtitle">User Management
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <div class="container-fluid px-4 mt-n10">
        <div class="row">
            <div class="col-md-8">
                <div class="card card-angles mb-4 mb-md-0">
                    <div class="card-header">
                        <h5 class="card-title mb-0">User List</h5>
                    </div>
                    <div class="card-body rounded-bottom">
                        <div class="form-group">
                            <input type="text" class="form-control mb-3" placeholder="Search"
                                wire:model="searchTerm" />
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped mb-0">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>NIP</th>
                                        <th>Email</th>
                                        <th>Role</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($users as $user)
                                        <tr>
                                            <td>{{ $user->name }}</td>
                                            <td>{{ $user->nip == '' ? '-' : $user->nip }}</td>
                                            <td>{{ $user->email }}</td>
                                            <td>
                                                @php
                                                    $rolename = $user->getRoleNames()->except('guru_piket')->first();
                                                @endphp
                                                @if ($rolename == 'admin')
                                                    <span class="badge bg-blue-soft text-blue">Admin</span>
                                                @elseif ($rolename == 'superadmin')
                                                    <span class="badge bg-purple-soft text-purple">Super Admin</span>
                                                @elseif ($rolename == 'user')
                                                    <span class="badge bg-green-soft text-green">User</span>
                                                @else
                                                    -
                                                @endif
                                                @if ($user->hasRole('guru_piket'))
                                                    <span class="badge bg-orange-soft text-orange">Guru Piket</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a wire:click="edit({{ $user->id }})"
                                                    class="btn btn-sm btn-warning me-2 mb-1">
                                                    <i class="fas fa-edit"></i>&nbsp;Edit
                                                </a>
                                                @if (Auth::user()->id != $user->id)
                                                    <a class="btn btn-sm btn-danger me-2 mb-1"
                                                        wire:click="deleteId({{ $user->id }})"
                                                        data-bs-toggle="modal" data-bs-target="#deleteModal">
                                                        <i class="fas fa-trash"></i>&nbsp;Delete
                                                    </a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-2">
                            {{ $users->links() }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card card-angles mb-4 mb-md-0">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Add / Edit User</h5>
                    </div>
                    <div class="card-body rounded-bottom">
                        <form method="POST" wire:submit.prevent="store()">
                            <div class="mb-3">
                                <label for="name" class="form-label">Full Name</label>
                                <input wire:model.lazy="name" type="text" name="name" class="form-control"
                                    id="name" placeholder="Full Name" required>
                            </div>
                            <div class="mb-3">
                                <label for="nip" class="form-label">NIP</label>
                                <input wire:model.lazy="nip" type="text" name="nip" class="form-control"
                                    id="nip" placeholder="NIP">
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="gelar_depan" class="form-label">Gelar Depan</label>
                                        <input wire:model.lazy="gelar_depan" type="text" name="gelar_depan"
                                            class="form-control" id="gelar_depan" placeholder="Gelar Depan">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="gelar_belakang" class="form-label">Gelar Belakang</label>
                                        <input wire:model.lazy="gelar_belakang" type="text" name="gelar_belakang"
                                            class="form-control" id="gelar_belakang" placeholder="Gelar Belakang">
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email address</label>
                                <input wire:model.lazy="email" type="email" name="email" class="form-control"
                                    id="email" placeholder="name@example.com" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input wire:model.lazy="password" type="password" name="password"
                                    class="form-control @error('password') is-invalid @enderror" id="password"
                                    placeholder="Password">
                                @error('password')
                                    <div class="alert alert-danger">
                                        <span>{{ $message }}</span>
                                    </div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="role" class="form-label">Role</label>
                                <select wire:model="role" name="role" class="form-select" id="role"
                                    required>
                                    <option value="">-- Select --</option>
                                    @foreach ($role_list as $data)
                                        <option value="{{ $data->id }}">{{ Str::upper($data->name) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-check mb-3">
                                <input class="form-check-input" id="guru_piket" type="checkbox" value="1"
                                    wire:model="guru_piket">
                                <label class="form-check-label" for="guru_piket">Guru Piket</label>
                            </div>
                            <div class="row align-items-center justify-content-between">
                                <div class="col-auto mb-3">
                                    <button class="btn btn-primary" type="submit">Submit</button>
                                </div>
                                <div class="col-auto mb-3">
                                    <button wire:click="resetInputFields()" class="btn btn-light border-dark"
                                        type="button">Reset</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="card card-angles mb-4 mb-md-0 mt-3">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Import User</h5>
                    </div>
                    <div class="card-body rounded-bottom">
                        <div class="mb-3">
                            <div class="custom-file">
                                <input class="form-control" type="file" wire:model="template_excel"
                                    id="upload{{ $iteration }}">
                            </div>
                        </div>
                        <div class="row align-items-center justify-content-between">
                            <div class="col-auto mb-1">
                                <button type="button" class="btn btn-info" wire:click="import">Import</button>
                            </div>
                            <div class="col-auto mb-1">
                                <a href="{{ asset('format_import_user.xlsx') }}"><button type="button"
                                        class="btn btn-secondary">Unduh Format</button></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Hapus -->
    <div wire:ignore.self class="modal fade" data-bs-backdrop="static" id="deleteModal" tabindex="-1"
        role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Delete Confirm</h5>
                    <button type="button" wire:click="resetInputFields()" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure want to delete?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" wire:click="resetInputFields()" class="btn btn-dark close-btn"
                        data-bs-dismiss="modal">Cancel</button>
                    <button type="button" wire:click.prevent="destroy()" class="btn btn-danger close-modal"
                        data-bs-dismiss="modal">Yes, Delete</button>
                </div>
            </div>
        </div>
    </div>
</div>
