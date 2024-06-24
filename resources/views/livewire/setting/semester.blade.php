@section('title', 'Semester')
@section('subtitle', 'Konfigurasi Semester')
@section('icon', 'fas fa-calendar')
<div>
    @include('layouts.default')
    <div class="container-fluid px-4 mt-n10">
        <div class="row">
            <div class="col-md-8">
                <div class="card card-angles mb-4 mb-md-0">
                    <div class="card-body rounded-top rounded-bottom">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped mb-0">
                                <thead>
                                    <tr>
                                        <th>Tahun Ajaran</th>
                                        <th>Semester</th>
                                        <th>Kepala Sekolah</th>
                                        <th>Status Aktif</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if ($smt->isNotEmpty())
                                        @foreach ($smt as $data)
                                            <tr>
                                                <td>{{ $data->tahun_ajaran->tahun_ajaran }}</td>
                                                <td>{{ $data->semester }}</td>
                                                <td>{{ $data->kepala_sekolah->nama }}</td>
                                                <td>
                                                    @if ($data->is_active == 0)
                                                        <span class="badge bg-dark">Tidak Aktif</span>
                                                    @elseif ($data->is_active == 1)
                                                        <span class="badge bg-green">Aktif</span>
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($data->is_active == 0)
                                                        <a wire:click="activate({{ $data->id }})"
                                                            class="btn btn-sm btn-success me-1 mb-2">
                                                            <i class="fas fa-check"></i>&nbsp;Activate
                                                        </a>
                                                    @endif
                                                    <a wire:click="edit({{ $data->id }})"
                                                        class="btn btn-sm btn-warning me-1 mb-2">
                                                        <i class="fas fa-edit"></i>&nbsp;Edit
                                                    </a>
                                                    <a class="btn btn-sm btn-danger me-1 mb-2"
                                                        wire:click="deleteId({{ $data->id }})"
                                                        data-bs-toggle="modal" data-bs-target="#deleteModal">
                                                        <i class="fas fa-trash"></i>&nbsp;Delete
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="4" class="text-center">Belum ada data</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card card-angles mb-4 mb-md-0">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Add / Edit Semester</h5>
                    </div>
                    <div class="card-body rounded-bottom">
                        <form method="POST" wire:submit.prevent="store()">
                            <div class="mb-3">
                                <label for="semester" class="form-label">Semester</label>
                                <input wire:model.lazy="semester" type="text" name="semester" class="form-control"
                                    id="semester" placeholder="Genap / Ganjil" required>
                            </div>
                            <div class="mb-3">
                                <label for="tahun_ajaran" class="form-label">Tahun Ajaran</label>
                                <select wire:model="ta_id" type="text" class="form-select" id="tahun_ajaran"
                                    placeholder="Tahun Ajaran" required>
                                    <option value="">-- Pilih Tahun Ajaran --</option>
                                    @if ($ta_list->isNotEmpty())
                                        @foreach ($ta_list as $ta)
                                            <option value="{{ $ta->id }}">{{ $ta->tahun_ajaran }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="kepala_sekolah" class="form-label">Kepala Sekolah</label>
                                <select wire:model="kepsek_id" type="text" class="form-select" id="kepala_sekolah"
                                    placeholder="Kepala Sekolah" required>
                                    <option value="">-- Pilih Kepala Sekolah --</option>
                                    @if ($kepsek_list->isNotEmpty())
                                        @foreach ($kepsek_list as $kp)
                                            <option value="{{ $kp->id }}">{{ $kp->nama }}</option>
                                        @endforeach
                                    @endif
                                </select>
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
