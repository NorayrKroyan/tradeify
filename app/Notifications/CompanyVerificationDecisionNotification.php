<?php

namespace App\Notifications;

use App\Models\Company;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CompanyVerificationDecisionNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Company $company,
        public string $decision,
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Company verification update')
            ->greeting("Hello {$notifiable->name},")
            ->line("The verification decision for {$this->company->legal_name} is: {$this->decision}.")
            ->line($this->company->rejection_reason ?: $this->company->suspension_reason ?: 'You can now review the status in your account.')
            ->action('View verification status', route('verification.status'));
    }

    public function toArray(object $notifiable): array
    {
        return [
            'company_id' => $this->company->id,
            'decision' => $this->decision,
            'status' => $this->company->verification_status->value,
        ];
    }
}
