<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MapController;
use App\Http\Controllers\BTSController;
use App\Http\Controllers\UserController;

//Default Link
Route::get('/', function () {
    return view('beranda');
});

//////////////////////////////////////
//////////SEMUA LINK NAVBAR///////////
//////////////////////////////////////

//Link untuk ke beranda
Route::get('/beranda', function () {
    return view('beranda');
})->name('beranda');

//Link untuk ke Peta BTS
Route::get('/petaAwal', [MapController::class, 'index'])->name('petaAwal');

//Link untuk menuju ke lanjutan Peta BTS
Route::get('/petalanjutan/{kode_kecamatan}', [MapController::class, 'lanjutan'])->name('petalanjutan');

//Link untuk Data BTS
Route::get('/databts', [BTSController::class, 'index'])->name('databts');

//////////////////////////////////////
//////END SEMUA LINK NAVBAR///////////
//////////////////////////////////////

///////////////////////////////////////////
//////SEMUA LINK Kelola Data BTS///////////
///////////////////////////////////////////
// Halaman admin (CRUD)
Route::get('/kelola-bts', [BTSController::class, 'kelolaIndex'])->name('kelola-bts.index')->middleware(['auth', 'verified']);
Route::get('/kelola-bts/create', [BTSController::class, 'create'])->name('kelola-bts.create')->middleware(['auth', 'verified']);
Route::post('/kelola-bts', [BTSController::class, 'store'])->name('kelola-bts.store')->middleware(['auth', 'verified']);
Route::get('/kelola-bts/{id}/edit', [BTSController::class, 'edit'])->name('kelola-bts.edit')->middleware(['auth', 'verified']);
Route::put('/kelola-bts/{id}', [BTSController::class, 'update'])->name('kelola-bts.update')->middleware(['auth', 'verified']);
Route::delete('/kelola-bts/{id}', [BTSController::class, 'destroy'])->name('kelola-bts.destroy')->middleware(['auth', 'verified']);

//EXPORT PDF
Route::get('/kelola-bts/export/pdf', [BTSController::class, 'exportPdf'])->middleware(['auth', 'verified'])->name('kelola-bts.export.pdf');

///////////////////////////////////////////
//////SEMUA LINK Kelola Data BTS///////////
///////////////////////////////////////////

// Link Untuk mengelola data pengguna
Route::resource('users', UserController::class)->middleware(['auth', 'verified']);



//Link untuk mengedit data pengguna
// Route::middleware('auth')->group(function () {
//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
// });
//END Link untuk mengedit data pengguna

// Route::middleware(['auth','role:superuser'])->group(function () {
//     Route::resource('users', UserController::class);
// });

// Route::get('/user', function () {
//     return view('users.index');
// })->middleware(['auth', 'verified'])->name('index');

//////////////
// Route::resource('kelola-bts', BTSController::class)->middleware(['auth', 'verified']);
//////////

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__.'/auth.php';
