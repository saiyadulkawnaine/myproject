<?php
$i=1;
?>
@if($new->isNotEmpty())
<table border="1" class="table_form">
  <caption>New Color Size</caption>
  <tr align="center">
    <td width="100px">Sales Order</td>
    <td width="100px">Fabric Color</td>
    <td width="30px">Dia/ Width</td>
    <td width="30px">Measurment</td>
    <td width="80px">Color Range</td>
    <td width="80px">Aop Type</td>
    <td width="80px">Coverage%</td>
    <td width="80px">No Of Color</td>
    <td width="70px">Budget Qty</td>
    <td width="60px">Budget Rate</td>
    <td width="80px">Budget Amount</td>
    <td width="70px">Prev. WO. Qty</td>
    <td width="70px">Bal. WO. Qty</td>
    <td width="70px">WO. Qty</td>
    <td width="60px">WO. Rate</td>
    <td width="80px">WO. Amount</td>
  </tr>
  <tbody>
    <?php
      // $i=1;
      //$totCutQty=0;
      $totBomQty=0;
      $totAmountQty=0;
      $totcumuQty=0;
      $totbalQty=0;
      $totpoQty=0;
      //$totpoPcsQty=0;
      $totpoAmount=0;
	  ?>
@foreach($new as $colorsize)
  <?php
  //  $totCutQty+=$colorsize->plan_cut_qty;
    $totBomQty+=$colorsize->bom_qty;
    $totAmountQty+=$colorsize->bom_amount;
    $totcumuQty+=$colorsize->prev_po_qty;
    $totbalQty+=$colorsize->balance_qty;
    $totpoQty+=$colorsize->qty;
    //$totpoPcsQty+=$colorsize->pcs_qty;
    $totpoAmount+=$colorsize->amount;
  ?>
    <tr align="center">
      <td width="100px">
        {{ $colorsize->sale_order_no }}
        <input type="hidden" name="po_aop_service_item_id[{{ $i }}]"  value="{{ $colorsize->po_aop_service_item_id }}"/>
        <input type="hidden" name="budget_fabric_prod_con_id[{{ $i }}]"  value="{{ $colorsize->budget_fabric_prod_con_id }}"/>
        <input type="hidden" name="sales_order_id[{{ $i }}]"  value="{{ $colorsize->sales_order_id }}"/>
      </td>
      <td width="100px">
        {{ $colorsize->fabric_color_name }}
        <input type="hidden" name="fabric_color_id[{{ $i }}]"  value="{{ $colorsize->fabric_color }}" readonly />
      </td>
      {{-- <td width="70px" align="right">{{ $colorsize->plan_cut_qty}}</td> --}}
      <td width="30px">
        <input type="text" name="dia[{{ $i }}]"  value="{{ $colorsize->dia }}" readonly />
      </td>
      <td width="30px">
        <input type="text" name="measurment[{{ $i }}]"  value="{{ $colorsize->measurment }}" readonly />
      </td>
      <td width="80px">
        {!! Form::select("colorrange_id[$i]", $colorrange,$colorsize->colorrange_id,array('id'=>'colorrange_id')) !!}
      </td>
      <td width="80px">
        {!! Form::select("embelishment_type_id[$i]", $aoptype,$colorsize->c_embelishment_type_id,array('id'=>'embelishment_type_id')) !!} 
      </td>
      <td width="80px">
        <input type="text" name="coverage[{{ $i }}]" id="coverage{{ $i }}" class="number integer" value="{{ $colorsize->c_coverage}}" />
      </td>
      <td width="80px">
        <input type="text" name="impression[{{ $i }}]" id="impression{{ $i }}" class="number integer" value="{{ $colorsize->c_impression}}" />
      </td>
      <td width="70px">
        <input type="text" name="bom_qty[{{ $i }}]" id="bom_qty{{ $i }}" class="number integer" value="{{ $colorsize->bom_qty}}" readonly/>
      </td>
      <td>
        <input type="text" name="bom_rate[{{ $i  }}]" id="bom_rate{{ $i }}" class="number integer" value="{{ $colorsize->bom_rate}}" readonly/>
      </td>
      <td>
        <input type="text" name="bom_amount[{{ $i }}]" id="bom_amount{{ $i }}" class="number integer" value="{{ $colorsize->bom_amount}}" readonly/>
      </td>
      <td width="70px">
        <input type="text" name="cuqty[{{ $i }}]" id="cuqty{{ $i }}" class="number integer" value="{{ $colorsize->prev_po_qty}}" readonly/>
      </td>
      <td width="70px">
        <input type="text" name="balqty[{{ $i }}]" id="balqty{{ $i }}" class="number integer" value="{{ $colorsize->balance_qty}}" readonly/>
      </td>
      <td width="70px">
        <input type="text" name="qty[{{ $i }}]" id="qty{{ $i }}" class="number integer" value="{{ $colorsize->qty }}" onchange="MsPoAopServiceItemQty.calculateAmount({{ $i }},{{$loop->count}},'qty')"/>
      </td>
      {{-- <td width="70px">
      <input type="text" name="pcs_qty[{{ $i }}]" id="pcs_qty{{ $i }}" class="number integer" value="{{ $colorsize->pcs_qty }}" onchange="MsPoAopServiceItemQty.calculateAmount({{ $i }},{{$loop->count}},'pcs_qty')"/>
      </td> --}}
      <td>
        <input type="text" name="rate[{{ $i  }}]" id="rate_{{ $i }}" class="number integer"  value="{{ $colorsize->rate }}" onchange="MsPoAopServiceItemQty.calculateAmount({{ $i }},{{$loop->count}},'rate')"/>
      </td>
      <td>
        <input type="text" name="amount[{{ $i }}]" id="amount_{{ $i }}" class="number integer" value="{{ $colorsize->amount }}" readonly/>
      </td>
    </tr>
  <?php
    $i++;
  ?>
