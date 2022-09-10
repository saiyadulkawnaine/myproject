<table border="1" style="border-style:dotted;line-height:25px;">
    <caption>Grey Yarn Issue to Inhouse </caption>
    <tr align="center">
    <th width="100px">Issue Date </th>
    <th width="100px">Issue No </th>
    <th width="100px">Count </th>
    <th width="100px">Yarn Desc. </th>
    <th width="100px">Type </th>

    <th width="130px">Yarn Color </th>
    <th width="130px">Kniting Company </th>
    <th width="100px" align="right">Qty</th>
    <th width="100px" align="right">Rate</th>
    <th width="100px" align="right">Amount</th>
    </tr>
    <?php
    $totIssQty=0;
    $totIssAmt=0;
    ?>
     @foreach($inisu as $row)
    <tr align="left">
    <td width="100px">{{$row->issue_date}} </td>
    <td width="100px">{{$row->issue_no}} </td>
    <td width="100px">{{$row->count_name}} </td>
    <td width="100px">{{$row->composition}} </td>
    <td width="100px">{{$row->yarn_type}} </td>
    <td width="100px">{{$row->yarn_color_name}} </td>

    <td width="130px">{{$row->issue_to_name}} </td>
    <td width="100px" align="right">{{number_format($row->qty,2)}}</td>
    <td width="100px" align="right">{{number_format($row->rate,2)}}</td>
    <td width="100px" align="right">{{number_format($row->amount,2)}}</td>
    </tr>
    <?php
    $totIssQty+=$row->qty;
    $totIssAmt+=$row->amount;
    ?>
    @endforeach
    <tr align="left">
    <td colspan="7">Total</td>
    <td width="100px" align="right">{{number_format($totIssQty,2)}}</td>
    <td width="100px" align="right"></td>
    <td width="100px" align="right">{{number_format($totIssAmt,2)}}</td>
    </tr>
</table>
<br/>

<table border="1" style="border-style:dotted;line-height:25px;">
    <caption>Grey Yarn Return From Inhouse </caption>
    <tr align="center">
    <th width="100px">Issue Date </th>
    <th width="100px">Issue No </th>
    <th width="100px">Count </th>
    <th width="100px">Yarn Desc. </th>
    <th width="100px">Type </th>

    <th width="130px">Yarn Color </th>
    <th width="130px">Kniting Company </th>
    <th width="100px" align="right">Qty</th>
    <th width="100px" align="right">Rate</th>
    <th width="100px" align="right">Amount</th>
    </tr>
    <?php
    $totIssRtnQty=0;
    $totIssRtnAmt=0;
    ?>
     @foreach($inisurtn as $row)
    <tr align="left">
    <td width="100px">{{$row->receive_date}} </td>
    <td width="100px">{{$row->receive_no}} </td>
    <td width="100px">{{$row->count_name}} </td>
    <td width="100px">{{$row->composition}} </td>
    <td width="100px">{{$row->yarn_type}} </td>
    <td width="100px">{{$row->yarn_color_name}} </td>

    <td width="130px">{{$row->kniting_company}} </td>
    <td width="100px" align="right">{{number_format($row->qty,2)}}</td>
    <td width="100px" align="right">{{number_format($row->rate,2)}}</td>
    <td width="100px" align="right">{{number_format($row->amount,2)}}</td>
    </tr>
    <?php
    $totIssRtnQty+=$row->qty;
    $totIssRtnAmt+=$row->amount;
    ?>
    @endforeach
    <tr align="left">
    <td colspan="7">Total</td>
    <td width="100px" align="right">{{number_format($totIssRtnQty,2)}}</td>
    <td width="100px" align="right"></td>
    <td width="100px" align="right">{{number_format($totIssRtnAmt,2)}}</td>
    </tr>
    <tr align="left" style="background-color: #ccc; font-weight: bold;">
    <td colspan="7">Total Net Issue To Inhouse</td>
    <td width="100px" align="right">{{number_format($totIssQty-$totIssRtnQty,2)}}</td>
    <td width="100px" align="right"></td>
    <td width="100px" align="right">{{number_format($totIssAmt-$totIssRtnAmt,2)}}</td>
    </tr>
</table>


