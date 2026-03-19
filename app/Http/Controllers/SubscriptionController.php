<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Unicodeveloper\Paystack\Facades\Paystack;
use App\Models\Subscription;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class SubscriptionController extends Controller
{
    
    public function subscribe()
    {
        $user = auth()->user();

        $reference = Str::uuid();

        $amount = 5000 * 100; // ₦5000 monthly

        $response = Http::withToken(config('services.paystack.secret_key'))
            ->post('https://api.paystack.co/transaction/initialize', [
                'email' => $user->email,
                'amount' => $amount,
                'reference' => $reference,
                'callback_url' => route('subscription.callback'),
                'metadata' => [
                    'user_id' => $user->id
                ]
            ])->json();

        if (!$response['status']) {
            return back()->with('error', 'Unable to initialize payment');
        }

        Subscription::create([
            'user_id' => $user->id,
            'reference' => $reference,
            'amount' => 5000,
            'starts_at' => now(),
            'expires_at' => now()->addMonth(),
            'status' => 'active'
        ]);

        return redirect($response['data']['authorization_url']);
    }

    public function callback(Request $request)
    {
        $reference = $request->reference;

        if (!$reference) {
            return redirect()->route('subscription.page')
                ->with('error','No payment reference supplied');
        }

        $response = Http::withToken(config('services.paystack.secret_key'))
            ->get("https://api.paystack.co/transaction/verify/".$reference)
            ->json();

        if (!$response['status']  || $response['data']['status'] !== 'success') {
            return redirect()->route('subscription.page')
                ->with('error','Payment verification failed');
        }

        $paymentData = $response['data'];

        if ($paymentData['status'] === 'success') {

            $userId = $paymentData['metadata']['user_id'];

            Subscription::updateOrCreate(
                ['reference' => $reference],
                [
                    'user_id' => $userId,
                    'amount' => $paymentData['amount'] / 100,
                    'starts_at' => now(),
                    'expires_at' => now()->addMonth(),
                    'status' => 'active'
                ]
            );

            return redirect()
                ->route('car.create')
                ->with('success','Subscription activated. Continue posting your car.');
        }

        return redirect()->route('subscription.page')
            ->with('error','Payment not successful');
    }
}