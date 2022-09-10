<table border="1" class="table_form">
        <tr align="center">
               <td colspan="2">Color</td>
               <td colspan="2">Size</td>
               @if($gmtsparts_type==1)
                <td colspan="8">Mesurement</td>
                @endif
                @if($gmtsparts_type==2)
                <td colspan="9">Mesurement</td>
                @endif
               <td colspan="6">
               <input type="checkbox" name="is_copy" id="is_copy" style="display:none"/>
               <input type="hidden" name="gsm" id="gsm" value="{{ $gsm }}"/>
               </td>
        </tr>
        <tr align="center">
               <td colspan="2"></td>
               <td colspan="2"></td>
               @if($gmtsparts_type==1)
               <td  colspan="3">Body</td>
               <td colspan="3">Sleeve </td>
               <td colspan="2">1/2 Chest</td>
                @endif
                @if($gmtsparts_type==2)
                
                <td  colspan="2">Front Rise</td>
                <td colspan="2">West Band</td>
                <td colspan="3">In Seam</td>
                <td colspan="2"> Half Thai</td>
                @endif
               <td colspan="6">
               </td>
        </tr>
        <tr align="center">
               <td width="100px">Name</td>
               <td width="100px">Code</td>
               <td width="100px">Name</td>
               <td width="100px">Code</td>
               @if($gmtsparts_type==1)
               <td width="70px">Length</td>
                    <td width="70px">Sewing Margin</td>
                    <td width="70px">Hem Margin</td>
                    <td width="70px">Length</td>
                    <td width="70px">Sewing Margin</td>
                    <td width="70px">Hem Margin</td>
                    <td width="70px">Length</td>
                    <td width="70px">Sewing Margin</td>
              @endif
              
              @if($gmtsparts_type==2)
                    <td width="70">Length</td>
                    <td  width="70">Sewing Margin</td>
                    <td  width="70">Length</td>
                    <td width="70"> Sewing Margin</td>
                    <td width="70">Length</td>
                    <td width="70">Sewing Margin</td>
                    <td width="70">Hem Margin</td>
                    <td width="70">Length</td> 
                    <td width="70">Sewing Margin</td>
              @endif
               
               <td width="50px">Dia</td>
               <td width="60px">Cons</td>
               <td width="50px">Pro. Loss</td>
               <td width="60px">Req. Cons</td>
               <td width="60px">Rate</td>
               <td width="60px">Amount</td>
           
        </tr>
    <tbody>
      <?php 
	  $i=1;
	  $readonly="";
	  if($gmtsparts_type==1 || $gmtsparts_type==2){
		  $readonly="readonly";
	  }else{
		  $readonly="";
	  }
	  ?>
        @foreach($colorsizes as $colorsize)
                    <tr align="center">
                    <td width="100px">
                    {{ $colorsize->color_name }}
                     <input type="hidden" name="mkt_cost_fabric_id[{{ $i }}]" id="mkt_cost_fabric_id_{{ $i }}" value="{{ $colorsize->mkt_cost_fabric_id }}"/>
                     <input type="hidden" name="style_color_id[{{ $i }}]" id="style_color_id_{{ $i }}" value="{{ $colorsize->style_color_id }}"/>
                     <input type="hidden" name="mkt_cost_id[{{ $i }}]" id="mkt_cost_id{{ $i }}" value="{{ $colorsize->mkt_cost_id }}"/>
                     <input type="hidden" name="style_gmt_color_size_id[{{ $i }}]" id="style_gmt_color_size_id{{ $i }}" value="{{ $colorsize->style_gmt_color_size_id }}"/>
                     
                     
                    </td>
                    <td width="100px">{{ $colorsize->color_name }}</td>
                    <td width="100px">
                    {{ $colorsize->size_name }}
                     <input type="hidden" name="style_size_id[{{ $i }}]" id="style_size_id_{{ $i }}" value="{{ $colorsize->style_size_id }}"/>
                    </td>
                    <td width="100px">{{ $colorsize->size_code }}</td>
                     @if($gmtsparts_type==1)
                    <td width="70px"><input type="text" name="body_lenght[{{ $i }}]" class="number integer" onchange="MsMktCostFabricCon.measurement_top(this.value,{{ $i }},{{ $loop->count}})" value="{{ $colorsize->body_lenght}}"/></td>
                    <td width="70px"><input type="text" name="body_sewing_margin[{{ $i }}]" class="number integer" onchange="MsMktCostFabricCon.measurement_top(this.value,{{ $i }},{{ $loop->count}})" value="{{ $colorsize->body_sewing_margin}}"/></td>
                    <td width="70px"><input type="text" name="body_hem_margin[{{ $i }}]" class="number integer" onchange="MsMktCostFabricCon.measurement_top(this.value,{{ $i }},{{ $loop->count}})" value="{{ $colorsize->body_hem_margin}}"/></td>
                    <td width="70px"><input type="text" name="sleeve_lenght[{{ $i }}]" class="number integer" onchange="MsMktCostFabricCon.measurement_top(this.value,{{ $i }},{{ $loop->count}})" value="{{ $colorsize->sleeve_lenght}}"/></td>
                    <td width="70px"><input type="text" name="sleeve_sewing_margin[{{ $i }}]" class="number integer" onchange="MsMktCostFabricCon.measurement_top(this.value,{{ $i }},{{ $loop->count}})" value="{{ $colorsize->sleeve_sewing_margin}}"/></td>
                    <td width="70px"><input type="text" name="sleeve_hem_margin[{{ $i }}]" class="number integer"  onchange="MsMktCostFabricCon.measurement_top(this.value,{{ $i }},{{ $loop->count}})" value="{{ $colorsize->sleeve_hem_margin}}"/></td>
                    <td width="70px"><input type="text" name="chest_lenght[{{ $i }}]" class="number integer" onchange="MsMktCostFabricCon.measurement_top(this.value,{{ $i }},{{ $loop->count}})" value="{{ $colorsize->chest_lenght}}"/></td>
                    <td width="70px"><input type="text" name="chest_sewing_margin[{{ $i }}]" class="number integer" onchange="MsMktCostFabricCon.measurement_top(this.value,{{ $i }},{{ $loop->count}})" value="{{ $colorsize->chest_sewing_margin}}"/></td>
                    @endif
                     @if($gmtsparts_type==2)
                    <td width="70px"><input type="text" name="frontraise_lenght[{{ $i }}]" class="number integer" onchange="MsMktCostFabricCon.measurement_bottom(this.value,{{ $i }},{{ $loop->count}})" value="{{ $colorsize->frontraise_lenght}}"/></td>
                    <td width="70px"><input type="text" name="frontraise_sewing_margin[{{ $i }}]" class="number integer" onchange="MsMktCostFabricCon.measurement_bottom(this.value,{{ $i }},{{ $loop->count}})" value="{{ $colorsize->frontraise_sewing_margin}}"/></td>
                    <td width="70px"><input type="text" name="westband_lenght[{{ $i }}]" class="number integer" onchange="MsMktCostFabricCon.measurement_bottom(this.value,{{ $i }},{{ $loop->count}})" value="{{ $colorsize->westband_lenght}}"/></td>
                    <td width="70px"><input type="text" name="westband_sewing_margin[{{ $i }}]" class="number integer" onchange="MsMktCostFabricCon.measurement_bottom(this.value,{{ $i }},{{ $loop->count}})" value="{{ $colorsize->westband_sewing_margin}}"/></td>
                    <td width="70px"><input type="text" name="inseam_lenght[{{ $i }}]" class="number integer" onchange="MsMktCostFabricCon.measurement_bottom(this.value,{{ $i }},{{ $loop->count}})" value="{{ $colorsize->inseam_lenght}}"/></td>
                    <td width="70px"><input type="text" name="inseam_sewing_margin[{{ $i }}]" class="number integer" onchange="MsMktCostFabricCon.measurement_bottom(this.value,{{ $i }},{{ $loop->count}})" value="{{ $colorsize->inseam_sewing_margin}}"/></td>
                    <td width="70px"><input type="text" name="inseam_hem_margin[{{ $i }}]" class="number integer" onchange="MsMktCostFabricCon.measurement_bottom(this.value,{{ $i }},{{ $loop->count}})" value="{{ $colorsize->inseam_hem_margin}}"/></td>
                    <td width="70px"><input type="text" name="thai_lenght[{{ $i }}]" class="number integer" onchange="MsMktCostFabricCon.measurement_bottom(this.value,{{ $i }},{{ $loop->count}})" value="{{ $colorsize->thai_lenght}}"/></td>
                    <td width="70px"><input type="text" name="thai_sewing_margin[{{ $i }}]" class="number integer" onchange="MsMktCostFabricCon.measurement_bottom(this.value,{{ $i }},{{ $loop->count}})" value="{{ $colorsize->thai_sewing_margin}}"/></td>
                     @endif
                    <td width="50px">
                    <input type="text" name="dia[{{ $i }}]" id="dia_{{ $i }}"  onchange="MsMktCostFabricCon.copyDia(this.value,{{ $i }},{{ $loop->count}})" value="{{ $colorsize->dia}}"/>
                    </td>
                    <td width="60px">
                    <input type="text" name="cons[{{ $i }}]" id="cons_{{ $i }}" class="number integer" onchange="MsMktCostFabricCon.calculate({{ $i }},{{ $loop->count}},'cons')" value="{{ $colorsize->cons}}"  /> <!-- {{ $readonly }} -->
                    </td>
                    <td width="50px">
                    <input type="text" name="process_loss[{{ $i }}]" id="process_loss_{{ $i }}" class="number integer" onchange="MsMktCostFabricCon.calculate({{ $i }},{{ $loop->count}},'process_loss')" value="{{ $colorsize->process_loss}}"/>
                    </td>
                    <td width="60px">
                    <input type="text" name="req_cons[{{ $i }}]" id="req_cons_{{ $i }}" class="number integer" onchange="MsMktCostFabricCon.calculate({{ $i }},{{ $loop->count}},'req_cons')" value="{{ $colorsize->req_cons}}" readonly/>
                    </td>
                    <td>
                    <input type="text" name="rate[{{ $i  }}]" id="rate_{{ $i }}" class="number integer" onchange="MsMktCostFabricCon.calculate({{ $i }},{{$loop->count}},'rate')" value="{{ $colorsize->rate}}"/>
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
   
</table>
<script>
$('.integer').keyup(function () {
    if (this.value != this.value.replace(/[^0-9\.]/g, '')) {
       this.value = this.value.replace(/[^0-9\.]/g, '');
    }
});
</script>