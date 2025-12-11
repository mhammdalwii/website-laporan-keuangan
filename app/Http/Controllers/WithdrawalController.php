<?php

namespace App\Http\Controllers;

use App\Models\Withdrawal;
use Illuminate\Http\Request;
use Midtrans\Config;
use Midtrans\Iris;
use Illuminate\Support\Str;

class WithdrawalController extends Controller
{
    public function __construct()
    {
        // Set your Merchant Server Key
        Config::$serverKey = config('midtrans.server_key');
        // Set to Development/Sandbox Environment (default). Set to true for Production Environment.
        Config::$isProduction = config('midtrans.is_production');
    }

    public function create()
    {
        // This method can be used to show the withdrawal form if we decide to have a separate page
        return view('withdraw.create'); // Assuming we will create this view
    }

    public function store(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:10000',
            'beneficiary_name' => 'required|string|max:255',
            'beneficiary_account' => 'required|string|max:255',
            'beneficiary_bank' => 'required|string|max:255',
        ]);

        try {
            // Generate a unique reference number for the payout
            $referenceNo = 'VM-WD-' . strtoupper(Str::random(10));

            $payouts = [
                'payouts' => [
                    [
                        'beneficiary_name' => $request->beneficiary_name,
                        'beneficiary_account' => $request->beneficiary_account,
                        'beneficiary_bank' => $request->beneficiary_bank,
                        'beneficiary_email' => 'payout@example.com', // Should be a valid email. Using a placeholder.
                        'amount' => $request->amount,
                        'notes' => 'Withdrawal from Vending Machine',
                        'payout_id' => $referenceNo, // Use the generated reference number
                    ]
                ]
            ];

            // Create a local record first with a 'pending' status
            $withdrawal = Withdrawal::create([
                'reference_no' => $referenceNo,
                'amount' => $request->amount,
                'beneficiary_name' => $request->beneficiary_name,
                'beneficiary_account' => $request->beneficiary_account,
                'beneficiary_bank' => $request->beneficiary_bank,
                'status' => 'pending',
            ]);

            $iris = new Iris();
            $response = $iris->createPayouts($payouts);

            if (isset($response->payouts[0]->status) && $response->payouts[0]->status == 'queued') {
                // If payout is successfully queued, update status to 'queued'
                $withdrawal->status = 'queued';
                $withdrawal->save();

                return back()->with('success', 'Withdrawal request has been sent and is being processed.');
            } else {
                 // If payout fails, update status to 'failed' and log errors
                $withdrawal->status = 'failed';
                $withdrawal->save();
                
                $errorMessage = 'Failed to create payout.';
                if (isset($response->errors)) {
                    $errorMessage .= ' Errors: ' . implode(', ', (array) $response->errors);
                }
                return back()->withErrors(['payout_error' => $errorMessage]);
            }

        } catch (\Exception $e) {
             if (isset($withdrawal)) {
                $withdrawal->status = 'failed';
                $withdrawal->save();
            }
            return back()->withErrors(['payout_error' => 'An error occurred: ' . $e->getMessage()]);
        }
    }
}
