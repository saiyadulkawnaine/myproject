<?php
$i=1;
?>
@if($new->isNotEmpty())
<table border="1" class="table_form">
  <caption>New</caption>
  <tr align="center">
  <td width="100px">Sales Order</td>
  <td width="100px">Country</td>
  <td width="100px">GMT.Color</td>
  <td width="100px">GMT.Size</td>
  <td width="100px">Emb.Name</td>
  <td width="100px">Emb.Type</td>
  <td width="100px">GMT.Part</td>
  <td width="100px">Emb.Size</td>
  <td width="70px">Bom.Qty</td>
  <td width="70px">Bom.Ratio</td>
  <td width="70px">Bom.Rate/Dzn</td>
  <td width="70px">Bom.Amount</td>
  <td width="70px">Prev. Wo. Qty</td>
  <td width="70px">Bal. Wo. Qty</td>
  <td width="70px">Wo. Qty</td>
  <td width="70px">Rate/Dzn</td>
  <td width="80px">Amount</td>
  <td width="100px">Remarks</td>
  </tr>
  <tbody>
      <?php
      $bom_qty=0;
      $bom_amount=0;
      $qty=0;
      $amount=0;
      ?>
      @foreach($new as $colorsize)
      <?php
      $bom_qty+=$colorsize->bom_qty;
      $bom_amount+=$colorsize->bom_amount;
      $qty+=$colorsize->qty;
      $amount+=$colorsize->amount;
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
      <input type="hidden" name="budget_emb_con_id[{{ $i }}]" id="budget_emb_con_id{{ $i }}" value="{{ $colorsize->budget_emb_con_id }}"/>
      <input type="hidden" name="po_emb_service_item_id[{{ $i }}]" id="po_emb_service_item_id{{ $i }}" value="{{ $colorsize->po_emb_service_item_id }}"/>
      </td>
      <td width="100px">
      {{ $colorsize->size_name }}
      </td>
      <td width="100px">
      {{ $colorsize->embelishment_name }}
      </td>
      <td width="100px">
      {{ $colorsize->embelishment_type }}
      </td>
      <td width="100px">
      {{ $colorsize->gmtspart_name }}
      </td>
      <td width="100px">
      {{ $colorsize->embelishment_size }}
      </td>
      <td width="70px">
      <input type="text" name="bom_qty[{{ $i }}]" id="bom_qty{{ $i }}" class="number integer"  value="{{ $colorsize->bom_qty}}" readonly/>
      </td>
       <td width="70px">
      <input type="text" name="bom_ratio[{{ $i }}]" id="bom_ratio{{ $i }}" class="number integer"  value="{{ $colorsize->bom_ratio}}" readonly/>
      </td>
      <td width="70px">
      <input type="text" name="bom_rate[{{ $i }}]" id="bom_rate{{ $i }}" class="number integer"  value="{{ $colorsize->bom_rate}}" readonly/>
      </td>
      <td width="70px">
      <input type="text" name="bom_amount[{{ $i }}]" id="bom_amount{{ $i }}" class="number integer" value="{{ $colorsize->bom_amount}}" readonly/>
      </td>
      <td>
      <input type="text" name="prev_po_qty[{{ $i  }}]" id="prev_po_qty{{ $i }}" class="number integer"  value="{{ $colorsize->prev_po_qty}}" readonly/>
      </td>

      <td>
      <input type="text" name="balance_qty[{{ $i  }}]" id="balance_qty{{ $i }}" class="number integer"  value="{{ $colorsize->balance_qty}}" readonly/>
      </td>

      <td>
      <input type="text" name="qty[{{ $i  }}]" id="qty{{ $i }}" class="number integer" onchange="MsPoEmbServiceItemQty.calculateAmount({{ $i }},{{$loop->count}},'qty')" value="{{ $colorsize->balance_qty}}"/>
      </td>

      <td>
      <input type="text" name="rate[{{ $i  }}]" id="rate_{{ $i }}" class="number integer" onchange="MsPoEmbServiceItemQty.calculateAmount({{ $i }},{{$loop->count}},'rate')" value="{{ $colorsize->bom_rate}}"/>
      </td>

      <td>
      <input type="text" name="amount[{{ $i }}]" id="amount_{{ $i }}" class="number" value="{{ $colorsize->bom_amount}}" readonly/>
      </td>
      <td>
      <input type="text" name="remarks[{{ $i }}]" id="remarks{{ $i }}"  value="{{ $colorsize->remarks}}" onchange="MsPoEmbServiceItemQty.copyRemarks({{ $i }},{{$loop->count}})"/>
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
    <td width="100px"></td>
    <td width="100px"></td>
    <td width="100px"></td>
    <td width="100px"></td>
    <td width="70px" align="right">{{  number_format($bom_qty,0,'.',',') }}</td>
    <td width="70px"></td>
    <td width="70px"></td>
    <td width="70px" align="right">{{  number_format($bom_amount,4,'.',',') }}</td>
    <td width="70px"></td>
    <td width="70px"></td>
    <td width="70px">{{  number_format($qty,0,'.',',') }}</td>
    <td width="70px">{{  number_format($amount,4,'.',',') }}</td>
    <td width="80px" align="right"></td>
    <td width="100px" align="right"></td>
    </tr>
  </tfoot>
