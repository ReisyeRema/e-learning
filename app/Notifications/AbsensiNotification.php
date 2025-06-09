<?php

namespace App\Notifications;

use Illuminate\Support\Str;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class AbsensiNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $pembelajaran;
    protected $tanggal;

    /**
     * Create a new notification instance.
     */
    public function __construct($pembelajaran, $tanggal)
    {
        $this->pembelajaran = $pembelajaran;
        $this->tanggal = $tanggal;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Absensi Baru Tersedia')
            ->greeting('Halo, ' . $notifiable->name)
            ->line('Absensi untuk mata pelajaran: ' . $this->pembelajaran->nama_mapel)
            ->line('Tanggal Absensi: ' . \Carbon\Carbon::parse($this->tanggal)->format('d M Y'))
            ->action('Isi Absensi', route('absensi.show', [
                'mapel' => Str::slug($this->pembelajaran->nama_mapel),
                'kelas' => Str::slug($this->pembelajaran->kelas->nama_kelas),
                'tahunAjaran' => str_replace('/', '-', $this->pembelajaran->tahunAjaran->nama_tahun),
                'semester' => Str::slug($this->pembelajaran->semester),
            ]))
            ->line('Jangan lupa untuk mengisi absensi hari ini.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
