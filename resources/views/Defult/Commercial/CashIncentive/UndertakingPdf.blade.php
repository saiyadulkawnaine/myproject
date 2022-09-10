<h1 align="center"><u>Undertaking</u></h1>
<p>To,<br>{{ $rows->bank_name }}<br>{{ $rows->branch_name }},<br>{{ $rows->bank_address }}.
</p>
<p align="center" style="font-size: 1.5em;">Page 1</p>
<p>{{ $rows->company_name }}&nbsp;,&nbsp;{{ $rows->company_address }} is hereby giving following undertaking that
</p>
<p>
    <ul>
        <li>Export proceeds of all the files mentioned in below matrix have been realized</li>
        <li>All the information and documents have been submitted, under F.E. circular number 09/2001 of Bangladesh Bank for cash incentive facility of domestic textile industry, are true and fair </li>
        <li>We have knitted fabrics by purchasing yarn from local manufacturer to make exportable garment</li>
    </ul><br>
<strong>Master LC / Contract Related Information</strong>
</p>
<table border="1" cellpadding="2">
    <tr>
        <th align="center" width="20px"><strong>SL</strong></th>
        <th align="center" width="130px"><strong>Master LC / Contract No </strong></th>
        <th align="center" width="80px"><strong>LC/Contract Date</strong></th>
        <th align="center" width="80px"><strong>Value</strong></th>
        <th align="center" width="170px"><strong>Related Contract</strong></th>
        <th align="center" width="80px"><strong>Country</strong></th>
        <th align="center" width="80px"><strong>Invoice Value</strong></th>
    </tr>
    <tr>
        <td align="center" width="20px">1</td>
        <td align="left" width="130px">{{ $rows->lc_sc_no }}</td>
        <td align="center" width="80px">{{ $rows->lc_sc_date }}</td>
        <td align="center" width="80px">{{ $rows->lc_sc_value }}</td>
        <td align="left" width="170px">{{ $rows->replacable_contract_no }}</td>
        <td align="center" width="80px">{{ $rows->country_name }}</td>
        <td align="center" width="80px">{{ $rows->currency_symbol }}{{ number_format($rows->invoice_value,2) }}</td>
    </tr>
</table>
<p></p>
<?php
$i=1;
$totalLcQty=0;
$totalLcAmount=0;
?>
<p><strong> Related Back to Back LC:</strong></p>
<table border="1" cellpadding="2">
    <tr>
        <th align="center" width="20px"><strong>SL</strong></th>
        <th align="center" width="100px"><strong>Back to Back LC No</strong></th>
        <th align="center" width="80px"><strong>LC Date</strong></th>
        <th align="center" width="80px"><strong>LC Value</strong></th>
        <th align="center" width="220px"><strong>Supplier</strong></th>
        <th align="center" width="60px"><strong>Item</strong></th>
        <th align="center" width="80px"><strong>Qty</strong></th>
    </tr>
    @foreach ($incentiveyarnbtblc as $btoblc)   
    <tr>
        <td align="center" width="20px">{{ $i++ }}</td>
        <td align="center" width="100px">{{ $btoblc->lc_no }}</td>
        <td align="center" width="80px">{{ $btoblc->lc_date }}</td>
        <td align="center" width="80px">{{ number_format($btoblc->lc_yarn_amount,2) }}</td>
        <td align="left" width="220px">{{ $btoblc->supplier_name }}</td>
        <td align="center" width="60px">Yarn</td>
        <td align="right" width="80px">{{ number_format($btoblc->lc_yarn_qty,2) }}</td>
    </tr>
    <?php
    $totalLcQty+=$btoblc->lc_yarn_qty;
    $totalLcAmount+=$btoblc->lc_yarn_amount;
    ?>
    @endforeach
    <tr>
        <td align="center" colspan="3"><strong>Total</strong></td>
        <td align="center" width="80px">{{ number_format($totalLcAmount,2) }}</td>
        <td align="center"  colspan="2"></td>
        <td align="right" width="80px">{{ number_format($totalLcQty,2) }}</td>
    </tr>
</table>

{{-- ////////////////////////// --}}
<br pagebreak="true"/>
<p align="center" style="font-size: 1.5em;">Page 2</p>
<p>We furthermore are declaring that, we did not apply or never apply in future for getting any facilities from Bangladesh Bank or any other institute against this export LC.</p>
{{-- ////////////////////////// --}}
<br pagebreak="true"/>
<p align="center" style="font-size: 1.5em;">Page 3</p>
<p></p>
<p></p>
<p>We are also hereby giving undertaking that we shall be bound to accept any reasonable legal consequences if found any mistake or untruth information, documents and declarations submitted as supporting of this incentive claim.</p>
<p></p>
<p></p>
<p></p>
<p></p>
<p></p>
<table>
    <tr>
        <td align="center">Yours faithfully</td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
</table>