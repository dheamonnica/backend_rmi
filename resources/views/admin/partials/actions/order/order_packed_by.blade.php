<td>
    @if($order->packed_by)
        {{ $order->getPackedByName->warehouse_name }}
    @endif
</td>
