<table border="1" class="table_form">
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
	  $i=1;
	  $reqTot=0;
	  $bomTot=0;
	  $amountTot=0;
	  ?>
        @foreach($dataArr as $orderId => $orderValue)
        @foreach($orderValue as $fabcolorId => $value)
         <?php
	  $fabric_color=$color_arr[$fabcolorId];
	  $bom_qty=$value['bom_qty'];
	  if(!$value['budget_fabric_prod_con_id']){
		  $bom_qty=$value['grey_fab'];
	  }
	  
	  $reqTot+=$value['grey_fab'];
	  $bomTot+=$bom_qty;
	  $amountTot+=$value['amount'];
	 
	  ?>
                    <tr align="center">
                    <td width="100px">
                     {{ $value['sale_order_no'] }}
                     <input type="hidden" name="budget_fabric_prod_id[{{ $i }}]" id="budget_fabric_prod_id{{ $i }}" value="{{ $value['budget_fabric_prod_id'] }}"/>
                     <input type="hidden" name="sales_order_id[{{ $i }}]" id="sales_order_id{{ $i }}" value="{{ $orderId }}"/>
                      <input type="hidden" name="budget_id[{{ $i }}]" id="budget_id{{ $i }}" value="{{$value['budget_id'] }}"/>
                     
                    </td>
                    
                    <td width="70px">
                    {{ $fabric_color }}
                    <input type="hidden" name="fabric_color_id[{{ $i }}]" id="fabric_color_id{{ $i }}" value="{{ $fabcolorId }}" readonly/>
                    </td>
                    <td width="70px" align="right">
                    {{ $value['grey_fab'] }}
                    </td>
                    <td width="70px">
                    <input type="text" name="bom_qty[{{ $i }}]" id="bom_qty{{ $i }}" class="number integer" value="{{ $bom_qty }}" onchange="MsBudgetFabricProdCon.calculate({{ $i }},{{$loop->count}},'bom_qty')"/>
                    </td>
                    <td>
                    <input type="text" name="rate[{{ $i  }}]" id="rate_{{ $i }}" class="number integer" onchange="MsBudgetFabricProdCon.calculate({{ $i }},{{$loop->count}},'rate')" value="{{ $value['rate']}}"/>
                    </td>
                    <td>
                    <input type="text" name="amount[{{ $i }}]" id="amount_{{ $i }}" class="number" value="{{ $value['amount']}}" readonly/>
                    </td>
                    </tr>
                      <?php

					  $i++;
					  ?>
                       @endforeach
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
<script>
$('.integer').keyup(function () {
    if (this.value != this.value.replace(/[^0-9\.]/g, '')) {
       this.value = this.value.replace(/[^0-9\.]/g, '');
    }
});
</script>
