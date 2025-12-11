<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Midtrans\Snap;
use Midtrans\Config;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $transactions = Transaction::with('product')->where('payment_type', '!=', 'manual')->latest()->get();
        return view('welcome', ['transactions' => $transactions]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $products = Product::all();
        $manualTransactions = Transaction::with('product')->where('payment_type', 'manual')->latest()->get();
        return view('input_data', [
            'products' => $products,
            'transactions' => $manualTransactions
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $product = Product::find($request->product_id);
        $order_id = 'ORD-' . time();
        $total_price = $product->price * $request->quantity;
        $total_hpp = $product->hpp * $request->quantity;

        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = true;
        Config::$is3ds = true;

        $params = array(
            'transaction_details' => array(
                'order_id' => $order_id,
                'gross_amount' => $total_price,
            ),
            'customer_details' => array(
                'first_name' => "Guest",
                'email' => "guest@example.com",
            ),
        );

        $snapToken = Snap::getSnapToken($params);

        $transaction = Transaction::create([
            'order_id' => $order_id,
            'product_id' => $product->id,
            'quantity' => $request->quantity,
            'total_price' => $total_price,
            'total_hpp' => $total_hpp,
            'status' => 'pending',
            'payment_response' => $snapToken,
        ]);

        return response()->json(['snap_token' => $snapToken]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Transaction $transaction)
    {
        return view('transactions.show', compact('transaction'));
    }
}
