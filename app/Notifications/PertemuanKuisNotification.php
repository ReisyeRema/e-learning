<?php

namespace App\Notifications;

use Illuminate\Support\Str;
use App\Models\PertemuanKuis;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class PertemuanKuisNotification extends Notification
{
    use Queueable;

    protected $pertemuanKuis;

    /**
     * Create a new notification instance.
     */
    public function __construct(PertemuanKuis $pertemuanKuis)
    {
        $this->pertemuanKuis = $pertemuanKuis;
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
        $pembelajaran = $this->pertemuanKuis->pembelajaran;

        return (new MailMessage)
            ->subject('Kuis Baru Telah Ditambahkan')
            ->greeting('Halo, ' . $notifiable->name)
            ->line('Ada Kuis baru untuk pembelajaran: ' . $this->pertemuanKuis->pembelajaran->nama_mapel)
            ->line('Deadline: ' . \Carbon\Carbon::parse($this->pertemuanKuis->deadline)->format('d M Y H:i'))
            ->action('Lihat Kuis', route('mata-pelajaran.show', [
                'mapel' => Str::slug($pembelajaran->nama_mapel),
                'kelas' => Str::slug($pembelajaran->kelas->nama_kelas),
                'tahunAjaran' => str_replace('/', '-', $pembelajaran->tahunAjaran->nama_tahun),
                'semester' => Str::slug($pembelajaran->semester),
            ]))->line('Silakan dikerjakan sebelum deadline!');
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
