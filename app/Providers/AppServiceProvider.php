<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Auth\Events\Login; // <-- Tambahan
use Illuminate\Support\Facades\Event; // <-- Tambahan

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // SENSOR OTOMATIS: Catat waktu last login setiap kali user berhasil masuk
        Event::listen(function (Login $event) {
            $event->user->last_login_at = now();
            $event->user->save();
        });
    }
}
