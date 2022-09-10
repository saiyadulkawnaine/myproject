<h2 align="center"><u>TO WHOM IT MAY CONCERN</u></h2>
<p>Certified that we have Opened the following Yarn & Accessories BTB L/C Against @foreach ($ReplaceableSalesContract as $data)
    <strong>{{ $data->sc_lc }}</strong>
@endforeach on account of our client {{ $rows->company_name }}, {{ $rows->company_address }}
</p>
<p><u><strong>Tag L/C No:</strong></u><br>
<?php
$i=1;
$totalReplacedAmount=0;
?>
<table border="1" cellpadding="2">
    <thead>
        <tr>
            <th width="40" align="center">SL</th>
            <th width="200" align="center">LC No</th>
            <th width="100" align="center">LC Date</th>
            <th  width="120" align="center">Value-{{ $rows->currency_code }}</th>
        </tr>
    </thead>
    @foreach ($Replaced as $replacedata)
    <tbody>
        <tr>
            <td width="40">{{ $i++ }}</td>
            <td width="200">{{ $replacedata->lc_sc_no }}</td>
            <td width="100" align="center">{{ $replacedata->lc_sc_date }}</td>
            <td  width="120" align="right">{{ number_format($replacedata->replaced_lc_sc_value,2) }}</td>
        </tr>
    </tbody>
    <?php
    $totalReplacedAmount+=$replacedata->replaced_lc_sc_value;
    ?>
    @endforeach
    <tfoot>
        <tr>
            <td colspan="3" align="right"><strong>Total</strong></td>
            <td align="right"  width="120">{{ number_format($totalReplacedAmount,2) }}</td>
        </tr>
    </tfoot>
</table></p>
<p align="right" style="font-size: 20px; font-style:italic;">{{ $rows->id }}</p>
<p></p>
<p><strong>Related Back To Back L/C Opened:</strong></p>
<table border="1" cellpadding="2">
    <?php
    $j=1;
    $lcAmountTotal=0;
    ?>
    <thead>
        <tr>
            <th width="40" align="center"><strong>SL No.</strong></th>
            <th width="200" align="center"><strong>Back To Back L/C No</strong></th>
            <th width="100" align="center"><strong>Date</strong></th>
            <th width="100" align="center"><strong>Value -{{ $rows->currency_code }}</strong></th>
            <th width="100" align="center"><strong>LC For</strong></th>
        </tr>
    </thead>
    @foreach ($btblc as $btblcs)
    <tbody>
        <tr  nobr="true">
            <td width="40">{{ $j++ }}</td>
            <td width="200" >{{ $btblcs->lc_no }}</td>
            <td width="100" align="center">{{ $btblcs->lc_date }}</td>
            <td width="100" align="right">{{ $btblcs->lc_amount }}</td>
            <td width="100" align="center">{{ $btblcs->menu_id }}</td>
        </tr>
    </tbody>
    <?php
    $lcAmountTotal+=$btblcs->lc_amount;
    ?>
    @endforeach
    <tfoot>
        <tr>
            <td colspan="3" align="right">Total</td>
            <td align="right"><strong>{{ $lcAmountTotal }}</strong></td>
            <td></td>
        </tr>
    </tfoot>
</table>
<p></p>
<p></p>
<p></p>
<p>We further certify that we have not open any other Back to Back L/C against Export L/C for {{ $rows->bank_name }} , {{ $rows->branch_name }}</p>
<p></p>
<p></p>
<p></p>
<p></p>
<table>
    <tr>
        <td align="center" width="230">{{ $rows->bank_name }}<br>{{ $rows->branch_name }}</td>
        <td width="80"></td>
        <td width="80"></td>
        <td align="center" width="230">{{ $rows->bank_name }}<br>{{ $rows->branch_name }}</td>
    </tr>
    <tr><td></td><td></td><td></td><td></td></tr>
    <tr><td></td><td></td><td></td><td></td></tr>
    <tr><td></td><td></td><td></td><td></td></tr>
    <tr><td></td><td></td><td></td><td></td></tr>
    <tr>
        <td align="center" width="230">--------------------------------<br>Authorized Signature</td>
        <td width="80"></td>
        <td width="80"></td>
        <td align="center" width="230">--------------------------------<br>Authorized Signature</td>
    </tr>
</table>