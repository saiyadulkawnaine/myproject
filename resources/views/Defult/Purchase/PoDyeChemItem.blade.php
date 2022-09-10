<?php
$i=1;
?>

<table border="1" class="table_form">
<tr align="center">
<td width="70px">Req. No</td>
<td width="80px">Category</td>
<td width="80px">Item Class</td>
<td width="80px">Sub Class</td>
<td width="120px">Item Description</td>
<td width="70px">Specification</td>
<td width="40px">UOM</td>
<td width="80px">Req.Qty</td>
<td width="80px">Req. Rate</td>
<td width="80px">Req. Amount</td>
<td width="80px">Prev.PO.Qty</td>
<td width="80px">Balance.Qty</td>
<td width="80px">PO.Qty</td>
<td width="80px">PO.Rate</td>
<td width="80px">Amount</td>
<td width="120px">Remarks</td>
</tr>
<tbody>
@foreach($rows as $row)
<tr align="left">
<td>
{{ $row->requisition_no }}
<input type="hidden" name="inv_pur_req_item_id[{{ $i }}]" id="inv_pur_req_item_id{{ $i }}" class="number integer" value="{{ $row->inv_pur_req_item_id}}" readonly/>
<input type="hidden" name="po_dye_chem_id[{{ $i }}]" id="po_dye_chem_id{{ $i }}" class="number integer" value="{{ $row->po_dye_chem_id}}" readonly/>
</td>
<td>
  {{ $row->category_name }}
</td>
<td>
{{ $row->class_name }}
</td>
<td>
{{ $row->sub_class_name }}
</td>
<td>
{{ $row->item_description }}
</td>
<td>
{{ $row->specification }}
</td>
<td>
{{ $row->uom_name }}
</td>
<td width="70px">
<input type="text" name="req_qty[{{ $i }}]" id="req_qty{{ $i }}" class="number integer" value="{{ $row->req_qty}}" readonly />
</td>
<td>
<input type="text" name="req_rate[{{ $i  }}]" id="req_rate{{ $i }}" class="number integer"  value="{{ $row->req_rate}}" readonly/>
</td>
<td>
<input type="text" name="req_amount[{{ $i }}]" id="req_amount{{ $i }}" class="number integer" value="{{ $row->req_amount}}" readonly/>
</td>

<td>
<input type="text" name="prev_po_qty[{{ $i  }}]" id="prev_po_qty{{ $i }}" class="number integer"  value="{{ $row->prev_po_qty}}" readonly/>
</td>
<td>
<input type="text" name="balance_qty[{{ $i }}]" id="balance_qty{{ $i }}" class="number integer" value="{{ $row->balance_qty}}" readonly/>
</td>

<td width="70px">
<input type="text" name="qty[{{ $i }}]" id="qty{{ $i }}" class="number integer" value="" onchange="MsPoDyeChemItem.calculateAmount({{ $i }},{{$loop->count}},'qty')"/>
</td>
<td>
<input type="text" name="rate[{{ $i  }}]" id="rate_{{ $i }}" class="number integer"  value="{{ $row->rate}}" onchange="MsPoDyeChemItem.calculateAmount({{ $i }},{{$loop->count}},'rate')"/>
</td>
<td>
<input type="text" name="amount[{{ $i }}]" id="amount_{{ $i }}" class="number integer" value="" readonly/>
</td>

<td>
<input type="text" name="remarks[{{ $i }}]" id="remarks{{ $i }}" class="number integer" value="{{ $row->remarks}}" readonly/>
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
