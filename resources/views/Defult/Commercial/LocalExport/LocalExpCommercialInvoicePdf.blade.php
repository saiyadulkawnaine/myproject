<h2 align="center">COMMERCIAL INVOICE</h2>
<table border="0" cellpadding="1" cellspacing="0">
    <tr>
        <td width="330"></td>
        <td width="150"></td>
        <td width="70" align="left">&nbsp;Invoice No:</td>
        <td width="80">{{ $localexpdocaccept['local_invoice_no'] }}</td>
    </tr>
    <tr>
        <td width="330"></td>
        <td width="150"></td>
        <td width="70" align="left">&nbsp;Invoice Date:</td>
        <td width="80">{{ $localexpdocaccept['local_invoice_date'] }}</td>
    </tr>
</table>
<table border="1" cellpadding="1" cellspacing="0">
    <tr>
        <td><p><strong><u>Shipper/Exporter :</u></strong><br/>
            <strong>{{ $localexpdocaccept['beneficiary'] }}</strong>,<br/>{{ $localexpdocaccept['company_address'] }}</p>
        </td>
        <td><p><strong><u>For Account & Risk Of :</u></strong><br/>
            <strong>{{ $localexpdocaccept['buyer_name'] }}</strong>,<br/>{{ $localexpdocaccept['buyer_address'] }}{{-- ,<br/>{{ $localexpdocaccept['buyer_email'] }} --}}</p></td>
    </tr>
    <tr>
        <td><p><strong><u>L/C ISSUING BANK :</u></strong><br/>
            {!! nl2br(e($localexpdocaccept['buyers_bank'])) !!}</p>
        </td>
        <td><p><strong><u>Proforma Invoice :</u>&nbsp;&nbsp;&nbsp;<u>Date:</u></strong><br/>
            {{ $localexpdocaccept['pi_no'] }}</p>
            <p><strong><u>L/C No.& Date:</u></strong><br/>
            <strong>{{ $localexpdocaccept['local_lc_no'] }}, Date:{{ $localexpdocaccept['lc_date'] }}</strong><br/></p>
        </td>
    </tr>
    <tr>
        <td><p><strong><u>Loading & Destination :</u></strong><br/>
            {{ $localexpdocaccept['delivery_place'] }}</p>
        </td>
        <td><p><strong><u>Export L/C Cont. No. & Date :</u></strong><br/>
            <strong>{{ $localexpdocaccept['customer_lc_sc'] }}</strong></p></td>
    </tr>
</table>
<p><strong align="center">FRIGHT PREPAID</strong></p>
<table border="1" cellpadding="1" cellspacing="" >
    <tr>
        <td align="center" width="30">#SL</td>
        <td align="center" width="100">Order No</td>
        <td align="center" width="260">Description</td>
        <td align="center" width="70">Qty</td>
        <td align="center" width="40">UOM</td>
        <td align="center" width="60">Unit<br/>Price</td>
        <td align="center" width="80">Value</td>
    </tr>
    <?php 
        $i=1;
        $total_Qty=0;
       $total_Amount=0;
    ?>
    @foreach($impdocaccept as $invoice)
    <tbody>
        <tr nobr="true">
            <td align="center" width="30">{{ $i++ }}</td>
            <td align="left" width="100">{{ $invoice->sale_order_no }}</td>
            <td align="left" width="260">{{ $invoice->item_description }}</td>
            <td align="right" width="70">{{ number_format($invoice->invoice_qty,0) }}</td>
            <td align="center" width="40">{{ $invoice->uom_code }}</td>
            <td align="right" width="60">{{ number_format($invoice->invoice_rate,4) }}</td>
            <td align="right" width="80">{{ number_format($invoice->invoice_amount,2) }}</td>
        </tr>
    </tbody>
    <?php 
       $total_Qty += $invoice->invoice_qty;
       $total_Amount += $invoice->invoice_amount;
    ?>
    @endforeach
    <tfoot>
        <tr>
            <td width="390" align="center"><strong>Total</strong></td>
            <td align="right" width="70"><strong>{{ number_format($total_Qty,0) }}</strong></td>
            <td align="center" width="40"></td>
            <td align="right" width="60"></td>
            <td align="right" width="80"><strong>{{ number_format($total_Amount,2) }}</strong></td>
        </tr>
        <tr>
            <td align="center" width="640"><strong>{{ $localexpdocaccept['inword'] }}</strong></td>
        </tr>
    </tfoot>
</table>