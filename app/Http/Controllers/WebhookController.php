<?php

namespace App\Http\Controllers;

use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{

    public function webhook(Request $request)
    {
        // Verify Paystack signature
        $signature = $request->header('x-paystack-signature');

        $computedSignature = hash_hmac(
            'sha512',
            $request->getContent(),
            config('services.paystack.secret_key')
        );

        if ($signature !== $computedSignature) {
            abort(403, 'Invalid signature');
        }

        $payload = $request->all();

        // Handle event
        if ($payload['event'] === 'charge.success') {

            $data = $payload['data'];

            Subscription::updateOrCreate(
                ['reference' => $data['reference']],
                [
                    'user_id' => $data['metadata']['user_id'],
                    'amount' => $data['amount'] / 100,
                    'starts_at' => now(),
                    'expires_at' => now()->addMonth(),
                    'status' => 'active'
                ]
            );
        }

        return response()->json(['status' => 'success']);
    }
}
