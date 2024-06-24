<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SiteConfig;
use Illuminate\Support\Facades\Hash;

class ConfigSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SiteConfig::create([
            'option_name'      => 'logo',
            'option_value'      => 'logo-default.png',
        ]);
        SiteConfig::create([
            'option_name'      => 'school_name',
            'option_value'      => 'SMA / SMK Negeri Indonesia',
        ]);
        SiteConfig::create([
            'option_name'      => 'kop_surat',
            'option_value'      => NULL,
        ]);
    }
}
