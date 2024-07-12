@section('title', 'Edit Jurnal Pembelajaran')
@section('icon', 'fas fa-calendar')
@section('back', route('jurnal'))
<div>
    @include('layouts.minimal')
    <div class="container-fluid px-4 mt-4">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-angles mb-4 mb-md-0">
                    <div class="card-body rounded-bottom">
                        <form>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="kelas" class="form-label">Kelas</label>
                                    <select wire:model="kelas" type="text" name="kelas" class="form-select"
                                        id="kelas" placeholder="Pilih Kelas" disabled>
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
                                <div class="col-md-6 mb-3">
                                    <label for="nama_kelas" class="form-label">Nama Kelas</label>
                                    <select wire:model="kelas_id" type="text" name="nama_kelas" class="form-select"
                                        id="nama_kelas" placeholder="Pilih Nama Kelas" disabled>
                                        <option value="">-- Pilih Nama Kelas --</option>
                                        @if ($nama_kelas_list)
                                            @foreach ($nama_kelas_list as $nm)
                                                <option value="{{ $nm->id }}">{{ $nm->nama_kelas }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="tanggal" class="form-label">Tanggal</label>
                                    <input wire:model.lazy="tanggal" type="date" class="form-control" id="tanggal"
                                        disabled>
                                </div>
                                <div class="col-6 col-md-3 mb-3">
                                    <label for="jam_mulai" class="form-label">Jam Mulai</label>
                                    <select wire:model="jam_mulai" type="text" name="jam_mulai" class="form-select"
                                        id="jam_mulai" placeholder="Jam Ke" required>
                                        <option value="">-- Jam Mulai --</option>
                                        @for ($i = 1; $i < 12; $i++)
                                            <option value="{{ $i }}">Jam Ke-{{ $i }}</option>
                                        @endfor
                                    </select>
                                </div>
                                <div class="col-6 col-md-3 mb-3">
                                    <label for="jam_selesai" class="form-label">Jam Selesai</label>
                                    <select wire:model="jam_selesai" type="text" name="jam_selesai"
                                        class="form-select" id="jam_selesai" placeholder="Jam Ke" required>
                                        <option value="">-- Jam Selesai --</option>
                                        @for ($i = 1; $i < 12; $i++)
                                            <option value="{{ $i }}">Jam Ke-{{ $i }}</option>
                                        @endfor
                                    </select>
                                </div>
                                @if (Auth::user()->hasRole(['admin', 'superadmin']))
                                    <div class="col-md-6 mb-3">
                                        <label for="user_id" class="form-label">Nama Guru</label>
                                        <select wire:model="user_id" type="text" name="user_id" class="form-select"
                                            id="user_id" placeholder="Pilih Guru" disabled>
                                            <option value="">-- Pilih Guru --</option>
                                            <option value="{{ Auth::user()->id }}">{{ ucwords(Auth::user()->name) }}
                                            </option>
                                            @if ($guru_list)
                                                @foreach ($guru_list as $gl)
                                                    <option value="{{ $gl->id }}">{{ ucwords($gl->name) }}
                                                    </option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                @endif
                                <div class="col-md-6 mb-3">
                                    <div class="mb-3">
                                        <label for="mapel" class="form-label">Mata Pelajaran</label>
                                        <select wire:model="mapel_id" class="form-select" id="mapel" required>
                                            <option value="">-- Select --</option>
                                            @if ($mapel_list)
                                                @foreach ($mapel_list as $datam)
                                                    <option value="{{ $datam->id }}">
                                                        {{ Str::upper($datam->nama_mapel) }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="row">
                                        <div class="col-6">
                                            <label for="materi" class="form-label">Materi</label>
                                        </div>
                                        <div class="col-6 text-end">
                                            @if ($mapel_id != '' && $user_id != '')
                                                <button type="button" class="btn btn-xs btn-success"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#createMateri">Tambah</button>
                                            @endif
                                        </div>
                                    </div>
                                    <select wire:model="materi_id" type="text" name="materi" class="form-select"
                                        id="materi" placeholder="Pilih Materi" required>
                                        <option value="">-- Pilih Materi --</option>
                                        @if ($materi_list)
                                            @foreach ($materi_list as $mtl)
                                                <option value="{{ $mtl->id }}">{{ $mtl->materi }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                <hr />
                                <div class="col-12">
                                    <div class="table-responsive">
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Nama</th>
                                                    <th width="20%">Kehadiran</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if ($siswa_list->isNotEmpty())
                                                    @foreach ($siswa_list as $key => $data_siswa)
                                                        <tr>
                                                            <td>{{ $data_siswa->siswa->nama }}</td>
                                                            <td>
                                                                <select wire:model="kehadiran.{{ $key }}"
                                                                    class="form-select" required>
                                                                    <option value="0">Hadir</option>
                                                                    <option value="1">Sakit</option>
                                                                    <option value="2">Izin</option>
                                                                    <option value="3">Alpha</option>
                                                                    <option value="4">Dispensasi</option>
                                                                </select>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @else
                                                    <tr>
                                                        <td colspan="2" class="text-center">Belum Ada Data
                                                            Siswa
                                                        </td>
                                                    </tr>
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="text-center">
                                        <button type="button" class="btn btn-primary"
                                            wire:click.prevent="store()">Simpan</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
