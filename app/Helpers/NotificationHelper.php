<?php

namespace App\Helpers;

use App\Enum\RoleEnum;
use App\Models\Suratkuasa\PendaftaranSuratKuasaModel;
use App\Models\User;
use App\Notifications\SuratKuasaStatusNotification;
use Illuminate\Support\Facades\Notification;

class NotificationHelper
{
    /**
     * Send a notification to all administrators and superadministrators.
     */
    public static function sendToAdmins(PendaftaranSuratKuasaModel $pendaftaran, string $title, string $message): void
    {
        $admins = User::whereIn('role', [RoleEnum::Administrator->value, RoleEnum::Superadmin->value])->get();
        if ($admins->isNotEmpty()) {
            Notification::send($admins, new SuratKuasaStatusNotification($pendaftaran, $title, $message));
        }
    }
}
