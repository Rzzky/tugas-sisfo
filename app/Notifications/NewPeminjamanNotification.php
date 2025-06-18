<?php

namespace App\Notifications;

use App\Models\Peminjaman;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\BroadcastMessage;

class NewPeminjamanNotification extends Notification
{
    use Queueable;

    protected $peminjaman;

    public function __construct(Peminjaman $peminjaman)
    {
        $this->peminjaman = $peminjaman;
    }

    public function via($notifiable)
    {
        return ['database', 'broadcast']; // Tambahkan 'mail' jika ingin email notifikasi
    }

    public function toDatabase($notifiable)
    {
        return [
            'peminjaman_id' => $this->peminjaman->id,
            'user_id' => $this->peminjaman->user->id,
            'message' => 'Peminjaman baru diajukan oleh ' . $this->peminjaman->user->username,
            'barang' => $this->peminjaman->barang->nama_barang
        ];
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'peminjaman_id' => $this->peminjaman->id,
            'message' => 'Peminjaman baru diajukan!'
        ]);
    }
}
