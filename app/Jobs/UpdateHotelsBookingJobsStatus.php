<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdateHotelsBookingJobsStatus implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    
    protected $invoiceId;
    
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($invoiceId)
    {
        $this->invoiceId = $invoiceId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        return 'OK';
        // Update the booking status after 24 hours
        DB::table('hotels_bookings')->where('invoice_no', $this->invoiceId)->update(['status' => 'CANCELLED']);
        
        // Optionally log or output a message
        // \Log::info("Booking status for invoice {$this->invoiceId} has been updated to CANCELLED.");
    }
}
