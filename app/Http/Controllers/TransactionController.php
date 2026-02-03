<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use App\Models\TransactionDetail;
use Illuminate\Support\Facades\Log;
use App\Models\Unit;
use Illuminate\Support\Facades\Redirect;



class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $units = Unit::All();
        $products = Product::paginate(5);
        return view('kasir.create', compact('products', 'units'));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Log::info('Data yang diterima:', $request->all());

    $request->validate([
        'items' => 'required|array|min:1',
        'items.*.product_id' => 'required|exists:products,id',
        'items.*.quantity' => 'required|integer|min:1',
        'paid_amount' => 'required|numeric|min:0',
    ], [
        'items.required' => 'Minimal harus ada satu produk dalam transaksi',
        'items.*.product_id.required' => 'Produk harus dipilih',
        'items.*.product_id.exists' => 'Produk yang dipilih tidak valid',
        'items.*.quantity.required' => 'Jumlah produk harus diisi',
        'items.*.quantity.min' => 'Jumlah produk minimal 1',
        'paid_amount.required' => 'Jumlah bayar harus diisi',
        'paid_amount.min' => 'Jumlah bayar tidak boleh negatif',
    ]);

    try {
        $transaction = Transaction::createFromRequest(
            $request->items,
            (float) $request->paid_amount

        );

        return redirect()->route('kasir.index')->with('success', 'Transaksi berhasil disimpan dengan ID: ' . $transaction->id);

    } catch (\Exception $e) {
        Log::error('Error saat menyimpan transaksi:', [
            'message' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
        ]);

        return redirect()
            ->back()
            ->withInput()
            ->with('error', 'Gagal menyimpan transaksi: ' . $e->getMessage());
    }
    }

    /**
     * Display the specified resource.
     */
    public function show(Transaction $transaction)
    {
        $transaction->load('details.product');
        return view('kasir.detail', compact('transaction'));
    }

    public function list(Request $request)
    {
        $query = Transaction::with('details.product')->orderBy('created_at', 'desc');

            // Filter search
            if ($request->filled('search')) {
                $keyword = $request->search;
                $query->where(function ($q) use ($keyword) {
                    $q->where('id', 'like', "%{$keyword}%")
                    ->orWhereHas('details.product', function ($pq) use ($keyword) {
                        $pq->where('name', 'like', "%{$keyword}%");
                    });
                });
            }

            // Filter tanggal
            if ($request->filled('start_date') && $request->filled('end_date')) {
                $start = $request->start_date;
                $end = $request->end_date;
                $query->whereBetween('created_at', ["{$start} 00:00:00", "{$end} 23:59:59"]);
            }

            // Tambahkan paginate (10 per halaman)
            $transactions = $query->paginate(10);

            return view('transaction.transaction', compact('transactions'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Transaction $transaction)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Transaction $transaction)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Transaction $transaction)
    {
        $transaction->delete();

        return Redirect::back()->with('success', 'Transaksi berhasil dihapus.');
    }

    public function receipt(Transaction $transaction)
    {
         $transaction->load('details.product');
        return view('transaction.receipt', compact('transaction'));
    }
}
