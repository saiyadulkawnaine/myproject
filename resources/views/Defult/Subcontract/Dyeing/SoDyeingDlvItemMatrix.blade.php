<form id="sodyeingdlvitemmatrixFrm">
<table border="1">
    <tr align="center">
        <th width="250px">Fabric Description</th>
        <th width="100px">Fabric Looks</th>
        <th width="80px">Fabric Shape</th>
        <th width="70px">GSM</th>
        <th width="70px">Dyeing Color</th>
        <th width="70px">Color Range</th>
        <th width="70px">Dye Type</th>
        <th width="70px">UOM</th>
        <th width="80px">Batch No</th>
        <th width="80px">Processe Used</th>
        <th width="70px">Fin. Dia</th>
        <th width="70px">Fin. GSM</th>
        <th width="80px">Dlv Qty</th>
        <th width="70px">Grey Used</th>
        <th width="70px">Rate</th>
        <th width="80px">Amount</th>
        <th width="80px">No of Roll</th>
        <th width="100px">Remarks</th>
    </tr>
<tbody>
<?php 
$i=1;
?>
@foreach($items as $item)
  <tr align="center">
    
    <td width="250px" align="left">
    <input type="hidden" name="so_dyeing_ref_id[{{ $i }}]" id="so_dyeing_ref_id{{ $i }}" value="{{ $item->so_dyeing_ref_id}}"/>
    <input type="hidden" name="so_dyeing_fabric_rcv_item_id[{{ $i }}]" id="so_dyeing_fabric_rcv_item_id{{ $i }}" value="{{ $item->so_dyeing_fabric_rcv_item_id}}"/>
        {{ $item->fabrication }}
    </td>
    <td width="100px">{{ $item->fabriclooks }}</td>
    <td width="80px">{{ $item->fabricshape }}</td>
    <td width="70px">{{ $item->gsm_weight }}</td>
    <td width="70px">{{ $item->dyeing_color }}</td>
    <td width="70px">{{ $item->colorrange_id }}</td>
    <td width="70px">{{ $item->dyetype }}</td>
    <td width="70px">{{ $item->uom_code }}</td>
    <td>
        <input type="text" name="batch_no[{{ $i }}]" id="batch_no{{ $i }}" />
    </td>
    <td>
        <input type="text" name="process_name[{{ $i }}]" id="process_name{{ $i }}" />
    </td>
     <td>
        <input type="text" name="fin_dia[{{ $i }}]" id="fin_dia{{ $i }}" />
    </td>
    <td>
        <input type="text" name="fin_gsm[{{ $i }}]" id="fin_gsm{{ $i }}" class="number integer"/>
    </td>
    <td>
        <input type="text" name="qty[{{ $i }}]" id="qty_{{ $i }}" class="number integer"  />
    </td>
    <td>
        <input type="text" name="grey_used[{{ $i }}]" id="grey_used{{ $i }}" class="number integer" onchange="MsSoDyeingDlvItem.calculate({{ $i }},{{ $loop->count}})"/>
    </td>
    <td>
        <input type="text" name="rate[{{ $i  }}]" id="rate_{{ $i }}" class="number integer" onchange="MsSoDyeingDlvItem.calculate({{ $i }},{{ $loop->count}})" value="{{$item->rate}}" />
    </td>
    <td>
        <input type="text" name="amount[{{ $i }}]" id="amount_{{ $i }}" class="number"  readonly/>
    </td>
     <td>
        <input type="text" name="no_of_roll[{{ $i }}]" id="no_of_roll{{ $i }}" class="number integer" value="{{ $item->no_of_roll }}"/>
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