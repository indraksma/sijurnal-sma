@section('title', 'Laporan')
@section('icon', 'fas fa-print')
<div>
    @include('layouts.default')
    <div class="container-xl px-4 mt-n10">
        <div class="row">
            @if (session('message'))
                <div class="col-12">
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <h5 class="alert-heading mb-0">{{ session('message') }}</h5>
                        <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                </div>
            @endif
            <div class="col-lg-6 mb-4" wire:ignore.self>
                <div class="card bg-info text-white h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="me-3">
                                <div class="text-white-75 small">Jurnal Kegiatan Pembelajaran</div>
                                <div class="text-lg fw-bold"></div>
                            </div>
                            <i class="feather-xl text-white-50" data-feather="printer"></i>
                        </div>
                    </div>
                    <div class="card-footer d-flex align-items-center justify-content-between small">
                        <a class="text-white stretched-link" href="#" wire:click="modalForm(1)"
                            data-bs-toggle="modal" data-bs-target="#modalKBM">Print</a>
                        <div class="text-white"><i class="fas fa-angle-right"></i></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 mb-4" wire:ignore.self>
                <div class="card bg-warning text-white h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="me-3">
                                <div class="text-white-75 small">Jurnal Kelas & Daftar Hadir</div>
                            </div>
                            <i class="feather-xl text-white-50" data-feather="printer"></i>
                        </div>
                    </div>
                    <div class="card-footer d-flex align-items-center justify-content-between small">
                        <a class="text-white stretched-link" href="#" wire:click="modalForm(2)"
                            data-bs-toggle="modal" data-bs-target="#modalJK">Print</a>
                        <div class="text-white"><i class="fas fa-angle-right"></i></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal KBM -->
    <div wire:ignore.self class="modal fade" data-bs-backdrop="static" id="modalKBM" tabindex="-1" role="dialog"
        aria-labelledby="modalKBMLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalKBMLabel">Print Laporan Jurnal KBM</h5>
                    <button type="button" wire:click="resetInputFields(1)" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <form method="POST" action="{{ route('print.kbm') }}">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="guru" class="form-label">Nama Guru</label>
                            @if (Auth::user()->hasRole(['admin', 'superadmin']))
                                <select wire:model="user_id" name="user_id" class="form-select" id="guru" required>
                                    <option value="">-- Select --</option>
                                    @foreach ($guru_list as $data)
                                        <option value="{{ $data->id }}">{{ Str::upper($data->name) }}</option>
                                    @endforeach
                                </select>
                            @else
                                <input wire:model="nama_guru" name="nama_guru" type="text" class="form-control"
                                    id="guru" readonly>
                                <input type="hidden" name="user_id" type="text" wire:model="user_id" />
                            @endif
                        </div>
                        <div class="mb-3">
                            <label for="tahun_kelas" class="form-label">Tahun Kelas</label>
                            <select wire:model="kelas" class="form-select" id="tahun_kelas" required>
                                <option value="">-- Select --</option>
                                @foreach ($kelas_list as $kls)
                                    <option value="{{ $kls->kelas }}">{{ Str::upper($kls->kelas) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="nama_kelas" class="form-label">Nama Kelas</label>
                            <select wire:model="kelas_id" name="kelas_id" class="form-select" id="nama_kelas" required>
                                <option value="">-- Select --</option>
                                @if ($nama_kelas_list)
                                    @foreach ($nama_kelas_list as $nm)
                                        <option value="{{ $nm->id }}">{{ Str::upper($nm->nama_kelas) }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="mapel" class="form-label">Mata Pelajaran</label>
                            <select wire:model="mapel_id" name="mapel_id" class="form-select" id="mapel"
                                required>
                                <option value="">-- Select --</option>
                                @if ($mapel_list)
                                    @foreach ($mapel_list as $datam)
                                        <option value="{{ $datam->id }}">{{ Str::upper($datam->nama_mapel) }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        @if ($showTanggal != true)
                            <div class="mb-3">
                                <label for="semester" class="form-label">Tahun Ajaran</label>
                                <select wire:model="semester_id" name="semester_id" class="form-select"
                                    id="semester" required>
                                    <option value="">-- Select --</option>
                                    @if ($semester_list)
                                        @foreach ($semester_list as $smt)
                                            <option value="{{ $smt->id }}">
                                                {{ Str::upper($smt->tahun_ajaran->tahun_ajaran . ' - ' . $smt->semester) }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button type="button" wire:click="resetInputFields(1)" class="btn btn-dark close-btn"
                            data-bs-dismiss="modal">Cancel</button>
                        @if ($showPrintBtn)
                            <button type="submit" class="btn btn-success">Print</button>
                        @endif
                    </div>

                </form>
            </div>
        </div>
    </div>

    <!-- Modal JK -->
    <div wire:ignore.self class="modal fade" data-bs-backdrop="static" id="modalJK" tabindex="-1" role="dialog"
        aria-labelledby="modalJKLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalJKLabel">Print Laporan Jurnal Kelas & Daftar Hadir</h5>
                    <button type="button" wire:click="resetInputFields(2)" class="btn-close"
                        data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="{{ route('print.jk') }}">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="jk_type" class="form-label">Jenis Jurnal</label>
                            <select wire:model="jk_type" name="jk_type" class="form-select" id="jk_type" required>
                                <option value="">-- Select --</option>
                                <option value="1">Harian</option>
                                <option value="2">Semester</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="tahun_kelas" class="form-label">Tahun Kelas</label>
                            <select wire:model="kelas" class="form-select" id="tahun_kelas" required>
                                <option value="">-- Select --</option>
                                @foreach ($kelas_list as $kls)
                                    <option value="{{ $kls->kelas }}">{{ Str::upper($kls->kelas) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="nama_kelas" class="form-label">Nama Kelas</label>
                            <select wire:model="kelas_id" name="kelas_id" class="form-select" id="nama_kelas"
                                required>
                                <option value="">-- Select --</option>
                                @if ($nama_kelas_list)
                                    @foreach ($nama_kelas_list as $nm)
                                        <option value="{{ $nm->id }}">{{ Str::upper($nm->nama_kelas) }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        @if ($jk_type)
                            @if ($showTanggal == true)
                                <div class="mb-3">
                                    <label for="tanggal" class="form-label">Tanggal</label>
                                    <input type="date" wire:model="tanggal" name="tanggal" class="form-control"
                                        id="tanggal" required />
                                </div>
                            @endif
                            @if ($showTanggal != true)
                                <div class="mb-3">
                                    <label for="semester" class="form-label">Semester</label>
                                    <select wire:model="semester_id" name="semester_id" class="form-select"
                                        id="semester" required>
                                        <option value="">-- Select --</option>
                                        @if ($semester_list)
                                            @foreach ($semester_list as $smt)
                                                <option value="{{ $smt->id }}">
                                                    {{ Str::upper($smt->tahun_ajaran->tahun_ajaran . ' - ' . $smt->semester) }}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            @endif
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button type="button" wire:click="resetInputFields(2)" class="btn btn-dark close-btn"
                            data-bs-dismiss="modal">Cancel</button>
                        @if ($showPrintBtn)
                            <button type="submit" class="btn btn-success">Print</button>
                        @endif
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
