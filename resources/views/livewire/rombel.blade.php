@section('title', 'Rombongan Belajar')
@section('icon', 'fas fa-server')
@push('footscripts')
    <script>
        window.addEventListener('storedData', event => {
            const rombel_modal = document.querySelector('#rombelModal');
            const modal = bootstrap.Modal.getInstance(rombel_modal);
            modal.hide();
        })
    </script>
@endpush
<div>
    @include('layouts.default')
    <div class="container-fluid px-4 mt-n10">
        <div class="card card-angles">
            <div class="card-body rounded-top rounded-bottom">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="kelas" class="form-label">Tahun Angkatan</label>
                        <select wire:model="kelas" type="text" name="kelas" class="form-select" id="kelas"
                            placeholder="Pilih Kelas" required>
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
                    <div class="col-md-4 mb-3">
                        <label for="jurusan" class="form-label">Kode Paket</label>
                        <select wire:model="jurusan_id" type="text" name="jurusan" class="form-select" id="jurusan"
                            placeholder="Pilih Paket" required>
                            <option value="">-- Pilih Paket --</option>
                            @if ($jurusan_list->isNotEmpty())
                                @foreach ($jurusan_list as $jrs)
                                    <option value="{{ $jrs->id }}">{{ $jrs->kode_jurusan }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
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
                    @if ($showSiswa)
                        <hr />
                        <div class="col-12">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th width="50px">Pilih</th>
                                        <th>Nama Siswa</th>
                                        <th>JK</th>
                                        <th>NIS</th>
                                        <th>NISN</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if ($siswa_list->isNotEmpty())
                                        @foreach ($siswa_list as $dtsiswa)
                                            <tr>
                                                <td><input type="checkbox" class="form-check-input"
                                                        wire:model="selected" value="{{ $dtsiswa->id }}" /></td>
                                                <td>{{ $dtsiswa->nama }}</td>
                                                <td>{{ $dtsiswa->jk }}</td>
                                                <td>{{ $dtsiswa->nis }}</td>
                                                <td>{{ $dtsiswa->nisn }}</td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="5" class="text-center">Belum ada Data Siswa</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                            @if ($selected)
                                <p>
                                    {{ count($selected) }} Siswa dipilih
                                </p>
                                <button class="btn btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#rombelModal">Pindahkan</button>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    {{-- Modal Pindah Kelas --}}
    <div wire:ignore.self class="modal fade" id="rombelModal" data-bs-backdrop="static" tabindex="-1" role="dialog"
        aria-labelledby="rombelModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="rombelModalLabel">Pindah Kelas</h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form wire:submit.prevent="pindah" method="POST">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12">
                                <h5>Kelas Saat Ini : {{ $nama_kelas }}</h5>
                                <hr />
                                <h5>Kelas Tujuan :</h5>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="kelas_pindah" class="form-label">Tahun Angkatan</label>
                                <select wire:model="kelas_pindah" type="text" name="kelas_pindah" class="form-select"
                                    id="kelas_pindah" placeholder="Pilih Kelas" required>
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
                                <label for="jurusan_pindah" class="form-label">Kode Paket</label>
                                <select wire:model="jurusan_pindah" type="text" name="jurusan_pindah"
                                    class="form-select" id="jurusan_pindah" placeholder="Pilih Paket" required>
                                    <option value="">-- Pilih Paket --</option>
                                    @if ($jurusan_list->isNotEmpty())
                                        @foreach ($jurusan_list as $jrs)
                                            <option value="{{ $jrs->id }}">{{ $jrs->kode_jurusan }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="col-md-12 mb-3">
                                <label for="nama_kelas_pindah" class="form-label">Nama Kelas</label>
                                <select wire:model="kelasid_pindah" type="text" name="nama_kelas_pindah"
                                    class="form-select" id="nama_kelas_pindah" placeholder="Pilih Nama Kelas"
                                    required>
                                    <option value="">-- Pilih Nama Kelas --</option>
                                    @if ($pindah_kelas_list)
                                        @foreach ($pindah_kelas_list as $pd)
                                            <option value="{{ $pd->id }}">{{ $pd->nama_kelas }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" wire:click="resetPindah()"
                            data-bs-dismiss="modal">Close</button>
                        <button class="btn btn-primary" type="submit">Pindahkan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
