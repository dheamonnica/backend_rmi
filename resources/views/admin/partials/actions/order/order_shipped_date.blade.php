<td>
    @if($order->shipping_date)
        {{ $order->shipping_date->toDayDateTimeString() }}
    @endif
</td>
