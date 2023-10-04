<?php

namespace App\Console\Commands;

use App\Models\PendingSMS;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class SendSMS extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:sms';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send scheduled SMS messages';

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

        // Retrieve and send scheduled SMS messages
        $scheduledMessages = PendingSMS::where('scheduled_at', '<=', now()->format('d-m-Y H:i'))
            ->where('status', 'pending') // Only process pending messages
            ->get();

        foreach ($scheduledMessages as $message) {
            // Replace the following code with your SMS API integration
            $response = Http::get('http://bulksmsbd.net/api/smsapi', [
                'api_key' => '98FSjkLUFkrB5DqDFIV5',
                'type' => 'text',
                'number' => $message->phones,
                'senderid' => '8809617612955',
                'message' => $message->message,
            ]);

            if ($response->successful()) {

                $message->update(['status' => 'deliver']);
            } else {

                $message->update(['status' => 'error']);
                // Handle the error as needed, e.g., mark the message as failed
            }
        }

        return response()->json([
            'status'        => 1,
            'message'       => "Success"
        ]);
    }
}