<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendAgentMail extends Mailable
{
    use Queueable, SerializesModels;

        public $welcome;
   
       

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($welcome=[])
    {
        
       $this->welcome = $welcome;
      
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {


        return $this->subject('')
                    ->view('template/frontend/userdashboard/pages/customer_subcription/agent_email/send_email_marketing')->with('welcome',$this->welcome);
        
      
    }
}
