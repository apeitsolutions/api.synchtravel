<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendAgentSale extends Mailable
{
    use Queueable, SerializesModels;
 public $sales;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($sales=[])
    {
       $this->sales = $sales;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
         return $this->subject('')
                    ->view('template.frontend.userdashboard.pages.agents.emails.sales')->with('sales',$this->sales);
    }
}
