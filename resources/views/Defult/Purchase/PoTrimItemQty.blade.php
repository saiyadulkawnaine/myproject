<?php
$i=1;
?>
@if($new->isNotEmpty())
<table border="1" style="border-style:dotted">
<caption>New Color Size</caption>
        <tr align="center">
               <td colspan="2">Sales Order & Country</td>
               <td>Color</td>
               <td>Size</td>
               <td colspan="6">
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
               <td width="100px">Name</td>
               <td width="155px">Trim Color</td>
               <td width="70px">Measurment</td>
               <td width="70px">Cut Qty (Psc)</td>
               <td width="70px">BOM Qty</td>
               <td width="60px">BOM Rate</td>
               <td width="80px">BOM Amount</td>
               
               <td width="70px">Prev. PO. Qty</td>
               <td width="70px">Bal. PO. Qty</td>
               <td width="70px">PO. Qty</td>
               <td width="60px">PO. Rate</td>
               <td width="80px">PO. Amount</td>
               <td width="100px">Description</td>

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
                    <input type="hidden" name="po_trim_item_id[{{ $i }}]"  value="{{ $colorsize->po_trim_item_id }}"/>
                    <input type="hidden" name="budget_trim_con_id[{{ $i }}]"  value="{{ $colorsize->budget_trim_con_id }}"/>
                    </td>
                    
                    <td width="100px">
                    {{ $colorsize->size_name }}
                    </td>
                    
                    <td width="150px">{{ $colorsize->trim_color_name }}</td>
                    <td width="70px">{{ $colorsize->measurment}}</td>
                    <td width="70px" align="right">{{ $colorsize->plan_cut_qty}}</td>
                    <td width="70px">
                    <input type="text" name="bom_trim[{{ $i }}]" id="bom_trim{{ $i }}" class="number integer" style="background-color:#FFFFFF;border:none" value="{{ $colorsize->bom_trim}}" readonly/>
                    </td>
                    <td>
                    <input type="text" name="bom_rate[{{ $i  }}]" id="bom_rate{{ $i }}" class="number integer" style="background-color:#FFFFFF;border:none" value="{{ $colorsize->bom_rate}}" readonly/>
                    </td>
                    <td>
                    <input type="text" name="bom_amount[{{ $i }}]" id="bom_amount{{ $i }}" class="number integer" style="background-color:#FFFFFF;border:none" value="{{ $colorsize->bom_amount}}" readonly/>
                    </td>
                    
                    <td width="70px">
                    <input type="text" name="cuqty[{{ $i }}]" id="cuqty{{ $i }}" class="number integer" style="background-color:#FFFFFF;border:none" value="{{ $colorsize->prev_po_qty}}" readonly/>
                    </td>
                    <td width="70px">
                    <input type="text" name="balty[{{ $i }}]" id="balty{{ $i }}" class="number integer" style="background-color:#FFFFFF;border:none" value="{{ $colorsize->balance_qty}}" readonly/>
                    </td>
                    <td width="70px">
                    <input type="text" name="qty[{{ $i }}]" id="qty{{ $i }}" class="number integer" style="background-color:#FFFFFF;border:none" value="{{ $colorsize->balance_qty }}" onchange="MsPoTrimItemQty.calculateAmount({{ $i }},{{$loop->count}},'qty')"/>
                    </td>
                    <td>
                    <input type="text" name="rate[{{ $i  }}]" id="rate_{{ $i }}" class="number integer" style="background-color:#FFFFFF;border:none"  value="{{ $colorsize->bom_rate }}" onchange="MsPoTrimItemQty.calculateAmount({{ $i }},{{$loop->count}},'rate')"/>
                    </td>
                    <td>
                    <input type="text" name="amount[{{ $i }}]" id="amount_{{ $i }}" class="number integer" style="background-color:#FFFFFF;border:none" value="{{ $colorsize->balance_amount }}" readonly/>
                    </td>
                    <td>
                    <input type="text" name="description[{{ $i }}]" id="description{{ $i }}" style="background-color:#FFFFFF;border:none" value="{{ $colorsize->description }}" onchange="MsPoTrimItemQty.copyDescription({{ $i }},{{$loop->count}})" />
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
               
               <td width="100px"></td>
               
               <td width="155px"> </td>
               <td width="70px"></td>
               <td width="70px" align="right"> {{ number_format($totCutQty,0,'.',',') }} </td>
               <td width="70px" align="right">{{ number_format($totGreyQty ,4,'.',',')}}</td>
               <td width="60px"></td>
               <td width="80px" align="right">{{ number_format($totAmountQty,4,'.',',') }}</td>
               
               <td width="70px" align="right">{{ number_format($totcumuQty ,4,'.',',')}}</td>
               <td width="70px" align="right">{{ number_format($totbalQty ,4,'.',',')}}</td>
               <td width="70px" align="right">{{ number_format($totpoQty ,4,'.',',')}}</td>
               <td width="60px"></td>
               <td width="80px" align="right">{{ number_format($totpoAmount,4,'.',',') }}</td>
               <td width="100px" align="right"></td>

        </tr>
</tfoot>
</table>
<br/>
<br/>
<br/>
@endif

