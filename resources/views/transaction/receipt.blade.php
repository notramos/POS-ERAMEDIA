<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Bon Transaksi #{{ $transaction->id }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            width: 300px;
            margin: 0 auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th,
        td {
            padding: 3px 0;
            font-size: 12px;
        }

        tfoot td {
            font-weight: bold;
            border-top: 1px solid #000;
        }

        @media print {
            button {
                display: none;
            }
        }
    </style>
</head>

<body>
    <h2 style="text-align:center;">ERAMEDIA</h2>
    <p style="text-align:center;">Jl. Contoh No. 123</p>
    <hr>

    <p><strong>Tanggal:</strong> {{ $transaction->created_at->format('d/m/Y H:i') }}</p>
    <p><strong>No Transaksi:</strong> #{{ $transaction->id }}</p>
    <p><strong>Customer:</strong> {{ $transaction->customer_name ?? 'Umum' }}</p>

    <table>
        <thead>
            <tr>
                <th>Produk</th>
                <th>Qty</th>
                <th align="right">Harga</th>
                <th align="right">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($transaction->details as $item)
                <tr>
                    <td>{{ $item->product->name ?? '-' }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td align="right">{{ number_format($item->product->price, 0, ',', '.') }}</td>
                    <td align="right">{{ number_format($item->subtotal, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3">Total</td>
                <td align="right">{{ number_format($transaction->total_price, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td colspan="3">Paid Amount</td>
                <td align="right">{{ number_format($transaction->paid_amount, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td colspan="3">Change Amount</td>
                <td align="right">{{ number_format($transaction->change_amount, 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>

    <div style="text-align:center; margin-top:15px;">
        <p>Terima kasih atas pembelian Anda!</p>
        <button onclick="window.print()">🖨 Cetak</button>
    </div>
</body>

</html>
