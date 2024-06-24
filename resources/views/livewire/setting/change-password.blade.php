@section('title', 'Change Password')
@section('icon', 'fas fa-cog')
<div>
    @include('layouts.default')
    <div class="container-fluid px-4 mt-n10">
        <div class="row justify-content-center">
            <div class="col-12 col-md-6">
                <div class="card card-angles">
                    <div class="card-body rounded-top rounded-bottom">
                        <form wire:submit.prevent="store()">
                            <div class="mb-3">
                                <label for="old_password" class="form-label">Old Password</label>
                                <input wire:model.lazy="old_password" type="password"
                                    class="form-control @error('old_password') is-invalid @enderror" id="old_password"
                                    required>
                                @error('old_password')
                                    <div class="alert alert-danger">Old password is not correct. Please enter the right
                                        password</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="new_password" class="form-label">New Password</label>
                                <input wire:model.lazy="new_password" type="password" class="form-control"
                                    id="new_password" required>
                            </div>
                            <div class="row align-items-center justify-content-between">
                                <div class="col-auto mb-1">
                                    <button type="submit" class="btn btn-info">Save</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
