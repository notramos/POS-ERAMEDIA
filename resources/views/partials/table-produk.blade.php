@foreach($products as $product)
    <tr 
        data-id="{{ $product->id }}"
        data-name="{{ $product->name }}"
        data-price="{{ $product->price }}"
        data-stock="{{ $product->stock }}"
        data-unit="{{ optional($product->unit)->name ?? 'pcs' }}"
        @if($product->stock <= 0) class="out-of-stock" @endif
    >
        <td>{{ $product->name }}</td>
        <td>{{ $product->stock }}</td>
        <td>Rp {{ number_format($product->price, 0, ',', '.') }}</td>
        <td>{{ optional($product->unit)->name ?? 'pcs' }}</td>
        <td>
            @if($product->stock > 0)
                <div class="input-group input-group-sm" style="width: 150px;">
                    <input 
                        type="number" 
                        class="form-control form-control-sm qty-add" 
                        value="1" 
                        min="1" 
                        max="{{ $product->stock }}" 
                        style="width: 60px; text-align: center;"
                        onkeypress="return event.charCode >= 48 && event.charCode <= 57"
                    >
                    <button type="button" class="btn btn-success btn-sm btn-add-item">+</button>
                </div>
            @else
                <span class="text-danger fw-bold">Habis</span>
            @endif
        </td>
    </tr>
@endforeach