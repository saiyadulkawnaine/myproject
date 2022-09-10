<?php
$i=1;
?>
@if($dataArr->isNotEmpty())
<table border="1" class="table_form">
  <caption>New</caption>
  <tr align="center">
  <td colspan="11">
  <input type="checkbox" name="is_copy" id="is_copy" checked/>Copy
  </td>
  </tr>
  <tr align="center">
  <td width="100px">Sales Order</td>
  <td width="70px">Gmt Color</td>
  <td width="70px">Grey Fab.</td>
  <td width="70px">Yarn Color</td>
  <td width="70px">Measurment</td>
  <td width="70px">Feeder</td>
  <td width="70px">Dye Wash</td>
  <td width="70px">Req. Qty</td>
  <td width="70px">Bom. Qty</td>
  <td width="70px">Rate</td>
  <td width="80px">Amount</td>
  </tr>
  <tbody>
  <?php
  $reqTot=0;
  $bomTot=0;
  $amountTot=0;
  ?>
  @foreach($dataArr as $row)
  <?php
  $gmt_color_name=$row->gmt_color_name;
  $yarn_color=$row->yarn_color;
  $reqTot+=$row->req_qty;
  ?>
  <tr align="center">
  <td width="100px">
  {{ $row->sale_order_no }}
  <input type="hidden" name="budget_yarn_dyeing_id[{{ $i }}]" id="budget_yarn_dyeing_id{{ $i }}" value="{{ $row->budget_yarn_dyeing_id }}"/>
  <input type="hidden" name="sales_order_id[{{ $i }}]" id="sales_order_id{{ $i }}" value="{{ $row->sale_order_id}}"/>
  <input type="hidden" name="budget_id[{{ $i }}]" id="budget_id{{ $i }}" value="{{$row->budget_id }}"/>
  <input type="hidden" name="style_fabrication_stripe_id[{{ $i }}]" id="style_fabrication_stripe_id{{ $i }}" value="{{$row->style_fabrication_stripe_id }}"/>
  </td>
  <td width="70px">
  {{ $gmt_color_name }}
  </td>
  <td width="70px">
  {{ $row->grey_fab }}
  </td>
  <td width="70px">
  {{ $yarn_color }}
  </td>
  <td width="70px" align="right">
  {{ $row->measurment }}
  </td>
  <td width="70px" align="right">
  {{ $row->feeder }}
  </td>
  <td width="70px" align="left">
  {{ $row->is_dye_wash_name }}
  </td>
  <td width="70px" align="right">
  {{ $row->req_qty }}
  </td>
  <td width="70px">
  <input type="text" name="bom_qty[{{ $i }}]" id="bom_qty{{ $i }}" class="number integer" value="" onchange="MsBudgetYarnDyeingCon.calculate({{ $i }},{{$loop->count}},'bom_qty')" onclick="MsBudgetYarnDyeingCon.setGreyAsBom({{ $i }},{{$loop->count}},'bom_qty',{{$row->req_qty}})" />
  </td>
  <td>
  <input type="text" name="rate[{{ $i  }}]" id="rate_{{ $i }}" class="number integer" onchange="MsBudgetYarnDyeingCon.calculate({{ $i }},{{$loop->count}},'rate')" value="{{ $row->smp_rate}}"/>
  <input type="hidden" name="overhead_rate[{{ $i  }}]" id="overhead_rate{{ $i }}" class="number integer" value="{{ $row->overhead_rate}}"/>
  </td>
  <td>
  <input type="text" name="amount[{{ $i }}]" id="amount_{{ $i }}" class="number" value="" readonly/>
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
  <td width="70px"> </td>
  <td width="70px"> </td>
  <td width="70px"> </td>
  <td width="70px"> </td>
  <td width="70px"> </td>
  <td width="70px"> </td>
  <td width="70px" align="right">{{ number_format($reqTot,4,'.',',') }}</td>
  <td width="70px" align="right"></td>
  <td width="70px"></td>
  <td width="80px" align="right"></td>
  </tr>
  </tfoot>
