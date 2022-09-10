<?php
$i=1;
?>
<table border="1" class="table_form">
<tr>
<td width="70">Style Ref</td>
<td width="100">Sale Order No</td>
<td width="100">GMT Color</td> 
<td width="100">Yarn Color</td> 
<td width="100">Color Range</td> 
<td width="100">BOM Qty</td>   
<td width="100">BOM Rate</td>   
<td width="100">BOM Amount</td>   
<td width="100">Measurment</td>   
<td width="100">Feeder</td>
<td width="100">Qty</td> 
<td width="100">Rate</td>
<td width="100">Amount</td>    
<td width="100">Process Loss %</td>
<td width="100">Wgt/Cone</td>    
<td width="100">Required Cone</td>    
<td width="100">Remark</td>    
</tr>
<tbody>
  <?php
  $i=1;
  ?>
@foreach ($salesorder as $row)
<tr>
<td width="70">
  {{$row->style_ref}}
    <input type="hidden" name="budget_yarn_dyeing_con_id[{{ $i }}]" id="budget_yarn_dyeing_con_id{{ $i }}" class="number integer" value="{{$row->budget_yarn_dyeing_con_id}}" />

</td>
<td width="100">{{$row->sale_order_no}}</td>
<td width="100">{{$row->gmt_color_name}}</td> 
<td width="100">{{$row->yarn_color_name}}</td> 
<td width="100">{!! Form::select("colorrange_id[$i]", $colorrange,'',array('id'=>'colorrange_id')) !!}</td> 
<td width="100">{{$row->bom_qty}}</td>   
<td width="100">{{$row->bom_rate}}</td>   
<td width="100">{{$row->bom_amount}}</td>   
<td width="100">{{$row->measurment}}</td>   
<td width="100">{{$row->feeder}}</td>
<td width="100">
  <input type="text" name="qty[{{ $i }}]" id="qty{{ $i }}" class="number integer" onchange="MsPoYarnDyeingItemBomQty.calculateAmount({{ $i }},{{$loop->count}},'qty')"/>
</td> 

<td width="100">
  <input type="text" name="rate[{{ $i }}]" id="rate{{ $i }}" class="number integer" onchange="MsPoYarnDyeingItemBomQty.calculateAmount({{ $i }},{{$loop->count}},'rate')"/>
</td>
<td width="100">
  <input type="text" name="amount[{{ $i }}]" id="amount{{ $i }}" class="number integer" onchange="MsPoYarnDyeingItemBomQty.calculateAmount({{ $i }},{{$loop->count}},'amount')"/>
</td>
<td width="100">
    <input type="text" name="process_loss_per[{{ $i }}]" id="process_loss_per{{ $i }}" class="number integer" value=""  onchange="MsPoYarnDyeingItemBomQty.calculateReqCone({{ $i }},{{$loop->count}},'wgt_per_cone')"/>
</td>
<td width="100">
  <input type="text" name="wgt_per_cone[{{ $i }}]" id="wgt_per_cone{{ $i }}" class="number integer" value=""  onchange="MsPoYarnDyeingItemBomQty.calculateReqCone({{ $i }},{{$loop->count}},'wgt_per_cone')"/>
</td>
<td width="100">
  <input type="text" name="req_cone[{{ $i }}]" id="req_cone{{ $i }}" class="number integer"/>
</td>      
<td width="100">
  <input type="text" name="remarks[{{ $i }}]" id="remarks{{ $i }}"/>
</td>   
</tr>
<?php
  $i++;
  ?>
  @endforeach
  
</tbody>
</table>
<script>
$('.integer').keyup(function () {
    if (this.value != this.value.replace(/[^0-9\.]/g, '')) {
       this.value = this.value.replace(/[^0-9\.]/g, '');
    }
});
</script>