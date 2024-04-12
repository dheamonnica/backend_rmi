<td>
    @if($order->shipped_by)
        {{ $order->getFulfilledName->warehouse_name }}
    @endif
</td>
