<h2 align="center">Dyes & Chemical Loan Ledger</h2>
<h4 align="center">Date {{$date_from}} - {{$date_to}}</h4>
<table border="1" cellpadding="1">
    <tr>
        <th width="80" align="center">Date</th>
        <th width="70" align="center">Trans No</th>
        <th width="70" align="center">Item Id</th>
        <th width="140" align="center">Item Description</th>
        <th width="40" align="center">UOM</th>
        <th width="270" align="center" colspan="3">Ouantity</th>
        <th width="270" align="center" colspan="3">Amount</th>
    </tr>
    <tr>
        <th width="400" align="center" colspan="5"></th>
        <th align="center" width="90">Receive</th>
        <th align="center" width="90"> Issue </th>
        <th align="center" width="90">Balance</th>
        <th align="center" width="90">Receive</th>
        <th align="center" width="90"> Issue </th>
        <th align="center" width="90">Balance</th>
    </tr>
    @foreach ($data as $supplier_id=>$rows)
    <?php
        $tRcvQty=0;
        $tIsuQty=0;
        $tRcvAmt=0;
        $tIsuAmt=0;
    ?>
        <tr>
            <td width="940" colspan="11" class="text-left">
                <strong>&nbsp;{{$supplier[$supplier_id]}}</strong>
            </td>
        </tr>
        @foreach ($rows as $index=>$row)
        <?php
        if ($row->trans_type_id==0) {
        ?>
        <tr>
            <td width="400" colspan="5" align="left">&nbsp;Opening Balance</td>
            <td width="270" colspan="3" align="right">{{ $row->open_balance_qty }}</td>
            <td width="270" colspan="3" align="right">{{ $row->open_balance_amount }}</td>
        </tr>
        <?php
            $balanceQty=$row->open_balance_qty;
            $balanceAmount=$row->open_balance_amount;
        }
        ?>

        <?php
 
        if ($row->trans_type_id>0) {
            $balanceQty=($balanceQty+$row->rcv_qty)-$row->isu_qty;
            $balanceAmount=($balanceAmount+$row->rcv_amount)-$row->isu_amount;
        ?>
            <tr>
                <td width="80" align="center"> {{ $row->trans_date }}</td>
                <td width="70" align="center">{{ $row->trans_no }}</td>
                <td width="70" align="center">{{ $row->item_account_id }}</td>
                <td width="140" align="center">{{$row->sub_class_name}},{{ $row->item_description }},{{ $row->specification }}</td>
                <td width="40" align="center">{{ $row->uom_code }}</td>
                <td width="90" align="right">{{ number_format($row->rcv_qty,2) }}</td>
                <td width="90" align="right">{{ number_format($row->isu_qty,2) }}</td>
                <td width="90" align="right">{{ number_format($balanceQty,2) }}</td>
                <td width="90" align="right">{{ number_format($row->rcv_amount,2) }}</td>
                <td width="90" align="right">{{ number_format($row->isu_amount,2) }}</td>
                <td width="90" align="right">{{ number_format($balanceAmount,2) }}</td>
            </tr>
        <?php
            $tRcvQty+=$row->rcv_qty;
            $tIsuQty+=$row->isu_qty;
            $tRcvAmt+=$row->rcv_amount;
            $tIsuAmt+=$row->isu_amount;
        }
        ?>
    @endforeach
        <tr>
            <td width="400" align="center" colspan="5">Total </td>
            <td width="90" align="right">{{ number_format($tRcvQty,2) }}</td>
            <td width="90" align="right">{{ number_format($tIsuQty,2) }}</td>
            <td width="90" align="right"></td>
            <td width="90" align="right">{{ number_format($tRcvAmt,2) }}</td>
            <td width="90" align="right">{{ number_format($tIsuAmt,2) }}</td>
            <td width="90" align="right"></td>
        </tr>
    @endforeach
</table>