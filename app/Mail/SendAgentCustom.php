<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendAgentCustom extends Mailable
{
    use Queueable, SerializesModels;

    public $Custom;


    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($Custom=[])
    {
        $this->Custom = $Custom;
   
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('')
                    ->view('template.frontend.userdashboard.pages.agents.emails.custom')->with(
                        'Custom',$this->Custom,
                        
                    );
    }
}
