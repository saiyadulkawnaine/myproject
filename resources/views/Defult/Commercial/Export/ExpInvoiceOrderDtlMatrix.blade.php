<?php
        $i=1;

?>
@if($expcolorsizes->isNotEmpty())
<p>New Color & Size wise Invoice Qty</p>
<table border="1">
    <tr align="center">
        <th width="200px"  class="text-center">Country</th>
        <th width="200px"  class="text-center">GMT Item</th>
        <th width="200px"  class="text-center">GMT Color</th>
        <th width="80px"  class="text-center">Size</th>
        <th width="80px"  class="text-center">Order Qty</th>
        <th width="80px"  class="text-center">Invoice Qty</th>
        <th width="80px"  class="text-center">Rate</th>
        <th width="80px"  class="text-center">Invoice Value</th>
    </tr>
    <tbody>
        
        @foreach($expcolorsizes as $colorsize)
        <tr align="center">
            <td width="200px">{{ $colorsize->country_name }}</td>
            <td width="200px">
                {{ $colorsize->item_description }}
                <input type="hidden" name="exp_invoice_order_id[{{ $i }}]" id="exp_invoice_id{{ $i }}" value="{{ $colorsize->exp_invoice_order_id }}"/>
                <input type="hidden" name="sales_order_gmt_color_size_id[{{ $i }}]" id="sales_order_gmt_color_size_id{{ $i }}" value="{{ $colorsize->sales_order_gmt_color_size_id }}"/>
            </td>
            <td width="200px">{{ $colorsize->color_name }}</td>
            <td width="80px">{{ $colorsize->size_name }}</td>
            <td width="80px" align="right">{{ $colorsize->color_qty }}</td>
            <td width="80px" align="right">
                <input type="text" name="qty[{{ $i }}]" id="qty{{ $i }}" class="number integer" onchange="MsExpInvoiceOrderDtl.calculate({{ $i }},{{ $loop->count}})" value=""/>
            </td>
            <td width="80px" align="right">
                <input type="text" name="rate[{{ $i }}]" id="rate{{ $i }}" class="number integer" onchange="MsExpInvoiceOrderDtl.calculate({{ $i }},{{ $loop->count}})" value=" {{ $colorsize->color_rate }} "/>
            </td>
            <td width="80px">
                <input type="text" name="amount[{{ $i }}]" id="amount_{{ $i }}" class="number" value="" readonly/>
            </td>
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
<p>Saved Color & Size wise Invoice Qty</p>
<table border="1">
    <tr align="center">
        <th width="200px"  class="text-center">Country</th>
        <th width="200px"  class="text-center">GMT Item</th>
        <th width="200px"  class="text-center">GMT Color</th>
        <th width="80px"  class="text-center">Size</th>
        <th width="80px"  class="text-center">Order Qty</th>
        <th width="80px"  class="text-center">Invoice Qty</th>
        <th width="80px"  class="text-center">Rate</th>
        <th width="80px"  class="text-center">Invoice Value</th>
        <th width="80px"  class="text-center"></th>
    </tr>
    <tbody>
        <?php
           $total_qty=0; 
           $total_amount=0;
           $avg_rate=0;

        ?>
        @foreach($saved as $colorsize)
        <tr align="center">
            <td width="200px">{{ $colorsize->country_name }}</td>
            <td width="200px">
                {{ $colorsize->item_description }}
                <input type="hidden" name="exp_invoice_order_id[{{ $i }}]" id="exp_invoice_id{{ $i }}" value="{{ $colorsize->exp_invoice_order_id }}"/>
                <input type="hidden" name="sales_order_gmt_color_size_id[{{ $i }}]" id="sales_order_gmt_color_size_id{{ $i }}" value="{{ $colorsize->sales_order_gmt_color_size_id }}"/>
            </td>
            <td width="200px">{{ $colorsize->color_name }}</td>
            <td width="80px">{{ $colorsize->size_name }}</td>
            <td width="80px" align="right">{{ $colorsize->plan_cut_qty }}</td>
            <td width="80px" align="right">
                <input type="text" name="qty[{{ $i }}]" id="qty{{ $i }}" class="number integer" onchange="MsExpInvoiceOrderDtl.calculate({{ $i }},{{ $loop->count}})" value="{{ $colorsize->qty }}"/>
            </td>
            <td width="80px" align="right">
                <input type="text" name="rate[{{ $i }}]" id="rate{{ $i }}" class="number integer" onchange="MsExpInvoiceOrderDtl.calculate({{ $i }},{{ $loop->count}})" value="{{ $colorsize->rate }}"/>
            </td>
            <td width="80px">
            <input type="text" name="amount[{{ $i }}]" id="amount_{{ $i }}" class="number" value="{{ $colorsize->amount }}" readonly/>
            </td>
            <td width="80px"><a href="javascript:void(0)" onclick="MsExpInvoiceOrderDtl.delete(event,{{ $colorsize->exp_invoice_order_dtl_id }})">Remove</a></td>
        </tr>
        
        <?php
            $i++;
            $total_qty+=$colorsize->qty; 
            $total_amount+=$colorsize->amount;
            $avg_rate=$total_amount/$total_qty;
        ?>
        @endforeach
    </tbody>
    <tfoot>
            <tr>
                <td colspan="5"></td>
                <td width="80px" align="right">{{ $total_qty }}</td>
                <td width="80px" align="right">{{ number_format($avg_rate,4) }}</td>
                <td width="80px" align="right">{{ $total_amount }}</td>
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
