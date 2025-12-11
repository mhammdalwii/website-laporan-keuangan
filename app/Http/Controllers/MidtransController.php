<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Notification;
use App\Models\Transaction;

class MidtransController extends Controller
{
    public function __construct()
    {
        // Set Midtrans configuration
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.sanitize');
        Config::$is3ds = config('midtrans.3ds');
    }

    public function notification(Request $request)
    {
        try {
            $notif = new Notification();

            $transaction = $notif->transaction_status;
            $type = $notif->payment_type;
            $order_id = $notif->order_id;
            $fraud = $notif->fraud_status;

            // Find transaction by order_id
            $transaction_record = Transaction::where('order_id', $order_id)->first();

            if ($transaction == 'capture') {
                // For credit card transaction, we need to check whether transaction is challenge by FDS or not
                if ($type == 'credit_card') {
                    if ($fraud == 'challenge') {
                        // TODO set transaction status on your database to 'challenge'
                        // and response with 200 OK
                        $transaction_record->update(['status' => 'challenge']);
                    } else {
                        // TODO set transaction status on your database to 'success'
                        // and response with 200 OK
                        $transaction_record->update(['status' => 'success']);
                    }
                }
            } else if ($transaction == 'settlement') {
                // TODO set transaction status on your database to 'success'
                // and response with 200 OK
                $transaction_record->update(['status' => 'success']);
            } else if ($transaction == 'pending') {
                // TODO set transaction status on your database to 'pending'
                // and response with 200 OK
                $transaction_record->update(['status' => 'pending']);
            } else if ($transaction == 'deny') {
                // TODO set transaction status on your database to 'denied'
                // and response with 200 OK
                $transaction_record->update(['status' => 'denied']);
            } else if ($transaction == 'expire') {
                // TODO set transaction status on your database to 'expire'
                // and response with 200 OK
                $transaction_record->update(['status' => 'expire']);
            } else if ($transaction == 'cancel') {
                // TODO set transaction status on your database to 'cancelled'
                // and response with 200 OK
                $transaction_record->update(['status' => 'cancelled']);
            }

            return response()->json(['message' => 'Notification handled'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
