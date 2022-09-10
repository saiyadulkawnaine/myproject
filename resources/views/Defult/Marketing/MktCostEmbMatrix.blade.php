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
               <td width="100px">Style Gmt</td>
               <td width="100px">Name</td>
               <td width="100px">Type</td>
               <td width="100px">Size</td>
               <td width="70px">GMT Qty </td>
               <td width="70px">Rate / {{ $Dzn }}</td>
                <td width="15px"></td>
               <td width="80px">Amount</td>
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
            $cons=$colorsize->cons;
			}
            else{
            $cons=$colorsize->costing_unit_id;
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
                    {{ $colorsize->item_description }}
                     
                    <input type="hidden" name="style_embelishment_id[{{ $i }}]" id="style_embelishment_id_{{ $i }}" value="{{ $colorsize->style_embelishment_id }}"/>
                    <input type="hidden" name="embelishment_id[{{ $i }}]" id="embelishment_id{{ $i }}" value="{{ $colorsize->embelishment_id }}"/>
                    <input type="hidden" name="embelishment_type_id[{{ $i }}]" id="embelishment_type_id{{ $i }}" value="{{ $colorsize->embelishment_type_id }}"/>
                    <input type="hidden" name="embelishment_size_id[{{ $i }}]" id="embelishment_size_id{{ $i }}" value="{{ $colorsize->embelishment_size_id }}"/>
                    </td>
                    <td width="100px">{{ $colorsize->embelishment_name }}</td>
                    <td width="100px">{{ $colorsize->embelishment_type}}</td>
                     <td width="100px">{{ $colorsize->embelishment_size_name}}</td>
                    <td width="70px">
                    <input type="text" name="cons[{{ $i }}]" id="cons_{{ $i }}" class="number integer" onchange="MsMktCostEmb.calculate({{ $i }},{{ $loop->count}},'cons')" value="{{ $cons }}" <?php echo $readonly; ?>/>
                    </td>
                    <td>
                    <input style="width:95%" type="text" name="rate[{{ $i  }}]" id="rate_{{ $i }}" class="number integer" onchange="MsMktCostEmb.calculate({{ $i }},{{$loop->count}},'rate')" value="{{ $colorsize->rate }}"/>
                    </td>
                     <td width="15px"><a class="threedotv" style="background-color:#0DAB33" href="javascript:void(0)" onClick="MsMktCostEmb.getRate({{ $i }})"></a></td>
                    <td>
                    <input type="text" name="amount[{{ $i }}]" id="amount_{{ $i }}" class="number" value="{{ $colorsize->amount}}" readonly />
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
               <td width="15px"></td>
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