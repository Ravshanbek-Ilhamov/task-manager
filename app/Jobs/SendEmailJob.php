<?php

namespace App\Jobs;

use App\Mail\ResetPassword;
use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendEmailJob implements ShouldQueue
{
    use Queueable;

    public $details;
    /**
     * Create a new job instance.
     */
    public function __construct($details)
    {
        $this->details = $details;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            Mail::to($this->details['email'])->send(new ResetPassword($this->details));

        } catch (Exception $e) {
            Log::error('Job failed: ' . $e->getMessage());
            throw $e; 
        }
    }
}
