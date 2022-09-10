<?php
$i=1;
?>
@if($colorsizes->isNotEmpty())
<table border="1" class="table_form">
  <caption>New</caption>
        <tr align="center">
               <td colspan="2"></td>
               <td colspan="3">Color</td>
               <td colspan="3">Size</td>
               <td colspan="12">
               </td>
        </tr>
        <tr align="center">
               <td width="100px">Sales Order</td>
               <td width="100px">Country</td>
               <td width="100px">Name</td>
               <td width="100px">Code</td>
               <td width="30px">Seqn</td>
               <td width="100px">Name</td>
               <td width="100px">Code</td>
               <td width="30px">Seqn</td>
               <td width="150px">Fabric Color</td>
               <td width="70px">Measurment</td>
               <td width="30px">Dia/ Width</td>
               <td width="70px">Cut Qty (Psc)</td>
               <td width="70px">Cad Cons</td>
               <td width="70px">Unlayable %</td>
               <td width="70px">Practical Cons</td>
               <td width="70px">Fin.Fab Qty</td>
               <td width="70px">Pro. Loss</td>
               <td width="70px">BOM Qty</td>
               <td width="70px">Rate</td>
               <td width="80px">Amount</td>

        </tr>
    <tbody>
      <?php
        //$i=1;
        $totCutQty=0;
        $totFinQty=0;
        $totGreyQty=0;
        $totAmountQty=0;
        $fin_fab=0;
        $grey_fab=0;
        ?>
        @foreach($colorsizes as $colorsize)
        <?php
        $fabric_color=$colorsize->color_name;
        $cons= $colorsize->smp_cons;
        $fin_fab= ($colorsize->plan_cut_qty/12)*$colorsize->smp_cons;
        $grey_fab= ($colorsize->plan_cut_qty/12)*$colorsize->smp_req_cons;
        $amount= $grey_fab*$colorsize->smp_rate;
        

        $totCutQty+=$colorsize->plan_cut_qty;
        $totFinQty+=$fin_fab;
        $totGreyQty+=$grey_fab;
        $totAmountQty+=$colorsize->amount;

        ?>
                    <tr align="center">
                    <td width="100px">
                     {{ $colorsize->sale_order_no }}
                    </td>
                    <td width="100px">
                     {{ $colorsize->country_name }}
                    </td>
                    <td width="100px">
                    {{ $colorsize->color_name }}
                     <input type="hidden" name="budget_fabric_id[{{ $i }}]" id="budget_fabric_id_{{ $i }}" value="{{ $colorsize->budget_fabric_id }}"/>
                    <input type="hidden" name="style_color_id[{{ $i }}]" id="style_color_id_{{ $i }}" value="{{ $colorsize->style_color_id }}"/>
                     <input type="hidden" name="budget_id[{{ $i }}]" id="budget_id{{ $i }}" value="{{ $colorsize->budget_id }}"/>
                     <input type="hidden" name="sales_order_gmt_color_size_id[{{ $i }}]" id="sales_order_gmt_color_size_id{{ $i }}" value="{{ $colorsize->sales_order_gmt_color_size_id }}"/>
                    </td>
                    <td width="100px">{{ $colorsize->color_code }}</td>
                    <td width="30px">{{ $colorsize->color_sort_id}}</td>
                    <td width="100px">
                    {{ $colorsize->size_name }}
                     <input type="hidden" name="style_size_id[{{ $i }}]" id="style_size_id_{{ $i }}" value="{{ $colorsize->style_size_id }}"/>
                    </td>
                    <td width="100px">{{ $colorsize->size_code }}</td>
                    <td width="30px">{{ $colorsize->size_sort_id}}</td>
                    <td width="150px">
                    <input type="text" name="fabric_color[{{ $i }}]" id="fabric_color{{ $i }}" value="{{ $fabric_color }}" class="fabricColor" onchange="MsBudgetFabricCon.copyFabricColor(this.value,{{ $i }},{{ $loop->count }})"/>
                    </td>
                    <td width="70px">
                     <input type="text" name="measurment[{{ $i }}]" id="measurment{{ $i }}" onchange="MsBudgetFabricCon.copyMeasu(this.value,{{ $i }},{{ $loop->count}})" value="{{ $colorsize->measurment}}"/>
                    </td>
                    
                    <td width="30px">
                     <?php
                     $dia='';
                     if($colorsize->smp_dia)
                     {
                        $dia=$colorsize->smp_dia;
                     }
                     else
                     {
                        $dia=$colorsize->cad_dia;
                     }
                     ?>

                    <input type="text" name="dia[{{ $i }}]" id="dia_{{ $i }}"  onchange="MsBudgetFabricCon.copyDia(this.value,{{ $i }},{{ $loop->count}})" value="{{ $dia }}"/>
                    </td>
                    <td width="70px">
                    <input type="text" name="plun_cut_qty[{{ $i }}]" id="plun_cut_qty{{ $i }}" class="number integer"  value="{{ $colorsize->plan_cut_qty}}" readonly/>
                    </td>
                    <td width="70px">
                     <input type="text" name="cad_cons[{{ $i }}]" id="cad_cons{{ $i }}" class="number integer"  value="{{ $colorsize->cad_cons}}" readonly/>
                    </td>
                    <td width="70px">
                    <input type="text" name="unlayable_per[{{ $i }}]" id="unlayable_per{{ $i }}" class="number integer"  value="{{ $unlayablePer}}" readonly/>
                    </td>
                    <td width="70px">
                    <input type="text" name="cons[{{ $i }}]" id="cons_{{ $i }}" class="number integer" onchange="MsBudgetFabricCon.calculate({{ $i }},{{ $loop->count}},'cons')" value="{{ $cons }}"/>
                    </td>
                    <td width="70px">
                    <input type="text" name="fin_fab[{{ $i }}]" id="fin_fab{{ $i }}" class="number integer" value="{{ $fin_fab}}" readonly/>
                    </td>
                    <td width="70px">
                    <input type="text" name="process_loss[{{ $i }}]" id="process_loss_{{ $i }}" class="number integer" onchange="MsBudgetFabricCon.calculate({{ $i }},{{ $loop->count}},'process_loss')" value="{{ $colorsize->smp_process_loss}}"/>
                    <input type="hidden" name="req_cons[{{ $i }}]" id="req_cons_{{ $i }}" class="number integer" onchange="MsBudgetFabricCon.calculate({{ $i }},{{ $loop->count}},'req_cons')" value="{{ $colorsize->smp_req_cons}}" readonly/>
                    </td>
                    <td width="70px">
                    <input type="text" name="grey_fab[{{ $i }}]" id="grey_fab{{ $i }}" class="number integer" value="{{ $grey_fab}}" readonly/>
                    </td>
                    <td>
                    <input type="text" name="rate[{{ $i  }}]" id="rate_{{ $i }}" class="number integer" onchange="MsBudgetFabricCon.calculate({{ $i }},{{$loop->count}},'rate')" value="{{ $colorsize->smp_rate}}"/>
                    </td>
                    <td>
                    <input type="text" name="amount[{{ $i }}]" id="amount_{{ $i }}" class="number" value="{{ $amount}}" readonly/>
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
               <td width="100px"></td>
               <td width="100px"></td>
               <td width="30px"></td>
               <td width="100px"></td>
               <td width="100px"></td>
               <td width="30px"></td>
               <td width="150px"> </td>
               <td width="70px"></td>
               <td width="30px"></td>
               <td width="70px" align="right"> {{ number_format($totCutQty,0,'.',',') }} </td>
               <td width="70px"></td>
               <td width="70px"></td>
               <td width="70px"></td>
               <td width="70px" align="right">{{ number_format($totFinQty,4,'.',',') }}</td>
               <td width="70px"></td>
               <td width="70px" align="right">{{ number_format($totGreyQty ,4,'.',',')}}</td>
               <td width="70px"></td>
               <td width="80px" align="right">{{ number_format($totAmountQty,4,'.',',') }}</td>

        </tr>
