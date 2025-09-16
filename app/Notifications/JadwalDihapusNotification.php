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
        return ['mail', 'database'];
    }
    
    public function toArray($notifiable)
    {
        $tanggal = \Carbon\Carbon::parse($this->jadwal->tanggal)->locale('id')->isoFormat('dddd, D MMMM Y');
        $shift = ucfirst($this->jadwal->shift);
        
        return [
            'title' => 'PEMBERITAHUAN RESMI: Pembatalan Jadwal Tugas',
            'message' => "Dengan ini disampaikan bahwa jadwal tugas Anda telah dibatalkan.\nTanggal: {$tanggal}\nShift: {$shift}\nMohon untuk segera mengkonfirmasi penerimaan pemberitahuan ini.",
            'tanggal' => $this->jadwal->tanggal,
            'shift' => $this->jadwal->shift
        ];
    }

    public function toMail($notifiable)
    {
        $tanggal = \Carbon\Carbon::parse($this->jadwal->tanggal)->locale('id')->isoFormat('dddd, D MMMM Y');
        $shift = ucfirst($this->jadwal->shift);
        
        return (new MailMessage)
            ->subject('PEMBERITAHUAN RESMI: Pembatalan Jadwal Tugas')
            ->greeting('Kepada ' . $notifiable->nama_lengkap . ',')
            ->line('Dengan ini disampaikan bahwa jadwal tugas Anda telah dibatalkan dengan rincian sebagai berikut:')
            ->line('Tanggal: ' . $tanggal)
            ->line('Shift: ' . $shift)
            ->line('Mohon untuk segera mengkonfirmasi penerimaan pemberitahuan ini.')
            ->line('Apabila terdapat pertanyaan atau keberatan, harap menghubungi administrator sistem.')
            ->salutation('Hormat Kami, Administrator PST');
    }
    }

