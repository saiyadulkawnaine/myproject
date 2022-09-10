<table border="1" class="table_form">
        <tr align="center">
               
               <td colspan="5">
               <input type="checkbox" name="is_copy" id="is_copy" checked/>Copy
               </td>
        </tr>
        <tr align="center">
               <td width="70px">Fabric Color</td>
               <td width="70px">Req. Qty</td>
               <td width="70px">Practical. Qty</td>
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
        @foreach($dataArr as $row)
        
                    <tr align="center">
                    
                    
                    <td width="70px">
                      <input type="hidden" name="smp_cost_fabric_prod_id[{{ $i }}]" id="smp_cost_fabric_prod_id{{ $i }}" value="{{ $row->smp_cost_fabric_prod_id }}"/>
                     
                      <input type="hidden" name="smp_cost_id[{{ $i }}]" id="smp_cost_id{{ $i }}" value="{{$row->smp_cost_id }}"/>
                    {{ $row->fabric_color }}
                    <input type="hidden" name="fabric_color_id[{{ $i }}]" id="fabric_color_id{{ $i }}" value="{{ $row->fabric_color_id }}" readonly/>
                    </td>
                    <td width="70px" align="right">
                    {{ $row->grey_fab }} 
                    </td>
                    <td width="70px">
                    <input type="text" name="bom_qty[{{ $i }}]" id="bom_qty{{ $i }}" class="number integer" value="{{$row->bom_qty}}" onchange="MsSmpCostDyeingCon.calculate({{ $i }},{{$loop->count}},'bom_qty')"/>
                    </td>
                    <td>
                    <input type="text" name="rate[{{ $i  }}]" id="rate_{{ $i }}" class="number integer" onchange="MsSmpCostDyeingCon.calculate({{ $i }},{{$loop->count}},'rate')" value="{{ $row->rate }}"/>
                    </td>
                    <td>
                    <input type="text" name="amount[{{ $i }}]" id="amount_{{ $i }}" class="number" value="{{ $row->amount }}" readonly/>
                    </td>
                    </tr>
                      <?php

					  $i++;
					  ?>
                      
        @endforeach
    </tbody>
    <tfoot>
    <tr align="center">
               <td width="70px"> </td>
               <td width="70px" align="right"></td>
               <td width="70px" align="right"></td>
               <td width="70px"></td>
               <td width="80px" align="right"></td>

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