</tfoot>
</table>
<br/>
@endif
@if($saved->isNotEmpty())
<table border="1" class="table_form">
  <caption>Saved</caption>
        <tr align="center">
               <td colspan="2"></td>
               <td colspan="3">Color</td>
               <td colspan="3">Size</td>
               <td colspan="12">
               </td>
        </tr>
        <tr align="center">
               <td width="100px">Sales Order</td>
               <td width="100px">Country</td>
               <td width="100px">Name</td>
               <td width="100px">Code</td>
               <td width="30px">Seqn</td>
               <td width="100px">Name</td>
               <td width="100px">Code</td>
               <td width="30px">Seqn</td>
               <td width="150px">Fabric Color</td>
               <td width="70px">Measurment</td>
               <td width="30px">Dia/ Width</td>
               <td width="70px">Cut Qty (Psc)</td>
               <td width="70px">Cad Cons</td>
               <td width="70px">Unlayable %</td>
               <td width="70px">Practical Cons</td>
               <td width="70px">Fin.Fab Qty</td>
               <td width="70px">Pro. Loss</td>
               <td width="70px">BOM Qty</td>
               <td width="70px">Rate</td>
               <td width="80px">Amount</td>

        </tr>
    <tbody>
      <?php
       // $i=1;
        $totCutQty=0;
        $totFinQty=0;
        $totGreyQty=0;
        $totAmountQty=0;
        $fin_fab=0;
        $grey_fab=0;
        ?>
        @foreach($saved as $colorsize)
        <?php
        $fabric_color=$color_arr[$colorsize->fabric_color];
        $cons= $colorsize->cons;
        $fin_fab= $colorsize->fin_fab;
        $grey_fab= $colorsize->grey_fab;
        

        $totCutQty+=$colorsize->plan_cut_qty;
        $totFinQty+=$fin_fab;
        $totGreyQty+=$grey_fab;
        $totAmountQty+=$colorsize->amount;

        ?>
                    <tr align="center">
                    <td width="100px">
                     {{ $colorsize->sale_order_no }}
                    </td>
                    <td width="100px">
                     {{ $colorsize->country_name }}
                    </td>
                    <td width="100px">
                    {{ $colorsize->color_name }}
                     <input type="hidden" name="budget_fabric_id[{{ $i }}]" id="budget_fabric_id_{{ $i }}" value="{{ $colorsize->budget_fabric_id }}"/>
                    <input type="hidden" name="style_color_id[{{ $i }}]" id="style_color_id_{{ $i }}" value="{{ $colorsize->style_color_id }}"/>
                     <input type="hidden" name="budget_id[{{ $i }}]" id="budget_id{{ $i }}" value="{{ $colorsize->budget_id }}"/>
                     <input type="hidden" name="sales_order_gmt_color_size_id[{{ $i }}]" id="sales_order_gmt_color_size_id{{ $i }}" value="{{ $colorsize->sales_order_gmt_color_size_id }}"/>
                    </td>
                    <td width="100px">{{ $colorsize->color_code }}</td>
                    <td width="30px">{{ $colorsize->color_sort_id}}</td>
                    <td width="100px">
                    {{ $colorsize->size_name }}
                     <input type="hidden" name="style_size_id[{{ $i }}]" id="style_size_id_{{ $i }}" value="{{ $colorsize->style_size_id }}"/>
                    </td>
                    <td width="100px">{{ $colorsize->size_code }}</td>
                    <td width="30px">{{ $colorsize->size_sort_id}}</td>
                    <td width="150px">
                    <input type="text" name="fabric_color[{{ $i }}]" id="fabric_color{{ $i }}" value="{{ $fabric_color }}" class="fabricColor" onchange="MsBudgetFabricCon.copyFabricColor(this.value,{{ $i }},{{ $loop->count }})"/>
                    </td>
                    <td width="70px">
                     <input type="text" name="measurment[{{ $i }}]" id="measurment{{ $i }}" onchange="MsBudgetFabricCon.copyMeasu(this.value,{{ $i }},{{ $loop->count}})" value="{{ $colorsize->measurment}}"/>
                    </td>
                    
                    <td width="30px">
                    <input type="text" name="dia[{{ $i }}]" id="dia_{{ $i }}"  onchange="MsBudgetFabricCon.copyDia(this.value,{{ $i }},{{ $loop->count}})" value="{{ $colorsize->dia}}"/>
                    </td>
                    <td width="70px">
                    <input type="text" name="plun_cut_qty[{{ $i }}]" id="plun_cut_qty{{ $i }}" class="number integer"  value="{{ $colorsize->plan_cut_qty}}" readonly/>
                    </td>
                     <td width="70px">
                    <input type="text" name="cad_cons[{{ $i }}]" id="cad_cons{{ $i }}" class="number integer"  value="{{ $colorsize->cad_cons}}" readonly/>
                    </td>
                    <td width="70px">
                    <input type="text" name="unlayable_per[{{ $i }}]" id="unlayable_per{{ $i }}" class="number integer"  value="{{ $colorsize->unlayable_per}}" readonly/>
                    </td>
                    <td width="70px">
                    <input type="text" name="cons[{{ $i }}]" id="cons_{{ $i }}" class="number integer" onchange="MsBudgetFabricCon.calculate({{ $i }},{{ $loop->count}},'cons')" value="{{ $cons }}"/>
                    </td>
                    <td width="70px">
                    <input type="text" name="fin_fab[{{ $i }}]" id="fin_fab{{ $i }}" class="number integer" value="{{ $fin_fab}}" readonly/>
                    </td>
                    <td width="70px">
                    <input type="text" name="process_loss[{{ $i }}]" id="process_loss_{{ $i }}" class="number integer" onchange="MsBudgetFabricCon.calculate({{ $i }},{{ $loop->count}},'process_loss')" value="{{ $colorsize->process_loss}}"/>
                    <input type="hidden" name="req_cons[{{ $i }}]" id="req_cons_{{ $i }}" class="number integer" onchange="MsBudgetFabricCon.calculate({{ $i }},{{ $loop->count}},'req_cons')" value="{{ $colorsize->req_cons}}" readonly/>
                    </td>
                    <td width="70px">
                    <input type="text" name="grey_fab[{{ $i }}]" id="grey_fab{{ $i }}" class="number integer" value="{{ $grey_fab}}" readonly/>
                    </td>
                    <td>
                    <input type="text" name="rate[{{ $i  }}]" id="rate_{{ $i }}" class="number integer" onchange="MsBudgetFabricCon.calculate({{ $i }},{{$loop->count}},'rate')" value="{{ $colorsize->rate}}"/>
                    </td>
                    <td>
                    <input type="text" name="amount[{{ $i }}]" id="amount_{{ $i }}" class="number" value="{{ $colorsize->amount}}" readonly/>
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
               <td width="100px"></td>
               <td width="100px"></td>
               <td width="30px"></td>
               <td width="100px"></td>
               <td width="100px"></td>
               <td width="30px"></td>
               <td width="150px"> </td>
               <td width="70px"></td>
               <td width="30px"></td>
               <td width="70px" align="right"> {{ number_format($totCutQty,0,'.',',') }} </td>
               <td width="70px"></td>
               <td width="70px"></td>
               <td width="70px"></td>
               <td width="70px" align="right">{{ number_format($totFinQty,4,'.',',') }}</td>
               <td width="70px"></td>
               <td width="70px" align="right">{{ number_format($totGreyQty ,4,'.',',')}}</td>
               <td width="70px"></td>
               <td width="80px" align="right">{{ number_format($totAmountQty,4,'.',',') }}</td>

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
$(document).ready(function() {
	var bloodhound = new Bloodhound({
	datumTokenizer: Bloodhound.tokenizers.whitespace,
	queryTokenizer: Bloodhound.tokenizers.whitespace,
	remote: {
	url: msApp.baseUrl()+'/color/getcolor?q=%QUERY%',
	wildcard: '%QUERY%'
	},
	});
	
	$('.fabricColor').typeahead({
	hint: true,
	highlight: true,
	minLength: 1
	}, {
	name: 'users',
	source: bloodhound,
	display: function(data) {
	return data.name  //Input value to be set when you select a suggestion. 
	},
	templates: {
	empty: [
	'<div class="list-group search-results-dropdown"><div class="list-group-item">Nothing found.</div></div>'
	],
	header: [
	'<div class="list-group search-results-dropdown">'
	],
	suggestion: function(data) {
	return '<div style="font-weight:normal; margin-top:-10px ! important;" class="list-group-item">' + data.name + '</div></div>'
	}
	}
	});
});
</script>
