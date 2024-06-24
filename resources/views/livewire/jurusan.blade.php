@section('title', 'Paket')
@section('subtitle', 'Pengelolaan Data Paket')
@section('icon', 'fas fa-fw fa-university')
<div>
    @include('layouts.default')
    <div class="container-fluid px-4 mt-n10">
        <div class="row">
            <div class="col-md-8">
                <div class="card card-angles mb-4 mb-md-0">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Data Paket</h5>
                    </div>
                    <div class="card-body rounded-bottom">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped mb-0">
                                <thead>
                                    <tr>
                                        <th>Nama Paket</th>
                                        <th>Kode Paket</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if ($jurusan->isNotEmpty())
                                        @foreach ($jurusan as $data)
                                            <tr>
                                                <td>{{ $data->nama_jurusan }}</td>
                                                <td>{{ $data->kode_jurusan }}</td>
                                                <td>
                                                    <a wire:click="edit({{ $data->id }})"
                                                        class="btn btn-sm btn-warning me-2 mb-2 mb-xl-0">
                                                        <i class="fas fa-edit"></i>&nbsp;Edit
                                                    </a>
                                                    <a class="btn btn-sm btn-danger me-2"
                                                        wire:click="deleteId({{ $data->id }})"
                                                        data-bs-toggle="modal" data-bs-target="#deleteModal">
                                                        <i class="fas fa-trash"></i>&nbsp;Delete
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="3" class="text-center">Belum ada data</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-2">
                            {{ $jurusan->links() }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card card-angles mb-4 mb-md-0">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Add / Edit Paket</h5>
                    </div>
                    <div class="card-body rounded-bottom">
                        <form method="POST" wire:submit.prevent="store()">
                            <div class="mb-3">
                                <label for="nama" class="form-label">Nama Paket</label>
                                <input wire:model.lazy="nama" type="text" name="nama" class="form-control"
                                    id="nama" placeholder="Nama Paket" required>
                            </div>
                            <div class="mb-3">
                                <label for="kode" class="form-label">Kode Paket</label>
                                <input wire:model.lazy="kode" type="text" name="kode" class="form-control"
                                    id="kode" placeholder="Kode Paket" required>
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

    <!-- Modal Hapus -->
    <div wire:ignore.self class="modal fade" data-bs-backdrop="static" id="deleteModal" tabindex="-1" role="dialog"
        aria-labelledby="deleteModalLabel" aria-hidden="true">
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
