<?php

use App\Livewire\Peoples;
use App\Livewire\Clients;
use App\Livewire\Notes;
use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use Illuminate\Support\Facades\Route;

// Redireciona a raiz para /notes
Route::redirect('/', '/notes')
    ->name('home');

// Rota de dashboard removida (ou mantida como redirecionamento)
Route::redirect('dashboard', '/notes')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function (){
    Route::get('notes', Notes::class)->name('notes');
    Route::redirect('settings', 'settings/profile');

    Route::get('settings/profile', Profile::class)->name('settings.profile');
    Route::get('settings/password', Password::class)->name('settings.password');
    Route::get('settings/appearance', Appearance::class)->name('settings.appearance');
    Route::get('peoples', Peoples::class)->name('peoples');
    Route::get('client', Clients::class)->name('client');
    Route::get('haircuts', \App\Livewire\ManageHaircuts::class)->name('haircuts');
});

require __DIR__.'/auth.php';