<?php

namespace App\Http\Livewire;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\MataPelajaran;
use Illuminate\Support\Facades\Auth;

class MapelTable extends DataTableComponent
{
    protected $model = MataPelajaran::class;

    protected $listeners = ['refreshMapelTable' => '$refresh'];

    public function configure(): void
    {
        $this->setPrimaryKey('id');

        $this->setAdditionalSelects(['mata_pelajarans.id']);
    }

    public function columns(): array
    {
        return [
            Column::make("Nama Mapel", "nama_mapel")
                ->searchable()
                ->sortable(),
            Column::make("Paket", "jurusan.kode_jurusan")
                ->sortable()
                ->searchable()
                ->format(function ($row) {
                    return $row ?: 'Umum / NA';
                }),
            Column::make('Action')
                ->label(
                    function ($row, Column $column) {
                        $edit = '<a href="#nama_mapel" class="btn btn-sm btn-warning mb-1" wire:click="$emit(' . "'edit', " . $row->id . ')"><i class="fas fa-edit"></i>&nbsp;Edit</a>';
                        $delete = '<a class="btn btn-sm btn-danger text-white mb-1" wire:click="$emit(' . "'deleteId', " . $row->id . ')" data-bs-toggle="modal" data-bs-target="#deleteModal"><i class="fas fa-trash"></i>&nbsp;Delete</a>';
                        /** @var \App\Models\User */
                        $user = Auth::user();
                        return $edit . '&nbsp;' . $delete;
                    }
                )->html(),
        ];
    }
}
