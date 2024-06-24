@section('title', 'Verifikasi Jurnal')
@section('icon', 'fas fa-check')
@push('footscripts')
    <script>
        window.addEventListener('closeModal', event => {
            const modale = document.querySelector('#modalVerif');
            const modal = bootstrap.Modal.getInstance(modale);
            modal.hide();
        })
    </script>
@endpush
<div>
    @include('layouts.default')
    <div class="container-fluid px-4 mt-n10">
        <div class="row">
            <div class="col-12">
                <div class="card card-angles mb-4 mb-md-0">
                    <div class="card-body rounded-top rounded-bottom">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Kelas</th>
                                        <th>Nama Guru</th>
                                        <th>Mata Pelajaran</th>
                                        <th>Materi</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if ($jurnal->isNotEmpty())
                                        @foreach ($jurnal as $data)
                                            <tr>
                                                <td>{{ \Carbon\Carbon::parse($data->tanggal)->isoFormat('D MMMM Y') }}
                                                </td>
                                                <td>{{ $data->kelas->nama_kelas }}</td>
                                                <td>{{ $data->user->name }}</td>
                                                <td>{{ $data->mata_pelajaran->nama_mapel }}</td>
                                                <td>{{ $data->materi->materi }}</td>
                                                <td><button type="button" class="btn btn-sm btn-success"
                                                        data-bs-toggle="modal" data-bs-target="#modalVerif"
                                                        wire:click="verify({{ $data->id }})">Verifikasi</button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr class="text-center">
                                            <td colspan="6">Belum ada data</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                        @if ($jurnal->isNotEmpty())
                            <div class="mt-2">
                                {{ $jurnal->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div wire:ignore.self class="modal fade" data-bs-backdrop="static" id="modalVerif" tabindex="-1" role="dialog"
        aria-labelledby="modalVerifLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalVerifLabel">Data Materi</h5>
                    <button type="button" wire:click="resetModal()" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="kehadiran" class="form-label">Kehadiran</label>
                        <select wire:model="kehadiran" class="form-select" id="kehadiran" required>
                            <option value="">-- Pilih --</option>
                            <option value="1">Hadir</option>
                            <option value="2">Tidak Hadir</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="tugas" class="form-label">Tugas</label>
                        <select wire:model="tugas" class="form-select" id="tugas" required>
                            <option value="">-- Pilih --</option>
                            @if ($kehadiran)
                                @if ($kehadiran == 1)
                                    <option value="1">Mengajar</option>
                                    <option value="2">Tugas</option>
                                @else
                                    <option value="3">Ada Tugas</option>
                                    <option value="4">Tidak Ada Tugas</option>
                                @endif
                            @endif
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" wire:click="resetModal()" class="btn btn-dark close-btn"
                        data-bs-dismiss="modal">Cancel</button>
                    <button type="button" wire:click.prevent="store()" class="btn btn-success">Simpan</button>
                </div>
            </div>
        </div>
    </div>
</div>
