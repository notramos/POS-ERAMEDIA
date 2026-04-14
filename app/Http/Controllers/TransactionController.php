<?php

namespace App\Http\Controllers;

use App\Contracts\TransactionServiceInterface;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class TransactionController extends Controller
{
    protected TransactionServiceInterface $transactionService;

    public function __construct(TransactionServiceInterface $transactionService)
    {
        $this->transactionService = $transactionService;
    }

    protected function getService()
    {
        return $this->transactionService;
    }

    public function index()
    {
        $units = Unit::all();
        $products = Product::paginate(5);

        return view('kasir.create', compact('products', 'units'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'paid_amount' => 'nullable|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'payment_method' => 'required|in:tunai,qris',
        ], [
            'items.required' => 'Minimal harus ada satu produk dalam transaksi',
            'items.*.product_id.required' => 'Produk harus dipilih',
            'items.*.product_id.exists' => 'Produk yang dipilih tidak valid',
            'items.*.quantity.required' => 'Jumlah produk harus diisi',
            'items.*.quantity.min' => 'Jumlah produk minimal 1',
            'paid_amount.required' => 'Jumlah bayar harus diisi',
            'payment_method.required' => 'Pilih metode pembayaran',
        ]);

        try {
            $transaction = $this->transactionService->createFromRequest(
                $validated['items'],
                $validated['paid_amount'] ?? null,
                $validated['payment_method'],
                (float) ($validated['discount'] ?? 0),
            );

            $transactionData = [
                'id' => $transaction->id,
                'total_price' => $transaction->total_price,
                'discount' => $transaction->discount,
                'paid_amount' => $transaction->paid_amount,
                'change_amount' => $transaction->change_amount,
                'payment_method' => $transaction->payment_method,
                'created_at' => $transaction->created_at,
                'details' => $transaction->details->map(fn ($detail) => [
                    'product_name' => $detail->product->name,
                    'quantity' => $detail->quantity,
                    'price' => $detail->product->price,
                    'subtotal' => $detail->subtotal,
                ]),
            ];

            return response()->json([
                'success' => true,
                'html' => view('kasir.modal.modalDetail', ['transaction' => (object) $transactionData])->render(),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        }
    }

    public function searchProducts(Request $request)
    {
        $query = trim($request->get('q', ''));

        if (strlen($query) < 2) {
            return response()->json(['html' => '']);
        }

        $products = $this->transactionService->searchProducts($query);
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
        $filters = $request->query();
        $transactions = $this->transactionService->getAll($filters);

        return view('transaction.transaction', compact('transactions'));
    }

    public function destroy(Transaction $transaction)
    {
        try {
            $this->transactionService->delete($transaction->id);

            return Redirect::back()->with('success', 'Transaksi berhasil dihapus.');
        } catch (\Exception $e) {
            return Redirect::back()->with('error', $e->getMessage());
        }
    }

    public function receipt(Transaction $transaction)
    {
        $transaction->load('details.product');

        return view('transaction.receipt', compact('transaction'));
    }
}
