<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use App\Models\TransactionDetail;
use Illuminate\Support\Facades\Log;
use App\Models\Unit;



class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $units = Unit::All();
        $products = Product::all();
        return view('kasir.create', compact('products', 'units'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
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
            DB::beginTransaction();

            Log::info('Mulai proses transaksi');

            // Hitung total transaksi
            $transactionItems = [];
            $totalAmount = 0;

            foreach ($request->items as $item) {
                $product = Product::with('unit')->findOrFail($item['product_id']); // ambil relasi unit langsung

                $unit = $product->unit;
                if (!$unit) {
                    throw new \Exception('Unit tidak ditemukan untuk produk: ' . $product->name);
                }

                $quantity = (int) $item['quantity'];
                $price = $unit->price_per_unit ?? $product->price;
                $subtotal = $price * $quantity;

                $transactionItems[] = [
                    'product_id' => $product->id,
                    'unit_id' => $unit->id,
                    'quantity' => $quantity,
                    'price' => $price,
                    'subtotal' => $subtotal,
                ];

                $totalAmount += $subtotal;
            }
            Log::info('Total amount calculated:', ['total' => $totalAmount]);

            // Validasi pembayaran
            $paidAmount = (float) $request->paid_amount;
            if ($paidAmount < $totalAmount) {
                throw new \Exception('Jumlah bayar tidak mencukupi. Total: Rp ' . number_format($totalAmount, 0, ',', '.'));
            }

            $changeAmount = $paidAmount - $totalAmount;

            // Data yang akan disimpan
            $transactionData = [
                'total_price' => $totalAmount,
                'paid_amount' => $paidAmount,
                'change_amount' => $changeAmount,
            ];

            Log::info('Data transaksi yang akan disimpan:', $transactionData);

            // Buat transaksi baru
            $transaction = Transaction::create($transactionData);

            Log::info('Transaksi berhasil dibuat:', ['id' => $transaction->id]);

            // Buat detail transaksi
            foreach ($transactionItems as $item) {
                $detailData = [
                    'transaction_id' => $transaction->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'subtotal' => $item['subtotal'],
                    'unit_id' => $item['unit_id'],
                ];

                Log::info('Membuat detail transaksi:', $detailData);

                TransactionDetail::create($detailData);
            }

            DB::commit();
            Log::info('Transaksi berhasil di-commit');
            return response()->json([
                'success' => true,
                'message' => 'Transaksi berhasil disimpan',
                'transaction_id' => $transaction->id,
                'total' => $totalAmount,
                'change' => $changeAmount
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Error dalam transaksi:', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat menyimpan transaksi: ' . $e->getMessage());
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
        //
    }

    public function receipt(Transaction $transaction)
    {
        $transaction->load('details.product');
        return view('transactions.receipt', compact('transaction'));
    }
}
