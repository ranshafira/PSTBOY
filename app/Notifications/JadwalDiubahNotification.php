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
        return ['mail', 'database'];
    }
    
    public function toArray($notifiable)
    {
        $tanggal = \Carbon\Carbon::parse($this->jadwal->tanggal)->locale('id')->isoFormat('dddd, D MMMM Y');
        $shift = ucfirst($this->jadwal->shift);
        
        if ($this->status === 'lama') {
            return [
                'title' => 'PEMBERITAHUAN RESMI: Perubahan Jadwal Tugas',
                'message' => "Dengan ini disampaikan bahwa jadwal tugas Anda telah diubah.\nTanggal: {$tanggal}\nShift: {$shift}\nJadwal ini telah dialihkan kepada petugas lain.\nMohon untuk segera mengkonfirmasi penerimaan pemberitahuan ini.",
                'tanggal' => $this->jadwal->tanggal,
                'shift' => $this->jadwal->shift,
                'status' => $this->status
            ];
        } else {
            return [
                'title' => 'PEMBERITAHUAN RESMI: Penugasan Jadwal Baru',
                'message' => "Dengan ini disampaikan bahwa Anda telah ditugaskan pada jadwal baru.\nTanggal: {$tanggal}\nShift: {$shift}\nMohon untuk memastikan ketersediaan Anda pada jadwal tersebut.\nKonfirmasi penerimaan tugas ini segera.",
                'tanggal' => $this->jadwal->tanggal,
                'shift' => $this->jadwal->shift,
                'status' => $this->status
            ];
        }
    }

    public function toMail($notifiable)
    {
        $tanggal = \Carbon\Carbon::parse($this->jadwal->tanggal)->locale('id')->isoFormat('dddd, D MMMM Y');
        $shift = ucfirst($this->jadwal->shift);
        
        if ($this->status === 'lama') {
            return (new MailMessage)
                ->subject('PEMBERITAHUAN RESMI: Perubahan Jadwal Tugas')
                ->greeting('Kepada ' . $notifiable->nama_lengkap . ',')
                ->line('Dengan ini disampaikan bahwa jadwal tugas Anda telah mengalami perubahan dengan rincian sebagai berikut:')
                ->line('Tanggal: ' . $tanggal)
                ->line('Shift: ' . $shift)
                ->line('Perlu diinformasikan bahwa jadwal tersebut telah dialihkan kepada petugas lain sesuai dengan kebutuhan operasional.')
                ->line('Mohon untuk segera mengkonfirmasi penerimaan pemberitahuan ini.')
                ->line('Apabila terdapat pertanyaan atau keberatan, harap menghubungi administrator sistem.')
                ->salutation('Hormat Kami, Administrator PST');
        } else {
            return (new MailMessage)
                ->subject('PEMBERITAHUAN RESMI: Penugasan Jadwal Baru')
                ->greeting('Kepada ' . $notifiable->nama_lengkap . ',')
                ->line('Dengan ini disampaikan bahwa Anda telah ditugaskan pada jadwal baru dengan rincian sebagai berikut:')
                ->line('Tanggal: ' . $tanggal)
                ->line('Shift: ' . $shift)
                ->line('Mohon untuk memastikan ketersediaan Anda pada jadwal tersebut dan melaksanakan tugas sesuai dengan ketentuan yang berlaku.')
                ->line('Konfirmasi penerimaan tugas ini wajib dilakukan segera.')
                ->line('Apabila terdapat kendala dalam pelaksanaan tugas, harap segera menghubungi administrator sistem.')
                ->salutation('Hormat Kami, Administrator PST');
        }
    }
}
