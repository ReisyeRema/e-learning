<?php

namespace App\Notifications;

use Illuminate\Support\Str;
use Illuminate\Bus\Queueable;
use App\Models\Forum;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class ForumDiskusiNotification extends Notification
{
    use Queueable;

    protected $forum;

    public function __construct(Forum $forum)
    {
        $this->forum = $forum;
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $pembelajaran = $this->forum->pembelajaran;
        $pengirim = $this->forum->user->name ?? 'Pengguna';

        // Buat parameter URL
        $params = [
            'mapel' => Str::slug($pembelajaran->nama_mapel),
            'kelas' => Str::slug($pembelajaran->kelas->nama_kelas),
            'tahunAjaran' => str_replace('/', '-', $pembelajaran->tahunAjaran->nama_tahun),
            'semester' => Str::slug($pembelajaran->semester),
            'forum' => $this->forum->id,
        ];

        // Pilih route berdasarkan role
        if ($notifiable->hasRole('Guru')) {
            $routeName = 'forum-diskusi-guru.view';
        } else {
            $routeName = 'forum-diskusi.view';
        }

        return (new MailMessage)
            ->subject('Diskusi Baru: ' . $this->forum->judul)
            ->greeting('Halo, ' . $notifiable->name)
            ->line('Diskusi baru telah ditambahkan untuk mata pelajaran: ' . $pembelajaran->nama_mapel)
            ->line('Judul Diskusi: ' . $this->forum->judul)
            ->line('Dikirim oleh: ' . $pengirim)
            ->action('Lihat Diskusi', route($routeName, $params))
            ->line('Silakan bergabung dalam diskusi!');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'forum_id' => $this->forum->id,
            'judul' => $this->forum->judul,
        ];
    }
}
