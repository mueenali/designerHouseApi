<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\ResetPassword as Notification;
use Illuminate\Notifications\Messages\MailMessage;

class ResetPassword extends Notification
{

    public function toMail($notifiable): MailMessage
    {
        $url = url(config('app.client_url').'/resetPassword/'
                 .$this->token.'?email='.urlencode($notifiable->email));
        return (new MailMessage())
            ->line("We have received your forget password request, use this link to reset your password")
            ->action('Reset Password', $url)
            ->line('In case you did not request a password reset, than ignore this email');
    }

}
