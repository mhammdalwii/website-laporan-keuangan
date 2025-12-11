<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Mengambil semua data ringkasan untuk dasbor utama.
     */
    public function getDashboardData()
    {
        // Menghitung metrik utama
        $totalRevenue = Transaction::sum('total_price');
        $totalProfit = DB::table('transactions')->sum(DB::raw('total_price - total_hpp'));
        $totalUnitsSold = Transaction::sum('quantity');

        // Menyiapkan data untuk grafik pendapatan 7 hari terakhir
        $revenueByDay = Transaction::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(total_price) as Pendapatan')
            )
            ->where('created_at', '>=', now()->subDays(7))
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get()
            ->map(function ($item) {
                // Format nama hari agar sesuai dengan frontend
                $item->name = \Carbon\Carbon::parse($item->date)->locale('id_ID')->isoFormat('ddd');
                $item->Pendapatan = (float) $item->Pendapatan;
                unset($item->date);
                return $item;
            });

        // Menyiapkan data untuk grafik produk terlaris
        $topProducts = Transaction::select(
                'products.name',
                DB::raw('SUM(transactions.quantity) as jumlah')
            )
            ->join('products', 'transactions.product_id', '=', 'products.id')
            ->groupBy('products.name')
            ->orderBy('jumlah', 'desc')
            ->limit(5)
            ->get();

        return response()->json([
            'metrics' => [
                'totalRevenue' => (float) $totalRevenue,
                'totalProfit' => (float) $totalProfit,
                'totalUnitsSold' => (int) $totalUnitsSold,
            ],
            'revenueChart' => $revenueByDay,
            'productChart' => $topProducts,
        ]);
    }

    /**
     * Mengambil daftar semua produk.
     */
    public function getProducts()
    {
        return response()->json(Product::all());
    }

    /**
     * Mengambil 10 transaksi terakhir.
     */
    public function getRecentTransactions()
    {
        $transactions = Transaction::with('product')->latest()->limit(10)->get();
        return response()->json($transactions);
    }

    /**
     * Mengambil seluruh riwayat transaksi.
     */
    public function getAllTransactions()
    {
        $transactions = Transaction::with('product')->latest()->get();
        return response()->json($transactions);
    }

    /**
     * Menerima dan menyimpan transaksi baru dari Vending Machine atau simulasi.
     */
    public function storeTransaction(Request $request)
    {
        $request->validate([
            'productId' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $product = Product::find($request->productId);

        $transaction = Transaction::create([
            'product_id' => $product->id,
            'quantity' => $request->quantity,
            'total_price' => $product->price * $request->quantity,
            'total_hpp' => $product->hpp * $request->quantity,
        ]);

        return response()->json(['message' => 'Transaksi berhasil disimpan!', 'data' => $transaction], 201);
    }
public function index()
{
    $transactions = Transaction::with('product')->latest()->get();
    return view('dashboard', compact('transactions'));
}
    /**
     * Menghapus semua riwayat transaksi.
     */
    public function clearTransactions()
    {
        // Menggunakan truncate untuk mereset tabel transaksi
        Transaction::truncate();
        return response()->json(['message' => 'Riwayat transaksi berhasil dibersihkan!']);
    }
}
