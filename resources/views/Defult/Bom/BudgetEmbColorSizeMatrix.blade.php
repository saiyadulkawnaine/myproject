<?php
$i=1;
?>
<table border="1" class="table_form">
  <tr align="center">
  <td colspan="13">
    <input type="checkbox" name="is_copy" id="is_copy" checked/>Copy
  </td>
  </tr>
</table>
@if($colorsizes->isNotEmpty())
<table border="1" class="table_form">
  <caption>New</caption>
  <tr align="center">
  <td colspan="2"></td>
  <td colspan="3">Color</td>
  <td colspan="3">Size</td>
  <td colspan="5">
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
  <td width="70px">Order Qty</td>
  <td width="70px">Req Ratio*</td>
  <td width="70px">Req. GMT Qty*</td>
  <td width="70px">Rate</td>
  <td width="80px">Amount</td>
  </tr>
  <tbody>
      <?php
      $ordTot=0;
      $reqTot=0;
      $amtTot=0;
      ?>
      @foreach($colorsizes as $colorsize)
      <?php
      $ordTot+=$colorsize->plan_cut_qty;
      $reqTot+=$colorsize->smp_req_cons;
      $amtTot+=$colorsize->smp_amount;
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
      <input type="hidden" name="budget_emb_id[{{ $i }}]" id="budget_emb_id_{{ $i }}" value="{{ $colorsize->budget_emb_id }}"/>
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
      <td width="70px">
      <input type="text" name="plun_cut_qty[{{ $i }}]" id="plun_cut_qty{{ $i }}" class="number integer"  value="{{ $colorsize->plan_cut_qty}}" readonly/>
      </td>
      <td width="70px">
      <input type="text" name="cons[{{ $i }}]" id="cons_{{ $i }}" class="number integer" onchange="MsBudgetEmbCon.calculate({{ $i }},{{ $loop->count}},'cons')" value="{{ $colorsize->smp_cons}}"/>
      </td>
      <td width="70px">
      <input type="text" name="req_cons[{{ $i }}]" id="req_cons_{{ $i }}" class="number integer" onchange="MsBudgetEmbCon.calculate({{ $i }},{{ $loop->count}},'req_cons')" value="{{ $colorsize->smp_req_cons}}" readonly/>
      </td>
      <td>
      <input type="text" name="rate[{{ $i  }}]" id="rate_{{ $i }}" class="number integer" onchange="MsBudgetEmbCon.calculate({{ $i }},{{$loop->count}},'rate')" value="{{ $colorsize->smp_rate}}"/>
      <input type="hidden" name="overhead_rate[{{ $i  }}]" id="overhead_rate{{ $i }}" class="number integer"  value="{{ $colorsize->overhead_rate}}"/>
      </td>
      <td>
      <input type="text" name="amount[{{ $i }}]" id="amount_{{ $i }}" class="number" value="{{ $colorsize->smp_amount}}" readonly/>
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
    <td width="70px" align="right">{{  number_format($ordTot,0,'.',',') }}</td>
    <td width="70px"></td>
    <td width="70px" align="right">{{  number_format($reqTot,0,'.',',') }}</td>
    <td width="70px"></td>
    <td width="80px" align="right">{{  number_format($amtTot,4,'.',',') }}</td>
    </tr>
  </tfoot>
</table>
@endif
@if($saved->isNotEmpty())
<table border="1" class="table_form">
  <caption>Saved</caption>
  <tr align="center">
  <td colspan="2"></td>
  <td colspan="3">Color</td>
  <td colspan="3">Size</td>
  <td colspan="5">
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
  <td width="70px">Order Qty</td>
  <td width="70px">Req Ratio*</td>
  <td width="70px">Req. GMT Qty*</td>
  <td width="70px">Rate</td>
  <td width="80px">Amount</td>
  </tr>
  <tbody>
    <?php
    $ordTot=0;
    $reqTot=0;
    $amtTot=0;
    ?>
    @foreach($saved as $colorsize)
    <?php
    $ordTot+=$colorsize->plan_cut_qty;
    $reqTot+=$colorsize->req_cons;
    $amtTot+=$colorsize->amount;
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
    <input type="hidden" name="budget_emb_id[{{ $i }}]" id="budget_emb_id_{{ $i }}" value="{{ $colorsize->budget_emb_id }}"/>
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



    <td width="70px">
    <input type="text" name="plun_cut_qty[{{ $i }}]" id="plun_cut_qty{{ $i }}" class="number integer"  value="{{ $colorsize->plan_cut_qty}}" readonly/>
    </td>
    <td width="70px">
    <input type="text" name="cons[{{ $i }}]" id="cons_{{ $i }}" class="number integer" onchange="MsBudgetEmbCon.calculate({{ $i }},{{ $loop->count}},'cons')" value="{{ $colorsize->cons}}"/>
    </td>

    <td width="70px">
    <input type="text" name="req_cons[{{ $i }}]" id="req_cons_{{ $i }}" class="number integer" onchange="MsBudgetEmbCon.calculate({{ $i }},{{ $loop->count}},'req_cons')" value="{{ $colorsize->req_cons}}" readonly/>
    </td>

    <td>
    <input type="text" name="rate[{{ $i  }}]" id="rate_{{ $i }}" class="number integer" onchange="MsBudgetEmbCon.calculate({{ $i }},{{$loop->count}},'rate')" value="{{ $colorsize->rate}}"/>
    <input type="hidden" name="overhead_rate[{{ $i  }}]" id="overhead_rate{{ $i }}" class="number integer"  value="{{ $colorsize->overhead_rate}}"/>
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
  <td width="70px" align="right">{{  number_format($ordTot,0,'.',',') }}</td>
  <td width="70px"></td>
  <td width="70px" align="right">{{  number_format($reqTot,0,'.',',') }}</td>
  <td width="70px"></td>
  <td width="80px" align="right">{{  number_format($amtTot,4,'.',',') }}</td>
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
