<?php

namespace App\Imports;

use App\Models\User;
use Spatie\Permission\Models\Role;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class UsersImport implements ToModel, WithStartRow
{
    public function startRow(): int
    {
        return 2;
    }

    public function model(array $row)
    {
        $user = User::create([
            'name' => $row[0],
            'gelar_depan' => $row[1],
            'gelar_belakang' => $row[2],
            'nip' => $row[3],
            'email' => $row[4],
            'password' => $row[5],
        ])->assignRole('user');

        return $user;
    }
}
