<?php
  $i=1;
?>
@if($colorsizes->isNotEmpty())
<p>Cut Panel Color & Size Wise Cutting</p>
<table border="1">
 <tr align="center">
  <th width="100px" class="text-center">Order No</th>
  <th width="200px" class="text-center">GMT Item</th>
  <th width="100px" class="text-center">GMT Color</th>
  <th width="80px" class="text-center">Body Part</th>
  <th width="100px" class="text-center">Order Qty</th>
  <th width="80px" class="text-center">Prev.<br />Rcv Qty</th>
  <th width="80px" class="text-center">Bal Qty</th>
  <th width="80px" class="text-center">Current<br />Rcv Qty</th>
 </tr>
 <tbody>
  @foreach ($colorsizes as $colorsize)
  <tr align="center">
   <td width="100px" class="text-center">{{ $colorsize->sales_order_no}}</td>
   <td width="200px" class="text-center">
    {{ $colorsize->item_desc}}
    <input type="hidden" name="so_emb_cutpanel_rcv_order_id[{{ $i }}]" id="so_emb_cutpanel_rcv_order_id[{{ $i }}]"
     value="{{ $colorsize->so_emb_cutpanel_rcv_order_id }}">
    <input type="hidden" name="so_emb_ref_id[{{ $i }}]" id="so_emb_ref_id[{{ $i }}]"
     value="{{ $colorsize->so_emb_ref_id }}">
   </td>
   <td width="100px" class="text-center">{{ $colorsize->gmt_color}}</td>
   <td width="80px" class="text-center">{{ $colorsize->body_part}}</td>
   <td width="100px" class="text-center">{{ $colorsize->order_qty}}</td>
   <td width="80px" class="text-center">{{ $colorsize->cumulative_qty}}</td>
   <td width="80px" class="text-center">{{ $colorsize->balance_qty}}</td>
   <td width="80px" class="text-center">
    <input type="text" name="qty[{{ $i }}]" id="qty[{{ $i }}]" value="{{ $colorsize->qty}}" class="number integer">
   </td>
  </tr>

  <?php 
  $i++;
  ?>
  @endforeach
 </tbody>
</table>
@endif

<script>
 $('.integer').keyup(function () {
    if (this.value != this.value.replace(/[^0-9\.]/g, '')) {
       this.value = this.value.replace(/[^0-9\.]/g, '');
    }
});
</script>