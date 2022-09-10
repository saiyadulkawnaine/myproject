<?php
$i=1;
?>
@if($dataArr->isNotEmpty())
<table border="1" class="table_form">
  <caption>New</caption>
        <tr align="center">
               
               <td colspan="6">
               <input type="checkbox" name="is_copy" id="is_copy" checked/>Copy
               </td>
        </tr>
        <tr align="center">
               <td width="100px">Sales Order</td>
               <td width="70px">Fabric Color</td>
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
                    $fabric_color=$row->fabric_color;
                    //$bom_qty=$row->bom_qty;
                    //$amount=$row->grey_fab*$row->smp_rate;
                    $reqTot+=$row->grey_fab;
                    //$bomTot+=$bom_qty;
                    //$amountTot+=$row->amount;
                    ?>
                    <tr align="center">
                    <td width="100px">
                     {{ $row->sale_order_no }}
                     <input type="hidden" name="budget_fabric_prod_id[{{ $i }}]" id="budget_fabric_prod_id{{ $i }}" value="{{ $row->budget_fabric_prod_id }}"/>
                     <input type="hidden" name="sales_order_id[{{ $i }}]" id="sales_order_id{{ $i }}" value="{{ $row->sale_order_id}}"/>
                      <input type="hidden" name="budget_id[{{ $i }}]" id="budget_id{{ $i }}" value="{{$row->budget_id }}"/>
                     
                    </td>
                    
                    <td width="70px">
                    {{ $fabric_color }}
                    <input type="hidden" name="fabric_color_id[{{ $i }}]" id="fabric_color_id{{ $i }}" value="{{ $row->fabric_color_id  }}" readonly/>
                    </td>
                    <td width="70px" align="right">
                    {{ $row->grey_fab }}
                    </td>
                    <td width="70px">
                    <input type="text" name="bom_qty[{{ $i }}]" id="bom_qty{{ $i }}" class="number integer" value="" onchange="MsBudgetFabricProdCon.calculate({{ $i }},{{$loop->count}},'bom_qty')" onclick="MsBudgetFabricProdCon.setGreyAsBom({{ $i }},{{$loop->count}},'bom_qty',{{$row->grey_fab}})" />
                    </td>
                    <td>
                    <input type="text" name="rate[{{ $i  }}]" id="rate_{{ $i }}" class="number integer" onchange="MsBudgetFabricProdCon.calculate({{ $i }},{{$loop->count}},'rate')" value="{{ $row->smp_rate}}"/>
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
<td width="100px">Sales Order</td>
<td width="70px">Fabric Color</td>
<td width="70px">Req. Qty</td>
<td width="70px">Bom. Qty</td>
<td width="70px">Rate</td>
<td width="80px">Amount</td>
<td width="80px">Action</td>
</tr>
<tbody>
<?php
//$i=1;
$reqTot=0;
$bomTot=0;
$amountTot=0;
?>
@foreach($saved as $row)
<?php
$fabric_color=$row->fabric_color;
// $bom_qty=$row->bom_qty;
$reqTot+=$row->grey_fab;
$bomTot+=$row->bom_qty;
$amountTot+=$row->amount;
$readonly='';
if($row->po_qty){
  $readonly='readonly';
}
else{
  $readonly='';
}
?>
<tr align="center">
<td width="100px">
{{ $row->sale_order_no }}
<input type="hidden" name="budget_fabric_prod_id[{{ $i }}]" id="budget_fabric_prod_id{{ $i }}" value="{{ $row->budget_fabric_prod_id }}"/>
<input type="hidden" name="sales_order_id[{{ $i }}]" id="sales_order_id{{ $i }}" value="{{ $row->sale_order_id}}"/>
<input type="hidden" name="budget_id[{{ $i }}]" id="budget_id{{ $i }}" value="{{$row->budget_id }}"/>
</td>
<td width="70px">
{{ $fabric_color }}
<input type="hidden" name="fabric_color_id[{{ $i }}]" id="fabric_color_id{{ $i }}" value="{{ $row->fabric_color_id  }}" readonly/>
</td>
<td width="70px" align="right">
{{ $row->grey_fab }}
</td>
<td width="70px">
<input type="text" name="bom_qty[{{ $i }}]" id="bom_qty{{ $i }}" class="number integer" value="{{ $row->bom_qty }}" onchange="MsBudgetFabricProdCon.calculate({{ $i }},{{$loop->count}},'bom_qty')" onclick="MsBudgetFabricProdCon.setGreyAsBom({{ $i }},{{$loop->count}},'bom_qty',{{$row->grey_fab}})" />
</td>
<td>
<input type="text" name="rate[{{ $i  }}]" id="rate_{{ $i }}" class="number integer" onchange="MsBudgetFabricProdCon.calculate({{ $i }},{{$loop->count}},'rate')" value="{{ $row->rate}}"/>
<input type="hidden" name="overhead_rate[{{ $i  }}]" id="overhead_rate{{ $i }}" class="number integer" value="{{ $row->overhead_rate}}"/>
</td>
<td>
<input type="text" name="amount[{{ $i }}]" id="amount_{{ $i }}" class="number" value="{{ $row->amount}}" readonly/>
</td>
<td>
<a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsBudgetFabricProdCon.delete(event,{{$row->budget_fabric_prod_con_id}})" >Remove</a></td>
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
<td width="70px" align="right">{{ number_format($reqTot,4,'.',',') }}</td>
<td width="70px" align="right">{{ number_format($bomTot ,4,'.',',')}}</td>
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
