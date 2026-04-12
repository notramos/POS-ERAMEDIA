<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
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
        .small { font-size: 11px; }
        @media print {
            body { width: auto; margin: 0; padding: 0; }
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
    STRUK PENJUALAN #{{ $transaction->id }}
</div>
<div class="text-center small">
    {{ $transaction->created_at->format('d/m/Y H:i') }}<br>
    Metode: {{ ucfirst($transaction->payment_method) }}
</div>

<div class="divider"></div>

@foreach($transaction->details as $detail)
    <div>
        <div>{{ substr($detail['product_name']?? 'Produk', 0, 25) }}</div>
        <div class="text-right">
            {{ $detail['quantity'] }} x Rp {{ number_format($detail['price'], 0, ',', '.') }} = Rp {{ number_format($detail['subtotal'], 0, ',', '.') }}
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

<script>
window.onload = function() {
    window.print();
};
</script>

</body>
</html>