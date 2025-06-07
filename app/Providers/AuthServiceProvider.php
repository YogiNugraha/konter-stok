<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
  /**
   * The model to policy mappings for the application.
   *
   * @var array<class-string, class-string>
   */
  protected $policies = [
    // 'App\Models\Model' => 'App\Policies\ModelPolicy',
  ];

  /**
   * Register any authentication / authorization services.
   */
  public function boot(): void
  {
    // Definisikan Gate 'is-admin' di sini
    // Gate ini akan 'terbuka' (return true) jika role user yang login adalah 'admin'
    Gate::define('is-admin', function ($user) {
      return $user->role == 'admin';
    });
  }
}
