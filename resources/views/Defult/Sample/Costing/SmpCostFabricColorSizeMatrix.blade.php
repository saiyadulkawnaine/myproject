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
               <td colspan="12">
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
               <td colspan="10">
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
               <td width="100px">Fabric Color</td>
               <td width="60px">Qty</td>
               <td width="60px">Cad Cons</td>
               <td width="60px">Unlayable %</td>
               <td width="60px">Practical Cons</td>
               <td width="60px">Fin. Fab</td>
               <td width="50px">Pro. Loss</td>
               <td width="60px">Req. Cons</td>
               <td width="60px">Grey. Fab</td>
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
         <?php 
          $fabric_color=$colorsize->color_name;
          if($colorsize->fabric_color){
             $fabric_color=$colorsize->fabric_color;
          }

          $unlayable_per=$unlayablePer;
          if($colorsize->unlayable_per){
             $unlayable_per=$colorsize->unlayable_per;
          }
         
        ?>
                    <tr align="center">
                    <td width="100px">
                    {{ $colorsize->color_name }}
                     <input type="hidden" name="smp_cost_fabric_id[{{ $i }}]" id="smp_cost_fabric_id_{{ $i }}" value="{{ $colorsize->smp_cost_fabric_id }}"/>
                     <input type="hidden" name="style_color_id[{{ $i }}]" id="style_color_id_{{ $i }}" value="{{ $colorsize->style_color_id }}"/>
                     <input type="hidden" name="smp_cost_id[{{ $i }}]" id="smp_cost_id{{ $i }}" value="{{ $colorsize->smp_cost_id }}"/>
                     <input type="hidden" name="style_sample_c_id[{{ $i }}]" id="style_sample_c_id{{ $i }}" value="{{ $colorsize->style_sample_c_id }}"/>
                     
                     
                    </td>
                    <td width="100px">{{ $colorsize->color_name }}</td>
                    <td width="100px">
                    {{ $colorsize->size_name }}
                     <input type="hidden" name="style_size_id[{{ $i }}]" id="style_size_id_{{ $i }}" value="{{ $colorsize->style_size_id }}"/>
                    </td>
                    <td width="100px">{{ $colorsize->size_code }}</td>
                     @if($gmtsparts_type==1)
                    <td width="70px"><input type="text" name="body_lenght[{{ $i }}]" class="number integer" onchange="MsSmpCostFabricCon.measurement_top(this.value,{{ $i }},{{ $loop->count}})" value="{{ $colorsize->body_lenght}}"/></td>
                    <td width="70px"><input type="text" name="body_sewing_margin[{{ $i }}]" class="number integer" onchange="MsSmpCostFabricCon.measurement_top(this.value,{{ $i }},{{ $loop->count}})" value="{{ $colorsize->body_sewing_margin}}"/></td>
                    <td width="70px"><input type="text" name="body_hem_margin[{{ $i }}]" class="number integer" onchange="MsSmpCostFabricCon.measurement_top(this.value,{{ $i }},{{ $loop->count}})" value="{{ $colorsize->body_hem_margin}}"/></td>
                    <td width="70px"><input type="text" name="sleeve_lenght[{{ $i }}]" class="number integer" onchange="MsSmpCostFabricCon.measurement_top(this.value,{{ $i }},{{ $loop->count}})" value="{{ $colorsize->sleeve_lenght}}"/></td>
                    <td width="70px"><input type="text" name="sleeve_sewing_margin[{{ $i }}]" class="number integer" onchange="MsSmpCostFabricCon.measurement_top(this.value,{{ $i }},{{ $loop->count}})" value="{{ $colorsize->sleeve_sewing_margin}}"/></td>
                    <td width="70px"><input type="text" name="sleeve_hem_margin[{{ $i }}]" class="number integer"  onchange="MsSmpCostFabricCon.measurement_top(this.value,{{ $i }},{{ $loop->count}})" value="{{ $colorsize->sleeve_hem_margin}}"/></td>
                    <td width="70px"><input type="text" name="chest_lenght[{{ $i }}]" class="number integer" onchange="MsSmpCostFabricCon.measurement_top(this.value,{{ $i }},{{ $loop->count}})" value="{{ $colorsize->chest_lenght}}"/></td>
                    <td width="70px"><input type="text" name="chest_sewing_margin[{{ $i }}]" class="number integer" onchange="MsSmpCostFabricCon.measurement_top(this.value,{{ $i }},{{ $loop->count}})" value="{{ $colorsize->chest_sewing_margin}}"/></td>
                    @endif
                     @if($gmtsparts_type==2)
                    <td width="70px"><input type="text" name="frontraise_lenght[{{ $i }}]" class="number integer" onchange="MsSmpCostFabricCon.measurement_bottom(this.value,{{ $i }},{{ $loop->count}})" value="{{ $colorsize->frontraise_lenght}}"/></td>
                    <td width="70px"><input type="text" name="frontraise_sewing_margin[{{ $i }}]" class="number integer" onchange="MsSmpCostFabricCon.measurement_bottom(this.value,{{ $i }},{{ $loop->count}})" value="{{ $colorsize->frontraise_sewing_margin}}"/></td>
                    <td width="70px"><input type="text" name="westband_lenght[{{ $i }}]" class="number integer" onchange="MsSmpCostFabricCon.measurement_bottom(this.value,{{ $i }},{{ $loop->count}})" value="{{ $colorsize->westband_lenght}}"/></td>
                    <td width="70px"><input type="text" name="westband_sewing_margin[{{ $i }}]" class="number integer" onchange="MsSmpCostFabricCon.measurement_bottom(this.value,{{ $i }},{{ $loop->count}})" value="{{ $colorsize->westband_sewing_margin}}"/></td>
                    <td width="70px"><input type="text" name="inseam_lenght[{{ $i }}]" class="number integer" onchange="MsSmpCostFabricCon.measurement_bottom(this.value,{{ $i }},{{ $loop->count}})" value="{{ $colorsize->inseam_lenght}}"/></td>
                    <td width="70px"><input type="text" name="inseam_sewing_margin[{{ $i }}]" class="number integer" onchange="MsSmpCostFabricCon.measurement_bottom(this.value,{{ $i }},{{ $loop->count}})" value="{{ $colorsize->inseam_sewing_margin}}"/></td>
                    <td width="70px"><input type="text" name="inseam_hem_margin[{{ $i }}]" class="number integer" onchange="MsSmpCostFabricCon.measurement_bottom(this.value,{{ $i }},{{ $loop->count}})" value="{{ $colorsize->inseam_hem_margin}}"/></td>
                    <td width="70px"><input type="text" name="thai_lenght[{{ $i }}]" class="number integer" onchange="MsSmpCostFabricCon.measurement_bottom(this.value,{{ $i }},{{ $loop->count}})" value="{{ $colorsize->thai_lenght}}"/></td>
                    <td width="70px"><input type="text" name="thai_sewing_margin[{{ $i }}]" class="number integer" onchange="MsSmpCostFabricCon.measurement_bottom(this.value,{{ $i }},{{ $loop->count}})" value="{{ $colorsize->thai_sewing_margin}}"/></td>
                     @endif
                    <td width="50px">
                    <input type="text" name="dia[{{ $i }}]" id="dia_{{ $i }}"  onchange="MsSmpCostFabricCon.copyDia(this.value,{{ $i }},{{ $loop->count}})" value="{{ $colorsize->dia}}"/>
                    </td>
                    <td width="100px">
                    <input type="text" name="fabric_color[{{ $i }}]" id="fabric_color{{ $i }}" value="{{ $fabric_color }}"/>
                    </td>
                    <td width="60px">
                    <input type="text" name="qty[{{ $i }}]" id="qty_{{ $i }}" class="number integer"  value="{{ $colorsize->qty}}" readonly/>
                    </td>
                    <td width="60px">
                    <input type="text" name="cad_cons[{{ $i }}]" id="cad_cons_{{ $i }}" class="number integer"  value="{{ $colorsize->cad_cons}}" readonly />
                    </td>
                    <td width="60px">
                    <input type="text" name="unlayable_per[{{ $i }}]" id="unlayable_per_{{ $i }}" class="number integer"  value="{{ $unlayable_per}}" readonly />
                    </td>
                    <td width="60px">
                    <input type="text" name="cons[{{ $i }}]" id="cons_{{ $i }}" class="number integer" onchange="MsSmpCostFabricCon.calculate({{ $i }},{{ $loop->count}},'cons')" value="{{ $colorsize->cons}}"/>
                    </td>

                    <td width="60px">
                    <input type="text" name="fin_fab[{{ $i }}]" id="fin_fab{{ $i }}" class="number integer"  value="{{ $colorsize->fin_fab}}" readonly />
                    </td>

                    <td width="50px">
                    <input type="text" name="process_loss[{{ $i }}]" id="process_loss_{{ $i }}" class="number integer" onchange="MsSmpCostFabricCon.calculate({{ $i }},{{ $loop->count}},'process_loss')" value="{{ $colorsize->process_loss}}"/>
                    </td>
                    <td width="60px">
                    <input type="text" name="req_cons[{{ $i }}]" id="req_cons_{{ $i }}" class="number integer" onchange="MsSmpCostFabricCon.calculate({{ $i }},{{ $loop->count}},'req_cons')" value="{{ $colorsize->req_cons}}" readonly/>
                    </td>
                    <td width="60px">
                    <input type="text" name="grey_fab[{{ $i }}]" id="grey_fab{{ $i }}" class="number integer"  value="{{ $colorsize->grey_fab}}" readonly />
                    </td>
                    <td>
                    <input type="text" name="rate[{{ $i  }}]" id="rate_{{ $i }}" class="number integer" onchange="MsSmpCostFabricCon.calculate({{ $i }},{{$loop->count}},'rate')" value="{{ $colorsize->rate}}"/>
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