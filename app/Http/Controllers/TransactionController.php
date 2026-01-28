<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use Illuminate\Support\Facades\File;

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
        $transactions = Transaction::latest()->get();
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
        $request->validate([
            'name'         => 'required|string|max:255',
            'total_price'  => 'required|numeric|min:0',
            'image'        => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $imagePath = null;

        if ($request->hasFile('image')) {
            $file = $request->file('image');

            $filename = uniqid() . '.' . $file->getClientOriginalExtension();

            $destination = public_path('bukti-transaksi');

            if (!File::exists($destination)) {
                File::makeDirectory($destination, 0755, true);
            }

            $file->move($destination, $filename);

            $imagePath = 'bukti-transaksi/' . $filename;
        }

        Transaction::create([
            'order_id'     => 'MANUAL-' . time(),
            'name'         => $request->name,
            'quantity'     => 1,
            'total_price'  => $request->total_price,
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
        if ($transaction->image) {
            $filePath = public_path($transaction->image);
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }

        $transaction->delete();

        return back()->with('success', 'Data berhasil dihapus.');
    }
}
