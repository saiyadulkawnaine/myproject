<table border="1">
   <tr align="center">
          <td width="100px">Style Ref</td>
          <td width="100px">Job No</td>
          <td width="100px">Sale Order No</td>
          <td width="250px">Item</td>
          <td width="80px">UOM</td>
          <td width="80px">Order Qty</td>
          <td width="80px">Cumulative Qty</td>
          <td width="80px">Balance Qty</td>
          <td width="80px">Qty</td>
          <td width="70px">Rate</td>
          <td width="80px">Amount</td>
   </tr>
<tbody>
 <?php 
 $i=1;
 ?>
   @foreach($orders as $order)
               <tr align="center">
               <td width="100px">
               {{ $order->style_ref }}
                <input type="hidden" name="sales_order_id[{{ $i }}]" id="sales_order_id{{ $i }}" value="{{ $order->id}}"/>
               </td>
               <td width="100px">{{ $order->job_no }}</td>
               <td width="100px">{{ $order->sale_order_no }}</td>
               <td width="250px">
               {{ $order->item_description }}
               </td>
               <td width="80px">{{ $order->uom_name}}</td>
               <td width="80px">{{ $order->qty }} </td>
               <td width="80px">{{ $order->cumulative_qty }} </td>
               <td width="80px"><input type="text" name="balance_qty[{{ $i }}]" id="balance_qty{{ $i }}" class="number integer"  value="{{ $order->balance_qty }}" readonly /> </td>
               <td width="80px">
               <input type="text" name="qty[{{ $i }}]" id="qty{{ $i }}" class="number integer" onchange="MsExpPiOrder.calculate({{ $i }},{{ $loop->count}})" value="{{ $order->balance_qty }}"/>
               </td>
               <td>
               <input type="text" name="rate[{{ $i  }}]" id="rate_{{ $i }}" class="number integer" value="{{ $order->rate }}" readonly />
               </td>
               <td>
               <input type="text" name="amount[{{ $i }}]" id="amount_{{ $i }}" class="number" value="{{ $order->tagable_amount }}" readonly/>
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