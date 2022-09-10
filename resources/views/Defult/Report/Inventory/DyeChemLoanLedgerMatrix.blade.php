<table border="1">
        <tr>
            <th width="100" class="text-center">Date</th>
            <th width="100" class="text-center">Trans No</th>
            <th width="100" class="text-center">Item Id</th>
            <th width="100" class="text-center">Item Description</th>
            <th width="100" class="text-center">UOM</th>
            <th width="310" class="text-center" colspan="3">Ouantity
            <table border="1">
                <tr>
                    <td class="text-center" width="100px">Receive</td>
                    <td class="text-center" width="100px"> Issue </td>
                    <td class="text-center" width="100px">Balance</td>
                </tr>
            </table>
            </th>
            <th width="310" class="text-center" colspan="3">Amount
            <table border="1">
                <tr>
                    <td class="text-center" width="100px">Receive</td>
                    <td class="text-center" width="100px"> Issue </td>
                    <td class="text-center" width="100px">Balance</td>
                </tr>
            </table>
            </th>
        </tr>
    
    @foreach ($data as $supplier_id=>$rows)
   
        <tr>
        <td width="1100" colspan="11" class="text-left">
        <strong>&nbsp;{{$supplier[$supplier_id]}}</strong>
        </td>
        </tr>
        <?php
        $tRcvQty=0;
        $tIsuQty=0;
        $tRcvAmt=0;
        $tIsuAmt=0;
        ?>
    @foreach ($rows as $index=>$row)
        <?php
        if ($row->trans_type_id==0) {
        ?>
        <tr>
            <td width="500" colspan="5" class="text-left">&nbsp;Opening Balance</td>
            <td width="300" colspan="3" class="text-right">{{ $row->open_balance_qty}}</td>
            <td width="300" colspan="3" class="text-right">{{ $row->open_balance_amount}}</td>
        </tr>
        <?php
        //
        ?>
        <?php
            //$tRcvQty=0;
            //$tIsuQty=0;
            //$tRcvAmt=0;
            //$tIsuAmt=0;
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
                <td width="100" class="text-center"> {{ $row->trans_date }}</td>
                <td width="100" class="text-center">{{ $row->trans_no }}</td>
                <td width="100" class="text-center">{{ $row->item_account_id }}</td>
                <td width="100" class="text-center">{{ $row->sub_class_name }}, {{ $row->item_description }} ,{{ $row->specification }}</td>
                <td width="100" class="text-center">{{ $row->uom_code }}</td>
                <td width="100" class="text-right">{{ number_format($row->rcv_qty,2) }}</td>
                <td width="100" class="text-right">{{ number_format($row->isu_qty,2) }}</td>
                <td width="100" class="text-right">{{ number_format($balanceQty,2) }}</td>
                <td width="100" class="text-right">{{ number_format($row->rcv_amount,2) }}</td>
                <td width="100" class="text-right">{{ number_format($row->isu_amount,2) }}</td>
                <td width="100" class="text-right">{{ number_format($balanceAmount,2) }}</td>
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
        <td width="500" class="text-center" colspan="5">Total </td>
        <td width="100" class="text-right">{{ number_format($tRcvQty,2) }}</td>
        <td width="100" class="text-right">{{ number_format($tIsuQty,2) }}</td>
        <td width="100" class="text-right"></td>
        <td width="100" class="text-right">{{ number_format($tRcvAmt,2) }}</td>
        <td width="100" class="text-right">{{ number_format($tIsuAmt,2) }}</td>
        <td width="100" class="text-right"></td>
    </tr>
    @endforeach
</table>