@section('title', 'Materi')
@section('subtitle', 'Data Materi Pembelajaran')
@section('icon', 'fas fa-file')
@push('headscripts')
    <script>
        window.addEventListener('storedData', event => {
            const create_modal = document.querySelector('#createModal');
            const modal = bootstrap.Modal.getInstance(create_modal);
            modal.hide();
        })
    </script>
@endpush
<div>
    @include('layouts.default')
    <div class="container-fluid px-4 mt-n10">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-angles mb-4 mb-md-0">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-6">
                                <h5 class="card-title mb-0">Data Materi</h5>
                            </div>
                            <div class="col-6 text-end">
                                <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal"
                                    data-bs-target="#createModal">Tambah</button>
                            </div>
                        </div>
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
                                        <th>Guru</th>
                                        <th>Mapel</th>
                                        <th>Topik</th>
                                        <th>Link Materi</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if ($data_materi->isNotEmpty())
                                        @foreach ($data_materi as $data)
                                            <tr>
                                                <td>{{ $data->user->name }}</td>
                                                <td>{{ $data->mata_pelajaran->nama_mapel }}</td>
                                                <td>{{ $data->materi }}</td>
                                                <td>
                                                    @if ($data->link != null)
                                                        <a href="{{ $data->link }}" class="btn btn-primary btn-sm"
                                                            target="_blank">Buka</a>
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                                <td>
                                                    <a wire:click="edit({{ $data->id }})" data-bs-toggle="modal"
                                                        data-bs-target="#createModal"
                                                        class="btn btn-sm btn-warning me-2 mb-2 mb-xl-0">
                                                        <i class="fas fa-edit"></i>&nbsp;Edit
                                                    </a>
                                                    @if (!$data->jurnal)
                                                        <a class="btn btn-sm btn-danger me-2"
                                                            wire:click="deleteId({{ $data->id }})"
                                                            data-bs-toggle="modal" data-bs-target="#deleteModal">
                                                            <i class="fas fa-trash"></i>&nbsp;Delete
                                                        </a>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="5" class="text-center">Belum ada data</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-2">
                            {{ $data_materi->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal Tambah Materi -->
    <div wire:ignore.self class="modal fade" data-bs-backdrop="static" id="createModal" tabindex="-1" role="dialog"
        aria-labelledby="createModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createModalLabel">Data Materi</h5>
                    <button type="button" wire:click="resetInputFields()" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="guru" class="form-label">Nama Guru</label>
                        @if (Auth::user()->hasRole(['admin', 'superadmin']))
                            <select wire:model="guru" class="form-select" id="guru" required>
                                <option value="">-- Select --</option>
                                @foreach ($guru_list as $data)
                                    <option value="{{ $data->id }}">{{ Str::upper($data->name) }}</option>
                                @endforeach
                            </select>
                        @else
                            <input wire:model="nama_guru" type="text" class="form-control" id="guru" readonly>
                        @endif
                    </div>
                    <div class="mb-3">
                        <label for="mapel" class="form-label">Mata Pelajaran</label>
                        <select wire:model="mapel_id" class="form-select" id="mapel" required>
                            <option value="">-- Select --</option>
                            @foreach ($mapel_list as $datam)
                                <option value="{{ $datam->id }}">{{ Str::upper($datam->nama_mapel) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="materi" class="form-label">Topik</label>
                        <textarea wire:model.lazy="materi" type="text" class="form-control" id="materi" placeholder="Topik" required
                            rows="2"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="ki_kd" class="form-label">Materi</label>
                        <textarea wire:model.lazy="ki_kd" type="text" class="form-control" id="ki_kd" placeholder="Materi" required
                            rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="link_materi" class="form-label">Link Materi</label>
                        <input wire:model.lazy="link_materi" type="text" class="form-control" id="link_materi"
                            placeholder="Link Materi">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" wire:click="resetInputFields()" class="btn btn-dark close-btn"
                        data-bs-dismiss="modal">Cancel</button>
                    <button type="button" wire:click.prevent="store()" class="btn btn-success">Simpan</button>
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
