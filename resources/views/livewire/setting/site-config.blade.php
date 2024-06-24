@section('title', 'Site Configuration')
@section('icon', 'fas fa-cogs')
<div>
    @include('layouts.default')
    <div class="container-fluid px-4 mt-n10">
        <div class="row justify-content-center">
            <div class="col-12 col-md-6">
                <div class="card card-angles">
                    <div class="card-body rounded-top rounded-bottom">
                        <form wire:submit.prevent="save" method="POST" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label for="school_name" class="form-label">School Name</label>
                                <input wire:model.lazy="school_name" type="text" class="form-control"
                                    id="school_name" required>
                            </div>
                            <div class="mb-3">
                                <label for="upload{{ $iteration }}" class="form-label">Logo</label>
                                <br />
                                @if ($logo != null)
                                    <img src="{{ url('storage/img/' . $logo) }}" style="max-width: 50%;"
                                        class="mb-2" />
                                @endif
                                <div class="custom-file">
                                    <input class="form-control" type="file" wire:model="photo"
                                        id="upload{{ $iteration }}">
                                </div>
                                @error('photo')
                                    <span class="error">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="uploads{{ $iteration }}" class="form-label">Kop Surat</label>
                                <br />
                                @if ($kop_surat != null)
                                    <img src="{{ url('storage/img/' . $kop_surat) }}" style="max-width: 100%;"
                                        class="mb-2" />
                                @endif
                                <div class="custom-file">
                                    <input class="form-control" type="file" wire:model="file_kop"
                                        id="uploads{{ $iteration }}">
                                </div>
                                @error('file_kop')
                                    <span class="error">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="row align-items-center justify-content-between">
                                <div class="col-auto mb-1">
                                    <button type="submit" class="btn btn-info">Save</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
