<h2 align="center">Pro Forma Invoice</h2>
<p align="center"><strong>PI No: &nbsp;{{ $localexppi['pi_no'] }} &nbsp;&nbsp;&nbsp;Date:&nbsp;{{ date('d-M-Y',strtotime($localexppi['pi_date'])) }}</strong><br/><br/>
<span align="center" style="font-size:40px"><strong><u>{{ $localexppi['production_area_id'] }}</u></strong></span></p>
<table>
    <tr>
        <td>To,</td>
    </tr>
    <tr>
        <td>{{ $localexppi['customer_name'] }}</td>
    </tr>
    <tr>
        <td>{{ $localexppi['customer_address'] }}</td>
    </tr>
    <tr>
        <td>Contact: {{ $localexppi['contact_person'] }}</td>
    </tr>
    <tr>
        <td>Remarks: {{ $localexppi['remarks'] }}</td>
    </tr>
</table>
<?php
    $i=1;
    $totalQty=0;
    $totalAmount=0;
?>
<table border="1" cellpadding="2" cellspacing="">
    <tr>
        <td width="25" align="center">#SL</td>
        <td width="160" align="center">Item Description</td>
        <td width="60" align="center">Dye/AOP Type</td>
        <td width="53" align="center">Qty</td>
        <td width="30" align="center">UOM</td>
        <td width="60" align="center">Rate-{{ $localexppi['currency_id'] }}</td>
        <td width="60" align="center">Amount-{{ $localexppi['currency_id'] }}</td>
        <td width="200" align="center">Buyer, Style & Sales Order</td>
    </tr>
    <tbody>
        @foreach($orders as $items)
        <tr nobr="true">
            <td width="25" align="center">{{ $i }}</td>
            <td width="160" align="left">{{ $items->item_description }}</td>
            <td width="60" align="center">{{ $items->dye_aop_type }}</td>
            <td width="53" align="right">{{ number_format($items->qty,2,'.',',') }}</td>
            <td width="30" align="center">{{ $items->uom_code }}</td>
            <td width="60" align="right">{{ number_format($items->order_rate,4) }}</td>
            <td width="60" align="right">{{ number_format($items->amount,2,'.',',') }}</td>
            <td width="200" align="left">{{ $items->Custom_buyer_name }} {{ $items->Custom_style_ref }}&{{ $items->Custom_sale_order_no }}</td>
        </tr>
    </tbody>
    <?php 
       $i++;
        $totalQty+=$items->qty;
        $totalAmount+=$items->amount;
    ?>
    @endforeach
    <tfoot>
            <tr>
                <td width="245" align="right"><strong>Total</strong></td>
                <td width="53" align="right"><strong>{{ number_format($totalQty,2,'.',',') }}</strong></td>
                <td width="30" align="center"></td>
                <td width="60" align="right"></td>
                <td width="60" align="right"><strong>{{ number_format($totalAmount,2,'.',',') }}</strong></td>
                <td align="center" width="200"></td>
            </tr>
    </tfoot>
</table>
<p></p>
<p><strong>In Words :  {{ $localexppi['inword'] }} </strong></p>
<table>
    <tr><th colspan="6"><strong>LC TERMS & CONDITIONS</strong></th></tr>
    <tr><th colspan="6"></th></tr>
    <tr>
        <th width="20">1.</th>
        <th width="90"align="left">PI Validity</th>
        <th width="528" align="left" colspan="4">: {{ $localexppi['pi_validity_days'] }}</th>
    </tr>
    <tr>
        <th width="20">2.</th>
        <th width="90"align="left">Payment Term</th>
        <th width="528" colspan="4" align="left">: {{ $localexppi['pay_term_id'] }}</th>
    </tr>
    <tr>
        <th width="20">3.</th>
        <th width="90"align="left">Advising Bank</th>
        <th width="528" colspan="4" align="left">: {{ $localexppi['advise_bank'] }}</th>
    </tr>
    <tr>
        <th width="20">4.</th>
        <th width="90"align="left">Account No</th>
        <th width="528" colspan="4" align="left">: {{ $localexppi['account_no'] }}</th>
    </tr>
    <tr>
        <th width="20">5.</th>
        <th width="90"align="left">Swift Code</th>
        <th width="528" colspan="4" align="left">: {{ $localexppi['swift_code'] }}</th>
    </tr>
    <tr>
        <th width="20">6.</th>
        <th width="90"align="left">LC Negotiable</th>
        <th width="528" colspan="4" align="left">: {{ $localexppi['lc_negotiable'] }}</th>
    </tr>
    <tr>
        <th width="20">7.</th>
        <th width="90"align="left">Over Due</th>
        <th width="528" colspan="4" align="left">: {{ $localexppi['overdue'] }}</th>
    </tr>
    <tr>
        <th width="20">8.</th>
        <th width="90"align="left">Maturity Date</th>
        <th width="528" colspan="4" align="left">: {{ $localexppi['maturity_date'] }}</th>
    </tr>
    <tr>
        <th width="20">9.</th>
        <th width="90"align="left">Delivery Place</th>
        <th width="528" colspan="4" align="left">: {{ $localexppi['delivery_place'] }}</th>
    </tr>
    <tr>
        <th width="20">10.</th>
        <th width="90"align="left">Partial Delivery</th>
        <th width="528" colspan="4" align="left">: {{ $localexppi['partial_delivery'] }}</th>
    </tr>
    <tr>
        <th width="20">11.</th>
        <th width="90"align="left">Tolerance %</th>
        <th width="528" colspan="4" align="left">: {{ $localexppi['tolerance'] }}</th>
    </tr>
    <tr>
        <th width="20">12.</th>
        <th width="90"align="left">HS Code</th>
        <th width="528" colspan="4" align="left">: {{ $localexppi['hs_code'] }}</th>
    </tr>
    <tr>
        <th width="20">13.</th>
        <th width="90" align="left">Vat Registration No</th>
        <th width="528" colspan="4" align="left">: {{ $localexppi['vat_number'] }}</th>
    </tr>
<?php
    $i=14;
?>
@foreach($localexppi['purchasetermscondition'] as $terms)
    <tr>
        <td width="20">{{$i}}.</td>
        <td width="618">{{$terms->term}}</td>
    </tr>
<?php
    $i++;
?>
@endforeach
    <tr><th colspan="6"></th></tr>
    <tr><th colspan="6"></th></tr>
    <tr><th colspan="6"></th></tr>
    <tr><th colspan="6"></th></tr>
    <tr><th colspan="6"><strong>Authorized By</strong></th></tr>
</table>