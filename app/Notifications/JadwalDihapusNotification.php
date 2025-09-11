<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Jadwal;

class JadwalDihapusNotification extends Notification
{
    use Queueable;

    protected $jadwal;

    public function __construct(Jadwal $jadwal)
    {
        $this->jadwal = $jadwal;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Jadwal Anda Telah Dihapus')
            ->greeting('Halo ' . $notifiable->nama_lengkap . ',')
            ->line('Kami ingin memberitahukan bahwa jadwal Anda telah dihapus.')
            ->line('📅 Tanggal: ' . \Carbon\Carbon::parse($this->jadwal->tanggal)->isoFormat('dddd, D MMMM Y'))
            ->line('⏰ Shift: ' . ucfirst($this->jadwal->shift))
            ->line('Jika ini adalah kesalahan, silakan hubungi admin.')
            ->salutation('Terima kasih');
    }
}
