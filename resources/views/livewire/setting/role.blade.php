@section('title', 'Roles')
@section('subtitle', 'Role Management')
@section('icon', 'fas fa-user-cog')
<div>
    @include('layouts.default')
    <div class="card">
        <div class="card-body">
            <div class="container-fluid px-4 mt-n10">
                <div class="row">
                    <div class="col-md-8">
                        <div class="card card-angles mb-4 mb-md-0">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Role List</h5>
                            </div>
                            <div class="card-body rounded-bottom">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped mb-0">
                                        <thead>
                                            <tr>
                                                <th>Name</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($roles as $role)
                                                <tr>
                                                    <td>{{ $role->name }}</td>
                                                    <td>
                                                        <a wire:click="edit({{ $role->id }})"
                                                            class="btn btn-sm btn-warning me-2 mb-2 mb-xl-0">
                                                            <i class="fas fa-edit"></i>&nbsp;Edit
                                                        </a>
                                                        <a class="btn btn-sm btn-danger me-2"
                                                            wire:click="destroy({{ $role->id }})"
                                                            class="btn btn-sm btn-danger"
                                                            onclick="confirm('Are you sure to delete?') || event.stopImmediatePropagation()">
                                                            <i class="fas fa-trash"></i>&nbsp;Delete
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card card-angles mb-4 mb-md-0">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Add / Edit Role</h5>
                            </div>
                            <div class="card-body rounded-bottom">
                                <form method="POST" wire:submit.prevent="store()">
                                    <div class="mb-3">
                                        <label for="role_name" class="form-label">Role Name</label>
                                        <input wire:model.lazy="role_name" type="text" name="role_name"
                                            class="form-control" id="role_name" placeholder="Role Name" required>
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
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
