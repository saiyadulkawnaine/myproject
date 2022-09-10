<form id="soaopfabricrcvinhrollmatrixFrm">
<table border="1">
    <tr align="center">
        <th width="120px">Roll No</th>
        <th width="100px">Custom No</th>
        <th width="80px">Style Ref</th>
        <th width="70px">Sales Order No</th>
        <th width="150px">Buyer</th>
        <th width="80px">Receive Qty</th>        
        <th width="80px">Room</th>        
        <th width="80px">Rack</th>        
        <th width="80px">Shelf</th>        
        <th width="100px">Remarks</th>
    </tr>
<tbody>
<?php 
$i=1;
?>
@foreach($prodknitqc as $item)
  <tr align="center">
    
    <td width="120px" align="left">
    <input type="hidden" name="prod_finish_dlv_roll_id[{{ $i }}]" id="prod_finish_dlv_roll_id{{ $i }}" value="{{ $item->id}}" readonly />
        {{ $item->prod_knit_item_roll_id }}
    </td>
    <td width="100px">{{ $item->custom_no }}</td>
    <td width="80px">{{ $item->style_ref }}</td>
    <td width="70px">{{ $item->sale_order_no }}</td>
    <td width="150px">{{ $item->buyer_name }}</td>
    <td>
        <input type="text" name="qty[{{ $i }}]" id="qty_{{ $i }}" class="number integer" value="{{$item->isu_qty}}" readonly />
    </td>
    <td>
        <input type="text" name="room[{{ $i }}]" id="room{{ $i }}"  onchange="MsSoAopFabricRcvInhRol.copyRoom(this.value,{{ $i }},{{ $loop->count}})" />
    </td>
    <td>
        <input type="text" name="rack[{{ $i }}]" id="rack{{ $i }}"  onchange="MsSoAopFabricRcvInhRol.copyRack(this.value,{{ $i }},{{ $loop->count}})" />
    </td>
    <td>
        <input type="text" name="shelf[{{ $i }}]" id="shelf{{ $i }}"  onchange="MsSoAopFabricRcvInhRol.copyShelf(this.value,{{ $i }},{{ $loop->count}})" />
    </td>
    
    <td>
        <input type="text" name="remarks[{{ $i }}]" id="remarks{{ $i }}" />
    </td>
  </tr>
<?php $i++; ?>
@endforeach
</tbody>
</table>
</form>
<script>
$('.integer').keyup(function () {
if (this.value != this.value.replace(/[^0-9\.]/g, '')) {
   this.value = this.value.replace(/[^0-9\.]/g, '');
}
});
</script>