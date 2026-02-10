<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;




class CustomResetPassword extends Notification
{
    
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public $token;

    public function __construct($token)
    {
        $this->token = $token;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
     {
         $url = url(route('admin.password.reset', [
             'token' => $this->token,
             'email' => $notifiable->email,
         ], false));

         return (new MailMessage)
             ->subject('Réinitialisation de votre mot de passe')
             ->greeting('Bonjour '.$notifiable->name.',')
             ->line('Vous recevez cet e-mail car nous avons reçu une demande de réinitialisation de mot de passe pour votre compte.')
             ->action('Réinitialiser mon mot de passe', $url)
             ->line('Ce lien de réinitialisation expirera dans 60 minutes.')
             ->line('Si vous n\'avez pas demandé cette réinitialisation, aucune action n\'est requise.');
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
