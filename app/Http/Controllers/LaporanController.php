<?php

namespace App\Http\Controllers;

use App\Models\Transaction;


use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $query = Transaction::with('details.product')
            ->orderBy('created_at', 'desc');

        // Search functionality
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                    ->orWhere('total_price', 'like', "%{$search}%")
                    ->orWhere('paid_amount', 'like', "%{$search}%");
            });
        }

        $transactions = $query->paginate(10);
        // Kalau request via AJAX, return hanya bagian isi tabel
        if ($request->ajax()) {
            return view('transaction.partials.list', compact('transactions'))->render();
        }

        // Jika request AJAX untuk mendapatkan detail transaksi
        if ($request->ajax() && $request->has('transaction_id')) {
            $transaction = Transaction::with('transactionDetails.product')
                ->findOrFail($request->transaction_id);

            return response()->json([
                'transaction' => $transaction,
                'details' => $transaction->transactionDetails->map(function ($detail) {
                    return [
                        'id' => $detail->id,
                        'product_id' => $detail->product_id,
                        'product_name' => $detail->product->name ?? 'Product Not Found',
                        'quantity' => $detail->quantity,
                        'subtotal' => $detail->subtotal,
                        'formatted_subtotal' => $detail->formatted_subtotal
                    ];
                })
            ]);
        }

        return view('transaction.transactionindex', compact('transactions'));
    }

    public function show($id)
    {
        $transaction = Transaction::with('details.product', 'details.unit')->findOrFail($id);

        $details = $transaction->details->map(function ($item) {
            $unitName = optional($item->unit)->name;
            $unitPrice = optional($item->unit)->price_per_unit;

            // Gunakan harga dari unit jika tersedia, jika tidak fallback ke harga produk
            $price = $unitPrice ?? $item->product->price;

            // Hitung subtotal
            $subtotal = $price * $item->quantity;

            // Format satuan tampilannya
            $formattedQty = $item->quantity . ' ' . ($unitName === 'pcs' ? 'pcs' : ($unitName ?? 'item'));

            return [
                'product_name' => $item->product->name,
                'quantity' => $item->quantity,
                'formatted_qty' => $formattedQty,
                'price' => number_format($price, 0, ',', '.'),
                'subtotal' => number_format($subtotal, 0, ',', '.'),
                'unit' => $unitName ?? 'item',
            ];
        });

        return response()->json([
            'details' => $details
        ]);
    }
}
