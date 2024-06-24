<?php

use App\Http\Controllers\PrintController;
use Illuminate\Support\Facades\Route;
use App\Http\Livewire\Home;
use App\Http\Livewire\Jurnal;
use App\Http\Livewire\AddJurnal;
use App\Http\Livewire\Agenda;
use App\Http\Livewire\EditJurnal;
use App\Http\Livewire\Jurusan;
use App\Http\Livewire\Kelas;
use App\Http\Livewire\Setting\KepalaSekolah;
use App\Http\Livewire\Setting\User;
use App\Http\Livewire\Setting\Roles;
use App\Http\Livewire\Setting\TahunAjaran;
use App\Http\Livewire\Setting\Semester;
use App\Http\Livewire\Siswa;
use App\Http\Livewire\MataPelajaran;
use App\Http\Livewire\Materi;
use App\Http\Livewire\Laporan;
use App\Http\Livewire\Rombel;
use App\Http\Livewire\Setting\ChangePassword;
use App\Http\Livewire\Setting\SiteConfig;
use App\Http\Livewire\SusulanJurnal;
use App\Http\Livewire\VerifikasiJurnal;

Route::middleware(['auth'])->group(function () {
    Route::get('/', Home::class)->name('home');
    Route::get('/agenda', Agenda::class)->name('agenda');
    Route::get('/siswa', Siswa::class)->name('siswa');
    Route::get('/materi', Materi::class)->name('materi');
    Route::get('/jurnal', Jurnal::class)->name('jurnal');
    Route::get('/jurnal/add', AddJurnal::class)->name('jurnal.add');
    Route::get('/jurnal/susulan', SusulanJurnal::class)->name('jurnal.susulan');
    Route::get('/jurnal/edit', EditJurnal::class)->name('jurnal.edit');
    Route::get('/laporan', Laporan::class)->name('laporan');
    Route::get('/changepass', ChangePassword::class)->name('changepass');
    Route::post('/print/kbm', [PrintController::class, 'kbm'])->name('print.kbm');
    Route::post('/print/jk', [PrintController::class, 'jk'])->name('print.jk');
    Route::post('/print/agenda', [PrintController::class, 'agenda'])->name('print.agenda');
});
Route::middleware(['auth', 'role:guru_piket|admin|superadmin'])->group(function () {
    Route::get('/verjurnal', VerifikasiJurnal::class)->name('verjurnal');
});
Route::middleware(['auth', 'role:admin|superadmin'])->group(function () {
    Route::get('/mapel', MataPelajaran::class)->name('mapel');
    Route::get('/user', User::class)->name('user');
    Route::get('/kepsek', KepalaSekolah::class)->name('kepsek');
    Route::get('/paket', Jurusan::class)->name('jurusan');
    Route::get('/kelas', Kelas::class)->name('kelas');
    Route::get('/semester', Semester::class)->name('semester');
    Route::get('/ta', TahunAjaran::class)->name('ta');
    Route::get('/rombel', Rombel::class)->name('rombel');
});
Route::middleware(['auth', 'role:superadmin'])->group(function () {
    Route::get('/roles', Roles::class)->name('roles');
    Route::get('/config', SiteConfig::class)->name('config');
});
