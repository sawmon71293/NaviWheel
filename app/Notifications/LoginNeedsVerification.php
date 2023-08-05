<?php

namespace App\Notifications;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use NotificationChannels\Twilio\TwilioSmsMessage;
use NotificationChannels\Twilio\TwilioChannel;
use Illuminate\Notifications\Notification;

class LoginNeedsVerification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct()
    {
        //
    }


    // public function via(object $notifiable): array
    // {
    //     return [TwilioChannel::class];
    // }

    // public function toTwilio($notifiable)
    // {
    //     $loginCode= rand(111111,999999);
    //     return (new TwilioSmsMessage())
    //     ->content("Your Andrewber login code is {$loginCode}, don't share this with anyone");
    // }



    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $loginCode= rand(111111,999999);
        
        $notifiable->update([
            'login_code'=>$loginCode
        ]);
        return (new MailMessage)
                    ->line("Here is your login code {$loginCode} ")
                    ->line('Thank you for using our application!');
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
