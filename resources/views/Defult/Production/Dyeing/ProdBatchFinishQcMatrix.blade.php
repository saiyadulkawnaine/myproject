<?php
$i=1;
?>
  <table border="1" class="table_form" width="4000px">
  <tr align="center">
  <td width="50px">#</td>
  <td width="80px">Roll No</td>
  <td width="100px">Custom No</td>
  <td width="150px">Order No</td>
  <td width="150px">Style Ref</td>
  <td width="150px">Buyer Name</td>
  <td width="70px">Batch Qty</td>
  <td width="70px">QC Pass Qty</td>
  <td width="70px">Reject Qty</td>
  <td width="70px">Grade</td>
  <td width="60px">GSM</td>
  <td width="80px">Dia</td>
  <td width="100px">Color Range</td>
  <td width="100px">Dyeing Color</td>
  <td width="80px">Body Part</td>
  <td width="250px">Fabric Description</td>
  <td width="70px">Fabric Shape</td>
  <td width="70px">Fabric Look</td>
  


  <td width="80px">Measurement</td>
  <td width="80px">Roll Length</td>
  <td width="80px">StitchLength</td>
  <td width="80px">Shrink %</td>
  <td width="80px">Dyeing Type</td>
  <td width="80px">Prod. Ref</td>
  <td width="80px">Gmt Sample</td>
  <td width="150px">Knitted By</td>
  <td width="150px">Produced For</td>
  <td width="">Yarn</td>

  </tr>
  <tbody>
    @foreach($data as $roll)
    <tr align="left">
    <td width="50px">
    {{$i}}
    </td>
    <td width="80px">
    {{ $roll->prod_knit_item_roll_id }}
    </td>
    <td width="100px">
    {{ $roll->custom_no }}
    <input type="hidden" name="prod_batch_roll_id[{{ $i }}]" id="prod_batch_roll_id{{ $i }}" class="number integer" value="{{ $roll->id}}" readonly/>
    </td>
    <td width="150px">
      {{ $roll->sale_order_no }}
    </td>
    <td width="150px">
      {{ $roll->style_ref }}
    </td>
    <td width="150px">
      {{ $roll->buyer_name }}
    </td>

    <td width="70px" align="right">
      {{ $roll->batch_qty}}
      <input type="hidden" name="batch_qty[{{ $i  }}]" id="batch_qty{{ $i }}" class="number integer" disabled value="{{$roll->batch_qty}}" />
    </td>
     <td width="70px" >
    <input type="text" name="qty[{{ $i  }}]" id="qty{{ $i }}" class="number integer" onchange="MsProdBatchFinishQcRoll.calculate_reject_multi({{ $i }},{{ $loop->count}})" value="{{$roll->batch_qty}}" />
    </td>
    <td width="70px" >
    <input type="text" name="reject_qty[{{ $i  }}]" id="reject_qty{{ $i }}" class="number integer" value="0" readonly />
    </td>
    <td width="70px" >
    {!! Form::select("grade_id[$i]", $rollqcresult,'',array('id'=>'grade_id','onchange'=>"MsProdBatchFinishQcRoll.copyGrade(this.value, $i , $loop->count)")) !!}
    </td>
    <td width="60px">
    <input type="text" name="gsm_weight[{{ $i  }}]" id="gsm_weight{{ $i }}" class="number integer" onchange="MsProdBatchFinishQcRoll.copyGSM(this.value,{{ $i }},{{ $loop->count}})"  value="" style="background-color: #c0c0c0"/>
    </td>
    <td width="80px">
    <input type="text" name="dia_width[{{ $i }}]" id="dia_width{{ $i }}" onchange="MsProdBatchFinishQcRoll.copyDia(this.value,{{ $i }},{{ $loop->count}})" value="" style="background-color: #c0c0c0"/>
    </td>
    <td width="100px">
      {{ $roll->colorrange_name}}
    </td>
    <td width="100px">
      {{ $roll->dyeing_color}}
    </td>
    <td width="80px">
      {{ $roll->body_part}}
    </td>

    <td width="250px">
      {{ $roll->fabrication}}
    </td>
    <td width="70px">
       {{ $roll->fabric_shape}}
    </td>
    <td width="70px">
      {{ $roll->fabric_look}}
    </td>
    

  <td width="80px">{{ $roll->measurement}}</td>
  <td width="80px">{{ $roll->roll_length}}</td>
  <td width="80px">{{ $roll->stitch_length}}</td>
  <td width="80px">{{ $roll->shrink_per}}</td>
  <td width="80px">{{ $roll->dyetype}}</td>
  <td width="80px">{{ $roll->prod_no}}</td>
  <td width="80px">{{ $roll->gmt_sample}}</td>
  <td width="150px">{{ $roll->supplier_name}}</td>
  <td width="150px">{{ $roll->customer_name}}</td>
  <td width="">{{ $roll->yarndtl}}</td>
    </tr>
    <?php
    $i++;
    ?>
    @endforeach
  </tbody>
  <tfoot>
    <tr align="center">
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