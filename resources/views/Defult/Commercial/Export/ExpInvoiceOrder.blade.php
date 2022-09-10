<?php
$i=1;
?>
@if($impdocaccepts->isNotEmpty())

<table border="1" class="table_form">
 <caption>New</caption>
 <tr align="center">
  <td width="100px">Order No</td>
  <td width="100px">Ship Date</td>
  <td width="100px">Attached Order Qty.</td>
  <td width="70px">Ex-Fact. Qty.</td>
  <td width="70px">Order Rate</td>
  <td width="70px">Current Inv. Qty.</td>
  <td width="70px">Rate</td>
  <td width="80px">Current Inv. Value</td>
  <td width="80px">Total Inv. Qty.</td>
  <td width="80px">PO Bal. Qty.</td>
  <td width="80px">Total Inv. Value</td>
  <td width="80px">Merchant</td>
  <td width="80px">Prod. Source</td>
  <td width="80px">Location</td>
  <td width="100px">Commodity</td>
 </tr>
 <tbody>


  @foreach($impdocaccepts as $impdocaccept)

  <tr align="center">
   <td width="100px">
    {{ $impdocaccept->sale_order_no }}
    <input type="hidden" name="exp_invoice_id[{{ $i }}]" id="exp_invoice_id{{ $i }}"
     value="{{ $impdocaccept->exp_invoice_id }}" />
    <input type="hidden" name="exp_pi_order_id[{{ $i }}]" id="exp_pi_order_id{{ $i }}"
     value="{{ $impdocaccept->exp_pi_order_id }}" />
   </td>
   <td width="100px">{{ $impdocaccept->ship_date }}</td>
   <td width="100px" align="right">{{ $impdocaccept->qty}}</td>
   <td width="100px" align="right">{{ $impdocaccept->qty}}</td>
   <td width="70px" align="right">
    {{ $impdocaccept->rate}}
   </td>
   <td>
    <input type="text" name="qty[{{ $i }}]" id="qty{{ $i }}" class="number integer"
     value=" {{ $impdocaccept->invoice_qty}}" onchange="MsExpInvoiceOrder.calculate({{ $i }})" />
   </td>

   <td>
    <input type="text" name="rate[{{ $i }}]" id="rate{{ $i }}" class="number integer" value=" {{ $impdocaccept->rate}}"
     onchange="MsExpInvoiceOrder.calculate({{ $i }})" />
   </td>

   <td>
    <input type="text" name="amount[{{ $i }}]" id="amount{{ $i }}" class="number integer"
     value=" {{ $impdocaccept->amount}}" readonly />
   </td>

   <td align="right">
    {{ $impdocaccept->cumulative_qty}}
   </td>
   <td align="right">
    {{ $impdocaccept->qty-$impdocaccept->cumulative_qty}}
   </td>
   <td>
    {{ $impdocaccept->cumulative_amount}}
   </td>
   <td>
    {{ $impdocaccept->marchent}}
   </td>
   <td>
    {!! Form::select("production_source_id[$i]", $productionsource,'',array('id'=>'production_source_id')) !!}
   </td>

   <td>
    {!! Form::select("location_id[$i]", $location,'',array('id'=>'location_id')) !!}
   </td>
   <td>
    <input type="text" name="commodity[{{ $i }}]" id="commodity{{ $i }}" value="" />
   </td>
  </tr>
  <?php
		      $i++;
		   ?>
  @endforeach
 </tbody>
 <tfoot>
  <td width="100px"></td>
  <td width="100px"></td>
  <td width="100px"></td>
  <td width="70px"></td>
  <td width="70px"></td>
  <td width="70px"></td>
  <td width="70px"></td>
  <td width="80px"></td>
  <td width="80px"></td>
  <td width="80px"></td>
  <td width="80px"></td>
  <td width="80px"></td>
  <td width="80px"></td>
  <td width="80px"></td>
  <td width="100px"></td>
 </tfoot>
</table>
@endif
@if($saved->isNotEmpty())

