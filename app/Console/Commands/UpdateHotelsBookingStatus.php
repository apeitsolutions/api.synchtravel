<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Jobs\UpdateHotelsBookingJobsStatus;
use Carbon\Carbon;
use DB;

class UpdateHotelsBookingStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'booking:update-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update the booking status after 24 hours';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // Get the bookings that need status updates (those made 24 hours ago)
        $bookings   = DB::table('hotels_bookings')
                        // ->where('customer_id','48')
                        ->where('invoice_no','ASH3495492')
                        // ->where('payment_Type','Bank_Payment')
                        // ->where('created_at', '<', Carbon::now()->subHours(24))
                        // ->where('status', '!=', 'CANCELLED')
                        ->get();
        
        // $this->info($bookings);
        
        foreach ($bookings as $val_BD) {
            // Dispatch the job to update the booking status
            // if(isset($val_BD->payment_details) && $val_BD->payment_details != null && $val_BD->payment_details != ''){
                $test = UpdateHotelsBookingJobsStatus::dispatch($val_BD->invoice_no);
                // return $test;
            // }
        }

        $this->info('Booking statuses updated successfully!');
        
        // return 0;
    }
}
