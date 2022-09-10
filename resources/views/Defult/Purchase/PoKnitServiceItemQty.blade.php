<?php
$i=1;
?>
@if($new->isNotEmpty())
<table border="1" class="table_form">
<caption>New Color Size</caption>
        
        <tr align="center">
               <td width="100px">Sales Order</td>
               <td width="100px">Ship Date</td>
               <td width="30px">Dia/ Width</td>
               <td width="30px">Measurment</td>
               <td width="80px">Fabric Color</td>
               <td width="70px">Cut Qty (Psc)</td>
               <td width="70px">BOM Qty</td>
               <td width="60px">BOM Rate</td>
               <td width="80px">BOM Amount</td>
               <td width="70px">Prev. PO. Qty</td>
               <td width="70px">Bal. PO. Qty</td>
               <td width="70px">PO. Qty</td>
               <td width="70px">EQV Pcs</td>
               <td width="60px">PO. Rate</td>
               <td width="80px">PO. Amount</td>
               <td width="80px">Color Range</td>
               <td width="80px">Plan Dia</td>
               <td width="80px">Plan GSM</td>
               <td width="80px">Stitch Length</td>
               <td width="80px">Spandex Stitch Length</td>
               <td width="80px">Draft Ratio</td>
               <td width="80px">Machine gg</td>

        </tr>
    <tbody>
      <?php
	 // $i=1;
	  $totCutQty=0;
	  $totGreyQty=0;
	  $totAmountQty=0;
	  $totcumuQty=0;
	  $totbalQty=0;
	  $totpoQty=0;
    $totpoPcsQty=0;
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
    $totpoPcsQty+=$colorsize->pcs_qty;
		$totpoAmount+=$colorsize->amount;
		 
	  ?>
                    <tr align="center">
                    <td width="100px">
                      {{ $colorsize->sale_order_no }}
                      <input type="hidden" name="po_knit_service_item_id[{{ $i }}]"  value="{{ $colorsize->po_knit_service_item_id }}"/>
                      <input type="hidden" name="sales_order_id[{{ $i }}]"  value="{{ $colorsize->sales_order_id }}"/>
                    </td>
                    <td width="100px">
                      {{ $colorsize->ship_date }}
                    </td>
                    
                    <td width="30px">
                      <input type="text" name="dia[{{ $i }}]"  value="{{ $colorsize->dia }}" readonly />
                    </td>
                    <td width="30px">
                      <input type="text" name="measurment[{{ $i }}]"  value="{{ $colorsize->measurment }}" readonly />
                    </td>
                    <td width="80px">
                      {{$colorsize->fabric_color_name}}
                      <input type="hidden" name="fabric_color_id[{{ $i }}]"  value="{{ $colorsize->fabric_color }}" readonly />
                    </td>
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
                    <input type="text" name="qty[{{ $i }}]" id="qty{{ $i }}" class="number integer" value="{{ $colorsize->qty }}" onchange="MsPoKnitServiceItemQty.calculateAmount({{ $i }},{{$loop->count}},'qty')"/>
                    </td>
                    <td width="70px">
                    <input type="text" name="pcs_qty[{{ $i }}]" id="pcs_qty{{ $i }}" class="number integer" value="{{ $colorsize->pcs_qty }}" onchange="MsPoKnitServiceItemQty.calculateAmount({{ $i }},{{$loop->count}},'pcs_qty')"/>
                    </td>
                    <td>
                    <input type="text" name="rate[{{ $i  }}]" id="rate_{{ $i }}" class="number integer"  value="{{ $colorsize->rate }}" onchange="MsPoKnitServiceItemQty.calculateAmount({{ $i }},{{$loop->count}},'rate')"/>
                    </td>
                    <td>
                    <input type="text" name="amount[{{ $i }}]" id="amount_{{ $i }}" class="number integer" value="{{ $colorsize->amount }}" readonly/>
                    </td>
                    <td>
                    
                    {!! Form::select("colorrange_id[$i]", $colorrange,$colorsize->colorrange_id,array('id'=>'colorrange_id')) !!}
                    </td>
                    <td>
                    <input type="text" name="pl_dia[{{ $i }}]" id="pl_dia{{ $i }}"  value="{{ $colorsize->pl_dia }}" />
                    </td>
                    <td>
                    <input type="text" name="pl_gsm_weight[{{ $i }}]" id="pl_gsm_weight{{ $i }}" class="number integer" value="{{ $colorsize->pl_gsm_weight }}" />
                    </td>
                    <td>
                    <input type="text" name="pl_stitch_length[{{ $i }}]" id="pl_stitch_length{{ $i }}"  value="{{ $colorsize->pl_stitch_length }}" />
                    </td>
                    <td>
                    <input type="text" name="pl_spandex_stitch_length[{{ $i }}]" id="pl_spandex_stitch_length{{ $i }}"  value="{{ $colorsize->pl_spandex_stitch_length }}" />
                    </td>
                    <td>
                    <input type="text" name="pl_draft_ratio[{{ $i }}]" id="pl_draft_ratio{{ $i }}" class="number integer" value="{{ $colorsize->pl_draft_ratio }}" />
                    </td>
                    <td>
                    <input type="text" name="pl_machine_gg[{{ $i }}]" id="pl_machine_gg{{ $i }}" class="number integer" value="{{ $colorsize->pl_machine_gg }}" />
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
               <td width="80px"></td>
               <td width="30px"></td>
               <td width="70px" align="right"> {{ number_format($totCutQty,0,'.',',') }} </td>
               <td width="70px" align="right">{{ number_format($totGreyQty ,4,'.',',')}}</td>
               <td width="60px"></td>
               <td width="80px" align="right">{{ number_format($totAmountQty,4,'.',',') }}</td>
               <td width="70px" align="right">{{ number_format($totcumuQty ,4,'.',',')}}</td>
               <td width="70px" align="right">{{ number_format($totbalQty ,4,'.',',')}}</td>
               <td width="70px" align="right">{{ number_format($totpoQty ,4,'.',',')}}</td>
               <td width="70px" align="right">{{ number_format($totpoPcsQty ,4,'.',',')}}</td>
               <td width="60px"></td>
               <td width="80px" align="right">{{ number_format($totpoAmount,4,'.',',') }}</td>
               <td width="80px"></td>
               <td width="80px"></td>
               <td width="80px"></td>
               <td width="80px"></td>
               <td width="80px"></td>
               <td width="80px"></td>
               <td width="80px"></td>
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
               <td width="100px">Ship Date</td>
               <td width="30px">Dia/ Width</td>
               <td width="30px">Measurment</td>
               <td width="30px">Fabric Color</td>
               
               <td width="70px">Cut Qty (Psc)</td>
               <td width="70px">BOM Qty</td>
               <td width="60px">BOM Rate</td>
               <td width="80px">BOM Amount</td>
               <td width="70px">Prev. PO. Qty</td>
               <td width="70px">Bal. PO. Qty</td>
               <td width="70px">PO. Qty</td>
               <td width="70px">EQV Pcs</td>
               <td width="60px">PO. Rate</td>
               <td width="80px">PO. Amount</td>

               <td width="80px">Color Range</td>
               <td width="80px">Plan Dia</td>
               <td width="80px">Plan GSM</td>
               <td width="80px">Stitch Length</td>
               <td width="80px">Spandex Stitch Length</td>
               <td width="80px">Draft Ratio</td>
               <td width="80px">Machine gg</td>
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
    $totpoPcsQty=0;
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
    $totpoPcsQty+=$colorsize->pcs_qty;
		$totpoAmount+=$colorsize->amount;
		 
	  ?>
                    <tr align="center">
                    <td width="100px">
                      {{ $colorsize->sale_order_no }}
                      <input type="hidden" name="po_knit_service_item_id[{{ $i }}]"  value="{{ $colorsize->po_knit_service_item_id }}"/>
                      <input type="hidden" name="sales_order_id[{{ $i }}]"  value="{{ $colorsize->sales_order_id }}"/>

                    </td>
                    <td width="100px">
                      {{ $colorsize->ship_date }}
                    </td>
                    <td width="30px">
                    <input type="text" name="dia[{{ $i }}]"  value="{{ $colorsize->dia }}" readonly />
                    </td>
                    <td width="30px">
                      <input type="text" name="measurment[{{ $i }}]"  value="{{ $colorsize->measurment }}" readonly />
                    </td>
                    <td width="80px">
                      {{$colorsize->fabric_color_name}}
                      <input type="hidden" name="fabric_color_id[{{ $i }}]"  value="{{ $colorsize->fabric_color }}" readonly />
                      
                    </td>
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
                    <input type="text" name="qty[{{ $i }}]" id="qty{{ $i }}" class="number integer" value="{{ $colorsize->qty }}" onchange="MsPoKnitServiceItemQty.calculateAmount({{ $i }},{{$loop->count}},'qty')"/>
                    </td>
                    <td width="70px">
                    <input type="text" name="pcs_qty[{{ $i }}]" id="pcs_qty{{ $i }}" class="number integer" value="{{ $colorsize->pcs_qty }}" onchange="MsPoKnitServiceItemQty.calculateAmount({{ $i }},{{$loop->count}},'pcs_qty')"/>
                    </td>
                    <td>
                    <input type="text" name="rate[{{ $i  }}]" id="rate_{{ $i }}" class="number integer"  value="{{ $colorsize->rate }}" onchange="MsPoKnitServiceItemQty.calculateAmount({{ $i }},{{$loop->count}},'rate')"/>
                    </td>
                    <td>
                    <input type="text" name="amount[{{ $i }}]" id="amount_{{ $i }}" class="number integer" value="{{ $colorsize->amount }}" readonly/>
                    </td>
                    <td>
                    <input type="hidden" name="po_knit_service_item_qty_id[{{ $i }}]" id="po_knit_service_item_qty_id{{ $i }}" class="number integer" value="{{ $colorsize->po_knit_service_item_qty_id }}" readonly/>
                    {!! Form::select("colorrange_id[$i]", $colorrange,$colorsize->colorrange_id,array('id'=>'colorrange_id')) !!} 
                    </td>

                    
                    <td>
                    <input type="text" name="pl_dia[{{ $i }}]" id="pl_dia{{ $i }}"  value="{{ $colorsize->pl_dia }}" />
                    </td>
                    <td>
                    <input type="text" name="pl_gsm_weight[{{ $i }}]" id="pl_gsm_weight{{ $i }}" class="number integer" value="{{ $colorsize->pl_gsm_weight }}" />
                    </td>
                    <td>
                    <input type="text" name="pl_stitch_length[{{ $i }}]" id="pl_stitch_length{{ $i }}"  value="{{ $colorsize->pl_stitch_length }}" />
                    </td>
                    <td>
                    <input type="text" name="pl_spandex_stitch_length[{{ $i }}]" id="pl_spandex_stitch_length{{ $i }}"  value="{{ $colorsize->pl_spandex_stitch_length }}" />
                    </td>
                    <td>
                    <input type="text" name="pl_draft_ratio[{{ $i }}]" id="pl_draft_ratio{{ $i }}" class="number integer" value="{{ $colorsize->pl_draft_ratio }}" />
                    </td>
                    <td>
                    <input type="text" name="pl_machine_gg[{{ $i }}]" id="pl_machine_gg{{ $i }}" class="number integer" value="{{ $colorsize->pl_machine_gg }}" />
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
               <td width="80px"></td>
               <td width="30px"></td>
               <td width="70px" align="right"> {{ number_format($totCutQty,0,'.',',') }} </td>
               <td width="70px" align="right">{{ number_format($totGreyQty ,4,'.',',')}}</td>
               <td width="60px"></td>
               <td width="80px" align="right">{{ number_format($totAmountQty,4,'.',',') }}</td>
               <td width="70px" align="right">{{ number_format($totcumuQty ,4,'.',',')}}</td>
               <td width="70px" align="right">{{ number_format($totbalQty ,4,'.',',')}}</td>
               <td width="70px" align="right">{{ number_format($totpoQty ,4,'.',',')}}</td>
               <td width="70px" align="right">{{ number_format($totpoPcsQty ,4,'.',',')}}</td>
               <td width="60px"></td>
               <td width="80px" align="right">{{ number_format($totpoAmount,4,'.',',') }}</td>
               <td width="80px"></td>
               <td width="80px"></td>
               <td width="80px"></td>
               <td width="80px"></td>
               <td width="80px"></td>
               <td width="80px"></td>
               <td width="80px"></td>
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
