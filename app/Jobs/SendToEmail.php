<?php

namespace App\Jobs;

use App\Mail\QrInvitation;
use App\Mail\ZoomInvitation;
use App\Models\Registration;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendToEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(public $email, public $imagePath)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $data = Registration::where('email', $this->email)->first();

        Mail::to($this->email)->send(new QrInvitation($this->imagePath));
    }
}