</table>
@endif
@if($saved->isNotEmpty())
<table border="1" class="table_form">
  <caption>Saved</caption>
  <tr align="center">
  <td colspan="11">
  <input type="checkbox" name="is_copy" id="is_copy" checked/>Copy
  </td>
  </tr>
  <tr align="center">
  <td width="100px">Sales Order</td>
  <td width="70px">Gmt Color</td>
  <td width="70px">Grey Fab.</td>
  <td width="70px">Yarn Color</td>
  <td width="70px">Measurment</td>
  <td width="70px">Feeder</td>
  <td width="70px">Dye Wash</td>
  <td width="70px">Req. Qty</td>
  <td width="70px">Bom. Qty</td>
  <td width="70px">Rate</td>
  <td width="80px">Amount</td>
  </tr>
  <tbody>
  <?php
  $reqTot=0;
  $bomTot=0;
  $amountTot=0;
  ?>
  @foreach($saved as $row)
  <?php
  $gmt_color_name=$row->gmt_color_name;
  $yarn_color=$row->yarn_color;
  $reqTot+=$row->req_qty;
  $bomTot+=$row->bom_qty;
  $amountTot+=$row->amount;
  ?>
  <tr align="center">
  <td width="100px">
  {{ $row->sale_order_no }}
  <input type="hidden" name="budget_yarn_dyeing_id[{{ $i }}]" id="budget_yarn_dyeing_id{{ $i }}" value="{{ $row->budget_yarn_dyeing_id }}"/>
  <input type="hidden" name="sales_order_id[{{ $i }}]" id="sales_order_id{{ $i }}" value="{{ $row->sale_order_id}}"/>
  <input type="hidden" name="budget_id[{{ $i }}]" id="budget_id{{ $i }}" value="{{$row->budget_id }}"/>
  <input type="hidden" name="style_fabrication_stripe_id[{{ $i }}]" id="style_fabrication_stripe_id{{ $i }}" value="{{$row->style_fabrication_stripe_id }}"/>
  </td>
  <td width="70px">
  {{ $gmt_color_name }}
  </td>
  <td width="70px">
  {{ $row->grey_fab }}
  </td>
  <td width="70px">
  {{ $yarn_color }}
  </td>
  <td width="70px" align="right">
  {{ $row->measurment }}
  </td>
  <td width="70px" align="right">
  {{ $row->feeder }}
  </td>
  <td width="70px" align="left">
  {{ $row->is_dye_wash_name }}
  </td>
  <td width="70px" align="right">
  {{ $row->req_qty }}
  </td>
  <td width="70px">
  <input type="text" name="bom_qty[{{ $i }}]" id="bom_qty{{ $i }}" class="number integer" value="{{$row->bom_qty}}" onchange="MsBudgetYarnDyeingCon.calculate({{ $i }},{{$loop->count}},'bom_qty')" onclick="MsBudgetYarnDyeingCon.setGreyAsBom({{ $i }},{{$loop->count}},'bom_qty',{{$row->req_qty}})" />
  </td>
  <td>
  <input type="text" name="rate[{{ $i  }}]" id="rate_{{ $i }}" class="number integer" onchange="MsBudgetYarnDyeingCon.calculate({{ $i }},{{$loop->count}},'rate')" value="{{ $row->rate}}"/>
  <input type="hidden" name="overhead_rate[{{ $i  }}]" id="overhead_rate{{ $i }}" class="number integer" value="{{ $row->overhead_rate}}"/>
  </td>
  <td>
  <input type="text" name="amount[{{ $i }}]" id="amount_{{ $i }}" class="number" value="{{$row->amount}}" readonly/>
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
  <td width="70px"> </td>
  <td width="70px"> </td>
  <td width="70px"> </td>
  <td width="70px"> </td>
  <td width="70px"> </td>
  <td width="70px"> </td>
  <td width="70px" align="right">{{ number_format($reqTot,4,'.',',') }}</td>
  <td width="70px" align="right">{{ number_format($bomTot,4,'.',',') }}</td>
  <td width="70px"></td>
  <td width="80px" align="right">{{ number_format($amountTot,4,'.',',') }}</td>
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
