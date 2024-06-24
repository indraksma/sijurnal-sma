<?php

namespace App\Http\Livewire;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\Jurnal;
use App\Models\User;
use Rappasoft\LaravelLivewireTables\Views\Filters\DateFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class JurnalTable extends DataTableComponent
{
    protected $listeners = ['refreshJurnalTable' => '$refresh'];
    protected $model = Jurnal::class;

    public function builder(): Builder
    {
        /** @var \App\Models\User */
        $user = Auth::user();
        if ($user->hasRole(['admin', 'superadmin', 'guru_piket'])) {
            return Jurnal::query();
        } else {
            return Jurnal::query()->where('jurnals.user_id', $user->id);
        }
    }

    public function configure(): void
    {
        $this->setDefaultSort('tanggal', 'desc');
        $this->setPrimaryKey('id');
        $this->setAdditionalSelects(['jurnals.id as jid', 'jam_selesai', 'status', 'jurnals.user_id', 'jurnals.verifikator_id', 'jurnals.tanggal_input']);
    }

    public function filters(): array
    {
        return [
            DateFilter::make('Tanggal')->filter(function (Builder $builder, string $value) {
                $builder->where('tanggal', $value);
            }),
        ];
    }

    public function columns(): array
    {
        /** @var \App\Models\User */
        $user = Auth::user();
        if ($user->hasRole(['admin', 'superadmin', 'guru_piket'])) {
            return [
                Column::make("Tanggal", "tanggal")
                    ->format(fn ($value, $row, Column $column) => date('d-m-Y', strtotime($row->tanggal)))
                    ->searchable(function (Builder $query, $searchTerm) {
                        if (date_parse($searchTerm)) {
                            $tanggal = date('Y-m-d', strtotime($searchTerm));
                        } else {
                            $tanggal = $searchTerm;
                        }
                        $query->orWhere('tanggal', 'like', $tanggal);
                    })
                    ->sortable(),
                Column::make("Jam Ke", "jam_mulai")->format(
                    fn ($value, $row, Column $column) => $row->jam_mulai . ' - ' . $row->jam_selesai
                )->sortable(),
                // Column::make("Jam Selesai", "jam_selesai")
                //     ->sortable()->searchable(),
                Column::make("Kelas", "kelas.nama_kelas")
                    ->sortable()->searchable(),
                Column::make("Nama Guru", "user.name")
                    ->sortable()->searchable(),
                Column::make("Mata Pelajaran", "mata_pelajaran.nama_mapel")
                    ->sortable()->searchable(),
                Column::make("Materi", "materi.materi")
                    ->sortable()->searchable(),
                Column::make('Status')
                    ->label(
                        function ($row, Column $column) {
                            if ($row->status == 1) {
                                $verif = '<span class="badge bg-success text-white mb-1" data-bs-toggle="tooltip" data-bs-placement="top" title="Terverifikasi Guru Piket / Admin">✓</span>';
                                if ($row->verifikator_id) {
                                    $verifikator = User::where('id', $row->verifikator_id)->first()->name;
                                    $verif .= '<br/><small>Verifikator : ' . $verifikator . '</small>';
                                }
                            } elseif ($row->status == 2) {
                                $verif = '<span class="badge bg-info text-white mb-1" data-bs-toggle="tooltip" data-bs-placement="top" title="Terverifikasi Sistem">✓</span>';
                                $verif .= '<a class="btn btn-xs btn-secondary text-white ml-1 mb-1" wire:click="$emit(' . "'verify', " . $row->jid . ')" data-bs-toggle="modal" data-bs-target="#modalVerif">Verifikasi</a>';
                            } else {
                                $verif = '<span class="badge bg-dark mb-1" data-bs-toggle="tooltip" data-bs-placement="top" title="Belum Terverifikasi">𐄂</span>';
                            }
                            if ($row->tanggal != $row->tanggal_input) {
                                $verif .= '<br/><span class="badge bg-primary text-white mb-1">Susulan</span>';
                            }
                            return $verif;
                        }
                    )->html(),
                Column::make('Action')
                    ->label(
                        function ($row, Column $column) {
                            $edit = '<a class="btn btn-sm btn-warning mb-1" href="' . route('jurnal.edit', ['idjurnal' => $row->jid]) . '">Edit</a>';
                            $presensi = '<a class="btn btn-sm btn-info text-white mb-1" wire:click="$emit(' . "'presensi', " . $row->jid . ')" data-bs-toggle="modal" data-bs-target="#presensiModal">Presensi</a>';
                            $delete = '<a class="btn btn-sm btn-danger text-white mb-1" wire:click="$emit(' . "'deleteId', " . $row->jid . ')" data-bs-toggle="modal" data-bs-target="#deleteModal">Delete</a>';
                            /** @var \App\Models\User */
                            $user = Auth::user();
                            if ($user->hasRole('guru_piket')) {
                                if ($row->user_id == $user->id) {
                                    return $presensi . '&nbsp;' . $edit;
                                } else {
                                    return $presensi;
                                }
                            } else {
                                return $presensi . '&nbsp;' . $edit . '&nbsp;' . $delete;
                            }
                        }
                    )->html(),
            ];
        } else {
            return [
                Column::make("Tanggal", "tanggal")
                    ->format(fn ($value, $row, Column $column) => date('d-m-Y', strtotime($row->tanggal)))
                    ->searchable(function (Builder $query, $searchTerm) {
                        if (date_parse($searchTerm)) {
                            $tanggal = date('Y-m-d', strtotime($searchTerm));
                        } else {
                            $tanggal = $searchTerm;
                        }
                        $query->orWhere('tanggal', 'like', $tanggal);
                    })
                    ->sortable(),
                Column::make("Jam Ke", "jam_mulai")->format(
                    fn ($value, $row, Column $column) => $row->jam_mulai . ' - ' . $row->jam_selesai
                )->sortable(),
                // Column::make("Jam Selesai", "jam_selesai")
                //     ->sortable()->searchable(),
                Column::make("Kelas", "kelas.nama_kelas")
                    ->sortable()->searchable(),
                Column::make("Mata Pelajaran", "mata_pelajaran.nama_mapel")
                    ->sortable()->searchable(),
                Column::make("Materi", "materi.materi")
                    ->sortable()->searchable(),
                Column::make('Status')
                    ->label(
                        function ($row, Column $column) {
                            /** @var \App\Models\User */
                            $user = Auth::user();
                            if ($row->status == 0) {
                                $verif = '<span class="badge bg-dark mb-1" data-bs-toggle="tooltip" data-bs-placement="top" title="Belum Terverifikasi">𐄂</span>';
                            } elseif ($row->status == 2) {
                                if ($user->hasRole('guru_piket')) {
                                    $verif = '<span class="badge bg-info text-white mb-1" data-bs-toggle="tooltip" data-bs-placement="top" title="Terverifikasi Sistem">✓</span>';
                                    $verif .= '<a class="btn btn-xs btn-secondary text-white ml-1 mb-1" wire:click="$emit(' . "'verify', " . $row->jid . ')" data-bs-toggle="modal" data-bs-target="#modalVerif">Verifikasi</a>';
                                } else {
                                    $verif = '<span class="badge bg-success text-white mb-1" data-bs-toggle="tooltip" data-bs-placement="top" title="Terverifikasi">✓</span>';
                                }
                            } else {
                                $verif = '<span class="badge bg-success text-white mb-1" data-bs-toggle="tooltip" data-bs-placement="top" title="Terverifikasi">✓</span>';
                            }
                            return $verif;
                        }
                    )->html(),
                Column::make('Action')
                    ->label(
                        function ($row, Column $column) {
                            $edit = '<a class="btn btn-sm btn-warning mb-1" href="' . route('jurnal.edit', ['idjurnal' => $row->jid]) . '">Edit</a>';
                            $presensi = '<a class="btn btn-sm btn-info text-white mb-1" wire:click="$emit(' . "'presensi', " . $row->jid . ')" data-bs-toggle="modal" data-bs-target="#presensiModal">Presensi</a>';
                            return $presensi . '&nbsp;' . $edit;
                        }
                    )->html(),
            ];
        }
    }
}
