@section('title', 'Siswa')
@section('subtitle', 'Data Siswa')
@section('icon', 'fas fa-user')
<div>
    @include('layouts.default')
    <div class="container-fluid px-4 mt-n10">
        <div class="row">

            @if (Auth::user()->hasRole(['admin', 'superadmin']))
                <div class="col-md-8">
                @else
                    <div class="col-md-12">
            @endif
            <div class="card card-angles mb-4 mb-md-0">
                <div class="card-header">
                    <h5 class="card-title mb-0">Data Siswa</h5>
                </div>
                <div class="card-body rounded-bottom">
                    <div class="form-group">
                        <input type="text" class="form-control mb-3" placeholder="Search" wire:model="searchTerm" />
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped mb-0">
                            <thead>
                                <tr>
                                    <th>Nama</th>
                                    <th>Kelas</th>
                                    <th>NIS</th>
                                    <th>JK</th>
                                    @if (Auth::user()->hasRole(['admin', 'superadmin']))
                                        <th>Action</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @if ($data_siswa->isNotEmpty())
                                    @foreach ($data_siswa as $data)
                                        <tr>
                                            <td>{{ $data->nama }}</td>
                                            <td>{{ $data->kelas->nama_kelas }}
                                            </td>
                                            <td>{{ $data->nis }}</td>
                                            <td>{{ $data->jk }}</td>
                                            @if (Auth::user()->hasRole(['admin', 'superadmin']))
                                                <td>
                                                    <a href="#siswaCard" wire:click="edit({{ $data->id }})"
                                                        class="btn btn-sm btn-warning me-2 mb-2 mb-xl-0">
                                                        <i class="fas fa-edit"></i>&nbsp;Edit
                                                    </a>
                                                    <a class="btn btn-sm btn-danger me-2"
                                                        wire:click="deleteId({{ $data->id }})"
                                                        data-bs-toggle="modal" data-bs-target="#deleteModal">
                                                        <i class="fas fa-trash"></i>&nbsp;Delete
                                                    </a>
                                                </td>
                                            @endif
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        @if (Auth::user()->hasRole(['admin', 'superadmin']))
                                            <td colspan="5" class="text-center">Belum ada data</td>
                                        @else
                                            <td colspan="4" class="text-center">Belum ada data</td>
                                        @endif
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-2">
                        {{ $data_siswa->links() }}
                    </div>
                </div>
            </div>
        </div>
        @if (Auth::user()->hasRole(['admin', 'superadmin']))
            <div class="col-md-4">
                <div class="card card-angles mb-4 mb-md-0" id="siswaCard">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Add / Edit Siswa</h5>
                    </div>
                    <div class="card-body rounded-bottom">
                        <form method="POST" wire:submit.prevent="store()">
                            <div class="mb-3">
                                <label for="nama" class="form-label">Nama</label>
                                <input wire:model.lazy="nama" type="text" name="nama" class="form-control"
                                    id="nama" placeholder="Nama Siswa" required>
                            </div>
                            <div class="mb-3">
                                <label for="nis" class="form-label">NIS</label>
                                <input wire:model.lazy="nis" type="text" name="nis" class="form-control"
                                    id="nis" placeholder="NIS" required>
                            </div>
                            <div class="mb-3">
                                <label for="kelas" class="form-label">Kelas</label>
                                <select wire:model="kelas" type="text" name="kelas" class="form-select"
                                    id="kelas" placeholder="Pilih Kelas" required>
                                    <option value="">-- Pilih Kelas --</option>
                                    @if ($kelas_list->isNotEmpty())
                                        @foreach ($kelas_list as $kls)
                                            <option value="{{ $kls->kelas }}">
                                                {{ $kls->kelas }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="jurusan" class="form-label">Kode Paket</label>
                                <select wire:model="jurusan_id" type="text" name="jurusan" class="form-select"
                                    id="jurusan" placeholder="Pilih Paket" required>
                                    <option value="">-- Pilih Paket --</option>
                                    @if ($jurusan_list->isNotEmpty())
                                        @foreach ($jurusan_list as $jrs)
                                            <option value="{{ $jrs->id }}">{{ $jrs->kode_jurusan }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="nama_kelas" class="form-label">Nama Kelas</label>
                                <select wire:model="kelas_id" type="text" name="nama_kelas" class="form-select"
                                    id="nama_kelas" placeholder="Pilih Nama Kelas" required>
                                    <option value="">-- Pilih Nama Kelas --</option>
                                    @if ($nama_kelas_list)
                                        @foreach ($nama_kelas_list as $nm)
                                            <option value="{{ $nm->id }}">{{ $nm->nama_kelas }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="jk" class="form-label">Jenis Kelamin</label>
                                <select wire:model="jk" class="form-select" id="jk" placeholder="Pilih JK"
                                    required>
                                    <option value="">-- Jenis Kelamin --</option>
                                    <option value="L">Laki-laki</option>
                                    <option value="P">Perempuan</option>
                                </select>
                            </div>
                            <div class="row align-items-center justify-content-between">
                                <div class="col-auto mb-2">
                                    <button class="btn btn-primary" type="submit">Submit</button>
                                </div>
                                <div class="col-auto mb-2">
                                    <button wire:click="resetInputFields()" class="btn btn-light border-dark"
                                        type="button">Reset</button>
                                </div>
                            </div>
                            @if (Auth::user()->hasRole(['admin', 'superadmin']))
                                <hr />
                                <div class="mb-3">
                                    <h5 class="card-title mb-0">Import Siswa</h5>
                                </div>
                                <div class="mb-3">
                                    <div class="custom-file">
                                        <input class="form-control" type="file" wire:model="template_excel"
                                            id="upload{{ $iteration }}">
                                    </div>
                                </div>
                                <div class="row align-items-center justify-content-between">
                                    <div class="col-auto mb-1">
                                        <button type="button" class="btn btn-info"
                                            wire:click="import">Import</button>
                                    </div>
                                    <div class="col-auto mb-1">
                                        <a href="{{ asset('format_import_siswa.xlsx') }}"><button type="button"
                                                class="btn btn-secondary">Unduh Format</button></a>
                                    </div>
                                </div>
                            @endif
                        </form>
                    </div>
                </div>
            </div>
        @endif
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