</table>
<br/>
<br/>
<br/>
@endif

@if($colorsizes->isNotEmpty())
<table border="1" class="table_form">
  <caption>Saved</caption>
  <tr align="center">
  <td width="100px">Sales Order</td>
  <td width="100px">Country</td>
  <td width="100px">GMT.Color</td>
  <td width="100px">GMT.Size</td>
  <td width="100px">Emb.Name</td>
  <td width="100px">Emb.Type</td>
  <td width="100px">GMT.Part</td>
  <td width="100px">Emb.Size</td>
  <td width="70px">Bom.Qty</td>
  <td width="70px">Bom.Ratio</td>
  <td width="70px">Bom.Rate/Dzn</td>
  <td width="70px">Bom.Amount</td>
  <td width="70px">Prev. Wo. Qty</td>
  <td width="70px">Bal. Wo. Qty</td>
  <td width="70px">Wo. Qty</td>
  <td width="70px">Rate/Dzn</td>
  <td width="80px">Amount</td>
  <td width="100px">Remarks</td>
  </tr>
  <tbody>
      <?php
      $bom_qty=0;
      $bom_amount=0;
      $qty=0;
      $amount=0;
      ?>
      @foreach($colorsizes as $colorsize)
      <?php
      $bom_qty+=$colorsize->bom_qty;
      $bom_amount+=$colorsize->bom_amount;
      $qty+=$colorsize->qty;
      $amount+=$colorsize->amount;
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
      <input type="hidden" name="budget_emb_con_id[{{ $i }}]" id="budget_emb_con_id{{ $i }}" value="{{ $colorsize->budget_emb_con_id }}"/>
      <input type="hidden" name="po_emb_service_item_id[{{ $i }}]" id="po_emb_service_item_id{{ $i }}" value="{{ $colorsize->po_emb_service_item_id }}"/>

      </td>
      <td width="100px">
      {{ $colorsize->size_name }}
      </td>
      <td width="100px">
      {{ $colorsize->embelishment_name }}
      </td>
      <td width="100px">
      {{ $colorsize->embelishment_type }}
      </td>
      <td width="100px">
      {{ $colorsize->gmtspart_name }}
      </td>
      <td width="100px">
      {{ $colorsize->embelishment_size }}
      </td>
      <td width="70px">
      <input type="text" name="bom_qty[{{ $i }}]" id="bom_qty{{ $i }}" class="number integer"  value="{{ $colorsize->bom_qty}}" readonly/>
      </td>
       <td width="70px">
      <input type="text" name="bom_ratio[{{ $i }}]" id="bom_ratio{{ $i }}" class="number integer"  value="{{ $colorsize->bom_ratio}}" readonly/>
      </td>
      <td width="70px">
      <input type="text" name="bom_rate[{{ $i }}]" id="bom_rate{{ $i }}" class="number integer"  value="{{ $colorsize->bom_rate}}" readonly/>
      </td>
      <td width="70px">
      <input type="text" name="bom_amount[{{ $i }}]" id="bom_amount{{ $i }}" class="number integer" value="{{ $colorsize->bom_amount}}" readonly/>
      </td>
      <td>
      <input type="text" name="prev_po_qty[{{ $i  }}]" id="prev_po_qty{{ $i }}" class="number integer"  value="{{ $colorsize->prev_po_qty}}" readonly/>
      </td>

      <td>
      <input type="text" name="balance_qty[{{ $i  }}]" id="balance_qty{{ $i }}" class="number integer"  value="{{ $colorsize->balance_qty}}" readonly/>
      </td>

      <td>
      <input type="text" name="qty[{{ $i  }}]" id="qty{{ $i }}" class="number integer" onchange="MsPoEmbServiceItemQty.calculateAmount({{ $i }},{{$loop->count}},'qty')" value="{{ $colorsize->qty}}"/>
      </td>

      <td>
      <input type="text" name="rate[{{ $i  }}]" id="rate_{{ $i }}" class="number integer" onchange="MsPoEmbServiceItemQty.calculateAmount({{ $i }},{{$loop->count}},'rate')" value="{{ $colorsize->rate}}"/>
      </td>

      <td>
      <input type="text" name="amount[{{ $i }}]" id="amount_{{ $i }}" class="number" value="{{ $colorsize->amount}}" readonly/>
      </td>
      <td>
      <input type="text" name="remarks[{{ $i }}]" id="remarks{{ $i }}"  value="{{ $colorsize->remarks}}" onchange="MsPoEmbServiceItemQty.copyRemarks({{ $i }},{{$loop->count}})"/>
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
    <td width="100px"></td>
    <td width="100px"></td>
    <td width="100px"></td>
    <td width="100px"></td>
    <td width="70px" align="right">{{  number_format($bom_qty,0,'.',',') }}</td>
    <td width="70px"></td>
    <td width="70px"></td>
    <td width="70px" align="right">{{  number_format($bom_amount,4,'.',',') }}</td>
    <td width="70px"></td>
    <td width="70px"></td>
    <td width="70px">{{  number_format($qty,0,'.',',') }}</td>
    <td width="70px">{{  number_format($amount,4,'.',',') }}</td>
    <td width="80px" align="right"></td>
    <td width="100px" align="right"></td>
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
