<table border="1">
  <tr align="center">
    <td width="100px">
      Customars</br>Style Ref</td>
    <td width="100px">Customars</br>Buyer</td>
    <td width="100px">Customars</br>Sale Order No</td>
    <td width="250px">Item</td>
    <td width="80px">UOM</td>
    <td width="80px">Order Qty</td>
    <td width="80px">Total PI</td>
    <td width="80px">Balance Qty</td>
    <td width="80px">PI Qty</td>
    <td width="70px">Rate</td>
    <td width="80px">Amount</td>
    <td width="80px">UP/Down<br />Charge</td>
    <td width="80px">Net <br />Amount</td>
  </tr>
  <tbody>
    <?php 
      $i=1;
    ?>
    @foreach($orders as $order)
    <tr align="center">
      <td width="100px">
        {{ $order->style_ref }}
        <input type="hidden" name="sales_order_ref_id[{{ $i }}]" id="sales_order_ref_id{{ $i }}"
          value="{{ $order->sales_order_ref_id}}" />
        <input type="hidden" name="sales_order_item_id[{{ $i }}]" id="sales_order_item_id{{ $i }}"
          value="{{ $order->sales_order_item_id}}" />
      </td>
      <td width="100px">{{ $order->buyer_name }}</td>
      <td width="100px">{{ $order->sale_order_no }}</td>
      <td width="250px">{{ $order->item_description }}</td>
      <td width="80px">{{ $order->uom_code}}</td>
      <td width="80px">{{ $order->order_qty }} </td>
      <td width="80px">{{ $order->cumulative_qty }} </td>
      <td width="80px">
        <input type="text" name="balance_qty[{{ $i }}]" id="balance_qty{{ $i }}" class="number integer"
          value="{{ $order->balance_qty }}" readonly />
      </td>
      <td width="80px">
        <input type="text" name="qty[{{ $i }}]" id="qty{{ $i }}" class="number integer"
          value="{{ $order->balance_qty }}" onchange="MsLocalExpPiOrder.calculate({{ $i }},{{ $loop->count}})" />
      </td>
      <td>
        <input type="text" name="rate[{{ $i  }}]" id="rate_{{ $i }}" class="number integer"
          value="{{ $order->order_rate }}" onchange="MsLocalExpPiOrder.calculate({{ $i }},{{ $loop->count}})"
          readonly />
      </td>
      <td>
        <input type="text" name="amount[{{ $i }}]" id="amount_{{ $i }}" class="number"
          value="{{ $order->tagable_amount }}" onchange="MsLocalExpPiOrder.netDiscountTotal({{ $i }},{{ $loop->count}})"
          readonly />
      </td>
      <td>
        <input type="text" name="discount_per[{{ $i }}]" id="discount_per_{{ $i }}" class="number number" value=""
          onchange="MsLocalExpPiOrder.netDiscountTotal({{ $i }},{{ $loop->count}})" />
      </td>
      <td>
        <input type="text" name="net_amount[{{ $i }}]" id="net_amount_{{ $i }}" class="number number"
          value="{{ $order->net_amount }}" />
      </td>
    </tr>
    <?php $i++;?>
    @endforeach
  </tbody>
</table>
<script>
  $('.integer').keyup(function () {
    if (this.value != this.value.replace(/[^0-9\.]/g, '')) {
       this.value = this.value.replace(/[^0-9\.]/g, '');
    }
});
</script>