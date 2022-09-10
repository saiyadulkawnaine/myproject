<table bitem="1">
    <tr align="center">
        <th width="40">Id</th>
        <th width="100">Category</th>
        <th width="100">Item Class</th>
        <td width="250px">Item Description</td>
        <td width="80px">Qty</td>
        <td width="70px">Rate</td>
        <td width="80px">Amount</td>
    </tr>
<tbody>
<?php 
$i=1;
?>
@foreach($items as $item)
  <tr align="center">
    <td width="40px">
        <input type="hidden" name="item_account_id[{{ $i }}]" id="item_account_id{{ $i }}" value="{{ $item->id}}"/>
    </td>
    <td width="100px">{{ $item->name }}</td>
    <td width="100px">{{ $item->class_name }}</td>
    <td width="250px">
        {{ $item->item_description }}
    </td>
    <td>
        <input type="text" name="qty[{{ $i }}]" id="qty_{{ $i }}" class="number integer" onchange="MsInvPurReqItem.calculate({{ $i }},{{ $loop->count}})" value="{{ $item->qty }}"/>
    </td>
    <td>
        <input type="text" name="rate[{{ $i  }}]" id="rate_{{ $i }}" class="number integer" value="{{ $item->rate }}" onchange="MsInvPurReqItem.calculate({{ $i }},{{ $loop->count}})" />
    </td>
    <td>
        <input type="text" name="amount[{{ $i }}]" id="amount_{{ $i }}" class="number" value="{{ $item->amount }}" readonly/>
    </td>
  </tr>
<?php $i++; ?>
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