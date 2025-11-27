<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendAgentMarketting extends Mailable
{
    use Queueable, SerializesModels;
    
    public $marketting;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($marketting=[])
    {
        $this->marketting = $marketting;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('')
                    ->view('template.frontend.userdashboard.pages.agents.emails.marketting')->with('marketting',$this->marketting);
    }
}
