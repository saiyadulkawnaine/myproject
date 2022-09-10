<form id="soaopfabricrcvitemmatrixFrm">
<table border="1" style="margin: 0 auto">
    <tr align="center">
        <th width="300px">Fabric Description</th>
        <th width="100px">Fabric Looks</th>
        <th width="80px">Fabric Shape</th>
        <th width="70px">GSM</th>
        <th width="100px">Aop Color</th>
        <th width="100px">Color Range</th>
        <th width="70px">UOM</th>
        <!-- <th width="80px">Receive Qty</th> -->
        <th width="100px">Recovery Rate</th>
        <!-- <th width="80px">Amount</th> -->
        <th width="100px">Process Loss %</th>
        <th width="100px">Real Rate/ Unit</th>
        <th width="100px">Yarn Desc.</th>
        <th width="100px">Remarks</th>
    </tr>
<tbody>
<?php 
$i=1;
?>
@foreach($items as $item)
  <tr align="center">
    
    <td width="300px" align="left">
    <input type="hidden" name="so_aop_ref_id[{{ $i }}]" id="so_aop_ref_id{{ $i }}" value="{{ $item->so_aop_ref_id}}"/>
        {{ $item->fabrication }}
    </td>
    <td width="100px">{{ $item->fabriclooks }}</td>
    <td width="80px">{{ $item->fabricshape }}</td>
    <td width="70px">{{ $item->gsm_weight }}</td>
    <td width="100px">{{ $item->aop_color }}</td>
    <td width="100px">{{ $item->colorrange_id }}</td>
    <td width="70px">{{ $item->uom_code }}</td>
    <!-- <td>
        <input type="text" name="qty[{{ $i }}]" id="qty_{{ $i }}" class="number integer" onchange="MsSoAopFabricRcvItem.calculate({{ $i }},{{ $loop->count}})" />
    </td> -->
    <td>
        <input type="text" name="rate[{{ $i  }}]" id="rate_{{ $i }}" class="number integer" onchange="MsSoAopFabricRcvItem.calculate({{ $i }},{{ $loop->count}})" />
    </td>
    <!-- <td>
        <input type="text" name="amount[{{ $i }}]" id="amount_{{ $i }}" class="number"  readonly/>
    </td> -->
     <td>
        <input type="text" name="process_loss_per[{{ $i }}]" id="process_loss_per{{ $i }}" class="number" value="{{ $item->process_loss_per }}"/>
    </td>
     <td>
        <input type="text" name="real_rate[{{ $i }}]" id="real_rate{{ $i }}" class="number" value="{{ $item->real_rate }}"/>
    </td>
    <td>
        <input type="text" name="yarn_des[{{ $i }}]" id="yarn_des{{ $i }}" />
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