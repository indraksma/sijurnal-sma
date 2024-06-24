@section('title', 'Agenda Harian Guru')
@section('icon', 'fas fa-clipboard')
@section('topbtn')
    <a class="btn btn-success mt-3 mr-2" wire:click="add" data-bs-toggle="modal" data-bs-target="#createModal"><i
            class="fas fa-plus"></i>&nbsp; Tambah</a>
    <a class="btn btn-info mt-3" data-bs-toggle="modal" data-bs-target="#printModal"><i class="fas fa-print"></i>&nbsp; Print
        Agenda</a>
@endsection
@push('footscripts')
    <script>
        window.addEventListener('storedData', event => {
            const modale = document.querySelector('#createModal');
            const modal = bootstrap.Modal.getInstance(modale);
            modal.hide();
        })
    </script>
@endpush
<div>
    @include('layouts.default')
    <div class="container-fluid px-4 mt-n10">
        <div class="row">
            @if (session('message'))
                <div class="col-12">
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <h5 class="alert-heading mb-0">{{ session('message') }}</h5>
                        <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                </div>
            @endif
            <div class="col-12">
                <div class="card card-angles">
                    <div class="card-body rounded-top rounded-bottom">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Waktu</th>
                                        @if (Auth::user()->hasRole(['admin', 'superadmin']))
                                            <th>Nama Guru</th>
                                        @endif
                                        <th>Rincian Kegiatan</th>
                                        <th>Keterangan</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if ($agenda->isNotEmpty())
                                        @foreach ($agenda as $data)
                                            <tr>
                                                <td>
                                                    {{ \Carbon\Carbon::parse($data->tanggal)->isoFormat('D MMMM Y') }}
                                                </td>
                                                <td>{{ \Carbon\Carbon::parse($data->waktu)->format('H:i') }}</td>
                                                @if (Auth::user()->hasRole(['admin', 'superadmin']))
                                                    <td>{{ $data->user->name }}</td>
                                                @endif
                                                <td>{{ $data->rincian }}</td>
                                                <td>{{ $data->keterangan }}</td>
                                                <td>
                                                    <button class="btn btn-sm btn-warning mb-1" data-bs-toggle="modal"
                                                        data-bs-target="#createModal"
                                                        wire:click="edit({{ $data->id }})">Edit</button>
                                                    <button class="btn btn-sm btn-danger mb-1" data-bs-toggle="modal"
                                                        data-bs-target="#deleteModal"
                                                        wire:click="delete({{ $data->id }})">Delete</button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr class="text-center">
                                            @if (Auth::user()->hasRole(['admin', 'superadmin']))
                                                <td colspan="6">Belum ada data</td>
                                            @else
                                                <td colspan="5">Belum ada data</td>
                                            @endif
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                        @if ($agenda->isNotEmpty())
                            <div class="mt-2">
                                {{ $agenda->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal Tambah -->
    <div wire:ignore.self class="modal fade" data-bs-backdrop="static" id="createModal" tabindex="-1" role="dialog"
        aria-labelledby="createModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createModalLabel">Data Agenda</h5>
                    <button type="button" wire:click="resetInputFields()" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="tanggal" class="form-label">Tanggal</label>
                        <input wire:model.lazy="tanggal" type="date" class="form-control" id="tanggal" required>
                    </div>
                    <div class="mb-3">
                        <label for="waktu" class="form-label">Waktu</label>
                        <input wire:model.lazy="waktu" type="time" class="form-control" id="waktu" required>
                    </div>
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
                            <input wire:model="nama_guru" type="text" class="form-control" id="guru" disabled>
                        @endif
                    </div>
                    <div class="mb-3">
                        <label for="rincian" class="form-label">Rincian Kegiatan</label>
                        <textarea wire:model.lazy="rincian" type="text" class="form-control" id="rincian" placeholder="Rincian Kegiatan"
                            required rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="keterangan" class="form-label">Keterangan</label>
                        <textarea wire:model.lazy="keterangan" type="text" class="form-control" id="keterangan" placeholder="Keterangan"
                            required rows="3"></textarea>
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
    <!-- Modal Print -->
    <div wire:ignore.self class="modal fade" data-bs-backdrop="static" id="printModal" tabindex="-1"
        role="dialog" aria-labelledby="printModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="printModalLabel">Cetak Agenda Bulanan</h5>
                    <button type="button" wire:click="resetInputFields()" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <form method="POST" action="{{ route('print.agenda') }}">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="guru" class="form-label">Nama Guru</label>
                            @if (Auth::user()->hasRole(['admin', 'superadmin']))
                                <select wire:model="guru" name="user_id" class="form-select" id="guru"
                                    required>
                                    <option value="">-- Select --</option>
                                    @foreach ($guru_list as $data)
                                        <option value="{{ $data->id }}">{{ Str::upper($data->name) }}</option>
                                    @endforeach
                                </select>
                            @else
                                <input wire:model="nama_guru" type="text" class="form-control" id="guru"
                                    disabled>
                                <input type="hidden" name="user_id" wire:model="user_id" />
                            @endif
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <div class="mb-3">
                                    <label for="bulan" class="form-label">Bulan</label>
                                    <select wire:model="bulan" name="bulan" type="text" class="form-select"
                                        id="bulan" required>
                                        <option value="">-- Select --</option>
                                        <option value="1">Januari</option>
                                        <option value="2">Februari</option>
                                        <option value="3">Maret</option>
                                        <option value="4">April</option>
                                        <option value="5">Mei</option>
                                        <option value="6">Juni</option>
                                        <option value="7">Juli</option>
                                        <option value="8">Agustus</option>
                                        <option value="9">September</option>
                                        <option value="10">Oktober</option>
                                        <option value="11">November</option>
                                        <option value="12">Desember</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mb-3">
                                    <label for="tahun" class="form-label">Tahun</label>
                                    <select wire:model="tahun" name="tahun" type="text" class="form-select"
                                        id="tahun" required>
                                        <option value="">-- Select --</option>
                                        @php
                                            $tahun = intval(date('Y'));
                                        @endphp
                                        @for ($i = 0; $i < 10; $i++)
                                            <option value="{{ $tahun }}">{{ $tahun }}</option>
                                            @php
                                                $tahun = $tahun - 1;
                                            @endphp
                                        @endfor
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" wire:click="resetInputFields()" class="btn btn-dark close-btn"
                            data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-info">Cetak</button>
                    </div>
                </form>
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
