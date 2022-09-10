<?php
$i=1;
?>

<table border="1" class="table_form">
<tr align="center">
<td width="350px">Yarn</td>
<td width="70px">PO. Qty</td>
<td width="60px">PO. Rate</td>
<td width="80px">PO. Amount</td>
<td width="80px">No Of Bag</td>
<td width="350px">Remarks</td>
</tr>
<tbody>
@foreach($rows as $colorsize)
<tr align="left">
<td width="350px">
{{ $colorsize->yarn_des }}
<input type="hidden" name="item_account_id[{{ $i }}]" id="item_account_id{{ $i }}" class="number integer" value="{{ $colorsize->item_account_id}}" readonly/>
<input type="hidden" name="po_yarn_id[{{ $i }}]" id="po_yarn_id{{ $i }}" class="number integer" value="{{ $colorsize->po_yarn_id}}" readonly/>
</td>
<td width="70px">
<input type="text" name="qty[{{ $i }}]" id="qty{{ $i }}" class="number integer" value="{{ $colorsize->balance_qty}}" onchange="MsPoYarnItem.calculateAmount({{ $i }},{{$loop->count}},'qty')"/>
</td>
<td>
<input type="text" name="rate[{{ $i  }}]" id="rate_{{ $i }}" class="number integer"  value="{{ $colorsize->rate}}" onchange="MsPoYarnItem.calculateAmount({{ $i }},{{$loop->count}},'rate')"/>
</td>
<td>
<input type="text" name="amount[{{ $i }}]" id="amount_{{ $i }}" class="number integer" value="{{ $colorsize->balance_amount}}" readonly/>
</td>
<td>
<input type="text" name="no_of_bag[{{ $i }}]" id="no_of_bag{{ $i }}" class="number integer" value="{{ $colorsize->no_of_bag}}"/>
</td>
<td>
<input type="text" name="remarks[{{ $i }}]" id="remarks{{ $i }}"  value="{{ $colorsize->remarks}}"/>
</td>
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
