<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate; // Pastikan ini ada

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        // TAMBAHKAN KODE INI
        Gate::define('is-owner', function ($user) {
            // Cek jika user punya role, dan jika nama role-nya (setelah diubah ke huruf kecil) adalah 'owner'
            return $user->role && strtolower($user->role->name) === 'owner';
        });

        // Anda bisa menambahkan Gate lain di sini nanti, contoh:
        Gate::define('is-cashier', function ($user) {
            return $user->role && strtolower($user->role->name) === 'cashier';
        });
    }
}
