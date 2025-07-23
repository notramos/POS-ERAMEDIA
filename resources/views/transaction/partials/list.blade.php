  @forelse($transactions as $transaction)
      <tr class="hover:bg-gray-50 cursor-pointer transaction-row" data-id="{{ $transaction->id }}">
          <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
              {{ $transaction->formatted_id }}
          </td>
          <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
              {{ $transaction->formatted_total_price }}
          </td>
          <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
              {{ $transaction->formatted_paid_amount }}
          </td>
          <td
              class="px-6 py-4 whitespace-nowrap text-sm {{ $transaction->change_amount > 0 ? 'text-green-600' : 'text-gray-500' }}">
              {{ $transaction->formatted_change_amount }}
          </td>
          <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
              {{ $transaction->formatted_date }}
          </td>
          <td class="px-6 py-4 whitespace-nowrap  text-sm font-medium">
              <a href="{{ route('kasir.detail', $transaction) }}" class="text-green-600 hover:text-green-700 mr-3">
                  <i class="fas fa-receipt"></i>
              </a>
              <form method="POST" action="" class="inline-block"
                  onsubmit="return confirm('Are you sure you want to delete this transaction?')">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="text-red-600 hover:text-red-700">
                      <i class="fas fa-trash"></i>
                  </button>
              </form>
          </td>
      </tr>
  @empty
      <tr>
          <td colspan="6" class="px-6 py-12 text-center text-gray-500">
              <i class="fas fa-inbox text-4xl mb-4 text-gray-300"></i>
              <p>No transactions found</p>
          </td>
      </tr>
  @endforelse
