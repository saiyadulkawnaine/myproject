<h2 align="center">{{-- &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; --}}<u>COST OF PRODUCTION</u></h2>
<p></p>
<p align="center">{{ $rows['sc_lc'] }}&nbsp; {{ $rows['replaces_lc_sc'] }}</p>
<p></p>
<p></p>
<div style="padding:0px;">Used BTB L/C Details:</div>
<table border="1" style="margin-left: auto; margin-right:auto;" cellpadding="1">
    <tr>
        <th width="230" align="center"><strong>Supplier</strong></th>
        <th width="100" align="center"><strong>LC No</strong></th>
        <th width="100" align="center"><strong>LC Date</strong></th>
        <th width="70" align="center"><strong>Yarn LC Qty/Kg</strong></th>
        <th width="70" align="center"><strong>LC Value-{{$rows->currency_code}}</strong></th>
    </tr>
    <?php
        $lcYarnTotalQty=0;
        $lcYarnTotalAmount=0;
        $totalConsumedQty=0;
        $totalConsumedAmount=0;
        $avgRate=0;
    ?>
    @foreach ($incentiveyarnbtblc as $item)
        <tr>
            <td width="230" align="left">{{ $item->supplier_name }}</td>
            <td width="100" align="center">{{ $item->lc_no }}</td>
            <td width="100" align="center">{{ date('d.m.Y',strtotime($item->lc_date)) }}</td>
            <td width="70" align="right">{{ number_format($item->lc_yarn_qty,2) }}</td>
            <td width="70" align="right">{{ number_format($item->lc_yarn_amount,2) }}</td>
        </tr>
    <?php
        $lcYarnTotalQty+=$item->lc_yarn_qty;
        $lcYarnTotalAmount+=$item->lc_yarn_amount;
        $totalConsumedQty+=$item->consumed_qty;
        $totalConsumedAmount+=$item->comsumed_amount;
        if ($totalConsumedQty) {
            $avgRate=$totalConsumedAmount/$totalConsumedQty;
        } 
    ?>
    @endforeach
    <tr>
        <td width="430" align="center" colspan="3"><strong>Total</strong></td>
        <td width="70" align="right"><strong>{{  number_format($lcYarnTotalQty,2) }}</strong></td>
        <td width="70" align="right"><strong>{{ number_format($lcYarnTotalAmount,2) }}</strong></td>
    </tr>
</table>
<p></p>
<p></p>
<p></p>
    
    <?php
        $totalKnitCharge=$totalConsumedQty*$rows->knitting_charge_per_kg;
        $totalDyeingCharge=$totalConsumedQty*$rows->dyeing_charge_per_kg;
        $totalFabricCost=$totalConsumedAmount+$totalKnitCharge+$totalDyeingCharge;
    ?>
<div><strong>Cost Calculation:</strong></div>
<table cellpadding="1" cellspacing="0" border="1" style="margin-left: auto; margin-right:auto;">
    <tr>
        <th align="center" width="40"><strong>SL</strong></th>
        <th align="center" width="260"><strong>Particulars</strong></th>
        <th align="center" width="100"><strong>Used Qty/Kg</strong></th>
        <th align="center" width="60"><strong>Rate/Kg</strong></th>
        <th align="center" width="110"><strong>Amount in {{$rows->currency_code}}</strong></th>
    </tr>
    <tr>
        <td align="center" width="40">1</td>
        <td align="left" width="530"><strong>Yarn Used</strong></td>
    </tr>
    <?php
        $j='a';
    ?>
    @foreach ($usedYarn as $yarnitem)
    <tr>
        <td align="center" width="40">{{$j++}})</td>
        <td align="left" width="260">{{ $yarnitem->item_description }}</td>
        <td align="right" width="100">{{ $yarnitem->consumed_qty }}</td>
        <td align="right" width="60">{{ $yarnitem->con_rate }}</td>
        <td align="right" width="110">{{ $yarnitem->comsumed_amount }}</td>
    </tr>
    @endforeach

    <tr>
        <td align="center" width="40">2</td>
        <td align="left" width="260"><strong>Total Yarn Used</strong></td>
        <td align="right" width="100"><strong>{{ number_format($totalConsumedQty,2) }}</strong></td>
        <td align="right" width="60"></td>
        <td align="right" width="110"><strong>{{ number_format($totalConsumedAmount,2) }}</strong></td>
    </tr>
    <tr>
        <td align="center" width="40">3</td>
        <td align="left" width="260"><strong>Knitting Charge</strong></td>
        <td align="right" width="100"><strong>{{ number_format($totalConsumedQty,2) }}</strong></td>
        <td align="right" width="60"><strong>{{ number_format($rows->knitting_charge_per_kg,2) }}</strong></td>
        <td align="right" width="110"><strong>{{ number_format($totalKnitCharge,2) }}</strong></td>
    </tr>
    <tr>
        <td align="center" width="40">4</td>
        <td align="left" width="260"><strong>Dyeing Charge</strong></td>
        <td align="right" width="100"><strong>{{ number_format($totalConsumedQty,2) }}</strong></td>
        <td align="right" width="60"><strong>{{ number_format($rows->dyeing_charge_per_kg,2) }}</strong></td>
        <td align="right" width="110"><strong>{{ number_format($totalDyeingCharge,2) }}</strong></td>
    </tr>
    <tr>
        <td align="center" width="460" colspan="4"><strong>Total Cost In {{$rows->currency_code}}</strong></td>
        <td align="right" width="110"><strong>{{ number_format($totalFabricCost,2) }}</strong></td>
    </tr>
</table>
<table cellpadding="0" cellspacing="0" border="0" style="margin-left: auto; margin-right:auto;">
    <tr>
        <td align="center" width="550" colspan="5">In Words:{{$rows->inword}} Only</td>
    </tr>
</table>
<p></p>
<p></p>
<p></p>
<p></p>
<p></p>
<p></p>
<p></p>
<table>
    <tr>
        <td><hr>&nbsp;&nbsp;&nbsp;&nbsp;Authorized Signature</td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
</table>
