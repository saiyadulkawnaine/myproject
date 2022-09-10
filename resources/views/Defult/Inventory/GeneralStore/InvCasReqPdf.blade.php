<h2 align="center">CASH REQUISITION</h2>
<h4 align="center">{{  $casreq['remarks'] }}</h4>
<table cellspacing="0" cellpadding="2">
    <tr>
        <th align="left"><strong>CR No&nbsp;:</strong>&nbsp;&nbsp;{{  $casreq['requisition_no'] }}</th>
        <th align="center">&nbsp;<strong>Date&nbsp;:</strong>&nbsp; {{  $casreq['req_date'] }}</th>
        <th align="center"><strong>Cash Need By&nbsp;:</strong>&nbsp; {{  $casreq['disburse_by'] }}</th>
    </tr>
    <tr>
        <th align="center"></th>
        <th align="center"></th>
        <th align="center"></th>
    </tr>
</table>
<table border="1" cellspacing="0" cellpadding="2">
    <?php
        $i=1;
        $total=0;
        $balance_total=0;
        //$paid_amount=0;
    ?>
    <thead>
        <tr>
            <td align="center"><strong>SL</strong></td>
            <td colspan="4" align="center"><strong>Item Description</strong></td>
            <td colspan="2" align="center"><strong>Quantity</strong></td>
            <td colspan="1" align="center"><strong>Unit</strong></td>
            <td colspan="2" align="center"><strong>Rate</strong></td>
            <td colspan="2" align="center"><strong>Amount</strong></td>
            <td colspan="3" align="center"><strong>Remarks</strong></td>
        </tr>
    </thead>
    <tbody>
    @foreach($casreqitem as $requision)
    <tr>
        <td>{{ $i }}</td>
            <td colspan="4">{{ $requision->item_description }}</td>
            <td colspan="2" align="right">{{ number_format($requision->item_qty,2) }}</td>
            <td colspan="1">{{ $requision->uom_code }}</td>
            <td colspan="2" align="right">{{ number_format($requision->item_rate,2)}}</td>
            <td colspan="2" align="right">{{ number_format($requision->item_amount,2) }}</td>
            <td colspan="3">{{ $requision->item_remarks }}</td>
    </tr>
    <?php
        $i++;
        $total+=$requision->item_amount;
       // $paid_amount+=$requision->paid_amount;
       // $balance_total=$total-$paid_amount;
       // $inword=$requision->inword;
    ?>
    @endforeach 
    </tbody>
</table>
<p></p>
<table border="0" cellspacing="0" cellpadding="2">
    <tbody>
        <tr>
            <td></td>
            <td colspan="4"></td>
            <td colspan="2" align="right"></td>
            <td colspan="1"></td>
            <td colspan="2" align="left"><strong>Total :</strong></td>
            <td colspan="2" align="right">{{ number_format($total,2) }}</td>
            <td colspan="3"></td>
        </tr>
        <tr>
            <td></td>
            <td colspan="4"></td>
            <td colspan="2" align="right"></td>
            <td colspan="1"></td>
            <td colspan="2" align="left"><strong>Amount Paid :</strong></td>
            <td colspan="2" align="right">{{ number_format($paid_amount,2) }}</td>
            <td colspan="3"></td>
        </tr>
        <?php
            $balance_total=$total-$paid_amount;
        ?>
        <tr>
            <td></td>
            <td colspan="4"></td>
            <td colspan="2" align="right"></td>
            <td colspan="1"></td>
            <td colspan="2" align="right"><strong>Balance To Pay:</strong></td>
            <td colspan="2" align="right">{{ number_format($balance_total,2) }}</td>
            <td colspan="3"></td>
        </tr>
        <tr>
            <td colspan="8">In Word( Taka ) : {{ $casreqitem->inword }} </td>
            <td colspan="3"></td>
            <td></td>
        </tr>
        <tr>
            <td colspan="8"></td>
            <td colspan="3"></td>
            <td></td>
        </tr>
    </tbody>
</table>
<table cellspacing="0" cellpadding="2">
    <tr>     
        <td colspan="2" align="left"><strong>Date:</strong></td>
        <td colspan="2" align="center"><strong>Amount</strong></td>
        <td colspan="2" align="center"><strong>Paid To:</strong></td>
        <td colspan="2" align="center"></td>
        <td colspan="3" align="left">Entry By</td>
        <td colspan="1" align="center">Entry Date</td>
        <td colspan="3" align="center"></td>
    </tr>
    @foreach ($casreqpaid as $item)
    <tr>  
        <td colspan="2" align="left">{{ $item->paid_date }}</td>
        <td colspan="2" align="center">{{ number_format($item->paid_amount,2) }}</td>
        <td colspan="2" align="center">{{ $item->user_name }}</td>
        <td colspan="2" align="center"></td>
        <td colspan="3" align="left">{{ $item->updatedby_user_name }}</td>
        <td colspan="1" align="center">{{ $item->entry_date }}</td>
        <td colspan="3" align="center"></td>
    </tr>
    
    @endforeach
</table>
<table border="0" cellspacing="0" cellpadding="2">
    <tr>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
    <tr>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
    <tr>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
    <tr>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
    <tr>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
    <tr>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
    <tr>
        <td><strong>Prepared By</strong></td>
        <td><strong>Demand By</strong></td>
        <td><strong>Price Verified By</strong></td>
        <td><strong>1st Approved</strong></td>
        <td><strong>2nd Approved</strong></td>
        <td><strong>3rd Approved</strong></td>
        <td><strong>Final Approved</strong></td>
    </tr>
    <tr>
        <td>
            {{ $casreq['user_name'] }}<br/>
            &nbsp;&nbsp;{{ $casreq['created_at'] }}</td>
        <td>
            <strong>{{ $casreq['demand_user_name'] }},{{ $casreq['dd_designation'] }}, {{ $casreq['demand_contact'] }} </strong>
        </td>
        <td>
            <strong>{{ $casreq['price_varify_user_name'] }},{{ $casreq['pv_designation'] }} ,{{ $casreq['price_varify_user_contact'] }}</strong>
        </td>
        <td><strong> </strong></td>
        <td><strong> </strong></td>
        <td><strong> </strong></td>
        <td><strong> </strong></td>
    </tr>
    <tr>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
    <tr>
        <td colspan="6"><strong>Edited By</strong>&nbsp;&nbsp;&nbsp;&nbsp;{{ $casreq['update_user_name'] }}&nbsp;&nbsp;&nbsp;{{ $casreq['updated_at'] }}</td>
        <td></td>
    </tr>
</table>