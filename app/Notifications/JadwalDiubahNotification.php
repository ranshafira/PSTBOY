<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class JadwalDiubahNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $jadwal;
    protected $status; // 'baru' atau 'lama'

    public function __construct($jadwal, $status)
    {
        $this->jadwal = $jadwal;
        $this->status = $status;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $tanggal = \Carbon\Carbon::parse($this->jadwal->tanggal)->locale('id')->isoFormat('dddd, D MMMM Y');
        $shift = ucfirst($this->jadwal->shift);
        
        if ($this->status === 'lama') {
            return (new MailMessage)
                ->subject('Perubahan Jadwal Anda')
                ->greeting('Halo ' . $notifiable->nama_lengkap . ',')
                ->line("Admin telah mengubah jadwal Anda.")
                ->line("Tanggal: **$tanggal**")
                ->line("Shift: **$shift**")
                ->line("Jadwal ini sekarang telah diberikan kepada petugas lain.")
                ->line('Terima kasih atas pengertiannya.');
        } else {
            return (new MailMessage)
                ->subject('Penugasan Jadwal Baru')
                ->greeting('Halo ' . $notifiable->nama_lengkap . ',')
                ->line("Anda telah ditugaskan pada jadwal baru oleh Admin.")
                ->line("Tanggal: **$tanggal**")
                ->line("Shift: **$shift**")
                ->line("Mohon pastikan Anda tersedia pada jadwal tersebut.")
                ->line('Terima kasih atas kerjasamanya.');
        }
    }
}
