<td>
    @if($order->delivery_by)
        {{ $order->getPackedByName->warehouse_name }}
    @endif
</td>
