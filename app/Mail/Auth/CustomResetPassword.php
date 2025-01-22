<?php

namespace App\Mail\Auth;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class CustomResetPassword extends ResetPassword implements ShouldQueue
{
    use Queueable;

    protected function resetUrl($notifiable): string
    {
        $frontendUrl = config('app.frontend_url');
        $token = $this->token;
        $email = $notifiable->getEmailForPasswordReset();

        return "{$frontendUrl}/reset-password?token={$token}&email={$email}";
    }

    public function toMail($notifiable): MailMessage
    {
        $url = $this->resetUrl($notifiable);

        return (new MailMessage)
            ->subject('Reset Password')
            ->markdown('emails.auth.reset-password', [
                'url' => $url,
                'user' => $notifiable,
            ]);
    }
}
