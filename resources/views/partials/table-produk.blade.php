@foreach($products as $index => $product)
    <tr 
        data-index="{{ $index }}"
        data-id="{{ $product->id }}"
        data-name="{{ $product->name }}"
        data-price="{{ $product->price }}"
        data-stock="{{ $product->stock }}"
        data-unit="{{ optional($product->unit)->name ?? 'pcs' }}"
        class="product-row {{ $product->stock <= 0 ? 'out-of-stock-row' : '' }}"
    >
        <td>
            <span class="product-number">{{ $index + 1 }}</span>
            <div class="product-name">{{ $product->name }}</div>
            <small class="product-unit">{{ optional($product->unit)->name ?? 'pcs' }}</small>
        </td>
        <td>
            @if($product->stock <= 0)
                <span class="stock-badge out">Habis</span>
            @elseif($product->stock < 10)
                <span class="stock-badge low-stock">
                    <i class="fas fa-exclamation-triangle me-1"></i>Low: {{ $product->stock }}
                </span>
            @else
                <span class="stock-badge available">{{ $product->stock }}</span>
            @endif
        </td>
        <td class="price-text">Rp {{ number_format($product->price, 0, ',', '.') }}</td>
        <td>
            @if($product->stock > 0)
                <div class="input-group input-group-sm" style="width: 100px;">
                    <input type="number" class="form-control text-center" style="width: 45px;" value="1" min="1" max="{{ $product->stock }}">
                    <button type="button" class="btn btn-success btn-sm">
                        <i class="fas fa-plus"></i>
                    </button>
                </div>
            @else
                <span class="text-danger fw-bold">-</span>
            @endif
        </td>
    </tr>
@endforeach