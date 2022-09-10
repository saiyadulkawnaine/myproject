<table border="1" style="border-style:dotted;line-height:25px;">
    <caption>Grey Yarn Issue For Dyeing </caption>
    <tr align="center">
    <th width="100px">Issue Date </th>
    <th width="100px">Issue No </th>
    <th width="100px">Count </th>
    <th width="100px">Yarn Desc. </th>
    <th width="100px">Type </th>

    <th width="130px">Yarn Color </th>
    
    <th width="100px" align="right">Qty</th>
    <th width="100px" align="right">Rate</th>
    <th width="100px" align="right">Amount</th>
    </tr>
    <?php
    $totIssQty=0;
    $totIssAmt=0;
    ?>
     @foreach($issue as $row)
    <tr align="left">
    <td width="100px">{{$row->issue_date}} </td>
    <td width="100px">{{$row->issue_no}} </td>
    <td width="100px">{{$row->count_name}} </td>
    <td width="100px">{{$row->composition}} </td>
    <td width="100px">{{$row->yarn_type}} </td>
    <td width="100px">{{$row->yarn_color_name}} </td>

    
    <td width="100px" align="right">{{number_format($row->grey_yarn_issue_qty_for_dye,2)}}</td>
    <td width="100px" align="right">{{number_format($row->grey_yarn_issue_rate_for_dye,2)}}</td>
    <td width="100px" align="right">{{number_format($row->grey_yarn_issue_amount_for_dye,2)}}</td>
    </tr>
    <?php
    $totIssQty+=$row->grey_yarn_issue_qty_for_dye;
    $totIssAmt+=$row->grey_yarn_issue_amount_for_dye;
    ?>
    @endforeach
    <tr align="left">
    <td colspan="6">Total</td>
    <td width="100px" align="right">{{number_format($totIssQty,2)}}</td>
    <td width="100px" align="right"></td>
    <td width="100px" align="right">{{number_format($totIssAmt,2)}}</td>
    </tr>
</table>

<table border="1" style="border-style:dotted;line-height:25px;">
    <caption>Grey Yarn Return From Dyeing </caption>
    <tr align="center">
    <th width="100px">Return Date </th>
    <th width="100px">Return No </th>
    <th width="100px">Count </th>
    <th width="100px">Yarn Desc. </th>
    <th width="100px">Type </th>

    <th width="130px">Yarn Color </th>
    <th width="100px" align="right">Qty</th>
    <th width="100px" align="right">Rate</th>
    <th width="100px" align="right">Amount</th>
    </tr>
    <?php
    $totRtnQty=0;
    $totRtnAmt=0;
    ?>
     @foreach($return as $row)
    <tr align="left">
    <td width="100px">{{$row->receive_date}} </td>
    <td width="100px">{{$row->receive_no}} </td>
    <td width="100px">{{$row->count_name}} </td>
    <td width="100px">{{$row->composition}} </td>
    <td width="100px">{{$row->yarn_type}} </td>

    <td width="130px">{{$row->yarn_color_name}} </td>
    <td width="100px" align="right">{{number_format($row->qty,2)}}</td>
    <td width="100px" align="right">{{number_format($row->rate,2)}}</td>
    <td width="100px" align="right">{{number_format($row->amount,2)}}</td>
    </tr>
    <?php
    $totRtnQty+=$row->qty;
    $totRtnAmt+=$row->amount;
    ?>
    @endforeach
    <tr align="left">
    <td colspan="6">Total</td>
    <td width="100px" align="right">{{number_format($totRtnQty,2)}}</td>
    <td width="100px" align="right"></td>
    <td width="100px" align="right">{{number_format($totRtnAmt,2)}}</td>
    </tr>
    <tr align="left">
    <td colspan="6">Net Issued</td>
    <td width="100px" align="right">{{number_format(($totIssQty-$totRtnQty),2)}}</td>
    <td width="100px" align="right"></td>
    <td width="100px" align="right">{{number_format(($totIssAmt-$totRtnAmt),2)}}</td>
    </tr>
</table>
<p></p>
<table border="1" style="border-style:dotted;line-height:25px;">
    <caption>Dyed Yarn Receive </caption>
    <tr align="center">
    <th width="100px">Receive Date </th>
    <th width="100px">Receive No </th>
    <th width="100px">Count </th>
    <th width="100px">Yarn Desc. </th>
    <th width="100px">Type </th>

    <th width="130px">Yarn Color</th>
    <th width="100px" align="right">Qty</th>
    <th width="100px" align="right">Rate</th>
    <th width="100px" align="right">Amount</th>
    </tr>
    <?php
    $totRcvQty=0;
    $totRcvAmt=0;
    ?>
     @foreach($receive as $row)
    <tr align="left">
    <td width="100px">{{$row->receive_date}} </td>
    <td width="100px">{{$row->receive_no}} </td>
    <td width="100px">{{$row->count_name}} </td>
    <td width="100px">{{$row->composition}} </td>
    <td width="100px">{{$row->yarn_type}} </td>

    <td width="130px">{{$row->yarn_color_name}} </td>
    <td width="100px" align="right">{{number_format($row->dyed_yarn_rcv_qty,2)}}</td>
    <td width="100px" align="right">{{number_format($row->dyed_yarn_rcv_rate,2)}}</td>
    <td width="100px" align="right">{{number_format($row->dyed_yarn_rcv_amount,2)}}</td>
    </tr>
     <?php
    $totRcvQty+=$row->dyed_yarn_rcv_qty;
    $totRcvAmt+=$row->dyed_yarn_rcv_amount;
    ?>
    @endforeach
    <tr align="left">
    <td colspan="6">Total</td>
    <td width="100px" align="right">{{number_format($totRcvQty,2)}}</td>
    <td width="100px" align="right"></td>
    <td width="100px" align="right">{{number_format($totRcvAmt,2)}}</td>
    </tr>

    <tr align="left">
    <td colspan="6">Dyed/Grey Yarn Receivable </td>
    <td width="100px" align="right">{{number_format(($totIssQty-$totRtnQty)-$totRcvQty,2)}}</td>
    <td width="100px" align="right"></td>
    <td width="100px" align="right">{{number_format(($totIssAmt-$totRtnAmt)-$totRcvAmt,2)}}</td>
    </tr>
    
</table>