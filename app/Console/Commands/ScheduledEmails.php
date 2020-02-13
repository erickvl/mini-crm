<?php

namespace App\Console\Commands;

use App\Email;
use App\Mail\SendMailable;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class ScheduledEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'All scheduled emails today will be sent';

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
     * @return mixed
     */
    public function handle()
    {
        // I used futureDate to test scheduled emails
        $futureDate = Carbon::parse('2020-02-14');
        $now = Carbon::now();

        // check all emails that scheduled to be sent today
        $emails = Email::whereDate('email_date', '=', $futureDate)->get();

        foreach ($emails as $email) {
            if ( !$email->delivered ) {
                Mail::send('email.reminder', ['data' => $email], function ($m) use ($email) {
                    $m->from( 'admin@admin.com', 'MINI_CRM');
        
                    $m->to($email['receiver'], '')->subject($email['subject']);
                });

                $email->update([
                    'delivered' => true
                ]);
            }
        }
    }
}