@if($colorsizes->isNotEmpty())
<table border="1">
<caption>Saved Color Size</caption>
        <tr align="center">
               
               <td colspan="2">Sales Order & Country</td>
               <td>Color</td>
               <td>Size</td>
               <td colspan="6">
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
               
               <td width="100px">Name</td>
               
               <td width="155px">Trim Color</td>
               <td width="70px">Measurment</td>
               <td width="70px">Cut Qty (Psc)</td>
               <td width="70px">BOM Qty</td>
               <td width="60px">BOM Rate</td>
               <td width="80px">BOM Amount</td>
               
               <td width="70px">Prev. PO. Qty</td>
               <td width="70px">Bal. PO. Qty</td>
               <td width="70px">PO. Qty</td>
               <td width="60px">PO. Rate</td>
               <td width="80px">PO. Amount</td>
               <td width="100px">Description</td>

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
                    <input type="hidden" name="po_trim_item_id[{{ $i }}]"  value="{{ $colorsize->po_trim_item_id }}"/>
                    <input type="hidden" name="budget_trim_con_id[{{ $i }}]"  value="{{ $colorsize->budget_trim_con_id }}"/>
                    </td>
                    
                    <td width="100px">
                    {{ $colorsize->size_name }}
                    </td>
                    <td width="150px">{{ $colorsize->trim_color_name }}</td>
                    <td width="70px">{{ $colorsize->measurment}}</td>
                    <td width="70px" align="right">{{ $colorsize->plan_cut_qty}}</td>
                    <td width="70px">
                    <input type="text" name="bom_trim[{{ $i }}]" id="bom_trim{{ $i }}" class="number integer" style="background-color:#FFFFFF;border:none" value="{{ $colorsize->bom_trim}}" readonly/>
                    </td>
                    <td>
                    <input type="text" name="bom_rate[{{ $i  }}]" id="bom_rate{{ $i }}" class="number integer" style="background-color:#FFFFFF;border:none" value="{{ $colorsize->bom_rate}}" readonly/>
                    </td>
                    <td>
                    <input type="text" name="bom_amount[{{ $i }}]" id="bom_amount{{ $i }}" class="number integer" style="background-color:#FFFFFF;border:none" value="{{ $colorsize->bom_amount}}" readonly/>
                    </td>
                    
                    <td width="70px">
                    <input type="text" name="cuqty[{{ $i }}]" id="cuqty{{ $i }}" class="number integer" style="background-color:#FFFFFF;border:none" value="{{ $colorsize->prev_po_qty}}" readonly/>
                    </td>
                    <td width="70px">
                    <input type="text" name="balqty[{{ $i }}]" id="balqty{{ $i }}" class="number integer" style="background-color:#FFFFFF;border:none" value="{{ $colorsize->balance_qty}}" readonly/>
                    </td>
                    <td width="70px">
                    <input type="text" name="qty[{{ $i }}]" id="qty{{ $i }}" class="number integer" style="background-color:#FFFFFF;border:none" value="{{ $colorsize->qty }}" onchange="MsPoTrimItemQty.calculateAmount({{ $i }},{{$loop->count}},'qty')"/>
                    </td>
                    <td>
                    <input type="text" name="rate[{{ $i  }}]" id="rate_{{ $i }}" class="number integer" style="background-color:#FFFFFF;border:none"  value="{{ $colorsize->rate }}" onchange="MsPoTrimItemQty.calculateAmount({{ $i }},{{$loop->count}},'rate')"/>
                    </td>
                    <td>
                    <input type="text" name="amount[{{ $i }}]" id="amount_{{ $i }}" class="number integer" style="background-color:#FFFFFF;border:none" value="{{ $colorsize->amount }}" readonly/>
                    </td>
                    <td>
                      <input type="text" name="description[{{ $i }}]" id="description{{ $i }}" style="background-color:#FFFFFF;border:none" value="{{ $colorsize->description }}" onchange="MsPoTrimItemQty.copyDescription({{ $i }},{{$loop->count}})"/>
                    <input type="hidden" name="po_trim_item_qty_id[{{ $i }}]" id="po_trim_item_qty_id{{ $i }}" class="number integer" style="background-color:#FFFFFF;border:none" value="{{ $colorsize->po_trim_item_qty_id }}" readonly/>
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
               
               <td width="100px"></td>
               
               <td width="155px"> </td>
               <td width="70px"></td>
               <td width="70px" align="right"> {{ number_format($totCutQty,0,'.',',') }} </td>
               <td width="70px" align="right">{{ number_format($totGreyQty ,4,'.',',')}}</td>
               <td width="60px"></td>
               <td width="80px" align="right">{{ number_format($totAmountQty,4,'.',',') }}</td>
               
               <td width="70px" align="right">{{ number_format($totcumuQty ,4,'.',',')}}</td>
               <td width="70px" align="right">{{ number_format($totbalQty ,4,'.',',')}}</td>
               <td width="70px" align="right">{{ number_format($totpoQty ,4,'.',',')}}</td>
               <td width="60px"></td>
               <td width="80px" align="right">{{ number_format($totpoAmount,4,'.',',') }}</td>
               <td width="100px" align="right"></td>

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
