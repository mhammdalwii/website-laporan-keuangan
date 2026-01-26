<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use Illuminate\Support\Facades\Storage;

class TransactionController extends Controller
{
    public function index()
    {
        $transactions = Transaction::with('product')
            ->where('payment_type', '!=', 'manual')
            ->latest()
            ->get();

        return view('welcome', compact('transactions'));
    }

    public function laporan()
    {
        $transactions = \App\Models\Transaction::latest()->get();

        return view('laporan', compact('transactions'));
    }

    public function create()
    {
        $transactions = Transaction::where('status', 'success')
            ->latest()
            ->get();

        return view('input_data', compact('transactions'));
    }

    public function store(Request $request)
    {
        // 1️⃣ Validasi
        $request->validate([
            'name'        => 'required|string|max:255',
            'total_price'=> 'required|numeric|min:0',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // 2️⃣ Upload bukti transaksi
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')
                ->store('bukti-transaksi', 'public');
        }

        // 3️⃣ Simpan ke DB
        Transaction::create([
            'order_id'     => 'MANUAL-' . time(),
            'name'         => $request->name,
            'quantity'     => 1,
            'total_price' => $request->total_price,
            'total_hpp'    => 0,
            'status'       => 'success',
            'payment_type' => 'manual',
            'image'        => $imagePath,
        ]);

        return redirect()
            ->route('transactions.create')
            ->with('success', 'Data berhasil disimpan!');
    }

    public function destroy(Transaction $transaction)
    {
        // 🗑️ Hapus file bukti jika ada
        if ($transaction->image) {
            Storage::disk('public')->delete($transaction->image);
        }

        $transaction->delete();

        return back()->with('success', 'Data berhasil dihapus.');
    }
}
