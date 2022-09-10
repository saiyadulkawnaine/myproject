<?php
$i=1;
?>
@if($new->isNotEmpty())
<table border="1" class="table_form">
<caption>New Color Size</caption>
        
        <tr align="center">
               <td width="350px">Yarn</td>
               <td width="100px">Sales Order</td>
               <td width="70px">Cut Qty (Psc)</td>
               <td width="70px">BOM Qty</td>
               <td width="60px">BOM Rate</td>
               <td width="80px">BOM Amount</td>
               
               <td width="70px">Prev. PO. Qty</td>
               <td width="70px">Bal. PO. Qty</td>
               <td width="70px">PO. Qty</td>
               <td width="60px">PO. Rate</td>
               <td width="80px">PO. Amount</td>
        </tr>
    <tbody>
        <?php
        ?>
        @foreach($new as $colorsize)
        <?php


        ?>
                    <tr align="left">
                    <td width="350px">
                      {{ $colorsize->yarn_des }}
                    </td>
                    <td width="100px">
                      {{ $colorsize->sale_order_no }}
                      <input type="hidden" name="pur_yarn_id[{{ $i }}]" id="pur_yarn_id{{ $i }}" class="number integer" value="{{ $colorsize->pur_yarn_id}}" readonly/>
                      <input type="hidden" name="budget_yarn_id[{{ $i }}]" id="budget_yarn_id{{ $i }}" class="number integer" value="{{ $colorsize->budget_yarn_id}}" readonly/>
                      <input type="hidden" name="sale_order_id[{{ $i }}]" id="sale_order_id{{ $i }}" class="number integer" value="{{ $colorsize->sale_order_id}}" readonly/>
                    </td>
                    
                    <td width="70px" align="right">{{ $colorsize->plan_cut_qty}}</td>
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
                    <input type="text" name="balty[{{ $i }}]" id="balty{{ $i }}" class="number integer" value="{{ $colorsize->balance_qty}}" readonly/>
                    </td>
                    <td width="70px">
                    <input type="text" name="qty[{{ $i }}]" id="qty{{ $i }}" class="number integer" value="" onchange="MsPurYarnQty.calculateAmount({{ $i }},{{$loop->count}},'qty')"/>
                    </td>
                    <td>
                    <input type="text" name="rate[{{ $i  }}]" id="rate_{{ $i }}" class="number integer"  value="{{ $colorsize->pur_yarn_rate }}" onchange="MsPurYarnQty.calculateAmount({{ $i }},{{$loop->count}},'rate')" readonly />
                    </td>
                    <td>
                    <input type="text" name="amount[{{ $i }}]" id="amount_{{ $i }}" class="number integer" value="" readonly/>
                    </td>
                    </tr>
		  <?php
		  $i++;
		  
          ?>
       
        @endforeach
    </tbody>
<tfoot>
<tr align="center">
               

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
          <td width="350px">Yarn</td>
               <td width="100px">Sales Order</td>
               <td width="70px">Cut Qty (Psc)</td>
               <td width="70px">BOM Qty</td>
               <td width="60px">BOM Rate</td>
               <td width="80px">BOM Amount</td>
               
               <td width="70px">Prev. PO. Qty</td>
               <td width="70px">Bal. PO. Qty</td>
               <td width="70px">PO. Qty</td>
               <td width="60px">PO. Rate</td>
               <td width="80px">PO. Amount</td>

        </tr>
    <tbody>
      <?php
	  
	  ?>
        @foreach($colorsizes as $colorsize)
         <?php
		 if($colorsize->qty){
		
		 
	  ?>
                    <tr align="center">
                      <td width="350px">
                      {{ $colorsize->yarn_des }}
                    </td>
                    <td width="100px">
                      {{ $colorsize->sale_order_no }}
                      <input type="hidden" name="pur_yarn_id[{{ $i }}]" id="pur_yarn_id{{ $i }}" class="number integer" value="{{ $colorsize->pur_yarn_id}}" readonly/>
                      <input type="hidden" name="budget_yarn_id[{{ $i }}]" id="budget_yarn_id{{ $i }}" class="number integer" value="{{ $colorsize->budget_yarn_id}}" readonly/>
                      <input type="hidden" name="sale_order_id[{{ $i }}]" id="sale_order_id{{ $i }}" class="number integer" value="{{ $colorsize->sale_order_id}}" readonly/>
                    </td>
                    
                    <td width="70px" align="right">{{ $colorsize->plan_cut_qty}}</td>
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
                    <input type="text" name="balty[{{ $i }}]" id="balty{{ $i }}" class="number integer" value="{{ $colorsize->balance_qty}}" readonly/>
                    </td>
                    <td width="70px">
                    <input type="text" name="qty[{{ $i }}]" id="qty{{ $i }}" class="number integer" value="{{ $colorsize->qty }}" onchange="MsPurYarnQty.calculateAmount({{ $i }},{{$loop->count}},'qty')"/>
                    </td>
                    <td>
                    <input type="text" name="rate[{{ $i  }}]" id="rate_{{ $i }}" class="number integer"  value="{{ $colorsize->rate }}" onchange="MsPurYarnQty.calculateAmount({{ $i }},{{$loop->count}},'rate')" readonly />
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