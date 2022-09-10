<h2 align="center"><strong><u>PACKING LIST</u></strong></h2>
<table>
    <tr>
        <td width="630">PI:&nbsp;&nbsp;<strong>{{ $localexpdocaccept['pi_no'] }}</strong></td>
    </tr>
    <tr>
        <td width="70">CONSIGNEE:</td>
        <td width="560">{{ $localexpdocaccept['buyer_name'] }}.<br/>{{ $localexpdocaccept['buyer_address'] }}{{-- ,<br/>{{ $localexpdocaccept['buyer_email'] }} --}}</td>
    </tr>
    <tr>
        <td width="630">L/C No.& Date:&nbsp;<strong>{{ $localexpdocaccept['local_lc_no'] }},&nbsp;&nbsp; Date:{{ $localexpdocaccept['lc_date'] }}</strong><br/>
        Export L/C Cont. No. & Date :&nbsp;<strong>{{ $localexpdocaccept['customer_lc_sc'] }}</strong></td>
    </tr>
    <tr><td></td></tr>
</table>
<table border="1" cellpadding="1" cellspacing="" >
    <tr>
        <td align="center" width="30">#SL</td>
        <td align="center" width="70">Invoice No</td>
        <td align="center" width="90">Order No</td>
        <td align="center" width="250">Description</td>
        <td align="center" width="40">UOM</td>
        <td align="center" width="70">Net WT</td>
        <td align="center" width="80">GRS WT</td>
    </tr>
    <?php 
        $i=1;
        $total_Qty=0;
        $total_gross_qty=0;
    ?>
    @foreach($impdocaccept as $invoice)
    <tbody>
        <tr nobr="true">
            <td align="center" width="30">{{ $i++ }}</td>
            <td align="left" width="70">{{ $invoice->local_invoice_no }}</td>
            <td align="left" width="90">{{ $invoice->sale_order_no }}</td>
            <td align="left" width="250">{{ $invoice->item_description }}</td>
            <td align="center" width="40">{{ $invoice->uom_code }}</td>
            <td align="right" width="70">{{ number_format($invoice->invoice_qty,0) }}</td>
            <td align="right" width="80">{{ number_format($invoice->gross_qty,0) }}</td>
        </tr>
    </tbody>
    <?php 
       $total_Qty += $invoice->invoice_qty;
       $total_gross_qty += $invoice->gross_qty;
    ?>
    @endforeach
    <tfoot>
       <tr>
            <td width="440" align="center"><strong>Total</strong></td>
            <td align="center" width="40"></td>
            <td align="right" width="70"><strong>{{ number_format($total_Qty,0) }}</strong></td>
            <td align="right" width="80"><strong>{{ number_format($total_gross_qty,0) }}</strong></td>
        </tr>
    </tfoot>
</table>
<table>
    <tr><td></td></tr>
    <tr>
        <td width="630">BANK:<br/>
            {{ $localexpdocaccept['buyers_bank'] }}
        </td>
    </tr>
</table>