<table border="1" class="table_form">
 <caption>Saved</caption>

 <tr align="center">
  <td width="100px">Order No</td>
  <td width="100px">Ship Date</td>
  <td width="100px">Attached Order Qty.</td>
  <td width="70px">Ex-Fact. Qty.</td>
  <td width="70px">Order Rate</td>
  <td width="70px">Current Inv. Qty.</td>
  <td width="70px">Rate</td>
  <td width="80px">Current Inv. Value</td>
  <td width="80px">Total Inv. Qty.</td>
  <td width="80px">PO Bal. Qty.</td>
  <td width="80px">Total Inv. Value</td>
  <td width="80px">Order Value</td>
  <td width="80px">Merchant</td>
  <td width="80px">Prod. Source</td>
  <td width="80px">Location</td>
  <td width="100px">Commodity</td>
  <td width="80px"></td>
 </tr>
 <tbody>

  <?php
        $tot_invoice_qty=0;
        $tot_invoice_amount=0;
        $tOrderVal=0;
        ?>
  @foreach($saved as $row)
  <?php
                  $tot_invoice_qty+=$row->invoice_qty;
                  $tot_invoice_amount+=$row->invoice_amount;
                  $tOrderVal+=$row->order_value;
                  ?>

  <tr align="center">
   <td width="100px">
    {{ $row->sale_order_no }}
    <input type="hidden" name="exp_invoice_id[{{ $i }}]" id="exp_invoice_id{{ $i }}"
     value="{{ $row->exp_invoice_id }}" />
    <input type="hidden" name="exp_pi_order_id[{{ $i }}]" id="exp_pi_order_id{{ $i }}"
     value="{{ $row->exp_pi_order_id }}" />
   </td>
   <td width="100px">{{ $row->ship_date }}</td>
   <td width="100px" align="right">{{ $row->qty}}</td>
   <td width="100px" align="right">{{ $row->qty}}</td>
   <td width="70px" align="right">
    {{ $row->rate}}
   </td>
   <td>
    <input type="text" name="qty[{{ $i }}]" id="qty{{ $i }}" class="number integer" value=" {{ $row->invoice_qty }}"
     onchange="MsExpInvoiceOrder.calculate({{$i}})" />
   </td>

   <td>
    <input type="text" name="rate[{{ $i }}]" id="rate{{ $i }}" class="number integer" value="{{ $row->invoice_rate }}"
     onchange="MsExpInvoiceOrder.calculate({{$i}})" />
   </td>

   <td>
    <a href="javascript:void(0)"
     onClick="MsExpInvoiceOrderDtl.createOrderDtl({{$row->exp_invoice_order_id}},{{$row->invoice_qty}})">
     <input type="text" name="amount[{{ $i }}]" id="amount{{ $i }}" class="number integer"
      value=" {{ $row->invoice_amount}}" readonly />
    </a>
   </td>
   <td align="right">
    {{ $row->cumulative_qty-$row->invoice_qty}}
   </td>
   <td align="right">
    {{ $row->qty-($row->cumulative_qty-$row->invoice_qty)}}
   </td>
   <td>
    {{ $row->cumulative_amount-$row->invoice_amount}}
   </td>
   <td align="right">
    {{ $row->order_value}}
   </td>
   <td>
    {{ $row->marchent}}
   </td>
   <td>
    {!! Form::select("production_source_id[$i]",
    $productionsource,$row->production_source_id,array('id'=>'production_source_id')) !!}
   </td>
   <td>
    {!! Form::select("location_id[$i]", $location,$row->location_id,array('id'=>'location_id')) !!}
   </td>
   <td>
    <input type="text" name="commodity[{{ $i }}]" id="commodity{{ $i }}" value="{{ $row->commodity}}" />
   </td>
   <td>
    <a href="javascript:void(0)" onclick="MsExpInvoiceOrder.delete(event,{{ $row->exp_invoice_order_id }})">Remove</a>
   </td>
  </tr>
  <?php
      $i++;
      ?>
  @endforeach
 </tbody>
 <tfoot>
  <td width="100px"></td>
  <td width="100px"></td>
  <td width="100px"></td>
  <td width="70px"></td>
  <td width="70px"></td>
  <td width="70px" align="right">{{ $tot_invoice_qty }}</td>
  <td width="70px"></td>
  <td width="80px" align="right">{{ $tot_invoice_amount }}</td>
  <td width="80px"></td>
  <td width="80px"></td>
  <td width="80px"></td>
  <td width="80px" align="right">{{ $tOrderVal }}</td>
  <td width="80px"></td>
  <td width="80px"></td>
  <td width="80px"></td>
  <td width="100px"></td>
  <td width="80px"></td>

 </tfoot>
</table>
@endif
<script>
 $('.integer').keyup(function () {
    if (this.value != this.value.replace(/[^0-9\.]/g, '')) {
       this.value = this.value.replace(/[^0-9\.]/g, '');
    }
});
</script>