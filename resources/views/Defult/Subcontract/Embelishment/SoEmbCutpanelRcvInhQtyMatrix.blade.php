<?php
        $i=1;
        //$ordQty=0;
        //$reqQty=0;
        //$bomQty=0;
        //$amontQty=0;
?>
@if($colorsizes->isNotEmpty())
<p>New Gmt Color Sewing</p>
<table border="1">
    <tr align="center">
        <th width="200px"  class="text-center">GMT Item</th>
        <th width="200px"  class="text-center">GMT Color</th>
        <th width="80px"  class="text-center">Size</th>
        <th width="80px"  class="text-center">Body Part</th>
        <th width="80px"  class="text-center">Required<br/> Qty</th>
        <th width="80px"  class="text-center">Total<br/>Rcv Qty</th>
        <th width="80px"  class="text-center">Balance<br/> Qty</th>
        <th width="80px"  class="text-center">Current<br/>Rcv Qty</th>
        <th width="40px"  class="text-center">Reject Qty</th>
    </tr>
    <tbody>
        
        @foreach($colorsizes as $colorsize)
         <tr align="center">
        <td width="200px">
            {{ $colorsize->item_description }}
            <input type="hidden" name="sales_order_gmt_color_size_id[{{ $i }}]" id="sales_order_gmt_color_size_id{{ $i }}" value="{{ $colorsize->sales_order_gmt_color_size_id }}"/>
        </td>
        <td width="200px">{{ $colorsize->color_name }}</td>
        <td width="80px">{{ $colorsize->size_name }}</td>
        <td width="80px" align="right">{{ $colorsize->gmtspart_name }}</td>
        <td width="80px" align="right">{{ $colorsize->req_qty }}</td>
        <td width="80px" align="right">{{ $colorsize->cumulative_qty }}</td>
        <td width="80px" align="right">{{ $colorsize->balance_qty }}</td>
        <td width="80px"><input type="text" name="qty[{{ $i }}]" id="qty{{ $i }}" value="" class="number integer"/></td>
        <td width="40px"><input type="text" name="reject_qty[{{ $i }}]" id="reject_qty{{ $i }}" value="" class="number integer"/></td>
    </tr>
        <?php
            $i++;
        ?>
        @endforeach
    </tbody>
</table>
@endif
@if($saved->isNotEmpty())
<br/>
<p>Saved Gmt Color Sewing</p>
<table border="1">
    <tr align="center">
        <th width="200px"  class="text-center">GMT Item</th>
        <th width="200px"  class="text-center">GMT Color</th>
        <th width="100px"  class="text-center">Size</th>
        <th width="80px"  class="text-center">Body Part</th>
        <th width="80px"  class="text-center">Required<br/> Qty</th>
        <th width="80px"  class="text-center">Total<br/>Rcv Qty</th>
        <th width="80px"  class="text-center">Balance<br/> Qty</th>
        <th width="80px"  class="text-center">Current<br/>Rcv Qty</th>
        <th width="40px"  class="text-center">Reject Qty</th>
        <th width="80px"  class="text-center"></th>
    </tr>
    <tbody>
        <?php
        //$i=1;
        //$ordQty=0;
        //$reqQty=0;
        //$bomQty=0;
        //$amontQty=0;
        ?>
        @foreach($saved as $colorsize)
        <tr align="center">
            <td width="200px">
                {{ $colorsize->item_description }}
                <input type="hidden" name="sales_order_gmt_color_size_id[{{ $i }}]" id="sales_order_gmt_color_size_id{{ $i }}" value="{{ $colorsize->sales_order_gmt_color_size_id }}"/>
            </td>
            <td width="200px">{{ $colorsize->color_name }}</td>
            <td width="80px">{{ $colorsize->size_name }}</td>
            <td width="80px" align="right">{{ $colorsize->gmtspart_name }}</td>
            <td width="80px" align="right">{{ $colorsize->req_qty }}</td>
            <td width="80px" align="right">{{ $colorsize->cumulative_qty_saved }}</td>
            <td width="80px" align="right">{{ $colorsize->balance_qty_saved }}</td>
            <td width="80px"><input type="text" name="qty[{{ $i }}]" id="qty{{ $i }}" value="{{ $colorsize->qty }}" class="number integer"/></td>
            <td width="40px"><input type="text" name="reject_qty[{{ $i }}]" id="reject_qty{{ $i }}" value="{{ $colorsize->reject_qty }}" class="number integer"/></td>
            <td width="80px"><a href="javascript:void(0)" onclick="MsSoEmbCutpanelRcvInhQty.delete(event,{{ $colorsize->so_emb_cutpanel_rcv_qty_id }})">Remove</a></td>
        </tr>
        <?php
            $i++;
        ?>
        @endforeach
    </tbody>
</table>
@endif
<script>
$('.integer').keyup(function () {
    if (this.value != this.value.replace(/[^0-9\.]/g, '')) {
       this.value = this.value.replace(/[^0-9\.]/g, '');
    }
});
</script>