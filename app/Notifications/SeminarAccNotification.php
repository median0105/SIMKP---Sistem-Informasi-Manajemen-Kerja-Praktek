<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\KerjaPraktek;

class SeminarAccNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $kerjaPraktek;
    protected $type;
    protected $additionalData;

    /**
     * Create a new notification instance.
     */
    public function __construct(KerjaPraktek $kerjaPraktek, string $type, array $additionalData = [])
    {
        $this->kerjaPraktek = $kerjaPraktek;
        $this->type = $type;
        $this->additionalData = $additionalData;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $message = '';
        $title = '';

        switch ($this->type) {
            case 'acc_pendaftaran':
                $title = 'Pendaftaran Seminar Di-ACC';
                $message = "Pendaftaran seminar Kerja Praktek Anda telah di-ACC. Jadwal seminar: {$this->kerjaPraktek->jadwal_seminar->format('d F Y H:i')} di {$this->kerjaPraktek->ruangan_seminar}";
                break;
            case 'tolak_pendaftaran':
                $title = 'Pendaftaran Seminar Ditolak';
                $message = "Pendaftaran seminar Kerja Praktek Anda ditolak. Catatan: {$this->kerjaPraktek->catatan_seminar}";
                break;
            case 'acc_seminar':
                $title = 'Seminar Di-ACC';
                $message = "Seminar Kerja Praktek Anda telah di-ACC. Silakan menunggu input nilai dari dosen penguji.";
                break;
            case 'nilai_seminar':
                $title = 'Nilai Seminar Telah Diinput';
                $nilaiAkhir = $this->additionalData['nilai_akhir'] ?? 0;
                $lulus = $this->additionalData['lulus'] ?? false;
                $status = $lulus ? 'LULUS' : 'TIDAK LULUS';
                $message = "Nilai akhir seminar Kerja Praktek Anda: {$nilaiAkhir} ({$status})";
                break;
        }

        return [
            'title' => $title,
            'message' => $message,
            'kerja_praktek_id' => $this->kerjaPraktek->id,
            'type' => $this->type,
            'data' => $this->additionalData,
        ];
    }
}
