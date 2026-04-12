<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Models\Product;
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

    public function store(Request $request)
    {
        Log::info('Data yang diterima:', $request->all());
        
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'paid_amount' => $request->payment_method === 'tunai' ? 'required|numeric|min:0' : 'nullable',
            'discount' => 'nullable|numeric|min:0',
        ], [ 
            'items.required' => 'Minimal harus ada satu produk dalam transaksi',
            'items.*.product_id.required' => 'Produk harus dipilih',
            'items.*.product_id.exists' => 'Produk yang dipilih tidak valid',
            'items.*.quantity.required' => 'Jumlah produk harus diisi',
            'items.*.quantity.min' => 'Jumlah produk minimal 1',
            'paid_amount.required' => 'Jumlah bayar harus diisi',
            'paid_amount.min' => 'Jumlah bayar tidak boleh negatif',
            'payment_method.required' => 'Pilih metode pembayaran',
            'payment_method.in' => 'Metode pembayaran tidak valid',
        ]);

        try {
            $transaction = Transaction::createFromRequest(
                $request->items,
                $request->paid_amount,
                $request->payment_method,
                (float) $request->discount,
            );

            $transactionData = [
                'id' => $transaction->id,
                'total_price' => $transaction->total_price,
                'discount' => $transaction->discount,
                'paid_amount' => $transaction->paid_amount,
                'change_amount' => $transaction->change_amount,
                'payment_method' => $transaction->payment_method,
                'created_at' => $transaction->created_at,
                'details' => $transaction->details->map(function ($detail) {
                    return [
                        'product_name' => $detail->product->name,
                        'quantity' => $detail->quantity,
                        'price' => $detail->product->price, 
                        'subtotal' => $detail->subtotal,
                    ];
                })
            ];

            Log::info('Transaksi berhasil disimpan:', $transactionData);

            return response()->json([
                'success' => true,
                'html' => view('kasir.modal.modalDetail', ['transaction' => (object) $transactionData])->render()
            ]);
        } catch (\Exception $e) {
            Log::error('Error saat menyimpan transaksi:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan transaksi: ' . $e->getMessage()
            ], 422);
        }
    }


    // Cari produk untuk fitur AJAX
    public function searchProducts(Request $request)
    {
        $query = trim($request->get('q', ''));
        if (strlen($query) < 2) {
            return response()->json(['html' => '']);
        }

        $products = Product::with('unit')
            ->where('name', 'LIKE', "%{$query}%")
            ->limit(100)
            ->get();

        $html = view('partials.table-produk', compact('products'))->render();
        return response()->json(['html' => $html]);
    }


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
