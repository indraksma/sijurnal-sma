@section('title', 'Jurnal Pembelajaran')
@section('subtitle', 'Data Jurnal Pembelajaran')
@section('icon', 'fas fa-calendar')
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
            <div class="col-md-12">
                <div class="card card-angles mb-4 mb-md-0">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-6">
                                <h5 class="card-title mb-0">Data Jurnal</h5>
                            </div>
                            <div class="col-6 text-end">
                                <a href="{{ route('jurnal.add') }}"><button type="button"
                                        class="btn btn-sm btn-success">Tambah</button></a>
                                <a href="{{ route('jurnal.susulan') }}"><button type="button"
                                        class="btn btn-sm btn-primary">Susulan</button></a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body rounded-bottom">
                        <div class="table-responsive">
                            <livewire:jurnal-table />
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <p class="mb-0 mt-4">Keterangan Status :<br />
                                    @if (Auth::user()->hasRole(['admin', 'superadmin', 'guru_piket']))
                                        <span class="badge bg-info mb-1" data-bs-toggle="tooltip"
                                            data-bs-placement="top" title="Belum Terverifikasi">✓</span> : Jurnal
                                        Diverifikasi oleh Sistem<br />
                                    @endif
                                    <span class="badge bg-success text-white mb-1" data-bs-toggle="tooltip"
                                        data-bs-placement="top" title="Terverifikasi">✓</span> : Jurnal Sudah
                                    Terverifikasi oleh Guru Piket / Admin.<br />
                                    <span class="badge bg-dark mb-1" data-bs-toggle="tooltip" data-bs-placement="top"
                                        title="Belum Terverifikasi">𐄂</span> : Jurnal Belum Diverifikasi oleh Guru
                                    Piket / Admin.
                                </p>
                            </div>
                        </div>
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
                    <button type="button" wire:click="resetDeleteId()" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure want to delete?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" wire:click="resetDeleteId()" class="btn btn-dark close-btn"
                        data-bs-dismiss="modal">Cancel</button>
                    <button type="button" wire:click.prevent="destroy()" class="btn btn-danger close-modal"
                        data-bs-dismiss="modal">Yes, Delete</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Presensi -->
    <div wire:ignore.self class="modal modal-xl fade" id="presensiModal" tabindex="-1" role="dialog"
        aria-labelledby="presensiModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="presensiModalLabel">Presensi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Nama</th>
                                    <th>Kelas</th>
                                    <th>NIS</th>
                                    <th>Presensi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($presensi)
                                    @foreach ($presensi as $data_presensi)
                                        <tr>
                                            <td>{{ $data_presensi->siswa->nama }}</td>
                                            <td>{{ $data_presensi->siswa->kelas->nama_kelas }}</td>
                                            <td>{{ $data_presensi->siswa->nis }}</td>
                                            @if ($data_presensi->presensi == 0)
                                                <td><span class="badge bg-primary text-white">Hadir</span></td>
                                            @elseif ($data_presensi->presensi == 1)
                                                <td><span class="badge bg-secondary text-white">Sakit</span></td>
                                            @elseif ($data_presensi->presensi == 2)
                                                <td><span class="badge bg-info">Izin</span></td>
                                            @elseif ($data_presensi->presensi == 3)
                                                <td><span class="badge bg-warning">Alpha</span></td>
                                            @elseif ($data_presensi->presensi == 4)
                                                <td><span class="badge bg-dark">Dispensasi</span></td>
                                            @endif
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Presensi</th>
                                    <th>H</th>
                                    <th>S</th>
                                    <th>I</th>
                                    <th>A</th>
                                    <th>D</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tr>
                                <td>Jumlah</td>
                                @if ($jurnal)
                                    <td>{{ $jurnal->hadir }}</td>
                                    <td>{{ $jurnal->sakit }}</td>
                                    <td>{{ $jurnal->izin }}</td>
                                    <td>{{ $jurnal->alpha }}</td>
                                    <td>{{ $jurnal->dispensasi }}</td>
                                    <td>{{ intval($jurnal->hadir) + intval($jurnal->sakit) + intval($jurnal->izin) + intval($jurnal->alpha) + intval($jurnal->dispensasi) }}
                                    </td>
                                @endif
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-dark close-btn" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal Verifikasi Jurnal -->
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
                    <button type="button" wire:click.prevent="storeVerify()" class="btn btn-success">Simpan</button>
                </div>
            </div>
        </div>
    </div>
</div>
