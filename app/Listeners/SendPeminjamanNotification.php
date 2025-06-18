<?php

namespace App\Listeners;

use App\Events\PeminjamanDiajukan;
use App\Models\User;
use Illuminate\Support\Facades\Notification;

class SendPeminjamanNotification
{
    public function handle(PeminjamanDiajukan $event)
    {
        $admins = User::where('level', 'admin')->get();

        Notification::send($admins, new \App\Notifications\NewPeminjamanNotification(
            $event->peminjaman
        ));
    }
}