@endforeach
  </tbody>
  <tfoot>
    <tr align="center">
      <td width="100px"></td>
      <td width="100px"></td>
      <td width="30px"></td>
      <td width="30px"></td>
      <td width="80px"></td>
      <td width="80px"></td>
      <td width="80px"></td>
      <td width="80px"></td>
      <td width="70px" align="right">{{ number_format($totBomQty ,2,'.',',')}}</td>
      <td width="60px"></td>
      <td width="80px" align="right">{{ number_format($totAmountQty,2,'.',',') }}</td>
      <td width="70px" align="right">{{ number_format($totcumuQty ,2,'.',',')}}</td>
      <td width="70px" align="right">{{ number_format($totbalQty ,2,'.',',')}}</td>
      <td width="70px" align="right">{{ number_format($totpoQty ,2,'.',',')}}</td>
      <td width="60px"></td>
      <td width="80px" align="right">{{ number_format($totpoAmount,2,'.',',') }}</td>
    </tr>
  </tfoot>
</table>
<br/>
<br/>
<br/>
@endif

@if($colorsizes->isNotEmpty())
<table border="1" class="table_form">
  <caption>Saved Color Size</caption>
  <tr align="center">
    <td width="100px">Sales Order</td>
    <td width="100px">Fabric Color</td>
    <td width="30px">Dia/ Width</td>
    <td width="30px">Measurment</td>
    <td width="80px">Color Range</td>
    <td width="80px">Aop Type</td>
    <td width="80px">Coverage%</td>
    <td width="80px">No Of Color</td>
    <td width="70px">Budget Qty</td>
    <td width="60px">Budget Rate</td>
    <td width="80px">Budget Amount</td>
    <td width="70px">Prev. WO. Qty</td>
    <td width="70px">Bal. WO. Qty</td>
    <td width="70px">WO. Qty</td>
    <td width="60px">WO. Rate</td>
    <td width="80px">WO. Amount</td>
  </tr>
  <tbody>
  <?php
    $totBomQty=0;
    $totAmountQty=0;
    $totcumuQty=0;
    $totbalQty=0;
    $totpoQty=0;
    $totpoAmount=0;
  ?>
