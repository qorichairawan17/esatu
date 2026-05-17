<?php

namespace App\Http\View\Composers;

use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class NotificationComposer
{
    /**
     * Bind data to the view.
     *
     * @return void
     */
    public function compose(View $view)
    {
        if (Auth::check()) {
            $user = Auth::user();
            // Ambil 5 notifikasi terbaru yang belum dibaca
            $unreadNotifications = $user->unreadNotifications()->latest()->take(5)->get();
            $unreadNotificationsCount = $user->unreadNotifications()->count();

            $view->with([
                'unreadNotifications' => $unreadNotifications,
                'unreadNotificationsCount' => $unreadNotificationsCount,
            ]);
        } else {
            // Provide default empty values when user is not authenticated
            $view->with([
                'unreadNotifications' => collect([]),
                'unreadNotificationsCount' => 0,
            ]);
        }
    }
}
