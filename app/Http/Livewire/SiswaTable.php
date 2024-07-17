<?php

namespace App\Http\Livewire;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\Siswa;
use Illuminate\Support\Facades\Auth;

class SiswaTable extends DataTableComponent
{
    protected $model = Siswa::class;

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setAdditionalSelects(['siswas.id']);
    }

    public function columns(): array
    {
        /** @var \App\Models\User */
        $user = Auth::user();
        if ($user->hasRole(['admin', 'superadmin'])) {
            return [
                Column::make("Nama", "nama")
                    ->searchable()
                    ->sortable(),
                Column::make("Kelas", "kelas.nama_kelas")
                    ->searchable()
                    ->sortable(),
                Column::make("NIS", "nis")
                    ->searchable()
                    ->sortable(),
                Column::make("JK", "jk")
                    ->searchable()
                    ->sortable(),
                Column::make('Action')
                    ->label(
                        function ($row, Column $column) {
                            $edit = '<a class="btn btn-sm btn-warning mb-1" href="#siswaCard" wire:click="$emit(' . "'edit', " . $row->id . ')">Edit</a>';
                            $delete = '<a class="btn btn-sm btn-danger text-white mb-1" wire:click="$emit(' . "'deleteId', " . $row->id . ')" data-bs-toggle="modal" data-bs-target="#deleteModal">Delete</a>';
                            return $edit . '&nbsp;' . $delete;
                        }
                    )->html(),
            ];
        } else {
            return [
                Column::make("Nama", "nama")
                    ->searchable()
                    ->sortable(),
                Column::make("Kelas", "kelas.nama_kelas")
                    ->searchable()
                    ->sortable(),
                Column::make("NIS", "nis")
                    ->searchable()
                    ->sortable(),
                Column::make("JK", "jk")
                    ->searchable()
                    ->sortable(),
            ];
        }
    }
}
