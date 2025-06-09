<?php

namespace App\Notifications;

use Illuminate\Support\Str;
use Illuminate\Bus\Queueable;
use App\Models\PertemuanTugas;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class PertemuanTugasNotification extends Notification
{
    use Queueable;

    protected $pertemuanTugas;

    /**
     * Create a new notification instance.
     */
    public function __construct(PertemuanTugas $pertemuanTugas)
    {
        $this->pertemuanTugas = $pertemuanTugas;
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
        $pembelajaran = $this->pertemuanTugas->pembelajaran;

        return (new MailMessage)
            ->subject('Tugas Baru Telah Ditambahkan')
            ->greeting('Halo, ' . $notifiable->name)
            ->line('Ada tugas baru untuk pembelajaran: ' . $this->pertemuanTugas->pembelajaran->nama_mapel)
            ->line('Deadline: ' . \Carbon\Carbon::parse($this->pertemuanTugas->deadline)->format('d M Y H:i'))
            ->action('Lihat Tugas', route('mata-pelajaran.show', [
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
