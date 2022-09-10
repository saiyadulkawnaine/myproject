<table border="1" class="table_form">
        <tr align="center">
               <td colspan="3">Color</td>
               <td colspan="3">Size</td>
               <td colspan="11">
               <input type="checkbox" name="is_copy" id="is_copy" checked/>Copy
               </td>
        </tr>
        <tr align="center">
               <td width="100px">Name</td>
               <td width="100px">Code</td>
               <td width="30px">Seqn</td>
               <td width="100px">Name</td>
               <td width="100px">Code</td>
               <td width="30px">Seqn</td>
               <td width="70px">Trim Color</td>
               <td width="70px">Measurment</td>
               <td width="70px">Order Qty</td>
               <td width="70px">Cons</td>
               <td width="70px">Req. Qty</td>
               <td width="70px">Pro. Loss</td>
               <td width="70px">BOM Qty</td>
               <td width="70px">Rate</td>
               <td width="80px">Amount</td>
        </tr>
    <tbody>
                    <?php
                      $i=1;
                      $ordQty=0;
                      $reqQty=0;
                      $bomQty=0;
                      $amontQty=0;
                    ?>
                    @foreach($colorsizes as $colorsize)
                      <?php
                        $trim_color=$colorsize->trim_color;
                        if(!$trim_color){
                        $trim_color=$colorsize->color_name;
                        }

                        $ordQty+=$colorsize->plan_cut_qty;
                        $reqQty+=$colorsize->req_trim;
                        $bomQty+=$colorsize->bom_trim;
                        $amontQty+=$colorsize->amount;
                      ?>
                    <tr align="center">
                    <td width="100px">
                    {{ $colorsize->color_name }}
                     <input type="hidden" name="smp_cost_trim_id[{{ $i }}]" id="smp_cost_trim_id{{ $i }}" value="{{ $colorsize->smp_cost_trim_id }}"/>
                     <input type="hidden" name="smp_cost_id[{{ $i }}]" id="smp_cost_id{{ $i }}" value="{{ $colorsize->smp_cost_id }}"/>
                     <input type="hidden" name="style_sample_c_id[{{ $i }}]" id="style_sample_c_id{{ $i }}" value="{{ $colorsize->style_sample_c_id }}"/>
                    </td>
                    <td width="100px">{{ $colorsize->color_code }}</td>
                    <td width="30px">{{ $colorsize->color_sort_id}}</td>
                    <td width="100px">
                    {{ $colorsize->size_name }}
                    </td>
                    <td width="100px">{{ $colorsize->size_code }}</td>
                    <td width="30px">{{ $colorsize->size_sort_id}}</td>
                    <td width="150px">
                    <input type="text" name="trim_color[{{ $i }}]" id="trim_color{{ $i }}" value="{{  $trim_color }}"/>
                    </td>
                    <td width="70px">
                     <input type="text" name="measurment[{{ $i }}]" id="measurment{{ $i }}" value="{{ $colorsize->measurment}}"/>
                    </td>
                    <td width="70px">
                    <input type="text" name="plun_cut_qty[{{ $i }}]" id="plun_cut_qty{{ $i }}" class="number integer"  value="{{ $colorsize->plan_cut_qty}}" readonly/>
                    </td>
                    <td width="70px">
                    <input type="text" name="cons[{{ $i }}]" id="cons_{{ $i }}" class="number integer" onchange="MsSmpCostTrimCon.calculate({{ $i }},{{ $loop->count}},'cons')" value="{{ $colorsize->cons}}"/>
                    </td>
                    <td width="70px">
                    <input type="text" name="req_trim[{{ $i }}]" id="req_trim{{ $i }}" class="number integer" value="{{ $colorsize->req_trim}}" readonly/>
                    </td>
                    <td width="70px">
                    <input type="text" name="process_loss[{{ $i }}]" id="process_loss_{{ $i }}" class="number integer" onchange="MsSmpCostTrimCon.calculate({{ $i }},{{ $loop->count}},'process_loss')" value="{{ $colorsize->process_loss}}"/>
                     <input type="hidden" name="req_cons[{{ $i }}]" id="req_cons_{{ $i }}" class="number integer" onchange="MsSmpCostTrimCon.calculate({{ $i }},{{ $loop->count}},'req_cons')" value="{{ $colorsize->req_cons}}" readonly/>
                    </td>
                    <td width="70px">
                    <input type="text" name="bom_trim[{{ $i }}]" id="bom_trim{{ $i }}" class="number integer" value="{{ $colorsize->bom_trim}}" readonly/>
                    </td>
                    <td>
                    <input type="text" name="rate[{{ $i  }}]" id="rate_{{ $i }}" class="number integer" onchange="MsSmpCostTrimCon.calculate({{ $i }},{{$loop->count}},'rate')" value="{{ $colorsize->rate}}"/>
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
               <td width="30px"></td>
               <td width="100px"></td>
               <td width="100px"></td>
               <td width="30px"></td>
               <td width="70px"> </td>
               <td width="70px"></td>
               <td width="70px" align="right">{{ number_format($ordQty , 0 , '.', ',')}}</td>
               <td width="70px"></td>
               <td width="70px" align="right">{{ number_format($reqQty , 4 , '.', ',')}}</td>
               <td width="70px"></td>
               <td width="70px" align="right">{{ number_format($bomQty , 4 , '.', ',')}}</td>
               <td width="70px"></td>
               <td width="80px" align="right">{{ number_format($amontQty , 4 , '.', ',')}}</td>
        </tr>
    </tfoot>
</table>
<script>
$('.integer').keyup(function () {
    if (this.value != this.value.replace(/[^0-9\.]/g, '')) {
       this.value = this.value.replace(/[^0-9\.]/g, '');
    }
});
</script>
