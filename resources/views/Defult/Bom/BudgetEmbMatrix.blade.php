<?php
            if ($costing_unit_id==1){
            $Dzn='Pcs';
			}
            else{
            $Dzn='Dzn';
			}
			?>
<table border="1" class="table_form">
        <tr align="center">
               <td colspan="6"><input type="checkbox" name="is_copy" id="is_copy" checked/>Copy</td>
        </tr>
        <tr align="center">
               <td width="100px">Company</td>
               <td width="100px">Style Gmt</td>
               <td width="100px">Name</td>
               <td width="100px">Type</td>
               <td width="70px">GMT Qty </td>
               <td width="70px">Rate / {{ $Dzn }}</td>
               <td width="80px">Amount</td>
               <td width="80px">Overhead Rate</td>
               <td width="80px">Overhead Amount</td>
        </tr>
    <tbody>
      <?php
	  $i=1;
	  $cons=0;
	  $tot=0;
	  ?>
        @foreach($embs as $colorsize)
         <?php
      if ($colorsize->cons){
		       $inputcons=$colorsize->cons;
               $cons=$colorsize->cons;
			}
            else{
				$inputcons=$colorsize->costing_unit_id;
            	$cons="Add";
			}
			if ($colorsize->costing_unit_id==1){
            $readonly='readonly';
			}
            else{
             $readonly='';
			}

			?>
                    <tr align="center">
                    <td width="100px">
                      {!! Form::select("company_id[$i]", $company,$colorsize->company_id,array('id'=>'company_id')) !!}
                    </td>
                    <td width="100px">
                    {{ $colorsize->item_description }}
                     <input type="hidden" name="budget_id[{{ $i }}]" id="budget_id_{{ $i }}" value="{{ $colorsize->budget_id }}"/>
                    <input type="hidden" name="style_embelishment_id[{{ $i }}]" id="style_embelishment_id_{{ $i }}" value="{{ $colorsize->style_embelishment_id }}"/>
                    <input type="hidden" name="budget_emb_id[{{ $i }}]" id="budget_emb_id{{ $i }}" value="{{ $colorsize->id }}"/>
                    <input type="hidden" name="overhead_rate[{{ $i }}]" id="overhead_rate{{ $i }}" value="{{ $colorsize->overhead_rate }}"/>
                    </td>
                    <td width="100px">{{ $colorsize->embelishment_name }}</td>
                    <td width="100px">{{ $colorsize->embelishment_type}}</td>
                    <td width="70px" align="right">
                    <input type="hidden" name="cons[{{ $i }}]" id="cons_{{ $i }}" class="number integer" onchange="MsBudgetEmb.calculate({{ $i }},{{ $loop->count}},'cons')" value="{{ $inputcons }}" <?php echo $readonly; ?>/>
                   <a href="javascript:void(0)" onclick="MsBudgetEmb.openConsWindow({{ $colorsize->id }})">{{ $cons }}</a>
                    </td>
                    <td>
                    <input type="text" name="rate[{{ $i  }}]" id="rate_{{ $i }}" class="number integer" onchange="MsBudgetEmb.calculate({{ $i }},{{$loop->count}},'rate')" value="{{ $colorsize->rate}}" readonly/>
                    </td>
                    <td>
                    <input type="text" name="amount[{{ $i }}]" id="amount_{{ $i }}" class="number" value="{{ $colorsize->amount}}" readonly/>
                    </td>
                    <td>
                      {{ $colorsize->overhead_rate }}
                    </td>
                    <td>
                      {{ $colorsize->overhead_amount }}
                    </td>
                    </tr>
                      <?php
					   $tot+=$colorsize->amount;
					  $i++;
					  ?>
        @endforeach
    </tbody>
    <tfoot>
               <td width="100px"></td>
               <td width="100px"></td>
               <td width="100px"></td>
               <td width="100px"></td>
               <td width="70px"></td>
               <td width="70px">Total</td>
               <td width="80px" align="right">{{ $tot }}</td>
    </tfoot>
</table>
<script>
$('.integer').keyup(function () {
    if (this.value != this.value.replace(/[^0-9\.]/g, '')) {
       this.value = this.value.replace(/[^0-9\.]/g, '');
    }
});
</script>
