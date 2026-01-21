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
    // Pastikan ada query ini untuk mengambil data ke view
    // 'latest()' agar data terbaru muncul paling atas
    $transactions = Transaction::where('status', 'success')->latest()->get();

    return view('input_data', compact('transactions'));
    }

    public function store(Request $request)
{
    // 1. Validasi
    $request->validate([
        'name' => 'required',
        'total_price' => 'required|numeric',
        // Validasi gambar: wajib format gambar, maks 2MB
        'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
    ]);

    // 2. Cek apakah user mengupload gambar
    $imagePath = null;
    if ($request->hasFile('image')) {
        $imagePath = $request->file('image')->store('transactions', 'public');
    }

    // 3. Simpan ke Database
    Transaction::create([
        'order_id' => 'MANUAL-' . time(), // Contoh hasil: MANUAL-1705638291
        'name' => $request->name,
        'total_price' => $request->total_price,
        'status' => 'success',
        'image' => $imagePath,
    ]);

    return redirect()->route('dashboard')->with('success', 'Data berhasil disimpan!');
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
