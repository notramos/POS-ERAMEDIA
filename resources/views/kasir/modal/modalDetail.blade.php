<div style="font-family: 'Courier New', monospace; font-size: 12px; line-height: 1.4;">
    <div class="text-center" style="font-weight: bold;">
        POS ERAMEDIA
    </div>
    <div class="text-center" style="font-size: 11px;">
        Jl. Contoh No. 123, Kota Contoh<br>
        Telp: (021) 1234-5678
    </div>

    <hr style="border:0; border-top:1px dashed #000; margin:8px 0;">

    <div class="text-center">STRUK PENJUALAN #{{ $transaction->id }}</div>
    <div class="text-center" style="font-size: 11px;">
        {{ $transaction->created_at->format('d/m/Y H:i') }}<br>
        Metode: <strong>{{ ucfirst($transaction->payment_method) }}</strong>
    </div>

    <hr style="border:0; border-top:1px dashed #000; margin:8px 0;">

    @foreach($transaction->details as $detail)
        <div style="display: flex; justify-content: space-between; font-size: 12px;">
            <span>{{ substr($detail['product_name'] ?? 'Produk', 0, 22) }}</span>
            <span>{{ $detail['quantity'] }} x {{ number_format($detail['price']) }} = {{ number_format($detail['subtotal']) }}</span>
        </div>
    @endforeach

    <hr style="border:0; border-top:1px dashed #000; margin:8px 0;">

    <div style="text-align: right; font-size: 12px;">
        Total: Rp {{ number_format($transaction->total_price, 0, ',', '.') }}<br>
        Bayar: Rp {{ number_format($transaction->paid_amount, 0, ',', '.') }}<br>
        <p><strong>Diskon:</strong> Rp {{ number_format($transaction->discount, 0, ',', '.') }}</p>
        <strong>Kembali: Rp {{ number_format($transaction->change_amount, 0, ',', '.') }}</strong>
    </div>

    <hr style="border:0; border-top:1px dashed #000; margin:8px 0;">

    <div class="text-center" style="font-size: 11px; margin-top: 10px;">
        Terima kasih atas kunjungan Anda!<br>
        Simpan struk ini sebagai bukti transaksi.
    </div>
</div>

<div class="text-center mt-4">
    <button class="btn btn-success btn-sm" onclick="loadPrintPreview({{ $transaction->id }})">
        🖨️ Cetak Struk
    </button>
    <button class="btn btn-secondary btn-sm" onclick="closeModal()">
        Tutup
    </button>
</div>

