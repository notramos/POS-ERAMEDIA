<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Penjualan #{{ $transaction->id }}</title>
    <style>
        body {
            font-family: 'Courier New', monospace;
            width: 300px;
            margin: 0 auto;
            padding: 10px;
            font-size: 12px;
            line-height: 1.4;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .bold { font-weight: bold; }
        .divider { border-top: 1px dashed #000; margin: 8px 0; }
        .no-margin { margin: 0; }
        .small { font-size: 11px; }
        @media print {
            body { width: auto; margin: 0; padding: 0; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>

<div class="text-center bold">
    POS ERAMEDIA
</div>
<div class="text-center small">
    Jl. Contoh No. 123, Kota Contoh<br>
    Telp: (021) 1234-5678
</div>

<div class="divider"></div>

<div class="text-center">
    STRUK PENJUALAN
</div>
<div class="text-center small">
    {{ $transaction->created_at->format('d/m/Y H:i') }}
</div>

<div class="divider"></div>

@foreach($transaction->details as $detail)
    <div class="item">
        <div>{{ substr($detail->product->name ?? 'Produk', 0, 25) }}</div>
        <div class="text-right">
            {{ $detail->quantity }} x Rp {{ number_format($detail->price, 0, ',', '.') }} = Rp {{ number_format($detail->subtotal, 0, ',', '.') }}
        </div>
    </div>
@endforeach

<div class="divider"></div>

<div class="text-right">
    Total: Rp {{ number_format($transaction->total_price, 0, ',', '.') }}
</div>
<div class="text-right">
    Bayar: Rp {{ number_format($transaction->paid_amount, 0, ',', '.') }}
</div>
<div class="text-right bold">
    Kembali: Rp {{ number_format($transaction->change_amount, 0, ',', '.') }}
</div>

<div class="divider"></div>

<div class="text-center small">
    Terima kasih atas kunjungan Anda!<br>
    Simpan struk ini sebagai bukti transaksi.
</div>

<div class="no-print text-center" style="margin-top: 20px;">
    <button onclick="window.print()" class="btn btn-primary">🖨️ Cetak Struk</button>
    <a href="{{ route('transactions.list') }}" class="btn btn-secondary">← Kembali</a>
</div>

</body>
</html>