@foreach($colorsizes as $colorsize)
  <?php
    if($colorsize->qty){
      $totBomQty+=$colorsize->bom_qty;
      $totAmountQty+=$colorsize->bom_amount;
      $totcumuQty+=$colorsize->prev_po_qty;
      $totbalQty+=$colorsize->balance_qty;
      $totpoQty+=$colorsize->qty;
      $totpoAmount+=$colorsize->amount;
  ?>
    <tr align="center">
      <td width="100px">
        {{ $colorsize->sale_order_no }}
        <input type="hidden" name="po_aop_service_item_id[{{ $i }}]"  value="{{ $colorsize->po_aop_service_item_id }}"/>
        <input type="hidden" name="budget_fabric_prod_con_id[{{ $i }}]"  value="{{ $colorsize->budget_fabric_prod_con_id }}"/>
        <input type="hidden" name="po_aop_service_item_qty_id[{{ $i }}]" id="po_aop_service_item_qty_id{{ $i }}" class="number integer" value="{{ $colorsize->po_aop_service_item_qty_id }}" readonly/>
        <input type="hidden" name="sales_order_id[{{ $i }}]"  value="{{ $colorsize->sales_order_id }}"/>
      </td>
      <td width="100px">
        {{ $colorsize->fabric_color_name }}
        <input type="hidden" name="fabric_color_id[{{ $i }}]"  value="{{ $colorsize->fabric_color }}" readonly />
      </td>
      <td width="30px">
        <input type="text" name="dia[{{ $i }}]"  value="{{ $colorsize->dia }}" readonly />
      </td>
      <td width="30px">
        <input type="text" name="measurment[{{ $i }}]"  value="{{ $colorsize->measurment }}" readonly />
      </td>
      <td width="80px">
        {!! Form::select("colorrange_id[$i]", $colorrange,$colorsize->colorrange_id,array('id'=>'colorrange_id')) !!} 
      </td>
      <td width="80px">
        {!! Form::select("embelishment_type_id[$i]", $aoptype,$colorsize->embelishment_type_id,array('id'=>'embelishment_type_id')) !!} 
      </td>
      <td width="80px">
        <input type="text" name="coverage[{{ $i }}]" id="coverage{{ $i }}" class="number integer" value="{{ $colorsize->coverage}}" />
      </td>
      <td width="80px">
        <input type="text" name="impression[{{ $i }}]" id="impression{{ $i }}" class="number integer" value="{{ $colorsize->impression}}" />
      </td>
      <td width="70px">
        <input type="text" name="bom_qty[{{ $i }}]" id="bom_qty{{ $i }}" class="number integer" value="{{ $colorsize->bom_qty}}" readonly/>
      </td>
      <td>
      <input type="text" name="bom_rate[{{ $i  }}]" id="bom_rate{{ $i }}" class="number integer" value="{{ $colorsize->bom_rate}}" readonly/>
      </td>
      <td>
        <input type="text" name="bom_amount[{{ $i }}]" id="bom_amount{{ $i }}" class="number integer" value="{{ $colorsize->bom_amount}}" readonly/>
      </td>
      <td width="70px">
        <input type="text" name="cuqty[{{ $i }}]" id="cuqty{{ $i }}" class="number integer" value="{{ $colorsize->prev_po_qty}}" readonly/>
      </td>
      <td width="70px">
        <input type="text" name="balqty[{{ $i }}]" id="balqty{{ $i }}" class="number integer" value="{{ $colorsize->balance_qty}}" readonly/>
      </td>
      <td width="70px">
        <input type="text" name="qty[{{ $i }}]" id="qty{{ $i }}" class="number integer" value="{{ $colorsize->qty }}" onchange="MsPoAopServiceItemQty.calculateAmount({{ $i }},{{$loop->count}},'qty')"/>
      </td>
      <td>
        <input type="text" name="rate[{{ $i  }}]" id="rate_{{ $i }}" class="number integer"  value="{{ $colorsize->rate }}" onchange="MsPoAopServiceItemQty.calculateAmount({{ $i }},{{$loop->count}},'rate')"/>
      </td>
      <td>
        <input type="text" name="amount[{{ $i }}]" id="amount_{{ $i }}" class="number integer" value="{{ $colorsize->amount }}" readonly/>
      </td>
    </tr>
  <?php
    $i++;
    }
  ?>
@endforeach
  </tbody>
  <tfoot>
    <tr align="center">
      <td width="100px"></td>
      <td width="100px"></td>
      <td width="30px"></td>
      <td width="30px"></td>
      <td width="80px"></td>
      <td width="80px"></td>
      <td width="80px"></td>
      <td width="80px"></td>
      <td width="70px" align="right">{{ number_format($totBomQty ,2,'.',',')}}</td>
      <td width="60px"></td>
      <td width="80px" align="right">{{ number_format($totAmountQty,2,'.',',') }}</td>
      <td width="70px" align="right">{{ number_format($totcumuQty ,2,'.',',')}}</td>
      <td width="70px" align="right">{{ number_format($totbalQty ,2,'.',',')}}</td>
      <td width="70px" align="right">{{ number_format($totpoQty ,2,'.',',')}}</td>
      <td width="60px"></td>
      <td width="80px" align="right">{{ number_format($totpoAmount,2,'.',',') }}</td>
    </tr>
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
