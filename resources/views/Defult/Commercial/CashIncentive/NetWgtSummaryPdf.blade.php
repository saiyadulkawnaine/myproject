<h2 align="center">NET WEIGHT SUMMARY</h2>
<P align="center"><u><strong>{{ $rows->sc_lc }}</strong></u></P>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

<table border="1" width="80%" style="margin-left: auto; margin-right:auto;" cellspacing="0" cellpadding="1">
    <thead>
        <tr>
            <th width="30" align="center"><strong>SL</strong></th>
            <th width="100" align="center"><strong>INVOICE NO</strong></th>
            <th width="100" align="center"><strong>NET WEIGHT</strong></th>
            <th width="60" align="center"><strong>COUNTRY</strong></th>
            <th width="70" align="center"><strong>INCENTIVE %</strong></th>
        </tr>
    </thead>
    <?php
            $i=1;
            $totalNetWgt=0;
            $processLoss=0;
            $grossTotalNetWgt=0;
        ?>
        @foreach ($incentiveclaim as $item)
    <tbody>
            <tr>
                <td width="30" align="center">{{ $i++ }}</td>
                <td width="100" align="center">{{ $item->invoice_no }}</td>
                <td width="100" align="right">{{ number_format($item->net_wgt_exp_qty,2) }}</td>
                <td width="60" align="center">{{ $item->country_name }}</td>
                <td width="70" align="center">{{ $item->claim }}</td>
            </tr>
        <?php
            $totalNetWgt+=$item->net_wgt_exp_qty;
            $processLoss=$totalNetWgt*(16/100);
            $grossTotalNetWgt=$totalNetWgt+$processLoss;
        ?>
    </tbody>
    @endforeach 
    <tfoot>
        <tr>
            <td width="30"></td>
            <td width="100"><strong>TOTAL</strong></td>
            <td width="100" align="right"><strong>{{ number_format($totalNetWgt,2) }}</strong></td>
            <td width="60"></td>
            <td width="70"></td>
        </tr>
        <tr>
            <td width="30"></td>
            <td width="100">16% Plus</td>
            <td width="100" align="right">{{ number_format($processLoss,2) }}</td>
            <td width="60"></td>
            <td width="70"></td>
        </tr>
        <tr>
            <td width="30"></td>
            <td width="100"><strong>G/TOTAL Kg</strong></td>
            <td width="100" align="right"><strong>{{ number_format($grossTotalNetWgt,2) }}</strong></td>
            <td width="60"></td>
            <td width="70"></td>
        </tr>
    </tfoot>
</table>