<table border="1" style="border-style:dotted;line-height:25px;">
    <caption>Grey Yarn Issue to Subcontract </caption>
    <tr align="center">
    <th width="100px">Issue Date </th>
    <th width="100px">Issue No </th>
    <th width="100px">Count </th>
    <th width="100px">Yarn Desc. </th>
    <th width="100px">Type </th>

    <th width="130px">Yarn Color </th>
    <th width="130px">Kniting Company </th>
    <th width="100px" align="right">Qty</th>
    <th width="100px" align="right">Rate</th>
    <th width="100px" align="right">Amount</th>
    </tr>
    <?php
    $totIssSubQty=0;
    $totIssSubAmt=0;
    ?>
     @foreach($inisuout as $row)
    <tr align="left">
    <td width="100px">{{$row->issue_date}} </td>
    <td width="100px">{{$row->issue_no}} </td>
    <td width="100px">{{$row->count_name}} </td>
    <td width="100px">{{$row->composition}} </td>
    <td width="100px">{{$row->yarn_type}} </td>
    <td width="100px">{{$row->yarn_color_name}} </td>

    <td width="130px">{{$row->issue_to_name}} </td>
    <td width="100px" align="right">{{number_format($row->qty,2)}}</td>
    <td width="100px" align="right">{{number_format($row->rate,2)}}</td>
    <td width="100px" align="right">{{number_format($row->amount,2)}}</td>
    </tr>
    <?php
    $totIssSubQty+=$row->qty;
    $totIssSubAmt+=$row->amount;
    ?>
    @endforeach
    <tr align="left">
    <td colspan="7">Total</td>
    <td width="100px" align="right">{{number_format($totIssSubQty,2)}}</td>
    <td width="100px" align="right"></td>
    <td width="100px" align="right">{{number_format($totIssSubAmt,2)}}</td>
    </tr>
</table>
<br/>

<table border="1" style="border-style:dotted;line-height:25px;">
    <caption>Grey Yarn Return From Subcontract </caption>
    <tr align="center">
    <th width="100px">Issue Date </th>
    <th width="100px">Issue No </th>
    <th width="100px">Count </th>
    <th width="100px">Yarn Desc. </th>
    <th width="100px">Type </th>

    <th width="130px">Yarn Color </th>
    <th width="130px">Kniting Company </th>
    <th width="100px" align="right">Qty</th>
    <th width="100px" align="right">Rate</th>
    <th width="100px" align="right">Amount</th>
    </tr>
    <?php
    $totIssRtnSubQty=0;
    $totIssRtnSubAmt=0;
    ?>
     @foreach($inisuoutrtn as $row)
    <tr align="left">
    <td width="100px">{{$row->receive_date}} </td>
    <td width="100px">{{$row->receive_no}} </td>
    <td width="100px">{{$row->count_name}} </td>
    <td width="100px">{{$row->composition}} </td>
    <td width="100px">{{$row->yarn_type}} </td>
    <td width="100px">{{$row->yarn_color_name}} </td>

    <td width="130px">{{$row->kniting_company}} </td>
    <td width="100px" align="right">{{number_format($row->qty,2)}}</td>
    <td width="100px" align="right">{{number_format($row->rate,2)}}</td>
    <td width="100px" align="right">{{number_format($row->amount,2)}}</td>
    </tr>
    <?php
    $totIssRtnSubQty+=$row->qty;
    $totIssRtnSubAmt+=$row->amount;
    ?>
    @endforeach
    <tr align="left">
    <td colspan="7">Total</td>
    <td width="100px" align="right">{{number_format($totIssRtnSubQty,2)}}</td>
    <td width="100px" align="right"></td>
    <td width="100px" align="right">{{number_format($totIssRtnSubAmt,2)}}</td>
    </tr>
    <tr align="left" style="background-color: #ccc; font-weight: bold;">
    <td colspan="7">Total Net Issue To Subcontract</td>
    <td width="100px" align="right">{{number_format($totIssSubQty-$totIssRtnSubQty,2)}}</td>
    <td width="100px" align="right"></td>
    <td width="100px" align="right">{{number_format($totIssSubAmt-$totIssRtnSubAmt,2)}}</td>
    </tr>

    <tr align="left" style="background-color: #ccc; font-weight: bold;">
    <td colspan="7">Total Net Issue</td>
    <td width="100px" align="right">{{number_format(($totIssQty-$totIssRtnQty)+($totIssSubQty-$totIssRtnSubQty),2)}}</td>
    <td width="100px" align="right"></td>
    <td width="100px" align="right">{{number_format(($totIssAmt-$totIssRtnAmt)+($totIssSubAmt-$totIssRtnSubAmt),2)}}</td>
    </tr>
</table>

