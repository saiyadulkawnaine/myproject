<?php
        $i=1;
?>
@if($colorsizes->isNotEmpty())
<p>New Cut Panel Received</p>
<table border="1">
    <tr align="center">
        <th width="100px"  class="text-center">Order No</th>
        <th width="100px"  class="text-center">Country</th>
        <th width="200px"  class="text-center">GMT Item</th>
        <th width="200px"  class="text-center">GMT Color</th>
        <th width="80px"  class="text-center">Size</th>
        <th width="80px"  class="text-center">Order Qty</th>
        <th width="80px"  class="text-center">Total <br/>Receive Qty</th>
        <th width="80px"  class="text-center">Current <br/>Receive Qty</th>
        <th width="100px"  class="text-center">Beneficiary</th>
        <th width="80px"  class="text-center">Buyer</th>
        <th width="80px"  class="text-center">Style Ref</th>
        <th width="80px"  class="text-center">Ship Date</th>
    </tr>
    <tbody>    
        @foreach($colorsizes as $colorsize)

        <tr align="center">
            <td width="100px"  class="text-center">{{ $colorsize->sale_order_no }}</td>
            <td width="100px"  class="text-center">{{ $colorsize->country_id }}</td>    
            <td width="200px">
                {{ $colorsize->item_description }}
                {{-- <input type="hidden" name="sales_order_gmt_color_size_id[{{ $i}}]" id="sales_order_gmt_color_size_id{{ $i}}" value="{{ $colorsize->sales_order_gmt_color_size_id }}"> --}}
                <input type="hidden" name="prod_gmt_dlv_input_qty_id[{{ $i }}]" id="prod_gmt_dlv_input_qty_id{{ $i }}" value="{{ $colorsize->prod_gmt_dlv_input_qty_id }}"/>
            </td>
            <td width="200px">{{ $colorsize->color_name }}</td>
            <td width="80px">{{ $colorsize->size_name }}</td>
            <td width="80px" align="right">{{ $colorsize->plan_cut_qty }}</td>
            <td width="80px" align="right">{{ $colorsize->total_receive_qty }}</td>
            <td width="80px"><input type="text" name="qty[{{ $i }}]" id="qty{{ $i }}" value="{{$colorsize->input_qty}}" class="number integer"/></td>
            <td width="100px"  class="text-center">{{ $colorsize->company_id }}</td>
            <td width="80px"  class="text-center">{{ $colorsize->buyer_name }}</td>
            <td width="80px"  class="text-center">{{ $colorsize->style_ref }}</td>
            <td width="80px"  class="text-center">{{ $colorsize->ship_date }}</td>
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
<p>Saved Cut Panel Received</p>
<table border="1">
    <tr align="center">
        <th width="100px"  class="text-center">Order No</th>
        <th width="100px"  class="text-center">Country</th>
        <th width="200px"  class="text-center">GMT Item</th>
        <th width="200px"  class="text-center">GMT Color</th>
        <th width="100px"  class="text-center">Size</th>
        <th width="80px"  class="text-center">Order Qty</th>
        <th width="80px"  class="text-center">Total <br/>Receive Qty</th>
        <th width="80px"  class="text-center">Current <br/>Receive Qty</th>
        <th width="100px"  class="text-center">Beneficiary</th>
        <th width="80px"  class="text-center">Buyer</th>
        <th width="80px"  class="text-center">Style Ref</th>
        <th width="80px"  class="text-center">Ship Date</th>
    </tr>
    <tbody>
        <?php

        ?>
        @foreach($saved as $colorsize)
        <tr align="center">
            <td width="100px" class="text-center">{{ $colorsize->sale_order_no }}</td>
            <td width="100px" class="text-center">{{ $colorsize->country_id }}</td>
            <td width="200px">
                {{ $colorsize->item_description }}
                {{-- <input type="hidden" name="id[{{ $i}}]" id="id_{{ $i}}" value=""> --}}
                <input type="hidden" name="prod_gmt_dlv_input_qty_id[{{ $i }}]" id="prod_gmt_dlv_input_qty_id{{ $i }}" value="{{ $colorsize->prod_gmt_dlv_input_qty_id }}"/>
            </td>
            <td width="200px">{{ $colorsize->color_name }}</td>
            <td width="80px">{{ $colorsize->size_name }}</td>
            <td width="80px" align="right">{{ $colorsize->plan_cut_qty }}</td>
            <td width="80px" align="right">{{ $colorsize->total_receive_qty }}</td>
            <td width="80px"><input type="text" name="qty[{{ $i }}]" id="qty{{ $i }}" value="{{ $colorsize->receive_qty }}" class="number integer"/></td>
            <td width="100px" class="text-center">{{ $colorsize->company_id }}</td>
            <td width="80px" class="text-center">{{ $colorsize->buyer_name }}</td>
            <td width="80px" class="text-center">{{ $colorsize->style_ref }}</td>
            <td width="80px" class="text-center">{{ $colorsize->ship_date }}</td>
            <td width="80px"><a href="javascript:void(0)" onclick="MsProdGmtRcvInputQty.delete(event,{{ $colorsize->prod_gmt_rcv_input_qty_id }})">Remove</a></td>
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
