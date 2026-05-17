<?php

namespace App\Providers;

use App\Http\View\Composers\NotificationComposer;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Mengikat data notifikasi ke view komponen top-header
        View::composer('admin.component.top-header', NotificationComposer::class);
    }
}
