<form id="soembprintrcvitemmatrixFrm">
 <table border="1" style="margin: 0 auto">
  <tr align="center">
   <th width="100">Buyer</th>
   <th width="100">Style Ref</th>
   <th width="100">Sales Order No</th>
   <th width="80">Emb. Name</th>
   <th width="80">Emb. Type</th>
   <th width="80">Emb. Size</th>
   <th width="100">GMT Item</th>
   <th width="100">GMT Part</th>
   <th width="100">GMT Color</th>
   <th width="80">GMT Size</th>
   <th width="80">So Qty</th>
   <th width="100"> Receive Qty</th>
   <th width="40">Uom</th>
   <th width="60">Rate</th>
   <th width="80">Amount</th>
   <th width="80">Delivery Date</th>
   <th width="150px">Remarks</th>
  </tr>
  <tbody>
   <?php 
        $i=1;
        ?>
   @foreach($items as $item)
   <tr>
    <td width="100">
     <input type="hidden" name="so_emb_ref_id[{{ $i }}]" id="so_emb_ref_id_{{ $i }}"
      value="{{ $item->so_emb_ref_id}}" />
     {{ $item->buyer_name }}
    </td>
    <td width="100">{{ $item->style_ref }}</td>
    <td width="100">{{ $item->sale_order_no }}</td>
    <td width="80">{{ $item->emb_name }}</td>
    <td width="80">{{ $item->emb_type }}</td>
    <td width="80">{{ $item->emb_size }}</td>
    <td width="100">{{ $item->item_desc }}</td>
    <td width="100">{{ $item->gmtspart }}</td>
    <td width="100">{{ $item->gmt_color }}</td>
    <td width="80">{{ $item->gmt_size }}</td>
    <td width="80">{{ $item->so_emb_item_qty }}</td>
    <td width="100"><input type="text" name="qty[{{ $i  }}]" id="qty_{{ $i }}" class="number integer" /></td>
    <td width="40">{{ $item->uom_name }}</td>
    <td width="60">{{ $item->rate }}</td>
    <td width="80">{{ $item->amount }}</td>
    <td width="80">{{ $item->delivery_date }}</td>
    <td width="150px"><input type="text" name="remarks[{{ $i }}]" id="remarks_{{ $i }}" /></td>
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