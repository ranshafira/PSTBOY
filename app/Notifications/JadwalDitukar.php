<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class JadwalDitukar extends Notification implements ShouldQueue
{
    use Queueable;

    protected $jadwalAsal;
    protected $petugasAsal;
    protected $petugasTujuan;
    protected $tanggal;
    protected $shift;

    /**
     * Create a new notification instance.
     */
    public function __construct($jadwalAsal, $petugasAsal, $petugasTujuan, $tanggal, $shift)
    {
        $this->jadwalAsal = $jadwalAsal;
        $this->petugasAsal = $petugasAsal;
        $this->petugasTujuan = $petugasTujuan;
        $this->tanggal = $tanggal;
        $this->shift = $shift;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $shiftText = $this->shift == 'pagi' ? 'Shift Pagi (08:00 - 11:30)' : 'Shift Siang (11:30 - 15:30)';

        return (new MailMessage)
            ->subject('📋 Notifikasi Pertukaran Jadwal - Sistem PST')
            ->greeting('Halo ' . $this->petugasTujuan->nama_lengkap . '!')
            ->line('**Telah terjadi pertukaran jadwal tugas yang melibatkan Anda.**')
            ->line('')
            ->line('**📊 Detail Pertukaran Jadwal:**')
            ->line('📅 **Tanggal:** ' . $this->tanggal)
            ->line('⏰ **Shift:** ' . $shiftText)
            ->line('👤 **Petugas Sebelumnya:** ' . $this->petugasAsal->nama_lengkap)
            ->line('👥 **Petugas Baru:** Anda')
            ->line('')
            ->line('**📝 Keterangan:**')
            ->line('Petugas sebelumnya telah mengalihkan tugas ini kepada Anda. Silakan persiapkan diri untuk melaksanakan tugas sesuai jadwal yang baru.')
            ->line('')
            ->action('📋 Lihat Jadwal Saya', url('/jadwal'))
            ->line('')
            ->line('Terima kasih telah menjadi bagian dari tim PST.')
            ->salutation('Salam hangat,<br>Sistem Management Jadwal PST');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'jadwal_ditukar',
            'title' => '🔁 Jadwal Telah Ditukar',
            'message' => 'Jadwal pada ' . $this->tanggal . ' (' . $this->shift . ') telah dialihkan dari ' . $this->petugasAsal->nama_lengkap . ' kepada Anda',
            'tanggal' => $this->tanggal,
            'shift' => $this->shift,
            'petugas_asal' => $this->petugasAsal->nama_lengkap,
            'petugas_tujuan' => $this->petugasTujuan->nama_lengkap,
            'jadwal_id' => $this->jadwalAsal->id,
            'url' => '/jadwal',
            'icon' => '🔄',
            'color' => 'orange'
        ];
    }
}
