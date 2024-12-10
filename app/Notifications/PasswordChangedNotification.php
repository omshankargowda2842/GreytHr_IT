<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Request;
use Torann\GeoIP\Facades\GeoIP;
use Jenssegers\Agent\Agent;

class PasswordChangedNotification extends Notification
{
    use Queueable;

    protected $companyName;

    /**
     * Create a new notification instance.
     */
    public function __construct($companyName)
    {
        $this->companyName = $companyName;
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
    public function toMail($notifiable)
    {
        // Gather additional information
        $ipAddress = Request::ip(); // Get the user's IP address
        $location = GeoIP::getLocation($ipAddress); // Get location based on IP
        $browser = Request::header('User-Agent'); // Get the User-Agent (browser) information

        // Detect device info
        $agent = new Agent();
        $device = 'Unknown Device';
        $os = $agent->platform(); // OS for desktop, tablet, or mobile
        $osVersion = $agent->version($os); // OS version

        if ($agent->isDesktop()) {
            $device = 'Desktop';
        } elseif ($agent->isTablet()) {
            $device = 'Tablet';
        } elseif ($agent->isMobile()) {
            $device = 'Mobile';
        }

        return (new MailMessage)
            ->subject('Your Password Has Been Changed')
            ->view('emails.password_changed', [
                'user' => $notifiable,
                'companyName' => $this->companyName,
                'ipAddress' => $ipAddress,
                'location' => $location,
                'browser' => $browser,
                'device' => $device,
                'os' => $os,
                'osVersion' => $osVersion, // Include OS version
                'logoUrl' => asset('images/it_xpert_logo.png'),
            ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [

            // Optionally include other info if needed
        ];
    }
}
