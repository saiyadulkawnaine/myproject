<h2 align="center">DELIVERY CHALLAN</h2>
<table border="0" cellpadding="1" cellspacing="0">
    <tr>
        <td width="330">To</td>
        <td width="150"></td>
        <td width="70" align="left">&nbsp;Invoice No:</td>
        <td width="80">{{ $localexpdocaccept['local_invoice_no'] }}</td>
    </tr>
    <tr>
        <td width="330"><strong>{{ $localexpdocaccept['buyer_name'] }}</strong>,<br/>{{ $localexpdocaccept['buyer_address'] }}{{-- ,<br/>{{ $localexpdocaccept['buyer_email'] }} --}}</td>
        <td width="150"></td>
        <td width="70" align="left">&nbsp;Invoice Date:</td>
        <td width="80">{{ $localexpdocaccept['local_invoice_date'] }}</td>
    </tr>
</table>
<table border="0" cellpadding="2" cellspacing="0">
    <tr>
        <td width="100">Proforma Invoice :</td>
        <td width="530"><strong>{{ $localexpdocaccept['pi_no'] }}</strong></td> 
    </tr>
    <tr>
        <td width="630" align="center" ><strong>FRIGHT PREPAID</strong></td>
    </tr>
</table>
<table border="1" cellpadding="1" cellspacing="" align="center">
    <tr>
        <td align="center" width="40">#SL</td>
        <td align="center" width="100">Order No</td>
        <td align="center" width="370">Description</td>
        <td align="center" width="80">Qty</td>
        <td align="center" width="40">UOM</td>
        {{-- <td align="center" width="60">Unit<br/>Price</td>
        <td align="center" width="80">Value</td> --}}
    </tr>
    <?php 
        $i=1;
       // $total_Qty=0;
      // $total_Amount=0;
    ?>
    @foreach($impdocaccept as $invoice)
    <tbody>
        <tr nobr="true">
            <td align="center" width="40">{{ $i++ }}</td>
            <td align="left" width="100">{{ $invoice->sale_order_no }}</td>
            <td align="left" width="370">{{ $invoice->item_description }}</td>
            <td align="right" width="80">{{ $invoice->invoice_qty }}</td>
            <td align="center" width="40">{{ $invoice->uom_code }}</td>
            {{-- <td align="right" width="60">{{ $invoice->invoice_rate }}</td>
            <td align="right" width="80">{{ $invoice->invoice_amount }}</td> --}}
        </tr>
    </tbody>
    <?php 
      // $total_Qty += $invoice->invoice_qty;
      // $total_Amount += $invoice->invoice_amount;
    ?>
    @endforeach
</table>
<p></p>
<p></p>
<table border="0" cellpadding="2" cellspacing="0" style="margin:0 auto">
    <tr>
        <td width="100"  align="left">L/C No. :</td>
        <td width="530"><strong>{{ $localexpdocaccept['local_lc_no'] }}, Date:{{ $localexpdocaccept['lc_date'] }}</strong></td>
    </tr>
    <tr>
        <td width="100"  align="left"></td>
        <td width="530"></td>
    </tr>
    <tr>
        <td width="100"  align="left">Export L/C No. :</td>
        <td width="530">{{ $localexpdocaccept['customer_lc_sc'] }}</td>
    </tr>
    <tr>
        <td width="100"  align="left"></td>
        <td width="530"></td>
    </tr>
    <tr>
        <td width="100"  align="left">Bank :</td>
        <td width="530">{!! nl2br(e($localexpdocaccept['buyers_bank'])) !!}</td>
    </tr>
</table>
<p></p>
<p></p>
<p></p>
<p></p>
<p><strong align="center">GOODS RECEIVED IN GOOD ORDER CONDITION</strong></p>
<p></p>
<p></p>
<p></p>
<p></p>
<p></p>
<p></p>
<p></p>
<p></p>
<p></p>
<p><strong align="right">AUTHORISED SIGNATURE</strong></p>