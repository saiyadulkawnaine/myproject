<?php
  $i=1;
?>
@if($colorsizes->isNotEmpty())
<p>New Gmt Color & Size Wise Leftover</p>
<table border="1">
  <tr align="center">
    <th width="20px" class="text-center">SL</th>
    <th width="100px" class="text-center">Order No</th>
    <th width="200px" class="text-center">GMT Item</th>
    <th width="100px" class="text-center">GMT Color</th>
    <th width="80px" class="text-center">Size</th>
    <th width="80px" class="text-center">Bal Qty</th>
    <th width="80px" class="text-center">DO Qty</th>
    <th width="80px" class="text-center">Rate</th>
    <th width="80px" class="text-center">Amount</th>
  </tr>
  <tbody>
    @foreach($colorsizes as $colorsize)
    <tr align="center">
      <td width="20px" class="text-center">{{ $i }}</td>
      <td width="100px" class="text-center">{{ $colorsize->sale_order_no }}</td>

      <td width="200px">
        {{ $colorsize->item_description }}
        <input type="hidden" name="sales_order_gmt_color_size_id[{{ $i }}]" id="sales_order_gmt_color_size_id{{ $i }}"
          value="{{ $colorsize->sales_order_gmt_color_size_id }}" />
        <input type="hidden" name="jhute_sale_dlv_order_item_id[{{ $i }}]" id="jhute_sale_dlv_order_item_id{{ $i }}"
          value="{{ $colorsize->jhute_sale_dlv_order_item_id }}" />
      </td>
      <td width="100px">{{ $colorsize->color_name }}</td>
      <td width="80px">{{ $colorsize->size_name }}</td>
      <td width="80px" align="right">{{ $colorsize->balance_qty }}</td>
      <td width="80px">
        <input type="text" name="qty[{{ $i }}]" id="qty{{ $i }}" value="" class="number integer"
          onchange="MsGmtLeftoverSaleOrderQty.calculateAmount({{ $i }},{{ $loop->count}},'qty')" />
      </td>
      <td width="80px">
        <input type="text" name="rate[{{ $i }}]" id="rate{{ $i }}" value="" class="number integer"
          onchange="MsGmtLeftoverSaleOrderQty.calculateAmount({{ $i }},{{ $loop->count}},'rate')" />
      </td>
      <td width="40px"><input type="text" name="amount[{{ $i }}]" id="amount{{ $i }}" value="" class="number integer" />
      </td>
    </tr>
    <?php
  $i++;
  ?>
    @endforeach
  </tbody>
</table>
@endif
@if($saved->isNotEmpty())
<br />
<p>Saved Gmt Color & Size Wise Cutting</p>
<table border="1">
  <tr align="center">
    <th width="20px" class="text-center">SL</th>
    <th width="100px" class="text-center">Order No</th>
    <th width="200px" class="text-center">GMT Item</th>
    <th width="100px" class="text-center">GMT Color</th>
    <th width="80px" class="text-center">Size</th>
    <th width="80px" class="text-center">Bal Qty</th>
    <th width="80px" class="text-center">DO Qty</th>
    <th width="80px" class="text-center">Rate</th>
    <th width="80px" class="text-center">Amount</th>
    <th width="80px" class="text-center"></th>
  </tr>
  <tbody>
    <?php
    //$i=1;
    $totalQty=0;
    $totalAmount=0;
  ?>
    @foreach($saved as $colorsize)
    <tr align="center">
      <td width="20px" class="text-center">{{ $i}}</td>
      <td width="100px" class="text-center">{{ $colorsize->sale_order_no }}</td>
      <td width="200px">
        {{ $colorsize->item_description }}
        <input type="hidden" name="jhute_sale_dlv_order_item_id[{{ $i }}]" id="jhute_sale_dlv_order_item_id{{ $i }}"
          value="{{ $colorsize->jhute_sale_dlv_order_item_id }}" />
        <input type="hidden" name="sales_order_gmt_color_size_id[{{ $i }}]" id="sales_order_gmt_color_size_id{{ $i }}"
          value="{{ $colorsize->sales_order_gmt_color_size_id }}" />
      </td>
      <td width="100px">{{ $colorsize->color_name }}</td>
      <td width="80px">{{ $colorsize->size_name }}</td>
      <td width="80px" align="right">{{ $colorsize->balance_qty }}</td>
      <td width="80px"><input type="text" name="qty[{{ $i }}]" id="qty{{ $i }}" value="{{ $colorsize->qty }}"
          class="number integer" onchange="MsGmtLeftoverSaleOrderQty.calculate({{ $i }},{{ $loop->count}})" /></td>
      <td width="80px"><input type="text" name="rate[{{ $i }}]" id="rate{{ $i }}" value="{{ $colorsize->rate }}"
          class="number integer" onchange="MsGmtLeftoverSaleOrderQty.calculate({{ $i }},{{ $loop->count}})" /></td>
      <td width="40px"><input type="text" name="amount[{{ $i }}]" id="amount{{ $i }}" value="{{ $colorsize->amount }}"
          class="number integer" /></td>
      <td width="80px"><a href="javascript:void(0)"
          onclick="MsGmtLeftoverSaleOrderQty.delete(event,{{ $colorsize->jhute_sale_dlv_order_qty_id }})">Remove</a>
      </td>
    </tr>
    <?php
    $i++;
    $totalQty+=$colorsize->qty;
    $totalAmount+=$colorsize->amount;
  ?>
    @endforeach
  </tbody>
  <tr align="center">
    <td class="text-center" colspan="6"><strong>Total</strong></td>
    <td width="80px" align="right">{{ $totalQty }}</td>
    <td width="80px"></td>
    <td width="40px" align="right">{{ $totalAmount }}</td>
    <td width="80px"></td>
  </tr>
</table>
@endif
<script>
  $('.integer').keyup(function () {
    if (this.value != this.value.replace(/[^0-9\.]/g, '')) {
       this.value = this.value.replace(/[^0-9\.]/g, '');
    }
});
</script>