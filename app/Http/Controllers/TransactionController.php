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
    public function index()
    {
        $transactions = Transaction::with('product')->where('payment_type', '!=', 'manual')->latest()->get();
        return view('welcome', ['transactions' => $transactions]);
    }

    public function create()
    {
        $products = Product::all();
        $manualTransactions = Transaction::with('product')->where('payment_type', 'manual')->latest()->get();
        return view('input_data', [
            'products' => $products,
            'transactions' => $manualTransactions
        ]);
    }

    public function store(Request $request)
    {

        $request->validate([
            'name' => 'required|string|max:255',
            'total_price' => 'required|numeric|min:0',
        ]);

        Transaction::create([
            'order_id' => 'EXP-' . time(),
            'name' => $request->name,
            'total_price' => $request->total_price,
            'product_id' => null,
            'quantity' => 1,
            'total_hpp' => 0,
            'status' => 'success',
            'payment_type' => 'manual',
            'payment_response' => null,
        ]);

        return redirect()->back()->with('success', 'Pengeluaran berhasil disimpan!');
    }

    public function show(Transaction $transaction)
    {

        return view('transactions.show', compact('transaction'));
    }

    public function destroy(Transaction $transaction)
    {
        $transaction->delete();
        return redirect()->back()->with('success', 'Data berhasil dihapus.');
    }
}
