<?php
$i=1;
?>
@if($new->isNotEmpty())
<table border="1" class="table_form">
<caption>New Color Size</caption>
        <tr align="center">
               <td colspan="2">Sales Order & Country</td>
               <td colspan="2">Color</td>
               <td colspan="2">Size</td>
               <td colspan="7">
                Budget
               </td>
               <td colspan="5">
                Purchase
               </td>
        </tr>
        <tr align="center">
               <td width="100px">Sales Order</td>
               <td width="100px">Country</td>
               
               <td width="150px">Name</td>
               <td width="30px">Seqn</td>
               
               <td width="100px">Name</td>
               <td width="30px">Seqn</td>
               
               <td width="155px">Fabric Color</td>
               <td width="70px">Measurment</td>
               <td width="30px">Dia/ Width</td>
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
	 // $i=1;
	  $totCutQty=0;
	  $totFinQty=0;
	  $totGreyQty=0;
	  $totAmountQty=0;
	  $totcumuQty=0;
	  $totbalQty=0;
	  $totpoQty=0;
	  $totpoAmount=0;
	  ?>
        @foreach($new as $colorsize)
         <?php
		$totCutQty+=$colorsize->plan_cut_qty;
		$totGreyQty+=$colorsize->grey_fab;
		$totAmountQty+=$colorsize->bom_amount;
		$totcumuQty+=$colorsize->prev_po_qty;
		$totbalQty+=$colorsize->balance_qty;
		$totpoQty+=$colorsize->qty;
		$totpoAmount+=$colorsize->amount;
		 
	  ?>
                    <tr align="center">
                    <td width="100px">{{ $colorsize->sale_order_no }}</td>
                    <td width="100px">{{ $colorsize->country_name }}</td>
                    
                    <td width="100px">
                    {{ $colorsize->color_name }}
                    <input type="hidden" name="pur_fabric_id[{{ $i }}]"  value="{{ $colorsize->pur_fabric_id }}"/>
                    <input type="hidden" name="budget_fabric_con_id[{{ $i }}]"  value="{{ $colorsize->budget_fabric_con_id }}"/>
                    </td>
                    <td width="30px">{{ $colorsize->color_sort_id}}</td>
                    
                    <td width="100px">
                    {{ $colorsize->size_name }}
                    </td>
                    <td width="30px">{{ $colorsize->size_sort_id}}</td>
                    
                    <td width="150px">{{ $colorsize->fabric_color_name }}</td>
                    <td width="70px">{{ $colorsize->measurment}}</td>
                    <td width="30px">{{ $colorsize->dia}}</td>
                    <td width="70px" align="right">{{ $colorsize->plan_cut_qty}}</td>
                    <td width="70px">
                    <input type="text" name="grey_fab[{{ $i }}]" id="grey_fab{{ $i }}" class="number integer" value="{{ $colorsize->grey_fab}}" readonly/>
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
                    <input type="text" name="qty[{{ $i }}]" id="qty{{ $i }}" class="number integer" value="{{ $colorsize->qty }}" onchange="MsPurFabricQty.calculateAmount({{ $i }},{{$loop->count}},'qty')"/>
                    </td>
                    <td>
                    <input type="text" name="rate[{{ $i  }}]" id="rate_{{ $i }}" class="number integer"  value="{{ $colorsize->rate }}" onchange="MsPurFabricQty.calculateAmount({{ $i }},{{$loop->count}},'rate')"/>
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
               
               <td width="150px"></td>
               <td width="30px"></td>
               
               <td width="100px"></td>
               <td width="30px"></td>
               
               <td width="155px"> </td>
               <td width="70px"></td>
               <td width="30px"></td>
               <td width="70px" align="right"> {{ number_format($totCutQty,0,'.',',') }} </td>
               <td width="70px" align="right">{{ number_format($totGreyQty ,4,'.',',')}}</td>
               <td width="60px"></td>
               <td width="80px" align="right">{{ number_format($totAmountQty,4,'.',',') }}</td>
               
               <td width="70px" align="right">{{ number_format($totcumuQty ,4,'.',',')}}</td>
               <td width="70px" align="right">{{ number_format($totbalQty ,4,'.',',')}}</td>
               <td width="70px" align="right">{{ number_format($totpoQty ,4,'.',',')}}</td>
               <td width="60px"></td>
               <td width="80px" align="right">{{ number_format($totpoAmount,4,'.',',') }}</td>

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
               
               <td colspan="2">Sales Order & Country</td>
               <td colspan="2">Color</td>
               <td colspan="2">Size</td>
               <td colspan="7">
                Budget
               </td>
               <td colspan="6">
                Purchase
               </td>
        </tr>
        <tr align="center">
               
               <td width="100px">Sales Order</td>
               <td width="100px">Country</td>
               
               <td width="150px">Name</td>
               <td width="30px">Seqn</td>
               
               <td width="100px">Name</td>
               <td width="30px">Seqn</td>
               
               <td width="155px">Fabric Color</td>
               <td width="70px">Measurment</td>
               <td width="30px">Dia/ Width</td>
               <td width="70px">Cut Qty (Psc)</td>
               <td width="70px">BOM Qty</td>
               <td width="60px">BOM Rate</td>
               <td width="80px">BOM Amount</td>
               
               <td width="70px">Prev. PO. Qty</td>
               <td width="70px">Bal. PO. Qty</td>
               <td width="70px">PO. Qty</td>
               <td width="60px">PO. Rate</td>
               <td width="80px">PO. Amount</td>
               <td width="40px">ID</td>

        </tr>
    <tbody>
      <?php
	  //$i=1;
	  $totCutQty=0;
	  $totFinQty=0;
	  $totGreyQty=0;
	  $totAmountQty=0;
	  $totcumuQty=0;
	  $totbalQty=0;
	  $totpoQty=0;
	  $totpoAmount=0;
	  ?>
        @foreach($colorsizes as $colorsize)
         <?php
		 if($colorsize->qty){
		$totCutQty+=$colorsize->plan_cut_qty;
		$totGreyQty+=$colorsize->grey_fab;
		$totAmountQty+=$colorsize->bom_amount;
		$totcumuQty+=$colorsize->prev_po_qty;
		$totbalQty+=$colorsize->balance_qty;
		$totpoQty+=$colorsize->qty;
		$totpoAmount+=$colorsize->amount;
		 
	  ?>
                    <tr align="center">
                    <td width="100px">{{ $colorsize->sale_order_no }}</td>
                    <td width="100px">{{ $colorsize->country_name }}</td>
                    
                    <td width="100px">
                    {{ $colorsize->color_name }}
                    <input type="hidden" name="pur_fabric_id[{{ $i }}]"  value="{{ $colorsize->pur_fabric_id }}"/>
                    <input type="hidden" name="budget_fabric_con_id[{{ $i }}]"  value="{{ $colorsize->budget_fabric_con_id }}"/>
                    </td>
                    <td width="30px">{{ $colorsize->color_sort_id}}</td>
                    
                    <td width="100px">
                    {{ $colorsize->size_name }}
                    </td>
                    <td width="30px">{{ $colorsize->size_sort_id}}</td>
                    
                    <td width="150px">{{ $colorsize->fabric_color_name }}</td>
                    <td width="70px">{{ $colorsize->measurment}}</td>
                    <td width="30px">{{ $colorsize->dia}}</td>
                    <td width="70px" align="right">{{ $colorsize->plan_cut_qty}}</td>
                    <td width="70px">
                    <input type="text" name="grey_fab[{{ $i }}]" id="grey_fab{{ $i }}" class="number integer" value="{{ $colorsize->grey_fab}}" readonly/>
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
                    <input type="text" name="qty[{{ $i }}]" id="qty{{ $i }}" class="number integer" value="{{ $colorsize->qty }}" onchange="MsPurFabricQty.calculateAmount({{ $i }},{{$loop->count}},'qty')"/>
                    </td>
                    <td>
                    <input type="text" name="rate[{{ $i  }}]" id="rate_{{ $i }}" class="number integer"  value="{{ $colorsize->rate }}" onchange="MsPurFabricQty.calculateAmount({{ $i }},{{$loop->count}},'rate')"/>
                    </td>
                    <td>
                    <input type="text" name="amount[{{ $i }}]" id="amount_{{ $i }}" class="number integer" value="{{ $colorsize->amount }}" readonly/>
                    </td>
                    <td>
                    <input type="text" name="amount[{{ $i }}]" id="amount_{{ $i }}" class="number integer" value="{{ $colorsize->pur_fabric_qty_id }}" readonly/>
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
               
               <td width="150px"></td>
               <td width="30px"></td>
               
               <td width="100px"></td>
               <td width="30px"></td>
               
               <td width="155px"> </td>
               <td width="70px"></td>
               <td width="30px"></td>
               <td width="70px" align="right"> {{ number_format($totCutQty,0,'.',',') }} </td>
               <td width="70px" align="right">{{ number_format($totGreyQty ,4,'.',',')}}</td>
               <td width="60px"></td>
               <td width="80px" align="right">{{ number_format($totAmountQty,4,'.',',') }}</td>
               
               <td width="70px" align="right">{{ number_format($totcumuQty ,4,'.',',')}}</td>
               <td width="70px" align="right">{{ number_format($totbalQty ,4,'.',',')}}</td>
               <td width="70px" align="right">{{ number_format($totpoQty ,4,'.',',')}}</td>
               <td width="60px"></td>
               <td width="80px" align="right">{{ number_format($totpoAmount,4,'.',',') }}</td>
               <td width="40px" align="right"></td>

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
